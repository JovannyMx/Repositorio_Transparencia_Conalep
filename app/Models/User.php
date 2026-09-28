<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
<<<<<<< HEAD
=======
use Spatie\Permission\Traits\HasRoles;
>>>>>>> origin/main

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
<<<<<<< HEAD
    use HasFactory, Notifiable;
=======
    use HasFactory, Notifiable, HasRoles;
>>>>>>> origin/main

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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

     public function areas()
    {
        return $this->belongsToMany(\App\Models\Area::class, 'user_areas');
    }
<<<<<<< HEAD
}
=======
}
>>>>>>> origin/main
