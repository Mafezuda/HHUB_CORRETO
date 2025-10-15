<?php
namespace Http\Controllers;
use Core\Controller;
use Domain\Services\AuthService;

class AuthController extends Controller {
    private AuthService $auth;

    public function __construct() {
        $this->auth = new AuthService();
    }

    public function login(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ok = $this->auth->login($_POST['email'], $_POST['senha']);
            echo $ok 
                ? "<p style='color:green;'>Login realizado com sucesso!</p>"
                : "<p style='color:red;'>Usuário ou senha incorretos.</p>";
        } else {
            $this->view('auth/login');
        }
    }

    public function register(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->auth->registrar($_POST['email'], $_POST['perfil'], $_POST['senha']);
            echo "<p style='color:green;'>Usuário cadastrado com sucesso!</p>";
        } else {
            $this->view('auth/register_usuario');
        }
    }
}
