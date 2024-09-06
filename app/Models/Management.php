<?php

namespace App\Models;

// use App\Models\Traits\UploadImage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Management extends Model
{
    use HasFactory;
    // use UploadImage;

    public const INSTAGRAM = 'instagram';
    public const LINKEDIN = 'linkedin';
    public const YOUTUBE = 'youtube';
    public const PROMO = 'promo';
    public const CONTACT = 'contact';
    public const ABOUT = 'about';
    public const JOBS = 'jobs';
    public const KATALOG = 'katalog';

    protected $fillable = ['type',  'title', 'description', 'additional_info', 'image'];

    public function scopeType($query, $key)
    {
        return $query->where('type', $key);
    }

    public function scopeAbout($query)
    {
        return $query->where("type", "about");
    }

    public function scopeContact($query)
    {
        return $query->where("type", "contact");
    }

    public function scopePromo($query)
    {
        return $query->where("type", "promo");
    }

    public function scopeKatalog($query)
    {
        return $query->where("type", "katalog");
    }

    public function scopeJobs($query)
    {
        return $query->where("type", "jobs");
    }

    public function scopeSocial($query)
    {
        return $query->whereIn('type', ['instagram', 'youtube', 'linkedin']);
    }

    public function getJsonValueAttribute($value)
    {
        return json_decode($value);
    }

    public function showImage()
    {
        if (Storage::exists($this->image)) {
            return "storage/$this->image";
        }
        return asset('static/admin/img/default.png');
    }
}
