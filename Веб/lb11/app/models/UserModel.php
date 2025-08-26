<?php

class UserModel extends BaseActiveRecord {
    protected static $tablename = "users";
    protected static $dbfields = [
        'id' => 'serial PRIMARY KEY',
        'fio' => 'varchar(255)',
        'login' => 'varchar(255) UNIQUE',
        'email'=> 'varchar(255) UNIQUE',
        'password_hash' => 'varchar(255)',
        'role_id' => 'smallint',
        'status_id' => 'smallint',
        'created_at' => 'timestamp DEFAULT CURRENT_TIMESTAMP',
        'updated_at'=> 'timestamp DEFAULT NULL',
    ];

    protected static $foreignKeys = [
        'status_id' => 'user_status(id)',
        'role_id' => 'user_role(id)',
    ];

    public $fio;
    public $login;
    public $email;
    public $password_hash = '';
    public $role_id;
    public $status_id;
    public $created_at;
    public $updated_at;

    private $errors = [];

    public function __construct(array $data = [])
    {
        parent::__construct();
        static::createTable();
        $this->fio = $data['fio'] ?? null;
        $this->login = $data['login'] ?? null;
        $this->email = $data['email'] ?? null;
        $this->setPassword($data['password'] ?? '');
        $this->role_id = $data['role_id'] ?? 3;
        $this->status_id = $data['status_id'] ?? 1;

        $dateTime = new DateTime('now', new DateTimeZone('Europe/Moscow'));
        $this->created_at = $dateTime->format('Y-m-d H:i:s');
        $this->updated_at = null;
    }

    public static function initializeTable()
    {   
        UserRole::initializeTable();
        UserStatus::initializeTable();
        static::createTable();

        $initialData = [
            // [
            //     'fio' => 'Боров Максим Геннадьевич',
            //     'login' => 'riki',
            //     'email' => 'maxborov05@gmail.com',
            //     'password' => '123456',
            //     'role_id' => 1,
            //     'status_id' => 1
            // ],
            // [
            //     'fio' => 'Боров Максим Геннадьевич Админ',
            //     'login' => 'riki_admin',
            //     'email' => 'admin228@gmail.com',
            //     'password'=> '123456',
            //     'role_id' => 2,
            //     'status_id' => 1
            // ],
            // [
            //     'fio' => 'Боров Максим Геннадьевич Пользователь',
            //     'login' => 'riki_user',
            //     'email' => 'user228@gmail.com',
            //     'password' => '123456',
            //     'role_id' => 3,
            //     'status_id' => 1
            // ],
            // [
            //     'fio' => 'Боров Максим Геннадьевич Заблокированный',
            //     'login' => 'riki_blocked',
            //     'email' => 'blocked228@gmail.com',
            //     'password' => '123456',
            //     'role_id'=> 2,
            //     'status_id' => 2
            // ]
        ];

        foreach ($initialData as $data) {
            $exists = static::$pdo->prepare("SELECT COUNT(*) FROM " . static::$tablename . " WHERE login = :login");
            $exists->execute(['login' => $data['login']]);
            $count = $exists->fetchColumn();

            if ($count == 0) {
                $user = new static($data);
                $user->save();
            }
        }
    }

    public static function isLoginAvailable(string $login): bool
    {
        $stmt = static::$pdo->prepare("SELECT COUNT(*) FROM " . static::$tablename . " WHERE login = :login");
        $stmt->execute(['login' => $login]);
        return $stmt->fetchColumn() == 0;
    }

    public function setPassword(string $password)
    {
        $this->password_hash = password_hash($password, PASSWORD_BCRYPT);
    }

