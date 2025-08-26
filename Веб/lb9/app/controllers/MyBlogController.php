<?php

require_once __DIR__.'/BaseController.php';

class MyBlogController extends BaseController {
    #[Route("blog")]
    public function index(){
        $title = 'Мой блог';
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