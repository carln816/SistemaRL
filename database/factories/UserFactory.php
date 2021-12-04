<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/
$factory->define(App\Persona::class, function(){
    return [
        'id'=> '1',
        'nombre'=> 'Demo',
        'tipo_documento' => 'Dni',
        'num_documento'=>'1111111',
        'direccion' => 'Upala', 
        'telefono' => '85858585', 
        'email' => 'demo@gmail.com', 
    ];
});

$factory->define(App\User::class, function (Faker $faker) {
    return [
        'idpersona' => 1,
        'idrol' =>1,
        'usuario' => 'demo',
        'password' => bcrypt('demo'),
        'remember_token' => str_random(10),
    ];
});
