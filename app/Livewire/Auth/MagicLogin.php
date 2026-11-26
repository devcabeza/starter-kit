<?php

declare(strict_types=1);

namespace App\Livewire\Auth;

use App\Application\Auth\Actions\SendMagicLinkAction;
use App\Application\Auth\DTOs\SendMagicLinkDTO;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Iniciar Sesión | Laravertex')]
class MagicLogin extends Component
{
    #[Validate('required|email|max:255', message: [
        'required' => 'El correo electrónico es obligatorio.',
        'email' => 'Por favor introduce un correo electrónico válido.',
        'max' => 'El correo electrónico no puede superar los 255 caracteres.',
    ])]
    public string $email = '';

    public string $honeypot = '';

    public function submit(SendMagicLinkAction $sendMagicLinkAction, Request $request): mixed
    {
        if ($this->honeypot !== '') {
            // Silently discard automated bot submissions
            return $this->redirectRoute('home', navigate: true);
        }

        $this->validate();

        $throttleKey = 'magic-link:'.strtolower(trim($this->email)).'|'.$request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            throw ValidationException::withMessages([
                'email' => "Demasiadas solicitudes. Por favor espera {$seconds} segundos.",
            ]);
        }

        RateLimiter::hit($throttleKey, 60);

        $dto = new SendMagicLinkDTO(
            email: $this->email,
            ipAddress: $request->ip(),
            userAgent: $request->userAgent(),
        );

        $sendMagicLinkAction->execute($dto);

        session(['magic_auth_email' => strtolower(trim($this->email))]);

        return $this->redirectRoute('auth.verify', ['email' => strtolower(trim($this->email))], navigate: true);
    }

    public function render(): View
    {
        return view('livewire.auth.magic-login');
    }
}
