<?php

use App\Model\Imagem;
use App\Model\Categoria;
use Faker\Generator as Faker;

$factory->define(Imagem::class, function (Faker $faker) {
    return [
        'titulo' => $faker->words(3, true) . '.jpg',
        'url' => $faker->uuid . '.jpg',
        'ativado' => 1,
        'idCategoria' => function () {
            return factory(Categoria::class)->create([
                'categoria_tipo' => 'imagem',
                'ativado' => 1
            ])->id;
        },
    ];
});

$factory->state(Imagem::class, 'active', [
    'ativado' => 1,
]);

$factory->state(Imagem::class, 'inactive', [
    'ativado' => 0,
]);
