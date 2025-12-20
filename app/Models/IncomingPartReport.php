<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class IncomingPartReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'iirdate',
        'itemnc',
        'nodoc',
        'quantity',
        'samplesize',
        'gilevel',
        'examiner_id',
        'start',
        'end',
        'duration',
        'supplier_code',
        'option',
    ];

    public static function getAll($page = 1, $perPage = 10, $search = [])
    {
        $offset = ($page - 1) * $perPage;

        $where = [];
        $params = [];

        if (!empty($search['itemnc'])) {
            $where[] = "a.itemnc ILIKE :itemnc";
            $params['itemnc'] = '%' . $search['itemnc'] . '%';
        }

        if (!empty($search['supplier'])) {
            $where[] = "c.supplier_code ILIKE :supplier";
            $params['supplier'] = '%' . $search['supplier'] . '%';
        }

        if (!empty($search['option'])) {
            $where[] = "a.option ILIKE :option";
            $params['option'] = '%' . $search['option'] . '%';
        }
        if (!empty($search['startdate']) && !empty($search['enddate'])) {
            $where[] = "a.iirdate BETWEEN :startdate AND :enddate";
            $params['startdate'] = $search['startdate'];
            $params['enddate'] = $search['enddate'];
        }

        $wheresql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

        // Count Query
        $countSql = "
            SELECT COUNT(*) as total
            FROM incoming_part_reports a 
            JOIN examiners b ON a.examiner_id = b.id
            JOIN suppliers c ON a.supplier_code = c.supplier_code
            JOIN itemncs d ON a.itemnc = d.item12nc
            $wheresql
        ";

        // Execute count query with same params (excluding limit/offset)
        $totalResult = DB::select($countSql, $params);
        $total = $totalResult[0]->total ?? 0;

        // Data Query
        $sql = "
            SELECT 
                a.id,
                a.iirdate, 
                a.itemnc, 
                d.partname, 
                a.nodoc,
                a.quantity,
                a.samplesize, 
                a.gilevel,
                b.name,
                a.examiner_id,
                a.start, 
                a.end, 
                a.duration,
                c.supplier_name,
                a.supplier_code,
                a.option
            FROM incoming_part_reports a 
            JOIN examiners b ON a.examiner_id = b.id
            JOIN suppliers c ON a.supplier_code = c.supplier_code
            JOIN itemncs d ON a.itemnc = d.item12nc
            $wheresql
            ORDER BY a.iirdate DESC
            LIMIT :limit OFFSET :offset
        ";

        // Add limit/offset to params
        $params['limit'] = (int) $perPage;
        $params['offset'] = (int) $offset;

        $data = DB::select($sql, $params);

        return [
            'data' => $data,
            'total' => $total
        ];
    }

    public static function getById($id)
    {
        $sql = "
        SELECT 
            a.id,
            a.iirdate, 
            a.itemnc, 
            d.partname, 
            a.nodoc,
            a.quantity,
            a.samplesize, 
            a.gilevel,
            b.name,
            a.examiner_id,
            a.start, 
            a.end, 
            a.duration,
            c.supplier_code,
            c.supplier_name,
            a.option
        FROM incoming_part_reports a 
        JOIN examiners b ON a.examiner_id = b.id
        JOIN suppliers c ON a.supplier_code = c.supplier_code
        JOIN itemncs d ON a.itemnc = d.item12nc
        where a.id = :id
    ";

        $params = ['id' => (int) $id];

        return DB::select($sql, $params);
    }
}
