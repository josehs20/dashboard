<?php

namespace App\Console\Commands\jobs;

use Illuminate\Console\Command;
use App\Models\Empresa;
use App\Jobs\AddIgradeToSyncJob;
use Illuminate\Support\Facades\Storage;

class AddIgradeToSyncCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'add-job:igrade';

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
        foreach ($empresas as $empresa) {

            $files = Storage::disk('ftp')->files("{$empresa->pasta}");

            foreach ($files as $file) {
                if ($this->filePermited($empresa, $file)) {

                    Storage::disk('local')->put($file, Storage::disk('ftp')->get($file));
                    $empresa->arquivos()->create([
                        'nome' => $file
                    ]);

                    $job = (new AddIgradeToSyncJob($empresa, $file))->delay(now()->addSeconds(3));
                    dispatch($job)->onQueue('second_queue');

                    echo "Added {$file}\n";
                }
            }
        }
    }

    private function filePermited($empresa, $file)
    {
        return (str_contains($file, '-IGRADE-') and $empresa->arquivos()->where('nome', $file)->count() == 0);
    }
}
