<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PropertyStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'name',
        'description',
        'badge_class',
        'color',
        'is_available',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Relationship with properties
     */
    public function properties(): HasMany
    {
        return $this->hasMany(Property::class, 'property_status_id');
    }

    /**
     * Scope for active property statuses
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for available property statuses
     */
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    /**
     * Scope for ordered property statuses
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    /**
     * Get property status by key
     */
    public static function getByKey(string $key): ?self
    {
        return static::where('key', $key)->first();
    }

    /**
     * Get all active property statuses as array for dropdowns
     */
    public static function getOptionsArray(): array
    {
        return static::active()
            ->ordered()
            ->pluck('name', 'id')
            ->toArray();
    }

    /**
     * Get all active property statuses with keys as array
     */
    public static function getKeyOptionsArray(): array
    {
        return static::active()
            ->ordered()
            ->pluck('name', 'key')
            ->toArray();
    }

    /**
     * Get all available property statuses as array
     */
    public static function getAvailableOptionsArray(): array
    {
        return static::active()
            ->available()
            ->ordered()
            ->pluck('name', 'key')
            ->toArray();
    }
}
