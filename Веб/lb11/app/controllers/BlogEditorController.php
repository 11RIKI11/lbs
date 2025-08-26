<?php

class BlogEditorController extends BaseController
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

    #[Route("blog/add")]
    public function index()
    {
        $title = 'Редактор блога';
        if(!$this->TryGetAccess()){
            return;
        }
        $this->view("BlogEditor", ['title' => $title]);
    }

    #[Route("blog/add", "POST")]
    public function handleAddForm()
    {
        $title = 'Редактор блога';
        if(!$this->TryGetAccess()){
            return;
        }
        $postData = [
            'title' => $_POST['title'] ?? '',
            'content' => $_POST['inputMessage'] ?? '',
            'img' => null,
            'author' => $_SESSION['user']['fio'] ?? 'admin',
        ];

        $blogModel = new BlogModel($postData);

        if ($blogModel->isValid()) {
            if(isset($_FILES['img']) && $_FILES['img']['error'] === UPLOAD_ERR_OK){
                $blogModel->uploadImg($_FILES['img']['tmp_name']);
            }
            $blogModel->save();
            header('Location: /blog');
            exit();
        }

        $this->view("BlogEditor", [
            'title' => $title,
            'post' => $postData,
            'errorsTags' => $blogModel->getErrorsHtml()
        ]);
    }

    #[Route("blog/add/upload")]
    public function upload()
    {
        $title = 'Редактор блога';
        if(!$this->TryGetAccess()){
            return;
        }
        $this->view("BlogUpload", ['title' => $title]);
    }

    #[Route("blog/add/upload", "POST")]
    public function handleUploadForm()
    {
        $title = 'Редактор блога';
        if(!$this->TryGetAccess()){
            return;
        }
        $errors = [];
        $success = false;

        if(isset($_FILES['blog_file'])){
            $file = $_FILES['blog_file'];
            if($file['error'] === UPLOAD_ERR_OK){
                $rusult = BlogModel::uploadBlogFile($file['tmp_name']);
                if(isset($rusult['success'])){
                    $success = true;
                }
                else{
                    $errors[] = $rusult['error'];
                }
                header('Location: /blog');
                exit();
            }
            else{
                $errors[] = 'Ошибка при загрузке файла';
                header('Location: /blog');
                exit();
            }
        }
        $this->view("BlogUpload", ['title' => $title, 'errors' => $errors, 'success' => $success]);
    }

    #[Route("blog/delete")]
    public function delete()
    {
        $title = 'Редактор блога';
        if(!$this->TryGetAccess()){
            return;
        }
        $postId = $_GET['id'] ?? null;
        if($postId !== null && is_numeric($postId)){
            $blogModel = BlogModel::find($postId);
            if($blogModel === null){
                header('Location: /blog');
                exit();
            }
            $blogComments = BlogCommentModel::findAllByField('blog_id', $postId);
            foreach ($blogComments as $comment) {
                $comment->delete();
            }
            $blogModel->delete();
        }
        header('Location: /blog');
        exit();
    }

    #[Route("blog/comment/delete")]
    public function deleteComment()
    {
        $id = $_GET['id'] ?? null;
        $cb = $_GET['cb'] ?? 'onCommentDeleted';
        header('Content-Type: application/javascript; charset=utf-8');
        if (!$id || !is_numeric($id)) {
            echo $cb . '({status:"error", message:"Некорректный id"});';
            exit;
        }
        $comment = BlogCommentModel::find($id);
        if (!$comment) {
            echo $cb . '({status:"error", message:"Комментарий не найден"});';
            exit;
        }
        $comment->delete();
        echo $cb . '({status:"success"});';
        exit;
    }


    #[Route("blog/edit", "POST")]
    public function edit()
    {
        header('Content-Type: application/json; charset=utf-8');
        if(!$this->TryGetAccess()){
            echo json_encode(['status' => 'error', 'message' => 'Нет доступа']);
            exit;
        }
        $id = $_POST['id'] ?? null;
        $title = $_POST['title'] ?? '';
        $content = $_POST['content'] ?? '';
        if (!$id || !is_numeric($id)) {
            echo json_encode(['status' => 'error', 'message' => 'Некорректный id']);
            exit;
        }
        $blog = BlogModel::find($id);
        if (!$blog) {
            echo json_encode(['status' => 'error', 'message' => 'Пост не найден']);
            exit;
        }
        $blog->title = $title;
        $blog->content = $content;
        if (!$blog->isValid()) {
            echo json_encode(['status' => 'error', 'message' => 'Ошибка валидации']);
            exit;
        }
        $blog->save();
        echo json_encode(['status' => 'success']);
        exit;
    }
}
