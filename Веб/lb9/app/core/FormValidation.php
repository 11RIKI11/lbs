<?php

class FormValidation
{
    private $rules = [];
    private $errors = [];

    public function isNotEmpty($data)
    {
        if (empty($data)) {
            return "Поле не должно быть пустым";
        }
        return null;
    }

    public function isInteger($data)
    {
        if (!is_numeric($data) || (int)$data != $data) {
            return "Поле должно быть целым числом.";
        }
        return null;
    }

    public function isLess($data, $value)
    {
        if (!is_numeric($data) || (int)$data != $data || $data > $value) {
            return "Поле должно быть целым числом и не больше, чем value.";
        }
        return null;
    }

    public function isGreater($data, $value)
    {
        if (!is_numeric($data) || (int)$data != $data || $data < $value) {
            return "Поле должно быть целым числом и не меньше, чем value.";
        }
        return null;
    }

    public function isEmail($data)
    {
        $pattern = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/";
        if (!preg_match($pattern, $data)) {
            return "Поле должно быть корректным email адресом.";
        }
        return null;
    }

    public function isFio($data) {
        $pattern = "/^([A-Za-zА-Яа-яЁё]+\s){2}[A-Za-zА-Яа-яЁё]+$/i";
        if(!preg_match($pattern, $data)){
            return "Фамилия имя и отчество введены неверно";
        }
        return null;
    }

    public function isPhoneNumber($data) {
        $pattern = "/^((\++[7])|[8])[0-9]{8,10}$/";
        if(!preg_match($pattern, $data)){
            return "Неверный формат номера телефона";
        }
        return null;
    }

    public function setRule($fieldName, $validatorName, ...$params)
    {
        if (!isset($this->rules[$fieldName])) {
            $this->rules[$fieldName] = [];
        }
        
        foreach ($this->rules[$fieldName] as $rule) {
            if ($rule[0] === $validatorName && $rule[1] === $params) {
                return;
            }
        }
        
        $this->rules[$fieldName][] = [$validatorName, $params];
    }

    public function validate($fieldsArray)
    {
        $this->errors = []; // Очищаем массив ошибок перед валидацией
        foreach ($this->rules as $field => $validators) {
            foreach ($validators as [$validator, $params]) {
                $error = $this->$validator($fieldsArray[$field] ?? '', ...$params);
                if ($error && !in_array($error, $this->errors[$field] ?? [])) {
                    $this->errors[$field][] = $error;
                }
            }
        }
    }

    public function showErrors()
    {
        if (empty($this->errors)) {
            return "<p>Ошибок нет.</p>";
        }
        $output = "<ul>";
        foreach ($this->errors as $field => $field_errors) {
            foreach ($field_errors as $error) {
                $output .= "<li>$field: $error</li>";
            }
        }
        $output .= "</ul>";
        return $output;
    }

    public function getErrors(){
        return $this->errors;
    }

    public function getFieldError($field)
    {
         return $this->errors[$field][0] ?? '';
    }

    public function generateErrorHTMLArrayByField() {
        $errorHTMLArray = [];
        foreach ($this->errors as $field => $errors) {
            foreach ($errors as $index => $error) {
                $errorHTMLArray[$field][$index] = "<p class='error-message' id='{$field}-error-message-{$index}'>{$error}</p>";
            }
        }
        return $errorHTMLArray;
    }

    public function isValid(){
        if(empty($this->errors)){
            return true;
        }
        return false;
    }
}
