<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $voti = [];
        $materie = ['italiano', 'inglese', 'storia', 'geografia'];
        foreach ($materie as $materia) {
            $voti[] = [
                'materia' => $materia,
                'voto' => $this->faker->numberBetween(5, 10),
            ];

            $voti[] = [
                'materia' => $materia,
                'voto' => $this->faker->numberBetween(5, 10),
            ];
        }
        return [
            'number' => $this->faker->bothify('MAT#####'),
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'birthdate' => $this->faker->date,
            'voti' => $voti,
        ];
    }
}
