-----------------------------------------------------------------------
-- cr_availability
-----------------------------------------------------------------------

DROP TABLE IF EXISTS [cr_availability];

CREATE TABLE [cr_availability]
(
    [id] INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    [eventId] INTEGER(30) NOT NULL,
    [userId] INTEGER NOT NULL,
    [available] BOOLEAN NOT NULL DEFAULT 1,
    [comment] VARCHAR(64) NOT NULL,
    UNIQUE ([id]),
    FOREIGN KEY ([userId]) REFERENCES [cr_users] ([id]),
    FOREIGN KEY ([eventId]) REFERENCES [cr_events] ([id])
);

-----------------------------------------------------------------------
-- cr_calendarTokens
-----------------------------------------------------------------------

DROP TABLE IF EXISTS [cr_calendarTokens];

CREATE TABLE [cr_calendarTokens]
(
    [id] INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    [token] VARCHAR(30) NOT NULL,
    [userId] INTEGER(30) NOT NULL,
    [format] VARCHAR(5) NOT NULL,
    [description] VARCHAR(100),
    [revoked] INTEGER(1) DEFAULT 0 NOT NULL,
    [revokedDate] TIMESTAMP,
    [lastFetched] TIMESTAMP,
    [created] TIMESTAMP,
    [updated] TIMESTAMP,
    UNIQUE ([token]),
    FOREIGN KEY ([userId]) REFERENCES [cr_users] ([id])
);

-----------------------------------------------------------------------
-- cr_discussion
-----------------------------------------------------------------------

DROP TABLE IF EXISTS [cr_discussion];

CREATE TABLE [cr_discussion]
(
    [id] INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    [topicParent] INTEGER(6) DEFAULT 0 NOT NULL,
    [CategoryParent] INTEGER(6) DEFAULT 0 NOT NULL,
    [userID] INTEGER(6) DEFAULT 0 NOT NULL,
    [topic] MEDIUMTEXT NOT NULL,
    [topicName] MEDIUMTEXT NOT NULL,
    [date] TIMESTAMP DEFAULT '0000-00-00 00:00:00' NOT NULL,
    UNIQUE ([id])
);

-----------------------------------------------------------------------
-- cr_discussionCategories
-----------------------------------------------------------------------

DROP TABLE IF EXISTS [cr_discussionCategories];

CREATE TABLE [cr_discussionCategories]
(
    [id] INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    [name] VARCHAR(255) DEFAULT '' NOT NULL,
    [description] MEDIUMTEXT NOT NULL,
    [parent] INTEGER(1) DEFAULT 0 NOT NULL,
    UNIQUE ([id])
);

-----------------------------------------------------------------------
-- cr_documents
-----------------------------------------------------------------------

DROP TABLE IF EXISTS [cr_documents];

CREATE TABLE [cr_documents]
(
    [id] INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    [title] VARCHAR(127) DEFAULT '' NOT NULL,
    [description] MEDIUMTEXT NOT NULL,
    [url] VARCHAR(127) DEFAULT '' NOT NULL,
    [link] MEDIUMTEXT NOT NULL,
    UNIQUE ([id])
);

-----------------------------------------------------------------------
-- cr_emails
-----------------------------------------------------------------------

DROP TABLE IF EXISTS [cr_emails];

CREATE TABLE [cr_emails]
(
    [id] INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    [emailTo] VARCHAR(100) DEFAULT '' NOT NULL,
    [emailBcc] VARCHAR(100) DEFAULT '' NOT NULL,
    [emailFrom] VARCHAR(100) NOT NULL,
    [subject] VARCHAR(150) NOT NULL,
    [message] MEDIUMTEXT NOT NULL,
    [error] MEDIUMTEXT,
    UNIQUE ([id])
);

-----------------------------------------------------------------------
-- cr_eventGroups
-----------------------------------------------------------------------

DROP TABLE IF EXISTS [cr_eventGroups];

CREATE TABLE [cr_eventGroups]
(
    [id] INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    [name] VARCHAR(128) DEFAULT '' NOT NULL,
    [description] MEDIUMTEXT NOT NULL,
    [archived] INTEGER(1) DEFAULT 0 NOT NULL,
    UNIQUE ([id])
);

-----------------------------------------------------------------------
-- cr_eventPeople
-----------------------------------------------------------------------

