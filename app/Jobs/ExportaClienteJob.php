<?php

namespace App\Jobs;

use App\Models\Empresa;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;


class ExportaClienteJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $json;
    private $dir;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($json, $dir)
    {
        $this->json = $json;
        $this->dir = $dir;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $empresa = Empresa::where('pasta', $this->dir)->first();
        $this->dir = 'APPVENDA/' . $this->dir;
   
        Storage::disk('local')->makeDirectory($this->dir);
        $filesBanco = $empresa->arquivosExp()->where('nome', 'like', '%' . "CLIENTE" . '%')->get();
        $count = 1;

        $file = $this->dir . '/CLIENTE-' . $count . '.json';

        while ($filesBanco->where('nome', $file)->first()) {
            $count++;
            $file = $this->dir . '/CLIENTE-' . $count . '.json';
        }

        $empresa->arquivosExp()->create(['nome' => $file, 'processado' => 0]);
        Storage::disk('local')->put($file, $this->json);

        if (!Storage::disk('ftp')->exists($this->dir)) {
            Storage::disk('ftp')->makeDirectory($this->dir);
        }

        $precessado = Storage::disk('ftp')->put($file, Storage::get($file));
        if (!$precessado) {
            $precessado = Storage::disk('ftp')->put($file, Storage::get($file));
            if (!$precessado) {
                $precessado = Storage::disk('ftp')->put($file, Storage::get($file));
            }
        }

        if ($precessado) {
            $empresa->arquivosExp()->where('nome', $file)
                ->update(['nome' => $file, 'processado' => 1]);
        }

        $empresa->update(['ultima_sincronizacao' => now()]);
        $empresa->logs()->create(['log' => "[Cliente] Exportado Pasta/dir = {$file}"]);
    }
}
