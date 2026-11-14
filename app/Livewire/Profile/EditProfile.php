<?php

declare(strict_types=1);

namespace App\Livewire\Profile;

use App\Application\User\Actions\UpdateProfileInformationAction;
use App\Application\User\DTOs\UpdateProfileDTO;
use App\Domain\User\Exceptions\EmailAlreadyInUseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Mi Perfil | Laravertex')]
class EditProfile extends Component
{
    public string $name = '';

    public string $email = '';

    public bool $saved = false;

    public function mount(): void
    {
        $user = Auth::user();

        if ($user) {
            $this->name = $user->name;
            $this->email = $user->email;
        }
    }

    public function save(UpdateProfileInformationAction $updateProfileAction): void
    {
        $this->reset('saved');

        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'name.max' => 'El nombre no puede superar los 255 caracteres.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Por favor introduce un correo electrónico válido.',
            'email.max' => 'El correo electrónico no puede superar los 255 caracteres.',
        ]);

        $currentUser = Auth::user();

        try {
            $dto = new UpdateProfileDTO(
                userId: $currentUser->id,
                name: $this->name,
                email: $this->email,
            );

            $updateProfileAction->execute($dto);

            $this->saved = true;
        } catch (EmailAlreadyInUseException $e) {
            throw ValidationException::withMessages([
                'email' => $e->getMessage(),
            ]);
        }
    }

    public function render(): View
    {
        return view('livewire.profile.edit-profile', [
            'user' => Auth::user(),
        ]);
    }
}
