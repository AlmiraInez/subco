<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Formula extends Model
{
    protected $guarded = [];
    public function detail()
    {
        return $this->hasMany(FormulaDetail::class, 'formula_id')->orderBy('order', 'asc');
    }
}
