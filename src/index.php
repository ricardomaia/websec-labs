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
    $_pages["sql-injection"] = "sql_injection.php";
    $_pages["sql-injection-post"] = "sql_injection_post.php";
    $_pages["sql-injection-blind"] = "sql_injection_blind.php";
    $_pages["xss-lab"] = "xss_lab.php";
    $_pages["session-lab"] = "session_lab.php";
    $_pages['clickjacking'] = 'clickjacking.php';
    $_pages['csrf-ssrf-demo'] = 'csrf_ssrf_demo.php';
    $_pages['csrf-realistic-demo'] = 'csrf_realistic_demo.php';
    $_pages['logout'] = 'logout.php';
    $_pages['profile'] = 'profile.php';
    $_pages['delete-account'] = 'delete-account.php';


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

    <?php
    if (isset($_GET['logout']) && $_GET['logout'] == 'true') {
    ?>
        <div class="alert alert-primary" role="alert">
            User logged out!
        </div>

    <?php
    }
    ?>
</body>

</html>