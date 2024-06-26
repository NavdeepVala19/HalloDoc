<?php

namespace App\Http\Middleware;

use App\Models\UserRoles;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminOrProvider
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
            if ($roleId === 1 || $roleId === 2) {
                return $next($request);
            }
        }
        return redirect()->route('login');
    }
}
