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

    const PATH_IMAGES = 'images/theme/';

    protected $title = '';
    protected $logoSocial = 'logo-social.png';
    protected $metaTags = [
        'description' => '',
        'robots' => 'index, follow',
        'viewport' => 'width=device-width, initial-scale=1.0'
    ];
    protected $metaTagSocials = [
        'facebook' => [
            ['property' => 'og:type', 'content' => 'website'],
            ['property' => 'og:url', 'content' => ''],
            ['property' => 'og:title', 'content' => ''],
            ['property' => 'og:description', 'content' => ''],
            ['property' => 'og:image', 'content' => ''],
        ],
        'twitter' => [
            ['property' => 'twitter:card', 'content' => 'summary_large_image'],
            ['property' => 'twitter:url', 'content' => ''],
            ['property' => 'twitter:title', 'content' => ''],
            ['property' => 'twitter:description', 'content' => ''],
            ['property' => 'twitter:image', 'content' => ''],
        ]
    ];

    protected function setTitle( $title ) {
        $this->title = $title ? $title . ' - ' : '';
    }

    protected function setMetaTags( $metaTag ) {
        if( $metaTag && is_array( $metaTag ) ) {
            $this->metaTags[$metaTag['key']] = $metaTag['value'];
        }
    }

    protected function setMetaTagSocial( $metaTagSocial ) {
        foreach ( $this->metaTagSocials as $idxMts => $mts ) {
            foreach ( $mts as $idxSocial => $social ) {
                if ($metaTagSocial && is_array($metaTagSocial)) {
                    foreach ($metaTagSocial as $mts2) {
                        if( $this->metaTagSocials[$idxMts][$idxSocial]['property'] === $mts2['property'] ) {
                            $this->metaTagSocials[$idxMts][$idxSocial]['content'] = $mts2['content'];
                        }
                    }
                }
            }
        }
    }

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
