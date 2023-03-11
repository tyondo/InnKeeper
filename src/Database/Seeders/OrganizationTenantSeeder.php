<?php

namespace Tyondo\Innkeeper\Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Tyondo\Innkeeper\Infrastructure\Services\OrganizationSetup;

class OrganizationTenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        $regex = "/(\+?254|0){1}[7]{1}([0-2]{1}[0-9]{1}|[9]{1}[0-2]{1})[0-9]{6}/";

        $organizationTenantService = new OrganizationSetup();

        foreach (range(1, 2) as $index){
            $organizationTenantService->initOrganizationSetup([
                'management_status' => $index === 0 ? true : $this->getOrganizationManagementType(),
                'organization_status' => $index === 0 ? true : $this->getOrganizationStatus(),
                'organization_name' => $faker->userName,
                //'organization_name' => $faker->userName,
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                // 'mobile_number' => $faker->phoneNumber,
                'mobile_number' => $faker->regexify($regex),
                'email' => $faker->unique(true,500)->email,
            ]);
        }
    }

    public function getOrganizationManagementType(){
        $statusList = ['new_organization'];
        $orgIndex = array_rand($statusList,1);
        return $statusList[$orgIndex];
    }

    public function getOrganizationStatus(){
        $statusList = ['active','suspended'];
        $orgIndex = array_rand($statusList,1);
        return $statusList[$orgIndex];
    }
}
