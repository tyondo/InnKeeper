<?php
namespace Tyondo\Innkeeper\Infrastructure\Services;

use Illuminate\Support\Facades\Auth;
use Tyondo\Innkeeper\Database\Models\Organization\Organization;
use Tyondo\Innkeeper\Database\Models\Organization\OrganizationArchive;
use Tyondo\Innkeeper\Database\Models\Organization\OrganizationConnection;
use Tyondo\Innkeeper\Database\Models\Organization\OrganizationUser;
use Tyondo\Innkeeper\Infrastructure\Helpers\DatabasePdoHelper;

class OrganizationManage
{
    protected $params;
    protected static $dbHandle;
    public function __construct($userOrganizationDetails = array())
    {
        $this->params = $userOrganizationDetails;
        self::$dbHandle = new DatabasePdoHelper(config("database.connections.mysql.host"),config("database.connections.mysql.port"),config("database.connections.mysql.username"),config("database.connections.mysql.password"));
    }

    public function createDatabase($databaseName ){
       return self::$dbHandle->pdoCreateDatabase($databaseName);
    }

    public function databaseExist($databasename,$json=false){
        return self::$dbHandle->pdoDoesDatabaseExist($databasename,$json);
    }

    public function destroyDatabase($databaseName){
        return self::$dbHandle->pdoDestroyDatabase($databaseName);
    }

    public function exportDatabase($databaseName,$isBackup=false,$isCompressed=false){
       return self::$dbHandle->dumpDatabase($databaseName,$isBackup,$isCompressed);
    }

    public function importDatabase(){

    }

    public function archiveOrganization($organizationIdentifier, $organizationDatabaseName){
        //TODO::REPORT and log this event.
            $backupResult = $this->exportDatabase($organizationDatabaseName,true,true);
            if ($backupResult['status'] == 'success'){
                //TODO:: Log exportation
                $destroyResult = $this->destroyDatabase($organizationDatabaseName);
                if ($destroyResult['status'] == 'success'){
                    //TODO:: Log db dropping
                    $organizationUsers = OrganizationUser::getUsersByOrganizationId($organizationIdentifier); //TODO::log and store this
                    $organizationUsersDeletionResponse = OrganizationUser::deleteUsersByOrganizationId($organizationIdentifier);

                    if ($organizationUsersDeletionResponse == 1){
                        $organizationConnection = OrganizationConnection::getOrganizationConnectionById($organizationIdentifier);
                        $organizationConnectionDeletionResult = OrganizationConnection::deleteOrganizationConnectionById($organizationIdentifier);
                        if ($organizationConnectionDeletionResult ==1){
                            $organization = Organization::find($organizationIdentifier);
                            $organizationDeletionResponse = Organization::destroy($organizationIdentifier);
                            if ($organizationDeletionResponse ==1){
                                $rspData = [
                                    'status' => 'success',
                                    'message' => 'Organization archived successfully',
                                    'data' => [
                                        'organization' => $organization,
                                        'organizationDeletionResponse' => $organizationDeletionResponse,
                                        'organizationUsers' => $organizationUsers,
                                        'organizationDeletionResult' => $organizationUsersDeletionResponse,
                                        'organizationConnectionDetails' => $organizationConnection,
                                        'orgnizationConnectionDeletionResult' => $organizationConnectionDeletionResult,
                                        'organizationDatabaseBackupFile' => $backupResult,
                                    ]
                                ];
                                (new OrganizationArchive())->fill([
                                    'organization' => ucwords($organization->name),
                                    'user_id' => Auth::id(),
                                    'organization_archive_details' => json_encode($rspData)
                                ])->save();
                                return [
                                    'status' => 'success',
                                    'message' => 'Organization archived successfully',
                                ];
                            }else{
                                return [
                                    'status' => 'fail',
                                    'message' => 'Unable to delete organization',
                                    'data' => [
                                        'completed' => [
                                            ['Archiving database','Deleting database',
                                                'Logging organization users','Deleting organization users',
                                                'Logging organization connection details'
                                            ]
                                        ],
                                        'pending' => [
                                            ['Deleting organization']
                                        ],
                                    ],
                                    'error' => [
                                        'code' => '',
                                        'serverResponse' => $organizationConnectionDeletionResult
                                    ]
                                ];
                            }

                        }else{
                            return [
                                'status' => 'fail',
                                'message' => 'Unable to delete organization connection',
                                'data' => [
                                    'completed' => [
                                        ['Archiving database','Deleting database',
                                            'Logging organization users','Deleting organization users',
                                            'Logging organization connection details'
                                        ]
                                    ],
                                    'pending' => [
                                        ['Deleting connection details']
                                    ],
                                ],
                                'error' => [
                                    'code' => '',
                                    'serverResponse' => $organizationConnectionDeletionResult
                                ]
                            ];
                        }
                    }else{
                        return [
                            'status' => 'fail',
                            'message' => 'Unable to delete organization users',
                            'data' => [
                                'completed' => [
                                    ['Archiving database','Deleting database','Logging organization users']
                                ],
                                'pending' => [
                                    ['Deleting organization users','Logging organization connection details','Deleting connection details']
                                ],
                            ],
                            'error' => [
                                'code' => '',
                                'serverResponse' => $organizationUsersDeletionResponse
                            ]
                        ];
                    }

                }else{
                    return [
                        'status' => 'fail',
                        'messsage' => "unable to drop database <$organizationDatabaseName>",
                        'serverResponse' => $destroyResult
                    ];
                }
            }else{
                return [
                    'status' => 'fail',
                    'messsage' => "unable to export database <$organizationDatabaseName>",
                    'serverResponse' => $backupResult
                ];
            }


    }
}
