<?php

namespace App\Jobs\Import;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

use App\Models\Teste;
use App\Models\NaoPerturbe;

use \PhpOffice\PhpSpreadsheet\Reader\Csv as ReadCsv;


class ImportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $importador = Teste::orderBy('created_at', 'desc')->select('id')->first();
        //$quantidadeDeLinhas = $this->count($importador->id);
        //DB::update("update teste set coluna_3 = $quantidadeDeLinhas where id = $importador->id");
        $this->import($importador->id);
        return true;
    }

    public function import($id)
    {

        ini_set('memory_limit', '8192M');

        touch('system.lock');
        unlink('system.lock');
        clearstatcache();

        #### Preciso: ###
        //  Importar dinamicamente pra tabela escolhida;
        //  Importar só registros novos;
        //  azer update dos registros já existentes;
        //  Gravar Log do que foi alterado (updated);

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
return true; ###
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

            $colunasName = '';
            $values = '';
            $acao = '';
            $set = '';

            foreach($finais as $key => $final) {

                foreach($final as $key => $f) {

                $colunasTemp = $key;
                $colunasName = $colunasName.', '."\"$colunasTemp\"";

                $valueTemp = $f;
                $values = $values.', '."'$valueTemp'";

                    # if coluna_8 existe na $tabelaSelecionada: update else insert
                    if( $colunasTemp == $colunaBaseComparacaoUpdate) {
                        $dadosExistentes = DB::select("select * from \"$tabelaSelecionada\" where \"$colunaBaseComparacaoUpdate\" = '$valueTemp'");
                        foreach($dadosExistentes as $dadoExistente){

                            $valorAntigo = $dadoExistente->$colunasTemp;
                            $where = "Where \"$colunaBaseComparacaoUpdate\" ="."'".$dadoExistente->$colunaBaseComparacaoUpdate."'";

                        }
                    }

                    $set = $set.', '."\"$colunasTemp\" = '$valueTemp'";

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
                DB::insert($insert);
            } else if($acao == 'update') {
                $set = 'set '.substr($set, 2, strlen($set)).', updated_at = NOW()';
                $update =
                "update $tabelaSelecionada
                $set
                $where";
                #dd($update);
                #$valorAntigo;
                DB::update($update);
            }

            # Atualiza a quantidade de linhas importadas no monitoramento
            # No futuro mostrar quantas foram atualizadas e quantos registros são novos
            DB::update("update teste set coluna_4 = $i where id = $id");
            $i++;
        }

        unset($worksheet);
        unset($i);

        return true;

    }

}
