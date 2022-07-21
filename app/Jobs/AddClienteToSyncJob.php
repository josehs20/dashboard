<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\ImportXml;

class AddClienteToSyncJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $filename;
    private $empresa;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($empresa, $filename)
    {
        $this->filename = $filename;
        $this->empresa  = $empresa;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $importados = ImportXml::clientes($this->empresa, $this->filename);
       // $this->empresa->arquivos()->where('nome', $this->filename)->update(['processado' => true]);

        $this->empresa->update(['ultima_sincronizacao' => now()]);
        $this->empresa->logs()->create([ 'log' => "[CLIENTE] Foram importados {$importados}"]);
    }
}
