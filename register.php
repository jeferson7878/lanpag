<?php
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
    
    $sql = "INSERT INTO users (nome, email, senha, role) VALUES ('$nome', '$email', '$senha', 'user')";
    if ($conn->query($sql) === TRUE) {
        echo "Registro bem-sucedido!";
        header("Location: login.php");
    } else {
        echo "Erro ao registrar: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>Registrar-se</h1>
        </div>
    </header>
    <div class="container">
        <form method="post" action="register.php">
            Nome: <input type="text" name="nome" required><br>
            Email: <input type="email" name="email" required><br>
            Senha: <input type="password" name="senha" required><br>
            <input type="submit" value="Registrar">
        </form>
        <a href="login.php">Voltar ao Login</a>
    </div>
</body>
</html>
