<?php

namespace Tyondo\Innkeeper\Infrastructure\Services;

use PleskX\Api\Client;
use Tyondo\Innkeeper\Infrastructure\Helpers\PleskServerAdapter;

class PleskServerDbSetup
{
    private int $webSpaceId;
    private int $databaseId;
    private string $dbName;
    private string $dbUsername;
    private string $dbUserPassword;
    private int $dbUserId;

    /**
     * @param int $webSpaceId
     */
    public function setWebSpaceId(int $webSpaceId): PleskServerDbSetup
    {
        $this->webSpaceId = $webSpaceId;
        return $this;
    }

    /**
     * @param string $dbName
     */
    public function setDbName(string $dbName): PleskServerDbSetup
    {
        $this->dbName = $dbName;
        return $this;
    }

    /**
     * @param string $dbUsername
     */
    public function setDbUsername(string $dbUsername): PleskServerDbSetup
    {
        $this->dbUsername = $dbUsername;
        return $this;
    }

    /**
     * @param string $dbUserPassword
     */
    public function setDbUserPassword(string $dbUserPassword): PleskServerDbSetup
    {
        $this->dbUserPassword = $dbUserPassword;
        return $this;
    }

    /**
     * @param int $dbUserId
     */
    public function setDbUserId(int $dbUserId): PleskServerDbSetup
    {
        $this->dbUserId = $dbUserId;
        return $this;
    }

    public function setUpDatabaseAndUser(){
        $database = $this->createPleskDatabase();
        return $this->createPleskDbUser($database->id);
    }

    public function setUpDatabaseAndAssignUser(){
        $database = $this->createPleskDatabase();
        return $this->assignDbUser();
    }

    public function createPleskDatabase(){
        $database = $this->getPleskAdapter()->getApiClient()->database()->create([
            'webspace-id' => $this->webSpaceId,
            'name' => $this->dbName,
            'type' => 'mysql',
            'db-server-id' => 1,
        ]);
        $this->databaseId = $database->id;
        return $database;
    }

    public function assignUser($userId, $dbId){
        $updateRequest = "<set-default-user>
      <db-id>$userId</db-id>
      <default-user-id>$dbId</default-user-id>
   </set-default-user>";

       return $this->getPleskAdapter()->getApiClient()->database()->request($updateRequest);
    }

    protected function assignDbUser(){
        $updateRequest = "<set-default-user>
      <db-id>$this->dbUserId</db-id>
      <default-user-id>$this->databaseId</default-user-id>
   </set-default-user>";

       return $this->getPleskAdapter()->getApiClient()->database()->request($updateRequest);
    }

    protected function createPleskDbUser(int $databaseId){
        return $this->getPleskAdapter()->getApiClient()->database()->createUser([
            'db-id' => $databaseId,
            'login' => $this->dbUsername,
            'password' => $this->dbUserPassword,
        ]);
    }

    private function getPleskAdapter() : PleskServerAdapter
    {
        return new PleskServerAdapter();
    }
}
