<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailPendaftaran extends Model
{
    //
    use HasFactory;

    protected $table = 'detail_pendaftaran';

    protected $fillable = [
        'pendaftaran_id',
        'nomor_telepon',
        'domisili',
        'komitmen',
        'keterampilan'
    ];

    public function pendaftaran(): BelongsTo{
        return $this->belongsTo(Pendaftaran::class, 'pendaftaran_id');
    }
}
