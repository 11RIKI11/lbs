<?php

abstract class BaseActiveRecord
{

    public static $pdo;
    public $id;

    protected static $tablename;
    protected static $dbfields = array();
    protected static $foreignKeys = array();

    public function __construct()
    {
        if (!static::$tablename) {
            return;
        }
        static::setupConnection();
        if (empty(static::$dbfields)) {
            static::getFields();
        }
    }

    public static function getFields()
    {
        $stmt = static::$pdo->query("SELECT column_name, data_type FROM information_schema.columns WHERE table_name = '" . static::$tablename . "'");
        while ($row = $stmt->fetch()) {
            static::$dbfields[$row['column_name']] = $row['data_type'];
        }
    }

    public static function setupConnection()
    {
        if (!isset(static::$pdo)) {
            try {
                static::$pdo = new PDO("pgsql:host=localhost;dbname=lrdb", "postgres", "7676");
            } catch (PDOException $ex) {
                die("Ошибка подключения к БД: {$ex->getMessage()}");
            }
        }
    }

    public static function find($id)
    {
        if($id === null || !is_numeric($id)) {
            return null;
        }
        $sql = "SELECT * FROM " . static::$tablename . " WHERE id = :id";
        $stmt = static::$pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $row = $stmt->fetch() ?: null;

        if (!$row) {
            return null;
        }
        $object = new static();

        foreach ($row as $key => $value) {
            if (property_exists($object, $key)) {
                $object->$key = $value;
            }
        }
        return $object;
    }

    public static function findAll()
    {
        $sql = "SELECT * FROM " . static::$tablename;
        $stmt = static::$pdo->query($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC, static::class);
        $rows = $stmt->fetchAll();
        return $rows;
    }

    public static function findAllAssoc(){
        $sql = "SELECT * FROM " . static::$tablename;
        $stmt = static::$pdo->query($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $rows = $stmt->fetchAll();
        return $rows;
    }

    public static function findAllByFieldAssoc(string $field, $value){
        $sql = "SELECT * FROM " . static::$tablename . " WHERE $field = :value";
        $stmt = static::$pdo->prepare($sql);
        $stmt->bindParam(':value', $value);
        $stmt->execute();

        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $rows = $stmt->fetchAll();
        return $rows;
    }

    public static function findByField($field, $value)
    {
        $sql = "SELECT * FROM " . static::$tablename . " WHERE $field = :value";
        $stmt = static::$pdo->prepare($sql);
        $stmt->bindParam(':value', $value);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $row =  $stmt->fetch() ?: null;
        if (!$row) {
            return null;
        }
        $object = new static();
        foreach ($row as $key => $value) {
            if (property_exists($object, $key)) {
                $object->$key = $value;
            }
        }
        return $object;
    }

    public function save()
    {
        $exists = isset($this->id) && static::exists($this->id);
        $columns = [];
        $values = [];
        $types = [];
        
        foreach (static::$dbfields as $field => $type) {
            if ($field === "id") {
                continue;
            }
            $columns[] = $field;
            $value = property_exists($this, $field) ? $this->$field : null;
            
            // Определяем тип параметра для PDO
            if (strpos($type, 'boolean') !== false) {
                $types[":$field"] = PDO::PARAM_BOOL;
                $value = (bool)$value;
            } elseif (strpos($type, 'integer') !== false) {
                $types[":$field"] = PDO::PARAM_INT;
                $value = (int)$value;
            } else {
                $types[":$field"] = PDO::PARAM_STR;
            }
            
            $values[":$field"] = $value;
        }
        
        if ($exists) {
            $sql = "UPDATE " . static::$tablename . " SET " . implode(', ', array_map(fn($field) => "$field = :$field", $columns)) . " WHERE id = :id";
            $values[":id"] = $this->id;
            $types[":id"] = PDO::PARAM_INT;
        } else {
            $sql = "INSERT INTO " . static::$tablename . " (" . implode(', ', $columns) . ") VALUES (" . implode(', ', array_map(fn($col) => ":$col", $columns)) . ")";
        }
        
        $stmt = static::$pdo->prepare($sql);
        
        // Привязываем значения с учетом их типов
        foreach ($values as $key => $value) {
            $stmt->bindValue($key, $value, $types[$key]);
        }
        
        $stmt->execute();

        if (!$exists) {
            $this->id = static::$pdo->lastInsertId();
        }
    }

    public function delete()
    {
        if (!$this->id) {
            throw new Exception("ID объекта не задан.");
        }
        $sql = "DELETE FROM " . static::$tablename . " WHERE id=:id";
        $stmt = static::$pdo->prepare($sql);
        $stmt->execute([':id' => $this->id]);
        if ($stmt) {
            return true;
        } else {
            print_r(static::$pdo->errorInfo());
            return false;
        }
    }

    public static function exists($id)
    {
        $sql = "SELECT COUNT(*) FROM " . static::$tablename . " WHERE id = :id";
        $stmt = static::$pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        $count = $stmt->fetchColumn();

        return $count > 0;
    }

    public static function createTable() {
        if (!isset(static::$tablename)) {
            throw new Exception("Название таблицы не задано.");
        }
    
        if (empty(static::$dbfields)) {
            throw new Exception("Поля таблицы не определены.");
        }
    
        $columns = [];
        foreach (static::$dbfields as $field => $type) {
            $columns[] = "$field $type";
        }

        if (!empty(static::$foreignKeys)) {
            foreach (static::$foreignKeys as $key => $reference) {
                $columns[] = "CONSTRAINT fk_$key FOREIGN KEY ($key) REFERENCES $reference";
            }
        }
    
        $sql = "CREATE TABLE IF NOT EXISTS " . static::$tablename . " (" . implode(", ", $columns) . ");";
    
        try {
            static::$pdo->exec($sql);
        } catch (PDOException $ex) {
            die("Ошибка создания таблицы: {$ex->getMessage()}");
        }
    }
}

?>
