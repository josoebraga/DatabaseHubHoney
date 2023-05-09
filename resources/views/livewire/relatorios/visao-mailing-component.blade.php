<div>

    <div class="row">
        <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0">
                <h6>Dashboard</h6>
            </div>

            <div class="card-body pt-4 p-3">
                <div class="row">
                   <div class="col-md-6">
                      <div class="form-group">
                         <label for="user.user_type_id" class="form-control-label">{{ __('Escolha a Tabela') }}</label>
                         <div class="@error('user.user_type_id')border border-danger rounded-3 @enderror">
                            <select wire:model="tabelaSelecionada" class="form-control-sm" name="choices-button" id="choices-button" placeholder="Departure">
                               <option value="" selected="">(Escolha a Tabela)</option>
                               @forelse ($this->tabelas as $tabela)
                               <option value="{{$tabela->table_name}}">{{$tabela->table_name}}</option>
                               @empty
                               @endforelse
                            </select>
                         </div>
                         @error('user.user_type_id')
                         <div class="text-danger">{{ $message }}</div>
                         @enderror
                      </div>
                   </div>
                   <div class="col-md-6">
                      <div class="form-group">
                         <label for="data" class="form-control-label">{{ __('Escolha a data:') }}</label>
                         <div class="@error('user.email')border border-danger rounded-3 @enderror"></div>
                         <input wire:model="data" class="form-control-sm" type="date"
                            placeholder="" id="data">
                      </div>
                      @error('user.email')
                      <div class="text-danger">{{ $message }}</div>
                      @enderror
                   </div>
                </div>
             </div>

             <div class="card-body pt-4 p-3">
                <div class="row">
                   <div class="col-md-6">
                      <div class="form-group">
                         <div class="@error('user.user_type_id')border border-danger rounded-3 @enderror">
                            <button type="submit" wire:click.prevent="carregar" class="btn btn-outline-primary btn-sm mb-0">Confirmar</button>
                        </div>
                      </div>
                   </div>
                </div>
             </div>


        </div>

        <div class="row">
            <div class="col-12">
              <div class="card mb-4">
                <div class="card-header pb-0">
                  <h6>Projects table</h6>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                  <div class="table-responsive p-0">
                    <table class="table align-items-center justify-content-center mb-0">
                      <thead>
                        <tr>
                          <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle text-center">Coluna</th>
                          <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle text-center">Quantidade de inválidos</th>
                          <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle text-center">%</th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody>
                        @if($this->contagens)
                        @forelse($this->contagens as $key => $contagem)

                        <tr>

                          <td class="align-middle text-center">
                            <p class="text-sm font-weight-bold mb-0">{{$key}}</p>
                          </td>

                          <td class="align-middle text-center">
                            <p class="text-sm font-weight-bold mb-0">{{$contagem}}</p>
                          </td>


                          <td class="align-middle text-center">
                            <div class="d-flex align-items-center justify-content-center">
                              <span class="me-2 text-xs font-weight-bold">{{$contagem/$this->total}}</span>
                              <div>
                                <div class="progress">
                                  <div class="progress-bar bg-gradient-info" role="progressbar" aria-valuenow="{{$contagem}}" aria-valuemin="0" aria-valuemax="{{$this->total}}" style="width: 15%;"></div>
                                </div>
                              </div>
                            </div>
                          </td>


                        </tr>
                        @empty

                        @endforelse
                        @endif


                      </tbody>
                    </table>

                  </div>
                </div>
              </div>
            </div>
          </div>



        </div>
        </div>
    </div>

</div>



