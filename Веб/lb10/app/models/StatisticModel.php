<?php

class StatisticModel extends BaseActiveRecord
{
    protected static $tablename = "guest";
    protected static $dbfields = [
        'id' => 'serial PRIMARY KEY',
        'ip_address' => 'varchar(255)',
        'user_agent' => 'varchar(255)',
        'host' => 'varchar(255)',
        'page' => 'varchar(255)',
        'user_id' => 'int',
        'page_path' => 'varchar(255)',
        'page_name' => 'varchar(255)',
        'visit_date' => 'timestamp DEFAULT CURRENT_TIMESTAMP'
    ];
    protected static $foreignKeys = [
        'user_id' => 'users(id)',
    ];
    public function __construct()
    {
        parent::__construct();
    }
}