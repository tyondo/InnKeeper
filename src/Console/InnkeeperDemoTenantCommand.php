<?php

namespace Tyondo\Innkeeper\Console;

use Illuminate\Console\Command;
use Tyondo\Innkeeper\Database\Seeders\OrganizationTenantSeeder;
use Tyondo\Innkeeper\Infrastructure\Services\PleskServerDbSetup;

class InnkeeperDemoTenantCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'innkeeper:tenant:demo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Used in setting up demo tenant account for evaluation';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->createDemoTenant();
      // $result = $this->testPleskDbAssignment();
       //$this->line(json_encode($result));
        return Command::SUCCESS;
    }

    private function createDemoTenant(){
        $seeder = new OrganizationTenantSeeder();
        $seeder->run();
    }

    private function testPleskDbAssignment(){
        $pleskServer = new PleskServerDbSetup();
        return  $pleskServer->assignUser(28,41);

       /*return $pleskServer->setWebSpaceId(51)->setDbName('dbTest')
           ->createPleskDatabase();*/
           // ->setDbUserId(28)
            //->setUpDatabaseAndAssignUser();
    }
}
