<?php

use Propel\Generator\Manager\MigrationManager;

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1494860053.
 * Generated on 2017-05-15 14:54:13 by user.
 */
class PropelMigration_1494860053
{
    public $comment = '';

    public function preUp(MigrationManager $manager)
    {
        // add the pre-migration code here
    }

    public function postUp(MigrationManager $manager)
    {
        // add the post-migration code here
    }

    public function preDown(MigrationManager $manager)
    {
        // add the pre-migration code here
    }

    public function postDown(MigrationManager $manager)
    {
        // add the post-migration code here
    }

    /**
     * Get the SQL statements for the Up migration.
     *
     * @return array list of the SQL strings to execute for the Up migration
     *               the keys being the datasources
     */
    public function getUpSQL()
    {
        return [
  'default' => '
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `bandMembers`;

DROP TABLE IF EXISTS `bands`;

RENAME TABLE `pendingusers` TO `pendingUser`;
RENAME TABLE `pendingUser` TO `pendingUsers`;

ALTER TABLE `calendarTokens`

  CHANGE `created` `created` DATETIME,

  ADD `updated` DATETIME AFTER `created`;

ALTER TABLE `calendarTokens` ADD CONSTRAINT `calendarTokens_fk_c9d9b8`
    FOREIGN KEY (`userId`)
    REFERENCES `users` (`id`);

ALTER TABLE `eventPeople`

  CHANGE `removed` `removed` SMALLINT(1) DEFAULT 0;

ALTER TABLE `eventPeople` ADD CONSTRAINT `eventPeople_fk_d8e156`
    FOREIGN KEY (`eventId`)
    REFERENCES `events` (`id`);

ALTER TABLE `eventPeople` ADD CONSTRAINT `eventPeople_fk_a9059a`
    FOREIGN KEY (`userRoleId`)
    REFERENCES `userRoles` (`id`);

ALTER TABLE `eventTypes` ADD CONSTRAINT `eventTypes_fk_021bda`
    FOREIGN KEY (`defaultLocationId`)
    REFERENCES `locations` (`id`)
    ON DELETE SET NULL;

ALTER TABLE `events`

  CHANGE `removed` `removed` SMALLINT(1) DEFAULT 0,

  CHANGE `updated` `updated` DATETIME,

  CHANGE `created` `created` DATETIME;

ALTER TABLE `events` ADD CONSTRAINT `events_fk_90b554`
    FOREIGN KEY (`createdBy`)
    REFERENCES `users` (`id`);

ALTER TABLE `events` ADD CONSTRAINT `events_fk_a77353`
    FOREIGN KEY (`type`)
    REFERENCES `eventTypes` (`id`);

ALTER TABLE `events` ADD CONSTRAINT `events_fk_722b0a`
    FOREIGN KEY (`subType`)
    REFERENCES `eventSubTypes` (`id`);

ALTER TABLE `events` ADD CONSTRAINT `events_fk_4ae72a`
    FOREIGN KEY (`location`)
    REFERENCES `locations` (`id`);

ALTER TABLE `events` ADD CONSTRAINT `events_fk_b0dbef`
    FOREIGN KEY (`eventGroup`)
    REFERENCES `eventGroups` (`id`);

ALTER TABLE `notificationClicks`

  CHANGE `timestamp` `timestamp` DATETIME;

ALTER TABLE `notificationClicks` ADD CONSTRAINT `notificationClicks_fk_ec64f2`
    FOREIGN KEY (`notificationId`)
    REFERENCES `notifications` (`id`);

ALTER TABLE `notifications` ADD CONSTRAINT `notifications_fk_c9d9b8`
    FOREIGN KEY (`userId`)
    REFERENCES `users` (`id`);

ALTER TABLE `roles` ADD CONSTRAINT `roles_fk_ebefa2`
    FOREIGN KEY (`groupId`)
    REFERENCES `groups` (`id`);

ALTER TABLE `socialAuth` ADD CONSTRAINT `socialAuth_fk_c9d9b8`
    FOREIGN KEY (`userId`)
    REFERENCES `users` (`id`);

ALTER TABLE `statistics`

  CHANGE `detail2` `detail2` TEXT NOT NULL,

  CHANGE `detail3` `detail3` TEXT NOT NULL;

ALTER TABLE `statistics` ADD CONSTRAINT `statistics_fk_c9d9b8`
    FOREIGN KEY (`userid`)
    REFERENCES `users` (`id`);

ALTER TABLE `swaps`

  CHANGE `created` `created` DATETIME,

  CHANGE `updated` `updated` DATETIME;

ALTER TABLE `userRoles` ADD CONSTRAINT `userRoles_fk_c9d9b8`
    FOREIGN KEY (`userId`)
    REFERENCES `users` (`id`);

ALTER TABLE `userRoles` ADD CONSTRAINT `userRoles_fk_733299`
    FOREIGN KEY (`roleId`)
    REFERENCES `roles` (`id`);

ALTER TABLE `users`

  CHANGE `created` `created` DATETIME,

  CHANGE `updated` `updated` DATETIME;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
];
    }

