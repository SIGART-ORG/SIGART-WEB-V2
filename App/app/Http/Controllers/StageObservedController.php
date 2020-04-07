<?php

namespace App\Http\Controllers;

use App\Models\ServiceStage;
use App\Models\StageObserved;
use App\Models\StageStatusDate;
use App\Models\Task;
use Illuminate\Http\Request;

class StageObservedController extends Controller
{
    public function listObservations( Request $request ) {
        $response = [
            'status' => true,
            'msg' => 'OK.',
            'observations' => []
        ];

        $stageID = $request->stage ? $request->stage : 0;
        $dataObervations = StageObserved::whereNotIn( 'status', [0,2] )
            ->where( 'service_stages_id', $stageID )
            ->orderBy( 'created_at', 'desc' )
            ->get();

        foreach( $dataObervations as $dataObervation ) {
            $row = new \stdClass();
            $row->id = $dataObervation->id;
            $row->created = $this->getDateComplete( $dataObervation->created_at );
            $row->name = $dataObervation->name;
            $row->description = $dataObervation->description;
            $row->reply = $dataObervation->reply;
            $row->replyDate = $this->getDateComplete( $dataObervation->date_reply );
            $row->replyLong = false;
            $row->status = $dataObervation->status;
            $row->statusName = $this->getStatus( 'observation', $dataObervation->status );
            $row->badge = $this->getClassBadge( $dataObervation->status );
            $row->isValidate = $dataObervation->is_validate_reply === 1 ? true : false;
            $response['observations'][] = $row;
        }
        return response()->json( $response );
    }

    public function store( Request $request ) {
        $response = [
            'status' => false,
            'msg' => 'No se pudo realizar la operación.'
        ];

        $stageID = $request->stage ? $request->stage : 0;
        $description = $request->observation ? $request->observation : 0;

        $stage = ServiceStage::find( $stageID );
        if( $stage ) {
            if( $stage->count_observerds < self::MAX_OBS_FOR_TASK ) {
                $observation = new StageObserved();
                $observation->service_stages_id = $stageID;
                $observation->name = $this->generateName( $stageID );
                $observation->description = $description;
                if ($observation->save()) {
                    $newStatus = 5;
                    StageStatusDate::generateStatus( $stageID, $stage->status, $newStatus );
                    $stage->status = $newStatus;
                    $stage->count_observerds += 1;
                    $stage->date_last_observed = date('Y-m-d');
                    if ($stage->save()) {
                        $response['status'] = true;
                        $response['msg'] = 'OK.';
                    }
                }
            } else {
                $response['msg'] = 'Se superó el número máximo(' . self::MAX_OBS_FOR_TASK . ') de observaciones por tarea.';
            }
        }

        return response()->json( $response );
    }

    private function generateName( $stage ) {
        $count = StageObserved::where( 'service_stages_id', $stage )->count();
        return 'OBS-' . $stage . '-' . ( $count + 1 );
    }

    public function approvedReply( Request $request ) {
        $response = [
            'status' => false,
            'msg' => 'No se puede realizar la operación.'
        ];

        $id = $request->id ? $request->id : 0;
        $observation = StageObserved::find( $id );
        if(  $observation && $observation->status === 4 && $observation->is_validate_reply === 0 ) {
            $observation->is_validate_reply = 1;
            $observation->validate_date = date( 'Y-m-d H:i:s' );
            if( $observation->save() ) {

                Task::approvedAllTasksByStage( $observation->service_stages_id );
                ServiceStage::setStateStage( $observation->service_stages_id );

                $response['status'] = true;
                $response['msg'] = 'OK.';
            }
        }

        return response()->json( $response, 200 );
    }
}
