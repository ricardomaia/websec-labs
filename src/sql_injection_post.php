<?php
defined('APP') or die('Access denied');
include("db.php");
?>


<h3>SQL Injection - POST</h3>

<div class="loginForm">
    <form action="?page=sql-injection-post" method="post">
        <label for=" username">Username</label>
        <input type="text" name="username" id="username" class="form-control" placeholder="Username" required>
        <label for="password">Password</label>
        <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
        <button type="submit" class="btn btn-primary">Login</button>
    </form>
</div>

<?php
if (isset($_POST['username'])) {
    $username = $_POST['username'];
    $password = md5($_POST['password']);
    $query = "SELECT * FROM user WHERE username = '$username' AND password = '$password'";
    $result = $mysqli->query($query);
    var_dump($query);
    var_dump($_POST);
    var_dump($result);

    if ($result->num_rows > 0) {
        $_SESSION['username'] = $username;
?>
        <div class="alert alert-success" role="alert">
            Login successful!
        </div>
    <?php
    } else {
    ?>
        <div class="alert alert-danger" role="alert">
            Login failed.
        </div>
<?php
    }
    $mysqli->close();
}
