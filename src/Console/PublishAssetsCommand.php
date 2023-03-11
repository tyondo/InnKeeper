<?php

namespace Tyondo\Innkeeper\Console;

use Illuminate\Console\Command;
use Tyondo\Innkeeper\InnkeeperServiceProvider;

class PublishAssetsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'innkeeper:publish:assets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish innkeeper public assets';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->call('vendor:publish',[
            '--provider' => InnkeeperServiceProvider::class,
            '--tag'=> "public_innkeeper"
        ]);
        return Command::SUCCESS;
    }
}
