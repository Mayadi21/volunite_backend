<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Pencapaian extends Model
{
    //
    use HasFactory;

    protected $table = 'pencapaian';

    protected $fillable = [
        'nama',
        'deskripsi',
        'thumbnail',
        'required_kategori',
        'required_count_kategori',
        'required_exp'
    ];

    public function kategori(): BelongsTo{
        return $this->belongsTo(Kategori::class, 'required_kategori');
    }
    public function user(): BelongsToMany{
        return $this->belongsToMany(User::class, 'pencapaian_user', 'pencapaian_id', 'user_id')->withTimestamps();
    }
}
