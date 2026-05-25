<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
class CheckRole
{
    public function handle(Request $request, Closure $next, string $role)
    {
        if (Session::get('role') !== $role) {
            abort(403, 'Bạn không có quyền truy cập trang này.');
        }
        return $next($request);
    }
}
