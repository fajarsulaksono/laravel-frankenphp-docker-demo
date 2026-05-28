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
    protected $signature = 'greet {name=World} {--greeting=Hello}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Greet someone with a custom message';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $name = $this->argument('name');
        $greeting = $this->option('greeting');

        $this->info("$greeting, $name! 👋");
        $this->line("Welcome to Laravel FrankenPHP!");
        $this->line("Running in: " . config('app.env'));
    }
}
