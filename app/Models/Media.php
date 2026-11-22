<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $fillable = ['title', 'type', 'genre', 'release_year', 'description'];

    protected $casts = [
        'release_year' => 'integer',
    ];

    /**
     * Relasi: satu Media bisa dikoleksi oleh banyak Pengguna (belongsToMany)
     */
    public function pengguna()
    {
        return $this->belongsToMany(Pengguna::class, 'media_pengguna');
    }
}
