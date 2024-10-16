<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $user = User::create([
            'name' => 'admin',
            'email' => 'admin@exemple.com',
            'password' => bcrypt('password'),
        ]);

        $role = Role::firstOrCreate(['name' => 'Admin']);

        $user->assignRole($role);

    }
}
