<?php
defined('APP') or die('Access denied');

// Processar exclusão da conta se foi confirmada
$accountDeleted = false;
if (isset($_POST['confirm_delete']) && $_POST['confirm_delete'] === 'yes') {
    $accountDeleted = true;
}
?>

<div class="container mt-4">
    <div class="card">
        <div class="card-header bg-danger text-white">
            <h5 class="mb-0">⚠️ Exclusão de Conta</h5>
        </div>
        <div class="card-body">
            <?php if (!$accountDeleted): ?>
                <div class="alert alert-warning" role="alert">
                    <strong>Atenção!</strong> Esta ação não pode ser desfeita. Todos os seus dados serão permanentemente removidos.
                </div>

                <p>Tem certeza que deseja excluir sua conta? Esta ação é <strong>irreversível</strong>.</p>

                <form method="POST" action="" id="deleteForm">
                    <input type="hidden" name="page" value="delete-account">

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="confirmCheck" required>
                            <label class="form-check-label" for="confirmCheck">
                                Eu entendo que esta ação é irreversível
                            </label>
                        </div>
                    </div>

                    <!-- Este é o botão que será clicado involuntariamente via clickjacking -->
                    <button type="submit" name="confirm_delete" value="yes" class="btn btn-danger btn-lg" id="deleteButton">
                        🗑️ DELETAR MINHA CONTA
                    </button>

                    <a href="?page=profile" class="btn btn-secondary btn-lg ms-2">Cancelar</a>
                </form>

                <div class="mt-4">
                    <small class="text-muted">
                        <strong>Dica de Segurança:</strong> Páginas sensíveis como esta deveriam implementar
                        proteção contra clickjacking usando headers X-Frame-Options ou CSP frame-ancestors.
                    </small>
                </div>

            <?php else: ?>
                <div class="alert alert-danger" role="alert">
                    <h4 class="alert-heading">🚨 Conta Excluída!</h4>
                    <p>Sua conta foi <strong>permanentemente excluída</strong> do sistema.</p>
                    <hr>
                    <p class="mb-0">Se esta ação foi executada sem seu conhecimento, você pode ter sido vítima de um ataque de clickjacking!</p>
                </div>

                <div class="alert alert-info" role="alert">
                    <strong>⚠️ Para fins educacionais:</strong> Em um sistema real, esta exclusão teria removido
                    todos os dados do usuário do banco de dados.
                </div>

                <a href="?page=home" class="btn btn-primary">Voltar ao Início</a>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
// Adicionar confirmação extra para tornar mais realista
document.getElementById('deleteForm')?.addEventListener('submit', function(e) {
    const checkbox = document.getElementById('confirmCheck');
    if (!checkbox.checked) {
        e.preventDefault();
        alert('Você deve confirmar que entende a ação antes de continuar.');
        return false;
    }

    // Em um cenário real, haveria uma confirmação adicional
    if (!confirm('ÚLTIMA CHANCE: Tem absoluta certeza que deseja excluir sua conta? Esta ação é IRREVERSÍVEL!')) {
        e.preventDefault();
        return false;
    }
});

// Para detectar se está sendo usado em iframe (clickjacking)
if (window.top !== window.self) {
    // Está sendo carregado em iframe - possível clickjacking
    console.warn('⚠️ POSSÍVEL CLICKJACKING DETECTADO: Esta página está sendo carregada em um iframe!');

    // Em um sistema real, você poderia implementar proteções aqui
    // Por exemplo: window.top.location = window.self.location;
}
</script>