<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportKegiatan extends Model
{
    //
    use HasFactory;

    protected $table = 'report_kegiatan';

    protected $fillable = [
        'kegiatan_id',
        'user_id',
        'keluhan',
        'detail_keluhan',
        'status'
    ];

    public function user(): BelongsTo{
        return $this->belongsTo(User::class, 'user_id');
    }

    public function kegiatan(): BelongsTo{
        return $this->belongsTo(Kegiatan::class, 'kegiatan_id');
    }
}
