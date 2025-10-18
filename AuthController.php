<?php
namespace Healthhub\Emr\Http\Controllers;

use Healthhub\Emr\Core\Controller;
use Healthhub\Emr\Domain\Services\AuthService;

class AuthController extends Controller
{
    public function loginForm(): void
    {
        $this->view('auth/login');
    }

    public function login(): void
    {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        $auth = new AuthService();

        if ($auth->attempt($email, $password)) {
            $this->redirect('/healthhub/public/home.php');
        } else {
            $this->view('auth/login', [
                'error' => 'E-mail ou senha incorretos.'
            ]);
        }
    }

    public function logout(): void
    {
        $auth = new AuthService();
        $auth->logout();
        $this->redirect('/healthhub/public/index.php');
    }
}
