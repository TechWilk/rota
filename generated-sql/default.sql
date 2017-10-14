-----------------------------------------------------------------------
-- availability
-----------------------------------------------------------------------

DROP TABLE IF EXISTS [availability];

CREATE TABLE [availability]
(
    [id] INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    [eventId] INTEGER(30) NOT NULL,
    [userId] INTEGER NOT NULL,
    [available] BOOLEAN NOT NULL DEFAULT 1,
    [comment] VARCHAR(64) NOT NULL,
    UNIQUE ([id]),
    FOREIGN KEY ([userId]) REFERENCES [users] ([id]),
    FOREIGN KEY ([eventId]) REFERENCES [events] ([id])
);

-----------------------------------------------------------------------
-- calendarTokens
-----------------------------------------------------------------------

DROP TABLE IF EXISTS [calendarTokens];

CREATE TABLE [calendarTokens]
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
    FOREIGN KEY ([userId]) REFERENCES [users] ([id])
);

-----------------------------------------------------------------------
-- discussion
-----------------------------------------------------------------------

DROP TABLE IF EXISTS [discussion];

CREATE TABLE [discussion]
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
-- discussionCategories
-----------------------------------------------------------------------

DROP TABLE IF EXISTS [discussionCategories];

CREATE TABLE [discussionCategories]
(
    [id] INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    [name] VARCHAR(255) DEFAULT '' NOT NULL,
    [description] MEDIUMTEXT NOT NULL,
    [parent] INTEGER(1) DEFAULT 0 NOT NULL,
    UNIQUE ([id])
);

-----------------------------------------------------------------------
-- documents
-----------------------------------------------------------------------

DROP TABLE IF EXISTS [documents];

CREATE TABLE [documents]
(
    [id] INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    [title] VARCHAR(127) DEFAULT '' NOT NULL,
    [description] MEDIUMTEXT NOT NULL,
    [url] VARCHAR(127) DEFAULT '' NOT NULL,
    [link] MEDIUMTEXT NOT NULL,
    UNIQUE ([id])
);

-----------------------------------------------------------------------
-- emails
-----------------------------------------------------------------------

DROP TABLE IF EXISTS [emails];

CREATE TABLE [emails]
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
-- eventGroups
-----------------------------------------------------------------------

DROP TABLE IF EXISTS [eventGroups];

CREATE TABLE [eventGroups]
(
    [id] INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    [name] VARCHAR(128) DEFAULT '' NOT NULL,
    [description] MEDIUMTEXT NOT NULL,
    [archived] INTEGER(1) DEFAULT 0 NOT NULL,
    UNIQUE ([id])
);

-----------------------------------------------------------------------
-- eventPeople
-----------------------------------------------------------------------

DROP TABLE IF EXISTS [eventPeople];

CREATE TABLE [eventPeople]
(
    [id] INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    [eventId] INTEGER DEFAULT 0 NOT NULL,
    [userRoleId] INTEGER DEFAULT 0 NOT NULL,
    [notified] SMALLINT(1) DEFAULT 0 NOT NULL,
    [removed] SMALLINT(1) DEFAULT 0,
    UNIQUE ([id]),
    FOREIGN KEY ([eventId]) REFERENCES [events] ([id]),
    FOREIGN KEY ([userRoleId]) REFERENCES [userRoles] ([id])
);

-----------------------------------------------------------------------
-- eventSubTypes
-----------------------------------------------------------------------

DROP TABLE IF EXISTS [eventSubTypes];

CREATE TABLE [eventSubTypes]
(
    [id] INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    [name] VARCHAR(128) DEFAULT '' NOT NULL,
    [description] MEDIUMTEXT NOT NULL,
    UNIQUE ([id])
);

-----------------------------------------------------------------------
-- eventTypes
-----------------------------------------------------------------------

DROP TABLE IF EXISTS [eventTypes];

CREATE TABLE [eventTypes]
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
    FOREIGN KEY ([defaultLocationId]) REFERENCES [locations] ([id])
        ON DELETE SET NULL
);

-----------------------------------------------------------------------
-- events
-----------------------------------------------------------------------

DROP TABLE IF EXISTS [events];

CREATE TABLE [events]
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
    FOREIGN KEY ([createdBy]) REFERENCES [users] ([id]),
    FOREIGN KEY ([type]) REFERENCES [eventTypes] ([id]),
    FOREIGN KEY ([subType]) REFERENCES [eventSubTypes] ([id]),
    FOREIGN KEY ([location]) REFERENCES [locations] ([id]),
    FOREIGN KEY ([eventGroup]) REFERENCES [eventGroups] ([id])
);

-----------------------------------------------------------------------
-- groups
-----------------------------------------------------------------------

DROP TABLE IF EXISTS [groups];

CREATE TABLE [groups]
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
-- locations
-----------------------------------------------------------------------

DROP TABLE IF EXISTS [locations];

CREATE TABLE [locations]
(
    [id] INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    [name] MEDIUMTEXT NOT NULL,
    [address] MEDIUMTEXT,
    UNIQUE ([id])
);

-----------------------------------------------------------------------
-- notificationClicks
-----------------------------------------------------------------------

DROP TABLE IF EXISTS [notificationClicks];

CREATE TABLE [notificationClicks]
(
    [id] INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    [notificationId] INTEGER(30) NOT NULL,
    [referer] VARCHAR(50) NOT NULL,
    [timestamp] TIMESTAMP,
    UNIQUE ([id]),
    FOREIGN KEY ([notificationId]) REFERENCES [notifications] ([id])
);

-----------------------------------------------------------------------
-- notifications
-----------------------------------------------------------------------

DROP TABLE IF EXISTS [notifications];

CREATE TABLE [notifications]
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
    FOREIGN KEY ([userId]) REFERENCES [users] ([id])
);

