<?php

require_once __DIR__.'/BaseController.php';

class StudyController extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->SetRolesAccess([
            'guest' => AccessActions::Unauthorized,
            'user' => AccessActions::Allow,
            'admin' => AccessActions::Allow,
            'superadmin' => AccessActions::Allow
        ]);
    }

    #[Route("study")]
    public function index(){
        $title = 'Учёба';
        if(!$this->TryGetAccess()){
            return;
        }
        $this->view("Study", ['title' => $title]);
    }
}

?>