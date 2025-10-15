<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Cadastrar Paciente</title>
</head>
<body>
  <h2>Cadastrar Paciente</h2>
  <form method="POST">
    <label>Nome Completo:</label><br>
    <input type="text" name="nome_completo" required><br>
    <label>CPF:</label><br>
    <input type="text" name="cpf" required><br>
    <label>Data de Nascimento:</label><br>
    <input type="date" name="data_nascimento" required><br>
    <label>Telefone:</label><br>
    <input type="text" name="telefone" required><br>
    <label>E-mail:</label><br>
    <input type="email" name="email" required><br>
    <label>Convênio:</label><br>
    <input type="text" name="convenio"><br>
    <label>Endereço:</label><br>
    <input type="text" name="endereco"><br><br>
    <button type="submit">Salvar</button>
  </form>
</body>
</html>
