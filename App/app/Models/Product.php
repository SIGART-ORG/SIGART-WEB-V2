<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    const TABLE_NAME = 'products';
    protected $table = self::TABLE_NAME;

    public function presentations() {
        return $this->hasMany( Presentation::class, 'products_id', 'id' );
    }

    public function category() {
        return $this->belongsTo( Category::class, 'category_id', 'id' );
    }
}
