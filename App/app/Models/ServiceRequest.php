<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Support\Facades\Auth;

class ServiceRequest extends Model
{
    const TABLE_NAME = 'service_requests';

    protected $table = self::TABLE_NAME;
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

    public function listData( $numPerPage, $status ) {

        $user = Auth::user();

        $data = DB::table( self::TABLE_NAME )
            ->where( self::TABLE_NAME . '.status', $status )
            ->where( self::TABLE_NAME . '.customers_id', $user->customers_id )
            ->orderBy( self::TABLE_NAME . '.created_at', 'desc')
            ->paginate( $numPerPage );

        return $data;
    }

    public function countData( $status ) {
        $user = Auth::user();

        $data = DB::table( self::TABLE_NAME )
            ->where( self::TABLE_NAME . '.status', $status )
            ->where( self::TABLE_NAME . '.customers_id', $user->customers_id )
            ->count();

        return $data;
    }
}
