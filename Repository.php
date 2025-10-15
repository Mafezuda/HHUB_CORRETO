<?php
namespace Core;
use Config\Database;

abstract class Repository {
    protected string $table;

    public function all(): array {
        $pdo = Database::getInstance();
        return $pdo->query("SELECT * FROM {$this->table}")->fetchAll();
    }

    public function find(int $id): ?array {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }
}
