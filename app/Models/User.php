<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    
    public function esAdministrador(): bool
    {
        return (int) $this->rol === 1;
    }

    public function esCocina(): bool
    {
        return (int) $this->rol === 2;
    }

    public function esAtencion(): bool
    {
        return (int) $this->rol === 3;
    }

    public function nombreRol(): string
    {
        return match ((int) $this->rol) {
            1 => 'Administrador',
            2 => 'Cocina',
            3 => 'Atención',
            default => 'Sin rol',
        };
    }


    /** @use HasFactory<UserFactory> */
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
        'rol',
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
            'rol' => 'integer',
        ];
    }
}
