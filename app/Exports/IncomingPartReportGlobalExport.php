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

class IncomingPartReportGlobalExport implements 
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
            $where[] = 'TRIM(a."option") = :option';
            $params['option'] = trim($this->filters['option']);
        }

        // Logika filter tanggal tetap ada di query, TAPI tidak akan dipakai untuk teks Summary
        if (!empty($this->filters['startdate']) && !empty($this->filters['enddate'])) {
            $where[] = "a.iirdate BETWEEN :startdate AND :enddate";
            $params['startdate'] = $this->filters['startdate'];
            $params['enddate'] = $this->filters['enddate'];
        }

        $wheresql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

        
        $sql = "
        SELECT
        c.supplier_name as nama_supplier,
        a.itemnc as item12nc,
        d.partname,
        a.iirdate as tglmasuk,
        a.nodoc as type,
        a.batch as batch_ke,
        a.quantity as jumlah,
        a.status as status 
        FROM incoming_part_reports a
        JOIN examiners b ON a.examiner_id = b.id
        JOIN suppliers c ON a.supplier_code = c.supplier_code
        JOIN itemncs d ON a.itemnc = d.item12nc
        $wheresql
        ORDER BY c.supplier_name ASC, a.iirdate ASC
        ";
        
        $result = DB::select($sql, $params);

        return $result;
        
    }
    
    public function headings(): array
    {
        return [
            'No',
            'Nama Supplier',
            'Item 12NC',
            'Part Name',
            'Tanggal Masuk',
            'Type',
            'Batch Ke',
            'Jumlah',
            'Diterima/Ditolak'
        ];
    }
    
    public function map($report): array
    {
        static $rowNumber = 0;
        $rowNumber++;
        
        return [
            $rowNumber,
            $report->nama_supplier ?? '-',
            $report->item12nc ?? '-',
            $report->partname ?? '-',
            $report->tglmasuk ? date('d/m/Y', strtotime($report->tglmasuk)) : '-',
            $report->type ?? '-',
            $report->batch_ke ?? '-',
            number_format($report->jumlah ?? 0, 0, ',', '.'),
            strtoupper($report->status ?? 'N') 
        ];
    }
    
    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '2E75B6']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            ],
        ];
    }
    
    public function columnWidths(): array
    {
        return [
            'A' => 6, 'B' => 30, 'C' => 18, 'D' => 35, 'E' => 15, 'F' => 15, 'G' => 12, 'H' => 12, 'I' => 18,
        ];
    }
    
    public function title(): string
    {
        return 'Laporan total Batch';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet;
                
                // 1. Ambil Data
                $data = $this->getData();
                
                // 2. Hitung Statistik
                $totalY = 0;
                $totalN = 0;

                foreach ($data as $row) {
                    $status = strtoupper($row->status ?? 'N');
                    if ($status === 'Y') {
                        $totalY++;
                    } else {
                        $totalN++;
                    }
                }

                $totalBatch = $totalY + $totalN;
                $percentY = $totalBatch > 0 ? round(($totalY / $totalBatch) * 100, 1) : 0;
                $percentN = $totalBatch > 0 ? round(($totalN / $totalBatch) * 100, 1) : 0;

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

                // 4. Tulis Data Utama
                $rowNumber = 2;
                foreach ($data as $item) {
                    $sheet->setCellValue('A'.$rowNumber, $rowNumber - 1);
                    $sheet->setCellValue('B'.$rowNumber, $item->nama_supplier ?? '-');
                    $sheet->setCellValue('C'.$rowNumber, $item->item12nc ?? '-');
                    $sheet->setCellValue('D'.$rowNumber, $item->partname ?? '-');
                    $sheet->setCellValue('E'.$rowNumber, $item->tglmasuk ? date('d/m/Y', strtotime($item->tglmasuk)) : '-');
                    $sheet->setCellValue('F'.$rowNumber, $item->type ?? '-');
                    $sheet->setCellValue('G'.$rowNumber, $item->batch_ke ?? '-');
                    $sheet->setCellValue('H'.$rowNumber, number_format($item->jumlah ?? 0, 0, ',', '.'));
                    $sheet->setCellValue('I'.$rowNumber, strtoupper($item->status ?? 'N'));
                    $rowNumber++;
                }

                // 5. Susun Array Summary
                $summaries = [
                    $dateRangeText, // Rentang Tanggal dari data aktual
                    "Total Batch Keseluruhan = $totalBatch",
                    "Total Batch Diterima Keseluruhan = $totalY",
                    "Total Batch Ditolak Keseluruhan = $totalN",
                    "% Batch Diterima Keseluruhan = " . $percentY . "%",
                    "% Batch Ditolak Keseluruhan = " . $percentN . "%"
                ];

                $summaryStartRow = $rowNumber + 1;

                // 6. Tulis Summary ke Excel
                foreach ($summaries as $index => $text) {
                    $currentRow = $summaryStartRow + $index;
                    
                    // Merge cell agar teks panjang muat
                    $sheet->mergeCells('B'.$currentRow.':C'.$currentRow);
                    $sheet->setCellValue('B'.$currentRow, $text);
                    
                    // Style untuk Summary
                    $sheet->getStyle('B'.$currentRow)->applyFromArray([
                        'font' => ['bold' => true, 'size' => 11],
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
                            'inside' => ['borderStyle' => Border::BORDER_THIN] 
                        ]
                    ]);
                }
            },
        ];
    }
}
