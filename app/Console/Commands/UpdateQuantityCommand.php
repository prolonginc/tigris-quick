<?php

namespace App\Console\Commands;

use App\Jobs\ParseQuickbooksQueue;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;

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
        Auth::logInUsingId(13);
        $delay = 0;
        for ($i=1; $i < 5000; $i++) {
            ParseQuickbooksQueue::dispatch($i)->delay(now()->addMinutes($delay));
            if($i %50 == 0) {
                $delay++;
            }
        }
        return 0;
    }
}
