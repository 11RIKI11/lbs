<?php

require_once __DIR__.'/BaseController.php';

class HistoryController extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->SetRolesAccess([
            'guest' => AccessActions::Unauthorized,
            'user' => AccessActions::Forbidden,
            'admin' => AccessActions::Allow,
            'superadmin' => AccessActions::Allow
        ]);
    }

    #[Route("history")]
    public function index(){
        $title = 'История просмотра';
        if(!$this->TryGetAccess()){
            return;
        }
        $this->view("History", ['title' => $title]);
    }
}

?>