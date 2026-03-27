<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckBookAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $book = $request->route('book');

        if (!$book || !$book->subscription) {

            return $next($request);
        }

        $user = $request->user();
        if (!$user || !$user->hasPermissionTo('premium')) {
            return redirect()->route('anggota.subscriptions')->with('gagal', 'Kamu tidak memiliki akses premium');
        }
        return $next($request);
    }
}
