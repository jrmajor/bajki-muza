<?php

return [

    'delete_log_items_older_than_days' => 30,

    'date_format' => 'Y-m-d H:i:s',

    'models' => [
        'monitored_scheduled_task' => Spatie\ScheduleMonitor\Models\MonitoredScheduledTask::class,
        'monitored_scheduled_log_item' => Spatie\ScheduleMonitor\Models\MonitoredScheduledTaskLogItem::class,
    ],

    'oh_dear' => [
        'api_token' => env('OH_DEAR_API_TOKEN', ''),
        'site_id' => env('OH_DEAR_SITE_ID'),
        'queue' => env('OH_DEAR_QUEUE'),
        'retry_job_for_minutes' => 10,
    ],

];
