<?php
session_start();
include 'db_connection.php';

// Verifica se o usuário está logado e é admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome_sala = $_POST['nome'];
    $descricao = $_POST['descricao'];

    $sql = "INSERT INTO rooms (nome, descricao) VALUES ('$nome_sala', '$descricao')";
    if ($conn->query($sql) === TRUE) {
        echo "Sala adicionada com sucesso!";
    } else {
        echo "Erro ao adicionar a sala: " . $conn->error;
    }
}

$salas = $conn->query("SELECT * FROM rooms");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Salas</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>Gerenciar Salas</h1>
            <ul>
                <li><a href="index.php">Voltar ao Painel</a></li>
            </ul>
        </div>
    </header>
    <div class="container">
        <form method="post" action="manage_rooms.php">
            Nome da Sala: <input type="text" name="nome" required><br>
            Descrição: <textarea name="descricao" required></textarea><br>
            <input type="submit" value="Adicionar Sala">
        </form>

        <h2>Salas Existentes</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Descrição</th>
            </tr>
            <?php while ($sala = $salas->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $sala['id']; ?></td>
                <td><?php echo $sala['nome']; ?></td>
                <td><?php echo $sala['descricao']; ?></td>
            </tr>
            <?php } ?>
        </table>
    </div>
</body>
</html>
