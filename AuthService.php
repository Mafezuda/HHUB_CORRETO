<?php
namespace Healthhub\Emr\Domain\Services;

use Healthhub\Emr\Domain\Repositories\UsuarioRepository;

class AuthService
{
    public function __construct(private UsuarioRepository $repo = new UsuarioRepository()) {}

    public function attempt(string $email, string $password): bool
    {
        $user = $this->repo->findByEmail($email);
        if (!$user) return false;
        if (!password_verify($password, $user->password_hash)) return false;

        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_name'] = $user->name;
        return true;
    }

    public function check(): bool
    {
        return !empty($_SESSION['user_id']);
    }

    public function logout(): void
    {
        session_destroy();
    }
}
