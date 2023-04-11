<?php

namespace App\Http\Livewire\LaravelExamples;

use App\Models\User;
use App\Models\UsersTypeModel;

use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Request;

use Livewire\Component;

class UserManagement extends Component
{

    public $users;
    public $usersType;
    public $showSuccesNotification  = false;

    public function mount(Request $request) {
        $this->users = User::join('users_type', 'users_type.id', '=', 'users.user_type_id')->select('users.*', 'users_type.type')->get();
    }
    public function render()
    {
        return view('livewire.laravel-examples.user-management');
    }
}
