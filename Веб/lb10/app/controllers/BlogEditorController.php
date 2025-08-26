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
            $blogModel->delete();
        }
        header('Location: /blog');
        exit();
    }
}
