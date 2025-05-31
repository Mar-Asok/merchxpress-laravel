<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'api/login',     // Exclude login
        'api/register',  // Exclude register
        'api/logout',    // Exclude logout (if you have one)
        // If you have a password reset endpoint:
        // 'api/forgot-password',
        // 'api/reset-password',
    ];

}
