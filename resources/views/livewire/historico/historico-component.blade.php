<div class="row">
    <div class="col-12">
      <div class="card mb-4">
        <div class="card-header pb-0">
          <h6>Authors table</h6>
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



        <div class="card-body px-0 pt-0 pb-2">
          <div class="table-responsive p-0">
            <table class="table align-items-center mb-0">
              <thead>
                <tr>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">#</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Usuário</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Dados Antigos</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Dados Novos</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Dados inválidos</th>
                  <th class="text-secondary opacity-7"></th>
                </tr>
              </thead>
              <tbody>


                @if($this->historicos)

                    @forelse ($this->historicos as $historico)
                    @php $json = json_decode($historico->historico); @endphp
                    <tr>
                    <td>{{$historico->id}}</td>
                    <td>
                        <div class="d-flex px-2 py-1">
                        <div class="d-flex flex-column justify-content-center">
                            <h6 class="mb-0 text-sm">{{$historico->name}}</h6>
                            @if(isset($json) && property_exists($json, 'acao'))
                                @forelse ($json->acao as $key => $value)
                                <p class="text-xs font-weight-bold mb-0">{{"Ação: $value"}}</p>
                                @empty

                                @endforelse
                            @endif
                        </div>
                        </div>
                    </td>
                    <td>
                        @if(isset($json) && property_exists($json, 'old'))
                            @forelse ($json->old as $key => $value)
                            <p class="text-xs font-weight-bold mb-0">{{"$key: $value\n"}}</p>
                            @empty

                            @endforelse
                        @endif
                    </td>
                    <td>
                        @if(isset($json) && property_exists($json, 'new'))
                            @forelse ($json->new as $key => $value)
                            <p class="text-xs font-weight-bold mb-0">{{"$key: $value\n"}}</p>
                            @empty

                            @endforelse
                        @endif
                    </td>
                    <td class="align-middle text-center">
                        @if(isset($json) && property_exists($json, 'invalidos'))
                            @forelse ($json->invalidos as $key => $value)
                            <p class="text-xs font-weight-bold mb-0">{{"$key: $value\n"}}</p>
                            @empty

                            @endforelse
                        @endif
                    </td>
                    </tr>

                    @empty

                    @endforelse

                @endif

              </tbody>
            </table>

            @if($this->historicos)
                <div class="d-flex justify-content-center">
                    {{ $this->historicos->links() }}
                </div>
            @endif


          </div>
        </div>
      </div>
    </div>
  </div>
















