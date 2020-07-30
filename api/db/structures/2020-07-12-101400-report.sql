CREATE TABLE `report`
(
    `id`                     int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `name`                   varchar(255) NOT NULL,
    `email`                  varchar(255) NOT NULL,
    `query`                  varchar(2000) NOT NULL,
    `frequency`              int(11) NOT NULL,
    `created`                datetime DEFAULT CURRENT_TIMESTAMP,
    `last_notified`          datetime NOT NULL,
    `next_notification`      datetime NOT NULL
);
