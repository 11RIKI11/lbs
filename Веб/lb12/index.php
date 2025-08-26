<?php
    require_once __DIR__.'/app/autoload.php';
    session_start();
    if (!isset($_SESSION['user'])) {
        $_SESSION['user']['role'] = 'guest';
        $_SESSION['user']['status'] = 'active';
        
    }
    BaseActiveRecord::setupConnection();
    VisitorModel::initializeTable();
    VisitorModel::saveVisitor();
    UserModel::initializeTable();

    $router = new Router();
    $router->dispatch();
?>