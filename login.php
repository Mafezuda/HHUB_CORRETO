<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Login | HealthHub EMR</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #007bff, #6610f2);
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .card {
      border: none;
      border-radius: 20px;
      box-shadow: 0 0 30px rgba(0,0,0,0.15);
    }
    .logo {
      font-size: 1.6rem;
      font-weight: 600;
      color: #007bff;
    }
    .btn-primary {
      border-radius: 25px;
      padding: 10px;
      font-weight: 500;
    }
    .text-muted {
      font-size: 0.9rem;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12 col-md-5">
        <div class="card p-4">
          <div class="card-body text-center">
            <h2 class="logo mb-3">HealthHub EMR</h2>
            <p class="text-muted mb-4">Acesse sua conta para continuar</p>

            <?php if (!empty($error)): ?>
              <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST" action="/healthhub/public/index.php?action=login">
              <div class="form-floating mb-3">
                <input type="email" name="email" class="form-control" id="email" placeholder="E-mail" required value="<?= htmlspecialchars($email ?? '') ?>">
                <label for="email">E-mail</label>
              </div>

              <div class="form-floating mb-3">
                <input type="password" name="password" class="form-control" id="password" placeholder="Senha" required>
                <label for="password">Senha</label>
              </div>

              <button type="submit" class="btn btn-primary w-100">Entrar</button>
            </form>

            <hr class="my-4">

            <p class="text-muted small mb-0">
              Dica: use o usuário padrão do banco <br>
              <b>admin@healthhub.test</b> / senha <b>password</b>
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
