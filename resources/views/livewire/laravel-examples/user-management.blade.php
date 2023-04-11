<div class="main-content">
    {{csrf_field()}}
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0">
                    <div class="d-flex flex-row justify-content-between">
                        <div>
                            <h5 class="mb-0">All Users</h5>
                        </div>
                        <a href="#" class="btn bg-gradient-primary btn-sm mb-0" type="button">+&nbsp; New User</a>
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
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Photo
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Name
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Email
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        role
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Creation Date
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($users as $key => $user)

                                <tr @if($user->status == false) class="table-danger" @endif>
                                    <td class="ps-4">
                                        <p class="text-xs font-weight-bold mb-0">{{$user->id}}</p>
                                    </td>
                                    <td>
                                        <div>
                                            <img src="/assets/img/team-1.jpg" class="avatar avatar-sm me-3">
                                        </div>
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

</div>
