<?php

return [
    'name' => 'Update widget',
    'description' => 'A report widget showing available updates to installed plugins',
    'settings' => 'Manage settings related to the Uptime Robot plugin.',
    'permission' => [
        'view_widget' => 'View dashboard widget',
        'access_settings' => 'Manage settings',
    ],
    'widget' => [
        'plugin_updates' => 'Plugin updates',
    ],
];
