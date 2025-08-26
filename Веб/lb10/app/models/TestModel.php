<?php

require_once __DIR__.'/../core/BaseActiveRecord.php';

class TestModel extends BaseActiveRecord {
    protected static $tablename = "test_results";
    protected static $dbfields = [
        'id' => 'serial PRIMARY KEY',
        'inputName' => 'varchar(255)',
        'studentGroup' => 'varchar(255)',
        'question1' => 'varchar(255)',
        'question2' => 'varchar(255)',
        'question3' => 'varchar',
        'score' => 'integer',
        'isPassed' => 'boolean DEFAULT false',
        'submissionDate' => 'timestamp DEFAULT CURRENT_TIMESTAMP',
        'errors' => 'text',
        'userId' => 'integer'
    ];

    protected static $foreignKeys = [
        'userId' => 'users(id)',
    ];

    public $id;
    public $inputName;
    public $studentGroup;
    public $question1;
    public $question2;
    public $question3;
    public $score;
    public $isPassed;
    public $submissionDate;
    public $errors;
    public $userId;

    private $validationErrors = [];
    private $validator;
    private $resultsVerification;

    public function __construct(array $data = []) {
        parent::__construct();
        static::createTable();
        $this->inputName = $data['inputName'] ?? null;
        $this->studentGroup = $data['studentGroup'] ?? null;
        $this->question1 = $data['question1'] ?? null;
        $this->question2 = $data['question2'] ?? null;
        $this->question3 = $data['question3'] ?? null;
        $this->score = $data['score'] ?? 0;
        $this->isPassed = $data['isPassed'] ?? false;
        $this->errors = $data['errors'] ?? null;
        $this->userId = $data['userId'] ?? null;
        
        $dateTime = new DateTime('now', new DateTimeZone('Europe/Moscow'));
        $this->submissionDate = $data['submissionDate'] ?? $dateTime->format('Y-m-d H:i:s');
        
        $this->validator = new CustomFormValidation();
        $this->resultsVerification = new ResultVerification();
        $this->validate();
    }

    public function validate() {
        $this->validator->setRule('inputName', 'isNotEmpty');
        $this->validator->setRule('inputName', 'isFio');
        $this->validator->setRule('studentGroup', 'comboSelected');
        $this->validator->setRule('question1', 'isNotEmpty');
        $this->validator->setRule('question2', 'comboSelected');
        $this->validator->setRule('question3', 'isNotEmpty');
        $this->validator->setRule('question3', 'isMessage');
        $this->validator->setRule('question3', 'isHaveLess30Words');

        $this->resultsVerification->setRule('question1', 'isQuestion1');
        $this->resultsVerification->setRule('question2', 'isQuestion2');
        $this->resultsVerification->setRule('question3', 'isQuestion3');

        $data = [
            'inputName' => $this->inputName,
            'studentGroup' => $this->studentGroup,
            'question1' => $this->question1,
            'question2' => $this->question2,
            'question3' => $this->question3
        ];

        $this->validator->validate($data);
        $this->resultsVerification->validate($data);
        $this->validationErrors = $this->validator->getErrors();
        $this->errors = json_encode($this->resultsVerification->getErrors(), JSON_UNESCAPED_UNICODE);
        $this->score = $this->resultsVerification->getScore();
        $this->isPassed = $this->resultsVerification->isPassed();
    }

    public function getErrorsHtml() {
        return $this->validator->generateErrorHTMLArrayByField();
    }

    public function getErrors() {
        return $this->validationErrors;
    }

    public function isValid() {
        return $this->validator->isValid();
    }

    public function getScore() {
        return $this->score;
    }

    public function isPassed() {
        return $this->isPassed;
    }
}

?>