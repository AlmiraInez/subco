<?php

namespace App\Http\Requests\Auth;

use App\Libraries\Facades\RateLimiter;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|email',
            'password' => 'required|min:6'
        ];
    }

    /**
     * Description
     * 
     * @return mixed
     * 
     * @throws Illuminate\Validation\ValidationException
     */
    public function authenticate()
    {
        $this->ensureIsNotRatelimited();

        $authToken = Auth::guard('api')->attempt($this->only('email', 'password'));

        if (!$authToken) {
            RateLimiter::hit($this->throttleKey(), 300);

            throw ValidationException::withMessages([
                'data' => __('auth.failed')
            ])->status(Response::HTTP_TOO_MANY_REQUESTS);
        }

        RateLimiter::clear($this->throttleKey());

        return $this->createToken($authToken);
    }

    protected function createToken($token)
    {
        return response()->json([
            'status' => Response::HTTP_OK,
            'data' => Auth::guard('api')->user(),
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ], Response::HTTP_OK);
    }

    /**
     * Ensure the throttle rate limiter
     * 
     * @return mixed
     * 
     * @throws Illuminate\Validation\ValidationException
     */
    protected function ensureIsNotRatelimited()
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'data' => trans('auth.throttle', [
                'seconds' => $seconds, 
                'minutes' => ceil($seconds / 60)
            ])
        ])->status(Response::HTTP_TOO_MANY_REQUESTS);
    }

    /**
     * Define the throttle key
     * 
     * @return string
     */
    protected function throttleKey()
    {
        return Str::lower($this->input('username'))."|".$this->ip();
    }
}
