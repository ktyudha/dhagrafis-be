<?php

namespace App\Models\Traits;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;

/**
 *
 * Trait ini digunakan untuk menghandle proses upload gambar. Gambar yang diupload otomatis akan
 * diresize menjadi 3 ukuran: lg, md, sm.
 *
 * Dan gambar juga akan otomatis dikonversi menjadi
 * next gen format webp (safari masih partial support saat trait ini ditulis: https://caniuse.com/webp)
 *
 * Kolom di db harus bernama "image" agar bisa memanfaatkan $appends
 */

trait UploadImage
{

    /**
     * Upload gambar.
     *
     * @param String $file file dari request file
     * @param String $directory lokasi direktori tujuan diupload
     * @param String $fileName nama file tanpa ekstensi
     */
    public function uploadImage($file, $directory = 'ugc', $fileName = null)
    {
        $image = Image::make($file);

        # hapus ekstensi file dari $fileName (kalau ada)
        $fileName = Str::slug(uniqid() . '-' . pathinfo($fileName ?? $file->getClientOriginalName())['filename']);

        if (!Storage::exists($directory)) {
            Storage::makeDirectory($directory);
        }

        $basePath = "{$directory}/{$fileName}";
        $baseRealPath = Storage::path($basePath);

        # save Image untuk 3 jenis
        (clone $image)->resize(1350, null, function ($constraint) {
            $constraint->aspectRatio();
        })->save("{$baseRealPath}-lg.webp");

        (clone $image)->resize(800, null, function ($constraint) {
            $constraint->aspectRatio();
        })->save("{$baseRealPath}-md.webp");

        (clone $image)->resize(320, null, function ($constraint) {
            $constraint->aspectRatio();
        })->save("{$baseRealPath}-sm.webp");
        return (object) [
            'lg' => parse_url("{$basePath}-lg.webp")['path'],
            'md' => parse_url("{$basePath}-md.webp")['path'],
            'sm' => parse_url("{$basePath}-sm.webp")['path'],
        ];
    }

    /**
     * Delete 3 versi image (lg, md, sm)
     */
    public function deleteImage($imagePath = null)
    {
        $baseImage = substr(@$imagePath ?? $this->image->path, 0, -7);
        Log::info('delete: ' . $baseImage . 'lg.webp');
        Log::info('delete: ' . $baseImage . 'md.webp');
        Log::info('delete: ' . $baseImage . 'sm.webp');
        try {
            Storage::delete($baseImage . 'lg.webp');
            Storage::delete($baseImage . 'md.webp');
            Storage::delete($baseImage . 'sm.webp');
            return true;
        } catch (\Throwable $th) {
            Log::error($th);
            return false;
        }
    }

    public function getImageAttribute($imagePath)
    {
        $baseImage = substr($imagePath, 0, -7);

        return (object) [
            'path' => $imagePath,
            'lg' => $imagePath ? Storage::url($baseImage) . 'lg.webp' : asset('static/admin/img/default.png'),
            'md' => $imagePath ? Storage::url($baseImage) . 'md.webp' : asset('static/admin/img/default.png'),
            'sm' => $imagePath ? Storage::url($baseImage) . 'sm.webp' : asset('static/admin/img/default.png'),
        ];
    }
}
