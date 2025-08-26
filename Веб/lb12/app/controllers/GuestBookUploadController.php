<?php

require_once __DIR__.'/BaseController.php';
require_once __DIR__.'/../models/GuestBookModel.php';

class GuestBookUploadController extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->SetRolesAccess([
            'guest' => AccessActions::Unauthorized,
            'user' => AccessActions::Forbidden,
            'admin' => AccessActions::Allow,
            'superadmin' => AccessActions::Allow
        ]);
    }

    #[Route("guestbook/upload")]
    public function index() {
        $title = 'Загрузка сообщений гостевой книги';
        if(!$this->TryGetAccess()){
            return;
        }
        $this->view("GuestBookUpload", ['title' => $title]);
    }

    #[Route("guestbook/upload", "POST")]
    public function handleUpload() {
        $title = 'Загрузка сообщений гостевой книги';
        if(!$this->TryGetAccess()){
            return;
        }
        $errors = [];
        $success = false;

        if (isset($_FILES['messages_file'])) {
            $file = $_FILES['messages_file'];
            
            if ($file['error'] === UPLOAD_ERR_OK) {
                $errors = GuestBookModel::uploadMessagesFile($file['tmp_name']);
                $success = empty($errors);
                header('Location: /guestbook');
                exit();
            } else {
                $errors[] = 'Ошибка при загрузке файла';
            }
        }

        $this->view("GuestBookUpload", [
            'title' => $title,
            'errors' => $errors,
            'success' => $success
        ]);
    }
} 