<?php

namespace Tyondo\Innkeeper\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Tyondo\Innkeeper\Infrastructure\BaseService;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function innkeeperServiceModel(): BaseService{
        return new BaseService();
    }
}
