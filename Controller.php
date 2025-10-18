<?php
namespace Healthhub\Emr\Core;

abstract class Controller {
    protected function view(string $view, array $data = []): void {
        extract($data);
        require __DIR__ . "/../../views/layout/header.php";
        require __DIR__ . "/../../views/{$view}.php";
        require __DIR__ . "/../../views/layout/footer.php";
    }

    protected function redirect(string $path): void {
        header("Location: {$path}");
        exit;
    }
}
