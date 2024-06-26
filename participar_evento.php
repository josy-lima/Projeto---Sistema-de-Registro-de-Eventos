<?php
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "eventos";

// Criar a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Inicializar variáveis para mensagens de sucesso/erro
$mensagem = "";

// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['idEvento'], $_POST['nomeUsuario'], $_POST['emailUsuario'])) {
        $idEvento = $_POST['idEvento'];
        $nomeUsuario = $_POST['nomeUsuario'];
        $emailUsuario = $_POST['emailUsuario'];

        // Preparar e vincular
        $stmt = $conn->prepare("INSERT INTO participacoes (evento_id, nome, email) VALUES (?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("iss", $idEvento, $nomeUsuario, $emailUsuario);

            // Executar
            if ($stmt->execute()) {
                $mensagem = "Inscrição realizada com sucesso!";
            } else {
                $mensagem = "Erro: " . $stmt->error;
            }

            $stmt->close();
        } else {
            $mensagem = "Erro na preparação da consulta: " . $conn->error;
        }
    } else {
        $mensagem = "Parâmetros inválidos.";
    }
}

// Buscar detalhes dos eventos
$sql = "SELECT id, nomeEvento FROM eventos";
$result = $conn->query($sql);

// Obter todos os eventos
$eventos = [];
if ($result->num_rows > 0) {
    while ($evento = $result->fetch_assoc()) {
        $eventos[] = $evento;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="participar_evento.css">
  <title>Participar do Evento</title>
</head>
<body>
  <header>
    <h1>Participar do Evento</h1>
  </header>

  <main>
    <?php if (!empty($mensagem)): ?>
        <p><?php echo htmlspecialchars($mensagem); ?></p>
    <?php endif; ?>

    <form action="participar_evento.php" method="post">
      <label for="idEvento">Escolha o Evento</label>
      <select id="idEvento" name="idEvento" required>
        <?php foreach ($eventos as $evento): ?>
            <option value="<?php echo htmlspecialchars($evento['id']); ?>"><?php echo htmlspecialchars($evento['nomeEvento']); ?></option>
        <?php endforeach; ?>
      </select>

      <label for="nomeUsuario">Nome</label>
      <input type="text" id="nomeUsuario" name="nomeUsuario" required placeholder="Seu nome">

      <label for="emailUsuario">Email</label>
      <input type="email" id="emailUsuario" name="emailUsuario" required placeholder="Seu email">

      <button type="submit">Participar</button>
    </form>
  </main>
</body>
</html>
