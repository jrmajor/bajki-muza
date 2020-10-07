<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    public function run()
    {
        $user = new User();
        $user->username = 'maksiuP';
        $user->password = Hash::make('password');
        $user->save();
    }
}
