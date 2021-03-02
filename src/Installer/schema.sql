SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+01:00";

DROP TABLE IF EXISTS `{{prefix}}settings`;
CREATE TABLE IF NOT EXISTS `{{prefix}}settings` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `value` longtext DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `{{prefix}}users`;
CREATE TABLE IF NOT EXISTS `{{prefix}}users` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` text NOT NULL,
  `role` int NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `{{prefix}}roles`;
CREATE TABLE IF NOT EXISTS `{{prefix}}roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `power` int(3) NOT NULL,
  `is_editable` tinyint(1) NOT NULL DEFAULT 1,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `{{prefix}}users`
  ADD CONSTRAINT `FK_USER_ROLE` FOREIGN KEY (`role`) REFERENCES `{{prefix}}roles` (`id`);

INSERT INTO `{{prefix}}settings` (name, value)
  VALUES ('title_site', '{{title}}'), ('description_site', '{{description}}');

INSERT INTO `{{prefix}}roles` (name, power, is_editable, is_default, created_at)
  VALUES ('Anonymous', 0, 0, 0, NOW()),
         ('User', 1, 0, 1, NOW()),
         ('Moderator', 50, 0, 0, NOW()),
         ('Admin', 80, 0, 0, NOW()),
         ('Super Admin', 100, 0, 0, NOW());

INSERT INTO `{{prefix}}users` (name, lastname, email, password, role, created_at)
  VALUES ('{{name}}', '{{lastname}}', '{{email}}', '{{password}}', 5, NOW());

COMMIT;
