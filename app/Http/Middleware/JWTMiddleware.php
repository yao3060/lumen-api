<?php
/**
 * Created by PhpStorm.
 * User: YAO
 * Date: 2018/9/4
 * Time: 6:20
 */

namespace App\Http\Middleware;

use Closure;

/** Requiere the JWT library. */
use \Firebase\JWT\JWT;
use \Firebase\JWT\ExpiredException;
use \Firebase\JWT\InvalidArgumentException;
use \Firebase\JWT\UnexpectedValueException;

class JWTMiddleware
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
        /*
         * Looking for the HTTP_AUTHORIZATION header, if not present just
         * return the user.
         * for test: $auth = 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9jZHBzc28yLnVhdC5jZHB5dW4uY29tIiwiaWF0IjoxNTM2MDI5NDY2LCJuYmYiOjE1MzYwMjk0NjYsImV4cCI6MTU5OTcwNjI2Niwib3Blbl9pZCI6Indlc3Rhcl9jZHBzc28yXzk4MzA5OGJlYWM0ODExZThhMTM0MDAwYzI5YTk3MDgyIiwiZGF0YSI6eyJ1c2VyIjp7ImlkIjoiMTUxMzcifX19.RWLypofoQAk1JrMLlilXCuTQHzfeRD159QaqXB_V_to';
         */
        $auth = isset($_SERVER['HTTP_AUTHORIZATION']) ?  $_SERVER['HTTP_AUTHORIZATION'] : false;

        /* Double check for different auth header string (server dependent) */
        if (!$auth) {
            $auth = isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION']) ?  $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] : false;
        }

        if (!$auth) {

            return response()->json(
                [
                    'code' => 'jwt_auth_no_auth_header',
                    'message' => 'Authorization header not found.'
                ],
                403
            );
        }

        /**
         * The HTTP_AUTHORIZATION is present verify the format
         * if the format is wrong return the user.
         */
        list($token) = sscanf($auth, 'Bearer %s');
        if (!$token) {

            return response()->json(
                [
                    'code' => 'jwt_auth_bad_auth_header',
                    'message' => 'Authorization header malformed.'
                ],
                403
            );
        }

        /** Get the Secret Key */
        $secret_key = defined('JWT_AUTH_SECRET_KEY') ? JWT_AUTH_SECRET_KEY : false;
        if (!$secret_key) {

            return response()->json(
                [
                    'code' => 'jwt_auth_bad_config',
                    'message' => 'JWT is not configurated properly, please contact the admin.'
                ],
                403
            );

        }

        /** Try to decode the token */
        try {

            $token = JWT::decode($token, $secret_key, array('HS256'));

            /** The Token is decoded now validate the iss */
            if ($token->iss != 'http://cdpsso2.uat.cdpyun.com') {
                /** The iss do not match, return error */
                return response()->json(
                    [
                        'code' => 'jwt_auth_bad_iss',
                        'message' => 'The iss do not match with this server.',
                        'data' => $token
                    ],
                    403
                );
            }
            /** So far so good, validate the user id in the token */
            if (!isset($token->data->user->id)) {
                /** No user id in the token, abort!! */
                return response()->json(
                    [
                        'code' => 'jwt_auth_bad_request',
                        'message' => 'User ID not found in the token'
                    ],
                    403
                );

            }

            set_current_user( $token->data->user->id );

            /** If the output is true return an answer to the request to show it */
            return $next($request);

        } catch ( InvalidArgumentException $exception) {

            return response()->json(
                [
                    'code' => 'jwt_auth_invalid_token',
                    'message' => $exception->getMessage()
                ],
                403
            );

        } catch ( UnexpectedValueException $exception) {

            return response()->json(
                [
                    'code' => 'jwt_auth_invalid_token',
                    'message' => $exception->getMessage()
                ],
                403
            );

        } catch ( ExpiredException $exception ) {

            return response()->json(
                [
                    'code' => 'jwt_auth_invalid_token',
                    'message' => $exception->getMessage()
                ],
                403
            );

        } catch (Exception $exception) {
            /** Something is wrong trying to decode the token, send back the error */
            return response()->json(
                [
                    'code' => 'jwt_auth_invalid_token',
                    'message' => $exception->getMessage()
                ],
                403
            );
        }

    }


    /**
     * Add CORs suppot to the request.
     */
    public function add_cors_support()
    {
        $enable_cors = defined('JWT_AUTH_CORS_ENABLE') ? JWT_AUTH_CORS_ENABLE : false;
        if ($enable_cors) {
            header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Authorization');
        }
    }
}