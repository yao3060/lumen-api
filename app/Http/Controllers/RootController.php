<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use App\Events\ExampleEvent;

class RootController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function index(Request $request){

    }

    public function json(Request $request){

        $cache_key = 'root_json';

//        $response = Cache::get($cache_key);
//
//        if( !is_null($response) ){
//            return response( $response )
//                ->header('X-Cache-Server', 'lumen')
//                ->header('X-Cache-Info', $cache_key );
//        }

        $encrypt = Crypt::encrypt('Hello world.');
        $response = [
            'encrypt' => $encrypt,
            'decrypted' => Crypt::decrypt($encrypt),
            'name' => 'Company Name',
            'description' => 'Company Description',
            'url' => '',
            'home' => '',
            'gmt_offset' => 8,
            'timezone_string' => "Asia/Shanghai",
            'namespaces' => [
                'jwt-auth/v1',
                'post/v1'
            ],
            'authentication' => [],
            'routes' => Route::getRoutes(),


        ];

        event(new ExampleEvent);

        return response( $response );

    }
    //
}
