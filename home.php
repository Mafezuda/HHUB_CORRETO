<?php
session_start();
require_once __DIR__ . '/../vendor/autoload.php';

if (empty($_SESSION['user_id'])) {
  header("Location: /healthhub/public/index.php");
  exit;
}
?>
<!doctype html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <title>Home - HealthHub EMR</title>
  <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
</head>
<body>
<main class="container">
  <h2>Olá, <?= htmlspecialchars($_SESSION['user_name'] ?? 'Usuário') ?></h2>
  <p>Escolha uma opção:</p>
  <a class="contrast" href="/healthhub/public/usuario/index.php">Cadastro de Usuário (CRUD)</a>
  <p><a href="/healthhub/public/logout.php">Sair</a></p>
</main>
</body>
</html>
