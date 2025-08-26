<?php

require_once __DIR__.'/../core/BaseActiveRecord.php';

class BlogModel extends BaseActiveRecord {

    public const IMG_DIR = __DIR__.'/../../public/images/blog';
    
    protected static $tablename = "blog";
    protected static $dbfields = [
        'id' => 'serial PRIMARY KEY',
        'title' => 'varchar(255)',
        'author' => 'varchar(255)',
        'img' => 'varchar',
        'content' => 'text',
        'created_at' => 'timestamp DEFAULT CURRENT_TIMESTAMP'
    ];

    public $id;
    public $title;
    public $author;
    public $img;
    public $content;
    public $created_at;

    private $validator;
    private $errors = [];

    public function __construct(array $data) {
        parent::__construct();
        static::createTable();
        $this->title = $data['title'] ?? null;
        $this->author = $data['author'] ?? 'admin';
        $this->content = $data['content'] ?? null;
        $this->img = $data['img'] ?? null;

        $dateTime = new DateTime('now', new DateTimeZone('Europe/Moscow'));
        $this->created_at = $data['created_at'] ?? $dateTime->format('Y-m-d H:i:s');
        $this->validator = new FormValidation();
        $this->validate();
    }

    public function validate() {
        $this->validator->setRule('title', 'isNotEmpty');
        $this->validator->setRule('content', 'isNotEmpty');
        $this->validator->setRule('author', 'isNotEmpty');

        $data =[
            'title' => $this->title,
            'content' => $this->content,
            'author' => $this->author
        ];
        $this->validator->validate($data);
        $this->errors = $this->validator->getErrors();
    }

    public function getErrorsHtml() {
        return $this->validator->generateErrorHTMLArrayByField();
    }

    public function getErrors() {
        return $this->errors;
    }

    public function isValid() {
        return $this->validator->isValid();
    }

    public function uploadImg($tempPath){
        if (empty($tempPath) || !is_uploaded_file($tempPath)) {
            $this->errors['img'] = 'Ошибка загрузки изображения';
            return false;
        }

        $extension = strtolower(pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION));
        
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        
        if (!in_array($extension, $allowedExtensions)) {
            $this->errors['img'] = 'Недопустимый формат изображения. Разрешены только JPG, PNG и GIF';
            return false;
        }

        if (!file_exists(self::IMG_DIR)) {
            mkdir(self::IMG_DIR, 0777, true);
        }

        $filename = uniqid() . '.' . $extension;
        $targetPath = self::IMG_DIR . '/' . $filename;

        if (move_uploaded_file($tempPath, $targetPath)) {
            $this->img = 'public/images/blog/' . $filename;
            return true;
        } else {
            $this->errors['img'] = 'Ошибка при сохранении изображения';
            return false;
        }
    }

    public static function uploadBlogFile($tempPath){
        $errors = [];
        
        $isValid = true;
        $fileHandle = fopen($tempPath, 'r');

        $uplodedPosts = [];
        
        if ($fileHandle) {
            try {
                while (($line = fgets($fileHandle)) !== false) {
                    $line = trim($line);
                    if (empty($line)) continue;
                    
                    $parts = explode(',', $line);
                    if (count($parts) !== 4) {
                        $isValid = false;
                        break;
                    }
                }
            } finally {
                fclose($fileHandle);
            }

            if($isValid){
                $sourceHandle = fopen($tempPath, 'r');

                while(($line = fgets($sourceHandle)) !== false){
                    $line = trim($line);
                    if(empty($line)) continue;

                    $parts = explode(',', $line);
                    
                    $data = [
                        'title' => $parts[0],
                        'content' => $parts[1],
                        'author' => $parts[2],
                        'created_at' => $parts[3]
                    ];

                    $blogModel = new BlogModel($data);

                    $blogModel->validate();
                    if($blogModel->isValid()){
                        $blogModel->save();
                        $uplodedPosts['success'][] = $blogModel;
                    }
                    else{
                        $uplodedPosts['error'][] = $blogModel->getErrors();
                    }
                    
                }

                fclose($sourceHandle);

                return $uplodedPosts;
            }

        } else {
            $errors['error'] = 'Ошибка при открытии файла';
        }

        return $errors;
    }
}
