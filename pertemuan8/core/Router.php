<?php

// Kelas router untuk memetakan URL ke Controller dan Method
class Router {
    protected $controller = 'AuthController';
    protected $method = 'index';
    protected $params = [];

    public function __construct() {
        $url = $this->parseUrl();

        // Tentukan controller dari segmen pertama URL
        if (isset($url[0]) && !empty($url[0])) {
            $raw = strtolower(str_replace(['_', '-'], '', $url[0]));
            if ($raw === 'login' || $raw === 'auth') {
                $controllerName = 'AuthController';
            } elseif ($raw === 'accounttype') {
                $controllerName = 'AccountTypeController';
            } else {
                $controllerName = ucfirst($url[0]) . 'Controller';
            }

            if (file_exists(__DIR__ . '/../app/Controllers/' . $controllerName . '.php')) {
                $this->controller = $controllerName;
                unset($url[0]);
            } else {
                $this->render404();
                return;
            }
        }

        require_once __DIR__ . '/../app/Controllers/' . $this->controller . '.php';
        $this->controller = new $this->controller;

        // Tentukan method dari segmen kedua URL
        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            } else {
                $this->render404();
                return;
            }
        }

        // Simpan parameter tambahan dari sisa segmen URL
        $this->params = $url ? array_values($url) : [];

        // Jalankan controller dan method
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    // Memecah query parameter url menjadi array
    private function parseUrl() {
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            return explode('/', $url);
        }
        return [];
    }

    // Menampilkan halaman error 404
    private function render404() {
        http_response_code(404);
        if (file_exists(__DIR__ . '/../app/Views/errors/404.php')) {
            require_once __DIR__ . '/../app/Views/errors/404.php';
        } else {
            echo "404 Halaman Tidak Ditemukan";
        }
        exit;
    }
}
