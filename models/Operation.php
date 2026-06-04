<?php
require_once 'config/config.php';

class Operation {
    private $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    public function getAllOperaciones() {
        $query = "SELECT o.*, 
                         v.total, cl.nombre as cliente_nom, cl.apellidos as cliente_ape, cl.num_documento,
                         s.nombre as sala_nom, f.placa as flota_placa,
                         d.nombre as difunto_nom, d.apellidos as difunto_ape
                  FROM operaciones o
                  JOIN ventas v ON o.id_venta = v.id
                  JOIN clientes cl ON v.id_cliente = cl.id
                  LEFT JOIN difuntos d ON d.id_cliente = cl.id
                  LEFT JOIN salas_velacion s ON o.id_sala = s.id
                  LEFT JOIN flota_movil f ON o.id_flota = f.id
                  ORDER BY o.fecha_programada ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function updateOperacion($id, $data) {
        $query = "UPDATE operaciones 
                  SET id_sala = :sala, id_flota = :flota, estado = :estado, observaciones = :obs 
                  WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':sala' => empty($data['id_sala']) ? null : $data['id_sala'],
            ':flota' => empty($data['id_flota']) ? null : $data['id_flota'],
            ':estado' => $data['estado'],
            ':obs' => $data['observaciones'],
            ':id' => $id
        ]);
    }
}
?>
