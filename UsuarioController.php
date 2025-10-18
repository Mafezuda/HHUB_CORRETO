<?php
namespace Healthhub\Emr\Http\Controllers;

use Healthhub\Emr\Core\Controller;
use Healthhub\Emr\Domain\Entities\Usuario;

class UsuarioController extends Controller
{
    public function index(): void
    {
        $usuarios = Usuario::all();
        $this->view('usuario/index', compact('usuarios'));
    }

    public function create(): void
    {
        $this->view('usuario/create');
    }

    public function store(): void
    {
        $u = new Usuario();
        $u->fill([
            'name'  => trim($_POST['name'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
        ]);
        $password = $_POST['password'] ?? '';
        $u->password_hash = password_hash($password, PASSWORD_BCRYPT);

        $u->save();
        $this->redirect('/healthhub/public/usuario/index.php');
    }

    public function edit(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $usuario = Usuario::find($id);
        if (!$usuario) $this->redirect('/healthhub/public/usuario/index.php');
        $this->view('usuario/edit', compact('usuario'));
    }

    public function update(): void
    {
        $id = (int)($_POST['id'] ?? 0);
        $u = Usuario::find($id);
        if (!$u) $this->redirect('/healthhub/public/usuario/index.php');

        $u->fill([
            'name'  => trim($_POST['name'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
        ]);
        if (!empty($_POST['password'])) {
            $u->password_hash = password_hash($_POST['password'], PASSWORD_BCRYPT);
        }
        $u->save();
        $this->redirect('/healthhub/public/usuario/index.php');
    }

    public function destroy(): void
    {
        $id = (int)($_POST['id'] ?? 0);
        $u = Usuario::find($id);
        if ($u) $u->delete();
        $this->redirect('/healthhub/public/usuario/index.php');
    }
}
