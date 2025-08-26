<?php

class AdminPanelController extends BaseController
{

    public function __construct() {
        parent::__construct();
        $this->SetRolesAccess([
            'guest' => AccessActions::Unauthorized,
            'user' => AccessActions::Forbidden,
            'admin' => AccessActions::Allow,
            'superadmin' => AccessActions::Allow
        ]);
    }

    #[Route("admin")]
    public function index()
    {
        $title = 'Панель администратора';
        if(!$this->TryGetAccess()){
            return;
        }
        $this->view("AdminPanel", ['title' => $title]);
    }

    #[Route("admin/users")]
    public function manageUsers()
    {
        $title = 'Управление пользователями';
        if(!$this->TryGetAccess()){
            return;
        }
        // Получаем всех пользователей для таблицы
        $users = UserModel::findAllAssoc();
        $this->view("AdminPanelUsers", [
            'title' => $title,
            'users' => $users
        ]);
    }

    #[Route("admin/users/edit")]
    public function userEdit() {
        $title = 'Редактор пользователя';
        if (!$this->TryGetAccess()) {
            return;
        }
        var_dump($_SESSION['user']);
        // Если передан id, показываем форму редактирования конкретного пользователя
        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $userId = (int)$_GET['id'];
            $user = UserModel::find($userId);
            if (!$user) {
                $this->view("errors/NotFound", ['title' => $title]);
                return;
            }
            $this->view("UsersEditor", ['title' => $title, 'user' => $user]);
            return;
        }
        header('Location: /admin/users');
        exit();
    }

    #[Route("admin/users/edit", "POST")]
    public function userEditHandle() {
        $title = 'Редактор пользователя';
        if (!$this->TryGetAccess()) {
            return;
        }

        // Получаем id из POST-параметра
        $id = isset($_POST['id']) ? (int)$_POST['id'] : null;
        if (!$id) {
            header('Location: /admin/users');
            exit();
        }

        $user = UserModel::find($id);
        if (!$user) {
            $this->view("errors/NotFound", ['title' => $title]);
            return;
        }

        // Обновляем поля
        error_log(print_r($_POST, true)); // Вывод в серверную консоль/лог
        echo "<script>console.log(" . json_encode($_POST) . ");</script>"; // Вывод в консоль браузера
        $user->fio = $_POST['fio'] ?? $user->fio;
        $user->login = $_POST['login'] ?? $user->login;
        $user->email = $_POST['email'] ?? $user->email;
        $user->role_id = $_POST['role_id'] ?? $user->role_id;
        $user->status_id = $_POST['status_id'] ?? $user->status_id;

        // Если задан новый пароль, обновляем его
        if (!empty($_POST['password'])) {
            $user->setPassword($_POST['password']);
        }

        $user->updated_at = date('Y-m-d H:i:s');
        $user->save();

        // После сохранения возвращаемся к списку пользователей или показываем сообщение
        header('Location: /admin/users');
        exit();
    }

    #[Route("admin/users/delete")]
    public function userDelete() {
        $title = 'Удаление пользователя';
        if (!$this->TryGetAccess()) {
            return;
        }

        // Получаем id из GET-параметра
        $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
        if (!$id) {
            header('Location: /admin/users');
            exit();
        }

        $user = UserModel::find($id);
        if (!$user) {
            $this->view("errors/NotFound", ['title' => $title]);
            return;
        }

        // Удаляем пользователя
        $user->delete();

        // После удаления возвращаемся к списку пользователей или показываем сообщение
        header('Location: /admin/users');
        exit();
    }

    #[Route("admin/visitors")]
    public function getVisitors() {
        $title = 'Посетители сайта';
        if (!$this->TryGetAccess()) {
            return;
        }

        $visitors = VisitorModel::findAllAssoc();
        $this->view("Visitors", [
            'title' => $title,
            'visitors' => $visitors
        ]);
    }

    #[Route("admin/test/results")]
    public function adminTestResults() {
        $title = 'Результаты тестирования (Админ)';
        if (!$this->TryGetAccess()) {
            return;
        }
        if($_SESSION['user']['id'] == null){
            UserModel::logoutUser();
            header('Location: /login');
            exit();
        }

        // Параметры пагинации
        $perPage = 10;
        $currentPage = isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0 ? (int)$_GET['page'] : 1;
        $offset = ($currentPage - 1) * $perPage;

        $allResults = TestModel::findAllAssoc();

        $totalRows = count($allResults);
        $totalPages = max(1, ceil($totalRows / $perPage));
        $results = array_slice($allResults, $offset, $perPage);

        $this->view("TestResults", [
            'title' => $title,
            'results' => $results,
            'totalPages' => $totalPages,
            'currentPage' => $currentPage,
            'perPage' => $perPage
        ]);
    }
}

?>