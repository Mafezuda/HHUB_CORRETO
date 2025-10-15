<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Http\Controllers\AuthController;
use Http\Controllers\PacienteController;

$page = $_GET['page'] ?? 'login';

switch($page) {
    case 'login':
        (new AuthController())->login();
        break;
    case 'register_usuario':
        (new AuthController())->register();
        break;
    case 'register_paciente':
        (new PacienteController())->create();
        break;
    default:
        echo "<h3>Página não encontrada.</h3>";
}
