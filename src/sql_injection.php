<?php
defined('APP') or die('Access denied');
include("db.php");
?>
<h3>SQL Injection</h3>

<a href="?page=sql-injection">Todos os registros</a><br />
<a href="?page=sql-injection&id=3">Filtrar por ID</a><br />
<a href='?page=sql-injection&id=3 union select 1, concat("%inicio%", database(), "%fim%" ), 3'>Database</a><br />
<a href='?page=sql-injection&id=3 union select 1, concat("<b>", table_name, "</b>") ,3 from information_schema.tables where table_schema=database()'>Tabelas</a><br />


<?php
$where = "";
if (isset($_GET['id'])) {
    $where = 'WHERE id = ' . $_GET['id'];
}
// Perform query
$query = "SELECT * FROM user $where";
if ($result = $mysqli->query($query)) {
    echo ("<h5>Número de registros: {$result->num_rows}</h5>");

    foreach ($result as $row) {
        echo $row['id'] . " " . $row['username'] . "<br>";
    }
    // Free result set
    $result->free_result();
} else {
    echo "Query failed";
}
echo "<pre>{$query}</pre>";

$mysqli->close();
