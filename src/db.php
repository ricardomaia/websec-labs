<?php
defined('APP') or die('Access denied');

$mysqli = new mysqli("db", "root", "root", "websec_labs");
// Check connection
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
}
