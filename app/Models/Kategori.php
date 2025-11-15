<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kategori extends Model
{
    //
    use HasFactory;

    protected $table = 'kategori';
    
    protected $fillable = [
        'nama_kategori',
        'deskripsi',
        'thumbnail'
    ];

    public function pencapaian(): HasMany{
        return $this->hasMany(Pencapaian::class, 'kategori_id');
    }

    public function kegiatan(): BelongsToMany{
        return $this->belongsToMany(Kegiatan::class, 'kategori_kegiatan', 'kategori_id', 'kegitan_id')->withTimestamps();
    }
}
