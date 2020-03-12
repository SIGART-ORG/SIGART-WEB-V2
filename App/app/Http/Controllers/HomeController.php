<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index() {
        $data = [
            'activeSide' => 'home'
        ];
        return view('classimax.pages.home', $data);
    }
}
