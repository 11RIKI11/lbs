<?php

require_once __DIR__.'/../core/BaseActiveRecord.php';

class PhotoModel extends BaseActiveRecord {
    protected static $tablename = "photos";
    protected static $dbfields = [
        'id' => 'serial PRIMARY KEY',
        'filename' => 'varchar(255)',
        'caption' => 'varchar(255)'
    ];

    public $id;
    public $filename;
    public $caption;

    public function __construct(string|null $filename = null, string|null $caption = null)
    {
        parent::__construct();
        $this->filename = $filename;
        $this->caption = $caption;
    }

    public static function initializeTable() {
        static::createTable();

        $initialData = [
            [
                'filename' => 'public/images/albom/profile.jpg',
                'caption' => 'Фото профиля'
            ],
            [
                'filename' => 'public/images/albom/Russia.jpeg',
                'caption' => 'Россия'
            ],
            [
                'filename' => 'public/images/albom/cat.jpg',
                'caption' => 'Котик'
            ],
            [
                'filename' => 'public/images/albom/img4.jpg',
                'caption' => 'Изображение 4'
            ],
            [
                'filename' => 'public/images/albom/img5.jpg',
                'caption' => 'Изображение 5'
            ],
            [
                'filename' => 'public/images/albom/img6.jpg',
                'caption' => 'Изображение 6'
            ],
            [
                'filename' => 'public/images/albom/img7.jpg',
                'caption' => 'Изображение 7'
            ],
            [
                'filename' => 'public/images/albom/img8.jpg',
                'caption' => 'Изображение 8'
            ],
            [
                'filename' => 'public/images/albom/img9.jpg',
                'caption' => 'Изображение 9'
            ],
            [
                'filename' => 'public/images/albom/img10.jpg',
                'caption' => 'Изображение 10'
            ],
            [
                'filename' => 'public/images/albom/img11.jpg',
                'caption' => 'Изображение 11'
            ],
            [
                'filename' => 'public/images/albom/img12.jpg',
                'caption' => 'Изображение 12'
            ],
            [
                'filename' => 'public/images/albom/img13.jpg',
                'caption' => 'Изображение 13'
            ],
            [
                'filename' => 'public/images/albom/img14.jpg',
                'caption' => 'Изображение 14'
            ],
            [
                'filename' => 'public/images/albom/img15.jpg',
                'caption' => 'Изображение 15'
            ]
        ];

        foreach ($initialData as $data) {
            $exists = static::$pdo->prepare("SELECT COUNT(*) FROM " . static::$tablename . " WHERE filename = :filename");
            $exists->execute(['filename' => $data['filename']]);
            $count = $exists->fetchColumn();

            if ($count == 0) { // Если записи не существует, добавляем её
                $photo = new static($data['filename'], $data['caption']);
                $photo->save();
            }
        }
    }
}

?>