<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Tabelas extends Model
{
    protected $table = 'information_schema.tables';
    protected $primaryKey = 'table_name';
    public $timestamps = true;

        public static function tabelas()
        {
            return DB::select("
                select table_name
                from information_schema.tables
                where table_catalog = 'honey' and
                table_schema = 'public' and
                table_type = 'BASE TABLE' and
                table_name not in (
                'password_resets',
                'failed_jobs',
                'personal_access_tokens',
                'jobs',
                'teste',
                'modificacoes',
                'migrations',
                'users',
                'permissions',
                'model_has_permissions',
                'model_has_roles',
                'roles',
                'role_has_permissions',
                'users_type'
                )
            ");
    }
}
