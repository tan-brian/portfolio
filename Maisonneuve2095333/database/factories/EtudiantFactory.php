<?php

namespace Database\Factories;

use App\Models\Etudiant;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Ville;

class EtudiantFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Etudiant::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $villeIds = Ville::all()->pluck('id')->toArray();
        
        return [
            'nom' => $this->faker->name(),
            'adresse' => $this->faker->address(),
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->email(),
            'daten_naissance' => $this->faker->date(),
            'ville_id' => $this->faker->randomElement($villeIds) 
        ];
    }
}
