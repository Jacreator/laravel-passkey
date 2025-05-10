<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Passkey>
 */
class PasskeyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'credential_id' => $this->faker->uuid(),
            'data' => json_encode([
                'id' => $this->faker->uuid(),
                'rawId' => $this->faker->uuid(),
                'response' => [
                    'attestationObject' => $this->faker->text(),
                    'clientDataJSON' => $this->faker->text(),
                ],
            ]),
            'user_id' => User::factory(),
        ];
    }
}
