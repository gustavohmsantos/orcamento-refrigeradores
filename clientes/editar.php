<?php
  session_start();

  $inactiveTime = 600;

  if (isset($_SESSION['ultimo_acesso'])) {
    $currentTime = time() - $_SESSION['ultimo_acesso'];

    if ($currentTime > $inactiveTime) {
      session_unset();
      session_destroy();
      header("Location: ../login.php?session_expired=1");
      exit();
    }
  }

  $_SESSION['ultimo_acesso'] = time();

  if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
  }

  include '../config/connection.php';

  $cliente = [];
  if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "SELECT * FROM clientes WHERE id_cliente = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
      $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

      if (!$cliente) {
        header("Location: visualizar.php?status=nao_encontrado");
        exit();
      }
    } else {
      header("Location: visualizar.php?status=erro");
      exit();
    }
  } else {
    header("Location: visualizar.php?status=sem_id");
    exit();
  }

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $cpf = !empty($_POST['cpf']) ? $_POST['cpf'] : NULL;
    $cnpj = !empty($_POST['cnpj']) ? $_POST['cnpj'] : NULL;
    $email = !empty($_POST['email']) ? $_POST['email'] : NULL;
    $telefone = $_POST['telefone'];
    $cep = !empty($_POST['cep']) ? $_POST['cep'] : NULL;
    $rua = $_POST['rua'];
    $numero = $_POST['numero'];
    $bairro = $_POST['bairro'];
    $cidade = $_POST['cidade'];
    $estado = $_POST['estado'];

    $sql = "UPDATE clientes 
    SET nome = :nome, cpf = :cpf, cnpj = :cnpj, email = :email, telefone = :telefone, 
        cep = :cep, rua = :rua, numero = :numero, bairro = :bairro, cidade = :cidade, estado = :estado 
    WHERE id_cliente = :id";

    try {
      $stmt = $pdo->prepare($sql);
      $stmt->bindParam(':nome', $nome);
      $stmt->bindParam(':cpf', $cpf);
      $stmt->bindParam(':cnpj', $cnpj);
      $stmt->bindParam(':email', $email);
      $stmt->bindParam(':telefone', $telefone);
      $stmt->bindParam(':cep', $cep);
      $stmt->bindParam(':rua', $rua);
      $stmt->bindParam(':numero', $numero);
      $stmt->bindParam(':bairro', $bairro);
      $stmt->bindParam(':cidade', $cidade);
      $stmt->bindParam(':estado', $estado);
      $stmt->bindParam(':id', $id, PDO::PARAM_INT);

      $stmt->execute();
      header("Location: editar.php?id=" . $_GET['id'] . "&status=sucesso");
      exit();
    } catch (PDOException $e) {
      $erro = urlencode($e->getMessage()); // Codifica o erro para ser enviado na URL
      header("Location: editar.php?id=" . $_GET['id'] . "&status=erro&mensagem=" . $erro);
      exit();
    }
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/x-icon" href="../assets/images/favicon.svg">
  <link rel="stylesheet" href="../assets/css/style.css">
  <link rel="stylesheet" href="../assets/css/pages/cadastrar.css">
  <script type="text/javascript" src="../assets/js/script.js" defer></script>
  <title>Cadastrar | IGE-TEC</title>
