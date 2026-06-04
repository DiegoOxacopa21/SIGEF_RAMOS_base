<?php
require_once 'controllers/BaseController.php';

class AdminController extends BaseController
{

    public function dashboard()
    {
        // Verificar que esté logueado
        $this->checkAuth();

        $role = $_SESSION['user_role_name'];

        $data = [
            'title' => 'Panel de Administración - ' . $role,
            'role' => $role
        ];
        $this->render('admin/dashboard', $data, 'admin');
    }

    // --- Vistas para Administrador ---
    public function usuarios()
    {
        $this->checkAuth(['Administrador']);
        require_once 'models/User.php';
        require_once 'models/Sede.php';
        require_once 'models/Role.php';

        $userModel = new User();
        $sedeModel = new Sede();
        $roleModel = new Role();

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action_type'])) {
            try {
                if ($_POST['action_type'] == 'crear_usuario') {
                    $userModel->crearUsuario($_POST);
                    header("Location: " . BASE_URL . "?controller=Admin&action=usuarios&msg=creado");
                    exit;
                }
                elseif ($_POST['action_type'] == 'editar_usuario') {
                    $userModel->editarUsuario($_POST);
                    header("Location: " . BASE_URL . "?controller=Admin&action=usuarios&msg=editado");
                    exit;
                }
                elseif ($_POST['action_type'] == 'toggle_estado') {
                    $userModel->toggleEstado($_POST['id_usuario'], $_POST['nuevo_estado']);
                    header("Location: " . BASE_URL . "?controller=Admin&action=usuarios&msg=estado_actualizado");
                    exit;
                }
            }
            catch (Exception $e) {
                header("Location: " . BASE_URL . "?controller=Admin&action=usuarios&msg=error&desc=" . urlencode($e->getMessage()));
                exit;
            }
        }

        $usuarios = $userModel->getAllUsers();
        $sedes = $sedeModel->getAllSedes();
        $roles = $roleModel->getAllRoles();

