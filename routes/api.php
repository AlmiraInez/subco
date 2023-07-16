<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
	'namespace' => 'Api',
	'as' => 'api.',
	'middleware' => 'cors'
], function() {

	// API V1
	Route::group(['prefix' => 'v1', 'namespace' => 'V1', 'as' => 'v1.'], function() {
		Route::post('auth', 'Auth\LoginController@store');

		Route::group(['middleware' => ['auth:api']], function() {

			Route::post('me', 'Auth\ProfileController')->name('api.profile');

			Route::post('logout', 'Auth\LoginController@destroy');
			

			Route::group(['prefix' => 'attendance', 'as' => 'attendance.', 'namespace' => 'Attendance'], function() {

				Route::resource('/', 'AttendanceController');
				// Attendance Revision
				Route::resource('revision', 'RevisionController');
				// Attendance Log
				Route::resource('log', 'LogController');

			});

			Route::apiResources([
				'scan' => 'Attendance\ScanController',
				'leave' => 'Leave\LeaveController',
			],[
				'except' => [
					'scan' => 'destroy'
				]
			]);

			Route::group(['prefix' => 'salary', 'as' => 'salary.', 'namespace' => 'Salary'], function() {
				Route::resource('report', 'SalaryReportController');
				Route::get('current', 'SalaryReportController@show')->name('salary.current');
			});

			Route::post('scan/out', 'Attendance\ScanController@destroy')->name('scan.destroy');

			Route::get('leavetype/select', 'Leave\LeaveTypeController@select')->name('leave.select');

			Route::get('leave/status', 'Leave\LeaveController@show')->name('leave.status');
		});
	});


});