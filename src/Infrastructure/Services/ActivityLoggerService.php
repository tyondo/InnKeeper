<?php

namespace Tyondo\Innkeeper\Infrastructure\Services;

use Illuminate\Support\Facades\Request;
use Tyondo\Innkeeper\Database\Models\LogActivity;

class ActivityLoggerService
{
    protected function logActivityModel(): LogActivity{
        return new LogActivity();
    }
    public static function addToLog(string $subject,array $logDetails = [])
    {
        $log = $logDetails;
        $log['subject'] = $subject;
        $log['url'] = Request::fullUrl();
        $log['method'] = Request::method();
        $log['ip'] = Request::ip();
        $log['agent'] = Request::header('user-agent');
        $log['user_id'] = auth()->check() ? auth()->user()->id : 0;
        LogActivity::create($log);
    }

    public static function logActivityLists()
    {
        return LogActivity::latest()->get();
    }
}
