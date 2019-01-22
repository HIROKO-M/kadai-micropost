<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class Authenticate
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  Guard  $auth
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($this->auth->guest()) {
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            } 
            else {
                 /* 修正 Hiroko
                  * ログインせずに /users などにアクセスした場合には、 
                  * route('login.get') のログインページにリダイレクトされ、
                  * ユーザにログインしなければ見られないことを認識させることが
                  * できます。*/
            
                return redirect()->guest(route('login.get'));  // 修正
            }   
            
        }

        return $next($request);
    }
}
