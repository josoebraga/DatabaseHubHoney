<?php

namespace App\Http\Livewire\Import;
use Livewire\Component;
use Livewire\WithFileUploads;

use Illuminate\Support\Facades\Storage;

use App\Models\Teste;
use App\Models\NaoPerturbe;
use \PhpOffice\PhpSpreadsheet\Reader\Csv as ReadCsv;
use PhpOffice\PhpSpreadsheet\Spreadsheet as Spreadsheet;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Auth;

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
        $tabelas = $this->tabelas();
        if(!empty($this->tabelaSelecionada)) {
            $this->colunas = $this->colunas();
        } else {
            $this->colunas = [];
        }
        return view('livewire.import.import', ['tabelas' => $tabelas]);
    }


    public function tabelas()
    {
        return DB::select("
            select table_name
            from information_schema.tables
            where table_catalog = 'honey' and
            table_schema = 'public' and
            table_type = 'BASE TABLE' and
            table_name not in (
            'password_resets',
            'failed_jobs',
            'personal_access_tokens',
            'jobs',
            'teste',
            'modificacoes',
            'migrations',
            'users',
            'permissions',
            'model_has_permissions',
            'model_has_roles',
            'roles',
            'role_has_permissions',
            'users_type'
            )
        ");
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

    public function validarCpfCnpj($tipo, $cpf, $cnpj)
    {
        if($tipo == 'cpf') {

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

        $teste = Teste::create([
            'coluna_1' => 'Teste: Coluna 1',
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
        $teste->save();

        ####### início



        # Busca o ID a se trabalhar

        $importador = Teste::orderBy('created_at', 'desc')->select('id', 'coluna_5', 'coluna_1', 'coluna_8')->first();
        $id = $importador->id;
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

        # Montar os arrays a importar

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
        foreach($arrayInsertFinal as $finais) {

            unset($dadosAntigos);
            $json = array();
            $colunasName = '';
            $values = '';
            $retornos = [];
            $acao = '';
            $set = '';
            #$arrayCompara = [];

            foreach($finais as $key => $final) {

                foreach($final as $key => $f) {

                $colunasTemp = trim($key);
                $valueTemp = trim($f);
                $retornoTemp = '';

                #dd(strtolower($colunasTemp));
                if(strpos(strtolower('_'.$colunasTemp), strtolower('CPF')) > 0 || strpos(strtolower('_'.$colunasTemp), strtolower('CNPJ')) > 0) { # Str Contains
                    $valueTemp = preg_replace('/[^0-9]/', '', $valueTemp);
                    $tipo = 'cpf';
                    if(strlen($valueTemp) > 11) {
                        $tipo = 'cnpj';
                    }
                    $retornoTemp = $this->validarCpfCnpj($tipo, $valueTemp, $valueTemp);
                    array_push($retornos, [$colunasTemp => $retornoTemp]);
                } else if(strpos(strtolower('_'.$colunasTemp), strtolower('FONE')) > 0) {
                    #dd(strtolower($colunasTemp));
                    #dd(strpos($colunasTemp, 'FONE'));
                } else if(strpos(strtolower('_'.$colunasTemp), strtolower('EMAIL')) > 0) {
                    #dd(strpos($colunasTemp, 'FONE'));
                } else if(strpos(strtolower('_'.$colunasTemp), strtolower('CEP')) > 0) {
                    #dd(strpos($colunasTemp, 'CEP'));
                }

                $colunasName = $colunasName.', '."\"$colunasTemp\"";
                $values = $values.', '."'$valueTemp'";

                #dd($colunasTemp, $valueTemp);
                    # if coluna_8 existe na $tabelaSelecionada: update else insert
                    $colunasSelect = substr($colunasName, 2, strlen($colunasName));
                    if( $colunasTemp == $colunaBaseComparacaoUpdate) {
                        #if(empty($dadosAntigos)) {
                        #    $dadosAntigos = DB::select("select $colunasSelect from \"$tabelaSelecionada\" where \"$colunaBaseComparacaoUpdate\" = '$valueTemp'");
                        #}
                        $dadosExistentes = DB::select("select * from \"$tabelaSelecionada\" where \"$colunaBaseComparacaoUpdate\" = '$valueTemp'");
                        #array_push($arrayCompara, $dadosExistentes);
                        foreach($dadosExistentes as $dadoExistente){
                            $where = "Where \"$colunaBaseComparacaoUpdate\" ="."'".$dadoExistente->$colunaBaseComparacaoUpdate."'";
                        }
                    }

                        $set = $set.', '."\"$colunasTemp\" = '$valueTemp'";
                        #$valorAntigo = $dadoExistente->$colunasTemp; # <- Criar uma lógica pra comparar e atualizar apenas os valores que efetivamente mudaram...!

                    if(!empty($dadosExistentes)) {
                        $acao = 'update';
                    } else {
                        $acao = 'insert';
                    }

                }
            }

            try{
                $dadosAntigos = DB::select("select $colunasSelect from \"$tabelaSelecionada\" $where");
            } catch (Exception $e) {}

            if($acao == 'insert') {
                $insert = "insert into \"$tabelaSelecionada\" ($colunasName, \"created_at\", \"updated_at\") values ($values, NOW(), NOW());";
                $insert = str_replace('(, ', '(', $insert);
                DB::insert($insert);
                # Aqui precisa registrar que o registo chegou
                #DB::insert("INSERT INTO public.modificacoes (\"NOME_TABELA\", \"LOG\", \"USER_ID\", created_at, updated_at) VALUES('$tabelaSelecionada', '$json', ".Auth::user()->id.", NOW(), NOW());");

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

                #dd($json);

                $insertLog = "INSERT INTO public.modificacoes (\"NOME_TABELA\", \"LOG\", \"USER_ID\", \"created_at\", \"updated_at\") VALUES('$tabelaSelecionada', '$json', ".Auth::user()->id.", NOW(), NOW());";
                $insertLog = str::replace('""', '"', $insertLog);
                DB::insert($insertLog);


            } else if($acao == 'update') {

                $set = 'set '.substr($set, 2, strlen($set)).', updated_at = NOW()';
                $update =
                "update $tabelaSelecionada
                $set
                $where";
                #dd($update);
                #$valorAntigo;
                #dd($arrayCompara[0][0]);
                DB::update($update);
            }

            # Atualiza a quantidade de linhas importadas no monitoramento
            # No futuro mostrar quantas foram atualizadas e quantos registros são novos
            }

            try {
            ######## Start Log #######

            $dadosNovos = DB::select("select $colunasSelect from \"$tabelaSelecionada\" $where");
            #dd([$dadosNovos, $dadosAntigos]);
            if($dadosNovos != $dadosAntigos && !empty($dadosNovos) && !empty($dadosAntigos)) {
            array_push($json,
                [
                    'old'=>[
                        $dadosAntigos
                    ],
                    'new'=>[
                        $dadosNovos
                    ]
                ]
            );
            $json = json_encode($json, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            $json = str::replace('[[', '[', $json);
            $json = str::replace(']]', ']', $json);
            $json = str::replace('[{', '{', $json);
            $json = str::replace('}]', '}', $json);
            #dd($json);
            DB::insert("INSERT INTO public.modificacoes (\"NOME_TABELA\", \"LOG\", \"USER_ID\", created_at, updated_at) VALUES('$tabelaSelecionada', '$json', ".Auth::user()->id.", NOW(), NOW());");

            ######## End Log #######
        }

        } catch (Exception $e) {}


        DB::update("update teste set coluna_4 = $i where id = $id");
        $i++;
    #$qsCompara = array_unique($qsCompara);
        #dd($arrayCompara);

        #foreach($dadosNovos as $old) {
        #    dd($old);
        #}


        dd('Ok');


        ####### Fim

        return redirect()->route('monitoramento');

    }

}
