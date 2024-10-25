<?php
session_start();
include 'db_connection.php';

// Verifica se o usuário está logado e é admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel Administrativo</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>Painel Administrativo</h1>
            <ul>
                <li><a href="manage_rooms.php">Gerenciar Salas</a></li>
                <li><a href="manage_users.php">Gerenciar Usuários</a></li>
                <li><a href="manage_bookings.php">Ver Agendamentos</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
    </header>
</body>
</html>
