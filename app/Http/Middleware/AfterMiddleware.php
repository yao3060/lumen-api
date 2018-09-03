<?php
/**
 * Created by PhpStorm.
 * User: YAO
 * Date: 2018/9/4
 * Time: 6:34
 */

namespace App\Http\Middleware;

use Closure;

class AfterMiddleware
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

        $response = $next($request);

        return $response;

    }
}