<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncomingPartReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'iirdate',
        'itemnc',
        'partname',
        'nodoc',
        'quantity',
        'samplesize',
        'gilevel',
        'examiner',
        'start',
        'end',
        'duration',
        'supplier',
        'option',
    ];
}
