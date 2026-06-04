<?php
require_once 'controllers/BaseController.php';
require_once 'models/User.php';

class AuthController extends BaseController {
    
    public function login() {
        // Si ya está logueado, redirigir al admin
        if (isset($_SESSION['user_id'])) {
            $this->redirect('?controller=Admin&action=dashboard');
        }

        $error = '';
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            $userModel = new User();
            if ($userModel->login($email, $password)) {
                $this->redirect('?controller=Admin&action=dashboard');
            } else {
                $error = 'Credenciales inválidas o usuario inactivo.';
            }
        }

        $data = [
            'title' => 'Iniciar Sesión',
            'error' => $error
        ];
        $this->render('admin/login', $data, 'public');
    }

    public function logout() {
        session_unset();
        session_destroy();
        $this->redirect('?controller=Home&action=index');
    }
}
?>
