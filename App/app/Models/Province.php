<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    const TABLE_NAME = 'provinces';
    protected $table = self::TABLE_NAME;

    protected $fillable = [
        'name',
        'departament_id'
    ];
}
