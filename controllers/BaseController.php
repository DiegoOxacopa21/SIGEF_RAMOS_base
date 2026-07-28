<?php
class BaseController {
    protected function render($view, $data = [], $layout = 'public') {
        // Extraer variables para que estén disponibles en la vista
        extract($data);

        // Capturar contenido de la vista
        ob_start();
        $viewFile = 'views/' . $view . '.php';
        if (file_exists($viewFile)) {
            require $viewFile;
        } else {
            echo "Vista no encontrada: " . $viewFile;
        }
        $content = ob_get_clean();

        // Renderizar dentro del layout
        $layoutFile = 'views/layouts/' . $layout . '.php';
        if (file_exists($layoutFile)) {
            require $layoutFile;
        } else {
            echo $content;
        }
    }

    protected function checkAuth($allowedRoles = []) {
        if (!isset($_SESSION['user_id'])) {
            header("Location: " . BASE_URL . "?controller=Auth&action=login");
            exit;
        }

        if (!empty($allowedRoles) && !in_array($_SESSION['user_role_name'], $allowedRoles) && $_SESSION['user_role_name'] != 'Administrador') {
            echo "Acceso denegado. No tienes permisos para ver esta página.";
            exit;
        }
    }

    protected function redirect($url) {
        header("Location: " . BASE_URL . $url);
        exit;
    }
}
?>
