<?php

require_once __DIR__.'/BaseController.php';
require_once __DIR__.'/../core/ResultVerification.php';

class TestController extends BaseController {
    #[Route("test")]
    public function index(){
        $title = 'Тест по дисциплине';
        $this->view("Test", ['title' => $title]);
    }

    #[Route("test", 'POST')]
    public function handleForm(){
        $title = 'Тест по дисциплине';
        $testModel = new TestModel($_POST);
        $testModel->validate();
        $errorsTags = $testModel->getErrorsHtml();
        $errors = $testModel->getErrors();

        if($testModel->isValid()){
            $testModel->save();
            header('Location: /');
            exit();
        }

        $this->view("Test", ['title' => $title, 'errorsTags' => $errorsTags, 'errors' => $errors, 'formData' => $_POST]);
    }
}

?>