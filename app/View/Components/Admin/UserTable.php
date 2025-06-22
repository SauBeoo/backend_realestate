<?php

namespace App\View\Components\Admin;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Illuminate\Pagination\LengthAwarePaginator;

class UserTable extends Component
{
    public LengthAwarePaginator $users;
    public array $sortParams;

    /**
     * Create a new component instance.
     */
    public function __construct(LengthAwarePaginator $users, array $sortParams = [])
    {
        $this->users = $users;
        $this->sortParams = $sortParams;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.admin.user-table');
    }
} 