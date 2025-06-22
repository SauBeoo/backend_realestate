<?php

namespace App\View\Components\Admin;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class UserSearch extends Component
{
    public $searchParams;
    public string $routeName;

    /**
     * Create a new component instance.
     */
    public function __construct($searchParams = null, string $routeName = 'admin.users.index')
    {
        $this->searchParams = $searchParams;
        $this->routeName = $routeName;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.admin.user-search');
    }
} 