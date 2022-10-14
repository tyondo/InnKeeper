<?php

namespace Tyondo\Innkeeper\Infrastructure\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Ramsey\Uuid\Uuid;
use Tyondo\Cirembo\Permission\Helpers\AuthorizationHelper;
use Tyondo\Cirembo\Permission\Models\User;
use Tyondo\Innkeeper\Database\Models\Organization\Organization;
use Tyondo\Innkeeper\Database\Models\Organization\OrganizationConnection;
use Tyondo\Innkeeper\Database\Models\Organization\OrganizationUser;
use Tyondo\Innkeeper\Infrastructure\Helpers\DatabasePdoHelper;
use Tyondo\Innkeeper\Infrastructure\Helpers\LandlordHelper;

class OrganizationSetup
{
    protected $name;
    protected $slug;
    protected $tenantUser;
    protected $params;
    protected $org;
    protected $orgDb;
    protected $connectionInfo;

    public function initOrganizationSetup(array $userOrganizationDetails){
        $this->tenantUser = new OrganizationUser();
        $this->org = new Organization();
        $this->connectionInfo = new OrganizationConnection();
        $this->params = $userOrganizationDetails;

       return $this->run();
    }

    public function run(){
        $this->createOrganization();
        $this->createDatabaseAndConnection();
        if ($this->params['management_status'] != 'manage_organization'){
            $this->runMigrations();
            $this->createUserInTenant();
        }
        return $this->org;
    }

    protected function createOrganization(){

        $this->slug = self::camelCase($this->params['organization_name']);

        $this->org->fill([
            'organization_uid' => Uuid::uuid4(),
            'organization_account' => time(),
            'management_status'=> $this->params['management_status'],
            'name' => $this->params['organization_name'],
            'slug' =>  $this->slug
        ]);

        $this->tenantUser->fill([
            'user_uid' => Uuid::uuid4(),
            'first_name' => $this->params['first_name'],
            'last_name' => $this->params['last_name'],
            'user_status' => $this->params['organization_status'],
            'mobile_number' => $this->params['mobile_number'],
            'email' => $this->params['email'],
            'password' => bcrypt($this->params['organization_name'])
        ]);

        $this->org->save();
        $this->org->users()->save($this->tenantUser);
    }

    protected function createDatabaseAndConnection(){
        //check if the creation status is set as manage
        if ($this->params['management_status'] == 'manage_organization'){
            DB::connection('innkeeper')->table('organization_connections')->insert(
                [
                    'organization_id' => $this->org->id,
                    'dbUsername' => env('DB_USERNAME'),
                    'dbHostname' => env('DB_HOST'),
                    'dbPassword' => env('DB_PASSWORD'),
                    'dbName' => $this->slug,
                    'created_at' => Carbon::now(),
                ]);
        }else{
            //creating the organization db
            $innkeeperDbEngine = config('innkeeper.tenant-db-server');
            $dbHost = env('DB_HOST');
            $dbPort = env('DB_PORT');
            $dbUsername = env('DB_USERNAME');
            $dbPassword = env('DB_PASSWORD');
            $dbName = $this->slug;
            $pleskSiteId = config('innkeeper.connections.innkeeper.plesk.web_space_id');
            if ($innkeeperDbEngine === 'mysql'){
                //TODO:: indicate this as the database administrative credentials for use
                $orgDb = new DatabasePdoHelper($dbHost,$dbPort,$dbUsername,$dbPassword);
                $result = $orgDb->pdoCreateDatabase($dbName); //TODO::log this call
            }elseif ($innkeeperDbEngine == 'plesk'){
                $pleskServer = new PleskServerDbSetup();
                $pleskServer->setWebSpaceId($pleskSiteId)->setDbName($dbName)
                    ->setDbUsername($dbName)->setDbUserPassword($dbPassword)
                    ->setUpDatabaseAndUser();
            }
            DB::connection('innkeeper')->table('organization_connections')->insert(
                [
                    'organization_id' => $this->org->id,
                    'dbUsername' => $dbUsername,
                    'dbHostname' => $dbHost,
                    'dbPassword' => $dbPassword,
                    'dbName' => $dbName,
                    'created_at' => Carbon::now(),
                ]);
            LandlordHelper::setTenantFromSlug($dbName);
        }

    }


    protected function runMigrations(){
        $commandList = config('innkeeper.migrations.commands');
        foreach ($commandList as $item){
            Artisan::call($item,['--no-interaction' => true]);
        }
        /*return Artisan::call('migrate', [
            '--database' => 'tenant',
            '--path' => '/database/migrations/tenants' //TODO:: have this as a parameter
        ]);*/
    }

    protected function createUserInTenant(){

      $id=  DB::connection('tenant')->table('users')->insertGetId(
            [
               // 'organization_id' => $this->org->id,
                //'created_by' => 'John Doe',
                //'user_uid' => Uuid::uuid4(),
               // 'api_key' => Uuid::uuid4(),
                'first_name' => $this->params['first_name'],
                'last_name' => $this->params['last_name'],
                //'status' => $this->params['organization_status'],
                'mobile_number' => $this->params['mobile_number'],
                'email' => $this->params['email'],
                'password' => bcrypt($this->params['organization_name']),
                'created_at' => Carbon::now(),
            ]);
      new AuthorizationHelper(User::findOrFail($id));
    }

    /**
     * This function is used to convert a string into a camelCase string
     * e.g it converts Hello World to helloWorld. It can be used in creating database names
     * @param $str
     * @param array $noStrip
     * @return mixed|string
     */
    public static function camelCase($str, array $noStrip = [])
    {
        // non-alpha and non-numeric characters become spaces
        $str = preg_replace('/[^a-z0-9' . implode("", $noStrip) . ']+/i', ' ', $str);
        $str = trim($str);
        // uppercase the first character of each word
        $str = ucwords($str);
        $str = str_replace(" ", "", $str);
        $str = lcfirst($str);

        return $str;
    }
}
