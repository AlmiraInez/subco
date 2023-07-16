<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Alfa6661\AutoNumber\AutoNumberTrait;


class Payment extends Model
{
    const STAT_PROCCESSING = 1;
    const STAT_APPROVED = 2;
    const STAT_REJECTED = 3;

    use AutoNumberTrait;
    protected $guarded = [];

    public function fasility()
    {
        return $this->hasOne('App\Models\Fasility', 'id', 'fasility_id');
    }
    public function room()
    {
        return $this->hasOne('App\Models\Room', 'id', 'room_id');
    }
    public function category()
    {
        return $this->hasOne('App\Models\RoomCategory', 'id', 'room_category');
    }
    public function tenant()
    {
        return $this->hasOne('App\Models\Tenant', 'id', 'tenant_id');
    }
    public function invoice()
    {
        return $this->hasOne('App\Models\Invoice', 'id', 'invoice_id');
    }
    public function transaction()
    {
        return $this->hasOne('App\Models\Transaction', 'id', 'trans_id');
    }
   
    public function getAutoNumberOptions()
    {
        return [
            'code_system' => [
                'format' => 'SUBPAY-?', // autonumber format. '?' will be replaced with the generated number.
                'length' => 7 // The number of digits in an autonumber
            ],
        ];
    }
}
