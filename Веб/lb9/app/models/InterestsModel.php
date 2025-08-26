<?php

require_once __DIR__ . '/../core/BaseActiveRecord.php';

class InterestsModel extends BaseActiveRecord
{
    protected static $tablename = "interests";
    protected static $dbfields = [
        'id' => 'serial PRIMARY KEY',
        'categoryId' => 'varchar',
        'text' => 'varchar',
        'imgPath' => 'varchar'
    ];
    protected static $foreignKeys = [
        'categoryId' => 'interestsCategories(categoryId)'
    ];

    public $id;
    public $categoryId;
    public $text;
    public $imgPath;

    public function __construct(string|null $categoryId, string|null $text, string|null $imgPath)
    {
        parent::__construct();
        $this->categoryId = $categoryId;
        $this->text = $text;
        $this->imgPath = $imgPath;
    }

    public static function initializeTable()
    {
        static::createTable();

        $initialData = [
            [
                'categoryId' => 'music',
                'text' => 'Radio Tapok',
                'imgPath' => 'public/images/interests/Radio Tapok.jpg'
            ],
            [
                'categoryId' => 'music',
                'text' => 'Sabaton',
                'imgPath' => 'public/images/interests/Sabaton.jpg'
            ],
            [
                'categoryId' => 'videogames',
                'text' => 'Ведьмак 3: Дикая охота',
                'imgPath' => 'public/images/interests/Witcher3.jpg'
            ],
            [
                'categoryId' => 'films',
                'text' => 'Форсаж 8',
                'imgPath' => 'public/images/interests/F8.jpg'
            ],
            [
                'categoryId' => 'series',
                'text' => 'Доктор стоун',
                'imgPath' => 'public/images/interests/DrStone.jpg'
            ],
            [
                'categoryId' => 'books',
                'text' => 'Ведьмак: Крещение огнём',
                'imgPath' => 'public/images/interests/WitcherBook1.jpg'
            ],
            [
                'categoryId' => 'boardgames',
                'text' => 'Oathsworn: Верные клятве',
                'imgPath' => 'public/images/interests/Oathsworn.png'
            ]
        ];

        foreach ($initialData as $data) {
            $exists = static::$pdo->prepare("SELECT COUNT(*) FROM " . static::$tablename . " WHERE imgPath = :imgPath");
            $exists->execute(['imgPath' => $data['imgPath']]);
            $count = $exists->fetchColumn();
            if ($count == 0) {
                $category = new static($data['categoryId'], $data['text'], $data['imgPath']);
                $category->save();
            }
        }
    }
}
