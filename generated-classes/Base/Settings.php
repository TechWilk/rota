<?php

namespace Base;

use \SettingsQuery as ChildSettingsQuery;
use \Exception;
use \PDO;
use Map\SettingsTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\BadMethodCallException;
use Propel\Runtime\Exception\LogicException;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Parser\AbstractParser;

/**
 * Base class that represents a row from the 'cr_settings' table.
 *
 *
 *
 * @package    propel.generator..Base
 */
abstract class Settings implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\Map\\SettingsTableMap';


    /**
     * attribute to determine if this object has previously been saved.
     * @var boolean
     */
    protected $new = true;

    /**
     * attribute to determine whether this object has been deleted.
     * @var boolean
     */
    protected $deleted = false;

    /**
     * The columns that have been modified in current object.
     * Tracking modified columns allows us to only update modified columns.
     * @var array
     */
    protected $modifiedColumns = array();

    /**
     * The (virtual) columns that are added at runtime
     * The formatters can add supplementary columns based on a resultset
     * @var array
     */
    protected $virtualColumns = array();

    /**
     * The value for the siteurl field.
     *
     * @var        string
     */
    protected $siteurl;

    /**
     * The value for the owner field.
     *
     * @var        string
     */
    protected $owner;

    /**
     * The value for the notificationemail field.
     *
     * @var        string
     */
    protected $notificationemail;

    /**
     * The value for the adminemailaddress field.
     *
     * @var        string
     */
    protected $adminemailaddress;

    /**
     * The value for the norehearsalemail field.
     *
     * @var        string
     */
    protected $norehearsalemail;

    /**
     * The value for the yesrehearsal field.
     *
     * @var        string
     */
    protected $yesrehearsal;

    /**
     * The value for the newusermessage field.
     *
     * @var        string
     */
    protected $newusermessage;

    /**
     * The value for the version field.
     *
     * @var        string
     */
    protected $version;

    /**
     * The value for the lang_locale field.
     *
     * @var        string
     */
    protected $lang_locale;

    /**
     * The value for the event_sorting_latest field.
     *
     * @var        int
     */
    protected $event_sorting_latest;

    /**
     * The value for the snapshot_show_two_month field.
     *
     * @var        int
     */
    protected $snapshot_show_two_month;

    /**
     * The value for the snapshot_reduce_skills_by_group field.
     *
     * @var        int
     */
    protected $snapshot_reduce_skills_by_group;

    /**
     * The value for the logged_in_show_snapshot_button field.
     *
     * @var        int
     */
    protected $logged_in_show_snapshot_button;

    /**
     * The value for the time_format_long field.
     *
     * @var        string
     */
    protected $time_format_long;

    /**
     * The value for the time_format_normal field.
     *
     * @var        string
     */
    protected $time_format_normal;

    /**
     * The value for the time_format_short field.
     *
     * @var        string
     */
    protected $time_format_short;

    /**
     * The value for the time_only_format field.
     *
     * @var        string
     */
    protected $time_only_format;

    /**
     * The value for the date_only_format field.
     *
     * @var        string
     */
    protected $date_only_format;

    /**
     * The value for the day_only_format field.
     *
     * @var        string
     */
    protected $day_only_format;

    /**
     * The value for the users_start_with_myevents field.
     *
     * @var        int
     */
    protected $users_start_with_myevents;

    /**
     * The value for the time_zone field.
     *
     * @var        string
     */
    protected $time_zone;

    /**
     * The value for the google_group_calendar field.
     *
     * @var        string
     */
    protected $google_group_calendar;

    /**
     * The value for the overviewemail field.
     *
     * @var        string
     */
    protected $overviewemail;

    /**
     * The value for the group_sorting_name field.
     *
     * @var        int
     */
    protected $group_sorting_name;

    /**
     * The value for the debug_mode field.
     *
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $debug_mode;

    /**
     * The value for the days_to_alert field.
     *
     * Note: this column has a database default value of: 5
     * @var        int
     */
    protected $days_to_alert;

    /**
     * The value for the token field.
     *
     * Note: this column has a database default value of: ''
     * @var        string
     */
    protected $token;

    /**
     * The value for the skin field.
     *
     * Note: this column has a database default value of: ''
     * @var        string
     */
    protected $skin;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see __construct()
     */
    public function applyDefaultValues()
    {
        $this->debug_mode = 0;
        $this->days_to_alert = 5;
        $this->token = '';
        $this->skin = '';
    }

    /**
     * Initializes internal state of Base\Settings object.
     * @see applyDefaults()
     */
    public function __construct()
    {
        $this->applyDefaultValues();
    }

    /**
     * Returns whether the object has been modified.
     *
     * @return boolean True if the object has been modified.
     */
    public function isModified()
    {
        return !!$this->modifiedColumns;
    }

    /**
     * Has specified column been modified?
     *
     * @param  string  $col column fully qualified name (TableMap::TYPE_COLNAME), e.g. Book::AUTHOR_ID
     * @return boolean True if $col has been modified.
     */
    public function isColumnModified($col)
    {
        return $this->modifiedColumns && isset($this->modifiedColumns[$col]);
    }

    /**
     * Get the columns that have been modified in this object.
     * @return array A unique list of the modified column names for this object.
     */
    public function getModifiedColumns()
    {
        return $this->modifiedColumns ? array_keys($this->modifiedColumns) : [];
    }

    /**
     * Returns whether the object has ever been saved.  This will
     * be false, if the object was retrieved from storage or was created
     * and then saved.
     *
     * @return boolean true, if the object has never been persisted.
     */
    public function isNew()
    {
        return $this->new;
    }

    /**
     * Setter for the isNew attribute.  This method will be called
     * by Propel-generated children and objects.
     *
     * @param boolean $b the state of the object.
     */
    public function setNew($b)
    {
        $this->new = (boolean) $b;
    }

    /**
     * Whether this object has been deleted.
     * @return boolean The deleted state of this object.
     */
    public function isDeleted()
    {
        return $this->deleted;
    }

    /**
     * Specify whether this object has been deleted.
     * @param  boolean $b The deleted state of this object.
     * @return void
     */
    public function setDeleted($b)
    {
        $this->deleted = (boolean) $b;
    }

    /**
     * Sets the modified state for the object to be false.
     * @param  string $col If supplied, only the specified column is reset.
     * @return void
     */
    public function resetModified($col = null)
    {
        if (null !== $col) {
            if (isset($this->modifiedColumns[$col])) {
                unset($this->modifiedColumns[$col]);
            }
        } else {
            $this->modifiedColumns = array();
        }
    }

    /**
     * Compares this with another <code>Settings</code> instance.  If
     * <code>obj</code> is an instance of <code>Settings</code>, delegates to
     * <code>equals(Settings)</code>.  Otherwise, returns <code>false</code>.
     *
     * @param  mixed   $obj The object to compare to.
     * @return boolean Whether equal to the object specified.
     */
    public function equals($obj)
    {
        if (!$obj instanceof static) {
            return false;
        }

        if ($this === $obj) {
            return true;
        }

        if (null === $this->getPrimaryKey() || null === $obj->getPrimaryKey()) {
            return false;
        }

        return $this->getPrimaryKey() === $obj->getPrimaryKey();
    }

    /**
     * Get the associative array of the virtual columns in this object
     *
     * @return array
     */
    public function getVirtualColumns()
    {
        return $this->virtualColumns;
    }

    /**
     * Checks the existence of a virtual column in this object
     *
     * @param  string  $name The virtual column name
     * @return boolean
     */
    public function hasVirtualColumn($name)
    {
        return array_key_exists($name, $this->virtualColumns);
    }

    /**
     * Get the value of a virtual column in this object
     *
     * @param  string $name The virtual column name
     * @return mixed
     *
     * @throws PropelException
     */
    public function getVirtualColumn($name)
    {
        if (!$this->hasVirtualColumn($name)) {
            throw new PropelException(sprintf('Cannot get value of inexistent virtual column %s.', $name));
        }

        return $this->virtualColumns[$name];
    }

    /**
     * Set the value of a virtual column in this object
     *
     * @param string $name  The virtual column name
     * @param mixed  $value The value to give to the virtual column
     *
     * @return $this|Settings The current object, for fluid interface
     */
    public function setVirtualColumn($name, $value)
    {
        $this->virtualColumns[$name] = $value;

        return $this;
    }

    /**
     * Logs a message using Propel::log().
     *
     * @param  string  $msg
     * @param  int     $priority One of the Propel::LOG_* logging levels
     * @return boolean
     */
    protected function log($msg, $priority = Propel::LOG_INFO)
    {
        return Propel::log(get_class($this) . ': ' . $msg, $priority);
    }

    /**
     * Export the current object properties to a string, using a given parser format
     * <code>
     * $book = BookQuery::create()->findPk(9012);
     * echo $book->exportTo('JSON');
     *  => {"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * @param  mixed   $parser                 A AbstractParser instance, or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param  boolean $includeLazyLoadColumns (optional) Whether to include lazy load(ed) columns. Defaults to TRUE.
     * @return string  The exported data
     */
    public function exportTo($parser, $includeLazyLoadColumns = true)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        return $parser->fromArray($this->toArray(TableMap::TYPE_PHPNAME, $includeLazyLoadColumns, array(), true));
    }

    /**
     * Clean up internal collections prior to serializing
     * Avoids recursive loops that turn into segmentation faults when serializing
     */
    public function __sleep()
    {
        $this->clearAllReferences();

        $cls = new \ReflectionClass($this);
        $propertyNames = [];
        $serializableProperties = array_diff($cls->getProperties(), $cls->getProperties(\ReflectionProperty::IS_STATIC));

        foreach ($serializableProperties as $property) {
            $propertyNames[] = $property->getName();
        }

        return $propertyNames;
    }

    /**
     * Get the [siteurl] column value.
     *
     * @return string
     */
    public function getSiteUrl()
    {
        return $this->siteurl;
    }

    /**
     * Get the [owner] column value.
     *
     * @return string
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Get the [notificationemail] column value.
     *
     * @return string
     */
    public function getNotificationEmail()
    {
        return $this->notificationemail;
    }

    /**
     * Get the [adminemailaddress] column value.
     *
     * @return string
     */
    public function getAdminEmailAddress()
    {
        return $this->adminemailaddress;
    }

    /**
     * Get the [norehearsalemail] column value.
     *
     * @return string
     */
    public function getNoRehearsalEmail()
    {
        return $this->norehearsalemail;
    }

    /**
     * Get the [yesrehearsal] column value.
     *
     * @return string
     */
    public function getYesRehearsal()
    {
        return $this->yesrehearsal;
    }

    /**
     * Get the [newusermessage] column value.
     *
     * @return string
     */
    public function getNewUserMessage()
    {
        return $this->newusermessage;
    }

    /**
     * Get the [version] column value.
     *
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Get the [lang_locale] column value.
     *
     * @return string
     */
    public function getLangLocale()
    {
        return $this->lang_locale;
    }

    /**
     * Get the [event_sorting_latest] column value.
     *
     * @return int
     */
    public function getEventSortingLatest()
    {
        return $this->event_sorting_latest;
    }

    /**
     * Get the [snapshot_show_two_month] column value.
     *
     * @return int
     */
    public function getSnapshotShowTwoMonth()
    {
        return $this->snapshot_show_two_month;
    }

    /**
     * Get the [snapshot_reduce_skills_by_group] column value.
     *
     * @return int
     */
    public function getSnapshotReduceSkillsByGroup()
    {
        return $this->snapshot_reduce_skills_by_group;
    }

    /**
     * Get the [logged_in_show_snapshot_button] column value.
     *
     * @return int
     */
    public function getLoggedInShowSnapshotButton()
    {
        return $this->logged_in_show_snapshot_button;
    }

    /**
     * Get the [time_format_long] column value.
     *
     * @return string
     */
    public function getTimeFormatLong()
    {
        return $this->time_format_long;
    }

    /**
     * Get the [time_format_normal] column value.
     *
     * @return string
     */
    public function getTimeFormatNormal()
    {
        return $this->time_format_normal;
    }

    /**
     * Get the [time_format_short] column value.
     *
     * @return string
     */
    public function getTimeFormatShort()
    {
        return $this->time_format_short;
    }

    /**
     * Get the [time_only_format] column value.
     *
     * @return string
     */
    public function getTimeOnlyFormat()
    {
        return $this->time_only_format;
    }

    /**
     * Get the [date_only_format] column value.
     *
     * @return string
     */
    public function getDateOnlyFormat()
    {
        return $this->date_only_format;
    }

    /**
     * Get the [day_only_format] column value.
     *
     * @return string
     */
    public function getDayOnlyFormat()
    {
        return $this->day_only_format;
    }

    /**
     * Get the [users_start_with_myevents] column value.
     *
     * @return int
     */
    public function getUsersStartWithMyEvents()
    {
        return $this->users_start_with_myevents;
    }

    /**
     * Get the [time_zone] column value.
     *
     * @return string
     */
    public function getTimeZone()
    {
        return $this->time_zone;
    }

    /**
     * Get the [google_group_calendar] column value.
     *
     * @return string
     */
    public function getGoogleGroupCalendar()
    {
        return $this->google_group_calendar;
    }

    /**
     * Get the [overviewemail] column value.
     *
     * @return string
     */
    public function getOverviewEmail()
    {
        return $this->overviewemail;
    }

    /**
     * Get the [group_sorting_name] column value.
     *
     * @return int
     */
    public function getGroupSortingName()
    {
        return $this->group_sorting_name;
    }

    /**
     * Get the [debug_mode] column value.
     *
     * @return int
     */
    public function getDebugMode()
    {
        return $this->debug_mode;
    }

    /**
     * Get the [days_to_alert] column value.
     *
     * @return int
     */
    public function getDaysToAlert()
    {
        return $this->days_to_alert;
    }

    /**
     * Get the [token] column value.
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Get the [skin] column value.
     *
     * @return string
     */
    public function getSkin()
    {
        return $this->skin;
    }

    /**
     * Set the value of [siteurl] column.
     *
     * @param string $v new value
     * @return $this|\Settings The current object (for fluent API support)
     */
    public function setSiteUrl($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->siteurl !== $v) {
            $this->siteurl = $v;
            $this->modifiedColumns[SettingsTableMap::COL_SITEURL] = true;
        }

        return $this;
    } // setSiteUrl()

    /**
     * Set the value of [owner] column.
     *
     * @param string $v new value
     * @return $this|\Settings The current object (for fluent API support)
     */
    public function setOwner($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->owner !== $v) {
            $this->owner = $v;
            $this->modifiedColumns[SettingsTableMap::COL_OWNER] = true;
        }

        return $this;
    } // setOwner()

    /**
     * Set the value of [notificationemail] column.
     *
     * @param string $v new value
     * @return $this|\Settings The current object (for fluent API support)
     */
    public function setNotificationEmail($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->notificationemail !== $v) {
            $this->notificationemail = $v;
            $this->modifiedColumns[SettingsTableMap::COL_NOTIFICATIONEMAIL] = true;
        }

        return $this;
    } // setNotificationEmail()

    /**
     * Set the value of [adminemailaddress] column.
     *
     * @param string $v new value
     * @return $this|\Settings The current object (for fluent API support)
     */
    public function setAdminEmailAddress($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->adminemailaddress !== $v) {
            $this->adminemailaddress = $v;
            $this->modifiedColumns[SettingsTableMap::COL_ADMINEMAILADDRESS] = true;
        }

        return $this;
    } // setAdminEmailAddress()

    /**
     * Set the value of [norehearsalemail] column.
     *
     * @param string $v new value
     * @return $this|\Settings The current object (for fluent API support)
     */
    public function setNoRehearsalEmail($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->norehearsalemail !== $v) {
            $this->norehearsalemail = $v;
            $this->modifiedColumns[SettingsTableMap::COL_NOREHEARSALEMAIL] = true;
        }

        return $this;
    } // setNoRehearsalEmail()

    /**
     * Set the value of [yesrehearsal] column.
     *
     * @param string $v new value
     * @return $this|\Settings The current object (for fluent API support)
     */
    public function setYesRehearsal($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->yesrehearsal !== $v) {
            $this->yesrehearsal = $v;
            $this->modifiedColumns[SettingsTableMap::COL_YESREHEARSAL] = true;
        }

        return $this;
    } // setYesRehearsal()

    /**
     * Set the value of [newusermessage] column.
     *
     * @param string $v new value
     * @return $this|\Settings The current object (for fluent API support)
     */
    public function setNewUserMessage($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->newusermessage !== $v) {
            $this->newusermessage = $v;
            $this->modifiedColumns[SettingsTableMap::COL_NEWUSERMESSAGE] = true;
        }

        return $this;
    } // setNewUserMessage()

    /**
     * Set the value of [version] column.
     *
     * @param string $v new value
     * @return $this|\Settings The current object (for fluent API support)
     */
    public function setVersion($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->version !== $v) {
            $this->version = $v;
            $this->modifiedColumns[SettingsTableMap::COL_VERSION] = true;
        }

        return $this;
    } // setVersion()

    /**
     * Set the value of [lang_locale] column.
     *
     * @param string $v new value
     * @return $this|\Settings The current object (for fluent API support)
     */
    public function setLangLocale($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->lang_locale !== $v) {
            $this->lang_locale = $v;
            $this->modifiedColumns[SettingsTableMap::COL_LANG_LOCALE] = true;
        }

        return $this;
    } // setLangLocale()

    /**
     * Set the value of [event_sorting_latest] column.
     *
     * @param int $v new value
     * @return $this|\Settings The current object (for fluent API support)
     */
    public function setEventSortingLatest($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->event_sorting_latest !== $v) {
            $this->event_sorting_latest = $v;
            $this->modifiedColumns[SettingsTableMap::COL_EVENT_SORTING_LATEST] = true;
        }

        return $this;
    } // setEventSortingLatest()

    /**
     * Set the value of [snapshot_show_two_month] column.
     *
     * @param int $v new value
     * @return $this|\Settings The current object (for fluent API support)
     */
    public function setSnapshotShowTwoMonth($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->snapshot_show_two_month !== $v) {
            $this->snapshot_show_two_month = $v;
            $this->modifiedColumns[SettingsTableMap::COL_SNAPSHOT_SHOW_TWO_MONTH] = true;
        }

        return $this;
    } // setSnapshotShowTwoMonth()

    /**
     * Set the value of [snapshot_reduce_skills_by_group] column.
     *
     * @param int $v new value
     * @return $this|\Settings The current object (for fluent API support)
     */
    public function setSnapshotReduceSkillsByGroup($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->snapshot_reduce_skills_by_group !== $v) {
            $this->snapshot_reduce_skills_by_group = $v;
            $this->modifiedColumns[SettingsTableMap::COL_SNAPSHOT_REDUCE_SKILLS_BY_GROUP] = true;
        }

        return $this;
    } // setSnapshotReduceSkillsByGroup()

    /**
     * Set the value of [logged_in_show_snapshot_button] column.
     *
     * @param int $v new value
     * @return $this|\Settings The current object (for fluent API support)
     */
    public function setLoggedInShowSnapshotButton($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->logged_in_show_snapshot_button !== $v) {
            $this->logged_in_show_snapshot_button = $v;
            $this->modifiedColumns[SettingsTableMap::COL_LOGGED_IN_SHOW_SNAPSHOT_BUTTON] = true;
        }

        return $this;
    } // setLoggedInShowSnapshotButton()

    /**
     * Set the value of [time_format_long] column.
     *
     * @param string $v new value
     * @return $this|\Settings The current object (for fluent API support)
     */
    public function setTimeFormatLong($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->time_format_long !== $v) {
            $this->time_format_long = $v;
            $this->modifiedColumns[SettingsTableMap::COL_TIME_FORMAT_LONG] = true;
        }

        return $this;
    } // setTimeFormatLong()

    /**
     * Set the value of [time_format_normal] column.
     *
     * @param string $v new value
     * @return $this|\Settings The current object (for fluent API support)
     */
    public function setTimeFormatNormal($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->time_format_normal !== $v) {
            $this->time_format_normal = $v;
            $this->modifiedColumns[SettingsTableMap::COL_TIME_FORMAT_NORMAL] = true;
        }

        return $this;
    } // setTimeFormatNormal()

    /**
     * Set the value of [time_format_short] column.
     *
     * @param string $v new value
     * @return $this|\Settings The current object (for fluent API support)
     */
    public function setTimeFormatShort($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->time_format_short !== $v) {
            $this->time_format_short = $v;
            $this->modifiedColumns[SettingsTableMap::COL_TIME_FORMAT_SHORT] = true;
        }

        return $this;
    } // setTimeFormatShort()

    /**
     * Set the value of [time_only_format] column.
     *
     * @param string $v new value
     * @return $this|\Settings The current object (for fluent API support)
     */
    public function setTimeOnlyFormat($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->time_only_format !== $v) {
            $this->time_only_format = $v;
            $this->modifiedColumns[SettingsTableMap::COL_TIME_ONLY_FORMAT] = true;
        }

        return $this;
    } // setTimeOnlyFormat()

    /**
     * Set the value of [date_only_format] column.
     *
     * @param string $v new value
     * @return $this|\Settings The current object (for fluent API support)
     */
    public function setDateOnlyFormat($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->date_only_format !== $v) {
            $this->date_only_format = $v;
            $this->modifiedColumns[SettingsTableMap::COL_DATE_ONLY_FORMAT] = true;
        }

        return $this;
    } // setDateOnlyFormat()

    /**
     * Set the value of [day_only_format] column.
     *
     * @param string $v new value
     * @return $this|\Settings The current object (for fluent API support)
     */
    public function setDayOnlyFormat($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->day_only_format !== $v) {
            $this->day_only_format = $v;
            $this->modifiedColumns[SettingsTableMap::COL_DAY_ONLY_FORMAT] = true;
        }

        return $this;
    } // setDayOnlyFormat()

    /**
     * Set the value of [users_start_with_myevents] column.
     *
     * @param int $v new value
     * @return $this|\Settings The current object (for fluent API support)
     */
    public function setUsersStartWithMyEvents($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->users_start_with_myevents !== $v) {
            $this->users_start_with_myevents = $v;
            $this->modifiedColumns[SettingsTableMap::COL_USERS_START_WITH_MYEVENTS] = true;
        }

        return $this;
    } // setUsersStartWithMyEvents()

    /**
     * Set the value of [time_zone] column.
     *
     * @param string $v new value
     * @return $this|\Settings The current object (for fluent API support)
     */
    public function setTimeZone($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->time_zone !== $v) {
            $this->time_zone = $v;
            $this->modifiedColumns[SettingsTableMap::COL_TIME_ZONE] = true;
        }

        return $this;
    } // setTimeZone()

    /**
     * Set the value of [google_group_calendar] column.
     *
     * @param string $v new value
     * @return $this|\Settings The current object (for fluent API support)
     */
    public function setGoogleGroupCalendar($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->google_group_calendar !== $v) {
            $this->google_group_calendar = $v;
            $this->modifiedColumns[SettingsTableMap::COL_GOOGLE_GROUP_CALENDAR] = true;
        }

        return $this;
    } // setGoogleGroupCalendar()

    /**
     * Set the value of [overviewemail] column.
     *
     * @param string $v new value
     * @return $this|\Settings The current object (for fluent API support)
     */
    public function setOverviewEmail($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->overviewemail !== $v) {
            $this->overviewemail = $v;
            $this->modifiedColumns[SettingsTableMap::COL_OVERVIEWEMAIL] = true;
        }

        return $this;
    } // setOverviewEmail()

    /**
     * Set the value of [group_sorting_name] column.
     *
     * @param int $v new value
     * @return $this|\Settings The current object (for fluent API support)
     */
    public function setGroupSortingName($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->group_sorting_name !== $v) {
            $this->group_sorting_name = $v;
            $this->modifiedColumns[SettingsTableMap::COL_GROUP_SORTING_NAME] = true;
        }

        return $this;
    } // setGroupSortingName()

    /**
     * Set the value of [debug_mode] column.
     *
     * @param int $v new value
     * @return $this|\Settings The current object (for fluent API support)
     */
    public function setDebugMode($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->debug_mode !== $v) {
            $this->debug_mode = $v;
            $this->modifiedColumns[SettingsTableMap::COL_DEBUG_MODE] = true;
        }

        return $this;
    } // setDebugMode()

    /**
     * Set the value of [days_to_alert] column.
     *
     * @param int $v new value
     * @return $this|\Settings The current object (for fluent API support)
     */
    public function setDaysToAlert($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->days_to_alert !== $v) {
            $this->days_to_alert = $v;
            $this->modifiedColumns[SettingsTableMap::COL_DAYS_TO_ALERT] = true;
        }

        return $this;
    } // setDaysToAlert()

    /**
     * Set the value of [token] column.
     *
     * @param string $v new value
     * @return $this|\Settings The current object (for fluent API support)
     */
    public function setToken($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->token !== $v) {
            $this->token = $v;
            $this->modifiedColumns[SettingsTableMap::COL_TOKEN] = true;
        }

        return $this;
    } // setToken()

    /**
     * Set the value of [skin] column.
     *
     * @param string $v new value
     * @return $this|\Settings The current object (for fluent API support)
     */
    public function setSkin($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->skin !== $v) {
            $this->skin = $v;
            $this->modifiedColumns[SettingsTableMap::COL_SKIN] = true;
        }

        return $this;
    } // setSkin()

    /**
     * Indicates whether the columns in this object are only set to default values.
     *
     * This method can be used in conjunction with isModified() to indicate whether an object is both
     * modified _and_ has some values set which are non-default.
     *
     * @return boolean Whether the columns in this object are only been set with default values.
     */
    public function hasOnlyDefaultValues()
    {
        if ($this->debug_mode !== 0) {
            return false;
        }

        if ($this->days_to_alert !== 5) {
            return false;
        }

        if ($this->token !== '') {
            return false;
        }

        if ($this->skin !== '') {
            return false;
        }

        // otherwise, everything was equal, so return TRUE
        return true;
    } // hasOnlyDefaultValues()

    /**
     * Hydrates (populates) the object variables with values from the database resultset.
     *
     * An offset (0-based "start column") is specified so that objects can be hydrated
     * with a subset of the columns in the resultset rows.  This is needed, for example,
     * for results of JOIN queries where the resultset row includes columns from two or
     * more tables.
     *
     * @param array   $row       The row returned by DataFetcher->fetch().
     * @param int     $startcol  0-based offset column which indicates which restultset column to start with.
     * @param boolean $rehydrate Whether this object is being re-hydrated from the database.
     * @param string  $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                  One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                            TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @return int             next starting column
     * @throws PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate($row, $startcol = 0, $rehydrate = false, $indexType = TableMap::TYPE_NUM)
    {
        try {
            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : SettingsTableMap::translateFieldName('SiteUrl', TableMap::TYPE_PHPNAME, $indexType)];
            $this->siteurl = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : SettingsTableMap::translateFieldName('Owner', TableMap::TYPE_PHPNAME, $indexType)];
            $this->owner = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : SettingsTableMap::translateFieldName('NotificationEmail', TableMap::TYPE_PHPNAME, $indexType)];
            $this->notificationemail = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : SettingsTableMap::translateFieldName('AdminEmailAddress', TableMap::TYPE_PHPNAME, $indexType)];
            $this->adminemailaddress = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : SettingsTableMap::translateFieldName('NoRehearsalEmail', TableMap::TYPE_PHPNAME, $indexType)];
            $this->norehearsalemail = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : SettingsTableMap::translateFieldName('YesRehearsal', TableMap::TYPE_PHPNAME, $indexType)];
            $this->yesrehearsal = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : SettingsTableMap::translateFieldName('NewUserMessage', TableMap::TYPE_PHPNAME, $indexType)];
            $this->newusermessage = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : SettingsTableMap::translateFieldName('Version', TableMap::TYPE_PHPNAME, $indexType)];
            $this->version = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : SettingsTableMap::translateFieldName('LangLocale', TableMap::TYPE_PHPNAME, $indexType)];
            $this->lang_locale = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 9 + $startcol : SettingsTableMap::translateFieldName('EventSortingLatest', TableMap::TYPE_PHPNAME, $indexType)];
            $this->event_sorting_latest = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 10 + $startcol : SettingsTableMap::translateFieldName('SnapshotShowTwoMonth', TableMap::TYPE_PHPNAME, $indexType)];
            $this->snapshot_show_two_month = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 11 + $startcol : SettingsTableMap::translateFieldName('SnapshotReduceSkillsByGroup', TableMap::TYPE_PHPNAME, $indexType)];
            $this->snapshot_reduce_skills_by_group = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 12 + $startcol : SettingsTableMap::translateFieldName('LoggedInShowSnapshotButton', TableMap::TYPE_PHPNAME, $indexType)];
            $this->logged_in_show_snapshot_button = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 13 + $startcol : SettingsTableMap::translateFieldName('TimeFormatLong', TableMap::TYPE_PHPNAME, $indexType)];
            $this->time_format_long = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 14 + $startcol : SettingsTableMap::translateFieldName('TimeFormatNormal', TableMap::TYPE_PHPNAME, $indexType)];
            $this->time_format_normal = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 15 + $startcol : SettingsTableMap::translateFieldName('TimeFormatShort', TableMap::TYPE_PHPNAME, $indexType)];
            $this->time_format_short = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 16 + $startcol : SettingsTableMap::translateFieldName('TimeOnlyFormat', TableMap::TYPE_PHPNAME, $indexType)];
            $this->time_only_format = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 17 + $startcol : SettingsTableMap::translateFieldName('DateOnlyFormat', TableMap::TYPE_PHPNAME, $indexType)];
            $this->date_only_format = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 18 + $startcol : SettingsTableMap::translateFieldName('DayOnlyFormat', TableMap::TYPE_PHPNAME, $indexType)];
            $this->day_only_format = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 19 + $startcol : SettingsTableMap::translateFieldName('UsersStartWithMyEvents', TableMap::TYPE_PHPNAME, $indexType)];
            $this->users_start_with_myevents = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 20 + $startcol : SettingsTableMap::translateFieldName('TimeZone', TableMap::TYPE_PHPNAME, $indexType)];
            $this->time_zone = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 21 + $startcol : SettingsTableMap::translateFieldName('GoogleGroupCalendar', TableMap::TYPE_PHPNAME, $indexType)];
            $this->google_group_calendar = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 22 + $startcol : SettingsTableMap::translateFieldName('OverviewEmail', TableMap::TYPE_PHPNAME, $indexType)];
            $this->overviewemail = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 23 + $startcol : SettingsTableMap::translateFieldName('GroupSortingName', TableMap::TYPE_PHPNAME, $indexType)];
            $this->group_sorting_name = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 24 + $startcol : SettingsTableMap::translateFieldName('DebugMode', TableMap::TYPE_PHPNAME, $indexType)];
            $this->debug_mode = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 25 + $startcol : SettingsTableMap::translateFieldName('DaysToAlert', TableMap::TYPE_PHPNAME, $indexType)];
            $this->days_to_alert = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 26 + $startcol : SettingsTableMap::translateFieldName('Token', TableMap::TYPE_PHPNAME, $indexType)];
            $this->token = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 27 + $startcol : SettingsTableMap::translateFieldName('Skin', TableMap::TYPE_PHPNAME, $indexType)];
            $this->skin = (null !== $col) ? (string) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 28; // 28 = SettingsTableMap::NUM_HYDRATE_COLUMNS.
        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Settings'), 0, $e);
        }
    }

    /**
     * Checks and repairs the internal consistency of the object.
     *
     * This method is executed after an already-instantiated object is re-hydrated
     * from the database.  It exists to check any foreign keys to make sure that
     * the objects related to the current object are correct based on foreign key.
     *
     * You can override this method in the stub class, but you should always invoke
     * the base method from the overridden method (i.e. parent::ensureConsistency()),
     * in case your model changes.
     *
     * @throws PropelException
     */
    public function ensureConsistency()
    {
    } // ensureConsistency

    /**
     * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
     *
     * This will only work if the object has been saved and has a valid primary key set.
     *
     * @param      boolean $deep (optional) Whether to also de-associated any related objects.
     * @param      ConnectionInterface $con (optional) The ConnectionInterface connection to use.
     * @return void
     * @throws PropelException - if this object is deleted, unsaved or doesn't have pk match in db
     */
    public function reload($deep = false, ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("Cannot reload a deleted object.");
        }

        if ($this->isNew()) {
            throw new PropelException("Cannot reload an unsaved object.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(SettingsTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildSettingsQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?
        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Settings::setDeleted()
     * @see Settings::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(SettingsTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildSettingsQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
                $this->setDeleted(true);
            }
        });
    }

    /**
     * Persists this object to the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All modified related objects will also be persisted in the doSave()
     * method.  This method wraps all precipitate database operations in a
     * single transaction.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see doSave()
     */
    public function save(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("You cannot save an object that has been deleted.");
        }

        if ($this->alreadyInSave) {
            return 0;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(SettingsTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $ret = $this->preSave($con);
            $isInsert = $this->isNew();
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
            } else {
                $ret = $ret && $this->preUpdate($con);
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                SettingsTableMap::addInstanceToPool($this);
            } else {
                $affectedRows = 0;
            }

            return $affectedRows;
        });
    }

    /**
     * Performs the work of inserting or updating the row in the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All related objects are also updated in this method.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see save()
     */
    protected function doSave(ConnectionInterface $con)
    {
        $affectedRows = 0; // initialize var to track total num of affected rows
        if (!$this->alreadyInSave) {
            $this->alreadyInSave = true;

            if ($this->isNew() || $this->isModified()) {
                // persist changes
                if ($this->isNew()) {
                    $this->doInsert($con);
                    $affectedRows += 1;
                } else {
                    $affectedRows += $this->doUpdate($con);
                }
                $this->resetModified();
            }

            $this->alreadyInSave = false;
        }

        return $affectedRows;
    } // doSave()

    /**
     * Insert the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @throws PropelException
     * @see doSave()
     */
    protected function doInsert(ConnectionInterface $con)
    {
        $modifiedColumns = array();
        $index = 0;


         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(SettingsTableMap::COL_SITEURL)) {
            $modifiedColumns[':p' . $index++]  = 'siteurl';
        }
        if ($this->isColumnModified(SettingsTableMap::COL_OWNER)) {
            $modifiedColumns[':p' . $index++]  = 'owner';
        }
        if ($this->isColumnModified(SettingsTableMap::COL_NOTIFICATIONEMAIL)) {
            $modifiedColumns[':p' . $index++]  = 'notificationemail';
        }
        if ($this->isColumnModified(SettingsTableMap::COL_ADMINEMAILADDRESS)) {
            $modifiedColumns[':p' . $index++]  = 'adminemailaddress';
        }
        if ($this->isColumnModified(SettingsTableMap::COL_NOREHEARSALEMAIL)) {
            $modifiedColumns[':p' . $index++]  = 'norehearsalemail';
        }
        if ($this->isColumnModified(SettingsTableMap::COL_YESREHEARSAL)) {
            $modifiedColumns[':p' . $index++]  = 'yesrehearsal';
        }
        if ($this->isColumnModified(SettingsTableMap::COL_NEWUSERMESSAGE)) {
            $modifiedColumns[':p' . $index++]  = 'newusermessage';
        }
        if ($this->isColumnModified(SettingsTableMap::COL_VERSION)) {
            $modifiedColumns[':p' . $index++]  = 'version';
        }
        if ($this->isColumnModified(SettingsTableMap::COL_LANG_LOCALE)) {
            $modifiedColumns[':p' . $index++]  = 'lang_locale';
        }
        if ($this->isColumnModified(SettingsTableMap::COL_EVENT_SORTING_LATEST)) {
            $modifiedColumns[':p' . $index++]  = 'event_sorting_latest';
        }
        if ($this->isColumnModified(SettingsTableMap::COL_SNAPSHOT_SHOW_TWO_MONTH)) {
            $modifiedColumns[':p' . $index++]  = 'snapshot_show_two_month';
        }
        if ($this->isColumnModified(SettingsTableMap::COL_SNAPSHOT_REDUCE_SKILLS_BY_GROUP)) {
            $modifiedColumns[':p' . $index++]  = 'snapshot_reduce_skills_by_group';
        }
        if ($this->isColumnModified(SettingsTableMap::COL_LOGGED_IN_SHOW_SNAPSHOT_BUTTON)) {
            $modifiedColumns[':p' . $index++]  = 'logged_in_show_snapshot_button';
        }
        if ($this->isColumnModified(SettingsTableMap::COL_TIME_FORMAT_LONG)) {
            $modifiedColumns[':p' . $index++]  = 'time_format_long';
        }
        if ($this->isColumnModified(SettingsTableMap::COL_TIME_FORMAT_NORMAL)) {
            $modifiedColumns[':p' . $index++]  = 'time_format_normal';
        }
        if ($this->isColumnModified(SettingsTableMap::COL_TIME_FORMAT_SHORT)) {
            $modifiedColumns[':p' . $index++]  = 'time_format_short';
        }
        if ($this->isColumnModified(SettingsTableMap::COL_TIME_ONLY_FORMAT)) {
            $modifiedColumns[':p' . $index++]  = 'time_only_format';
        }
        if ($this->isColumnModified(SettingsTableMap::COL_DATE_ONLY_FORMAT)) {
            $modifiedColumns[':p' . $index++]  = 'date_only_format';
        }
        if ($this->isColumnModified(SettingsTableMap::COL_DAY_ONLY_FORMAT)) {
            $modifiedColumns[':p' . $index++]  = 'day_only_format';
        }
        if ($this->isColumnModified(SettingsTableMap::COL_USERS_START_WITH_MYEVENTS)) {
            $modifiedColumns[':p' . $index++]  = 'users_start_with_myevents';
        }
        if ($this->isColumnModified(SettingsTableMap::COL_TIME_ZONE)) {
            $modifiedColumns[':p' . $index++]  = 'time_zone';
        }
        if ($this->isColumnModified(SettingsTableMap::COL_GOOGLE_GROUP_CALENDAR)) {
            $modifiedColumns[':p' . $index++]  = 'google_group_calendar';
        }
        if ($this->isColumnModified(SettingsTableMap::COL_OVERVIEWEMAIL)) {
            $modifiedColumns[':p' . $index++]  = 'overviewemail';
        }
        if ($this->isColumnModified(SettingsTableMap::COL_GROUP_SORTING_NAME)) {
            $modifiedColumns[':p' . $index++]  = 'group_sorting_name';
        }
        if ($this->isColumnModified(SettingsTableMap::COL_DEBUG_MODE)) {
            $modifiedColumns[':p' . $index++]  = 'debug_mode';
        }
        if ($this->isColumnModified(SettingsTableMap::COL_DAYS_TO_ALERT)) {
            $modifiedColumns[':p' . $index++]  = 'days_to_alert';
        }
        if ($this->isColumnModified(SettingsTableMap::COL_TOKEN)) {
            $modifiedColumns[':p' . $index++]  = 'token';
        }
        if ($this->isColumnModified(SettingsTableMap::COL_SKIN)) {
            $modifiedColumns[':p' . $index++]  = 'skin';
        }

        $sql = sprintf(
            'INSERT INTO cr_settings (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'siteurl':
                        $stmt->bindValue($identifier, $this->siteurl, PDO::PARAM_STR);
                        break;
                    case 'owner':
                        $stmt->bindValue($identifier, $this->owner, PDO::PARAM_STR);
                        break;
                    case 'notificationemail':
                        $stmt->bindValue($identifier, $this->notificationemail, PDO::PARAM_STR);
                        break;
                    case 'adminemailaddress':
                        $stmt->bindValue($identifier, $this->adminemailaddress, PDO::PARAM_STR);
                        break;
                    case 'norehearsalemail':
                        $stmt->bindValue($identifier, $this->norehearsalemail, PDO::PARAM_STR);
                        break;
                    case 'yesrehearsal':
                        $stmt->bindValue($identifier, $this->yesrehearsal, PDO::PARAM_STR);
                        break;
                    case 'newusermessage':
                        $stmt->bindValue($identifier, $this->newusermessage, PDO::PARAM_STR);
                        break;
                    case 'version':
                        $stmt->bindValue($identifier, $this->version, PDO::PARAM_STR);
                        break;
                    case 'lang_locale':
                        $stmt->bindValue($identifier, $this->lang_locale, PDO::PARAM_STR);
                        break;
                    case 'event_sorting_latest':
                        $stmt->bindValue($identifier, $this->event_sorting_latest, PDO::PARAM_INT);
                        break;
                    case 'snapshot_show_two_month':
                        $stmt->bindValue($identifier, $this->snapshot_show_two_month, PDO::PARAM_INT);
                        break;
                    case 'snapshot_reduce_skills_by_group':
                        $stmt->bindValue($identifier, $this->snapshot_reduce_skills_by_group, PDO::PARAM_INT);
                        break;
                    case 'logged_in_show_snapshot_button':
                        $stmt->bindValue($identifier, $this->logged_in_show_snapshot_button, PDO::PARAM_INT);
                        break;
                    case 'time_format_long':
                        $stmt->bindValue($identifier, $this->time_format_long, PDO::PARAM_STR);
                        break;
                    case 'time_format_normal':
                        $stmt->bindValue($identifier, $this->time_format_normal, PDO::PARAM_STR);
                        break;
                    case 'time_format_short':
                        $stmt->bindValue($identifier, $this->time_format_short, PDO::PARAM_STR);
                        break;
                    case 'time_only_format':
                        $stmt->bindValue($identifier, $this->time_only_format, PDO::PARAM_STR);
                        break;
                    case 'date_only_format':
                        $stmt->bindValue($identifier, $this->date_only_format, PDO::PARAM_STR);
                        break;
                    case 'day_only_format':
                        $stmt->bindValue($identifier, $this->day_only_format, PDO::PARAM_STR);
                        break;
                    case 'users_start_with_myevents':
                        $stmt->bindValue($identifier, $this->users_start_with_myevents, PDO::PARAM_INT);
                        break;
                    case 'time_zone':
                        $stmt->bindValue($identifier, $this->time_zone, PDO::PARAM_STR);
                        break;
                    case 'google_group_calendar':
                        $stmt->bindValue($identifier, $this->google_group_calendar, PDO::PARAM_STR);
                        break;
                    case 'overviewemail':
                        $stmt->bindValue($identifier, $this->overviewemail, PDO::PARAM_STR);
                        break;
                    case 'group_sorting_name':
                        $stmt->bindValue($identifier, $this->group_sorting_name, PDO::PARAM_INT);
                        break;
                    case 'debug_mode':
                        $stmt->bindValue($identifier, $this->debug_mode, PDO::PARAM_INT);
                        break;
                    case 'days_to_alert':
                        $stmt->bindValue($identifier, $this->days_to_alert, PDO::PARAM_INT);
                        break;
                    case 'token':
                        $stmt->bindValue($identifier, $this->token, PDO::PARAM_STR);
                        break;
                    case 'skin':
                        $stmt->bindValue($identifier, $this->skin, PDO::PARAM_STR);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), 0, $e);
        }

        $this->setNew(false);
    }

    /**
     * Update the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @return Integer Number of updated rows
     * @see doSave()
     */
    protected function doUpdate(ConnectionInterface $con)
    {
        $selectCriteria = $this->buildPkeyCriteria();
        $valuesCriteria = $this->buildCriteria();

        return $selectCriteria->doUpdate($valuesCriteria, $con);
    }

    /**
     * Retrieves a field from the object by name passed in as a string.
     *
     * @param      string $name name
     * @param      string $type The type of fieldname the $name is of:
     *                     one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                     TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                     Defaults to TableMap::TYPE_PHPNAME.
     * @return mixed Value of field.
     */
    public function getByName($name, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = SettingsTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
        $field = $this->getByPosition($pos);

        return $field;
    }

    /**
     * Retrieves a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param      int $pos position in xml schema
     * @return mixed Value of field at $pos
     */
    public function getByPosition($pos)
    {
        switch ($pos) {
            case 0:
                return $this->getSiteUrl();
                break;
            case 1:
                return $this->getOwner();
                break;
            case 2:
                return $this->getNotificationEmail();
                break;
            case 3:
                return $this->getAdminEmailAddress();
                break;
            case 4:
                return $this->getNoRehearsalEmail();
                break;
            case 5:
                return $this->getYesRehearsal();
                break;
            case 6:
                return $this->getNewUserMessage();
                break;
            case 7:
                return $this->getVersion();
                break;
            case 8:
                return $this->getLangLocale();
                break;
            case 9:
                return $this->getEventSortingLatest();
                break;
            case 10:
                return $this->getSnapshotShowTwoMonth();
                break;
            case 11:
                return $this->getSnapshotReduceSkillsByGroup();
                break;
            case 12:
                return $this->getLoggedInShowSnapshotButton();
                break;
            case 13:
                return $this->getTimeFormatLong();
                break;
            case 14:
                return $this->getTimeFormatNormal();
                break;
            case 15:
                return $this->getTimeFormatShort();
                break;
            case 16:
                return $this->getTimeOnlyFormat();
                break;
            case 17:
                return $this->getDateOnlyFormat();
                break;
            case 18:
                return $this->getDayOnlyFormat();
                break;
            case 19:
                return $this->getUsersStartWithMyEvents();
                break;
            case 20:
                return $this->getTimeZone();
                break;
            case 21:
                return $this->getGoogleGroupCalendar();
                break;
            case 22:
                return $this->getOverviewEmail();
                break;
            case 23:
                return $this->getGroupSortingName();
                break;
            case 24:
                return $this->getDebugMode();
                break;
            case 25:
                return $this->getDaysToAlert();
                break;
            case 26:
                return $this->getToken();
                break;
            case 27:
                return $this->getSkin();
                break;
            default:
                return null;
                break;
        } // switch()
    }

    /**
     * Exports the object as an array.
     *
     * You can specify the key type of the array by passing one of the class
     * type constants.
     *
     * @param     string  $keyType (optional) One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     *                    TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                    Defaults to TableMap::TYPE_PHPNAME.
     * @param     boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to TRUE.
     * @param     array $alreadyDumpedObjects List of objects to skip to avoid recursion
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = TableMap::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array())
    {
        if (isset($alreadyDumpedObjects['Settings'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Settings'][$this->hashCode()] = true;
        $keys = SettingsTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getSiteUrl(),
            $keys[1] => $this->getOwner(),
            $keys[2] => $this->getNotificationEmail(),
            $keys[3] => $this->getAdminEmailAddress(),
            $keys[4] => $this->getNoRehearsalEmail(),
            $keys[5] => $this->getYesRehearsal(),
            $keys[6] => $this->getNewUserMessage(),
            $keys[7] => $this->getVersion(),
            $keys[8] => $this->getLangLocale(),
            $keys[9] => $this->getEventSortingLatest(),
            $keys[10] => $this->getSnapshotShowTwoMonth(),
            $keys[11] => $this->getSnapshotReduceSkillsByGroup(),
            $keys[12] => $this->getLoggedInShowSnapshotButton(),
            $keys[13] => $this->getTimeFormatLong(),
            $keys[14] => $this->getTimeFormatNormal(),
            $keys[15] => $this->getTimeFormatShort(),
            $keys[16] => $this->getTimeOnlyFormat(),
            $keys[17] => $this->getDateOnlyFormat(),
            $keys[18] => $this->getDayOnlyFormat(),
            $keys[19] => $this->getUsersStartWithMyEvents(),
            $keys[20] => $this->getTimeZone(),
            $keys[21] => $this->getGoogleGroupCalendar(),
            $keys[22] => $this->getOverviewEmail(),
            $keys[23] => $this->getGroupSortingName(),
            $keys[24] => $this->getDebugMode(),
            $keys[25] => $this->getDaysToAlert(),
            $keys[26] => $this->getToken(),
            $keys[27] => $this->getSkin(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }


        return $result;
    }

    /**
     * Sets a field from the object by name passed in as a string.
     *
     * @param  string $name
     * @param  mixed  $value field value
     * @param  string $type The type of fieldname the $name is of:
     *                one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                Defaults to TableMap::TYPE_PHPNAME.
     * @return $this|\Settings
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = SettingsTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\Settings
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setSiteUrl($value);
                break;
            case 1:
                $this->setOwner($value);
                break;
            case 2:
                $this->setNotificationEmail($value);
                break;
            case 3:
                $this->setAdminEmailAddress($value);
                break;
            case 4:
                $this->setNoRehearsalEmail($value);
                break;
            case 5:
                $this->setYesRehearsal($value);
                break;
            case 6:
                $this->setNewUserMessage($value);
                break;
            case 7:
                $this->setVersion($value);
                break;
            case 8:
                $this->setLangLocale($value);
                break;
            case 9:
                $this->setEventSortingLatest($value);
                break;
            case 10:
                $this->setSnapshotShowTwoMonth($value);
                break;
            case 11:
                $this->setSnapshotReduceSkillsByGroup($value);
                break;
            case 12:
                $this->setLoggedInShowSnapshotButton($value);
                break;
            case 13:
                $this->setTimeFormatLong($value);
                break;
            case 14:
                $this->setTimeFormatNormal($value);
                break;
            case 15:
                $this->setTimeFormatShort($value);
                break;
            case 16:
                $this->setTimeOnlyFormat($value);
                break;
            case 17:
                $this->setDateOnlyFormat($value);
                break;
            case 18:
                $this->setDayOnlyFormat($value);
                break;
            case 19:
                $this->setUsersStartWithMyEvents($value);
                break;
            case 20:
                $this->setTimeZone($value);
                break;
            case 21:
                $this->setGoogleGroupCalendar($value);
                break;
            case 22:
                $this->setOverviewEmail($value);
                break;
            case 23:
                $this->setGroupSortingName($value);
                break;
            case 24:
                $this->setDebugMode($value);
                break;
            case 25:
                $this->setDaysToAlert($value);
                break;
            case 26:
                $this->setToken($value);
                break;
            case 27:
                $this->setSkin($value);
                break;
        } // switch()

        return $this;
    }

    /**
     * Populates the object using an array.
     *
     * This is particularly useful when populating an object from one of the
     * request arrays (e.g. $_POST).  This method goes through the column
     * names, checking to see whether a matching key exists in populated
     * array. If so the setByName() method is called for that column.
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_PHPNAME.
     *
     * @param      array  $arr     An array to populate the object from.
     * @param      string $keyType The type of keys the array uses.
     * @return void
     */
    public function fromArray($arr, $keyType = TableMap::TYPE_PHPNAME)
    {
        $keys = SettingsTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setSiteUrl($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setOwner($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setNotificationEmail($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setAdminEmailAddress($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setNoRehearsalEmail($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setYesRehearsal($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setNewUserMessage($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setVersion($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->setLangLocale($arr[$keys[8]]);
        }
        if (array_key_exists($keys[9], $arr)) {
            $this->setEventSortingLatest($arr[$keys[9]]);
        }
        if (array_key_exists($keys[10], $arr)) {
            $this->setSnapshotShowTwoMonth($arr[$keys[10]]);
        }
        if (array_key_exists($keys[11], $arr)) {
            $this->setSnapshotReduceSkillsByGroup($arr[$keys[11]]);
        }
        if (array_key_exists($keys[12], $arr)) {
            $this->setLoggedInShowSnapshotButton($arr[$keys[12]]);
        }
        if (array_key_exists($keys[13], $arr)) {
            $this->setTimeFormatLong($arr[$keys[13]]);
        }
        if (array_key_exists($keys[14], $arr)) {
            $this->setTimeFormatNormal($arr[$keys[14]]);
        }
        if (array_key_exists($keys[15], $arr)) {
            $this->setTimeFormatShort($arr[$keys[15]]);
        }
        if (array_key_exists($keys[16], $arr)) {
            $this->setTimeOnlyFormat($arr[$keys[16]]);
        }
        if (array_key_exists($keys[17], $arr)) {
            $this->setDateOnlyFormat($arr[$keys[17]]);
        }
        if (array_key_exists($keys[18], $arr)) {
            $this->setDayOnlyFormat($arr[$keys[18]]);
        }
        if (array_key_exists($keys[19], $arr)) {
            $this->setUsersStartWithMyEvents($arr[$keys[19]]);
        }
        if (array_key_exists($keys[20], $arr)) {
            $this->setTimeZone($arr[$keys[20]]);
        }
        if (array_key_exists($keys[21], $arr)) {
            $this->setGoogleGroupCalendar($arr[$keys[21]]);
        }
        if (array_key_exists($keys[22], $arr)) {
            $this->setOverviewEmail($arr[$keys[22]]);
        }
        if (array_key_exists($keys[23], $arr)) {
            $this->setGroupSortingName($arr[$keys[23]]);
        }
        if (array_key_exists($keys[24], $arr)) {
            $this->setDebugMode($arr[$keys[24]]);
        }
        if (array_key_exists($keys[25], $arr)) {
            $this->setDaysToAlert($arr[$keys[25]]);
        }
        if (array_key_exists($keys[26], $arr)) {
            $this->setToken($arr[$keys[26]]);
        }
        if (array_key_exists($keys[27], $arr)) {
            $this->setSkin($arr[$keys[27]]);
        }
    }

     /**
     * Populate the current object from a string, using a given parser format
     * <code>
     * $book = new Book();
     * $book->importFrom('JSON', '{"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_PHPNAME.
     *
     * @param mixed $parser A AbstractParser instance,
     *                       or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param string $data The source data to import from
     * @param string $keyType The type of keys the array uses.
     *
     * @return $this|\Settings The current object, for fluid interface
     */
    public function importFrom($parser, $data, $keyType = TableMap::TYPE_PHPNAME)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        $this->fromArray($parser->toArray($data), $keyType);

        return $this;
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(SettingsTableMap::DATABASE_NAME);

        if ($this->isColumnModified(SettingsTableMap::COL_SITEURL)) {
            $criteria->add(SettingsTableMap::COL_SITEURL, $this->siteurl);
        }
        if ($this->isColumnModified(SettingsTableMap::COL_OWNER)) {
            $criteria->add(SettingsTableMap::COL_OWNER, $this->owner);
        }
        if ($this->isColumnModified(SettingsTableMap::COL_NOTIFICATIONEMAIL)) {
            $criteria->add(SettingsTableMap::COL_NOTIFICATIONEMAIL, $this->notificationemail);
        }
        if ($this->isColumnModified(SettingsTableMap::COL_ADMINEMAILADDRESS)) {
            $criteria->add(SettingsTableMap::COL_ADMINEMAILADDRESS, $this->adminemailaddress);
        }
        if ($this->isColumnModified(SettingsTableMap::COL_NOREHEARSALEMAIL)) {
            $criteria->add(SettingsTableMap::COL_NOREHEARSALEMAIL, $this->norehearsalemail);
        }
        if ($this->isColumnModified(SettingsTableMap::COL_YESREHEARSAL)) {
            $criteria->add(SettingsTableMap::COL_YESREHEARSAL, $this->yesrehearsal);
        }
        if ($this->isColumnModified(SettingsTableMap::COL_NEWUSERMESSAGE)) {
            $criteria->add(SettingsTableMap::COL_NEWUSERMESSAGE, $this->newusermessage);
        }
        if ($this->isColumnModified(SettingsTableMap::COL_VERSION)) {
            $criteria->add(SettingsTableMap::COL_VERSION, $this->version);
        }
        if ($this->isColumnModified(SettingsTableMap::COL_LANG_LOCALE)) {
            $criteria->add(SettingsTableMap::COL_LANG_LOCALE, $this->lang_locale);
        }
        if ($this->isColumnModified(SettingsTableMap::COL_EVENT_SORTING_LATEST)) {
            $criteria->add(SettingsTableMap::COL_EVENT_SORTING_LATEST, $this->event_sorting_latest);
        }
        if ($this->isColumnModified(SettingsTableMap::COL_SNAPSHOT_SHOW_TWO_MONTH)) {
            $criteria->add(SettingsTableMap::COL_SNAPSHOT_SHOW_TWO_MONTH, $this->snapshot_show_two_month);
        }
        if ($this->isColumnModified(SettingsTableMap::COL_SNAPSHOT_REDUCE_SKILLS_BY_GROUP)) {
            $criteria->add(SettingsTableMap::COL_SNAPSHOT_REDUCE_SKILLS_BY_GROUP, $this->snapshot_reduce_skills_by_group);
        }
        if ($this->isColumnModified(SettingsTableMap::COL_LOGGED_IN_SHOW_SNAPSHOT_BUTTON)) {
            $criteria->add(SettingsTableMap::COL_LOGGED_IN_SHOW_SNAPSHOT_BUTTON, $this->logged_in_show_snapshot_button);
        }
        if ($this->isColumnModified(SettingsTableMap::COL_TIME_FORMAT_LONG)) {
            $criteria->add(SettingsTableMap::COL_TIME_FORMAT_LONG, $this->time_format_long);
        }
        if ($this->isColumnModified(SettingsTableMap::COL_TIME_FORMAT_NORMAL)) {
            $criteria->add(SettingsTableMap::COL_TIME_FORMAT_NORMAL, $this->time_format_normal);
        }
        if ($this->isColumnModified(SettingsTableMap::COL_TIME_FORMAT_SHORT)) {
            $criteria->add(SettingsTableMap::COL_TIME_FORMAT_SHORT, $this->time_format_short);
        }
        if ($this->isColumnModified(SettingsTableMap::COL_TIME_ONLY_FORMAT)) {
            $criteria->add(SettingsTableMap::COL_TIME_ONLY_FORMAT, $this->time_only_format);
        }
        if ($this->isColumnModified(SettingsTableMap::COL_DATE_ONLY_FORMAT)) {
            $criteria->add(SettingsTableMap::COL_DATE_ONLY_FORMAT, $this->date_only_format);
        }
        if ($this->isColumnModified(SettingsTableMap::COL_DAY_ONLY_FORMAT)) {
            $criteria->add(SettingsTableMap::COL_DAY_ONLY_FORMAT, $this->day_only_format);
        }
        if ($this->isColumnModified(SettingsTableMap::COL_USERS_START_WITH_MYEVENTS)) {
            $criteria->add(SettingsTableMap::COL_USERS_START_WITH_MYEVENTS, $this->users_start_with_myevents);
        }
        if ($this->isColumnModified(SettingsTableMap::COL_TIME_ZONE)) {
            $criteria->add(SettingsTableMap::COL_TIME_ZONE, $this->time_zone);
        }
        if ($this->isColumnModified(SettingsTableMap::COL_GOOGLE_GROUP_CALENDAR)) {
            $criteria->add(SettingsTableMap::COL_GOOGLE_GROUP_CALENDAR, $this->google_group_calendar);
        }
        if ($this->isColumnModified(SettingsTableMap::COL_OVERVIEWEMAIL)) {
            $criteria->add(SettingsTableMap::COL_OVERVIEWEMAIL, $this->overviewemail);
        }
        if ($this->isColumnModified(SettingsTableMap::COL_GROUP_SORTING_NAME)) {
            $criteria->add(SettingsTableMap::COL_GROUP_SORTING_NAME, $this->group_sorting_name);
        }
        if ($this->isColumnModified(SettingsTableMap::COL_DEBUG_MODE)) {
            $criteria->add(SettingsTableMap::COL_DEBUG_MODE, $this->debug_mode);
        }
        if ($this->isColumnModified(SettingsTableMap::COL_DAYS_TO_ALERT)) {
            $criteria->add(SettingsTableMap::COL_DAYS_TO_ALERT, $this->days_to_alert);
        }
        if ($this->isColumnModified(SettingsTableMap::COL_TOKEN)) {
            $criteria->add(SettingsTableMap::COL_TOKEN, $this->token);
        }
        if ($this->isColumnModified(SettingsTableMap::COL_SKIN)) {
            $criteria->add(SettingsTableMap::COL_SKIN, $this->skin);
        }

        return $criteria;
    }

    /**
     * Builds a Criteria object containing the primary key for this object.
     *
     * Unlike buildCriteria() this method includes the primary key values regardless
     * of whether or not they have been modified.
     *
     * @throws LogicException if no primary key is defined
     *
     * @return Criteria The Criteria object containing value(s) for primary key(s).
     */
    public function buildPkeyCriteria()
    {
        throw new LogicException('The Settings object has no primary key');

        return $criteria;
    }

    /**
     * If the primary key is not null, return the hashcode of the
     * primary key. Otherwise, return the hash code of the object.
     *
     * @return int Hashcode
     */
    public function hashCode()
    {
        $validPk = false;

        $validPrimaryKeyFKs = 0;
        $primaryKeyFKs = [];

        if ($validPk) {
            return crc32(json_encode($this->getPrimaryKey(), JSON_UNESCAPED_UNICODE));
        } elseif ($validPrimaryKeyFKs) {
            return crc32(json_encode($primaryKeyFKs, JSON_UNESCAPED_UNICODE));
        }

        return spl_object_hash($this);
    }

    /**
     * Returns NULL since this table doesn't have a primary key.
     * This method exists only for BC and is deprecated!
     * @return null
     */
    public function getPrimaryKey()
    {
        return null;
    }

    /**
     * Dummy primary key setter.
     *
     * This function only exists to preserve backwards compatibility.  It is no longer
     * needed or required by the Persistent interface.  It will be removed in next BC-breaking
     * release of Propel.
     *
     * @deprecated
     */
    public function setPrimaryKey($pk)
    {
        // do nothing, because this object doesn't have any primary keys
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {
        return ;
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of \Settings (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setSiteUrl($this->getSiteUrl());
        $copyObj->setOwner($this->getOwner());
        $copyObj->setNotificationEmail($this->getNotificationEmail());
        $copyObj->setAdminEmailAddress($this->getAdminEmailAddress());
        $copyObj->setNoRehearsalEmail($this->getNoRehearsalEmail());
        $copyObj->setYesRehearsal($this->getYesRehearsal());
        $copyObj->setNewUserMessage($this->getNewUserMessage());
        $copyObj->setVersion($this->getVersion());
        $copyObj->setLangLocale($this->getLangLocale());
        $copyObj->setEventSortingLatest($this->getEventSortingLatest());
        $copyObj->setSnapshotShowTwoMonth($this->getSnapshotShowTwoMonth());
        $copyObj->setSnapshotReduceSkillsByGroup($this->getSnapshotReduceSkillsByGroup());
        $copyObj->setLoggedInShowSnapshotButton($this->getLoggedInShowSnapshotButton());
        $copyObj->setTimeFormatLong($this->getTimeFormatLong());
        $copyObj->setTimeFormatNormal($this->getTimeFormatNormal());
        $copyObj->setTimeFormatShort($this->getTimeFormatShort());
        $copyObj->setTimeOnlyFormat($this->getTimeOnlyFormat());
        $copyObj->setDateOnlyFormat($this->getDateOnlyFormat());
        $copyObj->setDayOnlyFormat($this->getDayOnlyFormat());
        $copyObj->setUsersStartWithMyEvents($this->getUsersStartWithMyEvents());
        $copyObj->setTimeZone($this->getTimeZone());
        $copyObj->setGoogleGroupCalendar($this->getGoogleGroupCalendar());
        $copyObj->setOverviewEmail($this->getOverviewEmail());
        $copyObj->setGroupSortingName($this->getGroupSortingName());
        $copyObj->setDebugMode($this->getDebugMode());
        $copyObj->setDaysToAlert($this->getDaysToAlert());
        $copyObj->setToken($this->getToken());
        $copyObj->setSkin($this->getSkin());
        if ($makeNew) {
            $copyObj->setNew(true);
        }
    }

    /**
     * Makes a copy of this object that will be inserted as a new row in table when saved.
     * It creates a new object filling in the simple attributes, but skipping any primary
     * keys that are defined for the table.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param  boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @return \Settings Clone of current object.
     * @throws PropelException
     */
    public function copy($deepCopy = false)
    {
        // we use get_class(), because this might be a subclass
        $clazz = get_class($this);
        $copyObj = new $clazz();
        $this->copyInto($copyObj, $deepCopy);

        return $copyObj;
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        $this->siteurl = null;
        $this->owner = null;
        $this->notificationemail = null;
        $this->adminemailaddress = null;
        $this->norehearsalemail = null;
        $this->yesrehearsal = null;
        $this->newusermessage = null;
        $this->version = null;
        $this->lang_locale = null;
        $this->event_sorting_latest = null;
        $this->snapshot_show_two_month = null;
        $this->snapshot_reduce_skills_by_group = null;
        $this->logged_in_show_snapshot_button = null;
        $this->time_format_long = null;
        $this->time_format_normal = null;
        $this->time_format_short = null;
        $this->time_only_format = null;
        $this->date_only_format = null;
        $this->day_only_format = null;
        $this->users_start_with_myevents = null;
        $this->time_zone = null;
        $this->google_group_calendar = null;
        $this->overviewemail = null;
        $this->group_sorting_name = null;
        $this->debug_mode = null;
        $this->days_to_alert = null;
        $this->token = null;
        $this->skin = null;
        $this->alreadyInSave = false;
        $this->clearAllReferences();
        $this->applyDefaultValues();
        $this->resetModified();
        $this->setNew(true);
        $this->setDeleted(false);
    }

    /**
     * Resets all references and back-references to other model objects or collections of model objects.
     *
     * This method is used to reset all php object references (not the actual reference in the database).
     * Necessary for object serialisation.
     *
     * @param      boolean $deep Whether to also clear the references on all referrer objects.
     */
    public function clearAllReferences($deep = false)
    {
        if ($deep) {
        } // if ($deep)
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(SettingsTableMap::DEFAULT_STRING_FORMAT);
    }

    /**
     * Code to be run before persisting the object
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preSave(ConnectionInterface $con = null)
    {
        if (is_callable('parent::preSave')) {
            return parent::preSave($con);
        }
        return true;
    }

    /**
     * Code to be run after persisting the object
     * @param ConnectionInterface $con
     */
    public function postSave(ConnectionInterface $con = null)
    {
        if (is_callable('parent::postSave')) {
            parent::postSave($con);
        }
    }

    /**
     * Code to be run before inserting to database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preInsert(ConnectionInterface $con = null)
    {
        if (is_callable('parent::preInsert')) {
            return parent::preInsert($con);
        }
        return true;
    }

    /**
     * Code to be run after inserting to database
     * @param ConnectionInterface $con
     */
    public function postInsert(ConnectionInterface $con = null)
    {
        if (is_callable('parent::postInsert')) {
            parent::postInsert($con);
        }
    }

    /**
     * Code to be run before updating the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preUpdate(ConnectionInterface $con = null)
    {
        if (is_callable('parent::preUpdate')) {
            return parent::preUpdate($con);
        }
        return true;
    }

    /**
     * Code to be run after updating the object in database
     * @param ConnectionInterface $con
     */
    public function postUpdate(ConnectionInterface $con = null)
    {
        if (is_callable('parent::postUpdate')) {
            parent::postUpdate($con);
        }
    }

    /**
     * Code to be run before deleting the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preDelete(ConnectionInterface $con = null)
    {
        if (is_callable('parent::preDelete')) {
            return parent::preDelete($con);
        }
        return true;
    }

    /**
     * Code to be run after deleting the object in database
     * @param ConnectionInterface $con
     */
    public function postDelete(ConnectionInterface $con = null)
    {
        if (is_callable('parent::postDelete')) {
            parent::postDelete($con);
        }
    }


    /**
     * Derived method to catches calls to undefined methods.
     *
     * Provides magic import/export method support (fromXML()/toXML(), fromYAML()/toYAML(), etc.).
     * Allows to define default __call() behavior if you overwrite __call()
     *
     * @param string $name
     * @param mixed  $params
     *
     * @return array|string
     */
    public function __call($name, $params)
    {
        if (0 === strpos($name, 'get')) {
            $virtualColumn = substr($name, 3);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }

            $virtualColumn = lcfirst($virtualColumn);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }
        }

        if (0 === strpos($name, 'from')) {
            $format = substr($name, 4);

            return $this->importFrom($format, reset($params));
        }

        if (0 === strpos($name, 'to')) {
            $format = substr($name, 2);
            $includeLazyLoadColumns = isset($params[0]) ? $params[0] : true;

            return $this->exportTo($format, $includeLazyLoadColumns);
        }

        throw new BadMethodCallException(sprintf('Call to undefined method: %s.', $name));
    }
}
