<?php
session_start();
include 'db_connection.php';

// Verifica se o usuário está logado e é admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

$usuarios = $conn->query("SELECT * FROM users");

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Usuários</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>Gerenciar Usuários</h1>
            <ul>
                <li><a href="index.php">Voltar ao Painel</a></li>
            </ul>
        </div>
    </header>
    <div class="container table-container">
        <table>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Role</th>
                <th>Ações</th>
            </tr>
            <?php while ($usuario = $usuarios->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $usuario['id']; ?></td>
                <td><?php echo $usuario['nome']; ?></td>
                <td><?php echo $usuario['email']; ?></td>
                <td><?php echo $usuario['role']; ?></td>
                <td>
                    <!-- Aqui você pode adicionar funcionalidades como editar ou deletar o usuário -->
                </td>
            </tr>
            <?php } ?>
        </table>
    </div>
</body>
</html>
