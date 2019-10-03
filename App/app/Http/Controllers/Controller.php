<?php

namespace App\Http\Controllers;

use App\Mail\SendMail;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function sendMail( $to, $subject, $template, $vars, $from = 'Automatic' ) {
        $dataMail            = new \stdClass();
        $dataMail->from      = $from;
        $dataMail->to        = $to;
        $dataMail->subject   = $subject;
        $dataMail->body      = '';
        $dataMail->vars      = $vars;

        \Mail::to( $to )->send( new SendMail( $dataMail, $template ) );
    }
}
