<?php

namespace App\View\Components\Admin;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class PropertyStats extends Component
{
    public array $statistics;
    public $totalCount;

    /**
     * Create a new component instance.
     */
    public function __construct(array $statistics, $totalCount = null)
    {
        $this->statistics = $statistics;
        $this->totalCount = $totalCount;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.admin.property-stats');
    }
}
