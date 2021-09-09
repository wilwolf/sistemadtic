<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Estudiantes;
use App\Models\Extension;

class EstudiantesFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Estudiantes::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'carnet' => $this->faker->numberBetween(-100000, 100000),
            'complemento' => $this->faker->regexify('[A-Za-z0-9]{20}'),
            'extension' => Extension::factory(),
            'nombre' => $this->faker->regexify('[A-Za-z0-9]{50}'),
            'apellidos' => $this->faker->regexify('[A-Za-z0-9]{50}'),
            'telefono' => $this->faker->numberBetween(-100000, 100000),
            'email' => $this->faker->safeEmail,
            'imagen' => $this->faker->regexify('[A-Za-z0-9]{255}'),
        ];
    }
}
