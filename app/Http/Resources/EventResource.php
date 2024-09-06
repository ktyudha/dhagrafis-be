<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    /**
     * The "data" wrapper that should be applied.
     *
     * @var string|null
     */
    public static $wrap = 'event';

    /**
     * Transform the resource into an array.
     *
     * @return array<string,
     *  mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'poster' => asset($this->poster),
            'start_date' => $this->start_date->format('d M Y'),
            'end_date' => $this->end_date?->format('d M Y'),
            'start_time' => $this->start_date->format('H:i'),
            'end_time' => $this->end_date?->format('H:i'),
            'location' => $this->location,
            'description' => $this->description,
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
