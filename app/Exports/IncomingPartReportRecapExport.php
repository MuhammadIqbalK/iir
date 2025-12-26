<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class IncomingPartReportRecapExport implements 
    WithHeadings, 
    WithMapping, 
    WithStyles,
    WithColumnWidths,
    WithTitle,
    WithEvents
{
    protected $filters;
    
    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }
    
    public function getData()
    {
        $where = [];
        $params = [];

        if (!empty($this->filters['itemnc'])) {
            $where[] = "a.itemnc ILIKE :itemnc";
            $params['itemnc'] = '%' . $this->filters['itemnc'] . '%';
        }

        if (!empty($this->filters['supplier'])) {
            $where[] = "c.supplier_code ILIKE :supplier";
            $params['supplier'] = '%' . $this->filters['supplier'] . '%';
        }

        if (!empty($this->filters['option'])) {
            $where[] = "a.option = :option";
            $params['option'] = $this->filters['option'];
        }

        if (!empty($this->filters['startdate']) && !empty($this->filters['enddate'])) {
            $where[] = "a.iirdate BETWEEN :startdate AND :enddate";
            $params['startdate'] = $this->filters['startdate'];
            $params['enddate'] = $this->filters['enddate'];
        }

        $wheresql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

        $sql = "
                SELECT
                    c.supplier_name AS nama_supplier,
                    SUM(CASE WHEN a.status = 'Y' THEN 1 ELSE 0 END) AS batch_diterima,
                    SUM(CASE WHEN a.status = 'N' THEN 1 ELSE 0 END) AS batch_ditolak
                FROM incoming_part_reports a
                JOIN suppliers c ON a.supplier_code = c.supplier_code
                {$wheresql}
                GROUP BY c.supplier_code, c.supplier_name
                ORDER BY c.supplier_name ASC;
        ";

        return DB::select($sql, $params);
    }
    
    public function headings(): array
    {
        return [
            'No',
            'Nama Supplier',
            'Batch Diterima',
            'Batch Ditolak'
        ];
    }
    
    public function map($report): array
    {
        static $rowNumber = 0;
        $rowNumber++;
        
        return [
            $rowNumber,
            $report->nama_supplier ?? '-',
            $report->batch_diterima ?? 0,
            $report->batch_ditolak ?? 0
        ];
    }
    
    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                    'size' => 11
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '28A745'] 
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
        ];
    }
    
    public function columnWidths(): array
    {
        return [
            'A' => 6,   
            'B' => 35,  
            'C' => 18, 
            'D' => 18, 
            // Tambahkan lebar untuk kolom Summary (Sebelah Kanan)
            'F' => 45, // Lebar kolom untuk teks summary
            'G' => 20, // Tambahan ruang jika perlu merge lebih lebar
        ];
    }
    
    public function title(): string
    {
        return 'Total batch diterima dan/ditolak oleh IQC';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet;
                
                // 1. Ambil Data
                $data = $this->getData();
                
                // 2. Hitung Grand Total
                $grandTotalY = 0;
                $grandTotalN = 0;

                foreach ($data as $row) {
                    $grandTotalY += ($row->batch_diterima ?? 0);
                    $grandTotalN += ($row->batch_ditolak ?? 0);
                }

                $totalBatch = $grandTotalY + $grandTotalN;
                $percentY = $totalBatch > 0 ? round(($grandTotalY / $totalBatch) * 100, 1) : 0;
                $percentN = $totalBatch > 0 ? round(($grandTotalN / $totalBatch) * 100, 1) : 0;

                // 3. LOGIKA TANGGAL (Filter vs Min/Max)
                // Cek apakah filter tanggal diisi
                if (!empty($this->filters['startdate']) && !empty($this->filters['enddate'])) {
                    // --- KASUS 1: Filter Ada ---
                    $start = date('d/m/Y', strtotime($this->filters['startdate']));
                    $end = date('d/m/Y', strtotime($this->filters['enddate']));
                    $dateRangeText = "Periode: $start s/d $end";

                } else {
                    // --- KASUS 2: Filter Kosong (Gunakan Query Min/Max) ---
                    
                    // Siapkan ulang where clause untuk query tanggal (tanpa filter tanggal)
                    $where = [];
                    $params = [];
                    if (!empty($this->filters['itemnc'])) {
                        $where[] = "itemnc ILIKE :itemnc";
                        $params['itemnc'] = '%' . $this->filters['itemnc'] . '%';
                    }
                    if (!empty($this->filters['supplier'])) {
                        $where[] = "supplier_code ILIKE :supplier";
                        $params['supplier'] = '%' . $this->filters['supplier'] . '%';
                    }
                    if (!empty($this->filters['option'])) {
                        $where[] = "`option` = :option";
                        $params['option'] = $this->filters['option'];
                    }
                    $wheresql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

                    $dateRangeText = "Periode: -";

                    // Query tambahan untuk ambil Min/Max
                    $dateSql = "SELECT MIN(iirdate) as min_date, MAX(iirdate) as max_date FROM incoming_part_reports {$wheresql}";
                    $dateResult = DB::select($dateSql, $params);

                    if ($dateResult && !empty($dateResult[0]->min_date)) {
                        // Masukkan ke array untuk menggunakan fungsi min/max (sesuai permintaan struktur kode)
                        $dates = [$dateResult[0]->min_date, $dateResult[0]->max_date];
                        
                        $minDate = min($dates);
                        $maxDate = max($dates);
                        
                        $start = date('d/m/Y', strtotime($minDate));
                        $end = date('d/m/Y', strtotime($maxDate));
                        
                        $dateRangeText = "Periode: $start s/d $end";
                    }
                }

                // 4. Tulis Data Utama (KOLOM KIRI: A s/d D)
                $rowNumber = 2;
                foreach ($data as $item) {
                    $sheet->setCellValue('A'.$rowNumber, $rowNumber - 1);
                    $sheet->setCellValue('B'.$rowNumber, $item->nama_supplier ?? '-');
                    $sheet->setCellValue('C'.$rowNumber, $item->batch_diterima ?? 0);
                    $sheet->setCellValue('D'.$rowNumber, $item->batch_ditolak ?? 0);
                    $rowNumber++;
                }
                
                // Tentukan batas bawah data utama
                $lastDataRow = $rowNumber - 1;

                // 5. Tulis Summary (KOLOM KANAN: Mulai dari F)
                // Kita mulai tulis summary di baris yang sama dengan baris 2 (data pertama)
                // agar posisinya bersebelahan (kiri-kanan), bukan di bawah.
                // Atau jika ingin summary berada di sisi kanan sejajar seluruh tabel, kita mulai dari baris 1 (header) s/d baris terakhir.
                
                $summaryStartRow = 2; // Mulai sejajar dengan data
                $summaryCol = 'F';    // Kolom F sebagai batas kiri summary
                
                $summaries = [
                    $dateRangeText,
                    "Total Batch Keseluruhan = $totalBatch",
                    "Total Batch Diterima Keseluruhan = $grandTotalY",
                    "Total Batch Ditolak Keseluruhan = $grandTotalN",
                    "% Batch Diterima Keseluruhan = " . $percentY . "%",
                    "% Batch Ditolak Keseluruhan = " . $percentN . "%"
                ];

                // Loop tulis summary bersebelahan kanan
                $currentSummaryRow = $summaryStartRow;
                foreach ($summaries as $text) {
                    $sheet->setCellValue($summaryCol.$currentSummaryRow, $text);
                    
                    // Style Summary
                    $sheet->getStyle($summaryCol.$currentSummaryRow)->applyFromArray([
                        'font' => ['bold' => true, 'size' => 10],
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => 'D9E1F2'] 
                        ],
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_LEFT,
                            'vertical' => Alignment::VERTICAL_CENTER,
                        ],
                        'borders' => [
                            'outline' => ['borderStyle' => Border::BORDER_THIN],
                            'vertical' => ['borderStyle' => Border::BORDER_THIN] // Border vertikal antar baris summary
                        ]
                    ]);
                    
                    $currentSummaryRow++;
                }

                // Opsional: Tambahkan border di sekeliling area summary agar rapi
                // Misal dari F2 sampai F(row terakhir summary)
                $lastSummaryRow = $currentSummaryRow - 1;
                $sheet->getStyle($summaryCol.$summaryStartRow.':'.$summaryCol.$lastSummaryRow)
                      ->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                      
            },
        ];
    }
}
