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
    public function run()
    {
        $faker = Faker::create();

        $user = User::create([
            'name' => 'teste2',
            'email' => 'teste2@gmail.com',
            'contacto' => '123456789',
            'salario' => 3000,
            'password' => bcrypt('12345678'),
        ]);

        $role = Role::firstOrCreate(['name' => 'Admin']);

        $user->assignRole($role);

       /* for ($i = 0; $i < 50; $i++) {
            DB::table('users')->insert([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'contacto' => $faker->phoneNumber,
                'salario' => $faker->numberBetween('0', '5000'),
                'password' => bcrypt('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }*/
    }
}
