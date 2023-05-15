<?php

use App\Models\Logs;

function addToLog($type, $message)
{
    $log = [];
    $log['type'] = $type;
    $log['message'] = $message;
    $log['user_id'] = auth()->check() ? auth()->user()->id : 1;
    Logs::create($log);
}