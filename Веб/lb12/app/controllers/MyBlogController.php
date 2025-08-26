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

        // Загрузка комментариев для каждого поста
        foreach ($posts as &$post) {
            $postId = $post['id'];
            $post['comments'] = BlogCommentModel::findAllByFieldAssoc('blog_id', $postId);
        }

        $this->view("MyBlog", [
            'title' => $title,
            'posts' => $posts,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages
        ]);
    }

    #[Route("blog/postComment")]
    public function postComment() {
        header('Content-Type: text/javascript; charset=utf-8');

        $xml = $_GET['xml'] ?? '';
        $cb = $_GET['cb'] ?? 'makeCommentComplete';

        if (!$xml) {
            echo 'var js_ErrCode = true;' . "\n";
            echo 'var js_ErrMsg = "Нет данных";' . "\n";
            echo 'var js_Result = null;' . "\n";
            echo "{$cb}();\n";
            exit;
        }

        libxml_use_internal_errors(true);
        $xmlObj = simplexml_load_string($xml);
        if (!$xmlObj) {
            echo 'var js_ErrCode = true;' . "\n";
            echo 'var js_ErrMsg = "Ошибка разбора XML";' . "\n";
            echo 'var js_Result = null;' . "\n";
            echo "{$cb}();\n";
            exit;
        }

        $postId = isset($xmlObj->postId) ? urldecode((string)$xmlObj->postId) : null;
        $text = (isset($xmlObj->text) && !empty($xmlObj->text)) ? urldecode((string)$xmlObj->text) : null;
        $author = $_SESSION['user']['fio'] ?? 'Неизвестный автор';

        if (!$postId || !$text) {
            echo 'var js_ErrCode = true;' . "\n";
            echo 'var js_ErrMsg = "Не все поля заполнены";' . "\n";
            echo 'var js_Result = null;' . "\n";
            echo "{$cb}();\n";
            exit;
        }

        $comment = new BlogCommentModel([
            'author' => $author,
            'content' => $text,
            'blog_id' => $postId
        ]);

        if (!$comment->isValid()) {
            echo 'var js_ErrCode = true;' . "\n";
            echo 'var js_ErrMsg = "Ошибка валидации";' . "\n";
            echo 'var js_Result = null;' . "\n";
            echo "{$cb}();\n";
            exit;
        }

        $comment->save();

        echo 'var js_ErrCode = false;' . "\n";
        echo 'var js_ErrMsg = "Комментарий добавлен";' . "\n";
        echo 'var js_Result = "ok";' . "\n";
        echo "{$cb}();\n";
        exit;
    }
}

?>