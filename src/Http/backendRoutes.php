<?php

$router->get('/impersonate/{id}', 'ImpersonatorController@impersonate')->name('impersonate');
$router->get('/stop-impersonating', 'ImpersonatorController@stopImpersonating')->name('stop-impersonate');