<?php
namespace Domain\Services;
use Config\Database;

class AuthService {
    public function login(string $email, string $senha): bool {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("SELECT * FROM usuario WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        return $user && password_verify($senha, $user['senha_hash']);
    }

    public function registrar(string $email, string $perfil, string $senha): bool {
        $hash = password_hash($senha, PASSWORD_BCRYPT);
        $stmt = Database::getInstance()->prepare(
            "INSERT INTO usuario (email, perfil, senha_hash) VALUES (?,?,?)"
        );
        return $stmt->execute([$email, $perfil, $hash]);
    }
}
