<?php
session_start();
require_once __DIR__ . '/../../vendor/autoload.php';

use Healthhub\Emr\Http\Controllers\UsuarioController;

if (empty($_SESSION['user_id'])) {
  header("Location: /healthhub/public/index.php");
  exit;
}

$controller = new UsuarioController();
$controller->home();

