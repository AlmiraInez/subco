<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

class InvalidAttendanceRevisionException extends Exception
{
    /**
     * Create a new exception instance.
     *
     * @param  string $message
     * @return void
     */
    public function __construct($message = 'The given data was invalid.')
    {
        parent::__construct($message);
    }

    /**
     * Report the exception.
     *
     * @return bool|null
     */
    public function report()
    {
        //
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
    	if ($request->wantsJson()) {
    		return response()->json([
    			'status'    => Response::HTTP_UNPROCESSABLE_ENTITY,
    			'message'   => $this->getMessage()
    		], Response::HTTP_UNPROCESSABLE_ENTITY);
    	}
    }
}
