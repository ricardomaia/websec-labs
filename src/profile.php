<?php
defined('APP') or die('Access denied');

// Iniciar sessão se ainda não foi iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verificar se o usuário está logado
if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    // Redirecionar para login ou mostrar mensagem de erro
    header('Location: /?page=login');
    exit();
}

include("db.php");
?>
<h3>Profile</h3>
<?php
// Usar prepared statement para evitar SQL injection
$stmt = $mysqli->prepare("SELECT username, email FROM user WHERE username = ?");
$stmt->bind_param("s", $_SESSION['username']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
?>
    <form class="loginForm" action="/?page=delete-account" method="post">
        <div class="mb-3">
            <p>Username: <?php echo htmlspecialchars($row['username']); ?></p>
            <p>Email: <?php echo htmlspecialchars($row['email']); ?></p>
        </div>
        <button type="submit" class="btn btn-primary">Excluir minha conta</button>
    </form>
<?php
} else {
    echo "<p>Usuário não encontrado.</p>";
}

$stmt->close();
$mysqli->close();
?>