-----------------------------------------------------------------------
-- pendingUsers
-----------------------------------------------------------------------

DROP TABLE IF EXISTS [pendingUsers];

CREATE TABLE [pendingUsers]
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
-- roles
-----------------------------------------------------------------------

DROP TABLE IF EXISTS [roles];

CREATE TABLE [roles]
(
    [id] INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    [groupId] INTEGER(6) DEFAULT 0 NOT NULL,
    [name] VARCHAR(15) DEFAULT '' NOT NULL,
    [description] MEDIUMTEXT NOT NULL,
    [rehersalId] INTEGER(6) DEFAULT 0 NOT NULL,
    [allowRoleSwaps] INTEGER(1),
    UNIQUE ([id]),
    FOREIGN KEY ([groupId]) REFERENCES [groups] ([id])
);

-----------------------------------------------------------------------
-- settings
-----------------------------------------------------------------------

DROP TABLE IF EXISTS [settings];

CREATE TABLE [settings]
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
-- socialAuth
-----------------------------------------------------------------------

DROP TABLE IF EXISTS [socialAuth];

CREATE TABLE [socialAuth]
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
    FOREIGN KEY ([userId]) REFERENCES [users] ([id])
);

-----------------------------------------------------------------------
-- statistics
-----------------------------------------------------------------------

DROP TABLE IF EXISTS [statistics];

CREATE TABLE [statistics]
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
    FOREIGN KEY ([userid]) REFERENCES [users] ([id])
);

-----------------------------------------------------------------------
-- subscriptions
-----------------------------------------------------------------------

DROP TABLE IF EXISTS [subscriptions];

CREATE TABLE [subscriptions]
(
    [id] INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    [userid] INTEGER(6) DEFAULT 0 NOT NULL,
    [categoryid] INTEGER(4) DEFAULT 0 NOT NULL,
    [topicid] INTEGER(4) DEFAULT 0 NOT NULL,
    UNIQUE ([id])
);

-----------------------------------------------------------------------
-- swaps
-----------------------------------------------------------------------

DROP TABLE IF EXISTS [swaps];

CREATE TABLE [swaps]
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
    FOREIGN KEY ([eventPersonId]) REFERENCES [eventPeople] ([id]),
    FOREIGN KEY ([oldUserRoleId]) REFERENCES [userRoles] ([id]),
    FOREIGN KEY ([newUserRoleId]) REFERENCES [userRoles] ([id]),
    FOREIGN KEY ([requestedBy]) REFERENCES [users] ([id])
);

-----------------------------------------------------------------------
-- userRoles
-----------------------------------------------------------------------

DROP TABLE IF EXISTS [userRoles];

CREATE TABLE [userRoles]
(
    [id] INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    [userId] INTEGER(30) DEFAULT 0 NOT NULL,
    [roleId] INTEGER DEFAULT 0 NOT NULL,
    [reserve] INTEGER DEFAULT 0 NOT NULL,
    UNIQUE ([id]),
    FOREIGN KEY ([userId]) REFERENCES [users] ([id]),
    FOREIGN KEY ([roleId]) REFERENCES [roles] ([id])
);

-----------------------------------------------------------------------
-- users
-----------------------------------------------------------------------

DROP TABLE IF EXISTS [users];

CREATE TABLE [users]
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
-- userPermissions
-----------------------------------------------------------------------

DROP TABLE IF EXISTS [userPermissions];

CREATE TABLE [userPermissions]
(
    [id] INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    [userId] INTEGER(30) DEFAULT 0 NOT NULL,
    [permissionId] INTEGER DEFAULT 0 NOT NULL,
    [created] TIMESTAMP,
    [updated] TIMESTAMP,
    UNIQUE ([id]),
    FOREIGN KEY ([userId]) REFERENCES [users] ([id]),
    FOREIGN KEY ([permissionId]) REFERENCES [permissions] ([id])
);

-----------------------------------------------------------------------
-- permissionGroups
-----------------------------------------------------------------------

DROP TABLE IF EXISTS [permissionGroups];

CREATE TABLE [permissionGroups]
(
    [id] INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    [name] VARCHAR(255),
    [description] MEDIUMTEXT,
    [created] TIMESTAMP,
    [updated] TIMESTAMP,
    UNIQUE ([id])
);

-----------------------------------------------------------------------
-- permissionGroupPermissions
-----------------------------------------------------------------------

DROP TABLE IF EXISTS [permissionGroupPermissions];

CREATE TABLE [permissionGroupPermissions]
(
    [id] INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    [permissionId] INTEGER(30) DEFAULT 0 NOT NULL,
    [permissionGroupId] INTEGER DEFAULT 0 NOT NULL,
    UNIQUE ([id]),
    FOREIGN KEY ([permissionId]) REFERENCES [permissions] ([id]),
    FOREIGN KEY ([permissionGroupId]) REFERENCES [permissionGroups] ([id])
);

-----------------------------------------------------------------------
-- permissions
-----------------------------------------------------------------------

DROP TABLE IF EXISTS [permissions];

CREATE TABLE [permissions]
(
    [id] INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    [name] VARCHAR(255),
    [description] MEDIUMTEXT,
    [slug] VARCHAR(10) NOT NULL,
    UNIQUE ([id])
);

-----------------------------------------------------------------------
-- loginFailures
-----------------------------------------------------------------------

DROP TABLE IF EXISTS [loginFailures];

CREATE TABLE [loginFailures]
(
    [username] VARCHAR(30) NOT NULL,
    [ipAddress] VARCHAR(15) NOT NULL,
    [timestamp] TIMESTAMP DEFAULT (datetime(CURRENT_TIMESTAMP, 'localtime')) NOT NULL
);
