<?php

return [
    'flows' => [
        'MODEL NAMESPACE GELECEK' => [
            ['name' => 'Manager Approval', 'type' => 'role', 'identifier' => ['manager']],
            ['name' => 'Finance Approval', 'type' => 'role', 'identifier' => ['finance']],
            ['name' => 'GM Approval', 'type' => 'user', 'identifier' => [1]],
        ],
    ],
    'permission_name' => 'approval_direct',
];