DROP TABLE IF EXISTS [cr_eventPeople];

CREATE TABLE [cr_eventPeople]
(
    [id] INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    [eventId] INTEGER DEFAULT 0 NOT NULL,
    [userRoleId] INTEGER DEFAULT 0 NOT NULL,
    [notified] SMALLINT(1) DEFAULT 0 NOT NULL,
    [removed] SMALLINT(1) DEFAULT 0,
    UNIQUE ([id]),
    FOREIGN KEY ([eventId]) REFERENCES [cr_events] ([id]),
    FOREIGN KEY ([userRoleId]) REFERENCES [cr_userRoles] ([id])
);

-----------------------------------------------------------------------
-- cr_eventSubTypes
-----------------------------------------------------------------------

DROP TABLE IF EXISTS [cr_eventSubTypes];

CREATE TABLE [cr_eventSubTypes]
(
    [id] INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    [name] VARCHAR(128) DEFAULT '' NOT NULL,
    [description] MEDIUMTEXT NOT NULL,
    UNIQUE ([id])
);

-----------------------------------------------------------------------
-- cr_eventTypes
-----------------------------------------------------------------------

DROP TABLE IF EXISTS [cr_eventTypes];

CREATE TABLE [cr_eventTypes]
(
    [id] INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    [name] VARCHAR(30) NOT NULL,
    [description] MEDIUMTEXT NOT NULL,
    [defaultDay] INTEGER(1),
    [defaultTime] TIME,
    [defaultRepitition] INTEGER(3),
    [defaultLocationId] INTEGER(30),
    [rehearsal] INTEGER(2) DEFAULT 0 NOT NULL,
    [groupformat] INTEGER(1) DEFAULT 0 NOT NULL,
    UNIQUE ([id]),
    FOREIGN KEY ([defaultLocationId]) REFERENCES [cr_locations] ([id])
        ON DELETE SET NULL
);

-----------------------------------------------------------------------
-- cr_events
-----------------------------------------------------------------------

DROP TABLE IF EXISTS [cr_events];

CREATE TABLE [cr_events]
(
    [id] INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    [date] TIMESTAMP DEFAULT '0000-00-00 00:00:00' NOT NULL,
    [name] MEDIUMTEXT NOT NULL,
    [createdBy] INTEGER(5) DEFAULT 0 NOT NULL,
    [rehearsalDate] TIMESTAMP DEFAULT '0000-00-00 00:00:00' NOT NULL,
    [type] INTEGER(30) DEFAULT 0 NOT NULL,
    [subType] INTEGER(30) DEFAULT 0 NOT NULL,
    [location] INTEGER DEFAULT 0 NOT NULL,
    [notified] INTEGER(2) DEFAULT 0 NOT NULL,
    [rehearsal] INTEGER DEFAULT 0 NOT NULL,
    [comment] MEDIUMTEXT NOT NULL,
    [removed] SMALLINT(1) DEFAULT 0,
    [eventGroup] INTEGER(30) NOT NULL,
    [sermonTitle] VARCHAR(64) DEFAULT '' NOT NULL,
    [bibleVerse] VARCHAR(64) DEFAULT '' NOT NULL,
    [created] TIMESTAMP,
    [updated] TIMESTAMP,
    UNIQUE ([id]),
    FOREIGN KEY ([createdBy]) REFERENCES [cr_users] ([id]),
    FOREIGN KEY ([type]) REFERENCES [cr_eventTypes] ([id]),
    FOREIGN KEY ([subType]) REFERENCES [cr_eventSubTypes] ([id]),
    FOREIGN KEY ([location]) REFERENCES [cr_locations] ([id]),
    FOREIGN KEY ([eventGroup]) REFERENCES [cr_eventGroups] ([id])
);

-----------------------------------------------------------------------
-- cr_groups
-----------------------------------------------------------------------

DROP TABLE IF EXISTS [cr_groups];

CREATE TABLE [cr_groups]
(
    [id] INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    [name] VARCHAR(25) DEFAULT '' NOT NULL,
    [rehearsal] INTEGER(1) DEFAULT 0 NOT NULL,
    [formatgroup] INTEGER(2) DEFAULT 0 NOT NULL,
    [description] MEDIUMTEXT,
    [allowRoleSwaps] INTEGER(1) DEFAULT 1 NOT NULL,
    UNIQUE ([id])
);

