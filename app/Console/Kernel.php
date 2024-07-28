<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use App\Http\Controllers\CarteSIAController;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Http;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->call(function () {
            $airac = new CarteSIAController();
            $new_airac = $airac->config_Date();
            $dateairac = $airac->checkdate();
            $date = date("Y-m-d");
            if ($new_airac == $date) {
                    http::post(env("Webhook_url_Airac_Info"), [
                        "content" => " :warning: Nouvelle Airac disponible version 2024-".$dateairac." :warning: ",
                    ]);
                }
        })->dailyAt("12:50")->timezone($this->scheduleTimezone());
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }

    protected function scheduleTimezone(): string
    {
        return 'Europe/Paris';
    }
}
