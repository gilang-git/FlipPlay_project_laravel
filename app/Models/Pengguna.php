<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengguna extends Model
{
    protected $table = 'pengguna';

    protected $fillable = ['name'];

    /**
     * Relasi: satu Pengguna bisa mengoleksi banyak Media (belongsToMany)
     */
    public function media()
    {
        return $this->belongsToMany(Media::class, 'media_pengguna');
    }
}
