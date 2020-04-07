<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    const TABLE_NAME = 'tasks';
    protected $table = self::TABLE_NAME;

    public function stage() {
        return $this->belongsTo( 'App\Models\ServiceStage', 'service_stages_id', 'id' );
    }

    public function assignedWorkers() {
        return $this->hasMany( 'App\Models\AssignedWorker', 'tasks_id', 'id' );
    }

    public function taskObserveds() {
        return $this->hasMany( 'App\Models\TaskObserved', 'tasks_id', 'id' );
    }

    public static function approvedAllTasksByStage( $stage, $status = 6 ) {
        self::where( 'service_stages_id', $stage )
            ->whereNotIn( 'status', [0,2] )
            ->update(['status' => $status]);
    }
}
