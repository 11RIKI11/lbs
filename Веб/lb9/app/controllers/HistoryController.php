<?php

require_once __DIR__.'/BaseController.php';

class HistoryController extends BaseController {
    #[Route("history")]
    public function index(){
        $title = 'История просмотра';
        $this->view("History", ['title' => $title]);
    }
}

?>