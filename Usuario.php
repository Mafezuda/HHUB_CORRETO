<?php
namespace Healthhub\Emr\Domain\Entities;

use Healthhub\Emr\Core\Model;

class Usuario extends Model
{
    protected string $table = 'users';
    protected string $primaryKey = 'id';
    protected array  $fillable = ['name', 'email', 'password_hash'];

    public ?int $id = null;
    public string $name = '';
    public string $email = '';
    public string $password_hash = '';
    public ?string $created_at = null;
    public ?string $updated_at = null;
}
