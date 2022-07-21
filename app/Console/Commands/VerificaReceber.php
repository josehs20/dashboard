<?php

namespace App\Console\Commands;


use Illuminate\Support\Facades\Storage;
use Illuminate\Console\Command;
use App\Models\Cliente;
use App\Models\Receita;



class VerificaReceber extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'verifica:receber';

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
        $cliente_null = Receita::where('cliente_id', null)->get();

        //echo  $estoque_null;
        foreach ($cliente_null as $key => $value) {

            $loja_verifica =  $value->loja_id;
            $id_cliente = $value->id;
            $alltech_id = $value->alltech_id;
            $cliente = Cliente::where('alltech_id', $alltech_id)->where('loja_id', $loja_verifica)->first();
                    
              if ($cliente) {
                  
                echo "Cliente $cliente->nome  --- id $cliente->id \n";

                $receber = Receita::find($id_cliente);
                $receber->cliente_id = $cliente->id;
                $receber->save();        
          }
      }
      echo " Receita verificada \n";
    }
    
}