<?php

namespace App\Models;

use Filament\Panel;
use App\Support\Enums\EmployeeStatus;
use App\Support\Traits\HasActivityLogs;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Employee extends Authenticatable implements FilamentUser
{
    use HasActivityLogs;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'supervisor_id',
        'system_identifier',
        'sap_number',
        'gov_id',
        'designation',
    ];

    protected $attributes = [
        'status' => EmployeeStatus::Active,
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
            'status' => EmployeeStatus::class,
        ];
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Employee $employee) {
            $employee->email_verified_at = now();
        });
    }

    public function timeEntries(): HasMany
    {
        return $this->hasMany(TimeEntry::class);
    }

    public function ongoingShift(): ?TimeEntry
    {
        return $this->timeEntries()
                    ->whereNotNull('clock_in_time')
                    ->whereNull('clock_out_time')
                    ->first();
    }

    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    #[Scope]
    public function userVisible($query, ?\Illuminate\Contracts\Auth\Authenticatable $user = null): void
    {
        $user ??= auth()->user();
        if ($user instanceof Employee) {
            $query->where('employees.id', $user->id);
            return;
        }

        /* @var User $user */
        if ($user->hasPermissionTo('view any employee')) {
            return;
        }

        if ($user->hasPermissionTo('view employees')) {
            $query->where('supervisor_id', $user->id);
            return;
        }

        $query->where('employees.id', 0);
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->canAccessEmployeePanel();
    }

    public function canAccessEmployeePanel(): bool
    {
        return $this->status->isActive();
    }

    public function getHasVerifiedEmailAttribute(): bool
    {
        return ! is_null($this->email_verified_at);
    }

    public function getFormattedNameWithSystemIdentifierAttribute(): string
    {
        return "{$this->name} ({$this->system_identifier})";
    }
}
