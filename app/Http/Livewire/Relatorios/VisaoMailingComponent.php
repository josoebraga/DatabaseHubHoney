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

    public function carregar()
    {
        $carbon = Carbon::parse($this->data);
        $dataFormatada = $carbon->format('Y-m-d');
        $this->historicos = Modificacoes::leftJoin('users', 'users.id', '=', 'modificacoes.user_id')->where('nome_tabela', '=', "$this->tabelaSelecionada")->whereRaw("to_char(modificacoes.created_at, 'YYYY-MM-DD') = '$dataFormatada'")->select('modificacoes.*', 'users.name')->orderBy('id', 'asc')->paginate(100);
        #dd($this->historicos);
        #foreach($this->historicos as $key => $historico) {
        #    dd(json_decode($historico->historico));
        #}


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
