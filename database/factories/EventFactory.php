<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->sentence();
        $is_published = fake()->boolean();
        $published_by = null;
        $published_at = null;
        [$sadmin, $admin] = User::oldest()->limit(2)->get();

        if ($is_published) {
            $published_at = now();
            $published_by = fake()->randomElement([$admin->id, $sadmin->id]);
        }

        return [
            'name' => $name,
            'slug' => \Illuminate\Support\Str::slug($name),
            'poster' => fake()->imageUrl(),
            'start_date' => fake()->dateTimeThisYear(),
            'end_date' => fake()->dateTimeThisYear(),
            'location' => fake()->address(),
            'description' => fake()->text(),
            'published_at' => $published_at,
            'published_by' => $published_by
        ];
    }
}
