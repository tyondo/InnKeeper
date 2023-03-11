<?php

namespace Tyondo\Innkeeper\Console;

use Illuminate\Console\Command;

class InnKeeperEnvSetUpCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'innkeeper:setup:env';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add require env variables in the .env file for the package to work';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $return = $this->configureEnvironmentFile();
        if ($return){
           // $this->call('tyondo:innkeeper:migrate');
        }
        return Command::SUCCESS;
    }

    private function configureEnvironmentFile(){
        $envKeys = config('innkeeper.env_config_keys');
        foreach ($envKeys as $titleHead => $keys){
            createEnvFileHeaders($titleHead);
            foreach ($keys as $key => $value){
                createOrUpdateEnvVariable($key,$value);
            }
        }
        return true;
    }
}
