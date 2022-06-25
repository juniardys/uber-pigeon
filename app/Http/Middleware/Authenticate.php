<?php

namespace App\Http\Middleware;

use Closure;
use App\Traits\ApiResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    use ApiResponse;

    public function handle($request, Closure $next, ...$guards)
    {
        if ($this->authenticate($request, $guards)) {
            return $next($request);
        } else {
            return $this->unauthenticated($request, $guards);
        }
    }

    protected function authenticate($request, array $guards)
    {
        if (empty($guards)) {
            $guards = [null];
        }

        foreach ($guards as $guard) {
            if ($this->auth->guard($guard)->check()) {
                $this->auth->shouldUse($guard);
                return true;
            }
        }

        return false;
    }

    protected function unauthenticated($request, array $guards)
    {
        return $this->responseError('Unauthorized access!', 40100, null, 401);
    }

    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            return route('login');
        }
    }
}
