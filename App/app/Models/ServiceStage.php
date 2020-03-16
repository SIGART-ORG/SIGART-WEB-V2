<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceStage extends Model
{
    public function service() {
        return $this->belongsTo( 'App\Models\Service', 'services_id', 'id' );
    }

    public function tasks() {
        return $this->hasMany( 'App\Models\Task', 'service_stages_id', 'id' );
    }
}
