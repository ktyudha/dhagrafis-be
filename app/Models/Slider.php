<?php

namespace App\Models;

use App\Models\Traits\UploadImage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Slider extends Model
{
    use UploadImage;

    protected $fillable = ['name', 'description', 'image', 'url', 'type'];
}
