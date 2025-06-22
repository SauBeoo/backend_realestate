<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PropertyType extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'name',
        'description',
        'icon',
        'color',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Relationship with properties
     */
    public function properties(): HasMany
    {
        return $this->hasMany(Property::class, 'property_type_id');
    }

    /**
     * Scope for active property types
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for ordered property types
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    /**
     * Get property type by key
     */
    public static function getByKey(string $key): ?self
    {
        return static::where('key', $key)->first();
    }

    /**
     * Get all active property types as array for dropdowns
     */
    public static function getOptionsArray(): array
    {
        return static::active()
            ->ordered()
            ->pluck('name', 'id')
            ->toArray();
    }

    /**
     * Get all active property types with keys as array
     */
    public static function getKeyOptionsArray(): array
    {
        return static::active()
            ->ordered()
            ->pluck('name', 'key')
            ->toArray();
    }
}
