<?php
require_once 'config/config.php';

class Client {
    private $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    public function getAllClientes() {
        $query = "SELECT * FROM clientes ORDER BY fecha_registro DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getClienteById($id) {
        $query = "SELECT * FROM clientes WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addCliente($data) {
        $query = "INSERT INTO clientes (tipo_documento, num_documento, nombre, apellidos, telefono, email, direccion) 
                  VALUES (:tipo_doc, :num_doc, :nombre, :apellidos, :telefono, :email, :direccion)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':tipo_doc' => $data['tipo_documento'],
            ':num_doc' => $data['num_documento'],
            ':nombre' => $data['nombre'],
            ':apellidos' => $data['apellidos'],
            ':telefono' => $data['telefono'],
            ':email' => $data['email'],
            ':direccion' => $data['direccion']
        ]);
    }
}
?>
