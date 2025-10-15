<?php
namespace Domain\Entities;
use Core\Model;

class Paciente extends Model {
    protected static string $table = 'paciente';
    protected array $fields = [
        'nome_completo','cpf','data_nascimento','telefone','email','convenio','endereco'
    ];

    public string $nome_completo;
    public string $cpf;
    public string $data_nascimento;
    public string $telefone;
    public string $email;
    public string $convenio;
    public string $endereco;
}
