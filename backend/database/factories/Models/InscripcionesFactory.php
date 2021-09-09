<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\IdEstudiante;
use App\Models\IdEvento;
use App\Models\IdFuente;
use App\Models\Inscripciones;

class InscripcionesFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Inscripciones::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id_evento' => IdEvento::factory(),
            'id_estudiante' => IdEstudiante::factory(),
            'id_fuente' => IdFuente::factory(),
            'estado' => $this->faker->regexify('[A-Za-z0-9]{20}'),
            'nota' => $this->faker->numberBetween(-10000, 10000),
            'monto' => $this->faker->numberBetween(-10000, 10000),
            'deposito' => $this->faker->regexify('[A-Za-z0-9]{20}'),
        ];
    }
}
