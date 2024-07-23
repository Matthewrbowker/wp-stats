<?php

namespace Database\Factories;

use App\Models\Performer;
use App\Models\Year;
use Illuminate\Database\Eloquent\Factories\Factory;

class YearFactory extends Factory
{
    protected $model = Year::class;

    public function definition()
    {
        return [
            'performer_id' => Performer::make(),
            'year' => $this->faker->year,
            'won' => $this->faker->boolean
        ];
    }
}
