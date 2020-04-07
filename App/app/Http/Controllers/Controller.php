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
    const PATH_VOUCHER = 'uploads/voucher/';
    const FORMAT_DATE = 'd/m/Y';
    const FORMAT_DATE_COMPLETE = 'd/m/Y h:i a';

    const MAX_OBS_FOR_TASK = 3;

    protected $title = '';
    protected $description = 'Somos una empresa altamente competitiva en cuanto a diseño, fabricación y servicios de carpintería y ebanistería. Ofrecer excelente calidad en productos de línea clásica, innovadora y de arte sacro a nivel comercial, residencial e institucional.';
    protected $logoSocial = 'logo-social.png';
    protected $metaTags = [
        'description' => '',
        'robots' => 'index, follow',
        'viewport' => 'width=device-width, initial-scale=1.0'
    ];
    protected $metaTagSocials = [
        'facebook' => [
            ['property' => 'fb:app_id', 'content' => ''],
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
        try {
            $dataMail            = new \stdClass();
            $dataMail->from      = $from;
            $dataMail->to        = $to;
            $dataMail->subject   = $subject;
            $dataMail->body      = '';
            $dataMail->vars      = $vars;

            \Mail::to( $to )->send( new SendMail( $dataMail, $template ) );
        } catch ( \Exception $e ) {
            return $e;
        }
    }

    protected function getDate( $date ) {
        return $date ? date( self::FORMAT_DATE, strtotime( $date ) ) : '---';
    }

    protected function getDateComplete( $date ) {
        return $date ? date( self::FORMAT_DATE_COMPLETE, strtotime( $date ) ) : '---';
    }

    protected function getStatus( $type, $status ) {
        $statusName = '';

        switch ( $type ) {
            case 'service':
                switch ( $status ) {
                    case 0:
                        $statusName = 'Desactivado';
                        break;
                    case 1:
                        $statusName = 'Generado';
                        break;
                    case 2:
                        $statusName = 'Eliminado';
                        break;
                    case 3:
                        $statusName = 'Por Iniciar';
                        break;
                    case 4:
                        $statusName = 'En proceso';
                        break;
                    case 5:
                        $statusName = 'Terminado';
                        break;
                    case 6:
                        $statusName = 'Finalizado';
                        break;
                    case 7:
                        $statusName = 'Pagado';
                        break;
                }
                break;
            case 'purchase':
                switch ( $status ) {
                    case 0:
                        $statusName = 'Desactivado';
                        break;
                    case 1:
                        $statusName = 'Pendiente';
                        break;
                    case 2:
                        $statusName = 'Anulado';
                        break;
                    case 3:
                        $statusName = 'Recepcionado';
                        break;
                    case 4:
                        $statusName = 'Pagado';
                        break;
                }
                break;
            case 'task':
            case 'stage':
                switch ( $status ) {
                    case 0:
                        $statusName = 'Desactivado';
                        break;
                    case 1:
                        $statusName = 'Por Iniciar';
                        break;
                    case 2:
                        $statusName = 'Eliminado';
                        break;
                    case 3:
                        $statusName = 'En proceso';
                        break;
                    case 4:
                        $statusName = 'Terminado';
                        break;
                    case 5:
                        $statusName = 'Observado';
                        break;
                    case 6:
                        $statusName = 'Finalizado';
                        break;
                }
                break;
            case 'observation':
                switch ( $status ) {
                    case 0:
                        $statusName = 'Desactivado';
                        break;
                    case 1:
                        $statusName = 'Pendiente de revisión';
                        break;
                    case 2:
                        $statusName = 'Eliminado';
                        break;
                    case 3:
                        $statusName = 'Obs. Aceptada';
                        break;
                    case 4:
                        $statusName = 'Obs. Denegada';
                        break;
                }
                break;
        }

        return $statusName;
    }

    public function getDataUser( $user ) {
        $data = [
            'name' => '',
            'document' => '',
            'role' => ''
        ];

        if( $user ) {
            $data['id'] = $user->id;
            $data['name'] = $user->name . ' ' . $user->last_name;
            $data['document'] = $user->document;
        }

        return $data;
    }

    public function getClassBadge( $status ) {
        $classbadge = '';
        switch ( $status ) {
            case 0:
                $classbadge = 'badge-secondary';
                break;
            case 1:
                $classbadge = 'badge-info';
                break;
            case 2:
                $classbadge = 'badge-warning';
                break;
            case 3:
                $classbadge = 'badge-success';
                break;
            case 4:
                $classbadge = 'badge-danger';
                break;
        }

        return $classbadge;
    }
}
