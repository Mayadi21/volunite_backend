<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Kegiatan extends Model
{
    //
    use HasFactory;

    protected $table = 'kegiatan';

    protected $fillable = [
        'user_id',
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

    protected function thumbnail(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? url('storage/' . $value) : null,
        );
    }

    public function user(): BelongsTo{
        return $this->belongsTo(User::class, 'user_id');
    }

    public function pendaftaran(): HasMany{
        return $this->hasMany(Pendaftaran::class, 'kegiatan_id');
    }

    public function rating(): HasMany{
        return $this->hasMany(RatingKegiatan::class, 'kegiatan_id');
    }

    public function report(): HasMany{
        return $this->hasMany(ReportKegiatan::class, 'kegiatan_id');
    }

    public function kategori(): BelongsToMany{
        return $this->belongsToMany(Kategori::class, 'kategori_kegiatan', 'kegiatan_id', 'kategori_id')->withTimestamps();
    }

    
}
