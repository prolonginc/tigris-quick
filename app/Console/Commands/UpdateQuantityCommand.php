<?php

namespace App\Console\Commands;

use App\Jobs\ParseQuickbooksQueue;
use Illuminate\Console\Command;

class UpdateQuantityCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'quantity:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'it updates the quantity';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $delay = 0;
        for ($i=1; $i < 4000; $i++) {
            ParseQuickbooksQueue::dispatch($i)->delay(now()->addMinutes($delay));
            if($i %50 == 0) {
                $delay++;
            }
        }
        return 0;
    }
}