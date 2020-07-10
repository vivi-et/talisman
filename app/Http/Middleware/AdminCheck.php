<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Closure;

class AdminCheck
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

    if ($request->path() == 'app/admin_login') {

      return $next($request);
    }


    if (!Auth::check()) {

      return response()->json([
        'msg' => 'You are not authorized',
        'url' => $request->path()
      ], 403);
    }

    $user = Auth::user();

    if ($user->userType === 'User') {
      return response()->json([
        'msg' => 'You are not authorized'
      ], 403);
    }
    return $next($request);
  }


  // Laravel ACL (access control list)
  /*
  role
  id, roleName, permission 등 => jsonData
  1  , Editor , [{name: tag, read: true, write: false, update: false, delete: false}] 리눅스 rxw 같은 권한 설정 가능
                    == Editor 는  tag라는 compoenent(:데이터)에 대해서 r-- 만 가능함
  */
}
