<?php

class LogoutController extends BaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->SetRolesAccess([
            'guest' => AccessActions::GoToHome,
            'user' => AccessActions::Allow,
            'admin' => AccessActions::Allow,
            'superadmin' => AccessActions::Allow
        ]);
    }
    #[Route("logout")]
    public function index() {
        if(!$this->TryGetAccess()){
            return;
        }
        UserModel::logoutUser();
        header('Location: /');
        exit;
    }
}
