CREATE TABLE `user`(
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `username` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `groupID` INT NULL,
    `hasAddHabit` TINYINT(1) NOT NULL,
    `lastConnection` DATETIME NOT NULL
);
ALTER TABLE
    `user` ADD PRIMARY KEY `user_id_primary`(`id`);
ALTER TABLE
    `user` ADD UNIQUE `user_username_unique`(`username`);
ALTER TABLE
    `user` ADD UNIQUE `user_email_unique`(`email`);
CREATE TABLE `group`(
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `score` INT NOT NULL
);
ALTER TABLE
    `group` ADD PRIMARY KEY `group_id_primary`(`id`);
CREATE TABLE `habit`(
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `description` VARCHAR(255) NOT NULL,
    `difficulty` VARCHAR(255) NOT NULL,
    `color` VARCHAR(255) NOT NULL,
    `start` DATETIME NOT NULL,
    `time` VARCHAR(255) NOT NULL,
    `userID` INT NOT NULL,
    `isDone` TINYINT(1) NOT NULL
);
ALTER TABLE
    `habit` ADD PRIMARY KEY `habit_id_primary`(`id`);
ALTER TABLE
    `habit` ADD CONSTRAINT `habit_userid_foreign` FOREIGN KEY(`userID`) REFERENCES `user`(`id`);
ALTER TABLE
    `user` ADD CONSTRAINT `user_groupid_foreign` FOREIGN KEY(`groupID`) REFERENCES `group`(`id`);