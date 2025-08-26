<?php

require_once __DIR__.'/../core/BaseActiveRecord.php';

class ContactModel extends BaseActiveRecord {

    protected static $tablename = "contacts";
    protected static $dbfields = [
        'id' => 'serial PRIMARY KEY',
        'inputName' => 'varchar(255)',
        'gender' => 'varchar(7)',
        'birthdate' => 'varchar(11)',
        'phoneNumber' => 'varchar(15)',
        'email' => 'varchar',
        'message' => 'varchar',
        'submission_date' => 'timestamp DEFAULT CURRENT_TIMESTAMP'
    ];

    public $id;
    public $inputName;
    public $gender;
    public $birthdate;
    public $phoneNumber;
    public $email;
    public $message;
    public $submission_date;

    private $errors = [];
    private $validator;

    public function __construct(array $data = []){
        parent::__construct();
        static::createTable();
        $this->inputName = $data['inputName'] ?? null;
        $this->gender = $data['gender'] ?? null;
        $this->birthdate = $data['birthdate'] ?? null;
        $this->phoneNumber = $data['phoneNumber'] ?? null;
        $this->email = $data['email'] ?? null;
        $this->message = $data['message'] ?? null;
        
        $dateTime = new DateTime('now', new DateTimeZone('Europe/Moscow'));
        $this->submission_date = $data['submission_date'] ?? $dateTime->format('Y-m-d H:i:s');
        
        $this->validator = new FormValidation();
    }

    public function validate(){
        $this->validator->setRule('inputName', 'isNotEmpty');
        $this->validator->setRule('inputName', 'isFio');
        $this->validator->setRule('birthdate', 'isNotEmpty');
        $this->validator->setRule('phoneNumber', 'isNotEmpty');
        $this->validator->setRule('phoneNumber', 'isPhoneNumber');
        $this->validator->setRule('email', 'isNotEmpty');
        $this->validator->setRule('email', 'isEmail');
        $this->validator->setRule('message', 'isNotEmpty');
        $this->validator->setRule('gender', 'isNotEmpty');
        $data = [];
        $data['inputName'] = $this->inputName;
        $data['gender'] = $this->gender;
        $data['birthdate'] = $this->birthdate;
        $data['phoneNumber'] = $this->phoneNumber;
        $data['email'] = $this->email;
        $data['message'] = $this->message;
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