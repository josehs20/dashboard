<?php

namespace App\Console\Commands;


use Illuminate\Support\Facades\Storage;
use App\Model\Close;
use Illuminate\Console\Command;
use App\Models\Empresa;



class AddClose extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:close';

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
        foreach($empresas as $empresa) {
          
            $files = Storage::disk('ftp')->files("{$empresa->pasta}"); 

            
            foreach($files as $file) {
                if($this->filePermited($empresa, $file) && !Storage::disk('local')->exists($file)) {
                Storage::disk('local')->put($file, Storage::disk('ftp')->get($file));
                
                    echo "Copiado arquivo {$file}\n"; 
                    $empresa->update([
                        'sincronizar' => true,
                    ]);
                }
            }
        }
        return 0;
    }
    private function filePermited($empresa, $file) {
       
        return (str_contains($file, 'CLOSE') and $empresa->arquivos()->where('nome', $file)->count() == 0);
       
    }
}

