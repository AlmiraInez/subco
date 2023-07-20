<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $guarded = [];

   
    public function fasilities()
    {
        return $this->hasMany('App\Models\RoomFasility', 'room_id', 'id');
    }
    public function category()
    {
        return $this->hasOne('App\Models\RoomCategory', 'id', 'category_id');
    }
    public function details()
    {
        return $this->hasMany('App\Models\RoomDetails', 'id', 'room_id');
    }
    public function roomfasilities()
    {
        return $this->hasMany('App\Models\RoomFasility', 'room_id', 'id');
    }
    public function allCases()
    {
        return Room::paginate(40);
    }

  

}
