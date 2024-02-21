<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Laravel\Passport\Http\Middleware\CheckClientCredentials;
use Symfony\Component\HttpFoundation\Response;

class ClientOrAuthApi
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            return app(Authenticate::class)->handle($request, $next, 'api');
        } catch (AuthenticationException $e) {
            return app(CheckClientCredentials::class)->handle($request, $next);
        }
    }
}
