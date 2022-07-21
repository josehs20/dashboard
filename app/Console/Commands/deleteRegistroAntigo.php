<?php

namespace App\Console\Commands;

use App\Models\Empresa;
use Illuminate\Console\Command;

class deleteRegistroAntigo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:registroAntigo';

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
        $empresas = Empresa::where('sincronizar', '2')->get();

        foreach ($empresas as $empresa) {

            $lojas = $empresa->lojas()->get();

            foreach ($lojas as $loja) {

                $this->deleteRegistroAntigo($loja);
            }
        }
    }

    public function deleteRegistroAntigo($loja)
    {
            $loja->caixas()->where('data', '<=', date("Y-m-d H:i:s", strtotime("-12 month")))->delete();
            $loja->vendas()->where('data', '<=', date("Y-m-d H:i:s", strtotime("-12 month")))->delete();
            $loja->devolucoes()->where('data', '<=', date("Y-m-d H:i:s", strtotime("-12 month")))->delete();
            $loja->vendaItens()->where('data', '<=', date("Y-m-d H:i:s", strtotime("-12 month")))->delete();
            $loja->devolucaoItens()->where('data', '<=', date("Y-m-d H:i:s", strtotime("-12 month")))->delete();
            $loja->receitas()->where('emissao', '<=', date("Y-m-d H:i:s", strtotime("-12 month")))->delete();
            $loja->despesas()->where('emissao', '<=', date("Y-m-d H:i:s", strtotime("-12 month")))->delete();

    }
}
