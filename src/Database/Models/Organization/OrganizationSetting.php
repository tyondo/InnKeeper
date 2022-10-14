<?php

namespace Tyondo\Innkeeper\Database\Models\Organization;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrganizationSetting extends Model
{
    use HasFactory;
    protected $connection = 'innkeeper';
}
