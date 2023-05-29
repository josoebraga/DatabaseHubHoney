<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class CreateTable extends Component
{
    public $tableName;
    public $columns = [];

    public function addColumn()
    {
        $this->columns[] = ['name' => '', 'type' => ''];
    }

    public function removeColumn($index)
    {
        unset($this->columns[$index]);
        $this->columns = array_values($this->columns);
    }

    public function createTable()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id(); // Adiciona uma coluna de chave primária com o nome "id"
            $table->timestamps(); // Adiciona colunas "created_at" e "updated_at"
    
            foreach ($this->columns as $column) {
                $table->{$column['type']}($column['name']);
            }
        });
    
        $this->reset(['tableName', 'columns']);
    }

    public function render()
    {
        return view('livewire.create-table');
    }
}
