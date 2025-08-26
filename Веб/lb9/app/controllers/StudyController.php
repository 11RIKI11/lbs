<?php

require_once __DIR__.'/BaseController.php';

class StudyController extends BaseController {
    #[Route("study")]
    public function index(){
        $title = 'Учёба';
        $this->view("Study", ['title' => $title]);
    }
}

?>