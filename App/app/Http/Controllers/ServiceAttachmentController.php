<?php

namespace App\Http\Controllers;

use App\Models\ServiceAttachment;
use Illuminate\Http\Request;

class ServiceAttachmentController extends Controller
{
    public function listServiceAttachment( Request $request ) {
        $id = $request->id ? $request->id : 0;

        $attachments = ServiceAttachment::where( 'status', 1 )
            ->where( 'services_id', $id )
            ->orderBy( 'created_at', 'desc' )
            ->get();

        $records = [];

        foreach( $attachments as $attachment ) {
            $row = new \stdClass();
            $row->id = $attachment->id;
            $row->name = $attachment->name;
            $row->file = $attachment->file ? asset( self::PATH_VOUCHER . $attachment->file ) : '';
            $row->valid = $attachment->is_valid;

            $records[] = $row;
        }

        return response()->json([
            'status' => true,
            'attachments' => $records
        ], 200);
    }
}
