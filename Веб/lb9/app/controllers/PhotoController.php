<?php

require_once __DIR__.'/BaseController.php';

class PhotoController extends BaseController {
    #[Route("photo")]
    public function index(){
        $title = 'Мой фотоальбом';
        PhotoModel::initializeTable();
        $photos = PhotoModel::findAllAssoc();
        $this->view("Photo", ['title' => $title, 'photos' => $photos]);
    }
}

?>