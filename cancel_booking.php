<?php
session_start();
include 'db_connection.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['id']) || $_SESSION['role'] != 'user') {
    header("Location: login.php");
    exit();
}

$booking_id = $_GET['id'];

$sql = "UPDATE bookings SET status = 'cancelado' WHERE id = $booking_id";
if ($conn->query($sql) === TRUE) {
    echo "Agendamento cancelado com sucesso!";
    header("Location: my_bookings.php");
} else {
    echo "Erro ao cancelar o agendamento: " . $conn->error;
}
?>
