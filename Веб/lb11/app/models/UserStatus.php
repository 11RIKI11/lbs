<?php

class UserStatus extends BaseActiveRecord {
    protected static $tablename = "user_status";
    protected static $dbfields = [
        'id' => 'smallserial PRIMARY KEY',
        'name' => 'varchar(255) UNIQUE'
    ];

    public $name;

    public function __construct(string|null $name = null)
    {
        parent::__construct();
        $this->name = $name;
    }

    public static function initializeTable()
    {
        static::createTable();

        $initialData = [
            ['name' => 'active'],
            ['name' => 'blocked']
        ];

        foreach ($initialData as $data) {
            $exists = static::$pdo->prepare("SELECT COUNT(*) FROM " . static::$tablename . " WHERE name = :name");
            $exists->execute(['name' => $data['name']]);
            $count = $exists->fetchColumn();

            if ($count == 0) {
                $status = new static($data['name']);
                $status->save();
            }
        }
    }
}