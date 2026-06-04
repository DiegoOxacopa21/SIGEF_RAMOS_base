<?php
require_once 'config/config.php';

class Catalog {
    private $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    public function getCatalogo() {
        $query = "SELECT * FROM productos_catalogo WHERE estado = 'disponible'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getServiciosAdicionales() {
        $query = "SELECT * FROM servicios WHERE estado = 'activo'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAtaudes() {
        $query = "SELECT * FROM productos_catalogo WHERE tipo = 'ataud' AND estado = 'disponible'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getOtrosProductos() {
        $query = "SELECT * FROM productos_catalogo WHERE tipo != 'ataud' AND estado = 'disponible'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getProductoById($id) {
        $query = "SELECT * FROM productos_catalogo WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getServicioById($id) {
        $query = "SELECT * FROM servicios WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllProductos() {
        $query = "SELECT * FROM productos_catalogo";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllServicios() {
        $query = "SELECT * FROM servicios";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // CRUD for products
    public function addProducto($data) {
        $query = "INSERT INTO productos_catalogo (tipo, nombre, descripcion, precio, imagen, estado) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$data['tipo'], $data['nombre'], $data['descripcion'], $data['precio'], $data['imagen'], $data['estado']]);
    }

    public function updateProducto($data) {
        if(!empty($data['imagen'])) {
            $query = "UPDATE productos_catalogo SET tipo=?, nombre=?, descripcion=?, precio=?, imagen=?, estado=? WHERE id=?";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute([$data['tipo'], $data['nombre'], $data['descripcion'], $data['precio'], $data['imagen'], $data['estado'], $data['id_producto']]);
        } else {
            $query = "UPDATE productos_catalogo SET tipo=?, nombre=?, descripcion=?, precio=?, estado=? WHERE id=?";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute([$data['tipo'], $data['nombre'], $data['descripcion'], $data['precio'], $data['estado'], $data['id_producto']]);
        }
    }

    public function deleteProducto($id) {
        $stmt = $this->conn->prepare("DELETE FROM productos_catalogo WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // CRUD for services
    public function addServicio($data) {
        $query = "INSERT INTO servicios (nombre, descripcion, precio_base, estado) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$data['nombre'], $data['descripcion'], $data['precio_base'], $data['estado']]);
    }

    public function updateServicio($data) {
        $query = "UPDATE servicios SET nombre=?, descripcion=?, precio_base=?, estado=? WHERE id=?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$data['nombre'], $data['descripcion'], $data['precio_base'], $data['estado'], $data['id_servicio']]);
    }

    public function deleteServicio($id) {
        $stmt = $this->conn->prepare("DELETE FROM servicios WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>
