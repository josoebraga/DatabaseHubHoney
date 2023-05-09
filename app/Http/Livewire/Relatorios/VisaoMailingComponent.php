<?php

namespace App\Http\Livewire\Relatorios;

use Livewire\Component;
use Livewire\WithPagination;

use App\Models\Modificacoes;
use App\Models\Tabelas;
use Illuminate\Support\Carbon;

class VisaoMailingComponent extends Component
{

    use WithPagination;

    private $tabelas;
    public $tabelaSelecionada;
    public $data;
    private $historicos;
    private $contagens;
    private $total;

    public function carregar()
    {
        $carbon = Carbon::parse($this->data);
        $dataFormatada = $carbon->format('Y-m-d');
        $colunas = [];
        $colunasTmp = [];
        $this->historicos = Modificacoes::leftJoin('users', 'users.id', '=', 'modificacoes.user_id')->where('nome_tabela', '=', "$this->tabelaSelecionada")->whereRaw("to_char(modificacoes.created_at, 'YYYY-MM-DD') = '$dataFormatada'")->select('modificacoes.*', 'users.name')->orderBy('id', 'asc')->paginate(100);
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

    public function mount()
    {
        if(!empty($this->tabelaSelecionada) && !empty($this->data)) {
            $this->carregar();
        }
    }

    public function render()
    {

        $this->tabelas = Tabelas::tabelas();
        $this->mount();
        return view('livewire.relatorios.visao-mailing-component');
    }
}
