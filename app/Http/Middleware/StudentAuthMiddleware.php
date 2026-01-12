<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StudentAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if student is logged in
        if (!session('student_logged_in')) {
            return redirect('/loginStudent')->withErrors(['auth' => 'Please login to access this page']);
        }

        return $next($request);
    }
}
