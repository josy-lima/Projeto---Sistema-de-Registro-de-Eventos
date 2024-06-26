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

// Buscar eventos
$sql = "SELECT id, nomeEvento, dataEvento, localEvento FROM eventos";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="listar_eventos.css">
  <title>Lista de Eventos</title>
</head>
<body>
  <header>
    <h1>Lista de Eventos</h1>
  </header>

  <main>
    <?php
    if ($result->num_rows > 0) {
        echo "<ul>";
        while($row = $result->fetch_assoc()) {
            echo "<li><a href='detalhes_evento.php?id=" . htmlspecialchars($row["id"]) . "'>" . htmlspecialchars($row["nomeEvento"]) . " - " . htmlspecialchars($row["dataEvento"]) . " - " . htmlspecialchars($row["localEvento"]) . "</a></li>";
        }
        echo "</ul>";
    } else {
        echo "Nenhum evento encontrado.";
    }
    $conn->close();
    ?>
  </main>
</body>
</html>
