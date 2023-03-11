<?php

namespace Tyondo\Innkeeper\Infrastructure;

use Tyondo\Innkeeper\Database\Models\Organization\Organization;
use Tyondo\Innkeeper\Database\Models\Organization\OrganizationConnection;

class BaseService
{
    public function organizationModel(): Organization{
        return new Organization();
    }

    public function organizationConnectionModel(): OrganizationConnection{
        return new OrganizationConnection();
    }
}
