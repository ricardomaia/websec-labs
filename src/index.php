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

    <title>WebSec Labs</title>
</head>

<body>

    <?php include("nav.php"); ?>
    <?php

    // Paginas permitidas
    $_pages = array();
    $_pages["home"] = "home.php";
    $_pages["file-inclusion"] = "file_inclusion.php";
    $_pages["phpinfo"] = "info.php";
    $_pages["sql-injection"] = "sql_injection.php";
    $_pages["setup"] = "setup.php";

    if (isset($_GET['page'])) {
        $page = $_GET['page'];

        if (in_array($page, array_keys($_pages))) {
            include($_pages[$page]);
        } else {
            include("home.php");
        }
    } else {
        include("home.php");
    }

    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</body>

</html>