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
        ####### Fim


        #dd('Ok');

        return redirect()->route('monitoramento');

    }

}
