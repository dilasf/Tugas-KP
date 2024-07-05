<?php

// namespace App\Http\Requests\Auth;

// use Illuminate\Auth\Events\Lockout;
// use Illuminate\Foundation\Http\FormRequest;
// use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\RateLimiter;
// use Illuminate\Support\Str;
// use Illuminate\Validation\ValidationException;

// class LoginRequest extends FormRequest
// {
//     /**
//      * Determine if the user is authorized to make this request.
//      */
//     public function authorize(): bool
//     {
//         return true;
//     }

//     /**
//      * Get the validation rules that apply to the request.
//      *
//      * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
//      */
//     public function rules(): array
//     {
//         return [
//             'email' => ['required', 'string', 'email'],
//             'password' => ['required', 'string'],
//         ];
//     }

//     /**
//      * Attempt to authenticate the request's credentials.
//      *
//      * @throws \Illuminate\Validation\ValidationException
//      */
//     public function authenticate(): void
//     {
//         $this->ensureIsNotRateLimited();

//         if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
//             RateLimiter::hit($this->throttleKey());

//             throw ValidationException::withMessages([
//                 'email' => trans('auth.failed'),
//             ]);
//         }

//         RateLimiter::clear($this->throttleKey());
//     }

//     /**
//      * Ensure the login request is not rate limited.
//      *
//      * @throws \Illuminate\Validation\ValidationException
//      */
//     public function ensureIsNotRateLimited(): void
//     {
//         if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
//             return;
//         }

//         event(new Lockout($this));

//         $seconds = RateLimiter::availableIn($this->throttleKey());

//         throw ValidationException::withMessages([
//             'email' => trans('auth.throttle', [
//                 'seconds' => $seconds,
//                 'minutes' => ceil($seconds / 60),
//             ]),
//         ]);
//     }

//     /**
//      * Get the rate limiting throttle key for the request.
//      */
//     public function throttleKey(): string
//     {
//         return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
//     }
// }
namespace App\Http\Requests\Auth;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $credentials = $this->only('login', 'password');
        $login = $credentials['login'];

        // Attempt to login with various fields
        if ($this->attemptLogin('email', $login, $credentials['password']) ||
            $this->attemptLogin('nuptk', $login, $credentials['password'], 'teacher') ||
            $this->attemptLogin('nip', $login, $credentials['password'])) {
            // $this->attemptLogin('nis', $login, $credentials['password']))
            $this->clearRateLimiter();
            return;
        }

        $this->rateLimitFailedLogin();
    }
    /**
     * Attempt to authenticate using a specific field.
     */
    protected function attemptLogin(string $field, string $login, string $password, string $model = 'user'): bool
    {
        $modelClass = $model === 'teacher' ? Teacher::class : User::class;

        // Build credentials array based on the specified field
        $credentials = [
            $field => $login,
            'password' => $password,
        ];

        // Attempt authentication using the specified model and credentials
        return Auth::guard()->attempt($credentials, $this->boolean('remember'));
    }

    // protected function attemptLogin(string $field, string $login, string $password, string $model = 'user'): bool
    // {
    //     $modelClass = $model === 'teacher' ? Teacher::class : User::class;

    //     // Build credentials array based on the specified field
    //     $credentials = [
    //         $field => $login,
    //         'password' => $password,
    //     ];

    //     // Attempt authentication using the specified model and credentials
    //     $authenticated = Auth::guard()->attempt($credentials, $this->boolean('remember'));

    //     if ($authenticated) {
    //         // Retrieve the user instance based on the login field
    //         $user = $modelClass::where($field, $login)->first();

    //         // Check if the user is a teacher and if their status is active
    //         if ($model === 'teacher' && $user && $user->status !== 'active') {
    //             Auth::logout(); // Logout the user if teacher status is not active
    //             return false;
    //         }

    //         return true;
    //     }

    //     return false;
    // }



    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function ensureIsNotRateLimited(): void
    {
        if (! $this->hasTooManyLoginAttempts()) {
            return;
        }

        $this->fireLockoutEvent();

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'login' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Increment the login attempts for the user.
     */
    protected function incrementLoginAttempts(): void
    {
        RateLimiter::hit($this->throttleKey());
    }

    /**
     * Determine if the user has too many failed login attempts.
     */
    protected function hasTooManyLoginAttempts(): bool
    {
        return RateLimiter::tooManyAttempts($this->throttleKey(), 5);
    }

    /**
     * Clear the login locks for the given user credentials.
     */
    protected function clearLoginAttempts(): void
    {
        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->input('login')).'|'.$this->ip());
    }

    /**
     * Fire the lockout event.
     */
    protected function fireLockoutEvent(): void
    {
        event(new Lockout($this));
    }

    /**
     * Clear the rate limiter for the login attempts.
     */
    protected function clearRateLimiter(): void
    {
        $this->clearLoginAttempts();
    }

    /**
     * Handle a failed login attempt.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function rateLimitFailedLogin(): void
    {
        $this->incrementLoginAttempts();

        throw ValidationException::withMessages([
            'login' => trans('auth.failed'),
        ]);
    }
}
