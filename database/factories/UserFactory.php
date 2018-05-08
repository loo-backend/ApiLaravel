<?php

use Faker\Generator as Faker;
use Webpatser\Uuid\Uuid;

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

function matrizRoles() {

    $rand = rand(0, 3);
    $roles = [];

    if($rand === 3) {

        $roles[0]['role'] = 'ADMIN';
        $roles[0]['permissions'][0]['permission'] = ['ALL'];

    } elseif($rand=== 2) {

        $roles[0]['role'] = 'ADMIN_STAFF';
        $roles[0]['permissions'][0]['permission'] = ['BROWSER','CREATE', 'READ', 'UPDATE'];

    } elseif($rand=== 1) {

        $roles[0]['role'] = 'CLINIC_ADMIN';
        $roles[0]['permissions'][0]['permission'] = ['ALL'];


    } else {

        $roles[0]['role'] = 'CLINIC_STAFF';
        $roles[0]['permissions'][0]['permission'] = ['BROWSER','CREATE', 'READ', 'UPDATE'];

        $roles[1]['role'] = 'CLINIC_DOCTOR';
        $roles[1]['permissions'][0]['permission'] = ['BROWSER','CREATE', 'READ', 'UPDATE'];

    }

    return $roles;

}


$factory->define(App\User::class, function (Faker $faker) {


    return [

        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => bcrypt('secret'), // secret
        'uuid' => Uuid::generate()->string,
        'roles' => matrizRoles(),

    ];

});

$factory->define(App\Produto::class, function (Faker $faker) {

    return [
        '_id' => $faker->uuid,
        'titulo' => $faker->name,
        'fabricante' => $faker->company,
        'preco' => $faker->randomNumber(3)

    ];

});

$factory->define(App\Pedido::class, function (Faker $faker) {

    return [
        '_id' => $faker->uuid,
        'usuario' => rand(1,3),
        'produto_id' => function () {
            return factory(App\Produto::class)->create()->id;
        },
        'quantidade' => $faker->randomNumber(2)

    ];

});
