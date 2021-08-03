<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Departamentos;

class DepartamentosFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Departamentos::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nombre' => $this->faker->regexify('[A-Za-z0-9]{50}'),
            'acro' => $this->faker->regexify('[A-Za-z0-9]{5}'),
        ];
    }
}
