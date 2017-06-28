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
 * This class defines the structure of the 'cr_settings' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class SettingsTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'TechWilk.Rota.Map.SettingsTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'cr_settings';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\TechWilk\\Rota\\Settings';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'TechWilk.Rota.Settings';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 28;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 28;

    /**
     * the column name for the siteurl field
     */
    const COL_SITEURL = 'cr_settings.siteurl';

    /**
     * the column name for the owner field
     */
    const COL_OWNER = 'cr_settings.owner';

    /**
     * the column name for the notificationemail field
     */
    const COL_NOTIFICATIONEMAIL = 'cr_settings.notificationemail';

    /**
     * the column name for the adminemailaddress field
     */
    const COL_ADMINEMAILADDRESS = 'cr_settings.adminemailaddress';

    /**
     * the column name for the norehearsalemail field
     */
    const COL_NOREHEARSALEMAIL = 'cr_settings.norehearsalemail';

    /**
     * the column name for the yesrehearsal field
     */
    const COL_YESREHEARSAL = 'cr_settings.yesrehearsal';

    /**
     * the column name for the newusermessage field
     */
    const COL_NEWUSERMESSAGE = 'cr_settings.newusermessage';

    /**
     * the column name for the version field
     */
    const COL_VERSION = 'cr_settings.version';

    /**
     * the column name for the lang_locale field
     */
    const COL_LANG_LOCALE = 'cr_settings.lang_locale';

    /**
     * the column name for the event_sorting_latest field
     */
    const COL_EVENT_SORTING_LATEST = 'cr_settings.event_sorting_latest';

    /**
     * the column name for the snapshot_show_two_month field
     */
    const COL_SNAPSHOT_SHOW_TWO_MONTH = 'cr_settings.snapshot_show_two_month';

    /**
     * the column name for the snapshot_reduce_skills_by_group field
     */
    const COL_SNAPSHOT_REDUCE_SKILLS_BY_GROUP = 'cr_settings.snapshot_reduce_skills_by_group';

    /**
     * the column name for the logged_in_show_snapshot_button field
     */
    const COL_LOGGED_IN_SHOW_SNAPSHOT_BUTTON = 'cr_settings.logged_in_show_snapshot_button';

    /**
     * the column name for the time_format_long field
     */
    const COL_TIME_FORMAT_LONG = 'cr_settings.time_format_long';

    /**
     * the column name for the time_format_normal field
     */
    const COL_TIME_FORMAT_NORMAL = 'cr_settings.time_format_normal';

    /**
     * the column name for the time_format_short field
     */
    const COL_TIME_FORMAT_SHORT = 'cr_settings.time_format_short';

    /**
     * the column name for the time_only_format field
     */
    const COL_TIME_ONLY_FORMAT = 'cr_settings.time_only_format';

    /**
     * the column name for the date_only_format field
     */
    const COL_DATE_ONLY_FORMAT = 'cr_settings.date_only_format';

    /**
     * the column name for the day_only_format field
     */
    const COL_DAY_ONLY_FORMAT = 'cr_settings.day_only_format';

    /**
     * the column name for the users_start_with_myevents field
     */
    const COL_USERS_START_WITH_MYEVENTS = 'cr_settings.users_start_with_myevents';

    /**
     * the column name for the time_zone field
     */
    const COL_TIME_ZONE = 'cr_settings.time_zone';

    /**
     * the column name for the google_group_calendar field
     */
    const COL_GOOGLE_GROUP_CALENDAR = 'cr_settings.google_group_calendar';

    /**
     * the column name for the overviewemail field
     */
    const COL_OVERVIEWEMAIL = 'cr_settings.overviewemail';

    /**
     * the column name for the group_sorting_name field
     */
    const COL_GROUP_SORTING_NAME = 'cr_settings.group_sorting_name';

    /**
     * the column name for the debug_mode field
     */
    const COL_DEBUG_MODE = 'cr_settings.debug_mode';

    /**
     * the column name for the days_to_alert field
     */
    const COL_DAYS_TO_ALERT = 'cr_settings.days_to_alert';

    /**
     * the column name for the token field
     */
    const COL_TOKEN = 'cr_settings.token';

    /**
     * the column name for the skin field
     */
    const COL_SKIN = 'cr_settings.skin';

    /**
     * The default string format for model objects of the related table
     */
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array(
        self::TYPE_PHPNAME       => array('SiteUrl', 'Owner', 'NotificationEmail', 'AdminEmailAddress', 'NoRehearsalEmail', 'YesRehearsal', 'NewUserMessage', 'Version', 'LangLocale', 'EventSortingLatest', 'SnapshotShowTwoMonth', 'SnapshotReduceSkillsByGroup', 'LoggedInShowSnapshotButton', 'TimeFormatLong', 'TimeFormatNormal', 'TimeFormatShort', 'TimeOnlyFormat', 'DateOnlyFormat', 'DayOnlyFormat', 'UsersStartWithMyEvents', 'TimeZone', 'GoogleGroupCalendar', 'OverviewEmail', 'GroupSortingName', 'DebugMode', 'DaysToAlert', 'Token', 'Skin', ),
        self::TYPE_CAMELNAME     => array('siteUrl', 'owner', 'notificationEmail', 'adminEmailAddress', 'noRehearsalEmail', 'yesRehearsal', 'newUserMessage', 'version', 'langLocale', 'eventSortingLatest', 'snapshotShowTwoMonth', 'snapshotReduceSkillsByGroup', 'loggedInShowSnapshotButton', 'timeFormatLong', 'timeFormatNormal', 'timeFormatShort', 'timeOnlyFormat', 'dateOnlyFormat', 'dayOnlyFormat', 'usersStartWithMyEvents', 'timeZone', 'googleGroupCalendar', 'overviewEmail', 'groupSortingName', 'debugMode', 'daysToAlert', 'token', 'skin', ),
        self::TYPE_COLNAME       => array(SettingsTableMap::COL_SITEURL, SettingsTableMap::COL_OWNER, SettingsTableMap::COL_NOTIFICATIONEMAIL, SettingsTableMap::COL_ADMINEMAILADDRESS, SettingsTableMap::COL_NOREHEARSALEMAIL, SettingsTableMap::COL_YESREHEARSAL, SettingsTableMap::COL_NEWUSERMESSAGE, SettingsTableMap::COL_VERSION, SettingsTableMap::COL_LANG_LOCALE, SettingsTableMap::COL_EVENT_SORTING_LATEST, SettingsTableMap::COL_SNAPSHOT_SHOW_TWO_MONTH, SettingsTableMap::COL_SNAPSHOT_REDUCE_SKILLS_BY_GROUP, SettingsTableMap::COL_LOGGED_IN_SHOW_SNAPSHOT_BUTTON, SettingsTableMap::COL_TIME_FORMAT_LONG, SettingsTableMap::COL_TIME_FORMAT_NORMAL, SettingsTableMap::COL_TIME_FORMAT_SHORT, SettingsTableMap::COL_TIME_ONLY_FORMAT, SettingsTableMap::COL_DATE_ONLY_FORMAT, SettingsTableMap::COL_DAY_ONLY_FORMAT, SettingsTableMap::COL_USERS_START_WITH_MYEVENTS, SettingsTableMap::COL_TIME_ZONE, SettingsTableMap::COL_GOOGLE_GROUP_CALENDAR, SettingsTableMap::COL_OVERVIEWEMAIL, SettingsTableMap::COL_GROUP_SORTING_NAME, SettingsTableMap::COL_DEBUG_MODE, SettingsTableMap::COL_DAYS_TO_ALERT, SettingsTableMap::COL_TOKEN, SettingsTableMap::COL_SKIN, ),
        self::TYPE_FIELDNAME     => array('siteurl', 'owner', 'notificationemail', 'adminemailaddress', 'norehearsalemail', 'yesrehearsal', 'newusermessage', 'version', 'lang_locale', 'event_sorting_latest', 'snapshot_show_two_month', 'snapshot_reduce_skills_by_group', 'logged_in_show_snapshot_button', 'time_format_long', 'time_format_normal', 'time_format_short', 'time_only_format', 'date_only_format', 'day_only_format', 'users_start_with_myevents', 'time_zone', 'google_group_calendar', 'overviewemail', 'group_sorting_name', 'debug_mode', 'days_to_alert', 'token', 'skin', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array(
        self::TYPE_PHPNAME       => array('SiteUrl' => 0, 'Owner' => 1, 'NotificationEmail' => 2, 'AdminEmailAddress' => 3, 'NoRehearsalEmail' => 4, 'YesRehearsal' => 5, 'NewUserMessage' => 6, 'Version' => 7, 'LangLocale' => 8, 'EventSortingLatest' => 9, 'SnapshotShowTwoMonth' => 10, 'SnapshotReduceSkillsByGroup' => 11, 'LoggedInShowSnapshotButton' => 12, 'TimeFormatLong' => 13, 'TimeFormatNormal' => 14, 'TimeFormatShort' => 15, 'TimeOnlyFormat' => 16, 'DateOnlyFormat' => 17, 'DayOnlyFormat' => 18, 'UsersStartWithMyEvents' => 19, 'TimeZone' => 20, 'GoogleGroupCalendar' => 21, 'OverviewEmail' => 22, 'GroupSortingName' => 23, 'DebugMode' => 24, 'DaysToAlert' => 25, 'Token' => 26, 'Skin' => 27, ),
        self::TYPE_CAMELNAME     => array('siteUrl' => 0, 'owner' => 1, 'notificationEmail' => 2, 'adminEmailAddress' => 3, 'noRehearsalEmail' => 4, 'yesRehearsal' => 5, 'newUserMessage' => 6, 'version' => 7, 'langLocale' => 8, 'eventSortingLatest' => 9, 'snapshotShowTwoMonth' => 10, 'snapshotReduceSkillsByGroup' => 11, 'loggedInShowSnapshotButton' => 12, 'timeFormatLong' => 13, 'timeFormatNormal' => 14, 'timeFormatShort' => 15, 'timeOnlyFormat' => 16, 'dateOnlyFormat' => 17, 'dayOnlyFormat' => 18, 'usersStartWithMyEvents' => 19, 'timeZone' => 20, 'googleGroupCalendar' => 21, 'overviewEmail' => 22, 'groupSortingName' => 23, 'debugMode' => 24, 'daysToAlert' => 25, 'token' => 26, 'skin' => 27, ),
        self::TYPE_COLNAME       => array(SettingsTableMap::COL_SITEURL => 0, SettingsTableMap::COL_OWNER => 1, SettingsTableMap::COL_NOTIFICATIONEMAIL => 2, SettingsTableMap::COL_ADMINEMAILADDRESS => 3, SettingsTableMap::COL_NOREHEARSALEMAIL => 4, SettingsTableMap::COL_YESREHEARSAL => 5, SettingsTableMap::COL_NEWUSERMESSAGE => 6, SettingsTableMap::COL_VERSION => 7, SettingsTableMap::COL_LANG_LOCALE => 8, SettingsTableMap::COL_EVENT_SORTING_LATEST => 9, SettingsTableMap::COL_SNAPSHOT_SHOW_TWO_MONTH => 10, SettingsTableMap::COL_SNAPSHOT_REDUCE_SKILLS_BY_GROUP => 11, SettingsTableMap::COL_LOGGED_IN_SHOW_SNAPSHOT_BUTTON => 12, SettingsTableMap::COL_TIME_FORMAT_LONG => 13, SettingsTableMap::COL_TIME_FORMAT_NORMAL => 14, SettingsTableMap::COL_TIME_FORMAT_SHORT => 15, SettingsTableMap::COL_TIME_ONLY_FORMAT => 16, SettingsTableMap::COL_DATE_ONLY_FORMAT => 17, SettingsTableMap::COL_DAY_ONLY_FORMAT => 18, SettingsTableMap::COL_USERS_START_WITH_MYEVENTS => 19, SettingsTableMap::COL_TIME_ZONE => 20, SettingsTableMap::COL_GOOGLE_GROUP_CALENDAR => 21, SettingsTableMap::COL_OVERVIEWEMAIL => 22, SettingsTableMap::COL_GROUP_SORTING_NAME => 23, SettingsTableMap::COL_DEBUG_MODE => 24, SettingsTableMap::COL_DAYS_TO_ALERT => 25, SettingsTableMap::COL_TOKEN => 26, SettingsTableMap::COL_SKIN => 27, ),
        self::TYPE_FIELDNAME     => array('siteurl' => 0, 'owner' => 1, 'notificationemail' => 2, 'adminemailaddress' => 3, 'norehearsalemail' => 4, 'yesrehearsal' => 5, 'newusermessage' => 6, 'version' => 7, 'lang_locale' => 8, 'event_sorting_latest' => 9, 'snapshot_show_two_month' => 10, 'snapshot_reduce_skills_by_group' => 11, 'logged_in_show_snapshot_button' => 12, 'time_format_long' => 13, 'time_format_normal' => 14, 'time_format_short' => 15, 'time_only_format' => 16, 'date_only_format' => 17, 'day_only_format' => 18, 'users_start_with_myevents' => 19, 'time_zone' => 20, 'google_group_calendar' => 21, 'overviewemail' => 22, 'group_sorting_name' => 23, 'debug_mode' => 24, 'days_to_alert' => 25, 'token' => 26, 'skin' => 27, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, )
    );

    /**
     * Initialize the table attributes and columns
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws PropelException
     */
    public function initialize()
    {
        // attributes
        $this->setName('cr_settings');
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
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
    } // buildRelations()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return string The primary key hash of the row
     */
    public static function getPrimaryKeyHashFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        return null;
    }

    /**
     * Retrieves the primary key from the DB resultset row
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, an array of the primary key columns will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return mixed The primary key of the row
     */
    public static function getPrimaryKeyFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
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
     * @param boolean $withPrefix Whether or not to return the path with the class name
     * @return string path.to.ClassName
     */
    public static function getOMClass($withPrefix = true)
    {
        return $withPrefix ? SettingsTableMap::CLASS_DEFAULT : SettingsTableMap::OM_CLASS;
    }

    /**
     * Populates an object of the default type or an object that inherit from the default.
     *
     * @param array  $row       row returned by DataFetcher->fetch().
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                 One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     * @return array           (Settings object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
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

        return array($obj, $col);
    }

    /**
     * The returned array will contain objects of the default type or
     * objects that inherit from the default.
     *
     * @param DataFetcherInterface $dataFetcher
     * @return array
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function populateObjects(DataFetcherInterface $dataFetcher)
    {
        $results = array();

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
     * @param Criteria $criteria object containing the columns to add.
     * @param string   $alias    optional table alias
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function addSelectColumns(Criteria $criteria, $alias = null)
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
     * Returns the TableMap related to this object.
     * This method is not needed for general use but a specific application could have a need.
     * @return TableMap
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function getTableMap()
    {
        return Propel::getServiceContainer()->getDatabaseMap(SettingsTableMap::DATABASE_NAME)->getTable(SettingsTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(SettingsTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(SettingsTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new SettingsTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a Settings or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Settings object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param  ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
     public static function doDelete($values, ConnectionInterface $con = null)
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
     * Deletes all rows from the cr_settings table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return SettingsQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Settings or Criteria object.
     *
     * @param mixed               $criteria Criteria or Settings object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
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
} // SettingsTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
SettingsTableMap::buildTableMap();
