<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Service extends Model
{
    const TABLE_NAME = 'services';
    protected $table = self::TABLE_NAME;

    public function serviceRequest() {
        return $this->belongsTo( 'App\Models\ServiceRequest', 'service_requests_id', 'id' );
    }

    public function serviceStages() {
        return $this->hasMany( 'App\Models\ServiceStage', 'services_id', 'id' );
    }

    public function serviceAttachment() {
        return $this->hasMany( 'App\Models\ServiceAttachment', 'services_id', 'id' );
    }

    public static function setStatus( $id ) {
        $service = self::find( $id );
        $stages = ServiceStage::where( 'services_id',  $id )
            ->whereNotIn( 'status', [0,2] )
            ->groupBy( 'status' )
            ->select( 'status', DB::raw('count(*) as total'))
            ->pluck('total','status')->all();

        $status = 3;
        $statusArreglo = [
            1 => 0,
            3 => 0,
            4 => 0,
            5 => 0,
            6 => 0
        ];
        $total = 0;

        foreach ( $stages as $idx => $stage ) {
            $statusArreglo[$idx] = $stage;
            $total += $stage;
        }

        if( $total > 0 ) {
            if ($statusArreglo[1] === $total) {
                $status = 3;
            }

            if ($statusArreglo[6] === $total) {
                $status = 5;
            }

            if ($statusArreglo[3] > 0 || $statusArreglo[4] > 0 && $statusArreglo[5] > 0) {
                $status = 4;
            }
        }

        $service->status = $status;
        $service->save();

        return true;
    }
}
