<?php
namespace Domain\Entities;
use Core\Model;

class LogAcesso extends Model {
    protected static string $table = 'log_acesso';
    protected array $fields = ['usuario_id', 'ip'];

    public int $usuario_id;
    public string $ip;
}
