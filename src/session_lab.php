<?php
defined('APP') or die('Access denied');
include("db.php");

// Start session for this lab (check if not already started)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<div class="container mt-4">
    <h3>🔐 Session Management Lab</h3>

    <div class="alert alert-warning" role="alert">
        <strong>⚠️ Aviso:</strong> Esta página contém vulnerabilidades de gerenciamento de sessão para fins educacionais!
    </div>

    <!-- Simple Login Form -->
    <div class="card mb-4">
        <div class="card-header">
            <h5>1. Login com Sessão Vulnerável</h5>
        </div>
        <div class="card-body">
            <?php
            // Handle login
            if(isset($_POST['action']) && $_POST['action'] == 'login') {
                $username = $_POST['username'];
                $password = md5($_POST['password']); // Vulnerable: MD5 hash

                $query = "SELECT * FROM user WHERE username = '$username' AND password = '$password'";
                $result = $mysqli->query($query);

                if($result && $result->num_rows > 0) {
                    $user = $result->fetch_assoc();

                    // Vulnerable session management
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['role'] = ($user['username'] == 'admin') ? 'admin' : 'user';
                    $_SESSION['login_time'] = time();

                    echo '<div class="alert alert-success">Login realizado com sucesso! Session ID: ' . session_id() . '</div>';
                } else {
                    echo '<div class="alert alert-danger">Credenciais inválidas!</div>';
                }
            }

            // Handle logout
            if(isset($_POST['action']) && $_POST['action'] == 'logout') {
                session_destroy();
                echo '<div class="alert alert-info">Logout realizado!</div>';
            }
            ?>

            <?php if(!isset($_SESSION['user_id'])): ?>
                <form method="POST" action="">
                    <input type="hidden" name="page" value="session-lab">
                    <input type="hidden" name="action" value="login">

                    <div class="mb-3">
                        <label for="username" class="form-label">Usuário:</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Senha:</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Login</button>
                </form>

                <div class="mt-3">
                    <small class="text-muted">
                        <strong>Credenciais válidas:</strong><br>
                        admin / password123<br>
                        foo / test<br>
                        bar / admin
                    </small>
                </div>
            <?php else: ?>
                <div class="alert alert-info">
                    <strong>Usuário logado:</strong> <?php echo $_SESSION['username']; ?><br>
                    <strong>Role:</strong> <?php echo $_SESSION['role']; ?><br>
                    <strong>Session ID:</strong> <code><?php echo session_id(); ?></code><br>
                    <strong>Login Time:</strong> <?php echo date('Y-m-d H:i:s', $_SESSION['login_time']); ?>
                </div>

                <form method="POST" action="" class="d-inline">
                    <input type="hidden" name="page" value="session-lab">
                    <input type="hidden" name="action" value="logout">
                    <button type="submit" class="btn btn-secondary">Logout</button>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <!-- Session Hijacking Demo -->
    <div class="card mb-4">
        <div class="card-header">
            <h5>2. Session Hijacking Demonstration</h5>
        </div>
        <div class="card-body">
            <p>Esta seção demonstra como um atacante pode sequestrar sessões:</p>

            <div class="mb-3">
                <strong>Session ID atual:</strong>
                <code id="currentSessionId"><?php echo session_id(); ?></code>
                <button class="btn btn-sm btn-outline-secondary" onclick="copySessionId()">Copiar</button>
            </div>

            <div class="mb-3">
                <label for="hijackSessionId" class="form-label">Testar com Session ID:</label>
                <div class="input-group">
                    <input type="text" class="form-control" id="hijackSessionId" placeholder="Cole um Session ID aqui">
                    <button class="btn btn-warning" onclick="hijackSession()">Simular Hijack</button>
                </div>
            </div>

            <div class="alert alert-warning">
                <strong>⚠️ Vulnerabilidades identificadas:</strong>
                <ul class="mb-0">
                    <li>Session ID previsível</li>
                    <li>Não há regeneração de session ID após login</li>
                    <li>Session ID exposto na URL/logs</li>
                    <li>Não há validação de IP/User-Agent</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Session Fixation Demo -->
    <div class="card mb-4">
        <div class="card-header">
            <h5>3. Session Fixation Attack</h5>
        </div>
        <div class="card-body">
            <p>Demonstração de como um atacante pode fixar o ID de sessão:</p>

            <div class="mb-3">
                <a href="?page=session-lab&PHPSESSID=ATTACKER_CONTROLLED_SESSION" class="btn btn-danger">
                    Link com Session ID Fixo
                </a>
            </div>

            <p class="text-muted">
                <small>
                    O link acima tenta definir um session ID controlado pelo atacante.
                    Se a vítima fizer login após clicar no link, o atacante conhecerá o session ID.
                </small>
            </p>

            <?php if(isset($_GET['PHPSESSID'])): ?>
                <div class="alert alert-danger">
                    <strong>🚨 Session Fixation Detectada!</strong><br>
                    Tentativa de definir session ID: <code><?php echo htmlspecialchars($_GET['PHPSESSID']); ?></code>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Admin Panel (Privilege Escalation) -->
    <?php if(isset($_SESSION['user_id'])): ?>
    <div class="card mb-4">
        <div class="card-header">
            <h5>4. Painel Administrativo (Teste de Privilégios)</h5>
        </div>
        <div class="card-body">
            <?php if($_SESSION['role'] == 'admin'): ?>
                <div class="alert alert-success">
                    <strong>✅ Acesso Administrativo Concedido!</strong><br>
                    Você tem privilégios de administrador.
                </div>

                <h6>Funções Administrativas:</h6>
                <ul>
                    <li>Visualizar todos os usuários</li>
                    <li>Modificar configurações do sistema</li>
                    <li>Acessar logs de segurança</li>
                    <li>Gerenciar sessões ativas</li>
                </ul>

                <!-- Display all users for admin -->
                <div class="mt-3">
                    <h6>👥 Usuários Registrados:</h6>
                    <?php
                    $result = $mysqli->query("SELECT id, username, email FROM user");
                    if($result) {
                        echo '<div class="table-responsive">';
                        echo '<table class="table table-sm">';
                        echo '<thead><tr><th>ID</th><th>Username</th><th>Email</th></tr></thead>';
                        echo '<tbody>';
                        while($row = $result->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td>' . $row['id'] . '</td>';
                            echo '<td>' . $row['username'] . '</td>';
                            echo '<td>' . $row['email'] . '</td>';
                            echo '</tr>';
                        }
                        echo '</tbody></table></div>';
                    }
                    ?>
                </div>
            <?php else: ?>
                <div class="alert alert-danger">
                    <strong>❌ Acesso Negado!</strong><br>
                    Você não tem privilégios administrativos.
                </div>

                <div class="mt-3">
                    <small class="text-muted">
                        <strong>Teste de Privilege Escalation:</strong><br>
                        Tente modificar o valor da sessão "role" para "admin" usando ferramentas do navegador
                        ou manipulação de cookies.
                    </small>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Security Best Practices -->
    <div class="card mb-4">
        <div class="card-header bg-success text-white">
            <h5>✅ Como Proteger Sessões</h5>
        </div>
        <div class="card-body">
            <h6>1. Configuração Segura de Sessão:</h6>
            <pre><code>// PHP
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => 'yoursite.com',
    'secure' => true,      // HTTPS only
    'httponly' => true,    // No JavaScript access
    'samesite' => 'Strict' // CSRF protection
]);

