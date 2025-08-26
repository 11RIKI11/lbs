<?php

require_once __DIR__.'/BaseController.php';
require_once __DIR__.'/../core/ResultVerification.php';

class TestResultsController extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->SetRolesAccess([
            'guest' => AccessActions::Unauthorized,
            'user' => AccessActions::Allow,
            'admin' => AccessActions::Allow,
            'superadmin' => AccessActions::Allow
        ]);
    }

    #[Route("test/results")]
    public function index() {
        $title = 'Результаты тестирования';
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

        // Получаем все подходящие записи
        if($_SESSION['user']['role'] != null)
            $allResults = TestModel::findAllByFieldAssoc('userId', $_SESSION['user']['id']);

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