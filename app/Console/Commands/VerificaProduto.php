<?php

namespace App\Console\Commands;


use Illuminate\Support\Facades\Storage;
use Illuminate\Console\Command;
use App\Models\Produto;
use App\Models\Estoque;



class VerificaProduto extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'verifica:produto';

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
        $estoque_null = Estoque::where('produto_id', null)->get();

        foreach ($estoque_null as $key => $value) {

            $loja_verifica =  $value->loja_id;
            $id_estoque = $value->id;
            $alltech_id = $value->alltech_id;
            $produto = Produto::where('alltech_id', $alltech_id)->where('loja_id', $loja_verifica)->first();
                    
              if ($produto) {
                  
                echo "Produto $produto->nome  --- id $produto->id \n";

                $estoque = Estoque::find($id_estoque);
                $estoque->produto_id = $produto->id;
                $estoque->save();    
          }
      }
      echo " Estoque verificado \n";
    }
    
}
