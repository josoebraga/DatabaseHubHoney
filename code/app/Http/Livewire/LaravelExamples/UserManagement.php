<?php

namespace App\Http\Livewire\LaravelExamples;

use App\Models\User;
use App\Models\UsersTypeModel;

use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Request;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

use Livewire\Component;

class UserManagement extends Component
{

    public $users;
    public $usersType;
    public $showSuccesNotification  = false;
    public $botaoNovoUsuario  = false;
    public $nomeCreate;
    public $emailCreate;
    public $telefoneCreate;
    public $localidadeCreate;
    public $perfilCreate;

    public function liberaCamposNovoUsuario() {
        if($this->botaoNovoUsuario == false) {
            $this->botaoNovoUsuario = true;
        } else {
            $this->botaoNovoUsuario = false;
        }
    }

    public function store() {

        $servico = User::create(
            [
            'name' => $this->nomeCreate,
            'email' => $this->emailCreate,
            'phone' => $this->telefoneCreate,
            'location' => $this->localidadeCreate,
            'password' => Hash::make('password'),
            'user_type_id' => $this->perfilCreate,
            'status' => true
            ]
        );

        $this->reset();
        $this->mount();

    }

    public function updateUserStatus($id, $status) {

        $getDate = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::now()->toDateTimeString())->toDateTimeString();
        $getDate = date('YmdHis', strtotime($getDate));
        #dd($getDate);

        $user = User::findOrFail($id);
        $user->status = ($status == 1 ? false : true);
        $user->password = ($status == 1 ? $getDate : Hash::make('password'));
        $user->updated_at = Carbon::now();
        $user->save();
        $this->mount();
    }

    public function mount() {
        if(Auth::user()->user_type_id == 1 || Auth::user()->user_type_id == 2) {
            if(Auth::user()->user_type_id == 1) {
                $this->users = User::join('users_type', 'users_type.id', '=', 'users.user_type_id')->select('users.*', 'users_type.type')->orderByDesc('updated_at')->get();
                $this->usersType = UsersTypeModel::all();
            } else if(Auth::user()->user_type_id == 2) {
                $this->users = User::join('users_type', 'users_type.id', '=', 'users.user_type_id')->whereIn('user_type_id', [2, 3])->select('users.*', 'users_type.type')->orderByDesc('updated_at')->get();
                $this->usersType = UsersTypeModel::whereIn('id', [2, 3])->get();
            }
        }
    }

    public function render()
    {
        return view('livewire.laravel-examples.user-management');
    }
}
