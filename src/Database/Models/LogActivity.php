<?php

namespace Tyondo\Innkeeper\Database\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogActivity extends Model
{
    use HasFactory;
    protected $connection = "innkeeper";
    protected $guarded = ['id'];
}
