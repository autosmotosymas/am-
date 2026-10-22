<?php

namespace App\Models;

use App\Notifications\ResetPasswordNotification;
use App\Notifications\VerificarEmailNotification;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name', 'email', 'password',
        'agencia_id', 'avatar', 'telefono', 'tema', 'activo',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'activo'            => 'boolean',
        ];
    }

    public function agencia(): BelongsTo
    {
        return $this->belongsTo(Agencia::class);
    }

    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class);
    }

    public function seguimientos(): HasMany
    {
        return $this->hasMany(Seguimiento::class);
    }

    public function agenciasVendidas(): HasMany
    {
        return $this->hasMany(Agencia::class, 'vendedor_id');
    }

    public function getAvatarUrlAttribute(): string
    {
        return $this->avatar
            ? asset('storage/' . $this->avatar)
            : 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=E8710A&color=fff&size=128';
    }

    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new VerificarEmailNotification);
    }

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    public function redirigirSegunRol(): string
    {
        if ($this->hasRole('admin'))       return route('admin.dashboard');
        if ($this->hasRole('agencia'))     return route('agencia.dashboard');
        if ($this->hasRole('capturador'))  return route('captura.index');
        if ($this->hasRole('vendedor'))    return route('vendedor.dashboard');
        return route('perfil.index');
    }
}
