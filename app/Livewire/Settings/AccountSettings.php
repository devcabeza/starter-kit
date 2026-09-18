<?php

declare(strict_types=1);

namespace App\Livewire\Settings;

use App\Application\User\Actions\DeleteAccountAction;
use App\Application\User\DTOs\DeleteAccountDTO;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Configuraciones | Laravertex')]
class AccountSettings extends Component
{
    public function deleteAccount(DeleteAccountAction $deleteAccountAction, Request $request): mixed
    {
        $user = Auth::user();

        if (! $user) {
            return $this->redirectRoute('login', navigate: true);
        }

        $userId = $user->id;

        Auth::logout();

        $deleteAccountAction->execute(new DeleteAccountDTO($userId));

        if ($request->hasSession()) {
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return $this->redirectRoute('home', navigate: true);
    }

    public function render(): View
    {
        return view('livewire.settings.account-settings', [
            'user' => Auth::user(),
        ]);
    }
}
