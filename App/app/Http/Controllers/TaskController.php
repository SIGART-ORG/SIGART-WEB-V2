<?php

namespace App\Http\Controllers;

use App\Models\ServiceStage;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function approved() {

        $response = [
            'status' => false,
            'msg' => 'No se pudo realizar la operación.'
        ];

        return response()->json( $response );
    }
}
