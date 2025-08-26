<?php

class BlogCommentModel extends BaseActiveRecord {
    protected static $tablename = 'blog_comments';
    protected static $dbfields = [
        'id' => 'serial PRIMARY KEY',
        'author' => 'varchar(255)',
        'content' => 'text',
        'created_at' => 'timestamp DEFAULT CURRENT_TIMESTAMP',
        'blog_id' => 'int'
    ];

    protected static $foreignKeys =[
        'blog_id' => 'blog(id)'
    ];
    public $id;
    public $author;
    public $content;
    public $created_at;
    public $blog_id;
    private $validator; 
    private $errors = [];
    public function __construct(array $data = []) {
        parent::__construct();
        static::createTable();
        $this->author = $data['author'] ?? null;
        $this->content = $data['content'] ?? null;
        $this->blog_id = $data['blog_id'] ?? 1; // Default blog ID, can be changed later

        $dateTime = new DateTime('now', new DateTimeZone('Europe/Moscow'));
        $this->created_at = $data['created_at'] ?? $dateTime->format('Y-m-d H:i:s');
        $this->validator = new FormValidation();
        $this->validate();
    }

    public function validate() {
        $this->validator->setRule('author', 'isNotEmpty');
        $this->validator->setRule('content', 'isNotEmpty');
        $this->validator->setRule('blog_id', 'isNotEmpty');
        $data = [
            'author' => $this->author,
            'content' => $this->content,
            'blog_id' => $this->blog_id
        ];
        $this->validator->validate($data);
        $this->errors = $this->validator->getErrors();
    }
    public function getErrorsHtml(){
        return $this->validator->generateErrorHTMLArrayByField();
    }

    public function getErrors(){
        return $this->errors;
    }

    public function isValid(){
        return $this->validator->isValid();
    }
}

?>