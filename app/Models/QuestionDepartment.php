<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionDepartment extends Model
{
    protected $guarded = [];

    public function question()
    {
        return $this->hasOne('App\Models\Question', 'id', 'question_id');
    }
    public function department()
    {
        return $this->hasOne('App\Models\Department', 'id', 'department_id');
    }
}
