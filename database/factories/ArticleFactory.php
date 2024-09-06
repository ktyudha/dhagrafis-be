<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence();
        $body = fake()->paragraphs(10, true);
        $is_published = fake()->boolean();
        $published_by = null;
        $published_at = null;

        [$sadmin, $admin] = User::oldest()->limit(2)->get();
        $categoriesIds = Category::all()->pluck('id');

        if ($is_published) {
            $published_at = now();
            $published_by = fake()->randomElement([$admin->id, $sadmin->id]);
        }

        return [
            'title' => $title,
            'slug' => Str::slug($title, dictionary: ['&' => 'and']),
            'image' => fake()->imageUrl(),
            'excerpt' => Str::limit($body),
            'body' => $body,
            'category_id' => fake()->randomElement($categoriesIds),
            'published_by' => $published_by,
            'published_at' => $published_at,
        ];
    }
}
