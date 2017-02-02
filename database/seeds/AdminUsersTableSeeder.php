<?php

use App\AdminUser;
use Illuminate\Database\Seeder;

class AdminUsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new AdminUser();
        $user->name = "Admin Qassis";
        $user->email = "basharqassis9@gmail.com";
        $user->password = crypt("secret","");
        $user->save();
    }
}
