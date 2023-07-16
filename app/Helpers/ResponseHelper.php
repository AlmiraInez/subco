<?php

/*
 * This file is part of BiisCorp project
 *
 * (c) Rizky Miftaqul <tizqy.miftaqul77@gmail.com>
 *
 */

if (!function_exists('to_json')) {
	
    /**
     * Return a new response json from the application.
     *
     * @param  \Illuminate\View\View|string|array|null  $content
     * @param  int     $status
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
	function to_json(array $content, int $code)
	{
		return response()->json($content, $code);
	}
}