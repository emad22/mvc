<?php

// echo __DIR__; // __DIR__ =>  C:\wamp64\www\blog

require __DIR__ .'/vendor/System/Application.php';
require __DIR__ . '/vendor/System/File.php';

use System\Application;
use System\File;

$file = new File(__DIR__);
$app =  Application::getInstance($file);

$app->run();

// var_dump(pre($app));
// use App\Controllers\Users\UsersCotroller;
// new UsersCotroller;
// $app->Session->set('name' , 'emad');