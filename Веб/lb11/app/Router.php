<?php

#[Attribute(Attribute::TARGET_METHOD)]
class Route {
    public function __construct(
        public string $path,
        public string $method = 'GET'
    ) {}
}

class Router {
    private array $routes = [];

    public function __construct() {
        $this->loadControllers();
    }

    private function loadControllers() {
        foreach (glob(__DIR__ . "/controllers/*Controller.php") as $file) {
            require_once $file;
            $className = basename($file, ".php");
            if (class_exists($className)) {
                $this->registerRoutes(new $className());
            }
        }
    }

    private function registerRoutes($controller) {
        $reflection = new ReflectionClass($controller);
        foreach ($reflection->getMethods() as $method) {
            $attributes = $method->getAttributes(Route::class);
            foreach ($attributes as $attribute) {
                $route = $attribute->newInstance();
                $routePath = trim($route->path, '/');
                $httpMethod = strtoupper($route->method);
                $this->routes[$httpMethod][$routePath] = [$controller, $method->getName()];
            }
        }
    }

    public function dispatch() {
        $uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        if (isset($this->routes[$requestMethod][$uri])) {
            [$controller, $method] = $this->routes[$requestMethod][$uri];
            call_user_func([$controller, $method]);
        } else {
            http_response_code(404);
            $controller = new BaseController();
            $controller->view('errors/NotFound', [
            'title' => 'Страница не найдена'
        ]);
        }
    }
}

?>