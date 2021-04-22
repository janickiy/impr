<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'firstname' => 'Иван',
			'lastname' => 'Иванов',
            'role' => 0 ,
            'email' => 'janickiy@mail.ru',
            'password' => app('hash')->make('1234567'),
        ]);
    }
}
