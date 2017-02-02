<?php

use App\User;
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
        $user = new User();
        $user->name = "Bashar Qassis";
        $user->email = "basharqassis9@hotmail.com";
        $user->password = crypt("secret","");
        $user->save();
    }
}
