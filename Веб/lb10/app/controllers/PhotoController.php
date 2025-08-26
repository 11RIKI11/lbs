<?php

require_once __DIR__.'/BaseController.php';

class PhotoController extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->SetRolesAccess([
            'guest' => AccessActions::Allow,
            'user' => AccessActions::Allow,
            'admin' => AccessActions::Allow,
            'superadmin' => AccessActions::Allow
        ]);
    }

    #[Route("photo")]
    public function index(){
        $title = 'Мой фотоальбом';
        if(!$this->TryGetAccess()){
            return;
        }
        PhotoModel::initializeTable();
        $photos = PhotoModel::findAllAssoc();
        $this->view("Photo", ['title' => $title, 'photos' => $photos]);
    }
}

?>