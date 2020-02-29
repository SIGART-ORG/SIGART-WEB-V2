<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Referenceterm extends Model
{
    const TABLE_NAME = 'referenceterms';

    protected $table = self::TABLE_NAME;

    public function saleQuotation() {
        return $this->belongsTo( 'App\Models\SaleQuotation', 'sales_quotations_id', 'id' );
    }
}
