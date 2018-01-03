<?php

$router = $di->getRouter();

$router->add(
    '/',
    [
        'controller' => 'dashboard',
        'action' => 'index'
    ]
);

$router->handle();
