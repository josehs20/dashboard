<?php

namespace App\Jobs;

use App\Services\ImportXml;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AddFunarioToSyncJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $empresa;
    private $filename;

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
        $importados = ImportXml::funarios($this->empresa, $this->filename);

        $this->empresa->arquivos()->where('nome', $this->filename)->update(['processado' => true]);

        $this->empresa->update(['ultima_sincronizacao' => now()]);
        $this->empresa->logs()->create(['log' => "[FUNCIONARIOS] Foram importados {$importados}"]);
    }
}
