<?php
require_once 'config/config.php';

class Payment {
    private $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    public function getMetodosPago() {
        $query = "SELECT * FROM metodos_pago WHERE estado = 'activo'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function registrarPago($data) {
        try {
            $this->conn->beginTransaction();

            // Insertar pago
            $query = "INSERT INTO pagos (id_venta, id_metodo_pago, id_cajero, monto, referencia) 
                      VALUES (:venta, :metodo, :cajero, :monto, :referencia)";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':venta' => $data['id_venta'],
                ':metodo' => $data['id_metodo_pago'],
                ':cajero' => $data['id_cajero'],
                ':monto' => $data['monto'],
                ':referencia' => $data['referencia'] ?? ''
            ]);

            // Actualizar estado de Venta a pagada
            $stmtV = $this->conn->prepare("UPDATE ventas SET estado = 'pagada' WHERE id = ?");
            $stmtV->execute([$data['id_venta']]);

            // Generar Comprobante automáticamente
            $tipoComp = $data['tipo_comprobante'] ?? 'boleta';
            $serie = $tipoComp == 'factura' ? 'F001' : 'B001';
            // Simular correlativo simple
            $stmtLastComp = $this->conn->prepare("SELECT MAX(id) as max_id FROM comprobantes");
            $stmtLastComp->execute();
            $res = $stmtLastComp->fetch(PDO::FETCH_ASSOC);
            $nextNum = str_pad(($res['max_id'] ?? 0) + 1, 6, '0', STR_PAD_LEFT);
            
            $stmtC = $this->conn->prepare("INSERT INTO comprobantes (id_venta, tipo, serie, numero, total) VALUES (?, ?, ?, ?, ?)");
            $stmtC->execute([$data['id_venta'], $tipoComp, $serie, $nextNum, $data['monto']]);

            $this->conn->commit();
            return true;
        } catch(Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    public function getAllComprobantes() {
        $query = "SELECT c.*, v.total as venta_total, cl.nombre, cl.apellidos 
                  FROM comprobantes c 
                  JOIN ventas v ON c.id_venta = v.id
                  JOIN clientes cl ON v.id_cliente = cl.id
                  ORDER BY c.fecha_emision DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
