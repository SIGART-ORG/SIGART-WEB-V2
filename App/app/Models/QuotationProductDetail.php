<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuotationProductDetail extends Model
{
    const TABLE_NAME = 'quotation_products_details';
    protected $table = self::TABLE_NAME;

    public function saleQuotationDetail() {
        return $this->belongsTo( SaleQuotationDetail::class, 'sales_quotations_details_id', 'id' );
    }

    public function presentation() {
        return $this->belongsTo( Presentation::class, 'presentation_id', 'id' );
    }
}
