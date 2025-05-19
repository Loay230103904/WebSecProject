<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'credit',
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
            'credit' => 'decimal:2',
        ];
    }

    public function hasRole($role): bool
    {
        // For now, we'll use a simple role check based on email
        // In a real application, you would have a proper roles table
        if ($role === 'Admin') {
            return $this->email === 'admin@example.com';
        } elseif ($role === 'Employee') {
            return $this->email === 'employee@example.com';
        } elseif ($role === 'Customer') {
            return !in_array($this->email, ['admin@example.com', 'employee@example.com']);
        }
        return false;
    }

    public function hasAnyRole($roles): bool
    {
        if (!is_array($roles)) {
            $roles = [$roles];
        }
        foreach ($roles as $role) {
            if ($this->hasRole($role)) {
                return true;
            }
        }
        return false;
    }

    public function purchases(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'purchases')
            ->withPivot('quantity', 'created_at')
            ->withTimestamps();
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Product::class, 'reviewed_by');
    }
}
