<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modificacoes extends Model
{
    use HasFactory;

    protected $table = 'modificacoes';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $fillable = ['nome_tabela', 'historico', 'user_id'];


}
