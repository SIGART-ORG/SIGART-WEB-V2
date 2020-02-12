<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function termsAndConditions() {
        $data = [
            'activeSide' => 'tyc'
        ];
        return view('classimax.pages.tyc', $data);
    }

    public function aboutUs() {
        $data = [
            'activeSide' => 'about-us'
        ];
        return view('classimax.pages.about-us', $data);
    }
}
