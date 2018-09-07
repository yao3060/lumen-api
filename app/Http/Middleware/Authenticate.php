<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;
/** Requiere the JWT library. */
use \Firebase\JWT\JWT;
use \Firebase\JWT\ExpiredException;
use \Firebase\JWT\InvalidArgumentException;
use \Firebase\JWT\UnexpectedValueException;
use \Firebase\JWT\SignatureInvalidException;

class Authenticate
{
    /**
     * The authentication guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {

            $auth = $request->header('AUTHORIZATION');

            if ( !$auth ) {
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
            $secret_key = env('JWT_AUTH_SECRET_KEY', false);
            if (!$secret_key) {

                return response()->json(
                    [
                        'code' => 'jwt_auth_bad_config',
                        'message' => 'JWT is not configurated properly, please contact the admin.'
                    ],
                    403
                );

            }


            //dd($token);
            /** Try to decode the token */
            try {

                $token = JWT::decode($token, $secret_key, array('HS256'));

                /** The Token is decoded now validate the iss */
                if ($token->iss != "http://ep-api.local") {
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

                //$user = User::find();
                $request->user = new \stdClass();
                $request->user->ID = $token->data->user->id;

                //$this->events->fire('firebase.jwt.valid', $user);

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

            } catch ( SignatureInvalidException $exception) {

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

        //        if ($this->auth->guard($guard)->guest()) {   }
    }
}
