<?php

namespace App\Console\Commands\Jobs;

use Illuminate\Console\Command;
use App\Jobs\AddProdutoToSyncJob;
use Illuminate\Support\Facades\Storage;
use App\Models\Empresa;
use App\Services\ImportXml;

class AddProdutoToSyncCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'add-job:produto';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $empresas = Empresa::where('sincronizar', true)->get();
        foreach($empresas as $empresa) {
            
            $files = Storage::disk('ftp')->files("{$empresa->pasta}"); 

            foreach($files as $file) {
                if($this->filePermited($empresa, $file)) {

                    Storage::disk('local')->put($file, Storage::disk('ftp')->get($file));
                    $empresa->arquivos()->create([
                        'nome' => $file
                    ]);

                    $job = (new AddProdutoToSyncJob($empresa, $file))->delay(now()->addSeconds(3));
                    dispatch($job)->onQueue('vendasProdSaldos');
                    
                    echo "Added {$file}\n";
                }
                                
            }
        }
    }

    private function filePermited($empresa, $file) {
        return (str_contains($file, '-PRODUTO-') and $empresa->arquivos()->where('nome', $file)->count() == 0);
    }
}
