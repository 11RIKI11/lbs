<?php

class UserRole extends BaseActiveRecord {
    protected static $tablename = "user_role";
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
            ['name' => 'superadmin'],
            ['name' => 'admin'],
            ['name' => 'user']
        ];

        foreach ($initialData as $data) {
            $exists = static::$pdo->prepare("SELECT COUNT(*) FROM " . static::$tablename . " WHERE name = :name");
            $exists->execute(['name' => $data['name']]);
            $count = $exists->fetchColumn();

            if ($count == 0) {
                $role = new static($data['name']);
                $role->save();
            }
        }
    }
}