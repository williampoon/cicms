<?php

return [
    'client' => [
        'host'        => 'http://dev.center.com/api/receive',
        'port'        => 80,
        'timeout'     => 1,
        'secret_key'  => 'aaa',
        'project'     => 'oa',
        'allow_types' => [
            'image/jpeg',
            'image/png',
            'image/gif',
            'application/x-empty',
            'application/octet-stream',
        ],
        'allow_size'  => 1024 * 1024 * 1024,
    ],
    'server' => [
        'secret_key'     => 'aaa',
        // 允许上传的项目
        'allow_projects' => [
            'oa',
        ],
        'allow_types'    => [
            'image/jpeg',
            'image/png',
            'image/gif',
            'application/x-empty',
            'application/octet-stream',
        ],
        'allow_size'     => 1024 * 1024 * 1024,
    ],
];
