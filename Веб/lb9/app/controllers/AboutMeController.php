<?php

require_once __DIR__.'/BaseController.php';

class AboutMeController extends BaseController {
    #[Route("aboutme", 'GET')]
    public function index(){
        $title = 'Обо мне';
        $this->view("AboutMe", ['title' => $title]);
    }
}

?>