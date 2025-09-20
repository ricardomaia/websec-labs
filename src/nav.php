<?php
defined('APP') or die('Access denied');
?>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="/"><img src="img/secure.png" width="32"> WebSec Labs</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="/">Home</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/?page=file-inclusion">File Inclusion</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        SQLi
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="/?page=sql-injection">SQLi GET</a></li>
                        <li><a class="dropdown-item" href="/?page=sql-injection-post">SQLi POST</a></li>
                        <li><a class="dropdown-item" href="/?page=sql-injection-blind">SQLi Blind</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/?page=xss-lab">XSS Lab</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/?page=session-lab">Session Management</a>
                </li>
                <li class="nav-item"><a class="nav-link" href="/?page=clickjacking">Clickjacking</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="csrfDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        CSRF Demo
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="csrfDropdown">
                        <li><a class="dropdown-item" href="/?page=csrf-ssrf-demo">Com CORS (Demo)</a></li>
                        <li><a class="dropdown-item" href="/?page=csrf-realistic-demo">Realista (Sem CORS)</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="info.php">PHP Info</a>
                </li>
            </ul>
        </div>
        <?php
        if (isset($_SESSION['username'])) {
        ?>
            <form action="/?page=logout" method="post">
                <button class="btn btn-outline-primary my-sm-0" type="submit">Logout</button>
            </form>
            &nbsp;
            <form action="/?page=profile" method="post">
                <button class="btn btn-outline-secondary" type="submit">Profile</button>
            </form>
        <?php
        };
        ?>
    </div>

</nav>