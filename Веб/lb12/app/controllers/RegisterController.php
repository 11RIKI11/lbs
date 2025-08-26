<?php

class RegisterController extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->SetRolesAccess([
            'guest' => AccessActions::Allow,
            'user' => AccessActions::GoToHome,
            'admin' => AccessActions::GoToHome,
            'superadmin' => AccessActions::GoToHome
        ]);
    }

    #[Route("auth/register")]
    public function index(){
        $title = 'Регистрация';
        if(!$this->TryGetAccess()){
            return;
        }
        $this->view("auth/Register", ['title' => $title]);
    }

    #[Route("auth/register", 'POST')]
    public function handleForm(){
        $title = 'Регистрация';
        if(!$this->TryGetAccess()){
            return;
        }
        
        $data = [
            'fio' => $_POST['fio'] ?? '',
            'login' => $_POST['login'] ?? '',
            'email' => $_POST['email'] ?? '',
            'password' => $_POST['password'] ?? '',
        ];

        $registerModel = new RegisterFormModel($data);
        $registerModel->validate();
        $errors = $registerModel->getErrors();
        $errorsTags = $registerModel->getErrorsHtml();

        if ($registerModel->isValid()) {
            $registerResult = UserModel::registerUser($data);
            if($registerResult['status'] == 'success'){
                UserModel::loginUser($data['login'], $data['password']);
                header('Location: /');
                exit;
            }
            if(isset($registerResult['errors'])){
                foreach($registerResult['errors'] as $key => $error) {
                    if(!isset($errors[$key])) {
                        $errors[$key] = $error;
                    }
                }
            }
            if(isset($registerResult['errorsTags'])) {
                foreach($registerResult['errorsTags'] as $key => $error) {
                    if(!isset($errorsTags[$key])) {
                        $errorsTags[$key] = $error;
                    }
                }
            }
        }

        $this->view("auth/Register", [
            'title' => 'Регистрация',
            'formData' => $data,
            'errors' => $errors,
            'errorsTags' => $errorsTags
        ]);
    }

    #[Route("auth/check-login", "POST")]
    public function checkLoginAvailability() {
        header('Content-Type: application/json');
        $login = $_POST['login'] ?? '';
        $result = [
            'available' => false,
            'message' => 'Логин не указан'
        ];
        if ($login !== '') {
            // Предполагается, что UserModel::isLoginAvailable реализован
            if (UserModel::isLoginAvailable($login)) {
                $result['available'] = true;
                $result['message'] = 'Логин свободен';
            } else {
                $result['message'] = 'Логин уже занят';
            }
        }
        echo json_encode($result);
        exit;
    }
}