<?php

namespace Tyondo\Innkeeper\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Tyondo\Innkeeper\Database\Seeders\InnkeeperTableSeeder;

class MigrationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'innkeeper:setup:migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create appropriate tables in the main database. It wipes out all data on that database';

    public $migrations_tables = [];

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
     *
     * @return int
     */
    public function handle()
    {
        $this->info("Begin persisting schema to database");
        $this->info("Checking if support tables exist");

        /*$this->call('db:wipe',[
            '--database' => 'innkeeper',
        ]);*/
        $this->info(json_encode($this->inactiveMigrations()));
        $this->call('db:wipe',[
            '--database' => 'innkeeper',
        ]);
        if ($this->inactiveMigrations()) {

            $this->info("Package tables does not exist in the current connection 'mysql'");
            $option = $this->choice('Do we migrate the tables?', ['YES', 'NO'], 0);

            if ($option == 'YES'){

                $this->call('migrate', [
                    '--database' => 'innkeeper',
                    '--path' => 'vendor/tyondo/innkeeper/src/Database/Migrations',
                ]);
                $this->info("Done migrating tables");
            }else{
                $this->info("you have selected {$option}. Moving on...");
                $this->line("");
                $this->info("There has been no change on your database schema");
            }
        }else{
            $this->info("There has been no change on your database schema");
            $this->info("Support tables exist");
        }

        $this->info("Setting up innkeeper types");
        $seeder = new InnkeeperTableSeeder();
        $seeder->run();
        $this->info("Done seeding tables");

        return Command::SUCCESS;
    }

    private function inactiveMigrations()
    {

        $migrations = File::files(dirname(dirname(__FILE__)).'/Database/Migrations');
        foreach ($migrations as $migration) {
            $this->migrations_tables[] = basename($migration, '.php');
        }

        $inactiveMigrations = [];
        $migration_arr = [];

        // Package Migrations
        $tables = $this->migrations_tables;

        try {
            // Application active migrations
            $migrations = DB::connection('innkeeper')->select('select * from '.DB::getTablePrefix().'migrations');

            foreach ($migrations as $migration_parent) { // Count active package migrations
                $migration_arr[] = $migration_parent->migration;
            }

            foreach ($tables as $table) {
                if (!in_array($table, $migration_arr)) {
                    $inactiveMigrations[] = $table;
                }
            }
        }catch (\Exception $exception){
            $inactiveMigrations = $tables;
        }


        return $inactiveMigrations;
    }
}
