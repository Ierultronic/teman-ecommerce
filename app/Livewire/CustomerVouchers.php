<?php

namespace App\Livewire;

use App\Models\Voucher;
use Livewire\Component;
use Livewire\WithPagination;

class CustomerVouchers extends Component
{
    use WithPagination;

    public $search = '';
    public $filterType = '';
    public $sortBy = 'priority';
    public $sortDirection = 'desc';

    protected $queryString = [
        'search' => ['except' => ''],
        'filterType' => ['except' => ''],
        'sortBy' => ['except' => 'priority'],
        'sortDirection' => ['except' => 'desc']
    ];

    public function mount()
    {
        // Ensure we're on page 1 when filters change
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterType()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    public function getAvailableVouchers()
    {
        $query = Voucher::active()
            ->where('status', 'active')
            ->where(function($q) {
                $q->whereNull('starts_at')
                  ->orWhere('starts_at', '<=', now());
            })
            ->where(function($q) {
                $q->whereNull('ends_at')
                  ->orWhere('ends_at', '>=', now());
            });

        // Apply search filter
        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('code', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        // Apply type filter
        if ($this->filterType) {
            $query->where('type', $this->filterType);
        }

        // Apply sorting
        switch ($this->sortBy) {
            case 'value':
                $query->orderBy('value', $this->sortDirection);
                break;
            case 'minimum_amount':
                $query->orderBy('minimum_amount', $this->sortDirection);
                break;
            case 'starts_at':
                $query->orderBy('starts_at', $this->sortDirection);
                break;
            case 'ends_at':
                $query->orderBy('ends_at', $this->sortDirection);
                break;
            default:
                $query->orderBy('created_at', $this->sortDirection);
                break;
        }

        return $query->paginate(12);
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->filterType = '';
        $this->sortBy = 'priority';
        $this->sortDirection = 'desc';
        $this->resetPage();
    }

    public function getCountdownTimeLeft($endTime)
    {
        $now = now();
        $diffInMinutes = $now->diffInMinutes($endTime, false);
        $diffInHours = $now->diffInHours($endTime, false);
        $diffInDays = $now->diffInDays($endTime, false);
        
        if ($now->greaterThan($endTime)) {
            return 'Expired';
        } elseif ($diffInDays >= 1) {
            // 1 day or more: show days
            return round($diffInDays) . ' days left';
        } elseif ($diffInHours >= 1) {
            // Less than 1 day: show only hours (no minutes)
            return round($diffInHours) . ' hrs left';
        } elseif ($diffInMinutes >= 1) {
            // Less than 1 hour: show only minutes
            return round($diffInMinutes) . ' mins left';
        } else {
            // Less than 1 minute
            return 'Expiring soon';
        }
    }

    public function render()
    {
        return view('livewire.customer-vouchers', [
            'vouchers' => $this->getAvailableVouchers(),
            'voucherTypes' => ['percentage' => 'Percentage', 'fixed' => 'Fixed Amount']
        ]);
    }
}
