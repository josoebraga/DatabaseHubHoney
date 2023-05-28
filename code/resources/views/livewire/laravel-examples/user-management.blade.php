<div class="main-content">
    {{csrf_field()}}
@if(Auth::user()->user_type_id == 1 || Auth::user()->user_type_id == 2)
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0">
                    <div class="d-flex flex-row justify-content-between">
                        <div>
                            <h5 class="mb-0">Lista de Usuários</h5>
                        </div>
                        <a href="#" wire:click.prevent="liberaCamposNovoUsuario" class="btn bg-gradient-primary btn-sm mb-0" type="button">@if($this->botaoNovoUsuario == true)-@else+@endif&nbsp; Novo Usuário</a>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        ID
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Nome
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        e-mail
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Perfil
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Data de Criação
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Ações
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($this->botaoNovoUsuario == true)
                                <tr>
                                    <td></td>
                                    <td>
                                        <label>Nome:</label>
                                        <input type="text" wire:model.lazy="nomeCreate" class="form-control">
                                    </td>
                                    <td>
                                        <label>e-mail:</label>
                                        <input wire:model.lazy="emailCreate" class="form-control" type="email" placeholder="exemplo@examplo.com.br">
                                    </td>
                                    <td>
                                        <label>Telefone:</label>
                                        <input type="tel" wire:model.lazy="telefoneCreate" class="form-control" placeholder="40770888444">
                                    </td>
                                    <td>
                                        <label>Localidade:</label>
                                        <input type="text" wire:model.lazy="localidadeCreate" class="form-control" placeholder="Localidade">
                                    </td>
                                    <td>
                                        <label>Perfil:</label>
                                        <select wire:model.lazy="perfilCreate" class="form-control form-control-alternative" name="choices-button" id="choices-button">
                                            <option>(Escolha Uma Opção)</option>
                                            @forelse ($usersType as $userType)
                                            <option value="{{$userType->id}}">{{$userType->type}}</option>
                                            @empty
                                            @endforelse
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center" colspan="7">
                                        @if(!empty($nomeCreate) && !empty($emailCreate) && !empty($telefoneCreate) && !empty($localidadeCreate) && !empty($perfilCreate))
                                            <a wire:click.prevent="store" class="btn btn-sm btn-primary">Salvar</a>
                                        @endif
                                    </td>
                                </tr>
                                @endif

                                @forelse ($users as $key => $user)

                                <tr @if($user->status == false) class="table-danger" @endif>
                                    <td class="ps-4">
                                        <p class="text-xs font-weight-bold mb-0">{{$user->id}}</p>
                                    </td>
                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0">{{$user->name}}</p>
                                    </td>
                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0">{{$user->email}}</p>
                                    </td>
                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0">{{$user->type}}</p>
                                    </td>
                                    <td class="text-center">
                                        <span class="text-secondary text-xs font-weight-bold">{{ \Carbon\Carbon::parse($user->created_at)->format('d/m/Y') }}</span>
                                    </td>

                                    <td class="text-center">
                                        <a href="/laravel-user-profile/{{$user->id}}" class="mx-3" data-bs-toggle="tooltip" data-bs-original-title="laravel-user-management">
                                            <i class="fas fa-user-edit text-secondary"></i>
                                        </a>
                                        <span>
                                            @if(Auth::user()->user_type_id == 1)
                                                @if($user->status == true)
                                                    <i class="cursor-pointer fas fa-toggle-on text-success" wire:click.prevent='updateUserStatus({{$user->id}}, 1)'></i>
                                                @else
                                                    <i class="cursor-pointer fas fa-toggle-off text-danger" wire:click.prevent='updateUserStatus({{$user->id}}, 2)'></i>
                                                @endif
                                            @else
                                                <i class="cursor-pointer fas fa-toggle-off text-muted"></i>
                                            @endif
                                        </span>
                                    </td>

                                </tr>

                                @empty

                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
<div class="row">
    <div class="col-12">
        <div class="card mb-4 mx-4">
            <br>
            <div class="alert alert-danger alert-dismissible fade show" style="color: white;" role="alert">
                <strong>Atenção!</strong> Seu usuário não tem permissão para acessar esta página!</strong>
            </div>
        </div>
    </div>
</div>
@endif
</div>
