<?php

namespace App\Http\Livewire\Historico;
use Livewire\Component;

use App\Models\Modificacoes;
use App\Models\Tabelas;
use Illuminate\Support\Carbon;

class HistoricoComponent extends Component
{

    private $tabelas;

    public function mount()
    {
        $this->tabelas = Tabelas::tabelas();
    }
    public function render()
    {
        $data = "2023-05-06 19:45:26.000";
        $carbon = Carbon::parse($data);
        $dataFormatada = $carbon->format('Y-m-d');
        $historicos = Modificacoes::where('nome_tabela', '=', 'lista_de_clientes_vip')->whereRaw("to_char(created_at, 'YYYY-MM-DD') = '$dataFormatada'")->get();
        #dd($historicos);
        foreach($historicos as $key => $historico) {
            #dd(json_decode($historico->historico));
        }


        return view('livewire.historico.historico-component');
    }
}