        $this->render('admin/usuarios', [
            'title' => 'Gestión de Usuarios',
            'usuarios' => $usuarios,
            'sedes' => $sedes,
            'roles' => $roles
        ], 'admin');
    }

    // --- Vistas para Vendedor ---
    public function cotizaciones()
    {
        $this->checkAuth(['Administrador', 'Vendedor', 'Gerente']);
        require_once 'models/Quotation.php';
        require_once 'models/Client.php';
        require_once 'models/Catalog.php';

        $quoteModel = new Quotation();
        $clientModel = new Client();
        $catalogModel = new Catalog();

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action_type'])) {
            if ($_POST['action_type'] == 'nueva_cotizacion') {
                if ($quoteModel->crearCotizacion($_POST)) {
                    header("Location: " . BASE_URL . "?controller=Admin&action=cotizaciones&msg=cotizacion_creada");
                    exit;
                }
                else {
                    header("Location: " . BASE_URL . "?controller=Admin&action=cotizaciones&msg=error");
                    exit;
                }
            }
            elseif ($_POST['action_type'] == 'eliminar_cotizacion') {
                if ($quoteModel->eliminarCotizacion($_POST['id_cotizacion'])) {
                    header("Location: " . BASE_URL . "?controller=Admin&action=cotizaciones&msg=cotizacion_eliminada");
                    exit;
                }
                else {
                    header("Location: " . BASE_URL . "?controller=Admin&action=cotizaciones&msg=error_eliminar");
                    exit;
                }
            }
        }

        $cotizaciones = $quoteModel->getAllCotizaciones();
        $clientes = $clientModel->getAllClientes();
        $ataudes = $catalogModel->getAtaudes();
        $servicios = $catalogModel->getServiciosAdicionales();

        $this->render('admin/cotizaciones', [
            'title' => 'Cotizaciones',
            'cotizaciones' => $cotizaciones,
            'clientes' => $clientes,
            'ataudes' => $ataudes,
            'servicios' => $servicios
        ], 'admin');
    }

    public function clientes()
    {
        $this->checkAuth(['Administrador', 'Vendedor', 'Gerente']);
        require_once 'models/Client.php';
        $clientModel = new Client();
        $clientes = $clientModel->getAllClientes();
        $this->render('admin/vendedor/clientes', ['title' => 'Gestión de Clientes', 'clientes' => $clientes], 'admin');
    }

    public function difuntos()
    {
        $this->checkAuth(['Administrador', 'Vendedor', 'Gerente', 'Operario']);
        require_once 'models/Difunto.php';
        $difuntoModel = new Difunto();
        $difuntos = $difuntoModel->getAllDifuntos();
        $this->render('admin/vendedor/difuntos', ['title' => 'Registro de Difuntos', 'difuntos' => $difuntos], 'admin');
    }

    public function ventas()
    {
        $this->checkAuth(['Administrador', 'Vendedor', 'Gerente', 'Cajero']);
        require_once 'models/Sale.php';
        $saleModel = new Sale();
        $ventas = $saleModel->getAllVentas();
        $this->render('admin/vendedor/ventas', ['title' => 'Gestión de Ventas', 'ventas' => $ventas], 'admin');
    }

    // --- Vistas para Cajero ---
    public function pagos()
    {
        $this->checkAuth(['Administrador', 'Cajero', 'Gerente']);
        require_once 'models/Sale.php';
        $saleModel = new Sale();
        $ventasPendientes = $saleModel->getVentasPendientes();
        $this->render('admin/cajero/pagos', ['title' => 'Registro de Pagos', 'ventas' => $ventasPendientes], 'admin');
    }

    public function comprobantes()
    {
        $this->checkAuth(['Administrador', 'Cajero', 'Gerente']);
        require_once 'models/Payment.php';
        $paymentModel = new Payment();
        $comprobantes = $paymentModel->getAllComprobantes();
        $this->render('admin/cajero/comprobantes', ['title' => 'Comprobantes Emitidos', 'comprobantes' => $comprobantes], 'admin');
    }

    // --- Vistas para Operario ---
    public function operaciones()
    {
        $this->checkAuth(['Administrador', 'Operario', 'Gerente']);
        require_once 'models/Operation.php';
        $opModel = new Operation();
        $operaciones = $opModel->getAllOperaciones();
        $this->render('admin/operario/operaciones', ['title' => 'Control de Operaciones', 'operaciones' => $operaciones], 'admin');
    }

    public function recursos()
    {
        $this->checkAuth(['Administrador', 'Operario', 'Gerente']);
        require_once 'models/Resource.php';
        $resModel = new Resource();
        $recursos = $resModel->getAllRecursos();
        $flota = $resModel->getAllFlota();
        $salas = $resModel->getAllSalas();
        $this->render('admin/operario/recursos', [
            'title' => 'Logística y Recursos',
            'recursos' => $recursos,
            'flota' => $flota,
            'salas' => $salas
        ], 'admin');
    }

    // --- Vistas para Gerente ---
    public function reportes()
    {
        $this->checkAuth(['Administrador', 'Gerente']);
        require_once 'models/Sale.php';
        require_once 'models/Sede.php';

        $saleModel = new Sale();
        $sedeModel = new Sede();

        $fecha_inicio = $_GET['fecha_inicio'] ?? '';
        $fecha_fin = $_GET['fecha_fin'] ?? '';
        $estado = $_GET['estado'] ?? '';
        $id_sede = $_GET['id_sede'] ?? '';

        $ventas = $saleModel->getVentasFiltradas($fecha_inicio, $fecha_fin, $estado, $id_sede);
        $sedes = $sedeModel->getAllSedes();

        if (isset($_GET['export']) && $_GET['export'] == 'csv') {
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename=reporte_ventas.csv');
            $output = fopen('php://output', 'w');
            // Formato UTF-8 para Excel
            fputs($output, "\xEF\xBB\xBF");
            fputcsv($output, ['N Venta', 'Fecha', 'Vendedor', 'Sede', 'Cliente', 'Estado', 'Total'], ';');
            foreach ($ventas as $v) {
                fputcsv($output, [
                    'VN-' . str_pad($v['id'], 5, '0', STR_PAD_LEFT),
                    date('d/m/Y H:i', strtotime($v['fecha'])),
                    $v['vendedor_nom'],
                    $v['sede_nom'],
                    $v['cliente_nom'] . ' ' . $v['cliente_ape'],
                    $v['estado'] == 'pagada' ? 'Cobrado' : 'Pendiente',
                    number_format($v['total'], 2, '.', '')
                ], ';');
            }
            fclose($output);
            exit;
        }

        $this->render('admin/gerente/reportes', [
            'title' => 'Reportes Gerenciales',
            'ventas' => $ventas,
            'sedes' => $sedes,
            'filtros' => compact('fecha_inicio', 'fecha_fin', 'estado', 'id_sede')
        ], 'admin');
    }

    public function modulo()
    {
        $this->checkAuth();
        $moduloName = isset($_GET['m']) ? $_GET['m'] : 'Módulo';
        $this->render('admin/placeholder', ['title' => $moduloName], 'admin');
    }

    public function catalogo()
    {
        $this->checkAuth(['Administrador']);
        require_once 'models/Catalog.php';

        $catalogModel = new Catalog();

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action_type'])) {
            try {
                if ($_POST['action_type'] == 'crear_producto') {
                    // Image Upload
                    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
                        $target_dir = "assets/img/catalog/";
                        if (!is_dir($target_dir))
                            mkdir($target_dir, 0755, true);
                        $file_name = time() . '_' . basename($_FILES["imagen"]["name"]);
                        $target_file = $target_dir . $file_name;
                        if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $target_file)) {
                            $_POST['imagen'] = $file_name;
                        }
                        else {
                            $_POST['imagen'] = '';
                        }
                    }
                    else {
                        $_POST['imagen'] = '';
                    }
                    $catalogModel->addProducto($_POST);
                    header("Location: " . BASE_URL . "?controller=Admin&action=catalogo&msg=producto_creado");
                    exit;
                }
                elseif ($_POST['action_type'] == 'editar_producto') {
                    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
                        $target_dir = "assets/img/catalog/";
                        if (!is_dir($target_dir))
                            mkdir($target_dir, 0755, true);
                        $file_name = time() . '_' . basename($_FILES["imagen"]["name"]);
                        $target_file = $target_dir . $file_name;
                        if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $target_file)) {
                            $_POST['imagen'] = $file_name;
                        }
                    }
                    else {
                        $_POST['imagen'] = '';
                    }
                    $catalogModel->updateProducto($_POST);
                    header("Location: " . BASE_URL . "?controller=Admin&action=catalogo&msg=producto_editado");
                    exit;
                }
                elseif ($_POST['action_type'] == 'eliminar_producto') {
                    $catalogModel->deleteProducto($_POST['id_producto']);
                    header("Location: " . BASE_URL . "?controller=Admin&action=catalogo&msg=producto_eliminado");
                    exit;
                }
                elseif ($_POST['action_type'] == 'crear_servicio') {
                    $catalogModel->addServicio($_POST);
                    header("Location: " . BASE_URL . "?controller=Admin&action=catalogo&msg=servicio_creado");
                    exit;
                }
                elseif ($_POST['action_type'] == 'editar_servicio') {
                    $catalogModel->updateServicio($_POST);
                    header("Location: " . BASE_URL . "?controller=Admin&action=catalogo&msg=servicio_editado");
                    exit;
                }
                elseif ($_POST['action_type'] == 'eliminar_servicio') {
                    $catalogModel->deleteServicio($_POST['id_servicio']);
                    header("Location: " . BASE_URL . "?controller=Admin&action=catalogo&msg=servicio_eliminado");
                    exit;
                }
            }
            catch (Exception $e) {
                header("Location: " . BASE_URL . "?controller=Admin&action=catalogo&msg=error&desc=" . urlencode($e->getMessage()));
                exit;
            }
        }

        $productos = $catalogModel->getAllProductos();
        $servicios = $catalogModel->getAllServicios();

        $this->render('admin/catalogo', [
            'title' => 'Catálogo de Productos y Servicios',
            'productos' => $productos,
            'servicios' => $servicios
        ], 'admin');
    }
}
?>
