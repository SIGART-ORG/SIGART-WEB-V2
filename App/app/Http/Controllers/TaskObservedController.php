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

        return response()->json( $response );
    }


}
