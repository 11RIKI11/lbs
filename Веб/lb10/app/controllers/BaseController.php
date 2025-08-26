<?php


enum AccessActions: string{
    case Allow = 'allow';
    case Forbidden = 'forbidden';
    case Unauthorized = 'unauthorized';
    case GoToHome = 'goToHome';
}

class BaseController {

    protected $allRoles = ['guest', 'user', 'admin', 'superadmin'];
    protected $rolesAccess = [];
    protected $rolesLayouts = [
        'guest' => 'guest/GuestLayout',
        'user' => 'user/UserLayout',
        'admin'=> 'admin/AdminLayout',
        'superadmin'=> 'superadmin/SuperadminLayout'
    ];

    protected string $viewPath = __DIR__. '/../Views/';

    public function __construct() {
        $this->SetRolesAccess([
            'guest' => AccessActions::Unauthorized,
            'user' => AccessActions::Forbidden,
            'admin' => AccessActions::Forbidden,
            'superadmin' => AccessActions::Forbidden
        ]);
    }
    public function view(string $viewName, $data = []): void
    {
        if(session_status() == PHP_SESSION_NONE){
            session_start();
        }
        if(!isset($_SESSION['user']) && !isset($_SESSION['user']['role'])){
            $_SESSION['user']['role'] = 'guest';
            $_SESSION['user']['status'] = 'active';
        }
        extract($data);
        $content = $viewName . '.php';
        require_once $this->viewPath . $this->rolesLayouts[$_SESSION['user']['role']] . '.php';
    }

    protected function SetRolesAccess(array $accessActions = []): void {

        foreach ($this->allRoles as $role) {
            if (!array_key_exists($role, $accessActions)) {
                $accessActions[$role] = AccessActions::Forbidden;
            }
        }

        foreach ($accessActions as $role => $action) {
            $this->SetAccess($role, $action);
        }
    }

    protected function SetAccess(string $role, AccessActions $action): void {
        if (!in_array($role, $this->allRoles, true)) {
                throw new InvalidArgumentException("Invalid role: $role");
        }

        if (!$action instanceof AccessActions) {
            throw new InvalidArgumentException("Invalid action for role $role");
        }
        $this->rolesAccess[$role] = $action;
    }

    protected function TryGetAccess(): bool {
        if(UserModel::isBlockedUser()){
            UserModel::logoutUser();
            header('Location: /');
            exit;
        }
        if(session_status() == PHP_SESSION_NONE){
            session_start();
        }
        if(!isset($_SESSION['user']) && !isset($_SESSION['user']['role'])){
            $_SESSION['user']['role'] = 'guest';
            $_SESSION['user']['status'] = 'active';
        }
        if($this->rolesAccess[$_SESSION['user']['role']] === AccessActions::Forbidden){
            $this->view('errors/Forbidden');
            return false;
        }
        if($this->rolesAccess[$_SESSION['user']['role']] === AccessActions::Unauthorized){
            $this->view('errors/Unauthorized');
            return false;
        }
        if($this->rolesAccess[$_SESSION['user']['role']] === AccessActions::GoToHome){
            header('Location: /');
            exit();
        }
        return true;
    }
}

?>