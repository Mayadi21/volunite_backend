<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sertifikat extends Model
{
    //
    use HasFactory;

    protected $table = 'sertifikat';

    protected $fillable = [
        'pendaftaran_id',
        'tanggal_terbit',
        'path_file'
    ];

    public function pendaftaran(): BelongsTo{
        return $this->belongsTo(Pendaftaran::class, 'pendaftaran_id');
    }
}
