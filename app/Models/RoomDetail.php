<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomDetail extends Model
{
    protected $guarded = [];

    public function fasility()
    {
        return $this->hasOne('App\Models\Room', 'id', 'room_id');
    }
}
