<?php

/**
 * Routing component
 */
class Router
{

    private $registry;
    private $path;

    public function __construct($registry, $path)
    {
        $this->registry = $registry;
        $this->path = $path;
    }

    public function runController()
    {
        $route = filter_input(INPUT_GET, 'route', FILTER_SANITIZE_URL);

        if (empty($route)) {
            $route = 'index';
        }

        $parts = explode('/', $route);

        // Get controller
        $controller = array_shift($parts) . 'Controller';

        $file = $this->path . DIRSEP . $controller . '.php';
        if (!is_readable($file)) {
            die('Controller not exists');
        }
        include($file);

        $controller = new $controller($this->registry);

        // Get action
        $action = array_shift($parts);

        if (empty($action)) {
            $action = 'index';
        }

        if (!is_callable([$controller, $action])) {
            die('Action not found');
        }

        // Execute with arguments
        $controller->$action($parts);
    }

}
