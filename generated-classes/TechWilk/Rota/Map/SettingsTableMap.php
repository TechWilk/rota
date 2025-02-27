<?php

namespace TechWilk\Rota\Map;

use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\InstancePoolTrait;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\DataFetcher\DataFetcherInterface;
use Propel\Runtime\Exception\LogicException;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\RelationMap;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Map\TableMapTrait;
use TechWilk\Rota\Settings;
use TechWilk\Rota\SettingsQuery;


/**
 * This class defines the structure of the 'settings' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 */
class SettingsTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    public const CLASS_NAME = 'TechWilk.Rota.Map.SettingsTableMap';

    /**
     * The default database name for this class
     */
    public const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    public const TABLE_NAME = 'settings';

    /**
     * The PHP name of this class (PascalCase)
     */
    public const TABLE_PHP_NAME = 'Settings';

    /**
     * The related Propel class for this table
     */
    public const OM_CLASS = '\\TechWilk\\Rota\\Settings';

    /**
     * A class that can be returned by this tableMap
     */
    public const CLASS_DEFAULT = 'TechWilk.Rota.Settings';

    /**
     * The total number of columns
     */
    public const NUM_COLUMNS = 28;

    /**
     * The number of lazy-loaded columns
     */
    public const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    public const NUM_HYDRATE_COLUMNS = 28;

    /**
     * the column name for the siteurl field
     */
    public const COL_SITEURL = 'settings.siteurl';

    /**
     * the column name for the owner field
     */
    public const COL_OWNER = 'settings.owner';

    /**
     * the column name for the notificationemail field
     */
    public const COL_NOTIFICATIONEMAIL = 'settings.notificationemail';

    /**
     * the column name for the adminemailaddress field
     */
    public const COL_ADMINEMAILADDRESS = 'settings.adminemailaddress';

    /**
     * the column name for the norehearsalemail field
     */
    public const COL_NOREHEARSALEMAIL = 'settings.norehearsalemail';

    /**
     * the column name for the yesrehearsal field
     */
    public const COL_YESREHEARSAL = 'settings.yesrehearsal';

    /**
     * the column name for the newusermessage field
     */
    public const COL_NEWUSERMESSAGE = 'settings.newusermessage';

    /**
     * the column name for the version field
     */
    public const COL_VERSION = 'settings.version';

    /**
     * the column name for the lang_locale field
     */
    public const COL_LANG_LOCALE = 'settings.lang_locale';

    /**
     * the column name for the event_sorting_latest field
     */
    public const COL_EVENT_SORTING_LATEST = 'settings.event_sorting_latest';

    /**
     * the column name for the snapshot_show_two_month field
     */
    public const COL_SNAPSHOT_SHOW_TWO_MONTH = 'settings.snapshot_show_two_month';

    /**
     * the column name for the snapshot_reduce_skills_by_group field
     */
    public const COL_SNAPSHOT_REDUCE_SKILLS_BY_GROUP = 'settings.snapshot_reduce_skills_by_group';

    /**
     * the column name for the logged_in_show_snapshot_button field
     */
    public const COL_LOGGED_IN_SHOW_SNAPSHOT_BUTTON = 'settings.logged_in_show_snapshot_button';

    /**
     * the column name for the time_format_long field
     */
    public const COL_TIME_FORMAT_LONG = 'settings.time_format_long';

    /**
     * the column name for the time_format_normal field
     */
    public const COL_TIME_FORMAT_NORMAL = 'settings.time_format_normal';

    /**
     * the column name for the time_format_short field
     */
    public const COL_TIME_FORMAT_SHORT = 'settings.time_format_short';

    /**
     * the column name for the time_only_format field
     */
    public const COL_TIME_ONLY_FORMAT = 'settings.time_only_format';

    /**
     * the column name for the date_only_format field
     */
    public const COL_DATE_ONLY_FORMAT = 'settings.date_only_format';

    /**
     * the column name for the day_only_format field
     */
    public const COL_DAY_ONLY_FORMAT = 'settings.day_only_format';

    /**
     * the column name for the users_start_with_myevents field
     */
    public const COL_USERS_START_WITH_MYEVENTS = 'settings.users_start_with_myevents';

    /**
     * the column name for the time_zone field
     */
    public const COL_TIME_ZONE = 'settings.time_zone';

    /**
     * the column name for the google_group_calendar field
     */
    public const COL_GOOGLE_GROUP_CALENDAR = 'settings.google_group_calendar';

    /**
     * the column name for the overviewemail field
     */
    public const COL_OVERVIEWEMAIL = 'settings.overviewemail';

    /**
     * the column name for the group_sorting_name field
     */
    public const COL_GROUP_SORTING_NAME = 'settings.group_sorting_name';

    /**
     * the column name for the debug_mode field
     */
    public const COL_DEBUG_MODE = 'settings.debug_mode';

    /**
     * the column name for the days_to_alert field
     */
    public const COL_DAYS_TO_ALERT = 'settings.days_to_alert';

    /**
     * the column name for the token field
     */
    public const COL_TOKEN = 'settings.token';

    /**
     * the column name for the skin field
     */
    public const COL_SKIN = 'settings.skin';

    /**
     * The default string format for model objects of the related table
     */
    public const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
     *
     * @var array<string, mixed>
     */
    protected static $fieldNames = [
        self::TYPE_PHPNAME       => ['SiteUrl', 'Owner', 'NotificationEmail', 'AdminEmailAddress', 'NoRehearsalEmail', 'YesRehearsal', 'NewUserMessage', 'Version', 'LangLocale', 'EventSortingLatest', 'SnapshotShowTwoMonth', 'SnapshotReduceSkillsByGroup', 'LoggedInShowSnapshotButton', 'TimeFormatLong', 'TimeFormatNormal', 'TimeFormatShort', 'TimeOnlyFormat', 'DateOnlyFormat', 'DayOnlyFormat', 'UsersStartWithMyEvents', 'TimeZone', 'GoogleGroupCalendar', 'OverviewEmail', 'GroupSortingName', 'DebugMode', 'DaysToAlert', 'Token', 'Skin', ],
        self::TYPE_CAMELNAME     => ['siteUrl', 'owner', 'notificationEmail', 'adminEmailAddress', 'noRehearsalEmail', 'yesRehearsal', 'newUserMessage', 'version', 'langLocale', 'eventSortingLatest', 'snapshotShowTwoMonth', 'snapshotReduceSkillsByGroup', 'loggedInShowSnapshotButton', 'timeFormatLong', 'timeFormatNormal', 'timeFormatShort', 'timeOnlyFormat', 'dateOnlyFormat', 'dayOnlyFormat', 'usersStartWithMyEvents', 'timeZone', 'googleGroupCalendar', 'overviewEmail', 'groupSortingName', 'debugMode', 'daysToAlert', 'token', 'skin', ],
        self::TYPE_COLNAME       => [SettingsTableMap::COL_SITEURL, SettingsTableMap::COL_OWNER, SettingsTableMap::COL_NOTIFICATIONEMAIL, SettingsTableMap::COL_ADMINEMAILADDRESS, SettingsTableMap::COL_NOREHEARSALEMAIL, SettingsTableMap::COL_YESREHEARSAL, SettingsTableMap::COL_NEWUSERMESSAGE, SettingsTableMap::COL_VERSION, SettingsTableMap::COL_LANG_LOCALE, SettingsTableMap::COL_EVENT_SORTING_LATEST, SettingsTableMap::COL_SNAPSHOT_SHOW_TWO_MONTH, SettingsTableMap::COL_SNAPSHOT_REDUCE_SKILLS_BY_GROUP, SettingsTableMap::COL_LOGGED_IN_SHOW_SNAPSHOT_BUTTON, SettingsTableMap::COL_TIME_FORMAT_LONG, SettingsTableMap::COL_TIME_FORMAT_NORMAL, SettingsTableMap::COL_TIME_FORMAT_SHORT, SettingsTableMap::COL_TIME_ONLY_FORMAT, SettingsTableMap::COL_DATE_ONLY_FORMAT, SettingsTableMap::COL_DAY_ONLY_FORMAT, SettingsTableMap::COL_USERS_START_WITH_MYEVENTS, SettingsTableMap::COL_TIME_ZONE, SettingsTableMap::COL_GOOGLE_GROUP_CALENDAR, SettingsTableMap::COL_OVERVIEWEMAIL, SettingsTableMap::COL_GROUP_SORTING_NAME, SettingsTableMap::COL_DEBUG_MODE, SettingsTableMap::COL_DAYS_TO_ALERT, SettingsTableMap::COL_TOKEN, SettingsTableMap::COL_SKIN, ],
        self::TYPE_FIELDNAME     => ['siteurl', 'owner', 'notificationemail', 'adminemailaddress', 'norehearsalemail', 'yesrehearsal', 'newusermessage', 'version', 'lang_locale', 'event_sorting_latest', 'snapshot_show_two_month', 'snapshot_reduce_skills_by_group', 'logged_in_show_snapshot_button', 'time_format_long', 'time_format_normal', 'time_format_short', 'time_only_format', 'date_only_format', 'day_only_format', 'users_start_with_myevents', 'time_zone', 'google_group_calendar', 'overviewemail', 'group_sorting_name', 'debug_mode', 'days_to_alert', 'token', 'skin', ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, ]
    ];

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     *
     * @var array<string, mixed>
     */
    protected static $fieldKeys = [
        self::TYPE_PHPNAME       => ['SiteUrl' => 0, 'Owner' => 1, 'NotificationEmail' => 2, 'AdminEmailAddress' => 3, 'NoRehearsalEmail' => 4, 'YesRehearsal' => 5, 'NewUserMessage' => 6, 'Version' => 7, 'LangLocale' => 8, 'EventSortingLatest' => 9, 'SnapshotShowTwoMonth' => 10, 'SnapshotReduceSkillsByGroup' => 11, 'LoggedInShowSnapshotButton' => 12, 'TimeFormatLong' => 13, 'TimeFormatNormal' => 14, 'TimeFormatShort' => 15, 'TimeOnlyFormat' => 16, 'DateOnlyFormat' => 17, 'DayOnlyFormat' => 18, 'UsersStartWithMyEvents' => 19, 'TimeZone' => 20, 'GoogleGroupCalendar' => 21, 'OverviewEmail' => 22, 'GroupSortingName' => 23, 'DebugMode' => 24, 'DaysToAlert' => 25, 'Token' => 26, 'Skin' => 27, ],
        self::TYPE_CAMELNAME     => ['siteUrl' => 0, 'owner' => 1, 'notificationEmail' => 2, 'adminEmailAddress' => 3, 'noRehearsalEmail' => 4, 'yesRehearsal' => 5, 'newUserMessage' => 6, 'version' => 7, 'langLocale' => 8, 'eventSortingLatest' => 9, 'snapshotShowTwoMonth' => 10, 'snapshotReduceSkillsByGroup' => 11, 'loggedInShowSnapshotButton' => 12, 'timeFormatLong' => 13, 'timeFormatNormal' => 14, 'timeFormatShort' => 15, 'timeOnlyFormat' => 16, 'dateOnlyFormat' => 17, 'dayOnlyFormat' => 18, 'usersStartWithMyEvents' => 19, 'timeZone' => 20, 'googleGroupCalendar' => 21, 'overviewEmail' => 22, 'groupSortingName' => 23, 'debugMode' => 24, 'daysToAlert' => 25, 'token' => 26, 'skin' => 27, ],
        self::TYPE_COLNAME       => [SettingsTableMap::COL_SITEURL => 0, SettingsTableMap::COL_OWNER => 1, SettingsTableMap::COL_NOTIFICATIONEMAIL => 2, SettingsTableMap::COL_ADMINEMAILADDRESS => 3, SettingsTableMap::COL_NOREHEARSALEMAIL => 4, SettingsTableMap::COL_YESREHEARSAL => 5, SettingsTableMap::COL_NEWUSERMESSAGE => 6, SettingsTableMap::COL_VERSION => 7, SettingsTableMap::COL_LANG_LOCALE => 8, SettingsTableMap::COL_EVENT_SORTING_LATEST => 9, SettingsTableMap::COL_SNAPSHOT_SHOW_TWO_MONTH => 10, SettingsTableMap::COL_SNAPSHOT_REDUCE_SKILLS_BY_GROUP => 11, SettingsTableMap::COL_LOGGED_IN_SHOW_SNAPSHOT_BUTTON => 12, SettingsTableMap::COL_TIME_FORMAT_LONG => 13, SettingsTableMap::COL_TIME_FORMAT_NORMAL => 14, SettingsTableMap::COL_TIME_FORMAT_SHORT => 15, SettingsTableMap::COL_TIME_ONLY_FORMAT => 16, SettingsTableMap::COL_DATE_ONLY_FORMAT => 17, SettingsTableMap::COL_DAY_ONLY_FORMAT => 18, SettingsTableMap::COL_USERS_START_WITH_MYEVENTS => 19, SettingsTableMap::COL_TIME_ZONE => 20, SettingsTableMap::COL_GOOGLE_GROUP_CALENDAR => 21, SettingsTableMap::COL_OVERVIEWEMAIL => 22, SettingsTableMap::COL_GROUP_SORTING_NAME => 23, SettingsTableMap::COL_DEBUG_MODE => 24, SettingsTableMap::COL_DAYS_TO_ALERT => 25, SettingsTableMap::COL_TOKEN => 26, SettingsTableMap::COL_SKIN => 27, ],
        self::TYPE_FIELDNAME     => ['siteurl' => 0, 'owner' => 1, 'notificationemail' => 2, 'adminemailaddress' => 3, 'norehearsalemail' => 4, 'yesrehearsal' => 5, 'newusermessage' => 6, 'version' => 7, 'lang_locale' => 8, 'event_sorting_latest' => 9, 'snapshot_show_two_month' => 10, 'snapshot_reduce_skills_by_group' => 11, 'logged_in_show_snapshot_button' => 12, 'time_format_long' => 13, 'time_format_normal' => 14, 'time_format_short' => 15, 'time_only_format' => 16, 'date_only_format' => 17, 'day_only_format' => 18, 'users_start_with_myevents' => 19, 'time_zone' => 20, 'google_group_calendar' => 21, 'overviewemail' => 22, 'group_sorting_name' => 23, 'debug_mode' => 24, 'days_to_alert' => 25, 'token' => 26, 'skin' => 27, ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, ]
    ];

    /**
     * Holds a list of column names and their normalized version.
     *
     * @var array<string>
     */
    protected $normalizedColumnNameMap = [
        'SiteUrl' => 'SITEURL',
        'Settings.SiteUrl' => 'SITEURL',
        'siteUrl' => 'SITEURL',
        'settings.siteUrl' => 'SITEURL',
        'SettingsTableMap::COL_SITEURL' => 'SITEURL',
        'COL_SITEURL' => 'SITEURL',
        'siteurl' => 'SITEURL',
        'settings.siteurl' => 'SITEURL',
        'Owner' => 'OWNER',
        'Settings.Owner' => 'OWNER',
        'owner' => 'OWNER',
        'settings.owner' => 'OWNER',
        'SettingsTableMap::COL_OWNER' => 'OWNER',
        'COL_OWNER' => 'OWNER',
        'NotificationEmail' => 'NOTIFICATIONEMAIL',
        'Settings.NotificationEmail' => 'NOTIFICATIONEMAIL',
        'notificationEmail' => 'NOTIFICATIONEMAIL',
        'settings.notificationEmail' => 'NOTIFICATIONEMAIL',
        'SettingsTableMap::COL_NOTIFICATIONEMAIL' => 'NOTIFICATIONEMAIL',
        'COL_NOTIFICATIONEMAIL' => 'NOTIFICATIONEMAIL',
        'notificationemail' => 'NOTIFICATIONEMAIL',
        'settings.notificationemail' => 'NOTIFICATIONEMAIL',
        'AdminEmailAddress' => 'ADMINEMAILADDRESS',
        'Settings.AdminEmailAddress' => 'ADMINEMAILADDRESS',
        'adminEmailAddress' => 'ADMINEMAILADDRESS',
        'settings.adminEmailAddress' => 'ADMINEMAILADDRESS',
        'SettingsTableMap::COL_ADMINEMAILADDRESS' => 'ADMINEMAILADDRESS',
        'COL_ADMINEMAILADDRESS' => 'ADMINEMAILADDRESS',
        'adminemailaddress' => 'ADMINEMAILADDRESS',
        'settings.adminemailaddress' => 'ADMINEMAILADDRESS',
        'NoRehearsalEmail' => 'NOREHEARSALEMAIL',
        'Settings.NoRehearsalEmail' => 'NOREHEARSALEMAIL',
        'noRehearsalEmail' => 'NOREHEARSALEMAIL',
        'settings.noRehearsalEmail' => 'NOREHEARSALEMAIL',
        'SettingsTableMap::COL_NOREHEARSALEMAIL' => 'NOREHEARSALEMAIL',
        'COL_NOREHEARSALEMAIL' => 'NOREHEARSALEMAIL',
        'norehearsalemail' => 'NOREHEARSALEMAIL',
        'settings.norehearsalemail' => 'NOREHEARSALEMAIL',
        'YesRehearsal' => 'YESREHEARSAL',
        'Settings.YesRehearsal' => 'YESREHEARSAL',
        'yesRehearsal' => 'YESREHEARSAL',
        'settings.yesRehearsal' => 'YESREHEARSAL',
        'SettingsTableMap::COL_YESREHEARSAL' => 'YESREHEARSAL',
        'COL_YESREHEARSAL' => 'YESREHEARSAL',
        'yesrehearsal' => 'YESREHEARSAL',
        'settings.yesrehearsal' => 'YESREHEARSAL',
        'NewUserMessage' => 'NEWUSERMESSAGE',
        'Settings.NewUserMessage' => 'NEWUSERMESSAGE',
        'newUserMessage' => 'NEWUSERMESSAGE',
        'settings.newUserMessage' => 'NEWUSERMESSAGE',
        'SettingsTableMap::COL_NEWUSERMESSAGE' => 'NEWUSERMESSAGE',
        'COL_NEWUSERMESSAGE' => 'NEWUSERMESSAGE',
        'newusermessage' => 'NEWUSERMESSAGE',
        'settings.newusermessage' => 'NEWUSERMESSAGE',
        'Version' => 'VERSION',
        'Settings.Version' => 'VERSION',
        'version' => 'VERSION',
        'settings.version' => 'VERSION',
        'SettingsTableMap::COL_VERSION' => 'VERSION',
        'COL_VERSION' => 'VERSION',
        'LangLocale' => 'LANG_LOCALE',
        'Settings.LangLocale' => 'LANG_LOCALE',
        'langLocale' => 'LANG_LOCALE',
        'settings.langLocale' => 'LANG_LOCALE',
        'SettingsTableMap::COL_LANG_LOCALE' => 'LANG_LOCALE',
        'COL_LANG_LOCALE' => 'LANG_LOCALE',
        'lang_locale' => 'LANG_LOCALE',
        'settings.lang_locale' => 'LANG_LOCALE',
        'EventSortingLatest' => 'EVENT_SORTING_LATEST',
        'Settings.EventSortingLatest' => 'EVENT_SORTING_LATEST',
        'eventSortingLatest' => 'EVENT_SORTING_LATEST',
        'settings.eventSortingLatest' => 'EVENT_SORTING_LATEST',
        'SettingsTableMap::COL_EVENT_SORTING_LATEST' => 'EVENT_SORTING_LATEST',
        'COL_EVENT_SORTING_LATEST' => 'EVENT_SORTING_LATEST',
        'event_sorting_latest' => 'EVENT_SORTING_LATEST',
        'settings.event_sorting_latest' => 'EVENT_SORTING_LATEST',
        'SnapshotShowTwoMonth' => 'SNAPSHOT_SHOW_TWO_MONTH',
        'Settings.SnapshotShowTwoMonth' => 'SNAPSHOT_SHOW_TWO_MONTH',
        'snapshotShowTwoMonth' => 'SNAPSHOT_SHOW_TWO_MONTH',
        'settings.snapshotShowTwoMonth' => 'SNAPSHOT_SHOW_TWO_MONTH',
        'SettingsTableMap::COL_SNAPSHOT_SHOW_TWO_MONTH' => 'SNAPSHOT_SHOW_TWO_MONTH',
        'COL_SNAPSHOT_SHOW_TWO_MONTH' => 'SNAPSHOT_SHOW_TWO_MONTH',
        'snapshot_show_two_month' => 'SNAPSHOT_SHOW_TWO_MONTH',
        'settings.snapshot_show_two_month' => 'SNAPSHOT_SHOW_TWO_MONTH',
        'SnapshotReduceSkillsByGroup' => 'SNAPSHOT_REDUCE_SKILLS_BY_GROUP',
        'Settings.SnapshotReduceSkillsByGroup' => 'SNAPSHOT_REDUCE_SKILLS_BY_GROUP',
        'snapshotReduceSkillsByGroup' => 'SNAPSHOT_REDUCE_SKILLS_BY_GROUP',
        'settings.snapshotReduceSkillsByGroup' => 'SNAPSHOT_REDUCE_SKILLS_BY_GROUP',
        'SettingsTableMap::COL_SNAPSHOT_REDUCE_SKILLS_BY_GROUP' => 'SNAPSHOT_REDUCE_SKILLS_BY_GROUP',
        'COL_SNAPSHOT_REDUCE_SKILLS_BY_GROUP' => 'SNAPSHOT_REDUCE_SKILLS_BY_GROUP',
        'snapshot_reduce_skills_by_group' => 'SNAPSHOT_REDUCE_SKILLS_BY_GROUP',
        'settings.snapshot_reduce_skills_by_group' => 'SNAPSHOT_REDUCE_SKILLS_BY_GROUP',
        'LoggedInShowSnapshotButton' => 'LOGGED_IN_SHOW_SNAPSHOT_BUTTON',
        'Settings.LoggedInShowSnapshotButton' => 'LOGGED_IN_SHOW_SNAPSHOT_BUTTON',
        'loggedInShowSnapshotButton' => 'LOGGED_IN_SHOW_SNAPSHOT_BUTTON',
        'settings.loggedInShowSnapshotButton' => 'LOGGED_IN_SHOW_SNAPSHOT_BUTTON',
        'SettingsTableMap::COL_LOGGED_IN_SHOW_SNAPSHOT_BUTTON' => 'LOGGED_IN_SHOW_SNAPSHOT_BUTTON',
        'COL_LOGGED_IN_SHOW_SNAPSHOT_BUTTON' => 'LOGGED_IN_SHOW_SNAPSHOT_BUTTON',
        'logged_in_show_snapshot_button' => 'LOGGED_IN_SHOW_SNAPSHOT_BUTTON',
        'settings.logged_in_show_snapshot_button' => 'LOGGED_IN_SHOW_SNAPSHOT_BUTTON',
        'TimeFormatLong' => 'TIME_FORMAT_LONG',
        'Settings.TimeFormatLong' => 'TIME_FORMAT_LONG',
        'timeFormatLong' => 'TIME_FORMAT_LONG',
        'settings.timeFormatLong' => 'TIME_FORMAT_LONG',
        'SettingsTableMap::COL_TIME_FORMAT_LONG' => 'TIME_FORMAT_LONG',
        'COL_TIME_FORMAT_LONG' => 'TIME_FORMAT_LONG',
        'time_format_long' => 'TIME_FORMAT_LONG',
        'settings.time_format_long' => 'TIME_FORMAT_LONG',
        'TimeFormatNormal' => 'TIME_FORMAT_NORMAL',
        'Settings.TimeFormatNormal' => 'TIME_FORMAT_NORMAL',
        'timeFormatNormal' => 'TIME_FORMAT_NORMAL',
        'settings.timeFormatNormal' => 'TIME_FORMAT_NORMAL',
        'SettingsTableMap::COL_TIME_FORMAT_NORMAL' => 'TIME_FORMAT_NORMAL',
        'COL_TIME_FORMAT_NORMAL' => 'TIME_FORMAT_NORMAL',
        'time_format_normal' => 'TIME_FORMAT_NORMAL',
        'settings.time_format_normal' => 'TIME_FORMAT_NORMAL',
        'TimeFormatShort' => 'TIME_FORMAT_SHORT',
        'Settings.TimeFormatShort' => 'TIME_FORMAT_SHORT',
        'timeFormatShort' => 'TIME_FORMAT_SHORT',
        'settings.timeFormatShort' => 'TIME_FORMAT_SHORT',
        'SettingsTableMap::COL_TIME_FORMAT_SHORT' => 'TIME_FORMAT_SHORT',
        'COL_TIME_FORMAT_SHORT' => 'TIME_FORMAT_SHORT',
        'time_format_short' => 'TIME_FORMAT_SHORT',
        'settings.time_format_short' => 'TIME_FORMAT_SHORT',
        'TimeOnlyFormat' => 'TIME_ONLY_FORMAT',
        'Settings.TimeOnlyFormat' => 'TIME_ONLY_FORMAT',
        'timeOnlyFormat' => 'TIME_ONLY_FORMAT',
        'settings.timeOnlyFormat' => 'TIME_ONLY_FORMAT',
        'SettingsTableMap::COL_TIME_ONLY_FORMAT' => 'TIME_ONLY_FORMAT',
        'COL_TIME_ONLY_FORMAT' => 'TIME_ONLY_FORMAT',
        'time_only_format' => 'TIME_ONLY_FORMAT',
        'settings.time_only_format' => 'TIME_ONLY_FORMAT',
        'DateOnlyFormat' => 'DATE_ONLY_FORMAT',
        'Settings.DateOnlyFormat' => 'DATE_ONLY_FORMAT',
        'dateOnlyFormat' => 'DATE_ONLY_FORMAT',
        'settings.dateOnlyFormat' => 'DATE_ONLY_FORMAT',
        'SettingsTableMap::COL_DATE_ONLY_FORMAT' => 'DATE_ONLY_FORMAT',
        'COL_DATE_ONLY_FORMAT' => 'DATE_ONLY_FORMAT',
        'date_only_format' => 'DATE_ONLY_FORMAT',
        'settings.date_only_format' => 'DATE_ONLY_FORMAT',
        'DayOnlyFormat' => 'DAY_ONLY_FORMAT',
        'Settings.DayOnlyFormat' => 'DAY_ONLY_FORMAT',
        'dayOnlyFormat' => 'DAY_ONLY_FORMAT',
        'settings.dayOnlyFormat' => 'DAY_ONLY_FORMAT',
        'SettingsTableMap::COL_DAY_ONLY_FORMAT' => 'DAY_ONLY_FORMAT',
        'COL_DAY_ONLY_FORMAT' => 'DAY_ONLY_FORMAT',
        'day_only_format' => 'DAY_ONLY_FORMAT',
        'settings.day_only_format' => 'DAY_ONLY_FORMAT',
        'UsersStartWithMyEvents' => 'USERS_START_WITH_MYEVENTS',
        'Settings.UsersStartWithMyEvents' => 'USERS_START_WITH_MYEVENTS',
        'usersStartWithMyEvents' => 'USERS_START_WITH_MYEVENTS',
        'settings.usersStartWithMyEvents' => 'USERS_START_WITH_MYEVENTS',
        'SettingsTableMap::COL_USERS_START_WITH_MYEVENTS' => 'USERS_START_WITH_MYEVENTS',
        'COL_USERS_START_WITH_MYEVENTS' => 'USERS_START_WITH_MYEVENTS',
        'users_start_with_myevents' => 'USERS_START_WITH_MYEVENTS',
        'settings.users_start_with_myevents' => 'USERS_START_WITH_MYEVENTS',
        'TimeZone' => 'TIME_ZONE',
        'Settings.TimeZone' => 'TIME_ZONE',
        'timeZone' => 'TIME_ZONE',
        'settings.timeZone' => 'TIME_ZONE',
        'SettingsTableMap::COL_TIME_ZONE' => 'TIME_ZONE',
        'COL_TIME_ZONE' => 'TIME_ZONE',
        'time_zone' => 'TIME_ZONE',
        'settings.time_zone' => 'TIME_ZONE',
        'GoogleGroupCalendar' => 'GOOGLE_GROUP_CALENDAR',
        'Settings.GoogleGroupCalendar' => 'GOOGLE_GROUP_CALENDAR',
        'googleGroupCalendar' => 'GOOGLE_GROUP_CALENDAR',
        'settings.googleGroupCalendar' => 'GOOGLE_GROUP_CALENDAR',
        'SettingsTableMap::COL_GOOGLE_GROUP_CALENDAR' => 'GOOGLE_GROUP_CALENDAR',
        'COL_GOOGLE_GROUP_CALENDAR' => 'GOOGLE_GROUP_CALENDAR',
        'google_group_calendar' => 'GOOGLE_GROUP_CALENDAR',
        'settings.google_group_calendar' => 'GOOGLE_GROUP_CALENDAR',
        'OverviewEmail' => 'OVERVIEWEMAIL',
        'Settings.OverviewEmail' => 'OVERVIEWEMAIL',
        'overviewEmail' => 'OVERVIEWEMAIL',
        'settings.overviewEmail' => 'OVERVIEWEMAIL',
        'SettingsTableMap::COL_OVERVIEWEMAIL' => 'OVERVIEWEMAIL',
        'COL_OVERVIEWEMAIL' => 'OVERVIEWEMAIL',
        'overviewemail' => 'OVERVIEWEMAIL',
        'settings.overviewemail' => 'OVERVIEWEMAIL',
        'GroupSortingName' => 'GROUP_SORTING_NAME',
        'Settings.GroupSortingName' => 'GROUP_SORTING_NAME',
        'groupSortingName' => 'GROUP_SORTING_NAME',
        'settings.groupSortingName' => 'GROUP_SORTING_NAME',
        'SettingsTableMap::COL_GROUP_SORTING_NAME' => 'GROUP_SORTING_NAME',
        'COL_GROUP_SORTING_NAME' => 'GROUP_SORTING_NAME',
        'group_sorting_name' => 'GROUP_SORTING_NAME',
        'settings.group_sorting_name' => 'GROUP_SORTING_NAME',
        'DebugMode' => 'DEBUG_MODE',
        'Settings.DebugMode' => 'DEBUG_MODE',
        'debugMode' => 'DEBUG_MODE',
        'settings.debugMode' => 'DEBUG_MODE',
        'SettingsTableMap::COL_DEBUG_MODE' => 'DEBUG_MODE',
        'COL_DEBUG_MODE' => 'DEBUG_MODE',
        'debug_mode' => 'DEBUG_MODE',
        'settings.debug_mode' => 'DEBUG_MODE',
        'DaysToAlert' => 'DAYS_TO_ALERT',
        'Settings.DaysToAlert' => 'DAYS_TO_ALERT',
        'daysToAlert' => 'DAYS_TO_ALERT',
        'settings.daysToAlert' => 'DAYS_TO_ALERT',
        'SettingsTableMap::COL_DAYS_TO_ALERT' => 'DAYS_TO_ALERT',
        'COL_DAYS_TO_ALERT' => 'DAYS_TO_ALERT',
        'days_to_alert' => 'DAYS_TO_ALERT',
        'settings.days_to_alert' => 'DAYS_TO_ALERT',
        'Token' => 'TOKEN',
        'Settings.Token' => 'TOKEN',
        'token' => 'TOKEN',
        'settings.token' => 'TOKEN',
        'SettingsTableMap::COL_TOKEN' => 'TOKEN',
        'COL_TOKEN' => 'TOKEN',
        'Skin' => 'SKIN',
        'Settings.Skin' => 'SKIN',
        'skin' => 'SKIN',
        'settings.skin' => 'SKIN',
        'SettingsTableMap::COL_SKIN' => 'SKIN',
        'COL_SKIN' => 'SKIN',
    ];

    /**
     * Initialize the table attributes and columns
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function initialize(): void
    {
        // attributes
        $this->setName('settings');
        $this->setPhpName('Settings');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\TechWilk\\Rota\\Settings');
        $this->setPackage('TechWilk.Rota');
        $this->setUseIdGenerator(false);
        // columns
        $this->addColumn('siteurl', 'SiteUrl', 'LONGVARCHAR', true, null, null);
        $this->addColumn('owner', 'Owner', 'LONGVARCHAR', true, null, null);
        $this->addColumn('notificationemail', 'NotificationEmail', 'LONGVARCHAR', false, null, null);
        $this->addColumn('adminemailaddress', 'AdminEmailAddress', 'LONGVARCHAR', false, null, null);
        $this->addColumn('norehearsalemail', 'NoRehearsalEmail', 'LONGVARCHAR', false, null, null);
        $this->addColumn('yesrehearsal', 'YesRehearsal', 'LONGVARCHAR', false, null, null);
        $this->addColumn('newusermessage', 'NewUserMessage', 'LONGVARCHAR', false, null, null);
        $this->addColumn('version', 'Version', 'VARCHAR', false, 20, null);
        $this->addColumn('lang_locale', 'LangLocale', 'VARCHAR', false, 20, null);
        $this->addColumn('event_sorting_latest', 'EventSortingLatest', 'INTEGER', false, 1, null);
        $this->addColumn('snapshot_show_two_month', 'SnapshotShowTwoMonth', 'INTEGER', false, 1, null);
        $this->addColumn('snapshot_reduce_skills_by_group', 'SnapshotReduceSkillsByGroup', 'INTEGER', false, 1, null);
        $this->addColumn('logged_in_show_snapshot_button', 'LoggedInShowSnapshotButton', 'INTEGER', false, 1, null);
        $this->addColumn('time_format_long', 'TimeFormatLong', 'VARCHAR', false, 50, null);
        $this->addColumn('time_format_normal', 'TimeFormatNormal', 'VARCHAR', false, 50, null);
        $this->addColumn('time_format_short', 'TimeFormatShort', 'VARCHAR', false, 50, null);
        $this->addColumn('time_only_format', 'TimeOnlyFormat', 'VARCHAR', false, 20, null);
        $this->addColumn('date_only_format', 'DateOnlyFormat', 'VARCHAR', false, 20, null);
        $this->addColumn('day_only_format', 'DayOnlyFormat', 'VARCHAR', false, 20, null);
        $this->addColumn('users_start_with_myevents', 'UsersStartWithMyEvents', 'INTEGER', false, 1, null);
        $this->addColumn('time_zone', 'TimeZone', 'VARCHAR', false, 50, null);
        $this->addColumn('google_group_calendar', 'GoogleGroupCalendar', 'VARCHAR', false, 100, null);
        $this->addColumn('overviewemail', 'OverviewEmail', 'LONGVARCHAR', false, null, null);
        $this->addColumn('group_sorting_name', 'GroupSortingName', 'INTEGER', false, 1, null);
        $this->addColumn('debug_mode', 'DebugMode', 'INTEGER', false, 1, 0);
        $this->addColumn('days_to_alert', 'DaysToAlert', 'INTEGER', false, 2, 5);
        $this->addColumn('token', 'Token', 'VARCHAR', false, 100, '');
        $this->addColumn('skin', 'Skin', 'VARCHAR', false, 20, '');
    }

    /**
     * Build the RelationMap objects for this table relationships
     *
     * @return void
     */
    public function buildRelations(): void
    {
    }

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param array $row Resultset row.
     * @param int $offset The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return string|null The primary key hash of the row
     */
    public static function getPrimaryKeyHashFromRow(array $row, int $offset = 0, string $indexType = TableMap::TYPE_NUM): ?string
    {
        return null;
    }

    /**
     * Retrieves the primary key from the DB resultset row
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, an array of the primary key columns will be returned.
     *
     * @param array $row Resultset row.
     * @param int $offset The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return mixed The primary key of the row
     */
    public static function getPrimaryKeyFromRow(array $row, int $offset = 0, string $indexType = TableMap::TYPE_NUM)
    {
        return '';
    }

    /**
     * The class that the tableMap will make instances of.
     *
     * If $withPrefix is true, the returned path
     * uses a dot-path notation which is translated into a path
     * relative to a location on the PHP include_path.
     * (e.g. path.to.MyClass -> 'path/to/MyClass.php')
     *
     * @param bool $withPrefix Whether to return the path with the class name
     * @return string path.to.ClassName
     */
    public static function getOMClass(bool $withPrefix = true): string
    {
        return $withPrefix ? SettingsTableMap::CLASS_DEFAULT : SettingsTableMap::OM_CLASS;
    }

    /**
     * Populates an object of the default type or an object that inherit from the default.
     *
     * @param array $row Row returned by DataFetcher->fetch().
     * @param int $offset The 0-based offset for reading from the resultset row.
     * @param string $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                 One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     * @return array (Settings object, last column rank)
     */
    public static function populateObject(array $row, int $offset = 0, string $indexType = TableMap::TYPE_NUM): array
    {
        $key = SettingsTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = SettingsTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + SettingsTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = SettingsTableMap::OM_CLASS;
            /** @var Settings $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            SettingsTableMap::addInstanceToPool($obj, $key);
        }

        return [$obj, $col];
    }

    /**
     * The returned array will contain objects of the default type or
     * objects that inherit from the default.
     *
     * @param DataFetcherInterface $dataFetcher
     * @return array<object>
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function populateObjects(DataFetcherInterface $dataFetcher): array
    {
        $results = [];

        // set the class once to avoid overhead in the loop
        $cls = static::getOMClass(false);
        // populate the object(s)
        while ($row = $dataFetcher->fetch()) {
            $key = SettingsTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = SettingsTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Settings $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                SettingsTableMap::addInstanceToPool($obj, $key);
            } // if key exists
        }

        return $results;
    }
    /**
     * Add all the columns needed to create a new object.
     *
     * Note: any columns that were marked with lazyLoad="true" in the
     * XML schema will not be added to the select list and only loaded
     * on demand.
     *
     * @param Criteria $criteria Object containing the columns to add.
     * @param string|null $alias Optional table alias
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     * @return void
     */
    public static function addSelectColumns(Criteria $criteria, ?string $alias = null): void
    {
        if (null === $alias) {
            $criteria->addSelectColumn(SettingsTableMap::COL_SITEURL);
            $criteria->addSelectColumn(SettingsTableMap::COL_OWNER);
            $criteria->addSelectColumn(SettingsTableMap::COL_NOTIFICATIONEMAIL);
            $criteria->addSelectColumn(SettingsTableMap::COL_ADMINEMAILADDRESS);
            $criteria->addSelectColumn(SettingsTableMap::COL_NOREHEARSALEMAIL);
            $criteria->addSelectColumn(SettingsTableMap::COL_YESREHEARSAL);
            $criteria->addSelectColumn(SettingsTableMap::COL_NEWUSERMESSAGE);
            $criteria->addSelectColumn(SettingsTableMap::COL_VERSION);
            $criteria->addSelectColumn(SettingsTableMap::COL_LANG_LOCALE);
            $criteria->addSelectColumn(SettingsTableMap::COL_EVENT_SORTING_LATEST);
            $criteria->addSelectColumn(SettingsTableMap::COL_SNAPSHOT_SHOW_TWO_MONTH);
            $criteria->addSelectColumn(SettingsTableMap::COL_SNAPSHOT_REDUCE_SKILLS_BY_GROUP);
            $criteria->addSelectColumn(SettingsTableMap::COL_LOGGED_IN_SHOW_SNAPSHOT_BUTTON);
            $criteria->addSelectColumn(SettingsTableMap::COL_TIME_FORMAT_LONG);
            $criteria->addSelectColumn(SettingsTableMap::COL_TIME_FORMAT_NORMAL);
            $criteria->addSelectColumn(SettingsTableMap::COL_TIME_FORMAT_SHORT);
            $criteria->addSelectColumn(SettingsTableMap::COL_TIME_ONLY_FORMAT);
            $criteria->addSelectColumn(SettingsTableMap::COL_DATE_ONLY_FORMAT);
            $criteria->addSelectColumn(SettingsTableMap::COL_DAY_ONLY_FORMAT);
            $criteria->addSelectColumn(SettingsTableMap::COL_USERS_START_WITH_MYEVENTS);
            $criteria->addSelectColumn(SettingsTableMap::COL_TIME_ZONE);
            $criteria->addSelectColumn(SettingsTableMap::COL_GOOGLE_GROUP_CALENDAR);
            $criteria->addSelectColumn(SettingsTableMap::COL_OVERVIEWEMAIL);
            $criteria->addSelectColumn(SettingsTableMap::COL_GROUP_SORTING_NAME);
            $criteria->addSelectColumn(SettingsTableMap::COL_DEBUG_MODE);
            $criteria->addSelectColumn(SettingsTableMap::COL_DAYS_TO_ALERT);
            $criteria->addSelectColumn(SettingsTableMap::COL_TOKEN);
            $criteria->addSelectColumn(SettingsTableMap::COL_SKIN);
        } else {
            $criteria->addSelectColumn($alias . '.siteurl');
            $criteria->addSelectColumn($alias . '.owner');
            $criteria->addSelectColumn($alias . '.notificationemail');
            $criteria->addSelectColumn($alias . '.adminemailaddress');
            $criteria->addSelectColumn($alias . '.norehearsalemail');
            $criteria->addSelectColumn($alias . '.yesrehearsal');
            $criteria->addSelectColumn($alias . '.newusermessage');
            $criteria->addSelectColumn($alias . '.version');
            $criteria->addSelectColumn($alias . '.lang_locale');
            $criteria->addSelectColumn($alias . '.event_sorting_latest');
            $criteria->addSelectColumn($alias . '.snapshot_show_two_month');
            $criteria->addSelectColumn($alias . '.snapshot_reduce_skills_by_group');
            $criteria->addSelectColumn($alias . '.logged_in_show_snapshot_button');
            $criteria->addSelectColumn($alias . '.time_format_long');
            $criteria->addSelectColumn($alias . '.time_format_normal');
            $criteria->addSelectColumn($alias . '.time_format_short');
            $criteria->addSelectColumn($alias . '.time_only_format');
            $criteria->addSelectColumn($alias . '.date_only_format');
            $criteria->addSelectColumn($alias . '.day_only_format');
            $criteria->addSelectColumn($alias . '.users_start_with_myevents');
            $criteria->addSelectColumn($alias . '.time_zone');
            $criteria->addSelectColumn($alias . '.google_group_calendar');
            $criteria->addSelectColumn($alias . '.overviewemail');
            $criteria->addSelectColumn($alias . '.group_sorting_name');
            $criteria->addSelectColumn($alias . '.debug_mode');
            $criteria->addSelectColumn($alias . '.days_to_alert');
            $criteria->addSelectColumn($alias . '.token');
            $criteria->addSelectColumn($alias . '.skin');
        }
    }

    /**
     * Remove all the columns needed to create a new object.
     *
     * Note: any columns that were marked with lazyLoad="true" in the
     * XML schema will not be removed as they are only loaded on demand.
     *
     * @param Criteria $criteria Object containing the columns to remove.
     * @param string|null $alias Optional table alias
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     * @return void
     */
    public static function removeSelectColumns(Criteria $criteria, ?string $alias = null): void
    {
        if (null === $alias) {
            $criteria->removeSelectColumn(SettingsTableMap::COL_SITEURL);
            $criteria->removeSelectColumn(SettingsTableMap::COL_OWNER);
            $criteria->removeSelectColumn(SettingsTableMap::COL_NOTIFICATIONEMAIL);
            $criteria->removeSelectColumn(SettingsTableMap::COL_ADMINEMAILADDRESS);
            $criteria->removeSelectColumn(SettingsTableMap::COL_NOREHEARSALEMAIL);
            $criteria->removeSelectColumn(SettingsTableMap::COL_YESREHEARSAL);
            $criteria->removeSelectColumn(SettingsTableMap::COL_NEWUSERMESSAGE);
            $criteria->removeSelectColumn(SettingsTableMap::COL_VERSION);
            $criteria->removeSelectColumn(SettingsTableMap::COL_LANG_LOCALE);
            $criteria->removeSelectColumn(SettingsTableMap::COL_EVENT_SORTING_LATEST);
            $criteria->removeSelectColumn(SettingsTableMap::COL_SNAPSHOT_SHOW_TWO_MONTH);
            $criteria->removeSelectColumn(SettingsTableMap::COL_SNAPSHOT_REDUCE_SKILLS_BY_GROUP);
            $criteria->removeSelectColumn(SettingsTableMap::COL_LOGGED_IN_SHOW_SNAPSHOT_BUTTON);
            $criteria->removeSelectColumn(SettingsTableMap::COL_TIME_FORMAT_LONG);
            $criteria->removeSelectColumn(SettingsTableMap::COL_TIME_FORMAT_NORMAL);
            $criteria->removeSelectColumn(SettingsTableMap::COL_TIME_FORMAT_SHORT);
            $criteria->removeSelectColumn(SettingsTableMap::COL_TIME_ONLY_FORMAT);
            $criteria->removeSelectColumn(SettingsTableMap::COL_DATE_ONLY_FORMAT);
            $criteria->removeSelectColumn(SettingsTableMap::COL_DAY_ONLY_FORMAT);
            $criteria->removeSelectColumn(SettingsTableMap::COL_USERS_START_WITH_MYEVENTS);
            $criteria->removeSelectColumn(SettingsTableMap::COL_TIME_ZONE);
            $criteria->removeSelectColumn(SettingsTableMap::COL_GOOGLE_GROUP_CALENDAR);
            $criteria->removeSelectColumn(SettingsTableMap::COL_OVERVIEWEMAIL);
            $criteria->removeSelectColumn(SettingsTableMap::COL_GROUP_SORTING_NAME);
            $criteria->removeSelectColumn(SettingsTableMap::COL_DEBUG_MODE);
            $criteria->removeSelectColumn(SettingsTableMap::COL_DAYS_TO_ALERT);
            $criteria->removeSelectColumn(SettingsTableMap::COL_TOKEN);
            $criteria->removeSelectColumn(SettingsTableMap::COL_SKIN);
        } else {
            $criteria->removeSelectColumn($alias . '.siteurl');
            $criteria->removeSelectColumn($alias . '.owner');
            $criteria->removeSelectColumn($alias . '.notificationemail');
            $criteria->removeSelectColumn($alias . '.adminemailaddress');
            $criteria->removeSelectColumn($alias . '.norehearsalemail');
            $criteria->removeSelectColumn($alias . '.yesrehearsal');
            $criteria->removeSelectColumn($alias . '.newusermessage');
            $criteria->removeSelectColumn($alias . '.version');
            $criteria->removeSelectColumn($alias . '.lang_locale');
            $criteria->removeSelectColumn($alias . '.event_sorting_latest');
            $criteria->removeSelectColumn($alias . '.snapshot_show_two_month');
            $criteria->removeSelectColumn($alias . '.snapshot_reduce_skills_by_group');
            $criteria->removeSelectColumn($alias . '.logged_in_show_snapshot_button');
            $criteria->removeSelectColumn($alias . '.time_format_long');
            $criteria->removeSelectColumn($alias . '.time_format_normal');
            $criteria->removeSelectColumn($alias . '.time_format_short');
            $criteria->removeSelectColumn($alias . '.time_only_format');
            $criteria->removeSelectColumn($alias . '.date_only_format');
            $criteria->removeSelectColumn($alias . '.day_only_format');
            $criteria->removeSelectColumn($alias . '.users_start_with_myevents');
            $criteria->removeSelectColumn($alias . '.time_zone');
            $criteria->removeSelectColumn($alias . '.google_group_calendar');
            $criteria->removeSelectColumn($alias . '.overviewemail');
            $criteria->removeSelectColumn($alias . '.group_sorting_name');
            $criteria->removeSelectColumn($alias . '.debug_mode');
            $criteria->removeSelectColumn($alias . '.days_to_alert');
            $criteria->removeSelectColumn($alias . '.token');
            $criteria->removeSelectColumn($alias . '.skin');
        }
    }

    /**
     * Returns the TableMap related to this object.
     * This method is not needed for general use but a specific application could have a need.
     * @return TableMap
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function getTableMap(): TableMap
    {
        return Propel::getServiceContainer()->getDatabaseMap(SettingsTableMap::DATABASE_NAME)->getTable(SettingsTableMap::TABLE_NAME);
    }

    /**
     * Performs a DELETE on the database, given a Settings or Criteria object OR a primary key value.
     *
     * @param mixed $values Criteria or Settings object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
     public static function doDelete($values, ?ConnectionInterface $con = null): int
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(SettingsTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \TechWilk\Rota\Settings) { // it's a model object
            // create criteria based on pk value
            $criteria = $values->buildCriteria();
        } else { // it's a primary key, or an array of pks
            throw new LogicException('The Settings object has no primary key');
        }

        $query = SettingsQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            SettingsTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                SettingsTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the settings table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(?ConnectionInterface $con = null): int
    {
        return SettingsQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Settings or Criteria object.
     *
     * @param mixed $criteria Criteria or Settings object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed The new primary key.
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ?ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(SettingsTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Settings object
        }


        // Set the correct dbName
        $query = SettingsQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

}
