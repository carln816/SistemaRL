<?php

use Illuminate\Database\Seeder;
use App\Persona;
use App\User;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //$this->call(UsersTableSeeder::class);
        factory(Persona::class)->create();
        factory(User::class)->create();
    }
}
