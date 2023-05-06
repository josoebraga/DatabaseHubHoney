<?php

namespace App\Http\Livewire\Historico;
use Livewire\Component;

use App\Models\Modificacoes;

class HistoricoComponent extends Component
{
    public function render()
    {

        $historicos = Modificacoes::all();
        #dd($historicos);
        foreach($historicos as $key => $historico) {
            dd(json_decode($historico->historico));
        }


        return view('livewire.historico.historico-component');
    }
}
