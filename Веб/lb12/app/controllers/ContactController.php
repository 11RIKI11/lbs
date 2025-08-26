<?php

require_once __DIR__.'/BaseController.php';
require_once __DIR__.'/../core/FormValidation.php';

class ContactController extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->SetRolesAccess([
            'guest' => AccessActions::Unauthorized,
            'user' => AccessActions::Allow,
            'admin' => AccessActions::Allow,
            'superadmin' => AccessActions::Allow
        ]);
    }

    #[Route("contact")]
    public function index(){
        $title = 'Контакт';
        if(!$this->TryGetAccess()){
            return;
        }
        $this->view("Contact", ['title' => $title]);
    }
    
    #[Route("contact", 'POST')]
    public function handleForm(){
        $title = 'Контакт';
        if(!$this->TryGetAccess()){
            return;
        }
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