<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
       $roleId = Role::where(['name' => 'Admin'])->first();

       $user1 = User::create([
           'first_name' => 'Admin',
           'last_name' => 'Admin',
           'email' => 'admin@test.com',
           'email_verified_at' => '2022-03-31 00:00:00',
           'role_id' => $roleId->id,
           'password' => Hash::make('Test1234'),
       ]);

       $roleId = Role::where(['name' => 'User'])->first();
       $user1 = User::create([
           'first_name' => 'User',
           'last_name' => 'User',
           'email' => 'user@test.com',
           'email_verified_at' => '2022-03-31 00:00:00',
           'role_id' => $roleId->id,
           'password' => Hash::make('Test1234'),
       ]);
    }
}
