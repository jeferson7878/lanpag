<?php
session_start();
include 'db_connection.php';

// Verifica se o usuário está logado e é admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

$agendamentos = $conn->query("SELECT bookings.*, rooms.nome AS room_nome, users.nome AS user_nome FROM bookings JOIN rooms ON bookings.room_id = rooms.id JOIN users ON bookings.user_id = users.id");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Agendamentos</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>Gerenciar Agendamentos</h1>
            <ul>
                <li><a href="index.php">Voltar ao Painel</a></li>
            </ul>
        </div>
    </header>
    <div class="container table-container">
        <table>
            <tr>
                <th>ID</th>
                <th>Sala</th>
                <th>Usuário</th>
                <th>Data</th>
                <th>Horário Início</th>
                <th>Horário Fim</th>
                <th>Status</th>
            </tr>
            <?php while ($agendamento = $agendamentos->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $agendamento['id']; ?></td>
                <td><?php echo $agendamento['room_nome']; ?></td>
                <td><?php echo $agendamento['user_nome']; ?></td>
                <td><?php echo $agendamento['data']; ?></td>
                <td><?php echo $agendamento['horario_inicio']; ?></td>
                <td><?php echo $agendamento['horario_fim']; ?></td>
                <td><?php echo $agendamento['status']; ?></td>
            </tr>
            <?php } ?>
        </table>
    </div>
</body>
</html>
