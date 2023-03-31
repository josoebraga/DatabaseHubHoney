<?php

namespace App\Http\Livewire\Import;
use App\Models\Teste;

use Livewire\Component;

use Livewire\WithPagination;


class Monitoramento extends Component
{

    use WithPagination;
    public $tempoRefresh;

    public function render()
    {

        if(empty(session('tempoRefresh'))) {
            session(['tempoRefresh' => 10000]);
        } else {
            $this->tempoRefresh = session('tempoRefresh');
        }

        $importacoes = Teste::orderBy('updated_at', 'desc')->paginate(10);
        return view('livewire.import.monitoramento',
                    [
                        'importacoes' => $importacoes,
                        #'tempoRefresh' => $this->tempoRefresh,
                    ]);
    }

    public function atualizaValorSessao()
    {
        if(empty(session('tempoRefresh'))) {
            $this->tempoRefresh = 10000;
        }
        if(!empty($this->tempoRefresh)) {
            session(['tempoRefresh' => $this->tempoRefresh]);
        } else {
           $this->tempoRefresh = session('tempoRefresh');
        }
        return redirect()->route('monitoramento');

    }

}
