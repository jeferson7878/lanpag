<?php
session_start();
require 'db_connection.php';

// Verifica se o usuário está autenticado
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
// Processamento do formulário de cadastro de sala
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['room_name'])) {
    $room_name = $_POST['room_name'];
    $booking_duration = $_POST['booking_duration'];

    // Inserindo a nova sala no banco de dados
    $sql = "INSERT INTO rooms (room_name, booking_duration) VALUES ('$room_name', '$booking_duration')";
    
    if ($conn->query($sql) === TRUE) {
        $room_success_message = "Sala cadastrada com sucesso!";
    } else {
        $room_error_message = "Erro ao cadastrar sala: " . $conn->error;
    }
}

// Função para verificar a duração do agendamento
function checkBookingDuration($start_time, $end_time) {
    $start = strtotime($start_time);
    $end = strtotime($end_time);
    $duration = ($end - $start) / 60; // Convertendo para minutos
    return $duration <= 240; // Máximo de 4 horas (240 minutos)
}

// Função para obter o próximo agendamento e calcular a contagem regressiva
function getNextBookingCountdown($conn) {
    $sql = "SELECT booking_date, start_time FROM bookings WHERE booking_date >= CURDATE() ORDER BY booking_date, start_time LIMIT 1";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $next_booking_time = strtotime($row['booking_date'] . ' ' . $row['start_time']);
        $current_time = time();
        $time_diff = $next_booking_time - $current_time;

        if ($time_diff > 0) {
            $hours = floor($time_diff / 3600);
            $minutes = floor(($time_diff % 3600) / 60);
            $seconds = $time_diff % 60;
            return sprintf("%02dh %02dm %02ds", $hours, $minutes, $seconds);
        } else {
            return "Ocupados acima";
        }
    }
    return "Nenhum agendamento futuro.";
}

// Processamento do formulário de agendamento
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['booking_date'])) {
    $room_id = $_POST['room_id'];
    $booking_date = $_POST['booking_date'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $booked_by = $_POST['booked_by'];

    // Verifica se a duração do agendamento é válida
    if (!checkBookingDuration($start_time, $end_time)) {
        $error_message = "Erro: A duração máxima permitida é de 4 horas por período.";
    } else {
        // Verificando se o horário está disponível
        $sql = "SELECT * FROM bookings WHERE room_id = '$room_id' AND booking_date = '$booking_date' AND 
                ('$start_time' < end_time AND '$end_time' > start_time)";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $error_message = "Erro: A sala já está reservada para o horário selecionado.";
        } else {
            // Inserindo a reserva no banco de dados
            $sql = "INSERT INTO bookings (room_id, booking_date, start_time, end_time, booked_by) 
                    VALUES ('$room_id', '$booking_date', '$start_time', '$end_time', '$booked_by')";

            if ($conn->query($sql) === TRUE) {
                $success_message = "Reserva realizada com sucesso!";
            } else {
                $error_message = "Erro ao reservar: " . $conn->error;
            }
        }
    }
}

// Função para converter o horário para um formato legível
function formatTime($time) {
    return date('H:i', strtotime($time));
}

// Função para obter os agendamentos
function getBookings($conn) {
    $sql = "SELECT bookings.id, rooms.room_name, bookings.booking_date, bookings.start_time, bookings.end_time, bookings.booked_by 
            FROM bookings 
            JOIN rooms ON bookings.room_id = rooms.id 
            ORDER BY bookings.booking_date, bookings.start_time";
    return $conn->query($sql);
}

$bookings = getBookings($conn);
$next_booking_countdown = getNextBookingCountdown($conn); // Obter o cronômetro do próximo agendamento
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendador de Salas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin: auto;
        }

        h1, h2 {
            text-align: center;
            color: #333;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }

        input[type="text"],
        input[type="number"],
        input[type="date"],
        input[type="time"],
        select {
            width: calc(100% - 22px);
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .message {
            text-align: center;
            font-size: 14px;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .section {
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ccc;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f7f7f7;
        }

        #countdown {
            text-align: center;
            font-size: 18px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Agendador de Salas</h1>
        
        <!-- Mensagens de Sucesso e Erro para Cadastro de Sala -->
        <?php if (isset($room_success_message)) { ?>
            <div class="message success"><?php echo $room_success_message; ?></div>
        <?php } ?>
        
        <?php if (isset($room_error_message)) { ?>
            <div class="message error"><?php echo $room_error_message; ?></div>
        <?php } ?>

        <!-- Formulário de Cadastro de Sala -->
        <div class="section">
            <h2>Cadastrar Nova Sala</h2>
            <form action="" method="post">
                <div class="form-group">
                    <label for="room_name">Nome da Sala:</label>
                    <input type="text" id="room_name" name="room_name" required>
                </div>
                <div class="form-group">
                    <label for="booking_duration">Duração do Agendamento (minutos):</label>
                    <input type="number" id="booking_duration" name="booking_duration" min="15" step="15" value="30" required>
                </div>
                <input type="submit" value="Cadastrar Sala">
            </form>
        </div>

        <!-- Mensagens de Sucesso e Erro para Agendamento -->
        <?php if (isset($success_message)) { ?>
            <div class="message success"><?php echo $success_message; ?></div>
        <?php } ?>
        
        <?php if (isset($error_message)) { ?>
            <div class="message error"><?php echo $error_message; ?></div>
        <?php } ?>

        <!-- Formulário de Agendamento de Sala -->
        <div class="section">
            <h2>Agendar uma Sala</h2>
            <form action="" method="post">
                <div class="form-group">
                    <label for="room">Selecione a Sala:</label>
                    <select name="room_id" id="room">
                        <?php
                        // Selecionando as salas disponíveis do banco de dados
                        $sql = "SELECT * FROM rooms";
                        $result = $conn->query($sql);
                        while($row = $result->fetch_assoc()) {
                            echo "<option value='".$row['id']."'>".$row['room_name']." (Duração: ".$row['booking_duration']." min)</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="date">Data:</label>
                    <input type="date" id="date" name="booking_date" required>
                </div>

                <div class="form-group">
                    <label for="start_time">Hora de Início:</label>
                    <input type="time" id="start_time" name="start_time" required>
                </div>

                <div class="form-group">
                    <label for="end_time">Hora de Término:</label>
                    <input type="time" id="end_time" name="end_time" required>
                </div>

                <div class="form-group">
                    <label for="booked_by">Reservado por:</label>
                    <input type="text" id="booked_by" name="booked_by" required>
                </div>

                <input type="submit" value="Agendar Sala">
            </form>
        </div>

        <!-- Visualização de Agendamentos -->
        <div class="section">
            <h2>Agendamentos Existentes</h2>
            <table>
                <tr>
                    <th>Sala</th>
                    <th>Data</th>
                    <th>Hora de Início</th>
                    <th>Hora de Término</th>
                    <th>Reservado por</th>
                </tr>
                <?php while($row = $bookings->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['room_name']; ?></td>
                        <td><?php echo $row['booking_date']; ?></td>
                        <td><?php echo formatTime($row['start_time']); ?></td>
                        <td><?php echo formatTime($row['end_time']); ?></td>
                        <td><?php echo $row['booked_by']; ?></td>
                    </tr>
                <?php } ?>
            </table>
        </div>

    
        <div id="countdown"><?php echo $next_booking_countdown; ?></div>

    </div>
</body>
</html>

<?php
$conn->close();
?>
