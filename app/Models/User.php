<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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
        'gov_assai_id',
        'cpf',
        'celular',
        'nivel_acesso',
        'role',
        'ativo',
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
            'ativo' => 'boolean',
        ];
    }

    /**
     * Vídeos enviados pelo usuário
     */
    public function videos()
    {
        return $this->hasMany(Video::class);
    }

    /**
     * Vídeos moderados pelo usuário
     */
    public function videosModeredos()
    {
        return $this->hasMany(Video::class, 'moderador_id');
    }

    /**
     * Verifica se é administrador
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Verifica se é moderador ou admin
     */
    public function isModerador()
    {
        return in_array($this->role, ['admin', 'moderador']);
    }

    /**
     * Verifica se é cidadão comum
     */
    public function isCidadao()
    {
        return $this->role === 'cidadao';
    }
}
