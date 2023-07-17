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
    public function allCases()
    {
        return Room::paginate(40);
    }

    public function searchCases(array $searchData)
    {
        $from = $searchData['from'];
        $to = $searchData['to'];
        $district = $searchData['district'];
  
         //Logic if all fields are given

        if(!empty($district)&&!empty($from)&&!empty($to)){
        $cases = Room::where('district',$district)
                        ->paginate(1);
                        return $cases;
        }
        
    }

}
