<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleQuotationDetail extends Model
{
    const TABLE_NAME = 'sales_quotations_details';
    protected $table = self::TABLE_NAME;

    public function saleQuotation() {
        return $this->belongsTo( SaleQuotation::class, 'sales_quotations_id', 'id' );
    }

    public function quotationDetailProducts() {
        return $this->hasMany( QuotationProductDetail::class, 'sales_quotations_details_id', 'id' );
    }
}
