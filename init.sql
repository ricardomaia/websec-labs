DROP DATABASE IF EXISTS `websec_labs`;
CREATE DATABASE `websec_labs`;
USE `websec_labs`;


DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


INSERT INTO `user` (`id`, `username`, `password`,`email`) VALUES
(1,	'admin',	'96ea88029a5de3aaa24685467f8396b3', 'admin@webseclabs.local'), 
(2,	'foo',	'5583413443164b56500def9a533c7c70','foo_21@webseclabs.local'), 
(3,	'bar',	'698dc19d489c4e4db73e28a713eab07b','b.bar@webseclabs.local'); 


DROP TABLE IF EXISTS `product`;
CREATE TABLE `product` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `price` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `product` (`id`, `name`, `description`, `price`) VALUES
(1,	'Pen Drive 8GB',	'',	100),
(2,	'HD Externo 1TB',	'',	199.99),
(3,	'teclado numérico',	'',	24.99);