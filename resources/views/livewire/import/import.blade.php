<div>
    {{-- Success is as dangerous as failure. --}}

  <div class="row">
    <div class="col-12">
      <div class="card mb-4">
        <div class="card-header pb-0">
          <h6>Tratamento de Mailing:</h6>
        </div>
        <div class="card-body px-0 pt-0 pb-2">
          <div class="table-responsive p-0">

{{--             <form wire:submit.prevent="save">
                <input type="file" wire:model="arquivo" class="custom-file-input">

                @error('arquivo') <span class="error">{{ $message }}</span> @enderror

                <button type="submit" class="btn btn-outline-primary btn-sm mb-0">Save Photo</button>
            </form>
 --}}
    <div>
        <form>
            {{csrf_field()}}
            <div
            x-data="{ isUploading: false, progress: 0 }"
            x-on:livewire-upload-start="isUploading = true"
            x-on:livewire-upload-finish="isUploading = false"
            x-on:livewire-upload-error="isUploading = false"
            x-on:livewire-upload-progress="progress = $event.detail.progress"
        >


        <div class="container">
            <div class="row">

            <br><br>

              <div class="col-sm">
                <span>Escolha o arquivo a ser importado:</span>
                <!-- File Input -->
                &nbsp;&nbsp;&nbsp;&nbsp;
                <input type="file" id="arquivo" wire:model="arquivo" class="form-control-sm" {{-- class="custom-file-input" --}}>

                @error('arquivo') <span class="error">{{ $message }}</span> @enderror

                <!-- Progress Bar -->
                <div x-show="isUploading">
                    &nbsp;&nbsp;
                    <progress max="100" x-bind:value="progress"></progress>
                </div>
              </div>


              </div>



              <br><br>

              </div>

              <div class="col-sm">
                <span>Escolha a tabela onde deseja salvar os dados:</span>
                <select wire:model="tabelaSelecionada" class="form-control-sm" name="choices-button" id="choices-button" placeholder="Departure">
                    <option value="" selected="">(Escolha uma tabela)</option>
                    @forelse ($tabelas as $tabela)
                        <option value="{{$tabela->table_name}}">{{$tabela->table_name}}</option>
                    @empty

                    @endforelse
                </select>
              </div>
              @if($tabelaSelecionada)
              <br><br>
              <div class="col-sm">
                <span>Escolha a coluna que servirá para verificar se o registro existe:</span>
                <select wire:model="colunaSelecionada" class="form-control-sm" name="choices-button" id="choices-button" placeholder="Departure">
                    <option value="" selected="">(Escolha uma coluna)</option>
                    @forelse ($colunas as $coluna)
                        <option value="{{$coluna->column_name}}">{{$coluna->column_name}}</option>
                    @empty

                    @endforelse
                </select>
              </div>
              @endif
              <br><br>
              @if($arquivo)
              @if(!empty($tabelaSelecionada) && !empty($colunaSelecionada))
              <div class="col-sm">
                <button type="submit" wire:click.prevent="save" class="btn btn-outline-primary btn-sm mb-0">Iniciar</button>
              </div>
              <br><br>
              @endif
              @endif

            </div>
          </div>

        </form>
    </div>


          </div>
        </div>
      </div>
    </div>
  </div>

</div>
<script>
  let file = document.querySelector('input[type="file"]').files[0]

  // Upload a file:
  @this.upload('arquivo', file, (uploadedFilename) => {
      // Success callback.
  }, () => {
      // Error callback.
  }, (event) => {
      // Progress callback.
      // event.detail.progress contains a number between 1 and 100 as the upload progresses.
  })

  // Upload multiple files:
  @this.uploadMultiple('arquivos', [file], successCallback, errorCallback, progressCallback)

  // Remove single file from multiple uploaded files
  @this.removeUpload('arquivos', uploadedFilename, successCallback)
</script>
