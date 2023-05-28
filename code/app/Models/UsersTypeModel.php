<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsersTypeModel extends Model
{
    use HasFactory;
    protected $table = 'users_type';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $fillable = ['type'];


}
