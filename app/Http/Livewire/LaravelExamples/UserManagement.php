<?php

namespace App\Http\Livewire\LaravelExamples;

use App\Models\User;
use App\Models\UsersTypeModel;

use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Request;

use Illuminate\Support\Carbon;

use Livewire\Component;

class UserManagement extends Component
{

    public $users;
    public $usersType;
    public $showSuccesNotification  = false;

    public function updateUserStatus($id, $status) {
        $user = User::findOrFail($id);
        $user->status = ($status == 1 ? false : true);
        $user->updated_at = Carbon::now();
        $user->save();
        $this->mount();
    }
    public function mount() {
        $this->users = User::join('users_type', 'users_type.id', '=', 'users.user_type_id')->select('users.*', 'users_type.type')->get();
    }
    public function render()
    {
        return view('livewire.laravel-examples.user-management');
    }
}
