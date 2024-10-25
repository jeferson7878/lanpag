<?php
session_start();
include 'db_connection.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['id']) || $_SESSION['role'] != 'user') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['id'];
$bookings = $conn->query("SELECT bookings.*, rooms.nome as room_nome FROM bookings JOIN rooms ON bookings.room_id = rooms.id WHERE bookings.user_id = $user_id");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Meus Agendamentos</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>Meus Agendamentos</h1>
        </div>
    </header>
    <div class="container table-container">
        <table>
            <tr>
                <th>ID</th>
                <th>Sala</th>
                <th>Data</th>
                <th>Horário Início</th>
                <th>Horário Fim</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
            <?php while ($booking = $bookings->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $booking['id']; ?></td>
                <td><?php echo $booking['room_nome']; ?></td>
                <td><?php echo $booking['data']; ?></td>
                <td><?php echo $booking['horario_inicio']; ?></td>
                <td><?php echo $booking['horario_fim']; ?></td>
                <td><?php echo $booking['status']; ?></td>
                <td>
                    <?php if ($booking['status'] == 'ativo') { ?>
                        <a href="edit_booking.php?id=<?php echo $booking['id']; ?>">Editar</a> | 
                        <a href="cancel_booking.php?id=<?php echo $booking['id']; ?>" onclick="return confirm('Tem certeza que deseja cancelar?')">Cancelar</a>
                    <?php } else { echo "N/A"; } ?>
                </td>
            </tr>
            <?php } ?>
        </table>
        <a href="book_room.php">Agendar Nova Sala</a> | <a href="logout.php">Logout</a>
    </div>
</body>
</html>