-----------------------------------------------------------------------
-- cr_locations
-----------------------------------------------------------------------

DROP TABLE IF EXISTS [cr_locations];

CREATE TABLE [cr_locations]
(
    [id] INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    [name] MEDIUMTEXT NOT NULL,
    [address] MEDIUMTEXT,
    UNIQUE ([id])
);

-----------------------------------------------------------------------
-- cr_notificationClicks
-----------------------------------------------------------------------

DROP TABLE IF EXISTS [cr_notificationClicks];

CREATE TABLE [cr_notificationClicks]
(
    [id] INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    [notificationId] INTEGER(30) NOT NULL,
    [referer] VARCHAR(50) NOT NULL,
    [timestamp] TIMESTAMP,
    UNIQUE ([id]),
    FOREIGN KEY ([notificationId]) REFERENCES [cr_notifications] ([id])
);

-----------------------------------------------------------------------
-- cr_notifications
-----------------------------------------------------------------------

DROP TABLE IF EXISTS [cr_notifications];

CREATE TABLE [cr_notifications]
(
    [id] INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    [timestamp] TIMESTAMP DEFAULT (datetime(CURRENT_TIMESTAMP, 'localtime')) NOT NULL,
    [userId] INTEGER(30) NOT NULL,
    [summary] VARCHAR(40) NOT NULL,
    [body] MEDIUMTEXT NOT NULL,
    [link] VARCHAR(150),
    [type] INTEGER(2) NOT NULL,
    [seen] INTEGER(1) DEFAULT 0 NOT NULL,
    [dismissed] INTEGER(1) DEFAULT 0 NOT NULL,
    [archived] INTEGER(1) DEFAULT 0 NOT NULL,
    UNIQUE ([id]),
    FOREIGN KEY ([userId]) REFERENCES [cr_users] ([id])
);

-----------------------------------------------------------------------
-- cr_pendingUsers
-----------------------------------------------------------------------

DROP TABLE IF EXISTS [cr_pendingUsers];

CREATE TABLE [cr_pendingUsers]
(
    [id] INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    [socialId] BIGINT(30),
    [firstName] VARCHAR(100) NOT NULL,
    [lastName] VARCHAR(100) NOT NULL,
    [email] VARCHAR(100) NOT NULL,
    [approved] INTEGER(1) DEFAULT 0 NOT NULL,
    [declined] INTEGER(1) DEFAULT 0 NOT NULL,
    [source] VARCHAR(50) NOT NULL,
    UNIQUE ([socialId]),
    UNIQUE ([id])
);

-----------------------------------------------------------------------
-- cr_roles
-----------------------------------------------------------------------

DROP TABLE IF EXISTS [cr_roles];

CREATE TABLE [cr_roles]
(
    [id] INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    [groupId] INTEGER(6) DEFAULT 0 NOT NULL,
    [name] VARCHAR(15) DEFAULT '' NOT NULL,
    [description] MEDIUMTEXT NOT NULL,
    [rehersalId] INTEGER(6) DEFAULT 0 NOT NULL,
    [allowRoleSwaps] INTEGER(1),
    UNIQUE ([id]),
    FOREIGN KEY ([groupId]) REFERENCES [cr_groups] ([id])
);

-----------------------------------------------------------------------
-- cr_settings
-----------------------------------------------------------------------

DROP TABLE IF EXISTS [cr_settings];

CREATE TABLE [cr_settings]
(
    [siteurl] MEDIUMTEXT NOT NULL,
    [owner] MEDIUMTEXT NOT NULL,
    [notificationemail] MEDIUMTEXT,
    [adminemailaddress] MEDIUMTEXT,
    [norehearsalemail] MEDIUMTEXT,
    [yesrehearsal] MEDIUMTEXT,
    [newusermessage] MEDIUMTEXT,
    [version] VARCHAR(20),
    [lang_locale] VARCHAR(20),
    [event_sorting_latest] INTEGER(1),
    [snapshot_show_two_month] INTEGER(1),
    [snapshot_reduce_skills_by_group] INTEGER(1),
    [logged_in_show_snapshot_button] INTEGER(1),
    [time_format_long] VARCHAR(50),
    [time_format_normal] VARCHAR(50),
    [time_format_short] VARCHAR(50),
    [time_only_format] VARCHAR(20),
    [date_only_format] VARCHAR(20),
    [day_only_format] VARCHAR(20),
    [users_start_with_myevents] INTEGER(1),
    [time_zone] VARCHAR(50),
    [google_group_calendar] VARCHAR(100),
    [overviewemail] MEDIUMTEXT,
    [group_sorting_name] INTEGER(1),
    [debug_mode] INTEGER(1) DEFAULT 0,
    [days_to_alert] INTEGER(2) DEFAULT 5,
    [token] VARCHAR(100) DEFAULT '',
    [skin] VARCHAR(20) DEFAULT ''
);

