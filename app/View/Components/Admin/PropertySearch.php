<?php

namespace App\View\Components\Admin;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\PropertyType;
use App\Models\PropertyStatus;
use Illuminate\Support\Facades\Log;

class PropertySearch extends Component
{
    public array $filters;
    public $propertyTypes;
    public $propertyStatuses;

    /**
     * Create a new component instance.
     */
    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
        $this->propertyTypes = PropertyType::where('is_active', true)->orderBy('sort_order')->orderBy('name')->get();
        $this->propertyStatuses = PropertyStatus::where('is_active', true)->orderBy('sort_order')->orderBy('name')->get();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.admin.property-search');
    }
}
