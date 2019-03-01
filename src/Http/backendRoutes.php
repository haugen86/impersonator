<?php

$router->get('/impersonate/{id}', 'ImpersonatorController@impersonate');
$router->get('/stop-impersonating', 'ImpersonatorController@stopImpersonating');