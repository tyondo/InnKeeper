<?php

namespace Tyondo\Innkeeper\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Tyondo\Innkeeper\Infrastructure\Services\OrganizationManage;
use Tyondo\Innkeeper\Infrastructure\Services\OrganizationSetup;

class OrganizationController extends Controller
{
    public function dashboard(Request $request){
        return view('innkeeper::pages.dashboard');
    }
    public function listOrganizations(Request $request){
        return view('innkeeper::pages.organizations.list')->with([
            'organizations' => $this->innkeeperServiceModel()->organizationModel()->all()
        ]);
    }
    public function createOrganizationForm(Request $request){
        return view('innkeeper::pages.organizations.create');
    }
    public function storeOrganization(Request $request){
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'mobile_number' => 'required',
            'email' => 'required|email',
            'organization_name' => 'required',
            'organization_status' => 'required',
            'domain' => 'required',
            'management_status' => 'required',
        ]);
        if ($validator->fails()){
            if ($request->ajax()){
                return response()->json($validator->errors(),422);
            }
            if ($request->expectsJson()){
                return response()->json([
                    'success' => false,
                    'message' => 'All inputs required.',
                    'data' => $validator->errors()
                ],422);
            }
            return redirect()->back()->withErrors($validator->errors());
        }else{
            return (new OrganizationSetup())->initOrganizationSetup($request->all());
        }
    }

    public function destroyOrganization(Request $request){
        $organizationId = $request->route()->parameter('organizationId');
         $organization = $this->innkeeperServiceModel()->organizationModel()->find($organizationId);
         $connection =$this->innkeeperServiceModel()->organizationConnectionModel()->where('organization_id',$organizationId)->first();
         if ($connection){
             $response = (new OrganizationManage([]))->archiveOrganization($organization->id,$connection->dbName);
             if ($response['status'] = 'success'){
                 return response()->json([
                     'success' => true,
                     'location_url' => '',
                     'data' => $response
                 ]);
             }
         }
         return redirect()->back();
    }

}
