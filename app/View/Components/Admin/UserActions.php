<?php

namespace App\View\Components\Admin;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class UserActions extends Component
{
    public string $bulkActionRoute;
    public string $exportRoute;

    /**
     * Create a new component instance.
     */
    public function __construct(
        string $bulkActionRoute = 'admin.users.bulk-action',
        string $exportRoute = 'admin.analytics.export'
    ) {
        $this->bulkActionRoute = $bulkActionRoute;
        $this->exportRoute = $exportRoute;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.admin.user-actions');
    }
} 