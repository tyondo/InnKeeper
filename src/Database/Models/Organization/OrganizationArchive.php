<?php

namespace Tyondo\Innkeeper\Database\Models\Organization;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrganizationArchive extends Model
{
    use HasFactory;
    protected $connection = 'innkeeper';
    protected $guarded = ['id'];

    protected $casts = [
        'organization_archive_details' => 'array',
    ];
}
