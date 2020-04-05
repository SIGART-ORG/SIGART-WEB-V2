<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class ServiceStage extends Model
{
    const TABLE_NAME = 'service_stages';
    protected $table = self::TABLE_NAME;

    public function service() {
        return $this->belongsTo( 'App\Models\Service', 'services_id', 'id' );
    }

    public function tasks() {
        return $this->hasMany( 'App\Models\Task', 'service_stages_id', 'id' );
    }

    public function observeds() {
        return $this->hasMany( 'App\Models\StageObserved', 'service_stages_id', 'id' );
    }

    public static function setStateStage( $id ) {
        $stage = self::find( $id );

        $tasks = Task::where( 'service_stages_id', $id )
            ->whereNotIn( 'status', [0,2] )
            ->groupBy( 'status' )
            ->select( 'status', DB::raw('count(*) as total'))
            ->pluck('total','status')->all();

        $status = 1;
        $total = 0;
        $statusArreglo = [
            1 => 0,
            3 => 0,
            4 => 0,
            5 => 0,
            6 => 0
        ];

        if( $total > 0 ) {
            if ($statusArreglo[1] === $total) {
                $status = 1;
            }

            if ($statusArreglo[4] === $total) {
                $status = 4;
            }

            if ($statusArreglo[6] === $total) {
                $status = 6;
            }

            if ($statusArreglo[3] > 0) {
                $status = 3;
            }

            if ($statusArreglo[5] > 0) {
                $status = 5;
            }
        }
        $countObs = $stage->observeds->where( 'status', 1 )->count();
        if( $countObs > 0 ) {
            $status = 5;
        }

        StageStatusDate::generateStatus( $id, $stage->status, $status );
        $stage->status = $status;
        $stage->save();

        return true;
    }
}
