<?php

namespace App\Console\Commands\Jobs;

use App\Jobs\AddFunarioToSyncJob;
use App\Models\Empresa;
use App\Models\Funario;
use App\Services\ImportXml;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class AddFunarioToSyncCommande extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'add-job:funario';

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
        // $empresas = Empresa::where('sincronizar', true)->get();
        // foreach ($empresas as $empresa) {

        //     $files = Storage::disk('ftp')->files("{$empresa->pasta}");

        //     foreach ($files as $file) {
        //         if ($this->filePermited($empresa, $file)) {
        //             Storage::disk('local')->put($file, Storage::disk('ftp')->get($file));
        //             $empresa->arquivos()->create([
        //                 'nome' => $file
        //             ]);

        //             $job = (new AddFunarioToSyncJob($empresa, $file))->delay(now()->addSeconds(3));
        //             dispatch($job)->onQueue('sincronizando');

        //             echo "Added {$file}\n";
        //         }
        //     }
        // }
        $empresa = Empresa::find(3);

        $self = new ImportXml;

        $dados = $self->getDados('FUNARIO.XML', null);
        $dados  = $dados['dados'];

        foreach ($dados as $key => $dado) {
            if (isset($dado['@attributes'])) {
                $dado = $dado['@attributes'];
            }
            //CidadeIbge::where('codigo', $dado['CODIGO'])->first();
            if (strval(trim($dado['SITUACAO'])) == 'A' && strval(trim($dado['TIPO'])) == 'V') {
                if (!$empresa->funcionarios()->where('alltech_id', preg_replace('/\D/', '', trim($dado['CODIGO'])))->first()) {

                    $empresa->funcionarios()->create([
                        'nome' => strval(trim($dado['NOME'])),
                        'alltech_id' => preg_replace('/\D/', '', trim($dado['CODIGO'])),
                        'tipo' => strval(trim($dado['TIPO'])),
                        'status' => 'inativo',
                    ]);
                    echo $dado['NOME'].'.';
                } else {
                    $empresa->funcionarios()->where('alltech_id', preg_replace('/\D/', '', trim($dado['CODIGO'])))->update([
                        'nome' => strval(trim($dado['NOME'])),
                        'alltech_id' => preg_replace('/\D/', '', trim($dado['CODIGO'])),
                        'tipo' => strval(trim($dado['TIPO'])),
                    ]);
                    echo $dado['NOME'].',';
                }
            } elseif (strval(trim($dado['SITUACAO'])) == 'I' && strval(trim($dado['TIPO'])) == 'V') {
                //entra funcao para deletar funcionario
            }
        }
    }

    private function filePermited($empresa, $file)
    {
        return (str_contains($file, '-FUNARIO-') and $empresa->arquivos()->where('nome', $file)->count() == 0);
    }
}
