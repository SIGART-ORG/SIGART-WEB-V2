<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    const TABLE_NAME = 'brands';
    protected $table = self::TABLE_NAME;

    public function presentations() {
        return $this->hasMany( Presentation::class, 'brands_id', 'id' );
    }
}
