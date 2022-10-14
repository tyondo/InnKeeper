<?php

namespace Tyondo\Innkeeper\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Tyondo\FrequentQuestions\Services\PolicyImporter;

class PolicyImporterCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tyondo:tags:policy:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $filePermissions = (new PolicyImporter(app_path('Policies'),'\App\Policies\\'))->getPermission();
        /*$this->line(json_encode($filePermissions));
        $this->line("");*/
        $dbPermissions = DB::table('permissions')->pluck('slug')->toArray();
        foreach ($filePermissions as $key => $value){
            if (in_array($value['slug'],$dbPermissions)){
                continue;
            }else{
                DB::table('permissions')->insert($value);

                $this->line("Added \n");
                $this->info(json_encode($value));
            }

        }
        return 0;
    }
}
