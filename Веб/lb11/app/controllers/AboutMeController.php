<?php

require_once __DIR__.'/BaseController.php';

class AboutMeController extends BaseController {
    public function __construct() {
        parent::__construct();
        $this->SetRolesAccess([
            'guest' => AccessActions::Allow,
            'user' => AccessActions::Allow,
            'admin' => AccessActions::Allow,
            'superadmin' => AccessActions::Allow
        ]);
    }
    #[Route("aboutme", 'GET')]
    public function index(){
        if(!$this->TryGetAccess()){
            return;
        }
        $title = 'Обо мне';
        $this->view('AboutMe', ['title' => $title]);
    }
}

?>