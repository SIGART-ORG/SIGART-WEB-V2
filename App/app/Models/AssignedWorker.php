<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssignedWorker extends Model
{
    public function task() {
        return $this->belongsTo( 'App\Models\AssignedWorker', 'tasks_id', 'id' );
    }

    public function user() {
       return  $this->belongsTo( 'App\Models\User', 'users_id', 'id' );
    }
}
