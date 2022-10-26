<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Produto>
 */
class ProdutoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'nome' => fake()->regexify('[A-Za-z0-9]{' . mt_rand(3, 60) . '}'),
            'valor' => fake()->randomNumber(2, true),
            'loja_id' => 1,
            'ativo' => fake()->boolean()
        ];
    }
}
