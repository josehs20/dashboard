<?php

namespace App\Console\Commands;

use App\Models\CidadeIbge;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Services\ImportXml;

class SyncCidadesIbgeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:cidadesIbge';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando para criar tabela de cidades do ibge somente dado quando a tabela de cidade ibge estiver vazia';

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
        $self = new ImportXml;

        $dados = $self->getDados('CIDADE_IBGE.XML', null);
        $dados  = $dados['dados'];

        foreach ($dados as $key => $dado) {
            if (isset($dado['@attributes'])) {
                $dado = $dado['@attributes'];
            }
            //CidadeIbge::where('codigo', $dado['CODIGO'])->first();
            if (!CidadeIbge::where('codigo', $dado['CODIGO'])->first()) {
                CidadeIbge::create([
                    'codigo' => intval(trim($dado['CODIGO'])),
                    'nome'   => strval($dado['NOME']),
                    'uf'     => strval($dado['UF']),
                ]);
                echo $dado['NOME'];
            } else {
                CidadeIbge::where('codigo', $dado['CODIGO'])->update([
                    'codigo' => intval(trim($dado['CODIGO'])),
                    'nome'   => strval($dado['NOME']),
                    'uf'     => strval($dado['UF']),
                ]);
                echo $dado['NOME'].'up';
            }
        }
    }
}
