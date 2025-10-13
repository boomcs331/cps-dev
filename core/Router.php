<?php
/**
 * Router Class
 * จัดการ routing ของแอปพลิเคชัน
 */
class Router
{
    private $routes = [];
    private $params = [];

    /**
     * เพิ่ม route ใหม่
     */
    public function addRoute($route, $controller, $action)
    {
        // แปลง route เป็น regex pattern
        $route = preg_replace('/\//', '\\/', $route);
        $route = preg_replace('/\{([a-z]+)\}/', '(?P<\1>[a-z0-9-]+)', $route);
        $route = '/^' . $route . '$/i';

        $this->routes[$route] = [
            'controller' => $controller,
            'action' => $action
        ];
    }

    /**
     * จับคู่ URL กับ routes
     */
    public function match($url)
    {
        foreach ($this->routes as $route => $params) {
            if (preg_match($route, $url, $matches)) {
                foreach ($matches as $key => $match) {
                    if (is_string($key)) {
                        $params[$key] = $match;
                    }
                }
                $this->params = $params;
                return true;
            }
        }
        return false;
    }

    /**
     * ดึง parameters
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * เริ่มต้นการ dispatch
     */
    public function dispatch()
    {
        $url = $_SERVER['REQUEST_URI'];

        // ตัด query string ออก
        if (($qpos = strpos($url, '?')) !== false) {
            $url = substr($url, 0, $qpos);
        }

        // ตัด base path (เช่น /cps) ออก
        $base = dirname($_SERVER['SCRIPT_NAME']);
        if ($base !== '/' && strpos($url, $base) === 0) {
            $url = substr($url, strlen($base));
        }

        $url = trim($url, '/');

        // ถ้า URL เป็นค่าว่างให้เป็นหน้าแรก
        if ($url === '') {
            $url = '/';
        } else {
            // เพิ่ม / นำหน้าเพื่อให้ตรงกับ route pattern
            $url = '/' . $url;
        }



        if ($this->match($url)) {
            $controller = $this->params['controller'];
            $action = $this->params['action'];

            $controller_file = CONTROLLERS_PATH . $controller . '.php';
            if (file_exists($controller_file)) {
                require_once $controller_file;
                if (class_exists($controller)) {
                    $controller_instance = new $controller();
                    if (method_exists($controller_instance, $action)) {
                        $params = $this->params;
                        unset($params['controller'], $params['action']);
                        if (!empty($params)) {
                            call_user_func_array(array($controller_instance, $action), array_values($params));
                        } else {
                            $controller_instance->$action();
                        }
                    } else {
                        $this->error404();
                    }
                } else {
                    $this->error404();
                }
            } else {
                $this->error404();
            }
        } else {
            $this->error404();
        }
    }

    /**
     * แสดงหน้า 404
     */
    private function error404()
    {
        http_response_code(404);
        echo '<h1>404 - Page Not Found</h1>';
        echo '<p>The page you are looking for does not exist.</p>';
        echo '<a href="' . BASE_URL . '">Go Home</a>';
    }
}
?> 