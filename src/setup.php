<?php
defined('APP') or die('Access denied');
?>
<h3>Setup</h3>
<?php
$mysqli = new mysqli("db", "root", "root", "mysql");
$query = "
DROP DATABASE IF EXISTS `websec_labs`;
CREATE DATABASE `websec_labs`;
USE `websec_labs`;
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `user` (`id`, `username`, `password`) VALUES
(1,	'admin',	'e10adc3949ba59abbe56e057f20f883e'),
(2,	'foo',	'5583413443164b56500def9a533c7c70'),
(3,	'bar',	'698dc19d489c4e4db73e28a713eab07b');
";

if ($result = $mysqli->multi_query($query)) {

    echo ('
    <div class="alert alert-success" role="alert">
        Banco de dados criado com sucesso!
    </div>
    ');
} else {

    echo ('
    <div class="alert alert-danger" role="alert">
        Falha na criação do banco de dados.
    </div>    
    ');

    echo ($mysqli->error);
}