ini_set('session.use_strict_mode', 1);
ini_set('session.cookie_httponly', 1);
ini_set('session.use_trans_sid', 0);</code></pre>

            <h6>2. Regenerar Session ID:</h6>
            <pre><code>// Após login bem-sucedido
session_regenerate_id(true);</code></pre>

            <h6>3. Validação de Sessão:</h6>
            <pre><code>// Verificar IP e User-Agent
if ($_SESSION['ip'] !== $_SERVER['REMOTE_ADDR'] ||
    $_SESSION['user_agent'] !== $_SERVER['HTTP_USER_AGENT']) {
    session_destroy();
    // Redirect to login
}</code></pre>

            <h6>4. Timeout de Sessão:</h6>
            <pre><code>// Verificar tempo de inatividade
if (time() - $_SESSION['last_activity'] > 1800) { // 30 minutos
    session_destroy();
    // Redirect to login
}
$_SESSION['last_activity'] = time();</code></pre>
        </div>
    </div>
</div>

<script>
function copySessionId() {
    const sessionId = document.getElementById('currentSessionId').textContent;
    navigator.clipboard.writeText(sessionId).then(function() {
        alert('Session ID copiado para a área de transferência!');
    });
}

function hijackSession() {
    const newSessionId = document.getElementById('hijackSessionId').value;
    if(newSessionId) {
        // This is just for demonstration - in real attacks this would be done differently
        alert('Em um ataque real, o atacante usaria o session ID: ' + newSessionId + ' para acessar a conta da vítima.');

        // You could demonstrate by setting a cookie (though this won't work due to HttpOnly in secure configs)
        document.cookie = 'PHPSESSID=' + newSessionId + '; path=/';

        alert('Tentativa de definir cookie PHPSESSID. Recarregue a página para testar.');
    } else {
        alert('Por favor, insira um Session ID para testar.');
    }
}

// Display current session info
window.onload = function() {
    console.log('Current session ID:', '<?php echo session_id(); ?>');
    console.log('Session data:', <?php echo json_encode($_SESSION); ?>);
}
</script>