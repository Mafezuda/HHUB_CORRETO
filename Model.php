<?php
namespace Healthhub\Emr\Core;

use Healthhub\Emr\Config\Database;
use PDO;

abstract class Model
{
    protected string $table;
    protected string $primaryKey = 'id';
    protected array $fillable = [];

    public function fill(array $data): void
    {
        foreach ($this->fillable as $f) {
            if (array_key_exists($f, $data)) {
                $this->{$f} = $data[$f];
            }
        }
    }

    public function save(): bool
    {
        $pdo = Database::pdo();
        $data = [];
        foreach ($this->fillable as $f) {
            $data[$f] = $this->{$f} ?? null;
        }

        if (!empty($this->{$this->primaryKey})) {
            $sets = implode(', ', array_map(fn($f) => "$f = :$f", array_keys($data)));
            $sql  = "UPDATE {$this->table} SET {$sets} WHERE {$this->primaryKey} = :pk";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':pk', $this->{$this->primaryKey});
        } else {
            $cols = implode(', ', array_keys($data));
            $vals = implode(', ', array_map(fn($f) => ":$f", array_keys($data)));
            $sql  = "INSERT INTO {$this->table} ($cols) VALUES ($vals)";
            $stmt = $pdo->prepare($sql);
        }

        foreach ($data as $k => $v) {
            $stmt->bindValue(":$k", $v);
        }

        $ok = $stmt->execute();
        if ($ok && empty($this->{$this->primaryKey})) {
            $this->{$this->primaryKey} = $pdo->lastInsertId();
        }
        return $ok;
    }

    public function delete(): bool
    {
        if (empty($this->{$this->primaryKey})) return false;
        $pdo = Database::pdo();
        $stmt = $pdo->prepare("DELETE FROM {$this->table} WHERE {$this->primaryKey} = ?");
        return $stmt->execute([$this->{$this->primaryKey}]);
    }

    public static function find(int $id): ?static
    {
        $obj = new static();
        $pdo = Database::pdo();
        $stmt = $pdo->prepare("SELECT * FROM {$obj->table} WHERE {$obj->primaryKey} = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        if (!$row) return null;
        foreach ($row as $k => $v) $obj->{$k} = $v;
        return $obj;
    }

    /** @return static[] */
    public static function all(): array
    {
        $obj = new static();
        $pdo = Database::pdo();
        $rows = $pdo->query("SELECT * FROM {$obj->table} ORDER BY {$obj->primaryKey} DESC")->fetchAll();
        return array_map(function ($row) use ($obj) {
            $e = new static();
            foreach ($row as $k => $v) $e->{$k} = $v;
            return $e;
        }, $rows);
    }
}
