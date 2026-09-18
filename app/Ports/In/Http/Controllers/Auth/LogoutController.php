<?php

declare(strict_types=1);

namespace App\Ports\In\Http\Controllers\Auth;

use App\Application\Auth\Actions\LogoutAction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class LogoutController
{
    public function logout(Request $request, LogoutAction $logoutAction): RedirectResponse
    {
        $logoutAction->execute($request);

        return redirect()->route('auth.login');
    }
}
