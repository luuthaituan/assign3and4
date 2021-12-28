<?php
namespace libs;
use app\controllers\Controller;

class Router {
    private $routeTable = [];
    private $currentRoute = null;

    public function register($method, $pattern, $dest = []) {
        if (!is_array($dest)){
            $dest = [$dest];
        }
        $method = strtolower($method);
        $this->routeTable[$method][$pattern] = [
            'controller' => $dest[0],
            'action' => $dest[1] ?? 'index'

        ];
    }

    public function matching() {
        $url = parse_url($_SERVER['REQUEST_URI']);
        $method = strtolower($_SERVER['REQUEST_METHOD']);
        $path = $url['path'];

        foreach ($this->routeTable[$method] as $pattern => $controller) {
            if($pattern === $path){
                $this->currentRoute = $this->routeTable[$method][$pattern];
                break;
            }

        }

    }
    public function dispatch() {
        if($this->getRoute()) {
            $controller = $this ->currentRoute['controller'];
            if(class_exists($controller)){
                $controller = new $controller;
                $action = $this->currentRoute['action'];
                if(is_callable([$controller, $action])){
                    call_user_func_array([$controller, $action], ['currentRoute'=>$this->currentRoute]);
                }
            }
        }
    }

    public function getRoute(){
        $this->matching();
        return $this->currentRoute;
    }

    public function getRouteTable() {
        return $this->routeTable;
    }
}
