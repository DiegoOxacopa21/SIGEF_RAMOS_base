<?php
require_once 'config/config.php';

// Front Controller simple
$controllerName = isset($_GET['controller']) ? $_GET['controller'] : 'Home';
$actionName = isset($_GET['action']) ? $_GET['action'] : 'index';

// Formato de nombre de controlador, ej: HomeController
$controllerClass = ucfirst($controllerName) . 'Controller';
$controllerFile = 'controllers/' . $controllerClass . '.php';

if (file_exists($controllerFile)) {
    require_once $controllerFile;
    if (class_exists($controllerClass)) {
        $controller = new $controllerClass();
        if (method_exists($controller, $actionName)) {
            $controller->$actionName();
        }
        else {
            // Acción no encontrada
            echo "Error 404: Acción '$actionName' no encontrada en '$controllerClass'";
        }
    }
    else {
        // Clase no encontrada
        echo "Error 404: Clase controlador '$controllerClass' no encontrada";
    }
}
else {
    // Archivo no 
    

    echo "Error 404: Archivo controlador '$controllerFile' no encontrado";
}
?>
