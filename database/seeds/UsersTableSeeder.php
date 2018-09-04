<?php
/**
 * Created by PhpStorm.
 * User: yao
 * Date: 9/4/18
 * Time: 2:12 PM
 */

use Illuminate\Database\Seeder;
class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // create 10 users using the user factory
        factory(App\User::class, 10)->create();
    }
}