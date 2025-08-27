<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TicketStatusDropdown extends Component
{
    public $ticketStatus;
    public $name;
    public $id;

    /**
     * Create a new component instance.
     */
    public function __construct(
        $name = '',
        $id = '',
    )
    {
        $this->name = $name;
        $this->id = $id;
        $this->ticketStatus = [
            [
                'name' => 'In Progress',
                'value' => 'inprogress',
            ],
            [
                'name' => 'On Hold',
                'value' => 'hold',
            ],
            [
                'name' => 'On Break',
                'value' => 'break',
            ],
            [
                'name' => 'Close',
                'value' => 'close',
            ],
        ];
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.ticket-status-dropdown', [
            'ticketStatus' => $this->ticketStatus
        ]);
    }
}
