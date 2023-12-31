<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Klaravel\Ntrust\Traits\NtrustRoleTrait;

class Role extends Model
{
    use NtrustRoleTrait;

    /*
     * Role profile to get value from ntrust config file.
     */
    protected $guarded = [];
    protected static $roleProfile = 'user';
}
