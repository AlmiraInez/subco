<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomFasility extends Model
{
    protected $guarded = [];

    public function room()
    {
        return $this->hasOne('App\Models\Room', 'id', 'room_id');
    }
    public function fasility()
    {
        return $this->hasOne('App\Models\Fasility', 'id', 'fasility_id');
    }
}
