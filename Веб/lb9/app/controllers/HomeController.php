<?php

require_once __DIR__.'/BaseController.php';

class HomeController extends BaseController {
    #[Route("")]
    public function index(){
        $title = 'Главная страница';
        $this->view("MainPage", ['title' => $title]);
    }
}

?>