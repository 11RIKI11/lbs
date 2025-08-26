<?php

require_once __DIR__.'/BaseController.php';
require_once __DIR__.'/../core/ResultVerification.php';

class TestController extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->SetRolesAccess([
            'guest' => AccessActions::Unauthorized,
            'user' => AccessActions::Allow,
            'admin' => AccessActions::Allow,
            'superadmin' => AccessActions::Allow
        ]);
    }

    #[Route("test")]
    public function index(){
        $title = 'Тест по дисциплине';
        if(!$this->TryGetAccess()){
            return;
        }
        $this->view("Test", ['title' => $title]);
    }

    #[Route("test", 'POST')]
    public function handleForm(){
        $title = 'Тест по дисциплине';
        if(!$this->TryGetAccess()){
            return;
        }
        $data = [
            'inputName' => $_POST['inputName'] ?? '',
            'studentGroup' => $_POST['studentGroup'] ?? '',
            'question1' => $_POST['question1'] ?? '',
            'question2' => $_POST['question2'] ?? '',
            'question3' => $_POST['question3'] ?? '',
            'userId' => $_SESSION['user']['id'] ?? null
        ];

        if($_SESSION['user']['id'] == null){
            UserModel::logoutUser();
            header('Location: /login');
            exit();
        }
        $testModel = new TestModel($data);
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