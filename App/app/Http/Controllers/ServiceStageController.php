<?php

namespace App\Http\Controllers;

use App\Models\ServiceStage;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceStageController extends Controller
{
    public function approved( Request $request ) {

        $user = Auth::user();

        $response = [
            'status' => false,
            'msg' => 'No se pudo realizar la operación.'
        ];

        $stageID = $request->stage ? $request->stage : 0;
        $stage = ServiceStage::find( $stageID );

        if( $stage ) {
            Task::approvedAllTasksByStage( $stageID );
            ServiceStage::setStateStage( $stageID );
            $response['status'] = true;
            $response['msg'] = 'Ok.';
        }

        return response()->json( $response );
    }
}
