<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    const TABLE_NAME = 'categories';
    protected $table = self::TABLE_NAME;

    public function products() {
        return $this->hasMany( Product::class, 'category_id', 'id' );
    }
}
