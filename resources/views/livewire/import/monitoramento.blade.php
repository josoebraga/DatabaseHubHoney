<div wire:poll.{{$tempoRefresh}}ms>
    Hora Atual: {{ now('America/Sao_Paulo')->format('d/m/Y H:i:s') }}
    <br>
    Atualizar a cada:
    <select wire:model="tempoRefresh" wire:change="atualizaValorSessao" class="form-control-sm" name="choices-button" id="choices-button" placeholder="Departure">
        <option value="" selected="">(Atualizar a cada)</option>
            <option value="2000">2 Segundo(s)</option>
            <option value="3000">3 Segundo(s)</option>
            <option value="4000">4 Segundo(s)</option>
            <option value="5000">5 Segundo(s)</option>
            <option value="6000">6 Segundo(s)</option>
            <option value="7000">7 Segundo(s)</option>
            <option value="8000">8 Segundo(s)</option>
            <option value="9000">9 Segundo(s)</option>
            <option value="10000">10 Segundo(s)</option>
    </select><br><br>

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
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle text-center">Tipo</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle text-center">Nome</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle text-center">Tabela</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle text-center">Qtd Linhas</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle text-center">Linhas Importadas</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle text-center">%</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>

                @forelse($importacoes as $importacao)

                <tr>

                    <td>
                    <div class="d-flex px-2">
                      <div>
                        <img src="../assets/img/file-formats/{{$importacao->coluna_2}}.png" class="avatar avatar-sm rounded-circle me-2">
                      </div>
                      <div class="my-auto">
                        <h6 class="mb-0 text-sm">{{$importacao->coluna_2}}</h6>
                      </div>
                    </div>
                  </td>

                  <td class="align-middle text-center">
                    <p class="text-sm font-weight-bold mb-0">{{$importacao->coluna_1}}</p>
                  </td>

                  <td class="align-middle text-center">
                    <p class="text-sm font-weight-bold mb-0">{{$importacao->coluna_5}}</p>
                  </td>

                  <td class="align-middle text-center">
                    <p class="text-sm font-weight-bold mb-0">@if($importacao->coluna_3 > 0 ) {{number_format($importacao->coluna_3,0,",",".") }} @endif</p>
                  </td>

                  <td class="align-middle text-center">
                    <p class="text-sm font-weight-bold mb-0">@if($importacao->coluna_4 > 0) {{number_format($importacao->coluna_4,0,",",".")}} @endif</p>
                  </td>

                  <td class="align-middle text-center">
                    <div class="d-flex align-items-center justify-content-center">
                      <span class="me-2 text-xs font-weight-bold">{{$importacao->coluna_3 > 0 ? number_format((($importacao->coluna_4/$importacao->coluna_3) * 100), 2, ',', '.') : 0 }}</span>
                      <div>
                        <div class="progress">
                          <div class="progress-bar bg-gradient-info" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: {{$importacao->coluna_3 > 0 ? number_format((($importacao->coluna_4/$importacao->coluna_3) * 100), 0, ',', '.') : 0 }}%;"></div>
                        </div>
                      </div>
                    </div>
                  </td>



                  <td class="align-middle">
                    <button class="btn btn-link text-secondary mb-0">
                      <i class="fa fa-ellipsis-v text-xs"></i>
                    </button>
                  </td>

                </tr>
                @empty

                @endforelse


              </tbody>
            </table>
            <div>{{$importacoes->links()}}</div>

          </div>
        </div>
      </div>
    </div>
  </div>

</div>
