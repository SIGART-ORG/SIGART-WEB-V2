<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContactUsController extends Controller
{
    public function dashboard() {
        $data = [
            'activeSide' => 'contact-us'
        ];

        return view('classimax.pages.contact-us', $data);
    }
}
