<?php if (!empty($error)): ?>
  <article class="contrast"><strong><?= htmlspecialchars($error) ?></strong></article>
<?php endif; ?>

<form method="post" action="/healthhub/public/index.php?action=login">
  <label>E-mail
    <input type="email" name="email" required>
  </label>
  <label>Senha
    <input type="password" name="password" required>
  </label>
  <button type="submit">Entrar</button>
</form>
