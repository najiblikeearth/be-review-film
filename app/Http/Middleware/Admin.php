<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Roles;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        $roleAdmin = Roles::where('name', 'admin')->first();
        if ($user->role_id != $roleAdmin->id) {
            return response()->json([
                'message' => "User tidak dapat mengakses halaman ini, yang bisa mengakses hanya Admin"
            ], 403);
        }

        return $next($request);
    }
}
