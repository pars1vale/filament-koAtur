<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasTenants;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements HasTenants, FilamentUser
{

    public function canAccessPanel(Panel $panel): bool
    {
        return match ($panel->getId()) {
            'system' => $this->hasRole('super_admin'),
            'admin'  => $this->hasAnyRole(['super_admin','owner', 'manager', 'kasir']),
            default  => false,
        };
    }
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

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

    public function getTenants(Panel $panel): Collection
    {
        // System panel TIDAK pakai tenant
        if ($panel->getId() === 'system') {
            return collect();
        }

        // Owner panel pakai outlet
        return $this->outlets;
    }

    public function canAccessTenant(Model $tenant): bool
    {
        // super admin bebas
        if ($this->hasRole('super_admin')) {
            return true;
        }

        return $this->outlets()->whereKey($tenant)->exists();
    }

    public function outlets(): BelongsToMany
    {
        return $this->belongsToMany(Outlet::class);
    }
}
