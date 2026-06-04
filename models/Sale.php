<?php
require_once 'config/config.php';

class Sale {
    private $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    public function getAllVentas() {
        $query = "SELECT v.*, c.nombre as cliente_nom, c.apellidos as cliente_ape, u.nombre as vendedor_nom 
                  FROM ventas v 
                  JOIN clientes c ON v.id_cliente = c.id
                  JOIN usuarios u ON v.id_vendedor = u.id 
                  ORDER BY v.fecha DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getVentasFiltradas($fecha_inicio, $fecha_fin, $estado, $id_sede) {
        $query = "SELECT v.*, c.nombre as cliente_nom, c.apellidos as cliente_ape, u.nombre as vendedor_nom, s.nombre as sede_nom 
                  FROM ventas v 
                  JOIN clientes c ON v.id_cliente = c.id
                  JOIN usuarios u ON v.id_vendedor = u.id 
                  JOIN sedes s ON u.id_sede = s.id
                  WHERE 1=1";
        
        $params = [];
        
        if (!empty($fecha_inicio)) {
            $query .= " AND DATE(v.fecha) >= ?";
            $params[] = $fecha_inicio;
        }
        if (!empty($fecha_fin)) {
            $query .= " AND DATE(v.fecha) <= ?";
            $params[] = $fecha_fin;
        }
        if (!empty($estado)) {
            $query .= " AND v.estado = ?";
            $params[] = $estado;
        }
        if (!empty($id_sede)) {
            $query .= " AND u.id_sede = ?";
            $params[] = $id_sede;
        }
        
        $query .= " ORDER BY v.fecha DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getVentasPendientes() {
        $query = "SELECT v.*, c.nombre as cliente_nom, c.apellidos as cliente_ape, c.num_documento
                  FROM ventas v 
                  JOIN clientes c ON v.id_cliente = c.id
                  WHERE v.estado = 'pendiente'
                  ORDER BY v.fecha ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function crearVentaDesdeCotizacion($id_cotizacion, $id_vendedor) {
        try {
            $this->conn->beginTransaction();
            
            // Traer cotización
            $stmt = $this->conn->prepare("SELECT * FROM cotizaciones WHERE id = ?");
            $stmt->execute([$id_cotizacion]);
            $cot = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if(!$cot) throw new Exception("Cotización no encontrada");

            // Crear Venta
            $stmtV = $this->conn->prepare("INSERT INTO ventas (id_cotizacion, id_cliente, id_vendedor, subtotal, igv, total, estado) 
                                           VALUES (?, ?, ?, ?, ?, ?, 'pendiente')");
            $stmtV->execute([$cot['id'], $cot['id_cliente'], $id_vendedor, $cot['subtotal'], $cot['igv'], $cot['total']]);
            $id_venta = $this->conn->lastInsertId();

            // Copiar Detalles
            $stmtDet = $this->conn->prepare("SELECT * FROM detalle_cotizacion WHERE id_cotizacion = ?");
            $stmtDet->execute([$id_cotizacion]);
            $detalles = $stmtDet->fetchAll(PDO::FETCH_ASSOC);

            $stmtInsDet = $this->conn->prepare("INSERT INTO detalle_venta (id_venta, id_producto, id_servicio, descripcion, cantidad, precio_unitario, subtotal) 
                                                VALUES (?, ?, ?, ?, ?, ?, ?)");
            foreach($detalles as $d) {
                $stmtInsDet->execute([$id_venta, $d['id_producto'], $d['id_servicio'], $d['descripcion'], $d['cantidad'], $d['precio_unitario'], $d['subtotal']]);
            }

            // Cambiar estado de la cotización
            $stmtUpdCot = $this->conn->prepare("UPDATE cotizaciones SET estado = 'aprobada' WHERE id = ?");
            $stmtUpdCot->execute([$id_cotizacion]);
            
            // Generar Operación pendiente automáticamente
            $stmtOp = $this->conn->prepare("INSERT INTO operaciones (id_venta, fecha_programada, estado) VALUES (?, DATE_ADD(NOW(), INTERVAL 1 DAY), 'pendiente')");
            $stmtOp->execute([$id_venta]);

            $this->conn->commit();
            return $id_venta;
        } catch(Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    public function crearVentaDirecta($data) {
        try {
            $this->conn->beginTransaction();
            $id_vendedor = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 1;
            $id_cliente = $data['id_cliente'];
            $subtotal = $data['subtotal'];
            $igv = $data['igv'];
            $total = $data['total'];
            
            // Crear Venta (sin id_cotizacion)
            $stmtV = $this->conn->prepare("INSERT INTO ventas (id_cotizacion, id_cliente, id_vendedor, subtotal, igv, total, estado) 
                                           VALUES (NULL, ?, ?, ?, ?, ?, 'pendiente')");
            $stmtV->execute([$id_cliente, $id_vendedor, $subtotal, $igv, $total]);
            $id_venta = $this->conn->lastInsertId();

            // Insertar Detalles
            if (!empty($data['items'])) {
                $stmtInsDet = $this->conn->prepare("INSERT INTO detalle_venta (id_venta, id_producto, id_servicio, descripcion, cantidad, precio_unitario, subtotal) 
                                                    VALUES (?, ?, ?, ?, ?, ?, ?)");
                foreach($data['items'] as $d) {
                    $id_prod = !empty($d['id_producto']) ? $d['id_producto'] : NULL;
                    $id_serv = !empty($d['id_servicio']) ? $d['id_servicio'] : NULL;
                    $stmtInsDet->execute([$id_venta, $id_prod, $id_serv, $d['descripcion'], $d['cantidad'], $d['precio'], $d['subtotal']]);
                }
            }

            // Generar Operación pendiente automáticamente
            $stmtOp = $this->conn->prepare("INSERT INTO operaciones (id_venta, fecha_programada, estado) VALUES (?, DATE_ADD(NOW(), INTERVAL 1 DAY), 'pendiente')");
            $stmtOp->execute([$id_venta]);

            $this->conn->commit();
            return $id_venta;
        } catch(Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }
    
    public function updateEstadoVenta($id_venta, $estado) {
        $stmt = $this->conn->prepare("UPDATE ventas SET estado = ? WHERE id = ?");
        return $stmt->execute([$estado, $id_venta]);
    }
}
?>
