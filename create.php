<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use Healthhub\Emr\Http\Controllers\UsuarioController;

$ctrl = new UsuarioController();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ctrl->store();
} else {
    $ctrl->createForm();
}
