<?php

class BaseController {
    protected string $viewPath = __DIR__. '/../Views/';

    protected function view(string $viewName, $data = []): void
    {
        extract($data);
        $content = $viewName . '.php';
        require_once $this->viewPath . 'Layout.php';
    }
}

?>