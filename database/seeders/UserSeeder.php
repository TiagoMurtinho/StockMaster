<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = [
            [
                'nome' => 'teste',
                'email' => 'teste@gmail.com',
                'password' => '12345678'
            ],
            [
                'nome' => 'teste2',
                'email' => 'teste2@gmail.com',
                'password' => '12345678'
            ],
        ];

        foreach ($user as $userData) {
            User::create($userData);
        }
    }
}
