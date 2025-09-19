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