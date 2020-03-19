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

    public static function setStateStage( $id ) {
        $stage = self::find( $id );

        $tasks = Task::where( 'service_stages_id', $id )
            ->whereNotIn( 'status', [0,2] )
            ->groupBy( 'status' )
            ->select( 'status', DB::raw('count(*) as total'))
            ->pluck('total','status')->all();

        $status = 1;
        $statusArreglo = [
            1 => 0,
            3 => 0,
            4 => 0,
            5 => 0,
            6 => 0
        ];

        foreach ( $tasks as $idx => $task ) {
            $statusArreglo[$idx] = $task;
        }

        if( $statusArreglo[3] > 0 || $statusArreglo[4] > 0 || $statusArreglo[5] > 0 ) {
            $status = 3;
        }

        if( $statusArreglo[6] > 0 && ( $statusArreglo[3] === 0 && $statusArreglo[4] === 0 && $statusArreglo[5] === 0 ) ) {
            $status = 4;
        }

        $stage->status = $status;
        $stage->save();

        return true;
    }
}
