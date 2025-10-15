<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Cadastrar Usuário</title>
</head>
<body>
  <h2>Novo Usuário</h2>
  <form method="POST">
    <label>E-mail:</label><br>
    <input type="email" name="email" required><br>
    <label>Perfil:</label><br>
    <input type="text" name="perfil" required><br>
    <label>Senha:</label><br>
    <input type="password" name="senha" required><br><br>
    <button type="submit">Salvar</button>
  </form>
  <p><a href="login.php">Voltar ao login</a></p>
</body>
</html>
