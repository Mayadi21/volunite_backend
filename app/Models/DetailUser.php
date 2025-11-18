<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailUser extends Model
{
    //
    use HasFactory;

    protected $table = 'detail_users';
    protected $fillable = [
        'user_id',
        'tanggal_lahir',
        'jenis_kelamin',
        'no_telepon',
        'domisili',
    ];

    public function user(): BelongsTo{
        return $this->belongsTo(User::class, 'user_id');
    }

}
