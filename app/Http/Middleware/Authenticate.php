<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    protected function redirectTo(Request $request): ?string
    {
        if ($request->expectsJson()) {
            return null;
        }

        if ($request->is('backend/*')) {
            return route('backend.login');
        } elseif ($request->is('frontend/*')) {
            return route('frontend.login');
        }

        return route('backend.login');
    }
}
