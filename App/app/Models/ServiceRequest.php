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

    public function services() {
        return $this->hasMany('App\Models\Service', 'service_requests_id', 'id' );
    }

    public function saleQuotations() {
        return $this->hasMany( 'App\Models\SaleQuotation', 'service_requests_id', 'id' );
    }

    public function listData( $numPerPage ) {

        $user = Auth::user();

        $data = DB::table( self::TABLE_NAME )
            ->whereIn( self::TABLE_NAME . '.status', [1, 3] )
            ->where( self::TABLE_NAME . '.customers_id', $user->customers_id )
            ->whereIn( self::TABLE_NAME . '.is_send', [0, 1, 2])
            ->orderBy( self::TABLE_NAME . '.created_at', 'desc')
            ->paginate( $numPerPage );

        return $data;
    }

    public function countData() {
        $user = Auth::user();

        $data = DB::table( self::TABLE_NAME )
            ->whereIn( self::TABLE_NAME . '.status', [1, 3] )
            ->whereIn( self::TABLE_NAME . '.is_send', [0, 1, 2])
            ->where( self::TABLE_NAME . '.customers_id', $user->customers_id )
            ->count();

        return $data;
    }

    public function countQuotesToApprove() {

        $user = Auth::user();
        $customerId = $user->customers_id;

        $data = SaleQuotation::where( 'status', 6 )
            ->where( 'customers_id', $customerId )
            ->count();

        return $data;
    }

    public function scopeIsSend( $query, $force ) {
        if( empty( $force ) ){
            return $query->whereIn( self::TABLE_NAME . '.is_send', [0,2] )
                ->where( self::TABLE_NAME . '.status', '!=', 2 );
        } else {
            return $query->where( self::TABLE_NAME . '.status', '!=', 2 );
        }
    }
}
