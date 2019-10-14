<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Support\Facades\Auth;

class ServiceRequest extends Model
{
    const TABLE_NAME = 'service_requests';

    private $user = null;

    protected $table = self::TABLE_NAME;

    public function __construct()
    {
        parent::__construct();
        $this->user = Auth::user();
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sites_id',
        'customers_id',
        'user_reg',
        'user_aproved',
        'date_reg',
        'date_aproved',
        'description',
        'observation',
        'status'
    ];

    public function listData( $numPerPage ) {

        $user = Auth::user();

        $data = DB::table( self::TABLE_NAME )
            ->where( self::TABLE_NAME . '.status', 1 )
            ->where( self::TABLE_NAME . '.customers_id', $user->customers_id )
            ->whereIn( self::TABLE_NAME . '.is_send', [0, 1, 2])
            ->orderBy( self::TABLE_NAME . '.created_at', 'desc')
            ->paginate( $numPerPage );

        return $data;
    }

    public function countData() {
        $user = Auth::user();

        $data = DB::table( self::TABLE_NAME )
            ->where( self::TABLE_NAME . '.status', 1 )
            ->whereIn( self::TABLE_NAME . '.is_send', [0, 1, 2])
            ->where( self::TABLE_NAME . '.customers_id', $user->customers_id )
            ->count();

        return $data;
    }

    public function countQuotesToApprove() {

        $SaleQuotation = new SaleQuotation();

        $data = DB::table( self::TABLE_NAME )
            ->join( $SaleQuotation::TABLE_NAME, $SaleQuotation::TABLE_NAME . '.service_requests_id', '=', self::TABLE_NAME . '.id' )
            ->where( self::TABLE_NAME . '.status', 1 )
            ->where( self::TABLE_NAME . '.is_send', 1 )
            ->where( $SaleQuotation::TABLE_NAME . '.status', 1 )
            ->where( $SaleQuotation::TABLE_NAME . '.is_approved_customer', 0 )
            ->where( $SaleQuotation::TABLE_NAME . '.customer_login_id', 0 )
            ->count();

        return $data;
    }
}
