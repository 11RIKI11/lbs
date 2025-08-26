<?php

require_once __DIR__.'/BaseController.php';

class GuestBookController extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->SetRolesAccess([
            'guest' => AccessActions::Allow,
            'user' => AccessActions::Allow,
            'admin' => AccessActions::Allow,
            'superadmin' => AccessActions::Allow
        ]);
    }

    #[Route("guestbook")]
    public function index(){
        $title = 'Гостевая книга';
        if(!$this->TryGetAccess()){
            return;
        }
        $messages = GuestBookModel::getMessages();
        $this->view("GuestBook", ['title' => $title, 'messages' => $messages]);
    }

    #[Route("guestbook", 'POST')]
    public function handleForm(){
        $title = 'Гостевая книга';
        if(!$this->TryGetAccess()){
            return;
        }
        $guestBookModel = new GuestBookModel($_POST);
        $guestBookModel->validate();

        if ($guestBookModel->isValid()) {
            $guestBookModel->saveMessage();
            header('Location: /guestbook');
            exit();
        }

        $errors = $guestBookModel->getErrors();
        $errorsTags = $guestBookModel->getErrorsHtml();
        $messages = GuestBookModel::getMessages();
        $this->view("GuestBook", [
            'title' => $title,
            'messages' => $messages,
            'formData' => $_POST,
            'errors' => $errors,
            'errorsTags' => $errorsTags
        ]);
    }
}

?>