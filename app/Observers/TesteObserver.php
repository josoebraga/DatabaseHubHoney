<?php

namespace App\Observers;

use App\Models\Teste;
use App\Jobs\Import\ImportJob;

class TesteObserver
{
    /**
     * Handle the Teste "created" event.
     *
     * @param  \App\Models\Teste  $teste
     * @return void
     */
    public function created(Teste $teste)
    {
        //
    }

    /**
     * Handle the Teste "updated" event.
     *
     * @param  \App\Models\Teste  $teste
     * @return void
     */
    public function updated(Teste $teste)
    {
        ImportJob::dispatch($teste); #dd('ImportJob::dispatch($teste)');
    }

    /**
     * Handle the Teste "deleted" event.
     *
     * @param  \App\Models\Teste  $teste
     * @return void
     */
    public function deleted(Teste $teste)
    {
        //
    }

    /**
     * Handle the Teste "restored" event.
     *
     * @param  \App\Models\Teste  $teste
     * @return void
     */
    public function restored(Teste $teste)
    {
        //
    }

    /**
     * Handle the Teste "force deleted" event.
     *
     * @param  \App\Models\Teste  $teste
     * @return void
     */
    public function forceDeleted(Teste $teste)
    {
        //
    }
}
