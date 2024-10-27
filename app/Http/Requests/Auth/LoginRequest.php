<?php

// namespace App\Http\Requests\Auth;

// use App\Models\Teacher;
// use App\Models\User;
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
//             'login' => ['required', 'string'],
//             'password' => ['required', 'string'],
//         ];
//     }

//     /**
//      * Attempt to authenticate the request's credentials.
//      *
//      * @throws \Illuminate\Validation\ValidationException
//      */
//     protected function authenticate(): void
// {
//     $this->ensureIsNotRateLimited();

//     $credentials = $this->only('login', 'password');
//     $login = $credentials['login'];

//     // Attempt to login with various fields and check status
//     if ($this->attemptLogin('email', $login, $credentials['password']) ||
//         $this->attemptLogin('nuptk', $login, $credentials['password'], 'teacher') ||
//         $this->attemptLogin('nip', $login, $credentials['password'], 'teacher') ||
//         $this->attemptLogin('nis', $login, $credentials['password'], 'student') ||
//         $this->attemptLogin('nisn', $login, $credentials['password'], 'student')) {

//         $this->clearRateLimiter();
//         return;
//     }

//     // Check if the user's account is inactive
//     if (User::where('login', $login)->exists() && User::where('login', $login)->first()->status !== 'active') {
//         throw ValidationException::withMessages([
//             'login' => 'Your account is inactive. Please contact the administrator.',
//         ]);
//     }

//     $this->rateLimitFailedLogin();
// }

//     /**
//      * Attempt to authenticate using a specific field.
//      */
//     protected function attemptLogin(string $field, string $login, string $password, string $model = 'user'): bool
//     {
//         $modelClass = $model === 'teacher' ? Teacher::class : User::class;

//         // Build credentials array based on the specified field
//         $credentials = [
//             $field => $login,
//             'password' => $password,
//         ];

//         // Attempt authentication using the specified model and credentials
//         return Auth::guard()->attempt($credentials, $this->boolean('remember'));
//     }

//     /**
//      * Ensure the login request is not rate limited.
//      *
//      * @throws \Illuminate\Validation\ValidationException
//      */
//     protected function ensureIsNotRateLimited(): void
//     {
//         if (! $this->hasTooManyLoginAttempts()) {
//             return;
//         }

//         $this->fireLockoutEvent();

//         $seconds = RateLimiter::availableIn($this->throttleKey());

//         throw ValidationException::withMessages([
//             'login' => trans('auth.throttle', [
//                 'seconds' => $seconds,
//                 'minutes' => ceil($seconds / 60),
//             ]),
//         ]);
//     }

//     /**
//      * Increment the login attempts for the user.
//      */
//     protected function incrementLoginAttempts(): void
//     {
//         RateLimiter::hit($this->throttleKey());
//     }

//     /**
//      * Determine if the user has too many failed login attempts.
//      */
//     protected function hasTooManyLoginAttempts(): bool
//     {
//         return RateLimiter::tooManyAttempts($this->throttleKey(), 5);
//     }

//     /**
//      * Clear the login locks for the given user credentials.
//      */
//     protected function clearLoginAttempts(): void
//     {
//         RateLimiter::clear($this->throttleKey());
//     }

//     /**
//      * Get the rate limiting throttle key for the request.
//      */
//     public function throttleKey(): string
//     {
//         return Str::transliterate(Str::lower($this->input('login')).'|'.$this->ip());
//     }

//     /**
//      * Fire the lockout event.
//      */
//     protected function fireLockoutEvent(): void
//     {
//         event(new Lockout($this));
//     }

//     /**
//      * Clear the rate limiter for the login attempts.
//      */
//     protected function clearRateLimiter(): void
//     {
//         $this->clearLoginAttempts();
//     }

//     /**
//      * Handle a failed login attempt.
//      *
//      * @throws \Illuminate\Validation\ValidationException
//      */
//     protected function rateLimitFailedLogin(): void
//     {
//         $this->incrementLoginAttempts();

//         throw ValidationException::withMessages([
//             'login' => trans('auth.failed'),
//         ]);
//     }
// }


namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

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

        // mendapatkan data dari tabel user
        $user = User::where('email', $login)
                    ->orWhere('nuptk', $login)
                    ->orWhere('nip', $login)
                    ->orWhere('nis', $login)
                    ->orWhere('nisn', $login)
                    ->first();

        // cek status user active atau inactive
        if (!$user) {
            throw ValidationException::withMessages([
                'login' => 'Kredensial ini tidak cocok dengan catatan kami.',
            ]);
        }

        if ($user->status !== 'active') {
            throw ValidationException::withMessages([
                'login' => 'Akun kamu tidak aktif. Silahkan hubungi administrator.',
            ]);
        }

        // Jika status aktif maka bisa login
        if (! $this->attemptLogin($login, $credentials['password'])) {
            $this->rateLimitFailedLogin();
        }

        $this->clearRateLimiter();
    }

    /**
     * Attempt to authenticate the user using various fields.
     */
    protected function attemptLogin(string $login, string $password): bool
    {
        // perrcobaan untuk login dengan berbagai macam jenis
        return Auth::attempt(['email' => $login, 'password' => $password]) ||
               Auth::attempt(['nuptk' => $login, 'password' => $password]) ||
               Auth::attempt(['nip' => $login, 'password' => $password]) ||
               Auth::attempt(['nis' => $login, 'password' => $password]) ||
               Auth::attempt(['nisn' => $login, 'password' => $password]);
    }

    /**
     * Ensure the user is not rate-limited.
     */
    protected function ensureIsNotRateLimited(): void
    {
        if (RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            $seconds = RateLimiter::availableIn($this->throttleKey());

            throw ValidationException::withMessages([
                'login' => trans('auth.throttle', [
                    'seconds' => $seconds,
                    'minutes' => ceil($seconds / 60),
                ]),
            ]);
        }
    }

    protected function incrementLoginAttempts(): void
    {
        RateLimiter::hit($this->throttleKey());
    }

    protected function clearLoginAttempts(): void
    {
        RateLimiter::clear($this->throttleKey());
    }

    public function throttleKey(): string
    {
        return Str::lower($this->input('login')).'|'.$this->ip();
    }

    public function clearRateLimiter(): void
    {
        $this->clearLoginAttempts();
    }

    protected function rateLimitFailedLogin(): void
    {
        $this->incrementLoginAttempts();

        throw ValidationException::withMessages([
            'login' => trans('auth.failed'),
        ]);
    }
}
