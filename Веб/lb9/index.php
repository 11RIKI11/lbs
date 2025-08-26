<?php
    require_once __DIR__.'/app/autoload.php';
    BaseActiveRecord::setupConnection();
    $router = new Router();
    $router->dispatch();
?>