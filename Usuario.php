<?php
namespace Healthhub\Emr\Domain\Entities;

use Healthhub\Emr\Config\Database;
use PDO;

class Usuario {
    public ?int $id = null;
    public string $name = '';
    public string $email = '';
    public string $password_hash = '';
    public ?string $created_at = null;
    public ?string $updated_at = null;

    public function save(): bool {
        $pdo = Database::getInstance();

        if ($this->id) {
            $stmt = $pdo->prepare(
                "UPDATE users SET name=?, email=?, password_hash=?, updated_at=NOW() WHERE id=?"
            );
            return $stmt->execute([$this->name, $this->email, $this->password_hash, $this->id]);
        } else {
            $stmt = $pdo->prepare(
                "INSERT INTO users (name, email, password_hash, created_at) VALUES (?, ?, ?, NOW())"
            );
            $ok = $stmt->execute([$this->name, $this->email, $this->password_hash]);
            if ($ok) {
                $this->id = (int) $pdo->lastInsertId();
            }
            return $ok;
        }
    }

    public static function findByEmail(string $email): ?self {
        $pdo = Database::getInstance();
        $st = $pdo->prepare("SELECT * FROM users WHERE email=? LIMIT 1");
        $st->execute([$email]);
        $st->setFetchMode(PDO::FETCH_CLASS, self::class);
        $u = $st->fetch();
        return $u ?: null;
    }

    public static function all(): array {
        $pdo = Database::getInstance();
        $st = $pdo->query("SELECT * FROM users ORDER BY id DESC");
        $st->setFetchMode(PDO::FETCH_CLASS, self::class);
        return $st->fetchAll();
    }
}
