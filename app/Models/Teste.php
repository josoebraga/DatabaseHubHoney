<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teste extends Model
{
    use HasFactory;

    protected $table = 'teste';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $fillable = [
        'coluna_1',
        'coluna_2',
        'coluna_3',
        'coluna_4',
        'coluna_5',
        'coluna_6',
        'coluna_8',
        'coluna_7'
    ];


}
