<?php

use Propel\Generator\Manager\MigrationManager;

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1494860053.
 * Generated on 2017-05-15 14:54:13 by user
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
     * Get the SQL statements for the Up migration
     *
     * @return array list of the SQL strings to execute for the Up migration
     *               the keys being the datasources
     */
    public function getUpSQL()
    {
        return array(
  'default' => '
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `cr_bandMembers`;

DROP TABLE IF EXISTS `cr_bands`;

RENAME TABLE `cr_pendingusers` TO `cr_pendingUser`;
RENAME TABLE `cr_pendingUser` TO `cr_pendingUsers`;

ALTER TABLE `cr_calendarTokens`

  CHANGE `created` `created` DATETIME,

  ADD `updated` DATETIME AFTER `created`;

ALTER TABLE `cr_calendarTokens` ADD CONSTRAINT `cr_calendarTokens_fk_c9d9b8`
    FOREIGN KEY (`userId`)
    REFERENCES `cr_users` (`id`);

ALTER TABLE `cr_eventPeople`

  CHANGE `removed` `removed` SMALLINT(1) DEFAULT 0;

ALTER TABLE `cr_eventPeople` ADD CONSTRAINT `cr_eventPeople_fk_d8e156`
    FOREIGN KEY (`eventId`)
    REFERENCES `cr_events` (`id`);

ALTER TABLE `cr_eventPeople` ADD CONSTRAINT `cr_eventPeople_fk_a9059a`
    FOREIGN KEY (`userRoleId`)
    REFERENCES `cr_userRoles` (`id`);

ALTER TABLE `cr_eventTypes` ADD CONSTRAINT `cr_eventTypes_fk_021bda`
    FOREIGN KEY (`defaultLocationId`)
    REFERENCES `cr_locations` (`id`)
    ON DELETE SET NULL;

ALTER TABLE `cr_events`

  CHANGE `removed` `removed` SMALLINT(1) DEFAULT 0,

  CHANGE `updated` `updated` DATETIME,

  CHANGE `created` `created` DATETIME;

ALTER TABLE `cr_events` ADD CONSTRAINT `cr_events_fk_90b554`
    FOREIGN KEY (`createdBy`)
    REFERENCES `cr_users` (`id`);

ALTER TABLE `cr_events` ADD CONSTRAINT `cr_events_fk_a77353`
    FOREIGN KEY (`type`)
    REFERENCES `cr_eventTypes` (`id`);

ALTER TABLE `cr_events` ADD CONSTRAINT `cr_events_fk_722b0a`
    FOREIGN KEY (`subType`)
    REFERENCES `cr_eventSubTypes` (`id`);

ALTER TABLE `cr_events` ADD CONSTRAINT `cr_events_fk_4ae72a`
    FOREIGN KEY (`location`)
    REFERENCES `cr_locations` (`id`);

ALTER TABLE `cr_events` ADD CONSTRAINT `cr_events_fk_b0dbef`
    FOREIGN KEY (`eventGroup`)
    REFERENCES `cr_eventGroups` (`id`);

ALTER TABLE `cr_notificationClicks`

  CHANGE `timestamp` `timestamp` DATETIME;

ALTER TABLE `cr_notificationClicks` ADD CONSTRAINT `cr_notificationClicks_fk_ec64f2`
    FOREIGN KEY (`notificationId`)
    REFERENCES `cr_notifications` (`id`);

ALTER TABLE `cr_notifications` ADD CONSTRAINT `cr_notifications_fk_c9d9b8`
    FOREIGN KEY (`userId`)
    REFERENCES `cr_users` (`id`);

ALTER TABLE `cr_roles` ADD CONSTRAINT `cr_roles_fk_ebefa2`
    FOREIGN KEY (`groupId`)
    REFERENCES `cr_groups` (`id`);

ALTER TABLE `cr_socialAuth` ADD CONSTRAINT `cr_socialAuth_fk_c9d9b8`
    FOREIGN KEY (`userId`)
    REFERENCES `cr_users` (`id`);

ALTER TABLE `cr_statistics`

  CHANGE `detail2` `detail2` TEXT NOT NULL,

  CHANGE `detail3` `detail3` TEXT NOT NULL;

ALTER TABLE `cr_statistics` ADD CONSTRAINT `cr_statistics_fk_c9d9b8`
    FOREIGN KEY (`userid`)
    REFERENCES `cr_users` (`id`);

ALTER TABLE `cr_swaps`

  CHANGE `created` `created` DATETIME,

  CHANGE `updated` `updated` DATETIME;

ALTER TABLE `cr_userRoles` ADD CONSTRAINT `cr_userRoles_fk_c9d9b8`
    FOREIGN KEY (`userId`)
    REFERENCES `cr_users` (`id`);

ALTER TABLE `cr_userRoles` ADD CONSTRAINT `cr_userRoles_fk_733299`
    FOREIGN KEY (`roleId`)
    REFERENCES `cr_roles` (`id`);

ALTER TABLE `cr_users`

  CHANGE `created` `created` DATETIME,

  CHANGE `updated` `updated` DATETIME;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

    /**
     * Get the SQL statements for the Down migration
     *
     * @return array list of the SQL strings to execute for the Down migration
     *               the keys being the datasources
     */
    public function getDownSQL()
    {
        return array(
  'default' => '
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

RENAME TABLE `cr_pendingUsers` TO `cr_pendingUser`;
RENAME TABLE `cr_pendingUser` TO `cr_pendingusers`;

ALTER TABLE `cr_calendarTokens` DROP FOREIGN KEY `cr_calendarTokens_fk_c9d9b8`;

ALTER TABLE `cr_calendarTokens`

  CHANGE `created` `created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

  DROP `updated`;

ALTER TABLE `cr_eventPeople` DROP FOREIGN KEY `cr_eventPeople_fk_d8e156`;

ALTER TABLE `cr_eventPeople` DROP FOREIGN KEY `cr_eventPeople_fk_a9059a`;

ALTER TABLE `cr_eventPeople`

  CHANGE `removed` `removed` VARCHAR(2) DEFAULT \'0\';

ALTER TABLE `cr_eventTypes` DROP FOREIGN KEY `cr_eventTypes_fk_021bda`;

ALTER TABLE `cr_events` DROP FOREIGN KEY `cr_events_fk_90b554`;

ALTER TABLE `cr_events` DROP FOREIGN KEY `cr_events_fk_a77353`;

ALTER TABLE `cr_events` DROP FOREIGN KEY `cr_events_fk_722b0a`;

ALTER TABLE `cr_events` DROP FOREIGN KEY `cr_events_fk_4ae72a`;

ALTER TABLE `cr_events` DROP FOREIGN KEY `cr_events_fk_b0dbef`;

ALTER TABLE `cr_events`

  CHANGE `removed` `removed` VARCHAR(2) DEFAULT \'0\',

  CHANGE `updated` `updated` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

  CHANGE `created` `created` DATETIME DEFAULT \'0000-00-00 00:00:00\' NOT NULL;

ALTER TABLE `cr_notificationClicks` DROP FOREIGN KEY `cr_notificationClicks_fk_ec64f2`;

ALTER TABLE `cr_notificationClicks`

  CHANGE `timestamp` `timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP;

ALTER TABLE `cr_notifications` DROP FOREIGN KEY `cr_notifications_fk_c9d9b8`;

ALTER TABLE `cr_roles` DROP FOREIGN KEY `cr_roles_fk_ebefa2`;

ALTER TABLE `cr_socialAuth` DROP FOREIGN KEY `cr_socialAuth_fk_c9d9b8`;

ALTER TABLE `cr_statistics` DROP FOREIGN KEY `cr_statistics_fk_c9d9b8`;

ALTER TABLE `cr_statistics`

  CHANGE `detail2` `detail2` TEXT,

  CHANGE `detail3` `detail3` TEXT;

ALTER TABLE `cr_swaps`

  CHANGE `created` `created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

  CHANGE `updated` `updated` DATETIME NOT NULL;

ALTER TABLE `cr_userRoles` DROP FOREIGN KEY `cr_userRoles_fk_c9d9b8`;

ALTER TABLE `cr_userRoles` DROP FOREIGN KEY `cr_userRoles_fk_733299`;

ALTER TABLE `cr_users`

  CHANGE `created` `created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

  CHANGE `updated` `updated` DATETIME NOT NULL;

CREATE TABLE `cr_bandMembers`
(
    `bandMembersID` INTEGER NOT NULL AUTO_INCREMENT,
    `bandID` INTEGER(4) DEFAULT 0 NOT NULL,
    `skillID` INTEGER(6) DEFAULT 0 NOT NULL,
    PRIMARY KEY (`bandMembersID`)
) ENGINE=InnoDB;

CREATE TABLE `cr_bands`
(
    `bandID` INTEGER NOT NULL AUTO_INCREMENT,
    `bandLeader` VARCHAR(30) DEFAULT \'\' NOT NULL,
    PRIMARY KEY (`bandID`)
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }
}
