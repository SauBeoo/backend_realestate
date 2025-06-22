<?php

namespace App\Domain\Property\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Domain\User\Models\User;
use App\Models\PropertyType;
use App\Models\PropertyStatus;

class Property extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'price',
        'property_type_id',
        'area',
        'bedrooms',
        'bathrooms',
        'address',
        'latitude',
        'longitude',
        'features',
        'images',
        'property_status_id',
        'owner_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'float',
        'area' => 'float',
        'bedrooms' => 'integer',
        'bathrooms' => 'integer',
        'latitude' => 'float',
        'longitude' => 'float',
        'features' => 'array',
        'images' => 'array',
        'property_type_id' => 'integer',
        'property_status_id' => 'integer',
    ];

    /**
     * Get the owner of the property.
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Get the property type.
     */
    public function propertyType(): BelongsTo
    {
        return $this->belongsTo(PropertyType::class, 'property_type_id');
    }

    /**
     * Get the property status.
     */
    public function propertyStatus(): BelongsTo
    {
        return $this->belongsTo(PropertyStatus::class, 'property_status_id');
    }

    /**
     * Get type attribute for backward compatibility.
     */
    public function getTypeAttribute(): ?string
    {
        return $this->propertyType?->key;
    }

    /**
     * Get status attribute for backward compatibility.
     */
    public function getStatusAttribute(): ?string
    {
        return $this->propertyStatus?->key;
    }

    /**
     * Get type name attribute.
     */
    public function getTypeNameAttribute(): ?string
    {
        return $this->propertyType?->name;
    }

    /**
     * Get status name attribute.
     */
    public function getStatusNameAttribute(): ?string
    {
        return $this->propertyStatus?->name;
    }

    /**
     * Check if property is available for sale or rent.
     */
    public function isAvailable(): bool
    {
        return $this->propertyStatus?->is_available ?? false;
    }
} 