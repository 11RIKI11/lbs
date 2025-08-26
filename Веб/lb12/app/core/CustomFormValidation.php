<?php

class CustomFormValidation extends FormValidation {

    public function isMessage($data) {
        $pattern = '/^[\p{L}\p{N},\-;.\'"\s]*$/u';
        $data = trim($data);
        if(!preg_match($pattern, $data)){
            return "Сообщение должно содержать только буквы английского и русского алфавита, цифры, пробелы и символы , - ; . ' \"";
        }
        return null;
    }

    public function isHaveLess30Words($data){
        $pattern = '/^(?:[\p{L}\p{N}]+,?\s*){0,30}$/u';
        $data = trim($data);
        if(!preg_match($pattern, $data)){
            return "Сообщение должно содержать не более 30 слов";
        }
        return null;
    }

    public function comboSelected($data){
        if(empty($data)){
            return 'Выберите вариант из списка';
        }
        return null;
    }
}

?>