-----------------------------------------------------------------------
-- cr_socialAuth
-----------------------------------------------------------------------

DROP TABLE IF EXISTS [cr_socialAuth];

CREATE TABLE [cr_socialAuth]
(
    [userId] INTEGER(30) NOT NULL,
    [platform] MEDIUMTEXT NOT NULL,
    [socialId] BIGINT(30) NOT NULL,
    [meta] MEDIUMTEXT,
    [revoked] INTEGER(1) DEFAULT 0 NOT NULL,
    PRIMARY KEY ([userId], [platform], [socialId]),
    UNIQUE ([socialId]),
    UNIQUE ([userId]),
    UNIQUE ([platform]),
    FOREIGN KEY ([userId]) REFERENCES [cr_users] ([id])
);

-----------------------------------------------------------------------
-- cr_statistics
-----------------------------------------------------------------------

DROP TABLE IF EXISTS [cr_statistics];

CREATE TABLE [cr_statistics]
(
    [id] INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    [userid] INTEGER(6) DEFAULT 0,
    [date] TIMESTAMP DEFAULT '0000-00-00 00:00:00' NOT NULL,
    [type] MEDIUMTEXT NOT NULL,
    [detail1] MEDIUMTEXT NOT NULL,
    [detail2] MEDIUMTEXT NOT NULL,
    [detail3] MEDIUMTEXT NOT NULL,
    [script] MEDIUMTEXT NOT NULL,
    UNIQUE ([id]),
    FOREIGN KEY ([userid]) REFERENCES [cr_users] ([id])
);

-----------------------------------------------------------------------
-- cr_subscriptions
-----------------------------------------------------------------------

DROP TABLE IF EXISTS [cr_subscriptions];

CREATE TABLE [cr_subscriptions]
(
    [id] INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    [userid] INTEGER(6) DEFAULT 0 NOT NULL,
    [categoryid] INTEGER(4) DEFAULT 0 NOT NULL,
    [topicid] INTEGER(4) DEFAULT 0 NOT NULL,
    UNIQUE ([id])
);

-----------------------------------------------------------------------
-- cr_swaps
-----------------------------------------------------------------------

DROP TABLE IF EXISTS [cr_swaps];

CREATE TABLE [cr_swaps]
(
    [id] INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    [eventPersonId] INTEGER DEFAULT 0 NOT NULL,
    [oldUserRoleId] INTEGER DEFAULT 0 NOT NULL,
    [newUserRoleId] INTEGER DEFAULT 0 NOT NULL,
    [accepted] INTEGER(1) DEFAULT 0 NOT NULL,
    [declined] INTEGER(1) DEFAULT 0 NOT NULL,
    [requestedBy] INTEGER NOT NULL,
    [verificationCode] VARCHAR(18) NOT NULL,
    [created] TIMESTAMP,
    [updated] TIMESTAMP,
    UNIQUE ([id]),
    FOREIGN KEY ([eventPersonId]) REFERENCES [cr_eventPeople] ([id]),
    FOREIGN KEY ([oldUserRoleId]) REFERENCES [cr_userRoles] ([id]),
    FOREIGN KEY ([newUserRoleId]) REFERENCES [cr_userRoles] ([id]),
    FOREIGN KEY ([requestedBy]) REFERENCES [cr_users] ([id])
);

-----------------------------------------------------------------------
-- cr_userRoles
-----------------------------------------------------------------------

DROP TABLE IF EXISTS [cr_userRoles];

