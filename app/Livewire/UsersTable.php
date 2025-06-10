<?php

namespace App\Livewire;

use App\Models\User;
use App\Utils\CustomFieldManager;
use Livewire\Component;
use Livewire\WithPagination;

class UsersTable extends Component
{
    use WithPagination;
    
    // Search and filter properties
    public $search = '';
    public $userType = '';
    public $status = '';
    public $perPage = 50;
    public $orderBy = 'created_at_desc'; // Default order by
    
    // Modal properties
    public $showBlockModal = false;
    public $showUnblockModal = false;
    public $showDeleteModal = false;
    public $selectedUserId = null;
    public $selectedUserName = '';
    public $blockReason = '';
    public $currentBlockReason = '';
    
    protected $queryString = [
        'search' => ['except' => ''],
        'userType' => ['except' => ''],
        'status' => ['except' => ''],
        'orderBy' => ['except' => 'created_at_asc'],
    ];
    
    // Reset pagination when filters change
    public function updatedSearch()
    {
        $this->resetPage();
    }
    
    public function updatedUserType()
    {
        $this->resetPage();
    }
    
    public function updatedStatus()
    {
        $this->resetPage();
    }
    
    public function updatedOrderBy()
    {
        $this->resetPage();
    }
    
    public function clearFilters()
    {
        $this->reset(['search', 'userType', 'status']);
        $this->resetPage();
    }
    
    // Modal handlers
    public function openBlockModal($userId)
    {
        $user = User::findOrFail($userId);
        $this->selectedUserId = $userId;
        $this->selectedUserName = $user->name;
        $this->blockReason = '';
        $this->showBlockModal = true;
    }
    
    public function openUnblockModal($userId)
    {
        $user = User::findOrFail($userId);
        $this->selectedUserId = $userId;
        $this->selectedUserName = $user->name;
        $this->currentBlockReason = CustomFieldManager::get_field($user, 'block_reason');
        $this->showUnblockModal = true;
    }
    
    public function openDeleteModal($userId)
    {
        $user = User::findOrFail($userId);
        $this->selectedUserId = $userId;
        $this->selectedUserName = $user->name;
        $this->showDeleteModal = true;
    }
    
    public function closeModals()
    {
        $this->showBlockModal = false;
        $this->showUnblockModal = false;
        $this->showDeleteModal = false;
        $this->selectedUserId = null;
        $this->selectedUserName = '';
        $this->blockReason = '';
        $this->currentBlockReason = '';
    }
    
    // User actions
    public function blockUser()
    {
        $user = User::findOrFail($this->selectedUserId);
        $user->blocked = true;
        $user->custom = CustomFieldManager::update_or_create_array($user->custom, [
            'block_reason' => $this->blockReason,
            'blocked_at' => now(),
        ]);
        $user->save();
        
        $this->closeModals();
        session()->flash('message', 'User blocked successfully.');
    }
    
    public function unblockUser()
    {
        $user = User::findOrFail($this->selectedUserId);
        $user->blocked = false;
        $user->save();
        
        $this->closeModals();
        session()->flash('message', 'User unblocked successfully.');
    }
    
    public function deleteUser()
    {
        $user = User::findOrFail($this->selectedUserId);
        $user->delete();
        
        $this->closeModals();
        session()->flash('message', 'User deleted successfully.');
    }
    
    public function render()
    {
        $users = User::with(['orders' => function($query) {
                $query->latest();
            }])
            ->withCount('orders')
            ->when($this->search, function($query) {
                return $query->where(function($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->userType !== '', function($query) {
                return $query->where('type', $this->userType);
            })
            ->when($this->status !== '', function($query) {
                if ($this->status === 'blocked') {
                    return $query->where('blocked', true);
                } else if ($this->status === 'active') {
                    return $query->where('blocked', false);
                }
                return $query;
            })
            ->when($this->orderBy === 'name_asc', function($query) {
                return $query->orderBy('name');
            })
            ->when($this->orderBy === 'name_desc', function($query) {
                return $query->orderBy('name', 'desc');
            })
            ->when($this->orderBy === 'created_at_asc', function($query) {
                return $query->orderBy('created_at');
            })
            ->when($this->orderBy === 'created_at_desc', function($query) {
                return $query->orderBy('created_at', 'desc');
            })
            ->when($this->orderBy === 'orders_count_asc', function($query) {
                return $query->orderBy('orders_count');
            })
            ->when($this->orderBy === 'orders_count_desc', function($query) {
                return $query->orderBy('orders_count', 'desc');
            })
            ->paginate($this->perPage);
            
        return view('livewire.users-table', [
            'users' => $users
        ]);
    }
}
