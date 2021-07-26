<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Eventos;
use App\Models\IdTitulo;
use App\Models\IdUser;

class EventosFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Eventos::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id_titulo' => IdTitulo::factory(),
            'id_user' => IdUser::factory(),
            'modalidad' => $this->faker->word,
            'cargah' => $this->faker->word,
            'fechainicio' => $this->faker->date(),
            'fechafin' => $this->faker->date(),
            'estado' => $this->faker->word,
        ];
    }
}
