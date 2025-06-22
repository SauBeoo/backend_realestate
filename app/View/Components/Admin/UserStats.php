<?php

namespace App\View\Components\Admin;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class UserStats extends Component
{
    public array $statistics;

    /**
     * Create a new component instance.
     */
    public function __construct(array $statistics)
    {
        $this->statistics = $statistics;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.admin.user-stats');
    }
} 