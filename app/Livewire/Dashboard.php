<?php

namespace App\Livewire;

use Livewire\Component;

class Dashboard extends Component
{
    protected $listeners = ['refreshDashboard'];

    public function refreshDashboard()
    {
        // Dispatch refresh to all child components
        $this->dispatch('refreshSalesOverview');
        $this->dispatch('refreshOrderStats');
        $this->dispatch('refreshProductStats');
        $this->dispatch('refreshRecentOrders');
        $this->dispatch('refreshLowStockAlert');
        $this->dispatch('refreshTopProducts');
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}
