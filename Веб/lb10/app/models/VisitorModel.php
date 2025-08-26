<?php

class VisitorModel extends BaseActiveRecord
{
    protected static $tablename = "visitors";
    protected static $dbfields = [
        'id' => 'serial PRIMARY KEY',
        'visit_datetime' => 'timestamp NOT NULL',
        'page' => 'varchar(255) NOT NULL',
        'ip_address' => 'varchar(64) NOT NULL',
        'host' => 'varchar(255) NOT NULL',
        'browser' => 'varchar(255) NOT NULL'
    ];

    public $visit_datetime;
    public $page;
    public $ip_address;
    public $host;
    public $browser;

    public function __construct($data = []) {
        parent::__construct();
        $this->visit_datetime = $data['visit_datetime'] ?? (new DateTime('now', new DateTimeZone('Europe/Moscow')))->format('Y-m-d H:i:s');
        $this->page = $data['page'] ?? '';
        $this->ip_address = $data['ip_address'] ?? '';
        $this->host =  $data['host'] ?? '';
        $this->browser = $data['browser'] ?? '';
    }

    public static function saveVisitor(){
        $ip = $_SERVER['REMOTE_ADDR'] ?? '';
        $host = gethostbyaddr($ip);
        $browser = $_SERVER['HTTP_USER_AGENT'] ?? '';
        $page = $_SERVER['REQUEST_URI'] ?? '';

        $visitor = new static([
            'visit_datetime' => (new DateTime('now', new DateTimeZone('Europe/Moscow')))->format('Y-m-d H:i:s'),
            'page' => $page,
            'ip_address' => $ip,
            'host' => $host,
            'browser' => $browser
        ]);

        return $visitor->save();
    }
    public static function initializeTable()
    {
        static::createTable();
    }   
}