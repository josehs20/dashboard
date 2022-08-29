<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('verifica:produto')->everyFifteenMinutes();
        // $schedule->command('verifica:receber')->everyFifteenMinutes();
        // $schedule->command('delete:registroAntigo')->everyThirtyMinutes();

        $schedule->command('sync:close')->everyFifteenMinutes();
        $schedule->command('add-job:generica')->everyFifteenMinutes();//first_queue
        $schedule->command('add-job:funario')->everyFifteenMinutes();//first_queue
        $schedule->command('add-job:caixa')->everyFifteenMinutes();//first_queue
        $schedule->command('add-job:cliente')->everyFifteenMinutes();//first_queue

        $schedule->command('add-job:endereco')->everyFifteenMinutes();//first_queue
        $schedule->command('add-job:grade')->everyFifteenMinutes();//second_queue
        $schedule->command('add-job:igrade')->everyFifteenMinutes();//second_queue

        $schedule->command('add-job:fornecedor')->everyFifteenMinutes();//first_queue
        $schedule->command('add-job:produto')->everyFifteenMinutes();//second_queue
        $schedule->command('add-job:receber')->everyFifteenMinutes();//first_queue
        $schedule->command('add-job:pagar')->everyFifteenMinutes();//first_queue

        $schedule->command('add-job:prodsaldo')->everyFifteenMinutes();//second_queue
        $schedule->command('add-job:venda')->everyFifteenMinutes();//second_queue
        $schedule->command('add-job:prodrazao')->everyFifteenMinutes();//second_queue
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
