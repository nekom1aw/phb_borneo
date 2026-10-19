<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class cmslogin
{
    /**
     * Halaman CMS hanya untuk akun yang sudah login (dan role yang diizinkan).
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // belum login
        if (! session()->has('account_id')) {
            return redirect()->route('login');
        }

        // role user
        $userRole = session('account_role');

        // cek role
        if (! empty($roles) && ! in_array($userRole, $roles)) {
            abort(403);
        }

        return $next($request);
    }
}
