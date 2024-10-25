<?php
session_start();
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($senha, $user['senha'])) {
            $_SESSION['id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            // Redirecionar com base na função
            if ($user['role'] == 'admin') {
                header("Location: index.php");
            } else {
                header("Location: book_room.php");
            }
            exit();
        } else {
            echo "Senha incorreta!";
        }
    } else {
        echo "Usuário não encontrado!";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>Login do Sistema de Agendamento</h1>
        </div>
    </header>
    <div class="container">
        <form method="post" action="login.php">
            <input type="email" name="email" placeholder="Digite seu email" required><br>
            <input type="password" name="senha" placeholder="Digite sua senha" required><br>
            <input type="submit" value="Entrar">
        </form>
        <a href="register.php">Registrar-se</a>
    </div>
</body>
</html>
