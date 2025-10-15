<?php
namespace Http\Controllers;
use Core\Controller;
use Domain\Entities\Paciente;

class PacienteController extends Controller {
    public function create(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $p = new Paciente();
            foreach ($p->fields as $f) {
                $p->$f = $_POST[$f] ?? null;
            }
            $p->save();
            echo "<p style='color:green;'>Paciente cadastrado no banco!</p>";
        } else {
            $this->view('paciente/register_paciente');
        }
    }

    public function listar(): void {
        $pacientes = Paciente::all();
        $this->view('paciente/listar', ['pacientes' => $pacientes]);
    }
}
