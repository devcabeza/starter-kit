<?php

declare(strict_types=1);

namespace App\Livewire\Auth;

use App\Application\Auth\Actions\SendMagicLinkAction;
use App\Application\Auth\Actions\VerifyMagicLinkTokenAction;
use App\Application\Auth\DTOs\SendMagicLinkDTO;
use App\Application\Auth\DTOs\VerifyMagicLinkDTO;
use App\Domain\Auth\Exceptions\InvalidMagicLinkTokenException;
use App\Domain\Auth\Exceptions\MagicLinkTokenAlreadyUsedException;
use App\Domain\Auth\Exceptions\MagicLinkTokenExpiredException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Verificar Código | Laravertex')]
class VerifyToken extends Component
{
    #[Url]
    public string $email = '';

    #[Url]
    public string $token = '';

    public ?string $errorMessage = null;

    public ?string $successMessage = null;

    public function mount(VerifyMagicLinkTokenAction $verifyAction, Request $request): mixed
    {
        if ($this->email === '') {
            $this->email = (string) session('magic_auth_email', '');
        }

        if ($this->email === '') {
            return $this->redirectRoute('auth.login', navigate: true);
        }

        // If a token was provided in the URL (1-click email link), attempt verification immediately
        if ($this->token !== '') {
            return $this->processVerification($verifyAction, $request);
        }

        return null;
    }

    public function verify(VerifyMagicLinkTokenAction $verifyAction, Request $request): mixed
    {
        return $this->processVerification($verifyAction, $request);
    }

    public function resend(SendMagicLinkAction $sendAction, Request $request): void
    {
        $this->errorMessage = null;
        $this->successMessage = null;

        $throttleKey = 'magic-resend:'.strtolower(trim($this->email)).'|'.$request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 2)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            $this->errorMessage = "Por favor espera {$seconds} segundos antes de solicitar otro código.";

            return;
        }

        RateLimiter::hit($throttleKey, 30);

        $dto = new SendMagicLinkDTO(
            email: $this->email,
            ipAddress: $request->ip(),
            userAgent: $request->userAgent(),
        );

        $sendAction->execute($dto);

        $this->token = '';
        $this->successMessage = 'Hemos enviado un nuevo código de 6 dígitos a tu correo.';
    }

    private function processVerification(VerifyMagicLinkTokenAction $verifyAction, Request $request): mixed
    {
        $this->errorMessage = null;
        $this->successMessage = null;

        $cleanToken = trim($this->token);

        if ($cleanToken === '') {
            $this->errorMessage = 'Por favor ingresa el código de 6 dígitos.';

            return null;
        }

        $throttleKey = 'magic-verify:'.strtolower(trim($this->email)).'|'.$request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            $this->errorMessage = "Demasiados intentos fallidos. Intenta nuevamente en {$seconds} segundos.";

            return null;
        }

        try {
            $dto = new VerifyMagicLinkDTO(
                email: $this->email,
                token: $cleanToken,
            );

            $user = $verifyAction->execute($dto);

            RateLimiter::clear($throttleKey);

            Auth::loginUsingId($user->id, remember: true);

            if ($request->hasSession()) {
                $request->session()->regenerate();
                $request->session()->forget('magic_auth_email');
            }

            return $this->redirectIntended(default: route('dashboard'), navigate: true);
        } catch (InvalidMagicLinkTokenException|MagicLinkTokenExpiredException|MagicLinkTokenAlreadyUsedException $e) {
            RateLimiter::hit($throttleKey, 300);
            $this->errorMessage = $e->getMessage();

            return null;
        }
    }

    public function render(): View
    {
        return view('livewire.auth.verify-token');
    }
}
