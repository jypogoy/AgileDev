<?php

$router = $di->getRouter();

$router->add(
    '/',
    [
        'controller' => 'dashboard',
        'action' => 'index'
    ]
);

$router->add(
    '/syslogs',
    [
        'controller' => 'system_logs',
        'action' => 'index'
    ]
);


$router->handle();
