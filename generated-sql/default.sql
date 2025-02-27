
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- calendarTokens
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `calendarTokens`;

CREATE TABLE `calendarTokens`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `token` VARCHAR(30) NOT NULL,
    `userId` INTEGER(30) NOT NULL,
    `format` VARCHAR(5) NOT NULL,
    `description` VARCHAR(100),
    `revoked` TINYINT(1) DEFAULT 0 NOT NULL,
    `revokedDate` TIMESTAMP NULL,
    `lastFetched` TIMESTAMP NULL,
    `created` TIMESTAMP NULL,
    `updated` TIMESTAMP NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `token` (`token`),
    INDEX `calendarTokens_fi_2596c7` (`userId`),
    CONSTRAINT `calendarTokens_fk_2596c7`
        FOREIGN KEY (`userId`)
        REFERENCES `users` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- comments
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `comments`;

CREATE TABLE `comments`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `eventId` INTEGER DEFAULT 0 NOT NULL,
    `userId` INTEGER DEFAULT 0 NOT NULL,
    `text` VARCHAR(255),
    `removed` TINYINT(1) DEFAULT 0,
    `created` TIMESTAMP NULL,
    `updated` TIMESTAMP NULL,
    PRIMARY KEY (`id`),
    INDEX `comments_fi_c07e67` (`eventId`),
    INDEX `comments_fi_2596c7` (`userId`),
    CONSTRAINT `comments_fk_c07e67`
        FOREIGN KEY (`eventId`)
        REFERENCES `events` (`id`),
    CONSTRAINT `comments_fk_2596c7`
        FOREIGN KEY (`userId`)
        REFERENCES `users` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- documents
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `documents`;

CREATE TABLE `documents`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(127) DEFAULT '' NOT NULL,
    `description` TEXT NOT NULL,
    `url` VARCHAR(127) DEFAULT '' NOT NULL,
    `link` TEXT NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- emails
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `emails`;

CREATE TABLE `emails`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `emailTo` VARCHAR(100) DEFAULT '' NOT NULL,
    `emailBcc` VARCHAR(100) DEFAULT '' NOT NULL,
    `emailFrom` VARCHAR(100) NOT NULL,
    `subject` VARCHAR(150) NOT NULL,
    `message` TEXT NOT NULL,
    `error` TEXT,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- eventGroups
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `eventGroups`;

