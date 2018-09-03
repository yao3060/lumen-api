<?php
/**
 * Created by PhpStorm.
 * User: YAO
 * Date: 2018/9/4
 * Time: 6:20
 */

namespace App\Http\Middleware;

use Closure;

class CheckToken
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

        if ( $request->input('token') != '111') {

            return response()->json(
                [
                    'error' => 'Unauthorized',
                    'message' => 'Unauthorized'
                ],
                401,
                ['X-Header-One' => 'Header Value']
            );

        }
        return $next($request);

    }
}