<?php
session_start();

// Verificar se o usuário está logado
if (isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit();
}

$host = "localhost";
$user = "root";
$password = "";
$database = "test";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $email = $conn->real_escape_string($email);

    $query = "SELECT id, password FROM users WHERE email = '$email'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if ($password === $user['password']) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $email;

            header("Location: index.php");
            exit();
        } else {
            $errorPassword = "Senha incorreta.";
        }
    } else {
        $errorEmail = "E-mail não encontrado.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/x-icon" href="./assets/images/favicon.svg">
  <link rel="stylesheet" href="./assets/css/style.css">
  <title>Login | IGE-TEC</title>
</head>
<body>
  <main class="login__container">
    <div class="login__content">
      <div class="login__logo">
        <img src="./assets/images/logo1.svg" alt="logo">
      </div>
      <h1 class="font-1-xxl">Login</h1>
      <p class="font-1-s">Entre para acessar seu sistema de gestão.</p>
      <form class="login__form" method="POST" onsubmit="return validateLogin()" action="login.php">
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
        <button class="login__button--submit font-1-xs" type="submit">Login</button>
      </form>
    </div>
    <div class="login__image">
      <img src="./assets/images/login.png" alt="cartões e uma caneta da IGE-TEC">
    </div>
  </main>
</body>
</html>