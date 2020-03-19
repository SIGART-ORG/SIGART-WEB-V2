<?php

namespace App\Http\Controllers;

use App\Models\ServiceStage;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function approved( Request $request ) {

        $user = Auth::user();
        $customer = $user->customers_id;

        $response = [
            'status' => false,
            'msg' => 'No se pudo realizar la operación.'
        ];

        $taskID = $request->task ? $request->task : 0;
        $task = Task::find( $taskID );

        if( $task ) {
            $task->status = 6;
            $task->customers_id = $customer;
            $task->customers_login_id = $user->id;
            $task->date_validate_customer = date( 'Y-m-d H:i:s' );
            if( $task->save() ) {
                ServiceStage::setStateStage( $task-> service_stages_id );
            }
            $response['status'] = true;
            $response['msg'] = 'Ok.';
        }

        return response()->json( $response );
    }
}