    public function verifyPassword(string $password): bool
    {
        return password_verify($password, $this->password_hash);
    }
    public static function loginUser(string $login, string $password) {
        $result = [
            'loginStatus' => 'success',
            'description' => 'Пользователь успешно авторизован',
            'login' => $login,
            'role' => null,
            'errors' => [], // Массив для всех ошибок
            'errorsTags' => [], // Массив для тегов ошибок
        ];
        $user = UserModel::findByField('login',$login);
        if (!$user) {
            echo '25';
            $result['loginStatus'] = 'error';
            $result['description'] = 'Пользователь не найден';
            $result['errors']['login'] = 'Пользователь с таким логином не найден';
            $result['errorsTags']['login'] = "<p class='error-message' id='login-error-message-not-found'>" . $result['errors']['login'] . "</p>";
            return $result;
        }
        if($user && $user->verifyPassword($password)) {
            if (session_status() === PHP_SESSION_NONE) {
                // Начинаем сессию только если она еще не начата
                session_start();
            }
            $role = UserRole::find($user->role_id);
            $status = UserStatus::find($user->status_id);
            if(!$role && !$status) {
                throw new \RuntimeException('Ошибка в базе данных: роль или статус не найдены.');
            }
            if ($status->name === 'blocked') {
                $result['loginStatus'] = 'error';
                $result['description'] = 'Пользователь заблокирован';
                $result['errors']['general'] = 'Пользователь заблокирован';
                $result['errorsTags']['general'] = "<p class='error-message' id='status-error-message-blocked'>" . $result['errors']['general'] . "</p>";
                return $result;
            }
            $_SESSION['user'] = [
                'id' => $user->id,
                'fio' => $user->fio,
                'login' => $user->login,
                'email' => $user->email,
                'role' => $role->name,
                'status' => $status->name
            ];
            var_dump($_SESSION['user']);
            $result['description'] = 'Пользователь успешно авторизован';
            $result['role'] = $role->name;
            return $result;
        }
        $result['loginStatus'] = 'error';
        $result['description'] = 'Неверный логин или пароль';
        $result['errors']['general'] = 'Неверный логин или пароль';
        $result['errorsTags']['general'] = "<p class='error-message' id='login-error-message-invalid'>" . $result['errors']['general'] . "</p>";
        return $result;
    }

    public static function getCurrentUser(): ?UserModel {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['user']['id'])) {
            return UserModel::find($_SESSION['user']['id']);
        }
        return null;
    }

    public static function isUserInRole(string $role): bool {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] === $role;
    }

    public static function registerUser(array $data)  {
        $result = [
            'status' => 'success',
            'description' => 'Пользователь успешно зарегистрирован',
            'errors' => [], // Массив для всех ошибок
            'errorsTags' => [], // Массив для тегов ошибок
            'userModel' => null
        ];

        // Проверка логина
        $user = UserModel::findByField('login', $data['login']);
        if ($user) {
            $result['status'] = 'error';
            $result['description'] = 'Ошибка регистрации';
            $result['errors']['login'] = 'Пользователь с таким логином уже существует';
            $result['errorsTags']['login'] = "<p class='error-message' id='login-error-message-unique'>" . $result['errors']['login'] . "</p>";
            return $result;
        }

        // Проверка email
        $user = UserModel::findByField('email', $data['email']);
        if ($user) {
            $result['status'] = 'error';
            $result['description'] = 'Ошибка регистрации';
            $result['errors']['email'] = 'Пользователь с таким email уже существует';
            $result['errorsTags']['email'] = "<p class='error-message' id='email-error-message-unique'>" . $result['errors']['email'] . "</p>";
            return $result; 
        }

        try {
            $user = new UserModel($data);
            $user->save();
            $result['userModel'] = $user;
        } catch (PDOException $e) {
            $result['status'] = 'error';
            $result['description'] = $e->getMessage();
            $result['errors']['general'] = 'Ошибка при создании пользователя';
            $result['errorsTags']['general'] = "<p class='error-message' id='general-error-message'>" . $result['errors']['general'] . "</p>";
            return $result;
        }

        return $result;
    }

    public static function logoutUser(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        unset($_SESSION['user']);
        unset($_SESSION['visitor']);
        $_SESSION['user']['role'] = 'guest';
        $_SESSION['user']['status'] = 'active';
        session_destroy();
    }

    public static function isBlockedUser(): bool {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user']['id'])) {
            return false;
        }

        // Get current user status from database
        $user = static::find($_SESSION['user']['id']);
        if (!$user) {
            return false;
        }

        $status = UserStatus::find($user->status_id);
        if (!$status) {
            throw new \RuntimeException('Ошибка в базе данных: статус не найден.');
        }

        // Update session with current status
        if ($_SESSION['user']['status'] !== $status->name) {
            $_SESSION['user']['status'] = $status->name;
        }

        return $status->name === 'blocked';
    }
}