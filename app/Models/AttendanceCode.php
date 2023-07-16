<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceCode extends Model
{
	protected $fillable = ['code', 'status'];

	public function scopeActive($query)
	{
		return $query->where('status', 'not scanned');
	}
}
