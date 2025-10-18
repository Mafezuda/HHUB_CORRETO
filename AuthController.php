<?php
namespace Healthhub\Emr\Http\Controllers;

use Healthhub\Emr\Core\Controller;
use Healthhub\Emr\Domain\Entities\Usuario;

class AuthController extends Controller {
    public function loginForm(): void {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!empty($_SESSION['user_id'])) {
            $this->redirect('/healthhub/public/usuario/index.php');
        }
        $this->view('auth/login');
    }

    public function login(): void {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $email = trim($_POST['email'] ?? '');
        $pass  = $_POST['password'] ?? '';

        $user = Usuario::findByEmail($email);
        if ($user && password_verify($pass, $user->password_hash)) {
            $_SESSION['user_id'] = $user->id;
            $_SESSION['user_name'] = $user->name;
            $this->redirect('/healthhub/public/usuario/index.php');
        } else {
            $error = "Credenciais inválidas.";
            $this->view('auth/login', compact('error', 'email'));
        }
    }

    public function logout(): void {
        if (session_status() === PHP_SESSION_NONE) session_start();
        session_destroy();
        $this->redirect('/healthhub/public/index.php');
    }
}
