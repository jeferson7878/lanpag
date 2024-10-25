<?php
session_start();
include 'db_connection.php';

// Verifica se o usuário está logado e é user
if (!isset($_SESSION['id']) || $_SESSION['role'] != 'user') {
    header("Location: login.php");
    exit();
}

$rooms = $conn->query("SELECT * FROM rooms");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $room_id = $_POST['room_id'];
    $data = $_POST['data'];
    $horario_inicio = $_POST['horario_inicio'];
    $horario_fim = $_POST['horario_fim'];
    $user_id = $_SESSION['id'];

    $sql = "INSERT INTO bookings (user_id, room_id, data, horario_inicio, horario_fim) VALUES ('$user_id', '$room_id', '$data', '$horario_inicio', '$horario_fim')";
    if ($conn->query($sql) === TRUE) {
        echo "Sala agendada com sucesso!";
    } else {
        echo "Erro ao agendar a sala: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Agendar Sala</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>Agendar Sala</h1>
        </div>
    </header>
    <div class="container">
        <form method="post" action="book_room.php">
            Sala: 
            <select name="room_id" required>
                <?php while ($room = $rooms->fetch_assoc()) { ?>
                <option value="<?php echo $room['id']; ?>"><?php echo $room['nome']; ?></option>
                <?php } ?>
            </select><br>
            Data: <input type="date" name="data" required><br>
            Horário de Início: <input type="time" name="horario_inicio" required><br>
            Horário de Fim: <input type="time" name="horario_fim" required><br>
            <input type="submit" value="Agendar">
        </form>
        <a href="my_bookings.php">Meus Agendamentos</a> | <a href="logout.php">Logout</a>
    </div>
</body>
</html>
