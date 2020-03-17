<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskObserved;
use Illuminate\Http\Request;

class TaskObservedController extends Controller
{
    public function store( Request $request ) {
        $response = [
            'status' => false,
            'msg' => 'No se pudo realizar la operación.'
        ];

        $taskID = $request->task ? $request->task : 0;
        $description = $request->observation ? $request->observation : 0;

        $task = Task::find( $taskID );
        if( $task ) {
            $observation = new TaskObserved();
            $observation->tasks_id =  $taskID;
            $observation->name =  $this->generateName( $taskID );
            $observation->description = $description;
            if( $observation->save() ) {
                $task->status = 5;
                $task->date_observed = date( 'Y-m-d H:i:s' );
                if( $task->save() ) {
                    $response['status'] = true;
                    $response['msg'] = 'OK.';
                }
            }
        }

        return response()->json( $response );
    }

    private function generateName( $task ) {
        $count = TaskObserved::where( 'tasks_id', $task )->count();
        return 'OBS-' . $task . '-' . ( $count + 1 );
    }
}
