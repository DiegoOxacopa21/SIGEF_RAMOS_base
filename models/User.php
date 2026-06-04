<?php
require_once 'config/config.php';

class User {
    private $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    public function login($email, $password) {
        $query = "SELECT u.*, r.nombre as rol_nombre FROM usuarios u 
                  JOIN roles r ON u.id_rol = r.id 
                  WHERE u.email = :email AND u.estado = 'activo'";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($password, $user['password'])) {
                // Configurar variables de sesión
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['nombre'];
                $_SESSION['user_role_id'] = $user['id_rol'];
                $_SESSION['user_role_name'] = $user['rol_nombre'];
                return true;
            }
        }
        return false;
    }

    public function getAllUsers() {
        $query = "SELECT u.*, r.nombre as rol_nombre, s.nombre as sede_nombre 
                  FROM usuarios u 
                  JOIN roles r ON u.id_rol = r.id 
                  JOIN sedes s ON u.id_sede = s.id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function crearUsuario($data) {
        $stmt = $this->conn->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt->execute([$data['email']]);
        if ($stmt->fetch()) {
            throw new Exception("El email ya está registrado.");
        }
        
        $hash = password_hash($data['password'], PASSWORD_DEFAULT);
        $query = "INSERT INTO usuarios (id_rol, id_sede, nombre, email, password, estado) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            $data['id_rol'], 
            $data['id_sede'], 
            $data['nombre'], 
            $data['email'], 
            $hash, 
            $data['estado'] ?? 'activo'
        ]);
    }

    public function editarUsuario($data) {
        $stmt = $this->conn->prepare("SELECT id FROM usuarios WHERE email = ? AND id != ?");
        $stmt->execute([$data['email'], $data['id_usuario']]);
        if ($stmt->fetch()) {
            throw new Exception("El email ya está registrado por otro usuario.");
        }

        $query = "UPDATE usuarios SET id_rol = ?, id_sede = ?, nombre = ?, email = ?, estado = ?";
        $params = [$data['id_rol'], $data['id_sede'], $data['nombre'], $data['email'], $data['estado']];
        
        if (!empty($data['password'])) {
            $query .= ", password = ?";
            $params[] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        
        $query .= " WHERE id = ?";
        $params[] = $data['id_usuario'];
        
        $stmt = $this->conn->prepare($query);
        return $stmt->execute($params);
    }
    
    public function toggleEstado($id, $estado) {
        // Prevent deleting the main admin directly to ensure integrity
        if ($id == 1 && $estado == 'inactivo') {
            throw new Exception("No se puede desactivar al Administrador principal.");
        }
        $stmt = $this->conn->prepare("UPDATE usuarios SET estado = ? WHERE id = ?");
        return $stmt->execute([$estado, $id]);
    }
}
?>
