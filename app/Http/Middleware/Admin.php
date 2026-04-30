<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Admin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || !auth()->user()->is_admin) {
            return redirect()->route('login')
                ->with('error', '관리자만 접근할 수 있습니다.');
        }

        return $next($request);
    }
}
