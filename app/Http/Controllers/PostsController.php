<?php
/**
 * Created by PhpStorm.
 * User: yao
 * Date: 9/6/18
 * Time: 10:51 AM
 */

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostsController extends Controller
{

    public function list( Request $request ){

        $user = DB::table('wp_usermeta')
            ->where('user_id', $request->user->ID)
            ->where('meta_key', 'wp_capabilities')
            ->value('meta_value');

        /**
         * @see maybe_unserialize()
         */
        $user = maybe_unserialize( $user );

        $posts = DB::table('wp_5_posts')
            ->where('post_type', 'post')
            ->offset(0)
            ->where('post_status', 'publish')
            ->limit(5)
            ->get();

        $response = $posts;

        return response( $response );

    }

}