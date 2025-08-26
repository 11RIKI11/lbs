<?php

class LoginFormModel {
    private $data = [];
    private $errors = [];
    private $validator;
    public function __construct($data) {
        $this->data = $data;
        $this->validator = new FormValidation();
        var_dump($data);
    }

    public function validate() {
        $this->validator->setRule('login', 'isNotEmpty');
        $this->validator->setRule('password', 'isNotEmpty');
        $this->validator->validate($this->data);

        $this->errors = $this->validator->getErrors();
        var_dump($this->errors);
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