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

    public function countSalesQuotationApproved() {

        return DB::table( self::TABLE_NAME )
            ->where( self::TABLE_NAME . '.status', 1 )
            ->where( self::TABLE_NAME . '.customers_id', $this->user->customers_id )
            ->where( self::TABLE_NAME . '.is_approved_customer', 1 )
            ->where( self::TABLE_NAME . '.customer_login_id', '>', 0 )
            ->count();
    }

    public function countArvhived() {
        return DB::table( self::TABLE_NAME )
            ->where( self::TABLE_NAME . '.status', 2 )
            ->where( self::TABLE_NAME . '.customers_id', $this->user->customers_id )
            ->count();
    }
}
