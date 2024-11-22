<?php
session_start();

require_once './config/connection.php';

if (isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $email = $_POST['email'];
  $password = $_POST['password'];

  try {
    $stmt = $pdo->prepare("SELECT id, password FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      $user = $stmt->fetch(PDO::FETCH_ASSOC);
      if ($password === $user['password']) {
        session_regenerate_id(true); // Regenerar ID da sessão
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $email;
        $_SESSION['ultimo_acesso'] = time(); // Timestamp inicial
        header("Location: index.php");
        exit();
      } else {
        $errorPassword = "Senha incorreta.";
      }
    } else {
      $errorEmail = "E-mail não encontrado.";
    }
  } catch (PDOException $e) {
    die("Erro na consulta: " . $e->getMessage());
  }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/x-icon" href="./assets/images/favicon.svg">
  <link rel="stylesheet" href="./assets/css/style.css">
  <link rel="stylesheet" href="./assets/css/pages/login.css">
  <title>Login | IGE-TEC</title>
</head>
<body>
  <main class="login-container">
    <div class="login-content">
      <div class="login-logo">
        <img src="./assets/images/logo1.svg" alt="logo">
      </div>
      <h1 class="font-1-xxl">Login</h1>
      <p class="font-1-s">Entre para acessar seu sistema de gestão.</p>
      <form class="login-form" method="POST" action="login.php">
        <fieldset>
          <label class="font-1-xs" for="email">E-mail</label>
          <input type="email" id="email" name="email" placeholder="Insira seu E-mail">
          <?php if (!empty($errorEmail)): ?>
            <span id="error-email" class="error-span font-1-xs"><?php echo htmlspecialchars($errorEmail); ?></span>
          <?php endif; ?>
        </fieldset>
        <fieldset>
          <label class="font-1-xs" for="password">Senha</label>
          <input type="password" id="password" name="password" placeholder="Insira sua senha">
          <?php if (!empty($errorPassword)): ?>
            <span id="error-password" class="error-span font-1-xs"><?php echo htmlspecialchars($errorPassword); ?></span>
          <?php endif; ?>
        </fieldset>
        <button class="login-button-submit font-1-xs" type="submit">Login</button>
      </form>
    </div>
    <div class="login-image">
      <img src="./assets/images/login.png" alt="cartões e uma caneta da IGE-TEC">
    </div>
  </main>
</body>
</html>