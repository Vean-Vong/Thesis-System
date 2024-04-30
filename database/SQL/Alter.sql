CREATE TABLE `app_settings`  (
  `id` tinyint NOT NULL AUTO_INCREMENT,
  `key` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `label` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `value` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL,
  PRIMARY KEY (`id`)
);

INSERT INTO `app_settings`(`id`, `key`, `label`, `value`, `description`) VALUES (1, 'logo', 'Logo', '/src/assets/images/logo.png', 'this logo for app');
INSERT INTO `app_settings`(`id`, `key`, `label`, `value`, `description`) VALUES (3, 'name_en', 'Intitute Name En', 'SOK PHIRUM POLYCLINIC & MATERNITY', NULL);
INSERT INTO `app_settings`(`id`, `key`, `label`, `value`, `description`) VALUES (2, 'name_kh', 'Intitute Name Kh', 'មន្ទីព្យាបាល សុខ ភិរម្យ', NULL);
