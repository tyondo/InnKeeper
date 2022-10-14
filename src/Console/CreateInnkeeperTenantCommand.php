<?php

namespace Tyondo\Innkeeper\Console;

use Illuminate\Console\Command;
use Tyondo\Innkeeper\Database\Seeders\OrganizationTenantSeeder;
use Tyondo\Innkeeper\Infrastructure\Services\PleskServerDbSetup;

class CreateInnkeeperTenantCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tyondo:innkeeper:tenant';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
