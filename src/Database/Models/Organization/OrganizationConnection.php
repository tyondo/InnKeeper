<?php

namespace Tyondo\Innkeeper\Database\Models\Organization;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrganizationConnection extends Model
{
    use HasFactory;

    protected $connection = 'innkeeper'; //for models dealing with tenat dbs use 'tenant'

    public static function deleteOrganizationConnectionById($organizationId){
        return self::where('organization_id',$organizationId)->delete();
    }

    public static function getOrganizationConnectionById($organizationId){
        return self::all()->where('organization_id',$organizationId);
    }
}
