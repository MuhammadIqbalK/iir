<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Itemnc extends Model
{
    use HasFactory;

    protected $fillable = [
        'item12nc',
        'partname',
        'type',
        'quantity',
        'unit',
    ];
}
