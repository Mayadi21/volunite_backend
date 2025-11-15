<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kegiatan extends Model
{
    //
    use HasFactory;

    protected $table = 'kegiatan';

    protected $fillable = [
        'judul',
        'thumbnail',
        'deskripsi',
        'lokasi',
        'syarat_ketentuan',
        'kuota',
        'tanggal_mulai',
        'tanggal_berakhir',
        'status'
    ];

    public function pendaftaran(): HasMany{
        return $this->hasMany(Pendaftaran::class, 'kegiatan_id');
    }

    public function rating(): HasMany{
        return $this->hasMany(RatingKegiatan::class, 'kegiatan_id');
    }

    public function kategori(): BelongsToMany{
        return $this->belongsToMany(Kategori::class, 'kategori_kegiatan', 'kegiatan_id', 'kategori_id')->withTimestamps();
    }

    
}
