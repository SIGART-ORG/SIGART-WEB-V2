<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    const TABLE_NAME = 'sales';
    protected $table = self::TABLE_NAME;

    public function service() {
        return $this->belongsTo( Service::class, 'services_id', 'id' );
    }
}
