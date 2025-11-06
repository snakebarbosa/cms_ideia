<?php

namespace Database\Factories;

use App\Model\Categoria;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoriaFactory extends Factory
{
    protected $model = Categoria::class;

    public function definition()
    {
        return [
            'titulo' => $this->faker->words(2, true),
            'categoria_tipo' => $this->faker->randomElement(['artigo', 'documento', 'imagem', 'faq', 'evento', 'link']),
            'parent' => 0,
            'slug' => $this->faker->slug,
            'ativado' => $this->faker->boolean(80), // 80% chance of being active
            'order' => $this->faker->numberBetween(1, 100),
            'default' => 0,
        ];
    }

    /**
     * Create a parent category (level 1)
     */
    public function parent()
    {
        return $this->state(function (array $attributes) {
            return [
                'parent' => 0,
            ];
        });
    }

    /**
     * Create a child category (level 2)
     */
    public function child($parentId = null)
    {
        return $this->state(function (array $attributes) use ($parentId) {
            return [
                'parent' => $parentId ?? 1,
            ];
        });
    }

    /**
     * Create an active category
     */
    public function active()
    {
        return $this->state(function (array $attributes) {
            return [
                'ativado' => 1,
            ];
        });
    }

    /**
     * Create an inactive category
     */
    public function inactive()
    {
        return $this->state(function (array $attributes) {
            return [
                'ativado' => 0,
            ];
        });
    }

    /**
     * Create a default category
     */
    public function default()
    {
        return $this->state(function (array $attributes) {
            return [
                'default' => 1,
                'parent' => 0,
            ];
        });
    }

    /**
     * Create category for specific type
     */
    public function ofType($type)
    {
        return $this->state(function (array $attributes) use ($type) {
            return [
                'categoria_tipo' => $type,
            ];
        });
    }

    /**
     * Create category with specific order
     */
    public function withOrder($order)
    {
        return $this->state(function (array $attributes) use ($order) {
            return [
                'order' => $order,
            ];
        });
    }
}