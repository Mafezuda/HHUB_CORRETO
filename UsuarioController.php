<?php
namespace Healthhub\Emr\Http\Controllers;

use Healthhub\Emr\Core\Controller;
use Healthhub\Emr\Domain\Entities\Usuario;

class UsuarioController extends Controller {
    private function requireAuth(): void {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (empty($_SESSION['user_id'])) {
            $this->redirect('/healthhub/public/index.php');
        }
    }

    // Página pós-login com botão de cadastro
    public function home(): void {
        $this->requireAuth();
        $users = Usuario::all(); // mostra também uma listinha pra provar que grava no BD
        $this->view('usuario/home', compact('users'));
    }

    public function createForm(): void {
        $this->requireAuth();
        $this->view('usuario/create');
    }

    public function store(): void {
        $this->requireAuth();

        // valida inputs
        $name     = trim($_POST['name'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($name === '' || $email === '' || $password === '') {
            $error = "Preencha todos os campos.";
            $this->view('usuario/create', compact('error', 'name', 'email'));
            return;
        }

        if (Usuario::findByEmail($email)) {
            $error = "E-mail já cadastrado.";
            $this->view('usuario/create', compact('error', 'name', 'email'));
            return;
        }

        $u = new Usuario();
        $u->name = $name;
        $u->email = $email;
        $u->password_hash = password_hash($password, PASSWORD_BCRYPT);

        if ($u->save()) {
            $success = "Usuário cadastrado com sucesso! (ID {$u->id})";
            $users = Usuario::all();
            $this->view('usuario/home', compact('success', 'users'));
        } else {
            $error = "Falha ao salvar no banco.";
            $this->view('usuario/create', compact('error', 'name', 'email'));
        }
    }
}
