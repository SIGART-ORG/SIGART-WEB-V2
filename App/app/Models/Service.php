<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    const TABLE_NAME = 'services';
    protected $table = self::TABLE_NAME;

    public function serviceRequest() {
        return $this->belongsTo( 'App\Models\ServiceRequest', 'service_requests_id', 'id' );
    }

    public function serviceStages() {
        return $this->hasMany( 'App\Models\ServiceStage', 'services_id', 'id' );
    }
}
