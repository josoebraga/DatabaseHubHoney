<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NaoPerturbe extends Model
{
    use HasFactory;

    protected $table = 'nao_perturbe';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $fillable = [
        'ORIGEM',
        'TELEFONE',
        'DATA_BLOQUEIO',
        'DATA_ARQUIVO',
        'EMPRESA_BLOQUEADA',
        'ORIGEM_DO_PEDIDO',
        'DATA_PROCESSAMENTO'
    ];


}
