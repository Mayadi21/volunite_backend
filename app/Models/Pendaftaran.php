<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Pendaftaran extends Model
{
    //
    use HasFactory;

    protected $table = 'pendaftaran';

    protected $fillable = [
        'user_id',
        'kegiatan_id',
        'status',
        'status_kehadiran',
        'tanggal_kehadiran'
    ];

    public function sertifikat(): HasOne{
        return $this->hasOne(Sertifikat::class, 'pendaftaran_id');
    }
    public function detailPendaftaran(): HasOne{
        return $this-> hasOne(DetailPendaftaran::class, 'pendaftaran_id');
    }
    public function user(): BelongsTo{
        return $this->belongsTo(User::class, 'user_id');
    }
    public function kegiatan(): BelongsTo{
        return $this->belongsTo(Kegiatan::class, 'kegiatan_id');
    }
    
}
