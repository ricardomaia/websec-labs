<?php
defined('APP') or die('Access denied');
include("db.php");
?>
<h3>Profile</h3>
<?php
$query = "SELECT * FROM user WHERE username = '" . $_SESSION['username'] . "'";
if ($result = $mysqli->query($query)) {
    $row = $result->fetch_assoc();

?>
    <form class="loginForm" action=" /?page=delete-account" method="post">
        <div class="mb-3">
            <?php echo "<p>Username: " . $row['username'] . "</p>"; ?>
            <?php echo "<p>Email: " . $row['email'] . "</p>"; ?>
        </div>
        <button type="submit" class="btn btn-primary">Excluir minha conta</button>

    <?php

    $result->free_result();
}

$mysqli->close();
