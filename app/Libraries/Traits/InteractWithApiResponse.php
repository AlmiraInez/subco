<?php 

namespace App\Libraries\Traits;
use Illuminate\Http\Response;

/*
 * This file is part of BiisCorp project
 *
 * (c) Rizky Miftaqul <tizqy.miftaqul77@gmail.com.com>
 *
 */

trait InteractWithApiResponse
{
	public function success($code, $message, $data)
	{
		$response['status'] = $code;
		$response['message'] = $message;
		$response['data'] = $data;
		
		return response()->json($response, $code);
	}

	public function error($message=null)
	{
		$response['status'] = Response::HTTP_UNPROCESSABLE_ENTITY;
		$response['message'] = $message ?: 'Terjadi kesalahan';

		return response()->json($response, Response::HTTP_UNPROCESSABLE_ENTITY);
	}
}