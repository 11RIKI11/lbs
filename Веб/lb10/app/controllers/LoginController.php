<?php

class LoginController extends BaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->SetRolesAccess([
            'guest' => AccessActions::Allow,
            'user' => AccessActions::GoToHome,
            'admin' => AccessActions::GoToHome,
            'superadmin' => AccessActions::GoToHome
        ]);
    }

    #[Route("auth/login")]
    public function index()
    {
        $title = 'Авторизация';
        if (!$this->TryGetAccess()) {
            return;
        }
        $this->view("auth/Login", ['title' => $title]);
    }

    #[Route("auth/login", 'POST')]
    public function handleForm()
    {
        $title = 'Авторизация';
        if (!$this->TryGetAccess()) {
            return;
        }

        $data = [
            'login' => $_POST['login'],
            'password' => $_POST['password']
        ];

        $loginModel = new LoginFormModel($data);
        $loginModel->validate();

        $errors = $loginModel->getErrors();
        $errorsTags = $loginModel->getErrorsHtml();

        if ($loginModel->isValid()) {
            $loginResult = UserModel::loginUser($data['login'], $data['password']);
            if ($loginResult['loginStatus'] == 'success') {
                header('Location: /');
                exit;
            }
            if (isset($loginResult['errors'])) {
                foreach ($loginResult['errors'] as $key => $error) {
                    // Перезаписываем только если нет ошибки валидации
                    if (!isset($errors[$key])) {
                        $errors[$key][] = $error;
                    }
                }
            }
            if (isset($loginResult['errorsTags'])) {
                foreach ($loginResult['errorsTags'] as $key => $error) {
                    // Перезаписываем только если нет ошибки валидации
                    if (!isset($errorsTags[$key])) {
                        $errorsTags[$key][] = $error;
                    }
                }
            }
        }

        $this->view("auth/Login", [
            'title' => $title,
            'formData' => $_POST,
            'errors' => $errors,
            'errorsTags' => $errorsTags
        ]);
    }
}
