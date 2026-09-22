<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForcePasswordChange
{
    /**
     * Jika user memiliki flag must_change_password = true,
     * paksa redirect ke halaman ganti password.
     * Kecualikan route profile.edit dan logout agar tidak loop.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (
            $user &&
            $user->must_change_password &&
            ! $request->routeIs('profile.edit', 'profile.update', 'password.update', 'logout')
        ) {
            return redirect()
                ->route('profile.edit')
                ->with('warning', 'Harap ganti kata sandi Anda sebelum melanjutkan.');
        }

        return $next($request);
    }
}
