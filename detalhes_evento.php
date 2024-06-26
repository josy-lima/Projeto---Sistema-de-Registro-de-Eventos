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

// Verificar se os parâmetros ids existem na URL, senão usar IDs padrão
$idsEvento = isset($_GET['ids']) ? explode(',', $_GET['ids']) : [1, 2, 3, 4];

// Preparar a consulta para buscar múltiplos eventos
$placeholders = implode(',', array_fill(0, count($idsEvento), '?'));
$sql = "SELECT * FROM eventos WHERE id IN ($placeholders)";
$stmt = $conn->prepare($sql);

// Criar os tipos de dados para bind_param
$types = str_repeat('i', count($idsEvento));
$stmt->bind_param($types, ...$idsEvento);
$stmt->execute();
$result = $stmt->get_result();

// Obter todos os eventos
$eventos = [];
while ($evento = $result->fetch_assoc()) {
    $eventos[] = $evento;
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="detalhes_evento.css">
  <title>Detalhes dos Eventos</title>
</head>
<body>
  <header>
    <h1>Detalhes dos Eventos</h1>
  </header>

  <main>
    <?php
    if (!empty($eventos)) {
        foreach ($eventos as $evento) {
            echo "<h2>" . htmlspecialchars($evento["nomeEvento"]) . "</h2>";
            echo "<p>Data: " . htmlspecialchars($evento["dataEvento"]) . "</p>";
            echo "<p>Hora de Início: " . htmlspecialchars($evento["horaInicio"]) . "</p>";
            echo "<p>Hora de Término: " . htmlspecialchars($evento["horaFim"]) . "</p>";
            echo "<p>Local: " . htmlspecialchars($evento["localEvento"]) . "</p>";
            echo "<p>Descrição: " . htmlspecialchars($evento["descricaoEvento"]) . "</p>";
            echo "<p>Organizador: " . htmlspecialchars($evento["organizadorEvento"]) . "</p>";
            echo "<a href='participar_evento.php?id=" . htmlspecialchars($evento["id"]) . "'>Participar deste evento</a><br><br>";
        }
    } else {
        echo "Nenhum evento encontrado.";
    }

    // Fechar a declaração preparada e a conexão
    if (isset($stmt)) {
        $stmt->close();
    }
    $conn->close();
    ?>
  </main>
</body>
</html>