</head>
<body class="container">
  <nav id="sidebar">
    <ul>
      <li>
        <a href="../index.php">
          <img width="150" height="75" src="../assets/images/logo2.svg" alt="">
        </a>
        <button onclick="toggleSidebar()" id="toggle-btn" class="rotate">
          <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed"><path d="M440-240 200-480l240-240 56 56-183 184 183 184-56 56Zm264 0L464-480l240-240 56 56-183 184 183 184-56 56Z"/></svg>
        </button>
      </li>
      <li>
        <a href="../index.php">
          <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed"><path d="M240-200h120v-240h240v240h120v-360L480-740 240-560v360Zm-80 80v-480l320-240 320 240v480H520v-240h-80v240H160Zm320-350Z"/></svg>
          <span class="font-2-s">Home</span>
        </a>
      </li>
      <li class="active">
        <button onclick="toggleSubMenu(this)" class="dropdown-btn">
          <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed"><path d="M40-160v-112q0-34 17.5-62.5T104-378q62-31 126-46.5T360-440q66 0 130 15.5T616-378q29 15 46.5 43.5T680-272v112H40Zm720 0v-120q0-44-24.5-84.5T666-434q51 6 96 20.5t84 35.5q36 20 55 44.5t19 53.5v120H760ZM360-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47Zm400-160q0 66-47 113t-113 47q-11 0-28-2.5t-28-5.5q27-32 41.5-71t14.5-81q0-42-14.5-81T544-792q14-5 28-6.5t28-1.5q66 0 113 47t47 113ZM120-240h480v-32q0-11-5.5-20T580-306q-54-27-109-40.5T360-360q-56 0-111 13.5T140-306q-9 5-14.5 14t-5.5 20v32Zm240-320q33 0 56.5-23.5T440-640q0-33-23.5-56.5T360-720q-33 0-56.5 23.5T280-640q0 33 23.5 56.5T360-560Zm0 320Zm0-400Z"/></svg>
          <span class="font-2-s">Clientes</span>
          <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed"><path d="M480-344 240-584l56-56 184 184 184-184 56 56-240 240Z"/></svg>
        </button>
        <ul class="sub-menu">
          <div>
            <li class="font-2-s"><a href="./cadastrar.php">Cadastrar</a></li>
            <li class="font-2-s"><a href="./visualizar.php">Visualizar</a></li>
          </div>
        </ul>
      </li>
    </ul>
  </nav>
  <main>
    <section>
      <h1 class="font-1-xl">Edição do cliente: <?= htmlspecialchars($cliente['id_cliente']) ?></h1>
      <form class="cadastrar-form" method="POST" action="./editar.php?id=<?= htmlspecialchars($id) ?>">
        <div class="form-row">
          <fieldset>
            <label class="font-1-s" for="nome">Nome<span class="required"> *</span></label>
            <input id="nome" class="font-1-xs" type="text" name="nome" placeholder="Nome" value="<?= htmlspecialchars($cliente['nome']) ?>">
            <span id="error-nome" class="error-span font-1-xs" style="display: none;">Nome inválido!</span>
          </fieldset>
        </div>
        <div class="form-row">
          <fieldset>
            <label class="font-1-s" for="cpf">CPF</label>
            <input id="cpf" class="font-1-xs" type="text" name="cpf" placeholder="CPF" value="<?= htmlspecialchars($cliente['cpf']) ?>">
          </fieldset>
          <fieldset>
            <label class="font-1-s" for="cnpj">CNPJ</label>
            <input id="cnpj" class="font-1-xs" type="text" name="cnpj" placeholder="CNPJ" value="<?= htmlspecialchars($cliente['cnpj']) ?>">
          </fieldset>
        </div>
        <div class="form-row">
          <fieldset>
            <label class="font-1-s" for="email">Email</label>
            <input id="email" class="font-1-xs" type="email" name="email" placeholder="Email" value="<?= htmlspecialchars($cliente['email']) ?>">
            <span id="error-email" class="error-span font-1-xs" style="display: none;">Email inválido!</span>
          </fieldset>
          <fieldset> 
            <label class="font-1-s" for="telefone">Telefone<span class="required"> *</span></label>
            <input id="telefone" class="font-1-xs" type="text" name="telefone" placeholder="Telefone" value="<?= htmlspecialchars($cliente['telefone']) ?>">
            <span id="error-telefone" class="error-span font-1-xs" style="display: none;">Telefone inválido!</span>
          </fieldset>
        </div>
        <div class="form-row">
          <fieldset> 
            <label class="font-1-s" for="cep">CEP</label>
            <input id="cep" class="font-1-xs" type="text" name="cep" placeholder="CEP" value="<?= htmlspecialchars($cliente['cep']) ?>">
          </fieldset>
          <button id="buscar-cep-btn" onclick="buscarCEP()" class="buscar-btn font-1-xs" type="button">Buscar</button>
        </div>
        <div class="form-row">
          <fieldset>
            <label class="font-1-s" for="rua">Rua<span class="required"> *</span></label>
            <input id="rua" class="font-1-xs" type="text" name="rua" placeholder="Rua" value="<?= htmlspecialchars($cliente['rua']) ?>">
            <span id="error-rua" class="error-span font-1-xs" style="display: none;">Rua inválida!</span>
          </fieldset>
          <fieldset> 
            <label class="font-1-s" for="numero">Número<span class="required"> *</span></label>
            <input id="numero" class="font-1-xs" type="text" name="numero" placeholder="Número" value="<?= htmlspecialchars($cliente['numero']) ?>">
            <span id="error-numero" class="error-span font-1-xs" style="display: none;">Número inválido!</span>
          </fieldset>
        </div>
        <div class="form-row">
          <fieldset> 
            <label class="font-1-s" for="bairro">Bairro<span class="required"> *</span></label>
            <input id="bairro" class="font-1-xs" type="text" name="bairro" placeholder="Bairro" value="<?= htmlspecialchars($cliente['bairro']) ?>">
            <span id="error-bairro" class="error-span font-1-xs" style="display: none;">Bairro inválido!</span>
          </fieldset>
          <fieldset>
            <label class="font-1-s" for="cidade">Cidade<span class="required"> *</span></label>
            <input id="cidade" class="font-1-xs" type="text" name="cidade" placeholder="Cidade" value="<?= htmlspecialchars($cliente['cidade']) ?>">
            <span id="error-cidade" class="error-span font-1-xs" style="display: none;">Cidade inválida!</span>
          </fieldset>
          <fieldset> 
            <label class="font-1-s" for="estado">Estado<span class="required"> *</span></label>
            <input id="estado" class="font-1-xs" type="text" name="estado" placeholder="Estado" value="<?= htmlspecialchars($cliente['estado']) ?>">
            <span id="error-estado" class="error-span font-1-xs" style="display: none;">Estado inválido!</span>
          </fieldset>
        </div>
        <button type="submit" class="submit-btn font-1-s ">Editar</button>
      </form>
    </section>
    <script>
      const cadastrarForm = document.querySelector(".cadastrar-form");
      cadastrarForm?.addEventListener("submit", validateForm);

      // Verifica o parâmetro na URL
      const urlParams = new URLSearchParams(window.location.search);
      const status = urlParams.get('status');

      // Se o status for "sucesso", exibe o alerta e limpa o formulário
      if (status === 'sucesso') {
        alert("Cadastro editado com sucesso!");
        document.getElementById('formCadastro')?.reset();
        cadastrarForm?.addEventListener("submit", validateForm);
      } else if (status === 'erro') {
        alert("Ocorreu um erro ao editar. Tente novamente.");
        cadastrarForm?.addEventListener("submit", validateForm);
      }

      function validateForm(e) {
        e.preventDefault();

        const campos = [
          { id: "nome", obrigatorio: true, mensagem: "O campo Nome é obrigatório." },
          { id: "email", regex: /^[^\s@]+@[^\s@]+\.[^\s@]+$/, mensagem: "Insira um email válido." },
          { id: "telefone", obrigatorio: true, mensagem: "O campo telefone é obrigatório." },
          { id: "rua", obrigatorio: true, mensagem: "O campo rua é obrigatório." },
          { id: "numero", obrigatorio: true, mensagem: "O campo número é obrigatório." },
          { id: "bairro", obrigatorio: true, mensagem: "O campo bairro é obrigatório." },
          { id: "cidade", obrigatorio: true, mensagem: "O campo cidade é obrigatório." },
          { id: "estado", obrigatorio: true, mensagem: "O campo estado é obrigatório." },
        ];

        let isValid = true;

        campos.forEach(campo => {
          const input = document.getElementById(campo.id);
          const errorSpan = document.getElementById(`error-${campo.id}`);
          const value = input.value.trim();

          if (campo.obrigatorio && value === "") {
            errorSpan.textContent = campo.mensagem;
           
            errorSpan.style.display = "block";
            input.classList.add("input-error");
            isValid = false;
          } else if (campo.regex && !campo.regex.test(value)) {
            
            errorSpan.textContent = campo.mensagem;
            errorSpan.style.display = "block";
            input.classList.add("input-error");
            isValid = false;
          } else {
            errorSpan.style.display = "none";
            input.classList.remove("input-error");
          }
        });

        if (!isValid) {
          return false;
        }

        e.target.submit();
      }

      document.querySelectorAll("input").forEach(input => {
        input.addEventListener("input", function () {
          const errorSpan = document.getElementById(`error-${input.id}`);
          const campo = input.value.trim();

          if (campo !== "") {
            if(errorSpan) {
              errorSpan.style.display = "none";
              input.classList.remove("input-error");
            }
          }
        });
      });

      function buscarCEP() {
        const cep = document.getElementById('cep').value.trim();

        if (!/^\d{8}$/.test(cep)) {
          alert('Por favor, insira um CEP válido com 8 dígitos.');
          return;
        }

        fetch(`https://viacep.com.br/ws/${cep}/json/`)
          .then(response => {
            if (!response.ok) {
              throw new Error('Erro ao buscar CEP.');
            }
            return response.json();
          })
          .then(data => {
            if (data.erro) {
              alert('CEP não encontrado!');
              return;
            }

            document.getElementById('rua').value = data.logradouro || '';
            document.getElementById('bairro').value = data.bairro || '';
            document.getElementById('cidade').value = data.localidade || '';
            document.getElementById('estado').value = data.uf || '';
          })
          .catch(error => {
            console.error('Erro:', error);
            alert('Não foi possível buscar o CEP. Tente novamente mais tarde.');
          });

          validateForm();
      }
    </script>
  </main>
</body>
</html>