<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Models\Traits\UploadImage;
use Cviebrock\EloquentSluggable\Sluggable;

class Service extends Model
{
    use Sluggable;
    use UploadImage;

    protected $fillable = ['title', 'type', 'description_short', 'description', 'image'];

    public function sluggable(): array
    {
        return [
            'type',
            'slug' => [
                'source' => 'title',
                'onUpdate' => true
            ]
        ];
    }

    public function showImage()
    {
        if (Storage::exists($this->image)) {
            return "storage/$this->image";
        }
        return asset('static/admin/img/default.png');
    }

    public function scopeService($query)
    {
        return $query->where("type", "service");
    }

    public function scopeKeunggulan($query)
    {
        return $query->where("type", "keunggulan");
    }
}
