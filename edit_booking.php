<?php
session_start();
include 'db_connection.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['id']) || $_SESSION['role'] != 'user') {
    header("Location: login.php");
    exit();
}

$booking_id = $_GET['id'];
$booking = $conn->query("SELECT * FROM bookings WHERE id = $booking_id")->fetch_assoc();
$rooms = $conn->query("SELECT * FROM rooms");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $room_id = $_POST['room_id'];
    $data = $_POST['data'];
    $horario_inicio = $_POST['horario_inicio'];
    $horario_fim = $_POST['horario_fim'];

    $sql = "UPDATE bookings SET room_id = '$room_id', data = '$data', horario_inicio = '$horario_inicio', horario_fim = '$horario_fim' WHERE id = $booking_id";
    if ($conn->query($sql) === TRUE) {
        echo "Agendamento atualizado com sucesso!";
        header("Location: my_bookings.php");
    } else {
        echo "Erro ao atualizar o agendamento: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Agendamento</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>Editar Agendamento</h1>
        </div>
    </header>
    <div class="container">
        <form method="post" action="edit_booking.php?id=<?php echo $booking_id; ?>">
            Sala: 
            <select name="room_id" required>
                <?php while ($room = $rooms->fetch_assoc()) { ?>
                <option value="<?php echo $room['id']; ?>" <?php if ($room['id'] == $booking['room_id']) echo 'selected'; ?>><?php echo $room['nome']; ?></option>
                <?php } ?>
            </select><br>
            Data: <input type="date" name="data" value="<?php echo $booking['data']; ?>" required><br>
            Horário de Início: <input type="time" name="horario_inicio" value="<?php echo $booking['horario_inicio']; ?>" required><br>
            Horário de Fim: <input type="time" name="horario_fim" value="<?php echo $booking['horario_fim']; ?>" required><br>
            <input type="submit" value="Atualizar">
        </form>
        <a href="my_bookings.php">Voltar</a>
    </div>
</body>
</html>
