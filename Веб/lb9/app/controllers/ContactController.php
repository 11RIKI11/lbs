<?php

require_once __DIR__.'/BaseController.php';
require_once __DIR__.'/../core/FormValidation.php';

class ContactController extends BaseController {
    #[Route("contact")]
    public function index(){
        $title = 'Контакт';
        $this->view("Contact", ['title' => $title]);
    }
    
    #[Route("contact", 'POST')]
    public function handleForm(){
        $title = 'Контакт';
        $contactModel = new ContactModel($_POST);
        $contactModel->validate();
        $errorsTags = $contactModel->getErrorsHtml();
        $errors = $contactModel->getErrors();

        if($contactModel->isValid()){
            $contactModel->save();
            header('Location: /');
            exit();
        }
        $this->view("Contact", ['title' => $title, 'errorsTags' => $errorsTags, 'errors' => $errors, 'formData' => $_POST]);
    }
}

?>