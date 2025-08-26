<?php

class GuestBookModel {
    private const MESSAGES_FILE = __DIR__.'/../../data/messages.inc';
    private const MESSAGES_DIR = __DIR__.'/../../data';

    private $data;
    private $validator;
    private $errors = [];

    public function __construct($data) {
        $this->data = $data;
        $this->validator = new FormValidation();
    }

    public function validate(){
        $this->validator->setRule('inputName', 'isNotEmpty');
        $this->validator->setRule('inputName', 'isFio');
        $this->validator->setRule('email', 'isNotEmpty');
        $this->validator->setRule('email', 'isEmail');
        $this->validator->setRule('messageInput', 'isNotEmpty');
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

    public function saveMessage(): void {
        if (!file_exists(self::MESSAGES_DIR)) {
            mkdir(self::MESSAGES_DIR, 0777, true);
        }

        $date = date('Y-m-d H:i:s');
        $messageData = [
            'date' => $date,
            'fio' => $this->data['inputName'],
            'email' => $this->data['email'],
            'message' => $this->data['messageInput']
        ];

        $messages = self::getMessages();
        array_unshift($messages, $messageData);

        $file = fopen(self::MESSAGES_FILE, 'w');
        if ($file === false) {
            throw new Exception('Не удалось открыть файл для записи');
        }

        try {
            foreach ($messages as $msg) {
                $line = implode(';', [
                    $msg['date'],
                    $msg['fio'],
                    $msg['email'],
                    $msg['message']
                ]) . "\n";
                
                if (fwrite($file, $line) === false) {
                    throw new Exception('Ошибка при записи в файл');
                }
            }
        } finally {
            fclose($file);
        }
    }

    public static function getMessages(): array {
        if (!file_exists(self::MESSAGES_FILE)) {
            return [];
        }

        $messages = [];
        
        $file = fopen(self::MESSAGES_FILE, 'r');
        if ($file === false) {
            throw new Exception('Не удалось открыть файл для чтения');
        }

        try {
            while (($line = fgets($file)) !== false) {
                $line = trim($line);
                if (empty($line)) continue;
                
                $parts = explode(';', $line, 4);
                if (count($parts) === 4) {
                    $messages[] = [
                        'date' => $parts[0],
                        'fio' => $parts[1],
                        'email' => $parts[2],
                        'message' => $parts[3]
                    ];
                }
            }
        } finally {
            fclose($file);
        }

        return $messages;
    }

    public static function uploadMessagesFile(string $tempPath): array {
        $errors = [];
        
        $isValid = true;
        $fileHandle = fopen($tempPath, 'r');
        
        if ($fileHandle) {
            try {
                while (($line = fgets($fileHandle)) !== false) {
                    $line = trim($line);
                    if (empty($line)) continue;
                    
                    $parts = explode(';', $line);
                    if (count($parts) !== 4) {
                        $isValid = false;
                        break;
                    }
                }
            } finally {
                fclose($fileHandle);
            }

            if ($isValid) {
                if (!file_exists(self::MESSAGES_DIR)) {
                    mkdir(self::MESSAGES_DIR, 0777, true);
                }

                $sourceHandle = fopen($tempPath, 'r');
                $targetHandle = fopen(self::MESSAGES_FILE, 'w');
                
                if ($sourceHandle && $targetHandle) {
                    try {
                        while (!feof($sourceHandle)) {
                            $buffer = fread($sourceHandle, 8192);
                            if ($buffer === false) {
                                throw new Exception('Ошибка чтения файла');
                            }
                            if (fwrite($targetHandle, $buffer) === false) {
                                throw new Exception('Ошибка записи файла');
                            }
                        }
                    } catch (Exception $e) {
                        $errors[] = $e->getMessage();
                    } finally {
                        fclose($sourceHandle);
                        fclose($targetHandle);
                    }
                } else {
                    $errors[] = 'Ошибка при открытии файла';
                }
            } else {
                $errors[] = 'Неверный формат файла. Файл должен содержать записи в формате: дата;фио;email;сообщение';
            }
        } else {
            $errors[] = 'Ошибка при открытии файла';
        }

        return $errors;
    }
} 