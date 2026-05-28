<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GreetCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'greet';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Display a simple Hello World greeting';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->info("Hello World! 👋");
        $this->line("Welcome to Laravel FrankenPHP!");
        $this->line("Environment: " . config('app.env'));
    }
}
