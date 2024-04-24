<?php
// white list routes

use System\Application;

$app = Application::getInstance();

$app->route->add('/' , 'Main/Home');
$app->route->add('/posts/:text/:id' , 'Posts/Post');