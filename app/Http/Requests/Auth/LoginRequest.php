<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /** Intentos fallidos permitidos antes del bloqueo */
    private const MAX_ATTEMPTS = 3;

    /** Duración del bloqueo (y de la ventana de intentos) en segundos: 3 minutos */
    private const LOCKOUT_SECONDS = 180;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Intenta autenticar. Tras 3 fallos seguidos bloquea el acceso 3 minutos.
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey(), self::LOCKOUT_SECONDS);

            $intentos = RateLimiter::attempts($this->throttleKey());

            // Tercer fallo: el bloqueo de 3 minutos empieza justo ahora
            if ($intentos >= self::MAX_ATTEMPTS) {
                Cache::put(
                    $this->lockoutKey(),
                    now()->addSeconds(self::LOCKOUT_SECONDS)->timestamp,
                    self::LOCKOUT_SECONDS
                );
                RateLimiter::clear($this->throttleKey());

                throw ValidationException::withMessages([
                    'email' => 'Demasiados intentos fallidos. Tu acceso fue bloqueado por 3 minutos.',
                ]);
            }

            $restantes = self::MAX_ATTEMPTS - $intentos;

            throw ValidationException::withMessages([
                'email' => 'Correo o contraseña incorrectos. Te ' .
                    ($restantes === 1 ? 'queda 1 intento.' : "quedan {$restantes} intentos."),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
        Cache::forget($this->lockoutKey());
    }

    /**
     * Si hay un bloqueo activo, corta el intento y avisa cuánto falta.
     */
    public function ensureIsNotRateLimited(): void
    {
        $hasta = Cache::get($this->lockoutKey());

        if (! $hasta) {
            return;
        }

        $segundos = $hasta - now()->timestamp;

        if ($segundos <= 0) {
            Cache::forget($this->lockoutKey());
            return;
        }

        event(new Lockout($this));

        $minutos = intdiv($segundos, 60);
        $resto = $segundos % 60;

        throw ValidationException::withMessages([
            'email' => "Acceso bloqueado temporalmente. Intenta de nuevo en {$minutos} min {$resto} s.",
        ]);
    }

    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')) . '|' . $this->ip());
    }

    private function lockoutKey(): string
    {
        return 'login-lockout:' . $this->throttleKey();
    }
}