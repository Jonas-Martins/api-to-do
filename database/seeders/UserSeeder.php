<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([ // criar os dados que vão para o banco
            'name' => 'Jonas Martins',
            'email' => 'jonasblz@outlook.com',
            'password' => Hash::make('1234')
        ]);
    }
}
