<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Referenceterm;
use App\Models\SaleQuotation;
use App\Models\Service;
use App\Models\ServiceAttachment;
use App\Models\ServiceRequest;
use App\Models\ServiceRequestDetail;
use App\Models\SiteVoucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    const PATH_UPLOAD_SR = 'uploads/service-request/';

    public function getServiceRequest() {

        $ServiceRequestClass = new ServiceRequest();

        $numPerpage = 10;

        $data = $ServiceRequestClass->listData( $numPerpage );

        foreach ( $data as $idx => $item ) {
            $data[ $idx ]->dateRegFormat = date( 'd/m/Y', strtotime( $item->date_reg ) );
            $data[ $idx ]->dateSendFormat = ( ! empty( $item->date_send ) ? date( 'd/m/Y H:i:s', strtotime( $item->date_send ) ) : '' );
        };

        return response()->json(['serviceRequest' => $data]);

    }

    public function generateServiceRequest( Request $request ) {

        $response = [
            'status'    => true,
            'message'   => 'OK'
        ];

        $type         = $request->type;
        $id         = $request->id;
        $name       = $request->name;
        $details    = $request->details;
        $dateDelivery    = $request->dateDelivery ? date( 'Y-m-d', strtotime( $request->dateDelivery ) ) : null;
        $address    = $request->address;
        $ubigeo     = json_decode( $request->ubigeo );
        $district   = $ubigeo->district;

        $SiteVoucherClass = new SiteVoucher();
        $typeVoucher = 2;

        if( ! empty( $details ) && is_array( $details ) && count( $details ) ) {
            $userSession = Auth::user();

            if( ! empty( $id ) && $id > 0 ) {

                $ServiceRequestClass = ServiceRequest::where( 'id', $id )
                    ->where( 'customers_id', $userSession->customers_id )
                    ->whereIn( 'is_send', [ 0, 2 ])
                    ->first();

                if( ! empty( $ServiceRequestClass ) && $ServiceRequestClass->id > 0 ) {

                    $ServiceRequestClass->description = $name;
                    $ServiceRequestClass->description_corrected = $name;
                    $ServiceRequestClass->address = $address;
                    $ServiceRequestClass->district_id = $district;
                    $ServiceRequestClass->delivery_date = $dateDelivery;
                    if( $type === 'send' ) {
                        $ServiceRequestClass->is_send = 1;
                        $ServiceRequestClass->date_send = date( 'Y-m-d H:i:s' );
                    }

                } else {

                    return response()->json([
                        'status'    => false,
                        'message'   => 'No se pudo editar la solicitud de servicio.'
                    ]);

                }
            } else {
                $correlative = $SiteVoucherClass->getNumberVoucherSite( $typeVoucher );

                $ServiceRequestClass = new ServiceRequest();
                $ServiceRequestClass->sites_id = 1;
                $ServiceRequestClass->type_vouchers_id = $typeVoucher;
                $ServiceRequestClass->date_emission = date('Y-m-d');
                $ServiceRequestClass->num_request = $correlative['correlative'];
                $ServiceRequestClass->customers_id = $userSession->customers_id;
                $ServiceRequestClass->date_reg = date('Y-m-d');
                $ServiceRequestClass->date_aproved = date('Y-m-d');
                $ServiceRequestClass->description = $name;
                $ServiceRequestClass->description_corrected = $name;
                $ServiceRequestClass->address = $address;
                $ServiceRequestClass->district_id = $district;
                $ServiceRequestClass->delivery_date = $dateDelivery;
                if( $type === 'send' ) {
                    $ServiceRequestClass->is_send = 1;
                    $ServiceRequestClass->date_send = date( 'Y-m-d H:i:s' );
                }
            }

            if( $request->hasFile( 'attachment' ) ) {
                $ServiceRequestClass->attachment = $this->uploadAttachment( $request->file( 'attachment' ) );
            }

            if( $ServiceRequestClass->save() ) {

                $response['document'] = $ServiceRequestClass->num_request;

                $SiteVoucherClass->increaseCorrelative( $typeVoucher );

                if( $id > 0 && $ServiceRequestClass->id > 0 ) {
                    ServiceRequestDetail::where( 'service_requests_id', $id )
                        ->whereIn( 'status', [ 0, 1 ])
                        ->update( ['status' => 2] );
                }

                foreach( $details as $item ) {

                    $detail = json_decode( $item );
                    if( $id > 0 && $detail->id > 0 ) {

                        $ServiceRequestDetailClass = ServiceRequestDetail::where( 'id', $detail->id )->first();
                        $ServiceRequestDetailClass->name                = Str::limit( $detail->item, 17, '...' );
                        $ServiceRequestDetailClass->description         = $detail->item;
                        $ServiceRequestDetailClass->description_corrected         = $detail->item;
                        $ServiceRequestDetailClass->quantity            = $detail->count;
                        $ServiceRequestDetailClass->status            = 1;

                    } else {

                        $ServiceRequestDetailClass = new ServiceRequestDetail();
                        $ServiceRequestDetailClass->service_requests_id = $ServiceRequestClass->id;
                        $ServiceRequestDetailClass->name                = Str::limit( $detail->item, 17, '...' );
                        $ServiceRequestDetailClass->description         = $detail->item;
                        $ServiceRequestDetailClass->description_corrected         = $detail->item;
                        $ServiceRequestDetailClass->quantity            = $detail->count;
                        $ServiceRequestDetailClass->assumed_customer    = 0;

                    }

                    $ServiceRequestDetailClass->save();
                }
            }

            return response()->json( $response );
        }

        return response()->json([
            'status'    => false,
            'message'   => 'No se pudo generar la solicitud de servicio.'
        ]);
    }

    private function uploadAttachment( $file ) {
        $destinationPath = public_path(self::PATH_UPLOAD_SR );
        $newImage = 'service-request-' . time() . '.' . $file->getClientOriginalExtension();
        $file->move( $destinationPath, $newImage );

        return asset( self::PATH_UPLOAD_SR . $newImage);
    }

    public function edit( Request $request ) {

        $response = [
            'status'            => false,
            'serviceRequest'    => []
        ];

        $user = Auth::user();

        $serviceRequestClass = new ServiceRequest();
        $serviceRequestDetailClass = new ServiceRequestDetail();

        $serviceRequest = $serviceRequestClass::where( $serviceRequestClass::TABLE_NAME . '.id', $request->id )
            ->isSend( $request->force )
            ->where( $serviceRequestClass::TABLE_NAME . '.customers_id', $user->customers_id )
            ->first();

        if( $serviceRequest ) {
            $district = $serviceRequest->district_id ? $serviceRequest->district_id : '';
            $address = $serviceRequest->address;

            if( $district === '' || $address === '' ) {
                $customer = Customer::find($user->customers_id);

                if ($customer) {
                    $district = $district === '' ? $customer->district_id : $district;
                    $address = $address === '' ? $customer->address : $address;
                }
            }

            $district = \FormatUbigeo::complete( $district );
            $ubigeoBD = \FormatUbigeo::ubigeo( $district );

            $ubigeo = new \stdClass();
            $ubigeo->district = $ubigeoBD['district_id'];
            $ubigeo->district_name = $ubigeoBD['district_name'];
            $ubigeo->province = $ubigeoBD['province_id'];
            $ubigeo->province_name = $ubigeoBD['province_name'];
            $ubigeo->departament = $ubigeoBD['departament_id'];
            $ubigeo->departament_name = $ubigeoBD['departament_name'];

            $serviceRequestDetail = $serviceRequestDetailClass::where( $serviceRequestDetailClass::TABLE_NAME . '.status', 1 )
                ->where( $serviceRequestDetailClass::TABLE_NAME . '.service_requests_id', $serviceRequest->id )
                ->get();

            $arrDetail = [];

            foreach ( $serviceRequestDetail as $detail ) {
                $arrDetail[] = $detail;
            }

            $dataUbigeo = [
                'departaments' => [],
                'provinces' => [],
                'districts' => [],
            ];

            $response['status'] = true;
            $response['serviceRequest'] = [
                'id'        => $serviceRequest->id,
                'dateReg'   => $serviceRequest->date_reg,
                'isSend'    => $serviceRequest->is_send,
                'name'      => $serviceRequest->description,
                'dateDelivery'      => $serviceRequest->delivery_date ? $serviceRequest->delivery_date : '',
                'address'   => $address,
                'ubigeo'    => $ubigeo,
                'detail'    => $arrDetail,
                'attachment'=> $serviceRequest->attachment,
                'dateRegFormat' => date( 'd/m/Y', strtotime( $serviceRequest->date_reg ) ),
                'dateSendFormat' => ( ! empty( $serviceRequest->date_send ) ? date( 'd/m/Y H:i:s', strtotime( $serviceRequest->date_send ) ) : '' ),
                'status' => $serviceRequest->status
            ];

        }

        return response()->json( $response );
    }

    public function send( Request $request ) {

        $id = $request->id;

        $serviceRequest = ServiceRequest::where('status', 1)
            ->where('id', $id)
            ->where( 'is_send', '!=', 1 )
            ->first();

        if( ! empty( $serviceRequest ) ) {

            $serviceRequest->is_send = 1;
            $serviceRequest->date_send = date( 'Y-m-d H:i:s' );
            $serviceRequest->save();

            return response()->json([
                'status' => true,
                'document' => $serviceRequest->num_request
            ]);
        }

        return response()->json([
            'status' => false
        ]);
    }

    public function delete( Request $request ) {

        $user = Auth::user();
        $id = $request->id;

        $serviceRequest = ServiceRequest::where( 'status', 1 )
            ->where( 'id', $id )
            ->where( 'is_send', '!=', 1 )
            ->first();

        if( ! empty( $serviceRequest ) ) {
            $serviceRequest->status = 2;
            $serviceRequest->save();

            SaleQuotation::where( 'service_requests_id', $id )
                ->where( 'status', '!=', 2 )
                ->update([
                    'status' => 3,
                    'is_approved_customer' => 3,
                    'customer_login_id' => $user->id,
                    'date_approved_customer' => date( 'Y-m-d H:i:s' )
                ]);

            return response()->json([
                'status' => true
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'No se puede eliminar esta solicitud de servicio.'
        ]);

    }

    public function listServices( Request $request ) {

        $user = Auth::user();
        $customer = $user->customers_id;

        $response = [
            'pagination' => [],
            'services' => []
        ];

        $services = Service::whereHas('serviceRequest', function ($query) use( $customer ) {
                $query->where( 'customers_id', $customer );
            })
            ->whereNotIn( 'status', [0,2] )
            ->orderBy( 'date_reg', 'desc' )
            ->paginate( 15 );


        foreach( $services as $service ) {
            $dataTasks = $this->getStages( $service, true );

            $row = new \stdClass();
            $row->id = $service->id;
            $row->status = $service->status;
            $row->subTotal = number_format( $service->sub_total, 2 );
            $row->igv = number_format( $service->igv, 2);
            $row->total = number_format( $service->total, 2);
            $row->document = $service->serial_doc . '-' . $service->number_doc;
            $row->orderPay = $service->is_send_order_pay;
            $row->start = $this->getDate( $service->start_date );
            $row->end = $this->getDate( $service->end_date );
            $row->voucherFiles = $service->serviceAttachment->whereIn( 'type', [1, 2] )->where( 'status', 1 )->count();
            $row->minPay = number_format( $service->pay_first, 2 );
            $payments = $service->sales->where('status', 1)->sum( 'total' );
            $difference = $service->total - $payments;
            $row->payments = number_format( $payments, 2 );
            $row->difference = $difference >= 0 ? number_format( $difference, 2 ) : number_format( 0, 2);


            $servicerequest = $service->serviceRequest;
            $row->servicerequest = new \stdClass();
            $row->servicerequest->id = $servicerequest->id;
            $row->servicerequest->document = $servicerequest->num_request;
            $row->servicerequest->name = $servicerequest->description;
            $row->servicerequest->send = $servicerequest->date_send ? date( 'Y-m-d', strtotime( $servicerequest->date_send ) ) : '---';

            $saleQuotation = $servicerequest->saleQuotations->sortByDesc( 'created_at' )->first();
            $referenceterm = $saleQuotation->referenceTerms->sortByDesc( 'created_at' )->first();
            $row->referenceTerm = new \stdClass();
            $row->referenceTerm->id = $referenceterm->id;
            $row->referenceTerm->pdf = $referenceterm->pdf_os;
            $row->referenceTerm->approved = $referenceterm->os_type_approved_customer;
            $row->referenceTerm->ejecution = $referenceterm->execution_time_days;

            $row->project = new \stdClass();
            $row->project->trafficLight = $dataTasks['trafficLight'];
            $row->project->percent = $dataTasks['percent'];
            $row->project->tasks = $dataTasks['stage'];

            $response['services'][] = $row;
        }

        return response()->json( $response );

    }

    public function approvedSO( Request $request ) {
        $service = $request->id ? $request->id : 0;
        $referenceterm = $request->referenceterm ? $request->referenceterm : 0;
        $action = $request->action ? $request->action : '';

        $response = $this->aproval( $referenceterm, $action, $service );

        return response()->json( $response );
    }

    private function aproval( $id, $action, $service ) {
        $user = Auth()->user();
        $customerId = $user->customers_id;

        $referenceTerm = Referenceterm::find( $id );
        $response = [
            'status' => false,
            'msg' => 'No se pudo realizar la operación'
        ];

        if( $referenceTerm && $referenceTerm->os_type_approved_customer === 0 ) {
            $referenceTerm->os_type_approved_customer = $action === 'aproval' ? 1: 0;
            $referenceTerm->os_date_approved_customer = date( 'Y-m-d H:i:s');
            $referenceTerm->os_user_approved_customer = $customerId;
            $referenceTerm->os_user_login_approved_customer = $user->id;
            if( $referenceTerm->save() ) {
                $this->changeToStatus( $service, $action );
                $response['status'] = true;
                $response['msg'] = 'OK';
            }
        }

        return $response;
    }

    private function changeToStatus( $id, $action ) {
        $service = Service::find( $id );
        if( $service && $service->status === 1 ) {
            $service->status = $action === 'aproval' ? 3: 0;
            $service->is_aproved_customer = $action === 'aproval' ? 1: 2;
            $service->date_aproved_customer = date( 'Y-m-d' );
            $service->save();
        }
    }

    public function detailAll( Request $request ) {
        $response = [
            'status' => false,
            'service' => []
        ];

        $id = $request->id ? $request->id : 0;

        $service = Service::find( $id );

        if( $service ) {
            $dataStage = $this->getStages( $service );

            $data = new \stdClass();
            $data->id = $service->id;
            $data->document = $service->serial_doc . '-' . $service->number_doc;
            $data->start = $this->getDate( $service->start_date );
            $data->end = $this->getDate( $service->end_date );
            $data->total = $service->total;
            $data->project = new \stdClass();
            $data->project->trafficLight = $dataStage['trafficLight'];
            $data->project->percent = $dataStage['percent'];
            $data->project->isValidateCustomer = $dataStage['isValidateCustomer'];
            $data->project->stages = $dataStage['stage'];

            $response['status'] = true;
            $response['service'] = $data;
        }

        return response()->json( $response );
    }

    private function getStages( $service, $onlyTotal = false ) {

        $isValidateCustomer = false;

        $stageTask = [
            0 => 0,/*Total*/
            1 => 0,/*toStart*/
            2 => 0,/*inProcess*/
            3 => 0,/*finished*/
            4 => 0,/*observed*/
            5 => 0,/*finalized*/
        ];

        $stagesSummary = [
            'toStart' => ['total' => 0, 'records' => []],
            'inProcess' => ['total' => 0, 'records' => []],
            'finished' => ['total' => 0, 'records' => []],
            'observed' => ['total' => 0, 'records' => []],
            'finalized' => ['total' => 0, 'records' => []],
        ];

        $stages = $service->serviceStages->whereNotIn('status', [0,2]);
        foreach ( $stages as $stage ) {
            $dataTasks = $stage->tasks->whereNotIn('status', [0,2]);

            $users = [
                'total' => 0,
                'records' => []
            ];
            foreach ( $dataTasks as $data_task ) {
                $users['records'] = $this->assignedWorkers($data_task, $users['records']);
            }
            $users['total'] = count( $users['records'] );

            $row = new \stdClass();
            if( !$onlyTotal ) {
                $dateStage = $this->stageStartAndEnd( $stage );

                $row->id = $stage->id;
                $row->name = $stage->name;
                $row->description = $stage->description;
                $row->status = $stage->status;
                $row->statusName = $this->getStatus('stage', $stage->status);
                $row->start = $dateStage->start;
                $row->end = $dateStage->end;
                $row->users = $users;
                $row->observeds = $this->observeds( $stage );
            }

            switch ( $stage->status ) {
                case 1:
                    $stagesSummary['toStart']['total']++;
                    $stagesSummary['toStart']['records'][] = $row;
                    $stageTask[0]++;
                    $stageTask[1]++;
                    break;
                case 3:
                    $stagesSummary['inProcess']['total']++;
                    $stagesSummary['inProcess']['records'][] = $row;
                    $stageTask[0]++;
                    $stageTask[2]++;
                    break;
                case 4:
                    $stagesSummary['finished']['total']++;
                    $stagesSummary['finished']['records'][] = $row;
                    $stageTask[0]++;
                    $stageTask[3]++;
                    break;
                case 5:
                    $stagesSummary['observed']['total']++;
                    $stagesSummary['observed']['records'][] = $row;
                    $stageTask[0]++;
                    $stageTask[4]++;
                    break;
                case 6:
                    $stagesSummary['finalized']['total']++;
                    $stagesSummary['finalized']['records'][] = $row;
                    $stageTask[0]++;
                    $stageTask[5]++;
                    break;
            }
        }

        $trafficLight = 0;
        $percent = 0;

        if( $stageTask[0] > 0 ) {
            $totalPoint = $stageTask[0] * 3;
            $points = ( $stageTask[1] * 0 ) + ( $stageTask[2] * 1 ) + ( $stageTask[3] * 2 ) + ( $stageTask[4] * 2 ) + ( $stageTask[5] * 3 );

            if( $totalPoint > 0 ) {
                $percent = round( ( $points/ $totalPoint ) * 100, 2 );
            }
            if( $percent <= 16.67 ) {
                $trafficLight = 1;
            } else if( $percent > 16.67 && $percent <= 33.33 ) {
                $trafficLight = 2;
            } else if( $percent > 33.33 && $percent <= 50 ) {
                $trafficLight = 3;
            } else if( $percent > 50 && $percent <= 66.6 ) {
                $trafficLight = 4;
            } else if( $percent > 66.66 && $percent <= 83.33 ) {
                $trafficLight = 5;
            } else {
                $trafficLight = 6;
            }
        }

        if( $stageTask[0] > 0 && ( $stageTask[0] - $stageTask[4] - $stageTask[5] ) === $stageTask[3] ) {
            $isValidateCustomer = true;
        }

        $data = [
            'trafficLight' => $trafficLight,
            'stage' => $stagesSummary,
            'percent' => $percent,
            'isValidateCustomer' => $isValidateCustomer
        ];

        return $data;
    }

    public function stageStartAndEnd( $stage ) {
        $status = $stage->status;
        $start = $stage->statusdate->where( 'stage_status', $status )
            ->where( 'status', 1 )->where( 'type', 1 )
            ->sortByDesc( 'register' )
            ->first();
        $startDate = $start ? $start->register : null;

        $end = $stage->statusdate->where( 'stage_status', $status )
            ->where( 'status', 1 )->where( 'type', 2 )
            ->sortByDesc( 'register' )
            ->first();
        $endDate = $end ? $end->register : null;

        $data = new \stdClass();
        $data->start = $this->getDateComplete( $startDate );
        $data->end = $this->getDateComplete( $endDate );

        return $data;
    }

    public function assignedWorkers( $task, $users ) {

        $idExist = array_column( $users, 'id' );

        if( count( $idExist ) > 0 ) {
            $assignedWorkers = $task->assignedWorkers->whereNotIn( 'users_id', $idExist )->where( 'status', 1 );
        } else {
            $assignedWorkers = $task->assignedWorkers->where( 'status', 1 );
        }

        foreach ( $assignedWorkers as $assigned_worker ) {
            $users[] = $this->getDataUser( $assigned_worker->user );
        }

        return $users;
    }

    public function observeds( $stage ) {
        $data = new \stdClass();

        $sent = $stage ? $stage->observeds->where('status', 1)->count() : 0;
        $solved = $stage ? $stage->observeds->where('status', 3)->count() : 0;
        $denied = $stage ? $stage->observeds->where('status', 4)->count() : 0;

        $data->sent = $sent;
        $data->solved = $solved;
        $data->denied = $denied;
        $data->forApproved = $stage ? $stage->observeds->where('status', 4)->where('is_validate_reply', 0)->count() : 0;
        $data->total = $sent + $solved + $denied;
        $data->isRegister = $sent > 0 ? false : true;
        return $data;
    }

    public function uploadVoucher( Request $request ) {
        $response = [
            'status' => false,
            'msg' => 'No se puede realizar la operación.'
        ];

        $id = $request->id ? $request->id : 0;
        $file = $request->hasFile( 'voucherFile' ) ? $request->file( 'voucherFile' ) : null;
        $orderPayTemp = $request->orderPayTemp ? $request->orderPayTemp : 0;
        $nunOper = $request->numOper ? $request->numOper : '';
        $mount = $request->mount ? $request->mount : 0;

        $type = 0;
        switch( $orderPayTemp ) {
            case 1:
            case 2:
                $type = 1;/*Primera orden de pago*/
                break;
            case 3:
            case 4:
                $type = 2;/*Primera orden de pago*/
                break;
        }

        if( $id > 0 && $file && $type > 0 ) {
            $service = Service::find( $id );
            if( $service ) {
                $destinationPath = public_path(self::PATH_VOUCHER );
                $nameImage = 'voucher-upload-' . time();
                $newImage = $nameImage . '.' . $file->getClientOriginalExtension();
                if( $file->move( $destinationPath, $newImage ) ) {
                    $attachment = new ServiceAttachment();
                    $attachment->services_id = $id;
                    $attachment->name = $nameImage;
                    $attachment->file = $newImage;
                    $attachment->type = $type;
                    $attachment->number_operation = $nunOper;
                    $attachment->mount = $mount;
                    if( $attachment->save() ) {
                        $response['status'] = true;
                        $response['msg'] = 'OK.';
                    }
                }
            }
        }
        return response()->json( $response, 200 );
    }
}
