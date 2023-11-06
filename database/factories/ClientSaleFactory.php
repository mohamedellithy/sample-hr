<?php

namespace Database\Factories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ClientSale>
 */
class ClientSaleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'client_id'=>Client::all()->random()->id,
            'amount'=>fake()->numberBetween(10,100),
            'remained'=>fake()->numberBetween(0,50),
            'sale_date'=>fake()->date('Y_m_d'),
        ];
    }
}
