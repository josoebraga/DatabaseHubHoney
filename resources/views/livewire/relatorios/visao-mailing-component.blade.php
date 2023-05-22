<div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

    <div class="row">
        <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0">
                <h6>Selecione as Opções disponíveis:</h6>
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
            @if(!empty($this->tabelaSelecionada) && !empty($this->data))
             <div class="card-body pt-4 p-3">
                <div class="row">
                   <div class="col-md-6">
                      <div class="form-group">
                         <div class="@error('user.user_type_id')border border-danger rounded-3 @enderror">
                            <button type="submit" wire:click.prevent="carregar" class="btn btn-outline-primary btn-sm mb-0">Atualizar</button>
                        </div>
                      </div>
                   </div>
                </div>
             </div>
            @endif


        </div>

        <div class="row">
            <div class="col-12">
              <div class="card mb-4">
                <div class="card-header pb-0">
                  <h6>Relatório de Inválidos:</h6>
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
                        @php
                            $label = array(); $data = array();
                        @endphp
                        @forelse($this->contagens as $key => $contagem)

                        <tr>

                          <td class="align-middle text-center">
                            <p class="text-sm font-weight-bold mb-0">{{$key}}</p>
                          </td>

                          <td class="align-middle text-center">
                            <p class="text-sm font-weight-bold mb-0">{{$contagem}}</p>
                          </td>

                            @php
                                array_push($label, $key);
                                array_push($data, $contagem);
                            @endphp


                          <td class="align-middle text-center">
                            <div class="d-flex align-items-center justify-content-center">
                              <span class="me-2 text-xs font-weight-bold">@if($this->qtdTotalDeRegistrosTabela) {{number_format(($contagem / $this->qtdTotalDeRegistrosTabela) * 100, 2)}}% @endif</span>
                              <div>
                                <div class="progress">
                                  <div class="progress-bar bg-gradient-info" role="progressbar" aria-valuenow="{{$contagem}}" aria-valuemin="0" aria-valuemax="{{$this->qtdTotalDeRegistrosTabela}}" style="width: 15%;"></div>
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

        <div class="row">
            <div class="col-12">
              <div class="card mb-4">
                <div class="card-header pb-0">
                  <h6>Total Geral:</h6>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                  <div class="table-responsive p-0">
                    <table class="table align-items-center justify-content-center mb-0">
                      <thead>
                        <tr>
                          <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle text-center">Quantidade Total de Registros</th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody>

                        @if($this->qtdTotalDeRegistrosTabela)
                        <tr>
                            <td class="align-middle text-center">
                                <p class="text-sm font-weight-bold mb-0">{{$this->qtdTotalDeRegistrosTabela}}</p>
                            </td>
                        </tr>
                        @endif


                      </tbody>
                    </table>

                  </div>
                </div>
              </div>
            </div>
          </div>

        <div class="row">
            <div class="col-12">
              <div class="card mb-4">
                <div class="card-header pb-0">
                  <h6>Ações do Mailing:</h6>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                  <div class="table-responsive p-0">

                    @if($this->qtdTotalDeRegistrosTabela)
                    <div class="card-body pt-4 p-3">
                        <div class="row">
                           <div class="col-md-6">
                              <div class="form-group">
                                 <div class="@error('user.user_type_id')border border-danger rounded-3 @enderror">
                                    <button type="submit" wire:click.prevent="export" class="btn btn-outline-primary btn-sm mb-0" wire:loading.attr="disabled">Exportar Dados</button>
                                </div>
                              </div>
                           </div>
                           @if(Auth::user()->user_type_id == 1 || Auth::user()->user_type_id == 2)
                           <div class="col-md-6">
                              <div class="form-group">
                                 <div class="@error('user.user_type_id')border border-danger rounded-3 @enderror">
                                    <button type="button" class="btn btn-outline-primary btn-sm mb-0" data-toggle="modal" data-target="#myModal">
                                        Excluir Dados
                                    </button>
                                </div>
                              </div>
                           </div>
                           @endif
                        </div>
                     </div>
                     @endif


                  </div>
                </div>
              </div>
            </div>
          </div>




  <!-- The Modal -->
  <div class="modal fade" id="myModal" wire:ignore.self>
    <div class="modal-dialog">
      <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Atenção!</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <!-- Modal body -->
        <div class="modal-body">
          Tem certeza de que desa excluir? Após clicar em sim, todos os dados serão perdidor. Continuar?
        </div>

        <!-- Modal footer -->
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" wire:click.prevent="delete" data-dismiss="modal">Sim</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Não</button>
        </div>

      </div>
    </div>
  </div>




        </div>
    </div>
</div>


