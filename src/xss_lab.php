<?php
defined('APP') or die('Access denied');
include("db.php");
?>

<div class="container mt-4">
    <h3>🚨 XSS (Cross-Site Scripting) Lab</h3>

    <div class="alert alert-warning" role="alert">
        <strong>⚠️ Aviso:</strong> Esta página é intencionalmente vulnerável para fins educacionais!
    </div>

    <!-- Reflected XSS -->
    <div class="card mb-4">
        <div class="card-header">
            <h5>1. Reflected XSS</h5>
        </div>
        <div class="card-body">
            <p>Digite seu nome para ver uma mensagem personalizada:</p>
            <form method="GET" action="">
                <input type="hidden" name="page" value="xss-lab">
                <div class="input-group">
                    <input type="text" class="form-control" name="name" placeholder="Seu nome"
                           value="<?php echo isset($_GET['name']) ? $_GET['name'] : ''; ?>">
                    <button class="btn btn-primary" type="submit">Enviar</button>
                </div>
            </form>

            <?php if(isset($_GET['name']) && !empty($_GET['name'])): ?>
                <div class="mt-3 p-3 bg-light border rounded">
                    <strong>Olá, <?php echo $_GET['name']; ?>! Bem-vindo ao laboratório!</strong>
                </div>

                <!-- Payloads de exemplo -->
                <div class="mt-3">
                    <small class="text-muted">
                        <strong>Payloads de teste:</strong><br>
                        <code>&lt;script&gt;alert('XSS')&lt;/script&gt;</code><br>
                        <code>&lt;img src=x onerror=alert('XSS')&gt;</code><br>
                        <code>&lt;svg onload=alert('XSS')&gt;</code>
                    </small>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Stored XSS via Comments -->
    <div class="card mb-4">
        <div class="card-header">
            <h5>2. Stored XSS - Sistema de Comentários</h5>
        </div>
        <div class="card-body">
            <!-- Form to add comments -->
            <form method="POST" action="">
                <input type="hidden" name="page" value="xss-lab">
                <input type="hidden" name="action" value="add_comment">

                <div class="mb-3">
                    <label for="comment_user" class="form-label">Seu nome:</label>
                    <input type="text" class="form-control" id="comment_user" name="comment_user" required>
                </div>

                <div class="mb-3">
                    <label for="comment_text" class="form-label">Comentário:</label>
                    <textarea class="form-control" id="comment_text" name="comment_text" rows="3" required></textarea>
                </div>

                <button type="submit" class="btn btn-success">Adicionar Comentário</button>
            </form>

            <?php
            // Handle comment submission
            if(isset($_POST['action']) && $_POST['action'] == 'add_comment') {
                $comment_user = $_POST['comment_user'];
                $comment_text = $_POST['comment_text'];
                $created_at = date('Y-m-d H:i:s');

                // Intentionally vulnerable - no input sanitization
                $query = "INSERT INTO xss_comments (username, comment, created_at) VALUES ('$comment_user', '$comment_text', '$created_at')";

                if($mysqli->query($query)) {
                    echo '<div class="alert alert-success mt-3">Comentário adicionado com sucesso!</div>';
                } else {
                    echo '<div class="alert alert-danger mt-3">Erro ao adicionar comentário.</div>';
                }
            }
            ?>

            <!-- Display comments -->
            <div class="mt-4">
                <h6>💬 Comentários dos usuários:</h6>

                <?php
                // Create table if not exists
                $mysqli->query("CREATE TABLE IF NOT EXISTS xss_comments (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    username VARCHAR(100) NOT NULL,
                    comment TEXT NOT NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                )");

                $result = $mysqli->query("SELECT * FROM xss_comments ORDER BY created_at DESC");

                if($result && $result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo '<div class="border-bottom pb-2 mb-2">';
                        echo '<strong>' . $row['username'] . '</strong> <small class="text-muted">(' . $row['created_at'] . ')</small><br>';
                        echo '<div>' . $row['comment'] . '</div>'; // Vulnerable - no XSS protection
                        echo '</div>';
                    }
                } else {
                    echo '<p class="text-muted">Nenhum comentário ainda. Seja o primeiro!</p>';
                }
                ?>
            </div>

            <div class="mt-3">
                <small class="text-muted">
                    <strong>Payloads para testar no comentário:</strong><br>
                    <code>&lt;script&gt;alert('Stored XSS!')&lt;/script&gt;</code><br>
                    <code>&lt;img src=x onerror=alert(document.cookie)&gt;</code><br>
                    <code>&lt;iframe src="javascript:alert('XSS')"&gt;&lt;/iframe&gt;</code>
                </small>
            </div>
        </div>
    </div>

    <!-- DOM-based XSS -->
    <div class="card mb-4">
        <div class="card-header">
            <h5>3. DOM-based XSS</h5>
        </div>
        <div class="card-body">
            <p>Esta seção demonstra XSS baseado em DOM manipulation:</p>

            <div class="input-group mb-3">
                <input type="text" class="form-control" id="domInput" placeholder="Digite algo aqui">
                <button class="btn btn-primary" onclick="updateDOM()">Atualizar</button>
            </div>

            <div id="domOutput" class="p-3 bg-light border rounded">
                <!-- Output will be displayed here -->
            </div>

            <div class="mt-3">
                <small class="text-muted">
                    <strong>Payload de teste:</strong><br>
                    <code>&lt;img src=x onerror=alert('DOM XSS')&gt;</code>
                </small>
            </div>
        </div>
    </div>

    <!-- Prevention Examples -->
    <div class="card mb-4">
        <div class="card-header bg-success text-white">
            <h5>✅ Como Prevenir XSS</h5>
        </div>
        <div class="card-body">
            <h6>1. Sanitização de Input:</h6>
            <pre><code>// PHP
$safe_input = htmlspecialchars($_GET['input'], ENT_QUOTES, 'UTF-8');

// JavaScript
const safeInput = input.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');</code></pre>

            <h6>2. Content Security Policy (CSP):</h6>
            <pre><code>Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline'</code></pre>

            <h6>3. Output Encoding:</h6>
            <pre><code>// Use htmlentities() em PHP
echo htmlentities($user_input, ENT_QUOTES, 'UTF-8');</code></pre>
        </div>
    </div>
</div>

<script>
function updateDOM() {
    var input = document.getElementById('domInput').value;
    // Vulnerable - directly inserting user input into DOM
    document.getElementById('domOutput').innerHTML = '<p>Você digitou: ' + input + '</p>';
}

// Auto-update from URL fragment (additional DOM XSS vector)
window.onload = function() {
    if(location.hash) {
        var fragment = decodeURIComponent(location.hash.substring(1));
        if(fragment) {
            document.getElementById('domOutput').innerHTML = '<p>Fragment: ' + fragment + '</p>';
        }
    }
}
</script>