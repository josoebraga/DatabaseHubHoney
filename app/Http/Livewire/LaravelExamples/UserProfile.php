<?php

namespace App\Http\Livewire\LaravelExamples;
use App\Models\User;
use App\Models\UsersTypeModel;
use Illuminate\Support\Facades\Auth;

use Livewire\Component;

class UserProfile extends Component
{
    #public User $user;
    #https://demos.creative-tim.com/argon-design-system/docs/foundation/icons.html
    public $user;
    public $usersType;
    public $userTypeSee;
    public $usertypeId;
    public $showSuccesNotification  = false;

    public $showDemoNotification = false;

    protected $rules = [
        'user.name' => 'max:40|min:3',
        'user.email' => 'email:rfc,dns',
        'user.phone' => 'max:10',
        'user.about' => 'max:200',
        'user.location' => 'min:3',
        'user.user_type_id' => 'required|numeric|min:1'
    ];

    public function mount() {
        $this->user = User::find(1);
        $this->usersType = UsersTypeModel::all();
        $this->userTypeSee = UsersTypeModel::find($this->user->user_type_id);

    }

    public function save() {
            $this->validate();
            $this->user->save();
            $this->showSuccesNotification = true;
    }
    public function render()
    {
        return view('livewire.laravel-examples.user-profile');
    }
}
