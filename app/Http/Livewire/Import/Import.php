<?php

namespace App\Http\Livewire\Import;
use Livewire\Component;
use Livewire\WithFileUploads;

use Illuminate\Support\Facades\Storage;

use App\Models\Teste;
use App\Models\Tabelas;
use App\Models\NaoPerturbe;
use \PhpOffice\PhpSpreadsheet\Reader\Csv as ReadCsv;
use PhpOffice\PhpSpreadsheet\Spreadsheet as Spreadsheet;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

# https://stackoverflow.com/questions/46141652/running-laravel-queuework-on-a-shared-hosting
# https://talltips.novate.co.uk/laravel/using-queues-on-shared-hosting-with-laravel

class Import extends Component
{
    use WithFileUploads;
    public $arquivo;
    public $tabelaSelecionada;
    public $colunas;
    public $colunaSelecionada;

    public function render()
    {
        $tabelas = Tabelas::tabelas();
        if(!empty($this->tabelaSelecionada)) {
            $this->colunas = $this->colunas();
        } else {
            $this->colunas = [];
        }
        return view('livewire.import.import', ['tabelas' => $tabelas]);
    }


    public function colunas()
    {
        return DB::select("
        SELECT
        column_name
        FROM
        information_schema.columns
        WHERE table_name = '$this->tabelaSelecionada' and
        column_name not in ('id', 'updated_at', 'created_at')
        order by ordinal_position
        ");
    }

    ####################################################################

    protected function validarEndereco($cep)
    {
        $response = Http::get("http://viacep.com.br/ws/$cep/json");
        return $response->json();
    }

    protected function validarEmail($email)
    {
        if(!preg_match('/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/', $email)){
            return 'inválido';
           }else{
            return 'válido';
           }
    }

    protected function validarTelefone($telefone)
    {
        // Verificar se o telefone possui 10 ou 11 dígitos
        if (!preg_match('/^(\d{10}|\d{11})$/', $telefone)) {
            return "inválido";
        } else {
            return "válido";
        }
    }

    protected function validarCpfCnpj(String $tipo, String $cpf, String $cnpj)
    {
        if($tipo == 'cpf') {


            if(!preg_match('/^\d{3}\d{3}\d{3}\d{2}$/', $cpf)) {
                return "inválido";
            }

            // Verificar se o CPF possui 11 dígitos
            if (strlen($cpf) != 11) {
                return "inválido";
            }

            // Verificar se todos os dígitos são iguais
            if (preg_match('/(\d)\1{10}/', $cpf)) {
                return "inválido";
            }

            // Verificar se o CPF é válido
            $digito1 = 0;
            $digito2 = 0;

            for ($i = 0, $j = 10; $i < 9; $i++, $j--) {
                $digito1 += $cpf[$i] * $j;
            }

            $resto = $digito1 % 11;
            $digito1 = ($resto < 2) ? 0 : (11 - $resto);

            for ($i = 0, $j = 11; $i < 10; $i++, $j--) {
                $digito2 += $cpf[$i] * $j;
            }

            $resto = $digito2 % 11;
            $digito2 = ($resto < 2) ? 0 : (11 - $resto);

            if (($cpf[9] != $digito1) || ($cpf[10] != $digito2)) {
                return "inválido";
            } else {
                return "válido";
            }

        } else if($tipo == 'cnpj') {

            if(!preg_match('/^\d{2}\d{3}\d{3}\d{4}\d{2}$/', $cnpj)) {
                return "inválido";
            }

            // Verificar se o CNPJ possui 14 dígitos
            if (strlen($cnpj) != 14) {
                return "inválido";
            }

            // Verificar se todos os dígitos são iguais
            if (preg_match('/(\d)\1{13}/', $cnpj)) {
                return "inválido";
            }

            // Verificar se o CNPJ é válido
            $soma1 = 0;
            $soma2 = 0;
            $peso = 5;

            for ($i = 0; $i < 12; $i++) {
                $soma1 += $cnpj[$i] * $peso;

                if ($peso == 2) {
                    $peso = 9;
                } else {
                    $peso--;
                }
            }

            $resto = $soma1 % 11;
            $digito1 = ($resto < 2) ? 0 : (11 - $resto);

            $peso = 6;

            for ($i = 0; $i < 13; $i++) {
                $soma2 += $cnpj[$i] * $peso;

                if ($peso == 2) {
                    $peso = 9;
                } else {
                    $peso--;
                }
            }

            $resto = $soma2 % 11;
            $digito2 = ($resto < 2) ? 0 : (11 - $resto);

            if (($cnpj[12] != $digito1) || ($cnpj[13] != $digito2)) {
                return "inválido";
            } else {
                return "válido";
            }


        }

    }

    public function save()
    {

        $this->validate([
            #'arquivo' => 'mimes:xls,txt,csv,xlsx|max:12288', // 1MB Max
            'arquivo' => 'mimes:xls,txt,csv,xlsx', // 1MB Max
        ]);

        #dd(Auth::user()->id);

        $teste = Teste::create([
            'coluna_1' => 'Teste: Coluna 1'
        ]);
        $this->arquivo->storeAs("public/arquivos/$teste->id/", "$teste->id.csv");
        $teste = Teste::find($teste->id);
        $teste->coluna_1 = "$teste->id.csv";
        $teste->coluna_2 = "csv";
        #$teste->coluna_3 = $this->tabelaSelecionada;
        $teste->coluna_4 = 0;
        $teste->coluna_5 = $this->tabelaSelecionada;
        $teste->coluna_6 = 0;
        $teste->coluna_7 = 0;
        $teste->coluna_8 = $this->colunaSelecionada;
        $teste->user_id = Auth::user()->id;
        $teste->save();

        ####### início
#/*


        # Busca o ID a se trabalhar

        $importador = Teste::orderBy('created_at', 'desc')->select('id', 'user_id', 'coluna_5', 'coluna_1', 'coluna_8')->first();
        $id = $importador->id;
        $userId = $importador->user_id;
        $tabelaSelecionada = $importador->coluna_5;
        $arquivo = $importador->coluna_1;
        $colunaBaseComparacaoUpdate = $importador->coluna_8;

        # Carrega o arquivo

        $reader = new ReadCsv();
        $reader->setInputEncoding('CP1252');
        $reader->setDelimiter(';');
        $reader->setEnclosure('');
        $reader->setSheetIndex(0);
        $spreadsheet = $reader->load(storage_path("app/public/arquivos/$id/".$arquivo));
        $worksheet = $spreadsheet->getActiveSheet();
        $i = 2;

        # Preenche um valor nas células vazias

        foreach ($worksheet->getRowIterator() as $row) {
            foreach ($row->getCellIterator() as $cell) {
                $value = $cell->getValue();
                if (empty($value)) {
                    $cell->setValue('null');
                }
            }
        }

        # Busca as colunas da tabela escolhida

        $colunasDatabelaSelecionada = DB::select("
        SELECT
        column_name
        FROM
        information_schema.columns
        WHERE table_name = '$tabelaSelecionada' and
        column_name not in ('id', 'updated_at', 'created_at')
        order by ordinal_position
        ");

        $colunas = [];

        $i = 1;
        foreach ($colunasDatabelaSelecionada as $key => $data) {
            foreach ($data as $k => $d) {
                array_push(
                            $colunas, $spreadsheet->getActiveSheet()->getCellByColumnAndRow($i, 1)->getValue()
                            );
                $i++;
            }
        }

        # Montar os arrays a importar #################################

        $countArray = count($colunas);
        $arrayInsert = [];
        $arrayInsertFinal = [];

        # Associa a coluna do BD com o respectivo dado do arquivo

        $i = 2;
        while($worksheet->getCell('A'.$i)->getValue() != '') {
            for($j=1; $j<=$countArray; $j++) {
                try {
                    array_push($arrayInsert, [ $colunas[$j-1] =>  $spreadsheet->getActiveSheet()->getCellByColumnAndRow(($j), ($i))->getValue()]);
                } catch (Exception $e) {}
                if(count($arrayInsert) == $countArray) {
                    array_push($arrayInsertFinal, $arrayInsert);
                    $arrayInsert = [];
                }
            }
            $i++;
        }

        # Atualiza a quantidade de linhas a importar no monitoramento

        $quantidadeDeLinhas = count($arrayInsertFinal);
        DB::update("update teste set coluna_3 = $quantidadeDeLinhas where id = $id");

        # Monta o insert dinamicamente

        $i = 1;

        # Trocar a posição da coluna $colunaBaseComparacaoUpdate para o [0]

        $arrayTempTroca = $arrayInsertFinal;
        $chavePrincipal = 0;

        foreach($arrayInsertFinal as $key => $finais) {
            foreach($finais as $k => $final) {
                    foreach($final as $j => $f) {
                        if(trim($j) == trim($colunaBaseComparacaoUpdate) && $key > 0) {
                            $chavePrincipal = $k;
                        }
                    }
            }
        }

        $i = 0;
        while($i <= count($arrayTempTroca)-1) {
            if($arrayTempTroca[$i][$chavePrincipal] > 0) {
                $temp = $arrayTempTroca[$i][0];
                $arrayTempTroca[$i][0] = $arrayTempTroca[$i][$chavePrincipal];
                $arrayTempTroca[$i][$chavePrincipal] = $temp;
            }
            $i++;
        }

        unset($arrayInsertFinal);
        $arrayInsertFinal = $arrayTempTroca;
        unset($arrayTempTroca);

        $linhasImportadas = 1;

        foreach($arrayInsertFinal as $finais) {

            unset($dadosAntigos);
            unset($dadosNovos);
            $json = array();
            $invalido = false;
            $colunasName = '';
            $values = '';
            $retornos = [];
            $acao = '';
            $set = '';
            $where = '';

            foreach($finais as $key => $final) {

                foreach($final as $key => $f) {

                $colunasTemp = trim($key);
                if(empty($colunasTemp)) {
                    $colunasTemp = 'NULL';
                }
                $valueTemp = trim($f);
                $retornoTemp = '';

                ####### Fazer o descarte de dados inválidos ###############################################################

                if(strpos(strtolower('_'.$colunasTemp), strtolower('CPF')) > 0 || strpos(strtolower('_'.$colunasTemp), strtolower('CNPJ')) > 0) { # Str Contains
                    $valueTemp = preg_replace('/[^0-9]/', '', trim($valueTemp));
                    $tipo = 'cpf';
                    if(strlen($valueTemp) > 11) {
                        $tipo = 'cnpj';
                    }
                    $retornoTemp = $this->validarCpfCnpj($tipo, $valueTemp, $valueTemp);
                    if($retornoTemp == 'inválido') {
                        array_push($retornos, [$colunasTemp => $retornoTemp]);
                        $invalido = true;
                    }
                } else if(strpos(strtolower('_'.$colunasTemp), strtolower('FONE')) > 0) {
                    // Remover espaços, parênteses e hífens do telefone
                    $valueTemp = preg_replace('/[\s()+-]/', '', trim($valueTemp));
                    $retornoTemp = $this->validarTelefone($valueTemp);
                    if($retornoTemp == 'inválido') {
                        array_push($retornos, [$colunasTemp => $retornoTemp]);
                        $invalido = true;
                    }
                } else if(strpos(strtolower('_'.$colunasTemp), strtolower('EMAIL')) > 0) {
                    $retornoTemp = $this->validarEmail($valueTemp);
                    if($retornoTemp == 'inválido') {
                        array_push($retornos, [$colunasTemp => $retornoTemp]);
                    }
                } else if(strpos(strtolower('_'.$colunasTemp), strtolower('CEP')) > 0) {
                    #dd('CEP');
                    if(!preg_match('/^[0-9]{5,5}([- ]?[0-9]{3,3})?$/', $valueTemp)){
                        $retornoTemp = 'inválido';
                       } else {
                            $valueTemp = preg_replace('/[^0-9]/', '', trim($valueTemp));
                            $retornoTemp = $this->validarEndereco($valueTemp);
                            try {
                                if($retornoTemp['erro'] == true) {
                                    $retornoTemp = 'inválido';
                                }
                            } catch(Exception $e) {
                            }
                            if($retornoTemp == 'inválido') {
                                array_push($retornos, [$colunasTemp => $retornoTemp]);
                            }
                       }
                }

                ################################################################################################################

                $colunasName = $colunasName.', '."\"$colunasTemp\"";
                $valueTemp = str_replace("'", "", $valueTemp);
                $values = $values.', '."'$valueTemp'";

                $colunasSelect = substr($colunasName, 2, strlen($colunasName));
                if( strtolower(trim($colunasTemp)) == strtolower(trim($colunaBaseComparacaoUpdate))) {
                    $dadosExistentes = DB::select("select * from \"$tabelaSelecionada\" where \"$colunaBaseComparacaoUpdate\" = '$valueTemp'");
                    foreach($dadosExistentes as $dadoExistente){
                        $where = "Where \"$colunaBaseComparacaoUpdate\" ="."'".$dadoExistente->$colunaBaseComparacaoUpdate."'";
                    }
                }

                try{
                    $dadosAntigos = DB::select("select $colunasSelect from \"$tabelaSelecionada\" $where");
                    $set = $set.', '."\"$colunasTemp\" = '$valueTemp'";
                } catch (Exception $e) {}

                    if(!empty($dadosExistentes)) {
                        $acao = 'update';
                    } else {
                        $acao = 'insert';
                    }

                }
            }


            if($acao == 'insert') {
                $insert = "insert into \"$tabelaSelecionada\" ($colunasName, \"created_at\", \"updated_at\") values ($values, NOW(), NOW());";
                $insert = str_replace('(, ', '(', $insert);
                if($invalido == false) {
                    DB::insert($insert);
                }

                $colunasName = substr($colunasName, 3);
                $values = substr($values, 3);
                $json = array();
                $array1 = [$colunasName];
                $array2 = [$values];
                $parts1 = explode(",", implode(",", $array1));
                $parts2 = explode(",", implode(",", $array2));
                $array = [];
                $retorno = [];
                foreach($parts1 as $key => $passada) {
                    $parts2[$key] = str::replace('\'', '', $parts2[$key]);
                    array_push($array, [$passada => $parts2[$key]]);
                }

                array_push($json,
                [
                    'old'=>[
                        $array
                    ],
                    'new'=>[
                        $array
                    ],
                    'invalidos' => [
                        $retornos
                    ], 'acao' => [
                        'insert'
                    ]
                ]
                );

                $json = json_encode($json, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                $json = str::replace('[[', '[', $json);
                $json = str::replace(']]', ']', $json);
                $json = str::replace('[{', '{', $json);
                $json = str::replace('}]', '}', $json);
                $json = str::replace('\"', '"', $json);
                $json = str::replace('" "', '"', $json);
                $json = str::replace('" ', '"', $json);
                $json = str::replace('},{', ',', $json);

                #dd($json);

                $insertLog = "INSERT INTO public.modificacoes (nome_tabela, historico, user_id, created_at, updated_at) VALUES('$tabelaSelecionada', '$json', '$userId', NOW(), NOW());";
                $insertLog = str::replace('""', '"', $insertLog);
                DB::insert($insertLog);


            } else if($acao == 'update') {

                $set = 'set '.substr($set, 2, strlen($set)).', updated_at = NOW()';
                $update =
                "update $tabelaSelecionada
                $set
                $where";
                #dd($update);
                #dd($where);
                #$valorAntigo;
                #dd($arrayCompara[0][0]);
                if($invalido == false) {
                    DB::update($update);
                }


                try {
                    ######## Start Log #######

                    $dadosNovos = DB::select("select $colunasSelect from \"$tabelaSelecionada\" $where");
                    #dd($where, [$dadosNovos, $dadosAntigos]);
                    #if(/*$dadosNovos != $dadosAntigos &&*/ !empty($dadosNovos) && !empty($dadosAntigos)) {

                        /*dd(                [
                            'old'=>[
                                $dadosAntigos
                            ],
                            'new'=>[
                                $dadosNovos
                            ],
                            'invalidos' => [
                                $retornos
                            ], 'acao' => [
                                'update'
                            ]
                        ]
                            );*/

                    array_push($json,
                        [
                            'old'=>[
                                $dadosAntigos
                            ],
                            'new'=>[
                                $dadosNovos
                            ],
                            'invalidos' => [
                                $retornos
                            ], 'acao' => [
                                'update'
                            ]
                        ]
                    );
                    $json = json_encode($json, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                    $json = str::replace('[[', '[', $json);
                    $json = str::replace(']]', ']', $json);
                    $json = str::replace('[{', '{', $json);
                    $json = str::replace('}]', '}', $json);
                    #dd($json);
                    DB::insert("INSERT INTO public.modificacoes (nome_tabela, historico, user_id, created_at, updated_at) VALUES('$tabelaSelecionada', '$json', '$userId', NOW(), NOW());");
                    #dd("INSERT INTO public.modificacoes (nome_tabela, historico, user_id, created_at, updated_at) VALUES('$tabelaSelecionada', '$json', '$userId', NOW(), NOW());");

                    #sleep(3);

                    ######## End Log #######
                #}

                } catch (Exception $e) {}




            }

            # Atualiza a quantidade de linhas importadas no monitoramento
            # No futuro mostrar quantas foram atualizadas e quantos registros são novos

            DB::update("update teste set coluna_4 = $linhasImportadas where id = $id");
            $linhasImportadas++;
            $i++;

            #sleep(4);


        }

        #$qsCompara = array_unique($qsCompara);
        #dd($arrayCompara);

        #foreach($dadosNovos as $old) {
        #    dd($old);
        #}

        try {
            DB::statement("DROP MATERIALIZED VIEW public.$tabelaSelecionada");
        } catch (Exception $e) {
        }
        try {
            DB::statement("CREATE MATERIALIZED VIEW public.view_$tabelaSelecionada AS SELECT * FROM public.$tabelaSelecionada;");
        } catch (Exception $e) {
        }

        unset($worksheet);
        unset($i);

        #return true;


        dd('Ok');

#*/
        ####### Fim


        #dd('Ok');

        return redirect()->route('monitoramento');

    }

}
