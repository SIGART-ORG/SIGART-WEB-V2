<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    public function stage() {
        return $this->belongsTo( 'App\Models\ServiceStage', 'service_stages_id', 'id' );
    }

    public function assignedWorkers() {
        return $this->hasMany( 'App\Models\AssignedWorker', 'tasks_id', 'id' );
    }

    public function taskObserveds() {
        return $this->hasMany( 'App\Models\TaskObserved', 'tasks_id', 'id' );
    }
}
