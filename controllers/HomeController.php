<?php
require_once 'controllers/BaseController.php';
require_once 'models/Catalog.php';

class HomeController extends BaseController {
    
    public function index() {
        $data = [
            'title' => 'Inicio - SIGEF RAMOS'
        ];
        $this->render('public/home', $data, 'public');
    }

    public function catalogo() {
        $catalogModel = new Catalog();
        $ataudes = $catalogModel->getAtaudes();
        $otrosProductos = $catalogModel->getOtrosProductos();
        $servicios = $catalogModel->getServiciosAdicionales();

        $data = [
            'title' => 'Catálogo de Servicios y Productos',
            'ataudes' => $ataudes,
            'otrosProductos' => $otrosProductos,
            'servicios' => $servicios
        ];
        $this->render('public/catalog', $data, 'public');
    }

    public function proforma() {
        $catalogModel = new Catalog();
        $ataudes = $catalogModel->getAtaudes();
        $otrosProductos = $catalogModel->getOtrosProductos();
        $servicios = $catalogModel->getServiciosAdicionales();

        $data = [
            'title' => 'Simulador de Proforma',
            'ataudes' => $ataudes,
            'otrosProductos' => $otrosProductos,
            'servicios' => $servicios
        ];
        $this->render('public/proforma', $data, 'public');
    }

    public function contacto() {
        $data = [
            'title' => 'Contacto - SIGEF RAMOS'
        ];
        $this->render('public/contacto', $data, 'public');
    }
}
?>
