<?php
session_start();
require_once __DIR__ . '/../../vendor/autoload.php';

use Healthhub\Emr\Tools\DbFirstGenerator;

if (empty($_SESSION['user_id'])) {
  header("Location: /healthhub/public/index.php");
  exit;
}

$table = $_GET['table'] ?? '';
if (!$table) {
  echo "<p>Informe ?table=nome_da_tabela</p>";
  exit;
}

$target = __DIR__ . '/../generated/' . basename($table);
$gen = new DbFirstGenerator();
$result = $gen->generate($table, $target);

echo "<p>CRUD gerado em: <code>/public/generated/{$table}</code></p>";
echo '<p><a href="/healthhub/public/generated/'.htmlspecialchars($table).'/index.php">Abrir CRUD gerado</a></p>';
