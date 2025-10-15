<?php
namespace Core;
use Config\Database;

abstract class Model {
    protected static string $table;
    protected array $fields = [];

    public function save(): bool {
        $pdo = Database::getInstance();
        $cols = implode(',', $this->fields);
        $placeholders = implode(',', array_map(fn($f)=>":$f",$this->fields));
        $sql = "INSERT INTO ".static::$table." ($cols) VALUES ($placeholders)";
        $stmt = $pdo->prepare($sql);
        foreach ($this->fields as $f) {
            $stmt->bindValue(":$f", $this->$f);
        }
        return $stmt->execute();
    }

    public static function all(): array {
        $pdo = Database::getInstance();
        $stmt = $pdo->query("SELECT * FROM ".static::$table);
        return $stmt->fetchAll();
    }
}
