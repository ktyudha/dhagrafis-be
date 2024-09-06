<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArtworkResource extends JsonResource
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
            'image' => asset($this->image),
            'title' => $this->title,
            'slug' => $this->slug,
            'caption' => $this->caption,
            'author' => $this->whenLoaded('author', fn () => [
                'id' => $this->author->id,
                'name' => $this->author->name,
                'email' => $this->author->email,
                'photo' => $this->author->photo ? asset($this->author->photo) : null,
            ]),
            'publisher' => $this->whenLoaded('publisher', fn () => [
                'id' => $this->author->id,
                'name' => $this->author->name,
                'email' => $this->author->email,
                'photo' => $this->author->photo ? asset($this->author->photo) : null,
            ]),
            'published_at' => $this->published_at?->format('d M Y'),
        ];
    }
}
