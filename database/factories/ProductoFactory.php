<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Producto;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Producto>
 */
class ProductoFactory extends Factory {

    protected $model = Producto::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categoriasId = [1,2,3];
        $espacios = ['living', 'comedor', 'cocina', 'dormitorio', 'baño'];
        $dimensiones = ['pequeño', 'mediano', 'grande'];
        $nombres = ['mesa', 'silla', 'sofá', 'cama', 'escritorio', 'estantería', 'armario', 'cómoda', 'mesilla', 'tocador'];

        return [
            'name' => $this->faker->randomElement($nombres),
            'sku' => $this->faker->unique()->randomNumber(8),
            'description' => $this->faker->text(100),
            'espacio' => $this->faker->randomElement($espacios),
            'dimensiones' => $this->faker->randomElement($dimensiones),
            'categoria_id' => $this->faker->randomElement($categoriasId),
        ];
    }
}
