<div>
    {{-- To attain knowledge, add things every day; To attain wisdom, subtract things every day. --}}

    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Criar Tabela:</h6>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">

                        <div class="mb-3">
                            <label for="table_name" class="form-label">Nome da Tabela</label>
                            <input wire:model="tableName" type="text" class="form-control" id="table_name">
                        </div>

                        <div class="mb-3">
                            <h5>Colunas</h5>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Nome</th>
                                        <th>Tipo</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($columns as $index => $column)
                                        <tr>
                                            <td>
                                                <input wire:model="columns.{{ $index }}.name" type="text"
                                                    class="form-control">
                                            </td>
                                            <td>
                                                <select wire:model="columns.{{ $index }}.type"
                                                    class="form-control">
                                                    <option value="">Selecione o Tipo</option>
                                                    <option value="string">String</option>
                                                    <option value="text">Text</option>
                                                    <option value="integer">Integer</option>
                                                    <option value="bigInteger">BigInteger</option>
                                                    <option value="boolean">Boolean</option>
                                                    <!-- Adicione outros tipos de dados conforme necessário -->
                                                </select>
                                            </td>
                                            <td>
                                                <button wire:click="removeColumn({{ $index }})"
                                                    class="btn btn-sm btn-danger">Remover</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <button wire:click="addColumn" class="btn btn-sm btn-primary">Adicionar Coluna</button>
                        </div>

                        <button wire:click="createTable" class="btn btn-primary">Criar Tabela</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
