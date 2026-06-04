<?php
require_once 'config/config.php';

class Quotation {
    private $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    public function saveSimulation($items, $subtotal, $igv, $total) {
        // En una simulación anónima, podríamos guardarla o simplemente devolver un objeto/array
        // As request says "simular una proforma", it typically happens on the client-side/session
        // But if we want to save into DB without client we do it here. 
        // For now, this might just be handled in session or returned for PDF.
    }
    
    public function getAllCotizaciones() {
        $query = "SELECT c.*, cl.nombre, cl.apellidos FROM cotizaciones c LEFT JOIN clientes cl ON c.id_cliente = cl.id ORDER BY fecha DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function crearCotizacion($data) {
        try {
            $this->conn->beginTransaction();
            
            if(empty($data['id_cliente']) || empty($data['items'])) {
                throw new Exception("Faltan datos requeridos.");
            }

            $subtotal = floatval($data['subtotal'] ?? 0);
            $igv = floatval($data['igv'] ?? 0);
            $total = floatval($data['total'] ?? 0);
            
            $query = "INSERT INTO cotizaciones (id_cliente, subtotal, igv, total, estado) VALUES (?, ?, ?, ?, 'pendiente')";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$data['id_cliente'], $subtotal, $igv, $total]);
            $id_cot = $this->conn->lastInsertId();

            $stmtDet = $this->conn->prepare("INSERT INTO detalle_cotizacion (id_cotizacion, id_producto, id_servicio, descripcion, cantidad, precio_unitario, subtotal) VALUES (?, ?, ?, ?, ?, ?, ?)");
            
            foreach($data['items'] as $item) {
                $id_prod = !empty($item['id_producto']) ? $item['id_producto'] : null;
                $id_serv = !empty($item['id_servicio']) ? $item['id_servicio'] : null;
                $stmtDet->execute([
                    $id_cot,
                    $id_prod,
                    $id_serv,
                    $item['descripcion'],
                    $item['cantidad'],
                    $item['precio'],
                    $item['subtotal']
                ]);
            }

            $this->conn->commit();
            return true;
        } catch(Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    public function eliminarCotizacion($id) {
        try {
            // Solo se pueden eliminar cotizaciones pendientes
            $stmtCheck = $this->conn->prepare("SELECT estado FROM cotizaciones WHERE id = ?");
            $stmtCheck->execute([$id]);
            $coti = $stmtCheck->fetch(PDO::FETCH_ASSOC);
            if(!$coti || $coti['estado'] != 'pendiente') {
                return false;
            }

            $this->conn->beginTransaction();
            $stmtDet = $this->conn->prepare("DELETE FROM detalle_cotizacion WHERE id_cotizacion = ?");
            $stmtDet->execute([$id]);
            
            $stmt = $this->conn->prepare("DELETE FROM cotizaciones WHERE id = ?");
            $stmt->execute([$id]);
            $this->conn->commit();
            return true;
        } catch(Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }
}
?>
