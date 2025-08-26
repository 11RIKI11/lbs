<?php

require_once __DIR__.'/BaseController.php';

class GuestBookController extends BaseController {
    #[Route("guestbook")]
    public function index(){
        $title = 'Гостевая книга';
        $messages = GuestBookModel::getMessages();
        $this->view("GuestBook", ['title' => $title, 'messages' => $messages]);
    }

    #[Route("guestbook", 'POST')]
    public function handleForm(){
        $title = 'Гостевая книга';
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