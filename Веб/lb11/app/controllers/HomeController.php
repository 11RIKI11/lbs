<?php

require_once __DIR__.'/BaseController.php';

class HomeController extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->SetRolesAccess([
            'guest' => AccessActions::Allow,
            'user' => AccessActions::Allow,
            'admin' => AccessActions::Allow,
            'superadmin' => AccessActions::Allow
        ]);
    }

    #[Route("")]
    public function index(){
        var_dump($_SESSION);
        $title = 'Главная страница';
        if(!$this->TryGetAccess()){
            return;
        }
        $this->view("MainPage", ['title' => $title]);
    }
}

?>