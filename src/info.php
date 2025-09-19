<?php
define('APP', true);
include("header.php");
?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="css/global.css" rel="stylesheet">

    <title>PHP Info - WebSec Labs</title>
</head>

<body>
    <?php include("nav.php"); ?>

    <div class="container mt-4">
        <h3>🔧 PHP Configuration Information</h3>

        <div class="alert alert-info" role="alert">
            <strong>ℹ️ Informação:</strong> Esta página exibe a configuração completa do PHP para análise de segurança.
        </div>

        <div class="row mb-3">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5>📊 Resumo das Configurações PHP</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <h6>Versão do PHP:</h6>
                                <span class="badge bg-primary"><?php echo PHP_VERSION; ?></span>
                            </div>
                            <div class="col-md-3">
                                <h6>Sistema Operacional:</h6>
                                <span class="badge bg-secondary"><?php echo PHP_OS; ?></span>
                            </div>
                            <div class="col-md-3">
                                <h6>Servidor Web:</h6>
                                <span class="badge bg-success"><?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'N/A'; ?></span>
                            </div>
                            <div class="col-md-3">
                                <h6>Arquitetura:</h6>
                                <span class="badge bg-warning text-dark"><?php echo php_uname('m'); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h6>🔒 Configurações de Segurança</h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <tbody>
                                <tr>
                                    <td><strong>allow_url_fopen:</strong></td>
                                    <td><span class="badge bg-<?php echo ini_get('allow_url_fopen') ? 'danger' : 'success'; ?>"><?php echo ini_get('allow_url_fopen') ? 'ON' : 'OFF'; ?></span></td>
                                </tr>
                                <tr>
                                    <td><strong>allow_url_include:</strong></td>
                                    <td><span class="badge bg-<?php echo ini_get('allow_url_include') ? 'danger' : 'success'; ?>"><?php echo ini_get('allow_url_include') ? 'ON' : 'OFF'; ?></span></td>
                                </tr>
                                <tr>
                                    <td><strong>display_errors:</strong></td>
                                    <td><span class="badge bg-<?php echo ini_get('display_errors') ? 'warning' : 'success'; ?>"><?php echo ini_get('display_errors') ? 'ON' : 'OFF'; ?></span></td>
                                </tr>
                                <tr>
                                    <td><strong>expose_php:</strong></td>
                                    <td><span class="badge bg-<?php echo ini_get('expose_php') ? 'warning' : 'success'; ?>"><?php echo ini_get('expose_php') ? 'ON' : 'OFF'; ?></span></td>
                                </tr>
                                <tr>
                                    <td><strong>register_globals:</strong></td>
                                    <td><span class="badge bg-<?php echo ini_get('register_globals') ? 'danger' : 'success'; ?>"><?php echo ini_get('register_globals') ? 'ON' : 'OFF'; ?></span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h6>📁 Diretórios e Limites</h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <tbody>
                                <tr>
                                    <td><strong>document_root:</strong></td>
                                    <td><small><?php echo $_SERVER['DOCUMENT_ROOT'] ?? 'N/A'; ?></small></td>
                                </tr>
                                <tr>
                                    <td><strong>include_path:</strong></td>
                                    <td><small><?php echo ini_get('include_path'); ?></small></td>
                                </tr>
                                <tr>
                                    <td><strong>open_basedir:</strong></td>
                                    <td><small><?php echo ini_get('open_basedir') ?: 'Não configurado'; ?></small></td>
                                </tr>
                                <tr>
                                    <td><strong>memory_limit:</strong></td>
                                    <td><span class="badge bg-info"><?php echo ini_get('memory_limit'); ?></span></td>
                                </tr>
                                <tr>
                                    <td><strong>max_execution_time:</strong></td>
                                    <td><span class="badge bg-info"><?php echo ini_get('max_execution_time'); ?>s</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>🔍 Informações Completas do PHP</h5>
                <button class="btn btn-outline-secondary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#phpinfoCollapse">
                    Mostrar/Ocultar Detalhes
                </button>
            </div>
            <div class="collapse" id="phpinfoCollapse">
                <div class="card-body">
                    <div style="overflow-x: auto;">
                        <?php phpinfo(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>