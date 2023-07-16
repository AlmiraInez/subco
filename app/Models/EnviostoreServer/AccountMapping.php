<?php

namespace App\Models\EnviostoreServer;

use Illuminate\Database\Eloquent\Model;

class AccountMapping extends Model
{
    protected $guarded = [];
    /**
     * Define the table associated with model from enviostore_server connection
     *
     * @var string
     */
    protected $connection = 'enviostore_server';

    /**
     * primaryKey 
     * 
     * @var integer
     * @access protected
     */
    protected $primaryKey = null;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;
    /**
     * Defince the table associated with model
     *
     * @var string
     */
    protected $table = 'account_mapping';
}
