<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\View;

class AuthAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = auth()->guard('teacher')->user() ?? auth()->guard('student')->user();
        if ($user) {
            View::share('user', $user['original']);
            return $next($request);
        }
        $callback = urlencode(env('APP_URL'));
        return redirect('http://login.kq.cc/?callback='.$callback);
    }
}
