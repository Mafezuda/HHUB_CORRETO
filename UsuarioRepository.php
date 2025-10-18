<?php
namespace Healthhub\Emr\Domain\Repositories;

use Healthhub\Emr\Config\Database;
use Healthhub\Emr\Domain\Entities\Usuario;
use PDO;

class UsuarioRepository
{
    public function findByEmail(string $email): ?Usuario
    {
        $pdo = Database::pdo();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) return null;
        $u = new Usuario();
        foreach ($row as $k => $v) $u->{$k} = $v;
        return $u;
    }
}
