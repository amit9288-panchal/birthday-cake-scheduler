<?php

namespace App\Console\Commands;

use App\Services\CakeDayService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Exception;

class CalculateDayCake extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'calculate:dayCake';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate day cake';

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
     */
    public function handle()
    {
        try {
            Log::info('Start calculating day cake');
            $service = new CakeDayService();
            $service->calculateCakeDays();
            Log::info('End calculating day cake');
        } catch (Exception $exception) {
            Log::error('Error: ' . $exception->getMessage());
        }
    }
}
