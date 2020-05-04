<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Support\Facades\Auth;

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

    public function statusdate() {
        return $this->hasMany( 'App\Models\StageStatusDate', 'service_stages_id', 'id' );
    }

    public static function setStateStage( $id ) {
        $stage = self::find( $id );
        if( $stage ) {
            $user = Auth::user();
            $tasks = Task::where('service_stages_id', $id)
                ->whereNotIn('status', [0, 2])
                ->groupBy('status')
                ->select('status', DB::raw('count(*) as total'))
                ->pluck('total', 'status')->all();

            $status = 1;
            $total = 0;
            $statusArreglo = [
                1 => 0,
                3 => 0,
                4 => 0,
                5 => 0,
                6 => 0
            ];

            foreach ($tasks as $idx => $task) {
                $statusArreglo[$idx] = $task;
                $total += $task;
            }

            if ($total > 0) {
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

            $countObs = $stage->observeds->where('status', 1)->count();
            if ($countObs > 0) {
                $status = 5;
            }

            StageStatusDate::generateStatus($id, $stage->status, $status);
            $stage->status = $status;
            if( $status === 6 ) {
                $stage->approved_type = 1;
                $stage->approved_customer_login_id = $user->id;
                $stage->approved_date = date( 'Y-m-d H:i:s' );
                StageStatusDate::registerStatus( $id, $status, 3, 'Ultimo movimiento de tarjeta.' );
            }
            if( $stage->save() ) {
                Service::setStatus( $stage->services_id );
            }

            return true;
        } else {
            return false;
        }
    }
}
