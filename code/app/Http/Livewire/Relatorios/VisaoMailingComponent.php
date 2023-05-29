<?php

namespace App\Http\Livewire\Relatorios;

use Livewire\Component;
use Livewire\WithPagination;

use App\Models\Modificacoes;
use App\Models\Tabelas;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\StreamedResponse;

class VisaoMailingComponent extends Component
{

    use WithPagination;

    private $tabelas;
    public $tabelaSelecionada;
    public $data;
    private $historicos;
    private $contagens;
    private $colunasTabela;
    private $total;
    public array $dataset = [];
    public array $labels = [];
    public $qtdTotalDeRegistrosTabela;
    public $showConfirmation = false;
    public $mensagem = null;

    protected $listeners = [
        'carregar' => 'carregar',
    ];

    public function carregar()
    {
        $carbon = Carbon::parse($this->data);
        $dataFormatada = $carbon->format('Y-m-d');
        $colunas = [];
        $colunasTmp = [];
        $this->historicos = Modificacoes::leftJoin('users', 'users.id', '=', 'modificacoes.user_id')->where('nome_tabela', '=', "$this->tabelaSelecionada")->whereRaw("to_char(modificacoes.created_at, 'YYYY-MM-DD') = '$dataFormatada'")->select('modificacoes.*', 'users.name')->orderBy('id', 'asc')->paginate(100);

        $registrosTabela = DB::select("select count(*) qtd from $this->tabelaSelecionada where TO_CHAR(created_at, 'YYYY-MM-DD') = '$dataFormatada'");
        foreach($registrosTabela as $qtdReg) {
            $this->qtdTotalDeRegistrosTabela = $qtdReg->qtd;
        }

        #dd($this->historicos);

        foreach($this->historicos as $key => $historico) {
            #dd(json_decode($historico->historico));
            $json = json_decode($historico->historico);
            if (isset($json) && property_exists($json, 'invalidos')) {

                foreach ($json->invalidos as $key => $value) {
                        array_push($colunas, [$key => 0]);
                }
            }
        }

        $resultado = array_reduce($colunas, function($acumulado, $atual) {
            foreach ($atual as $chave => $valor) {
                if (!isset($acumulado[$chave])) {
                    $acumulado[$chave] = [];
                }
                $acumulado[$chave][] = $valor;
            }
            return $acumulado;
        }, []);

        $this->contagens = array_map(function($valor) {
            return count($valor);
        }, $resultado);
        $this->total = array_sum($this->contagens);

    }

    public function restaurar()
    {
        $carbon = Carbon::parse($this->data);
        $dataFormatada = $carbon->format('Y-m-d');
        $chave = null;
        $update = [];
        #$dados = DB::table($this->tabelaSelecionada)->whereRaw("TO_CHAR(created_at, 'YYYY-MM-DD') = ?", [$dataFormatada])->get();
        $dados = DB::table('teste')->where('coluna_5', '=', $this->tabelaSelecionada)->whereRaw("TO_CHAR(created_at, 'YYYY-MM-DD') = ?", [$dataFormatada])->select('coluna_8 as chave')->orderByRaw('created_at desc')->limit(1)->get();
        foreach ($dados as $key => $value) {
            $chave = $value->chave;
        }

        #############################################################

        $modificacoes = Modificacoes::leftJoin('users', 'users.id', '=', 'modificacoes.user_id')->where('nome_tabela', '=', "$this->tabelaSelecionada")->whereRaw("to_char(modificacoes.created_at, 'YYYY-MM-DD') = '$dataFormatada'")->select('modificacoes.*', 'users.name')->orderBy('id', 'asc')->get();

        #dd($this->historicos);

        foreach($modificacoes as $key => $historico) {
            #dd(json_decode($historico->historico));
            #$json = json_decode($historico->historico, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            $json = json_decode($historico->historico);
            #dd($json->old);
            $chaveAtual = null;
            if (property_exists($json, 'old')) {
                #dd($json->old);
                foreach ($json->old as $key => $value) {
                    if($key == $chave) {
                        $chaveAtual = $value;
                    }
                    $update[$key] = $value;
                } #dd($update);

                $now = Carbon::now();
                $value = $now->format('Y-m-d H:i:s');
                $update['updated_at'] = $value;

                DB::table($this->tabelaSelecionada)
                ->where("$chave", '=', "$chaveAtual")
                ->update($update);
            }
        }

        #############################################################

        $this->mount('Ok');

    }

    public function limparMensagem()
    {
        $this->mensagem = null;
        #$this->mount(null);
    }

    public function delete()
    {

        $table = $this->tabelaSelecionada;
        $carbon = Carbon::parse($this->data);
        $dataFormatada = $carbon->format('Y-m-d');

        // Obter todas as colunas da tabela selecionada
        $columns = Schema::getColumnListing($table);

        // Remover as colunas "created_at" e "updated_at" da lista de colunas
        $columns = array_diff($columns, ['created_at', 'updated_at']);

        $data = DB::table('public.modificacoes')
                    ->whereRaw("nome_tabela = '$this->tabelaSelecionada'")
                    ->whereRaw("to_char(created_at, 'YYYY-MM-DD') = '$dataFormatada'")
                    ->delete();

        $data = DB::table( $this->tabelaSelecionada)
                    ->select($columns)
                    ->whereRaw("to_char(created_at, 'YYYY-MM-DD') = '$dataFormatada'")
                    ->delete();

                    try {
                        DB::statement("REFRESH MATERIALIZED VIEW public.view_$table;");
                    } catch (Exception $e) {
                    }

                    $this->mount(null);
    }

    public function export()
    {
            $table = $this->tabelaSelecionada;
            $carbon = Carbon::parse($this->data);
            $dataFormatada = $carbon->format('Y-m-d');

            try {
                DB::statement("DROP MATERIALIZED VIEW public.$table");
            } catch (Exception $e) {
            }
            try {
                DB::statement("CREATE MATERIALIZED VIEW public.view_$table AS SELECT * FROM public.$table;");
            } catch (Exception $e) {
            }
            try {
                DB::statement("REFRESH MATERIALIZED VIEW public.view_$table;");
            } catch (Exception $e) {
            }

            $view = "view_$table";

            // Obter todas as colunas da tabela selecionada
            $columns = Schema::getColumnListing($table);

            // Remover as colunas "created_at" e "updated_at" da lista de colunas
            $columns = array_diff($columns, ['created_at', 'updated_at', 'id']);

            $data = DB::table($view)->select($columns)->whereRaw("to_char(created_at, 'YYYY-MM-DD') = '$dataFormatada'")->orderByRaw('1 asc')->get();

            $response = new StreamedResponse(function () use ($data) {

            $handle = fopen('php://output', 'w');

            // Defina o delimitador como ";"
            $delimiter = ';';

            // Obtenha as colunas da tabela selecionada
            $columns = !empty($data) ? array_keys((array) $data[0]) : [];

            // Escreva o cabeçalho do arquivo CSV com o delimitador personalizado
            fputcsv($handle, $columns, $delimiter);

            // Escreva os dados com o delimitador personalizado
            foreach ($data as $item) {
                fputcsv($handle, (array) $item, $delimiter);
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename='.$this->tabelaSelecionada.'_resultado');

        return $response;
    }

    public function mount($mensagem = null)
    {
        if(!empty($this->tabelaSelecionada) && !empty($this->data)) {
            $this->carregar();
            if($mensagem) {
                $this->mensagem = $mensagem;
            }
        }
    }

    public function render()
    {

        $this->tabelas = Tabelas::tabelas();
        $this->mount(null);
        return view('livewire.relatorios.visao-mailing-component');
    }
}
