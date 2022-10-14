<?php

namespace Tyondo\Innkeeper\Database\Models\Organization;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Organization extends Model
{
    use HasFactory;

    protected $connection = 'innkeeper';
    protected $guarded = ['id'];

    public function orgConnection(){
        return $this->hasOne(OrganizationConnection::class,'organization_id','id');
    }

    public function users(){
        //return $this->hasOne('Tyondo\Cirembo\Modules\Landlord\Models\OrganizationUser');
        return $this->hasMany(OrganizationUser::class);
    }

}
