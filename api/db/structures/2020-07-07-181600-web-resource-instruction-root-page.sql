CREATE TABLE `web_resource`
(
    `id`                int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `url`               varchar(2083) NOT NULL,
    `name`              varchar(100) NOT NULL UNIQUE
);


CREATE TABLE `instruction`
(
    `id`                int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `resource_id`       int(11) NOT NULL,
    `name`              varchar(100) NOT NULL,
    `target`            enum('list', 'detail') NOT NULL,
    `type`              enum('attr', 'attrs', 'link', 'links', 'text', 'texts') NOT NULL,
    `selector`          varchar(255) NOT NULL,
    `attribute`         varchar(255) DEFAULT NULL,
    `modifier`          varchar(255) DEFAULT NULL,

    CONSTRAINT `instruction_web_resource_fk` FOREIGN KEY (`resource_id`) REFERENCES `web_resource` (`id`) ON DELETE CASCADE
);

CREATE TABLE `root_page`
(
    `id`                int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `resource_id`       int(11) NOT NULL,
    `url`               varchar(2083) NOT NULL,
    `name`              varchar(100) NOT NULL,

    CONSTRAINT `root_page_web_resource_fk` FOREIGN KEY (`resource_id`) REFERENCES `web_resource` (`id`) ON DELETE CASCADE
);
