<?php
session_start();
$_SESSION['user_id'] = 1;
$_SESSION['user_role_name'] = 'Administrador';
$_SESSION['user_name'] = 'TestUser';

define('BASE_URL', 'http://localhost/sigef-ramosT/');

require_once 'controllers/BaseController.php';

class TestController extends BaseController
{
    public function testDashboard()
    {
        $role = $_SESSION['user_role_name'];
        $data = [
            'title' => 'Panel de Administración - ' . $role,
            'role' => $role
        ];

        $view = 'admin/dashboard';
        $layout = 'admin';

        extract($data);
        ob_start();
        $viewFile = 'views/' . $view . '.php';
        if (file_exists($viewFile)) {
            require $viewFile;
        }
        else {
            echo "Vista no encontrada: $viewFile\n";
        }
        $content = ob_get_clean();

        ob_start();
        $layoutFile = 'views/layouts/' . $layout . '.php';
        if (file_exists($layoutFile)) {
            require $layoutFile;
        }
        else {
            echo $content;
        }
        $fullHtml = ob_get_clean();

        file_put_contents('test_out.html', $fullHtml);
        echo "HTML escrito en test_out.html: " . strlen($fullHtml) . " bytes\n";
    }
}

$c = new TestController();
$c->testDashboard();
