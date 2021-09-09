<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Fuentes;

class FuentesFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Fuentes::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nombre' => $this->faker->regexify('[A-Za-z0-9]{20}'),
            'descripcion' => $this->faker->regexify('[A-Za-z0-9]{50}'),
            'cuenta' => $this->faker->regexify('[A-Za-z0-9]{30}'),
            'estado' => $this->faker->boolean,
        ];
    }
}
