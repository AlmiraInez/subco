<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormulaDetail extends Model
{
    protected $guarded = [];

    public function formula()
    {
        return $this->hasOne('App\Models\Formula', 'id', 'formula_id');
    }
    public function answer()
    {
        return $this->hasOne('App\Models\Answer', 'id', 'answer_id');
    }
}
