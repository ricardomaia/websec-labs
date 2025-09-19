<?php
defined('APP') or die('Access denied');
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="jumbotron bg-primary text-white p-5 rounded mb-4">
                <h1 class="display-4">🛡️ WebSec Labs</h1>
                <p class="lead">Ambiente de treinamento para vulnerabilidades em aplicações web</p>
                <hr class="my-4 border-light">
                <p>⚠️ <strong>Atenção:</strong> Este ambiente contém vulnerabilidades intencionais para fins educacionais. Não use em produção!</p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <h3 class="mb-4">🎯 Laboratórios Disponíveis</h3>
        </div>
    </div>

    <div class="row">
        <!-- SQL Injection Lab -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-danger text-white">
                    <h5 class="card-title mb-0">💉 SQL Injection</h5>
                </div>
                <div class="card-body">
                    <p class="card-text">Aprenda sobre injeção SQL testando queries vulneráveis e técnicas de bypass.</p>
                    <ul class="list-unstyled">
                        <li>✓ Union-based injection</li>
                        <li>✓ Information schema enumeration</li>
                        <li>✓ Database fingerprinting</li>
                    </ul>
                </div>
                <div class="card-footer">
                    <a href="?page=sql-injection" class="btn btn-danger">Iniciar Lab</a>
                </div>
            </div>
        </div>

        <!-- XSS Lab -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-warning text-dark">
                    <h5 class="card-title mb-0">🚨 Cross-Site Scripting (XSS)</h5>
                </div>
                <div class="card-body">
                    <p class="card-text">Explore diferentes tipos de vulnerabilidades XSS e técnicas de exploração.</p>
                    <ul class="list-unstyled">
                        <li>✓ Reflected XSS</li>
                        <li>✓ Stored XSS</li>
                        <li>✓ DOM-based XSS</li>
                    </ul>
                </div>
                <div class="card-footer">
                    <a href="?page=xss-lab" class="btn btn-warning text-dark">Iniciar Lab</a>
                </div>
            </div>
        </div>

        <!-- File Inclusion Lab -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title mb-0">📁 File Inclusion</h5>
                </div>
                <div class="card-body">
                    <p class="card-text">Teste vulnerabilidades de inclusão de arquivos (LFI/RFI) e path traversal.</p>
                    <ul class="list-unstyled">
                        <li>✓ Local File Inclusion (LFI)</li>
                        <li>✓ Path Traversal</li>
                        <li>✓ PHP Filter exploitation</li>
                    </ul>
                </div>
                <div class="card-footer">
                    <a href="?page=file-inclusion" class="btn btn-info">Iniciar Lab</a>
                </div>
            </div>
        </div>

        <!-- Session Management Lab -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0">🔐 Session Management</h5>
                </div>
                <div class="card-body">
                    <p class="card-text">Explore vulnerabilidades de gerenciamento de sessão e técnicas de sequestro.</p>
                    <ul class="list-unstyled">
                        <li>✓ Session Hijacking</li>
                        <li>✓ Session Fixation</li>
                        <li>✓ Privilege Escalation</li>
                    </ul>
                </div>
                <div class="card-footer">
                    <a href="?page=session-lab" class="btn btn-success">Iniciar Lab</a>
                </div>
            </div>
        </div>

        <!-- Clickjacking Lab -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-secondary text-white">
                    <h5 class="card-title mb-0">🖱️ Clickjacking</h5>
                </div>
                <div class="card-body">
                    <p class="card-text">Entenda como ataques de clickjacking funcionam e como preveni-los.</p>
                    <ul class="list-unstyled">
                        <li>✓ Frame-based attacks</li>
                        <li>✓ X-Frame-Options bypass</li>
                        <li>✓ UI redressing</li>
                    </ul>
                </div>
                <div class="card-footer">
                    <a href="?page=clickjacking" class="btn btn-secondary">Iniciar Lab</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Credentials Section -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h5 class="card-title mb-0">🔐 Credenciais de Teste</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <h6>👤 Usuários Disponíveis:</h6>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between">
                                    <span><strong>admin</strong></span>
                                    <code>password123</code>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span><strong>foo</strong></span>
                                    <code>test</code>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span><strong>bar</strong></span>
                                    <code>admin</code>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-4">
                            <h6>🗄️ Database:</h6>
                            <ul class="list-unstyled">
                                <li><strong>Host:</strong> db:3306</li>
                                <li><strong>User:</strong> root</li>
                                <li><strong>Password:</strong> root</li>
                                <li><strong>Database:</strong> websec_labs</li>
                            </ul>
                        </div>
                        <div class="col-md-4">
                            <h6>🌐 Serviços:</h6>
                            <ul class="list-unstyled">
                                <li><strong>App:</strong> <a href="http://localhost" target="_blank">localhost:80</a></li>
                                <li><strong>Adminer:</strong> <a href="http://localhost:8080" target="_blank">localhost:8080</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="mt-3">
                        <small class="text-muted">
                            📋 Para documentação completa das credenciais, consulte o arquivo <code>CREDENTIALS.md</code>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Security Tips -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="alert alert-info">
                <h5 class="alert-heading">💡 Dicas de Segurança</h5>
                <hr>
                <ul class="mb-0">
                    <li><strong>Sempre valide e sanitize inputs</strong> - Nunca confie em dados do usuário</li>
                    <li><strong>Use prepared statements</strong> - Para prevenir SQL injection</li>
                    <li><strong>Implemente CSP</strong> - Content Security Policy previne XSS</li>
                    <li><strong>Configure headers de segurança</strong> - X-Frame-Options, X-XSS-Protection, etc.</li>
                    <li><strong>Mantenha software atualizado</strong> - Aplique patches de segurança regularmente</li>
                </ul>
            </div>
        </div>
    </div>
</div>