<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $allowedRoles = collect($roles)
            ->map(fn (string $role) => UserRole::tryFrom(strtolower($role)))
            ->filter()
            ->all();

        abort_unless(
            $request->user() && in_array($request->user()->role, $allowedRoles, true),
            403,
        );

        return $next($request);
    }
}
