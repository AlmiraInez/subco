<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kpi extends Model
{
    protected $guarded = [];

    public function question()
    {
        return $this->hasOne('App\Models\Question', 'id', 'question_id');
    }
    public function answer()
    {
        return $this->hasOne('App\Models\Answer', 'id', 'answer_id');
    }
    public function employee()
    {
        return $this->hasOne('App\Models\Employee', 'id', 'employee_id');
    }
}
