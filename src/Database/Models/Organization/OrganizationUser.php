<?php

namespace Tyondo\Innkeeper\Database\Models\Organization;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Hash;

class OrganizationUser extends Model
{
    use HasFactory;
    protected $connection = 'innkeeper';

    public static function boot()
    {
        parent::boot();

        static::creating(function($model)
        {
            $model->display_name = $model->first_name . ' ' . $model->last_name;
            $model->password = Hash::make('password');
        });

        /*static::updating(function($model)
        {
            //change to Auth::user() if you are using the default auth provider
            $user = Confide::user();
            $model->updated_by = $user->id;
        });*/
    }

    protected $guarded = ['id'];

    public function organizations(){
        return $this->belongsTo(Organization::class);
    }

    public static function getUsersByOrganizationId($organizationId){
        return self::all()->where('organization_id',$organizationId);
    }

    public static function deleteUsersByOrganizationId($organizationId){
        return self::where('organization_id',$organizationId)->delete();
    }
}
