<?php

namespace App\Http\Middleware;

use Closure;
use App\Http\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiAuth
{
    use ResponseTrait;

    /**
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header('token') ?? null;
        if (!$this->isValidToken($token)) {
            return $this->unauthorizedResponse();
        }
        return $next($request);
    }

    private function isValidToken(?string $token): bool
    {
        return $token == env('API_AUTH_TOKEN');
    }
}
