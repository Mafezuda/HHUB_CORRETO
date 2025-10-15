<?php
namespace Domain\Entities;
use Core\Model;

class Usuario extends Model {
    protected static string $table = 'usuario';
    protected array $fields = ['email', 'perfil', 'senha_hash'];

    public string $email;
    public string $perfil;
    public string $senha_hash;
}
