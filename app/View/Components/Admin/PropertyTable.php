<?php

namespace App\View\Components\Admin;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Illuminate\Pagination\LengthAwarePaginator;

class PropertyTable extends Component
{
    public $properties;

    /**
     * Create a new component instance.
     */
    public function __construct($properties)
    {
        $this->properties = $properties;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.admin.property-table');
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeClass(string $status): string
    {
        return match ($status) {
            'available' => 'bg-success',
            'pending' => 'bg-warning',
            'sold' => 'bg-danger',
            'rented' => 'bg-info',
            default => 'bg-secondary',
        };
    }
}
