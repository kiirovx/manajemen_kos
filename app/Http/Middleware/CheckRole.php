<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // dd($request->user(), $role);
        if (! $request->user()) {
            return redirect()->route('login.page');
        }

        if ($request->user()->role !== $role) {

            return redirect()->route(
                $request->user()->role === 'admin' ? 'dashboard.admin' : 'dashboard.users'
            );
        }

        return $next($request);
    }
}
