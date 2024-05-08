<?php

namespace App\Http\Middleware;

use App\Models\UserRoles;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class checkAdminLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $userId = Auth::user()->id;
            $roleId = UserRoles::where('user_id', $userId)->first()->role_id;
            if ($roleId == 1) {
                return $next($request);
            } else {
                return redirect()->route('login');
            }
        }

        return redirect()->route('login');
    }
}