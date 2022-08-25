<?php

namespace App\Jobs;

use App\Models\Empresa;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class ExportaVendaJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $json;
    private $dir;
    private $loja_alltech_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($json, $dir, $loja_alltech_id)
    {
        $this->json = $json;
        $this->dir = $dir;
        $this->loja_alltech_id = $loja_alltech_id;
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
        $filesBanco = $empresa->arquivosExp()->where('nome', 'like', '%' . "VONLINE" . '%')->get();
        $count = 1;

        //numeração de loja para nomear arquivo
        $num_loja = "000";
        $num_loja .= $this->loja_alltech_id;
        $num_loja = substr($num_loja, -3);

        $file = $this->dir . '/VONLINE-' . $count . '-' . $num_loja . '.json';

        while ($filesBanco->where('nome', $file)->first()) {
            $count++;
            $file = $this->dir . '/VONLINE-' . $count . '-' . $num_loja . '.json';
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
        $empresa->logs()->create(['log' => "[Venda] Exportado Pasta/dir = {$file}"]);
    }
}