CREATE TABLE `eventGroups`
(
    `id` INTEGER(30) NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(128) DEFAULT '' NOT NULL,
    `description` TEXT NOT NULL,
    `archived` TINYINT(1) DEFAULT 0 NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- eventPeople
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `eventPeople`;

CREATE TABLE `eventPeople`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `eventId` INTEGER DEFAULT 0 NOT NULL,
    `userRoleId` INTEGER DEFAULT 0 NOT NULL,
    `notified` SMALLINT(1) DEFAULT 0 NOT NULL,
    `removed` SMALLINT(1) DEFAULT 0,
    PRIMARY KEY (`id`),
    INDEX `eventPeople_fi_c07e67` (`eventId`),
    INDEX `eventPeople_fi_f11fe6` (`userRoleId`),
    CONSTRAINT `eventPeople_fk_c07e67`
        FOREIGN KEY (`eventId`)
        REFERENCES `events` (`id`),
    CONSTRAINT `eventPeople_fk_f11fe6`
        FOREIGN KEY (`userRoleId`)
        REFERENCES `userRoles` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- eventSubTypes
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `eventSubTypes`;

CREATE TABLE `eventSubTypes`
(
    `id` INTEGER(30) NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(128) DEFAULT '' NOT NULL,
    `description` TEXT NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- eventTypes
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `eventTypes`;

CREATE TABLE `eventTypes`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(30) NOT NULL,
    `description` TEXT NOT NULL,
    `defaultDay` INTEGER(1),
    `defaultTime` TIME,
    `defaultRepitition` INTEGER(3),
    `defaultLocationId` INTEGER(30),
    `rehearsal` INTEGER(2) DEFAULT 0 NOT NULL,
    `groupformat` INTEGER(1) DEFAULT 0 NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `eventTypes_fi_c58e0b` (`defaultLocationId`),
    CONSTRAINT `eventTypes_fk_c58e0b`
        FOREIGN KEY (`defaultLocationId`)
        REFERENCES `locations` (`id`)
        ON DELETE SET NULL
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- events
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `events`;

CREATE TABLE `events`
(
    `id` INTEGER(6) NOT NULL AUTO_INCREMENT,
    `date` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
    `name` TEXT NOT NULL,
    `createdBy` INTEGER DEFAULT 0 NOT NULL,
    `rehearsalDate` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
    `type` INTEGER(30) DEFAULT 0 NOT NULL,
    `subType` INTEGER(30) DEFAULT 0 NOT NULL,
    `location` INTEGER DEFAULT 0 NOT NULL,
    `notified` INTEGER(2) DEFAULT 0 NOT NULL,
    `rehearsal` INTEGER DEFAULT 0 NOT NULL,
    `removed` SMALLINT(1) DEFAULT 0,
    `eventGroup` INTEGER(30),
    `sermonTitle` VARCHAR(64),
    `bibleVerse` VARCHAR(64),
    `created` TIMESTAMP NULL,
    `updated` TIMESTAMP NULL,
    PRIMARY KEY (`id`),
    INDEX `events_fi_40a46e` (`createdBy`),
    INDEX `events_fi_80afae` (`type`),
    INDEX `events_fi_8cc9da` (`subType`),
    INDEX `events_fi_fb6343` (`location`),
    INDEX `events_fi_c5f971` (`eventGroup`),
    CONSTRAINT `events_fk_40a46e`
        FOREIGN KEY (`createdBy`)
        REFERENCES `users` (`id`),
    CONSTRAINT `events_fk_80afae`
        FOREIGN KEY (`type`)
        REFERENCES `eventTypes` (`id`),
    CONSTRAINT `events_fk_8cc9da`
        FOREIGN KEY (`subType`)
        REFERENCES `eventSubTypes` (`id`),
    CONSTRAINT `events_fk_fb6343`
        FOREIGN KEY (`location`)
        REFERENCES `locations` (`id`),
    CONSTRAINT `events_fk_c5f971`
        FOREIGN KEY (`eventGroup`)
        REFERENCES `eventGroups` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- availability
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `availability`;

CREATE TABLE `availability`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `eventId` INTEGER NOT NULL,
    `userId` INTEGER NOT NULL,
    `available` TINYINT(1) DEFAULT 1 NOT NULL,
    `comment` VARCHAR(64),
    PRIMARY KEY (`id`),
    INDEX `availability_fi_2596c7` (`userId`),
    INDEX `availability_fi_c07e67` (`eventId`),
    CONSTRAINT `availability_fk_2596c7`
        FOREIGN KEY (`userId`)
        REFERENCES `users` (`id`),
    CONSTRAINT `availability_fk_c07e67`
        FOREIGN KEY (`eventId`)
        REFERENCES `events` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- groups
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `groups`;

CREATE TABLE `groups`
(
    `id` INTEGER(3) NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(25) DEFAULT '' NOT NULL,
    `rehearsal` INTEGER(1) DEFAULT 0 NOT NULL,
    `formatgroup` INTEGER(2) DEFAULT 0 NOT NULL,
    `description` TEXT,
    `allowRoleSwaps` TINYINT(1) DEFAULT 1 NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- locations
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `locations`;

CREATE TABLE `locations`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` TEXT NOT NULL,
    `address` TEXT,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- notificationClicks
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `notificationClicks`;

CREATE TABLE `notificationClicks`
(
    `id` INTEGER(30) NOT NULL AUTO_INCREMENT,
    `notificationId` INTEGER(30) NOT NULL,
    `referer` VARCHAR(50) NOT NULL,
    `timestamp` TIMESTAMP NULL,
    PRIMARY KEY (`id`),
    INDEX `notificationClicks_fi_1e5260` (`notificationId`),
    CONSTRAINT `notificationClicks_fk_1e5260`
        FOREIGN KEY (`notificationId`)
        REFERENCES `notifications` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- notifications
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `notifications`;

CREATE TABLE `notifications`
(
    `id` INTEGER(30) NOT NULL AUTO_INCREMENT,
    `timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `userId` INTEGER(30) NOT NULL,
    `summary` VARCHAR(40) NOT NULL,
    `body` TEXT NOT NULL,
    `link` VARCHAR(150),
    `type` INTEGER(2) NOT NULL,
    `seen` TINYINT(1) DEFAULT 0 NOT NULL,
    `dismissed` TINYINT(1) DEFAULT 0 NOT NULL,
    `archived` TINYINT(1) DEFAULT 0 NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `notifications_fi_2596c7` (`userId`),
    CONSTRAINT `notifications_fk_2596c7`
        FOREIGN KEY (`userId`)
        REFERENCES `users` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- pendingUsers
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `pendingUsers`;

CREATE TABLE `pendingUsers`
(
    `id` INTEGER(30) NOT NULL AUTO_INCREMENT,
    `socialId` BIGINT(30),
    `firstName` VARCHAR(100) NOT NULL,
    `lastName` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) NOT NULL,
    `approved` TINYINT(1) DEFAULT 0 NOT NULL,
    `declined` TINYINT(1) DEFAULT 0 NOT NULL,
    `source` VARCHAR(50) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `userId` (`socialId`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- roles
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `roles`;

CREATE TABLE `roles`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `groupId` INTEGER(6) DEFAULT 0 NOT NULL,
    `name` VARCHAR(15) DEFAULT '' NOT NULL,
    `description` TEXT NOT NULL,
    `rehersalId` INTEGER(6) DEFAULT 0 NOT NULL,
    `allowRoleSwaps` TINYINT(1),
    PRIMARY KEY (`id`),
    INDEX `roles_fi_1264f9` (`groupId`),
    CONSTRAINT `roles_fk_1264f9`
        FOREIGN KEY (`groupId`)
        REFERENCES `groups` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- settings
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `settings`;

CREATE TABLE `settings`
(
    `siteurl` TEXT NOT NULL,
    `owner` TEXT NOT NULL,
    `notificationemail` TEXT,
    `adminemailaddress` TEXT,
    `norehearsalemail` TEXT,
    `yesrehearsal` TEXT,
    `newusermessage` TEXT,
    `version` VARCHAR(20),
    `lang_locale` VARCHAR(20),
    `event_sorting_latest` INTEGER(1),
    `snapshot_show_two_month` INTEGER(1),
    `snapshot_reduce_skills_by_group` INTEGER(1),
    `logged_in_show_snapshot_button` INTEGER(1),
    `time_format_long` VARCHAR(50),
    `time_format_normal` VARCHAR(50),
    `time_format_short` VARCHAR(50),
    `time_only_format` VARCHAR(20),
    `date_only_format` VARCHAR(20),
    `day_only_format` VARCHAR(20),
    `users_start_with_myevents` INTEGER(1),
    `time_zone` VARCHAR(50),
    `google_group_calendar` VARCHAR(100),
    `overviewemail` TEXT,
    `group_sorting_name` INTEGER(1),
    `debug_mode` INTEGER(1) DEFAULT 0,
    `days_to_alert` INTEGER(2) DEFAULT 5,
    `token` VARCHAR(100) DEFAULT '',
    `skin` VARCHAR(20) DEFAULT ''
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- socialAuth
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `socialAuth`;

CREATE TABLE `socialAuth`
(
    `userId` INTEGER(30) NOT NULL,
    `platform` VARCHAR(10) NOT NULL,
    `socialId` BIGINT(30) NOT NULL,
    `meta` TEXT,
    `revoked` TINYINT(1) DEFAULT 0 NOT NULL,
    PRIMARY KEY (`userId`,`platform`,`socialId`),
    UNIQUE INDEX `socialId` (`socialId`),
    CONSTRAINT `socialAuth_fk_2596c7`
        FOREIGN KEY (`userId`)
        REFERENCES `users` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- statistics
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `statistics`;

CREATE TABLE `statistics`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `userid` INTEGER(6) DEFAULT 0,
    `date` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
    `type` TEXT NOT NULL,
    `detail1` TEXT NOT NULL,
    `detail2` TEXT NOT NULL,
    `detail3` TEXT NOT NULL,
    `script` TEXT NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `statistics_fi_2596c7` (`userid`),
    CONSTRAINT `statistics_fk_2596c7`
        FOREIGN KEY (`userid`)
        REFERENCES `users` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- swaps
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `swaps`;

CREATE TABLE `swaps`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `eventPersonId` INTEGER DEFAULT 0 NOT NULL,
    `oldUserRoleId` INTEGER DEFAULT 0 NOT NULL,
    `newUserRoleId` INTEGER DEFAULT 0 NOT NULL,
    `accepted` INTEGER(1) DEFAULT 0 NOT NULL,
    `declined` INTEGER(1) DEFAULT 0 NOT NULL,
    `requestedBy` INTEGER NOT NULL,
    `verificationCode` VARCHAR(18) NOT NULL,
    `created` TIMESTAMP NULL,
    `updated` TIMESTAMP NULL,
    PRIMARY KEY (`id`),
    INDEX `swaps_fi_2e6337` (`eventPersonId`),
    INDEX `swaps_fi_fd4ea7` (`oldUserRoleId`),
    INDEX `swaps_fi_11ab3c` (`newUserRoleId`),
    INDEX `swaps_fi_53db89` (`requestedBy`),
    CONSTRAINT `swaps_fk_2e6337`
        FOREIGN KEY (`eventPersonId`)
        REFERENCES `eventPeople` (`id`),
    CONSTRAINT `swaps_fk_fd4ea7`
        FOREIGN KEY (`oldUserRoleId`)
        REFERENCES `userRoles` (`id`),
    CONSTRAINT `swaps_fk_11ab3c`
        FOREIGN KEY (`newUserRoleId`)
        REFERENCES `userRoles` (`id`),
    CONSTRAINT `swaps_fk_53db89`
        FOREIGN KEY (`requestedBy`)
        REFERENCES `users` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- userRoles
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `userRoles`;

CREATE TABLE `userRoles`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `userId` INTEGER(30) DEFAULT 0 NOT NULL,
    `roleId` INTEGER DEFAULT 0 NOT NULL,
    `reserve` TINYINT(1) DEFAULT 0 NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `userRoles_fi_2596c7` (`userId`),
    INDEX `userRoles_fi_c19afe` (`roleId`),
    CONSTRAINT `userRoles_fk_2596c7`
        FOREIGN KEY (`userId`)
        REFERENCES `users` (`id`),
    CONSTRAINT `userRoles_fk_c19afe`
        FOREIGN KEY (`roleId`)
        REFERENCES `roles` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- users
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users`
(
    `id` INTEGER(30) NOT NULL AUTO_INCREMENT,
    `firstName` VARCHAR(30) DEFAULT '' NOT NULL,
    `lastName` VARCHAR(30) DEFAULT '' NOT NULL,
    `username` VARCHAR(30) DEFAULT '' NOT NULL,
    `password` VARCHAR(200) DEFAULT '' NOT NULL,
    `isAdmin` CHAR(2) DEFAULT '0' NOT NULL,
    `email` VARCHAR(255),
    `mobile` VARCHAR(15) DEFAULT '' NOT NULL,
    `isOverviewRecipient` CHAR(2) DEFAULT '0' NOT NULL,
    `recieveReminderEmails` TINYINT(1) DEFAULT 1 NOT NULL,
    `isBandAdmin` CHAR(2) DEFAULT '0' NOT NULL,
    `isEventEditor` CHAR(2) DEFAULT '0' NOT NULL,
    `lastLogin` TIMESTAMP NULL,
    `passwordChanged` TIMESTAMP NULL,
    `created` TIMESTAMP NULL,
    `updated` TIMESTAMP NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- userPermissions
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `userPermissions`;

CREATE TABLE `userPermissions`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `userId` INTEGER(30) DEFAULT 0 NOT NULL,
    `permissionId` INTEGER DEFAULT 0 NOT NULL,
    `created` TIMESTAMP NULL,
    `updated` TIMESTAMP NULL,
    PRIMARY KEY (`id`),
    INDEX `userPermissions_fi_2596c7` (`userId`),
    INDEX `userPermissions_fi_5234cd` (`permissionId`),
    CONSTRAINT `userPermissions_fk_2596c7`
        FOREIGN KEY (`userId`)
        REFERENCES `users` (`id`),
    CONSTRAINT `userPermissions_fk_5234cd`
        FOREIGN KEY (`permissionId`)
        REFERENCES `permissions` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- permissionGroups
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `permissionGroups`;

CREATE TABLE `permissionGroups`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255),
    `description` TEXT,
    `created` TIMESTAMP NULL,
    `updated` TIMESTAMP NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- permissionGroupPermissions
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `permissionGroupPermissions`;

CREATE TABLE `permissionGroupPermissions`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `permissionId` INTEGER(30) DEFAULT 0 NOT NULL,
    `permissionGroupId` INTEGER DEFAULT 0 NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `permissionGroupPermissions_fi_5234cd` (`permissionId`),
    INDEX `permissionGroupPermissions_fi_32d04a` (`permissionGroupId`),
    CONSTRAINT `permissionGroupPermissions_fk_5234cd`
        FOREIGN KEY (`permissionId`)
        REFERENCES `permissions` (`id`),
    CONSTRAINT `permissionGroupPermissions_fk_32d04a`
        FOREIGN KEY (`permissionGroupId`)
        REFERENCES `permissionGroups` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- permissions
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `permissions`;

CREATE TABLE `permissions`
(
    `id` INTEGER(30) NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255),
    `description` TEXT,
    `slug` VARCHAR(10) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- loginFailures
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `loginFailures`;

CREATE TABLE `loginFailures`
(
    `username` VARCHAR(30) NOT NULL,
    `ipAddress` VARCHAR(15) NOT NULL,
    `timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
