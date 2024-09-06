<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'image' => asset($this->image),
            'slug' => $this->slug,
            'excerpt' => $this->whenHas('excerpt'),
            'body' => $this->whenHas('body'),
            'category' => $this->whenLoaded('category', fn() => [
                'id' => $this->category->id,
                'slug' => $this->category->slug,
                'name' => $this->category->name,
            ]),
            'author' => $this->whenLoaded('author', fn() => [
                'id' => $this->author->id,
                'name' => $this->author->name,
                'email' => $this->author->email,
                'photo' => $this->author->photo ? asset($this->author->photo) : null,
            ]),
            'publisher' => $this->whenLoaded('publisher', fn() => [
                'id' => $this->author->id,
                'name' => $this->author->name,
                'email' => $this->author->email,
                'photo' => $this->author->photo ? asset($this->author->photo) : null,
            ]),
            'published_at' => $this->published_at?->format('d M Y'),
        ];
    }
}
