<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'path_profil',
        'role',

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function detailUser(): HasOne{
        return $this->hasOne(DetailUser::class, 'user_id');
    }

    public function pendaftaran(): HasMany{
        return $this->hasMany(Pendaftaran::class, 'user_id');
    }

    public function rating(): HasMany{
        return $this->hasMany(RatingKegiatan::class, 'user_id');
    }

    public function report(): HasMany{
        return $this->hasMany(ReportKegiatan::class, 'user_id');
    }

    public function pencapaian(): BelongsToMany{
        return $this->belongsToMany(Pencapaian::class, 'pencapaian_user', 'pencapaian_id', 'user_id')
        ->withTimestamps();
    }

    
}
