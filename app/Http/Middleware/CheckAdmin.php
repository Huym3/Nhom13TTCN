<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CheckAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Session::has('maNguoiDung')) {
            return redirect()->route('admin.login')->with('error', 'Vui lòng đăng nhập Admin.');
        }

        if (Session::get('role') !== 'Admin') {
            Session::flush();
            return redirect()->route('login')->with('error', 'Bạn không có quyền truy cập.');
        }

        return $next($request);
    }
}
