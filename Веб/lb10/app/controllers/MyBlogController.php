<?php

class MyBlogController extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->SetRolesAccess([
            'guest' => AccessActions::Unauthorized,
            'user' => AccessActions::Allow,
            'admin' => AccessActions::Allow,
            'superadmin' => AccessActions::Allow
        ]);
    }

    #[Route("blog")]
    public function index(){
        $title = 'Мой блог';
        if(!$this->TryGetAccess()){
            return;
        }
        $data = BlogModel::findAllAssoc();

        $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = 5;

        $totalPages = ceil(count($data) / $perPage);

        $offset = ($currentPage - 1) * $perPage;

        $posts = array_slice($data, $offset, $perPage);

        $this->view("MyBlog", ['title' => $title, 'posts' => $posts, 'currentPage' => $currentPage, 'totalPages' => $totalPages]);
    }
}

?>