    /**
     * Get the SQL statements for the Down migration.
     *
     * @return array list of the SQL strings to execute for the Down migration
     *               the keys being the datasources
     */
    public function getDownSQL()
    {
        return [
  'default' => '
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

RENAME TABLE `pendingUsers` TO `pendingUser`;
RENAME TABLE `pendingUser` TO `pendingusers`;

ALTER TABLE `calendarTokens` DROP FOREIGN KEY `calendarTokens_fk_c9d9b8`;

ALTER TABLE `calendarTokens`

  CHANGE `created` `created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

  DROP `updated`;

ALTER TABLE `eventPeople` DROP FOREIGN KEY `eventPeople_fk_d8e156`;

ALTER TABLE `eventPeople` DROP FOREIGN KEY `eventPeople_fk_a9059a`;

ALTER TABLE `eventPeople`

  CHANGE `removed` `removed` VARCHAR(2) DEFAULT \'0\';

ALTER TABLE `eventTypes` DROP FOREIGN KEY `eventTypes_fk_021bda`;

ALTER TABLE `events` DROP FOREIGN KEY `events_fk_90b554`;

ALTER TABLE `events` DROP FOREIGN KEY `events_fk_a77353`;

ALTER TABLE `events` DROP FOREIGN KEY `events_fk_722b0a`;

ALTER TABLE `events` DROP FOREIGN KEY `events_fk_4ae72a`;

ALTER TABLE `events` DROP FOREIGN KEY `events_fk_b0dbef`;

ALTER TABLE `events`

  CHANGE `removed` `removed` VARCHAR(2) DEFAULT \'0\',

  CHANGE `updated` `updated` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

  CHANGE `created` `created` DATETIME DEFAULT \'0000-00-00 00:00:00\' NOT NULL;

ALTER TABLE `notificationClicks` DROP FOREIGN KEY `notificationClicks_fk_ec64f2`;

ALTER TABLE `notificationClicks`

  CHANGE `timestamp` `timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP;

ALTER TABLE `notifications` DROP FOREIGN KEY `notifications_fk_c9d9b8`;

ALTER TABLE `roles` DROP FOREIGN KEY `roles_fk_ebefa2`;

ALTER TABLE `socialAuth` DROP FOREIGN KEY `socialAuth_fk_c9d9b8`;

ALTER TABLE `statistics` DROP FOREIGN KEY `statistics_fk_c9d9b8`;

ALTER TABLE `statistics`

  CHANGE `detail2` `detail2` TEXT,

  CHANGE `detail3` `detail3` TEXT;

ALTER TABLE `swaps`

  CHANGE `created` `created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

  CHANGE `updated` `updated` DATETIME NOT NULL;

ALTER TABLE `userRoles` DROP FOREIGN KEY `userRoles_fk_c9d9b8`;

ALTER TABLE `userRoles` DROP FOREIGN KEY `userRoles_fk_733299`;

ALTER TABLE `users`

  CHANGE `created` `created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

  CHANGE `updated` `updated` DATETIME NOT NULL;

CREATE TABLE `bandMembers`
(
    `bandMembersID` INTEGER NOT NULL AUTO_INCREMENT,
    `bandID` INTEGER(4) DEFAULT 0 NOT NULL,
    `skillID` INTEGER(6) DEFAULT 0 NOT NULL,
    PRIMARY KEY (`bandMembersID`)
) ENGINE=InnoDB;

CREATE TABLE `bands`
(
    `bandID` INTEGER NOT NULL AUTO_INCREMENT,
    `bandLeader` VARCHAR(30) DEFAULT \'\' NOT NULL,
    PRIMARY KEY (`bandID`)
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
];
    }
}
