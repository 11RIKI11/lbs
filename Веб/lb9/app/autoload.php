<?php

function autoloader($class) {
    $baseDir = __DIR__; 
    $file = searchClassFile($baseDir, $class);
    
    if ($file) {
        require_once $file; 
    } else {
        echo "Файл для класса {$class} не найден!";
    }
}

function searchClassFile($directory, $class) {  
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));

    // Проходим по всем файлам
    foreach ($iterator as $file) {
        if ($file->isFile() && $file->getFilename() === $class . '.php') {
            return $file->getRealPath(); // Возвращаем полный путь к файлу, если нашли
        }
    }

    return null; // Если файл не найден, возвращаем null
}

// Регистрируем автозагрузчик
spl_autoload_register('autoloader');

?>