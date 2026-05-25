<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CheckLogin
{
    public function handle(Request $request, Closure $next)
    {
        if (!Session::has('maNguoiDung')) {
            return redirect()->route('login')
                             ->with('error', 'Vui lòng đăng nhập.');
        }
        return $next($request);
    }
}