CREATE TABLE [cr_userRoles]
(
    [id] INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    [userId] INTEGER(30) DEFAULT 0 NOT NULL,
    [roleId] INTEGER DEFAULT 0 NOT NULL,
    [reserve] INTEGER DEFAULT 0 NOT NULL,
    UNIQUE ([id]),
    FOREIGN KEY ([userId]) REFERENCES [cr_users] ([id]),
    FOREIGN KEY ([roleId]) REFERENCES [cr_roles] ([id])
);

-----------------------------------------------------------------------
-- cr_users
-----------------------------------------------------------------------

DROP TABLE IF EXISTS [cr_users];

CREATE TABLE [cr_users]
(
    [id] INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    [firstName] VARCHAR(30) DEFAULT '' NOT NULL,
    [lastName] VARCHAR(30) DEFAULT '' NOT NULL,
    [username] VARCHAR(30) DEFAULT '' NOT NULL,
    [password] VARCHAR(200) DEFAULT '' NOT NULL,
    [isAdmin] CHAR(2) DEFAULT '0' NOT NULL,
    [email] VARCHAR(255),
    [mobile] VARCHAR(15) DEFAULT '' NOT NULL,
    [isOverviewRecipient] CHAR(2) DEFAULT '0' NOT NULL,
    [recieveReminderEmails] INTEGER(1) DEFAULT 1 NOT NULL,
    [isBandAdmin] CHAR(2) DEFAULT '0' NOT NULL,
    [isEventEditor] CHAR(2) DEFAULT '0' NOT NULL,
    [lastLogin] TIMESTAMP,
    [passwordChanged] TIMESTAMP,
    [created] TIMESTAMP,
    [updated] TIMESTAMP,
    UNIQUE ([id])
);

-----------------------------------------------------------------------
-- cr_userPermissions
-----------------------------------------------------------------------

DROP TABLE IF EXISTS [cr_userPermissions];

CREATE TABLE [cr_userPermissions]
(
    [id] INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    [userId] INTEGER(30) DEFAULT 0 NOT NULL,
    [permissionId] INTEGER DEFAULT 0 NOT NULL,
    [created] TIMESTAMP,
    [updated] TIMESTAMP,
    UNIQUE ([id]),
    FOREIGN KEY ([userId]) REFERENCES [cr_users] ([id]),
    FOREIGN KEY ([permissionId]) REFERENCES [cr_permissions] ([id])
);

-----------------------------------------------------------------------
-- cr_permissionGroups
-----------------------------------------------------------------------

DROP TABLE IF EXISTS [cr_permissionGroups];

CREATE TABLE [cr_permissionGroups]
(
    [id] INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    [name] VARCHAR(255),
    [description] MEDIUMTEXT,
    [created] TIMESTAMP,
    [updated] TIMESTAMP,
    UNIQUE ([id])
);

-----------------------------------------------------------------------
-- cr_permissionGroupPermissions
-----------------------------------------------------------------------

DROP TABLE IF EXISTS [cr_permissionGroupPermissions];

CREATE TABLE [cr_permissionGroupPermissions]
(
    [id] INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    [permissionId] INTEGER(30) DEFAULT 0 NOT NULL,
    [permissionGroupId] INTEGER DEFAULT 0 NOT NULL,
    UNIQUE ([id]),
    FOREIGN KEY ([permissionId]) REFERENCES [cr_permissions] ([id]),
    FOREIGN KEY ([permissionGroupId]) REFERENCES [cr_permissionGroups] ([id])
);

-----------------------------------------------------------------------
-- cr_permissions
-----------------------------------------------------------------------

DROP TABLE IF EXISTS [cr_permissions];

CREATE TABLE [cr_permissions]
(
    [id] INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    [name] VARCHAR(255),
    [description] MEDIUMTEXT,
    [slug] VARCHAR(10) NOT NULL,
    UNIQUE ([id])
);

-----------------------------------------------------------------------
-- cr_loginFailures
-----------------------------------------------------------------------

DROP TABLE IF EXISTS [cr_loginFailures];

CREATE TABLE [cr_loginFailures]
(
    [username] VARCHAR(30) NOT NULL,
    [ipAddress] VARCHAR(15) NOT NULL,
    [timestamp] TIMESTAMP DEFAULT (datetime(CURRENT_TIMESTAMP, 'localtime')) NOT NULL
);
