<?php

namespace App\Models\EnviostoreServer;

use Illuminate\Database\Eloquent\Model;
use Alfa6661\AutoNumber\AutoNumberTrait;

class Expense extends Model
{
    use AutoNumberTrait;
    protected $guarded = [];
    /**
     * Define the table associated with model from enviostore_server connection
     *
     * @var string
     */
    protected $connection = 'enviostore_server';

    /**
     * Defince the table associated with model
     *
     * @var string
     */
    protected $table = 'expense';

    public function getAutoNumberOptions()
    {
        return [
            'expense_no' => [
                'format' => 'SR.'.date('Ymd').'.?', // autonumber format. '?' will be replaced with the generated number.
                'length' => 5 // The number of digits in an autonumber
            ],
        ];
    }
}
