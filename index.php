<?php
session_start();

$inactiveTime = 600;

if (isset($_SESSION['ultimo_acesso'])) {
  $currentTime = time() - $_SESSION['ultimo_acesso'];

  if ($currentTime > $inactiveTime) {
    session_unset();
    session_destroy();
    header("Location: login.php?session_expired=1");
    exit();
  }
}

$_SESSION['ultimo_acesso'] = time();

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

include './config/connection.php';

$query_pessoa_fisica = "SELECT COUNT(*) FROM clientes WHERE cnpj IS NULL";
$stmt = $pdo->query($query_pessoa_fisica);
$pessoa_fisica = $stmt->fetchColumn();

$query_pessoa_juridica = "SELECT COUNT(*) FROM clientes WHERE cpf IS NULL";
$stmt = $pdo->query($query_pessoa_juridica);
$pessoa_juridica = $stmt->fetchColumn();

$query_total_clientes = "SELECT COUNT(*) FROM clientes";
$stmt = $pdo->query($query_total_clientes);
$total_clientes = $stmt->fetchColumn();

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/x-icon" href="./assets/images/favicon.svg">
  <link rel="stylesheet" href="./assets/css/style.css">
  <link rel="stylesheet" href="./assets/css/pages/home.css">
  <script type="text/javascript" src="./assets/js/script.js" defer></script>
  <title>Home | IGE-TEC</title>
</head>
<body class="container">
  <nav id="sidebar">
    <ul>
      <li>
        <a href="index.php">
          <img width="150" height="75" src="./assets/images/logo2.svg" alt="">
        </a>
        <button onclick="toggleSidebar()" id="toggle-btn" class="rotate">
          <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed"><path d="M440-240 200-480l240-240 56 56-183 184 183 184-56 56Zm264 0L464-480l240-240 56 56-183 184 183 184-56 56Z"/></svg>
        </button>
      </li>
      <li class="active">
        <a href="index.php">
          <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed"><path d="M240-200h120v-240h240v240h120v-360L480-740 240-560v360Zm-80 80v-480l320-240 320 240v480H520v-240h-80v240H160Zm320-350Z"/></svg>
          <span>Home</span>
        </a>
      </li>
      <li>
        <button onclick="toggleSubMenu(this)" class="dropdown-btn">
          <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed"><path d="M40-160v-112q0-34 17.5-62.5T104-378q62-31 126-46.5T360-440q66 0 130 15.5T616-378q29 15 46.5 43.5T680-272v112H40Zm720 0v-120q0-44-24.5-84.5T666-434q51 6 96 20.5t84 35.5q36 20 55 44.5t19 53.5v120H760ZM360-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47Zm400-160q0 66-47 113t-113 47q-11 0-28-2.5t-28-5.5q27-32 41.5-71t14.5-81q0-42-14.5-81T544-792q14-5 28-6.5t28-1.5q66 0 113 47t47 113ZM120-240h480v-32q0-11-5.5-20T580-306q-54-27-109-40.5T360-360q-56 0-111 13.5T140-306q-9 5-14.5 14t-5.5 20v32Zm240-320q33 0 56.5-23.5T440-640q0-33-23.5-56.5T360-720q-33 0-56.5 23.5T280-640q0 33 23.5 56.5T360-560Zm0 320Zm0-400Z"/></svg>
          <span class="font-2-s">Clientes</span>
          <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed"><path d="M480-344 240-584l56-56 184 184 184-184 56 56-240 240Z"/></svg>
        </button>
        <ul class="sub-menu">
          <div>
            <li class="font-2-s"><a href="./clientes/cadastrar.php" >Cadastrar</a></li>
            <li class="font-2-s"><a href="./clientes/visualizar.php" >Visualizar</a></li>
          </div>
        </ul>
      </li>
    </ul>
  </nav>
  <main>
    <div class="dashboard-card">
      <div class="dashboard-content">
        <div>
          <span class="font-1-xl"><?php echo $pessoa_fisica; ?></span>
          <img src="" alt="">
        </div>
        <p class="font-1-m">Clientes pessoa física cadastrados</p>
      </div>
    </div>
    <div class="dashboard-card">
      <div class="dashboard-content">
        <div>
          <span class="font-1-xl"><?php echo $pessoa_juridica; ?></span>
          <img src="" alt="">
        </div>
        <p class="font-1-m">Clientes pessoa jurídica cadastrados</p>
      </div>
    </div>
    <div class="dashboard-card">
      <div class="dashboard-content">
        <div>
          <span class="font-1-xl"><?php echo $total_clientes; ?></span>
          <img src="" alt="">
        </div>
        <p class="font-1-m">Clientes cadastrados</p>
      </div>
    </div>
  </main>
</body>
</html>