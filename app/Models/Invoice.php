<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Alfa6661\AutoNumber\AutoNumberTrait;



class Invoice extends Model
{
    const STAT_UNPAID = 0;
    const STAT_PAID= 1;
    const STAT_PROCCESSING = 1;
    const STAT_APPROVED = 2;
    const STAT_REJECTED = 3;

    use AutoNumberTrait;
    protected $guarded = [];

    public function tenant()
    {
        return $this->hasOne('App\Models\Tenant', 'id', 'tenant_id');
    }
    public function room()
    {
        return $this->hasOne('App\Models\Room', 'id', 'room_id');
    }
    public function category()
    {
        return $this->hasOne('App\Models\RoomCategory', 'id', 'room_category');
    }
    public function transaction()
    {
        return $this->hasOne('App\Models\Transaction', 'id', 'trans_id');
    }
    public function getAutoNumberOptions()
    {
        return [
            'code_system' => [
                'format' =>'SUBINV-?', // autonumber format. '?' will be replaced with the generated number.
                'length' => 7 // The number of digits in an autonumber
            ],
        ];
    }
}