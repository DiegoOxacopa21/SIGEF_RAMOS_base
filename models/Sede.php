<?php
require_once 'config/config.php';

class Sede {
    private $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    public function getAllSedes() {
        $query = "SELECT * FROM sedes WHERE estado = 'activo'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
