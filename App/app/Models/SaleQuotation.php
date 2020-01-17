<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Support\Facades\Auth;

class SaleQuotation extends Model
{
    const TABLE_NAME = 'sales_quotations';

    protected $table = self::TABLE_NAME;

    private $user = null;

    public function __construct()
    {
        parent::__construct();
        $this->user = Auth::user();
    }

    public function serviceRequest() {
        return $this->belongsTo( 'App\Models\ServiceRequest', 'service_requests_id', 'id' );
    }

    public function countSalesQuotationApproved() {

        $user = Auth::user();
        $customerId = $user->customers_id;

        return self::where( 'status', 8 )
            ->where( 'customers_id', $customerId )
            ->count();
    }

    public function countArvhived() {
        $user = Auth::user();
        $customerId = $user->customers_id;

        return self::where( 'status', 9 )
            ->where( 'customers_id', $customerId )
            ->count();
    }
}
