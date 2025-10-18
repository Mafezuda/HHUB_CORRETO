<?php
namespace Healthhub\Emr\Tools;

use Healthhub\Emr\Config\Database;
use PDO;

class DbFirstGenerator
{
    public function generate(string $table, string $targetDir): array
    {
        $pdo = Database::pdo();
        $cols = $pdo->query("SHOW COLUMNS FROM `{$table}`")->fetchAll(PDO::FETCH_ASSOC);
        if (!$cols) throw new \RuntimeException("Tabela {$table} não encontrada.");

        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

        // Gerar index.php (listar)
        $index = <<<PHP
<?php
session_start();
require_once __DIR__ . '/../../../vendor/autoload.php';

use Healthhub\Emr\Config\Database;

if (empty(\$_SESSION['user_id'])) { header("Location: /healthhub/public/index.php"); exit; }

\$pdo = Database::pdo();
\$rows = \$pdo->query("SELECT * FROM {$table} ORDER BY 1 DESC")->fetchAll();

?><!doctype html><html lang="pt-br"><head>
<meta charset="utf-8"><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
<title>{$table} - CRUD</title></head><body><main class="container">
<h3>{$table} - CRUD (gerado)</h3>
<a href="create.php">+ Novo</a>
<table role="grid"><thead><tr>
PHP;

        foreach ($cols as $c) { $index .= "<th>{$c['Field']}</th>"; }
        $index .= "<th>Ações</th></tr></thead><tbody><?php foreach (\$rows as \$r): ?><tr>";

        foreach ($cols as $c) { $f = $c['Field']; $index .= "<td><?= htmlspecialchars(\$r['{$f}']) ?></td>"; }
        $pk = $cols[0]['Field'];
        $index .= <<<PHP
<td>
  <a href="edit.php?{$pk}=<?= urlencode(\$r['{$pk}']) ?>">Editar</a>
  <form method="post" action="destroy.php" style="display:inline" onsubmit="return confirm('Excluir?')">
    <input type="hidden" name="{$pk}" value="<?= htmlspecialchars(\$r['{$pk}']) ?>">
    <button type="submit">Excluir</button>
  </form>
</td>
</tr><?php endforeach; ?></tbody></table></main></body></html>
PHP;

        file_put_contents($targetDir . '/index.php', $index);

        // create.php
        $formFields = '';
        foreach ($cols as $c) {
            if ($c['Key'] === 'PRI') continue;
            $f = $c['Field'];
            $formFields .= "<label>{$f}<input type=\"text\" name=\"{$f}\"></label>\n";
        }
        $create = <<<PHP
<?php
session_start();
require_once __DIR__ . '/../../../vendor/autoload.php';
if (empty(\$_SESSION['user_id'])) { header("Location: /healthhub/public/index.php"); exit; }
?><!doctype html><html lang="pt-br"><head>
<meta charset="utf-8"><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
<title>Novo {$table}</title></head><body><main class="container">
<h3>Novo {$table}</h3>
<form method="post" action="store.php">
{$formFields}
<button type="submit">Salvar</button>
</form>
</main></body></html>
PHP;
        file_put_contents($targetDir . '/create.php', $create);

        // store.php
        $nonPk = array_filter($cols, fn($c) => $c['Key'] !== 'PRI');
        $fields = implode(', ', array_map(fn($c) => "`{$c['Field']}`", $nonPk));
        $vals   = implode(', ', array_map(fn($c) => ":" . $c['Field'], $nonPk));
        $binds  = '';
        foreach ($nonPk as $c) {
            $f = $c['Field'];
            $binds .= "\$stmt->bindValue(':{$f}', \$_POST['{$f}'] ?? null);\n";
        }
        $store = <<<PHP
<?php
session_start();
require_once __DIR__ . '/../../../vendor/autoload.php';
use Healthhub\Emr\Config\Database;

if (empty(\$_SESSION['user_id'])) { header("Location: /healthhub/public/index.php"); exit; }
\$pdo = Database::pdo();
\$sql = "INSERT INTO `{$table}` ({$fields}) VALUES ({$vals})";
\$stmt = \$pdo->prepare(\$sql);
{$binds}
\$stmt->execute();
header("Location: /healthhub/public/generated/{$table}/index.php");
PHP;
        file_put_contents($targetDir . '/store.php', $store);

        // edit.php
        $pk = $cols[0]['Field'];
        $inputs = '';
        foreach ($cols as $c) {
            if ($c['Key'] === 'PRI') continue;
            $f = $c['Field'];
            $inputs .= "<label>{$f}<input type=\"text\" name=\"{$f}\" value=\"<?= htmlspecialchars(\$row['{$f}'] ?? '') ?>\"></label>\n";
        }
        $edit = <<<PHP
<?php
session_start();
require_once __DIR__ . '/../../../vendor/autoload.php';
use Healthhub\Emr\Config\Database;

if (empty(\$_SESSION['user_id'])) { header("Location: /healthhub/public/index.php"); exit; }
\$pdo = Database::pdo();
\${$pk} = \$_GET['{$pk}'] ?? null;
\$row = null;
if (\${$pk}) {
  \$stmt = \$pdo->prepare("SELECT * FROM `{$table}` WHERE `{$pk}` = ?");
  \$stmt->execute([\${$pk}]);
  \$row = \$stmt->fetch();
}
?><!doctype html><html lang="pt-br"><head>
<meta charset="utf-8"><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
<title>Editar {$table}</title></head><body><main class="container">
<h3>Editar {$table}</h3>
<form method="post" action="update.php">
  <input type="hidden" name="{$pk}" value="<?= htmlspecialchars(\$row['{$pk}'] ?? '') ?>">
  {$inputs}
  <button type="submit">Atualizar</button>
</form>
</main></body></html>
PHP;
        file_put_contents($targetDir . '/edit.php', $edit);

        // update.php
        $sets = implode(', ', array_map(fn($c) => "`{$c['Field']}` = :" . $c['Field'], $nonPk));
        $bindsU = '';
        foreach ($nonPk as $c) {
            $f = $c['Field'];
            $bindsU .= "\$stmt->bindValue(':{$f}', \$_POST['{$f}'] ?? null);\n";
        }
        $update = <<<PHP
<?php
session_start();
require_once __DIR__ . '/../../../vendor/autoload.php';
use Healthhub\Emr\Config\Database;

if (empty(\$_SESSION['user_id'])) { header("Location: /healthhub/public/index.php"); exit; }
\$pdo = Database::pdo();
\$sql = "UPDATE `{$table}` SET {$sets} WHERE `{$pk}` = :pk";
\$stmt = \$pdo->prepare(\$sql);
{$bindsU}
\$stmt->bindValue(':pk', \$_POST['{$pk}'] ?? null);
\$stmt->execute();
header("Location: /healthhub/public/generated/{$table}/index.php");
PHP;
        file_put_contents($targetDir . '/update.php', $update);

        // destroy.php
        $destroy = <<<PHP
<?php
session_start();
require_once __DIR__ . '/../../../vendor/autoload.php';
use Healthhub\Emr\Config\Database;

if (empty(\$_SESSION['user_id'])) { header("Location: /healthhub/public/index.php"); exit; }
\$pdo = Database::pdo();
\$stmt = \$pdo->prepare("DELETE FROM `{$table}` WHERE `{$pk}` = ?");
\$stmt->execute([\$_POST['{$pk}'] ?? null]);
header("Location: /healthhub/public/generated/{$table}/index.php");
PHP;
        file_put_contents($targetDir . '/destroy.php', $destroy);

        return ['ok' => true, 'path' => $targetDir];
    }
}
