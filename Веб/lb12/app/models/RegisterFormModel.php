<?php

class RegisterFormModel {

    private $data = [];
    private $errors = [];
    private $validator;
    public function __construct($data) {
        $this->data = $data;
        $this->validator = new FormValidation();
    }

    public function validate() {
        $this->validator->setRule('fio', 'isNotEmpty');
        $this->validator->setRule('fio', 'isFio');
        $this->validator->setRule('login', 'isNotEmpty');
        $this->validator->setRule('password', 'isNotEmpty');
        $this->validator->setRule('email', 'isNotEmpty');
        $this->validator->setRule('email', 'isEmail');
        $this->validator->validate($this->data);

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