<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): Response
    {
        $request->validate([
            'username' => ['required', 'string'], // Expect username or email
            'password' => ['required', 'string'],
        ]);

        // Attempt to authenticate using 'username' or 'email'
        // This allows users to login with either their username or email
        if (! Auth::attempt(['username' => $request->username, 'password' => $request->password], $request->boolean('remember')) &&
            ! Auth::attempt(['email' => $request->username, 'password' => $request->password], $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'username' => trans('auth.failed'), // Use the default authentication failed message
            ]);
        }

        $request->session()->regenerate();

        return response()->noContent(); // Return 204 No Content for successful login
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): Response
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response()->noContent(); // Return 204 No Content for successful logout
    }
}