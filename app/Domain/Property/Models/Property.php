<?php

namespace App\Domain\Property\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Domain\User\Models\User;

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
        'type',
        'area',
        'bedrooms',
        'bathrooms',
        'address',
        'latitude',
        'longitude',
        'features',
        'images',
        'status',
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
    ];

    /**
     * Get the owner of the property.
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
} 