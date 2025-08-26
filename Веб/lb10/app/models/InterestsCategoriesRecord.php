<?php

class InterestsCategoriesRecord extends BaseActiveRecord
{
    protected static $tablename = "interestsCategories";
    protected static $dbfields = [
        'id' => 'serial PRIMARY KEY',
        'categoryId' => 'varchar UNIQUE',
        'categoryName' => 'varchar',
        'description' => 'varchar'
    ];

    public $id;
    public $categoryId;
    public $categoryName;
    public $description;

    public function __construct(string|null $categoryId = null, string|null $categoryName = null, string|null $description = null)
    {
        parent::__construct();
        $this->categoryId = $categoryId;
        $this->categoryName = $categoryName;
        $this->description = $description;
    }

    public static function initializeTable()
    {
        static::createTable();

        $initialData = [
            [
                'categoryId' => 'general',
                'categoryName' => 'Общее',
                'description' => 'На этой странице приведены мои интересы, 
                каждый из которых объединяет одна особенность - 
                возможность услышать/увидеть интересную историю.'
            ],
            [
                'categoryId' => 'music',
                'categoryName' => 'Музыка',
                'description' => 'Предпочитаю музыку, в которой есть ощутимый смысл или заложенный сюжет. 
                Сюда можно отнести как песни о военной истории (Radio Tapok - Цусима, Sabaton - The Attack of Dead Men и т.д.),
                так и просто различные истории (Король и Шут - Мёртвый Анархист и т.д.).'
            ],
            [
                'categoryId' => 'videogames',
                'categoryName' => 'Видеоигры',
                'description' => 'Предпочитаю игры с глубоким и проработанным сюжетом.
                Зачастую это одиночные игры, где сюжет основное направление,
                однако также не против сыграть в другие игры, 
                в которых на сюжет акцента меньше.
                Также высоко ценю игры, в которых есть и сюжет (даже если не самый проработанный)
                и возможность проходить этот сюжет в мультиплеере.'
            ],
            [
                'categoryId' => 'films',
                'categoryName' => 'Фильмы',
                'description' => 'Предпочитаю фильмы различных жанров.'
            ],
            [
                'categoryId' => 'series',
                'categoryName' => 'Сериалы',
                'description' => 'Предпочтения в данной категории довольно расплывчаты. 
                В отличии от фильмов, у сериалов достаточно времени, 
                чтобы раскрыть персонажей и дать возможность погрузиться в историю.
                По этой причине конкретный жанр сериалов назвать сложно.
                Также в эту категорию можно отнести сериалы в стиле аниме.'
            ],
            [
                'categoryId' => 'books',
                'categoryName' => 'Книги',
                'description' => 'Предпочитаю книги в жанре фантастика или 
                фентези с проработанным миром.'
            ],
            [
                'categoryId' => 'boardgames',
                'categoryName' => 'Настольные игры',
                'description' => 'Предпочитаю настольные игры, которые обладают большой реиграбельностью. 
                Не люблю игры, где весь процесс сводится просто к сбору колоды (а зачем мы её собирали, где продолжение).'
            ]
        ];

        foreach ($initialData as $data) {
            $exists = static::$pdo->prepare("SELECT COUNT(*) FROM " . static::$tablename . " WHERE categoryId = :categoryId");
            $exists->execute(['categoryId' => $data['categoryId']]);
            $count = $exists->fetchColumn();

            if ($count == 0) {
                $category = new static($data['categoryId'], $data['categoryName'], $data['description']);
                $category->save();
            }
        }
    }
}
