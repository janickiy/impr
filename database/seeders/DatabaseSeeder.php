<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
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
