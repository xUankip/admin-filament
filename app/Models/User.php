<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasTenants;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable implements FilamentUser, HasTenants, MustVerifyEmail
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use HasRoles;

    /**
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $guarded=false;

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function canAccessPanel(Panel $panel): bool
    {
        $isVerified = (bool) $this->email_verified_at;
        $panelId = method_exists($panel, 'getId') ? $panel->getId() : null;

        if ($panelId === 'pro') {
            // Admin panel: only staff_admin or super_admin
            return $isVerified && ($this->hasRole('super_admin') || $this->hasRole('staff_admin'));
        }

        if ($panelId === 'app') {
            // Organizer panel: staff_organizer, staff_admin, or super_admin
            return $isVerified && ($this->hasRole('super_admin') || $this->hasRole('staff_admin') || $this->hasRole('staff_organizer'));
        }

        return false;
    }

    public function canAccessTenant(Model $tenant): bool
    {
        return true;
    }

    /** @return Collection<int,Team> */
    public function getTenants(Panel $panel): Collection
    {
        return Team::all();
    }

    public function detail(): HasOne { return $this->hasOne(UserDetail::class); }
}
