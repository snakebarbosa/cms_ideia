<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Model\Artigo::class, function (Faker\Generator $faker) {
    return [
        'alias' => $faker->sentence(3),
        'ativado' => $faker->boolean,
        'destaque' => $faker->boolean,
        'publicar' => $faker->date(),
        'data_criacao' => $faker->date(),
        'despublicar' => $faker->dateTimeBetween('+1 year', '+2 years')->format('Y-m-d'),
        'idCategoria' => 1,
        'keyword' => $faker->words(3, true),
        'idUser' => function () {
            return factory(App\User::class)->create()->id;
        },
        'idImagem' => $faker->numberBetween(1, 10),
        'slug' => $faker->slug,
        'descricao' => $faker->paragraph,
    ];
});

$factory->define(App\Model\Categoria::class, function (Faker\Generator $faker) {
    return [
        'titulo' => $faker->words(2, true),
        'ativado' => 1,
        'artigo' => 1,
        'imagem' => $faker->boolean,
        'documento' => $faker->boolean,
        'faq' => $faker->boolean,
        'link' => $faker->boolean,
        'default' => $faker->boolean,
        'parent' => null,
        'idTipo' => null,
    ];
});

$factory->define(App\Model\Tag::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->word,
    ];
});

$factory->define(App\Model\Documento::class, function (Faker\Generator $faker) {
    return [
        'titulo' => $faker->sentence(3),
        'ficheiro' => $faker->word . '.pdf',
        'ativado' => 1,
        'idCategoria' => 1,
        'idUser' => function () {
            return factory(App\User::class)->create()->id;
        },
    ];
});

$factory->define(App\Model\Conteudo::class, function (Faker\Generator $faker) {
    return [
        'titulo' => $faker->sentence(3),
        'texto' => $faker->paragraph,
        'ativado' => 1,
        'idLanguage' => 1,
        'idArtigo' => function () {
            return factory(App\Model\Artigo::class)->create()->id;
        },
    ];
});

$factory->define(App\Model\Faq::class, function (Faker\Generator $faker) {
    return [
        'alias' => $faker->sentence(3),
        'ativado' => 1,
        'publicar' => $faker->date(),
        'despublicar' => $faker->dateTimeBetween('+1 year', '+2 years')->format('Y-m-d'),
        'idCategoria' => 1,
        'idUser' => function () {
            return factory(App\User::class)->create()->id;
        },
        'slug' => $faker->slug,
    ];
});
