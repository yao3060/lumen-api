<?php
/**
 * Created by PhpStorm.
 * User: yao
 * Date: 9/4/18
 * Time: 2:57 PM
 */

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use MikeMcLin\WpPassword\Facades\WpPassword;

class AuthController extends Controller
{

    public function token(Request $request){

        $username = $request->get('username');
        $password = $request->get('password');

        $user = $this->wp_authenticate($username, $password);

        $user_id = $user->ID;
        $user_cache = Cache::get( 'user_tokens_' . $user_id );
        if( !is_null($user_cache) ){
            return response( $user_cache )
                ->header('X-Cache-Server', 'lumen')
                ->header('X-Cache-Info', 'user_tokens_' . $user_id);
        }

        $key = env('JWT_SECRET', false);
        $issuedAt = time();
        $notBefore = $issuedAt;
        $expire = $issuedAt + (3600 * 24 * 365);
        $source_domain = 'http://example.org';
        $open_id = $user->user_login;

        $token = array(
            'iss' => $source_domain,
            'iat' => $issuedAt,
            'nbf' => $notBefore,
            'exp' => $expire,
            'open_id' => $open_id,
            'data' => array(
                'user' => array(
                    'id' => $user_id,
                ),
            ),
        );

        $user = (array)$user;

        $token = JWT::encode($token, $key);
        $user['token'] = $token;
        $user['meta'] = $this->get_usermeta($user_id);

        Cache::put( 'user_token_' . $user_id, $user, 3600 );

        return response( $user );
    }

    /**
     * Try to authenticate the user with the passed credentials
     * @see wp_authenticate_username_password
     * @see wp_authenticate_email_password
     * @see wp_authenticate_spam_check
     */
    public function wp_authenticate($username, $password){

        $user = DB::table('wp_users')->where('user_login', $username)->first();
        if(is_null($user)){
            $user = DB::table('wp_users')->where('user_email', $username)->first();
        }

        if(!is_null($user) && $user->user_status == 1){

            return response([
                'code' => 'jwt_auth_failed',
                'message' => 'User is marked as a closed.',
                "data"=> [ "status" => 403 ]
            ], 403)->send();
        }

        if ( WpPassword::check( $password, $user->user_pass ) ) {
            // Password success!
            unset($user->user_pass);
            unset($user->user_url);
            unset($user->user_registered);
            unset($user->user_activation_key);
            return $user;

        } else {
            // Password failed :( incorrect_password
            return response([
                'code' => 'jwt_auth_failed',
                'message' => 'The password you entered is incorrect.',
                "data"=> [ "status" => 403 ]
            ], 403)->send();
        }

    }

    public function get_usermeta($user_id){

        $wp_usermeta = DB::table('wp_usermeta')
            ->where( 'user_id', '=', $user_id )
            ->whereIn('meta_key', ['source_domain', 'emp_code', 'primary_blog'])
            ->get();

        $meta = [];
        foreach ($wp_usermeta as $value){
            $meta[$value->meta_key] = $value->meta_value;
        }

        return (array)$meta;
    }

}