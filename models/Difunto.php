<?php
require_once 'config/config.php';

class Difunto {
    private $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    public function getAllDifuntos() {
        $query = "SELECT d.*, c.nombre as cliente_nom, c.apellidos as cliente_ape 
                  FROM difuntos d 
                  JOIN clientes c ON d.id_cliente = c.id 
                  ORDER BY d.id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getDifuntosByCliente($id_cliente) {
        $query = "SELECT * FROM difuntos WHERE id_cliente = :id_cliente";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_cliente', $id_cliente);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addDifunto($data) {
        $query = "INSERT INTO difuntos (id_cliente, num_documento, nombre, apellidos, fecha_nacimiento, fecha_defuncion, causa_fallecimiento, lugar_fallecimiento) 
                  VALUES (:id_cliente, :num_doc, :nombre, :apellidos, :fecha_nac, :fecha_def, :causa, :lugar)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':id_cliente' => $data['id_cliente'],
            ':num_doc' => $data['num_documento'],
            ':nombre' => $data['nombre'],
            ':apellidos' => $data['apellidos'],
            ':fecha_nac' => $data['fecha_nacimiento'],
            ':fecha_def' => $data['fecha_defuncion'],
            ':causa' => $data['causa'],
            ':lugar' => $data['lugar']
        ]);
    }
}
?>
