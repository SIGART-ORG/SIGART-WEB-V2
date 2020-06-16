<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PresentationImage extends Model
{
    const TABLE_NAME = 'presentation_images';
    protected $table = self::TABLE_NAME;

    public function presentation() {
        return $this->belongsTo( Presentation::class, 'presentation_id', 'id' );
    }
}
