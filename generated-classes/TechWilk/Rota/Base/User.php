<?php

namespace TechWilk\Rota\Base;

use \DateTime;
use \Exception;
use \PDO;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\BadMethodCallException;
use Propel\Runtime\Exception\LogicException;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Parser\AbstractParser;
use Propel\Runtime\Util\PropelDateTime;
use TechWilk\Rota\CalendarToken as ChildCalendarToken;
use TechWilk\Rota\CalendarTokenQuery as ChildCalendarTokenQuery;
use TechWilk\Rota\Event as ChildEvent;
use TechWilk\Rota\EventQuery as ChildEventQuery;
use TechWilk\Rota\Notification as ChildNotification;
use TechWilk\Rota\NotificationQuery as ChildNotificationQuery;
use TechWilk\Rota\SocialAuth as ChildSocialAuth;
use TechWilk\Rota\SocialAuthQuery as ChildSocialAuthQuery;
use TechWilk\Rota\Statistic as ChildStatistic;
use TechWilk\Rota\StatisticQuery as ChildStatisticQuery;
use TechWilk\Rota\Swap as ChildSwap;
use TechWilk\Rota\SwapQuery as ChildSwapQuery;
use TechWilk\Rota\Unavailable as ChildUnavailable;
use TechWilk\Rota\UnavailableQuery as ChildUnavailableQuery;
use TechWilk\Rota\User as ChildUser;
use TechWilk\Rota\UserPermission as ChildUserPermission;
use TechWilk\Rota\UserPermissionQuery as ChildUserPermissionQuery;
use TechWilk\Rota\UserQuery as ChildUserQuery;
use TechWilk\Rota\UserRole as ChildUserRole;
use TechWilk\Rota\UserRoleQuery as ChildUserRoleQuery;
use TechWilk\Rota\Map\CalendarTokenTableMap;
use TechWilk\Rota\Map\EventTableMap;
use TechWilk\Rota\Map\NotificationTableMap;
use TechWilk\Rota\Map\SocialAuthTableMap;
use TechWilk\Rota\Map\StatisticTableMap;
use TechWilk\Rota\Map\SwapTableMap;
use TechWilk\Rota\Map\UnavailableTableMap;
use TechWilk\Rota\Map\UserPermissionTableMap;
use TechWilk\Rota\Map\UserRoleTableMap;
use TechWilk\Rota\Map\UserTableMap;

/**
 * Base class that represents a row from the 'cr_users' table.
 *
 *
 *
 * @package    propel.generator.TechWilk.Rota.Base
 */
abstract class User implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\TechWilk\\Rota\\Map\\UserTableMap';


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
     * The value for the id field.
     *
     * @var        int
     */
    protected $id;

    /**
     * The value for the firstname field.
     *
     * Note: this column has a database default value of: ''
     * @var        string
     */
    protected $firstname;

    /**
     * The value for the lastname field.
     *
     * Note: this column has a database default value of: ''
     * @var        string
     */
    protected $lastname;

    /**
     * The value for the username field.
     *
     * Note: this column has a database default value of: ''
     * @var        string
     */
    protected $username;

    /**
     * The value for the password field.
     *
     * Note: this column has a database default value of: ''
     * @var        string
     */
    protected $password;

    /**
     * The value for the isadmin field.
     *
     * Note: this column has a database default value of: '0'
     * @var        string
     */
    protected $isadmin;

    /**
     * The value for the email field.
     *
     * @var        \TechWilk\Rota\EmailAddress
     */
    protected $email;

    /**
     * The value for the mobile field.
     *
     * Note: this column has a database default value of: ''
     * @var        string
     */
    protected $mobile;

    /**
     * The value for the isoverviewrecipient field.
     *
     * Note: this column has a database default value of: '0'
     * @var        string
     */
    protected $isoverviewrecipient;

    /**
     * The value for the recievereminderemails field.
     *
     * Note: this column has a database default value of: true
     * @var        boolean
     */
    protected $recievereminderemails;

    /**
     * The value for the isbandadmin field.
     *
     * Note: this column has a database default value of: '0'
     * @var        string
     */
    protected $isbandadmin;

    /**
     * The value for the iseventeditor field.
     *
     * Note: this column has a database default value of: '0'
     * @var        string
     */
    protected $iseventeditor;

    /**
     * The value for the lastlogin field.
     *
     * @var        DateTime
     */
    protected $lastlogin;

    /**
     * The value for the passwordchanged field.
     *
     * @var        DateTime
     */
    protected $passwordchanged;

    /**
     * The value for the created field.
     *
     * @var        DateTime
     */
    protected $created;

    /**
     * The value for the updated field.
     *
     * @var        DateTime
     */
    protected $updated;

    /**
     * @var        ObjectCollection|ChildCalendarToken[] Collection to store aggregation of ChildCalendarToken objects.
     */
    protected $collCalendarTokens;
    protected $collCalendarTokensPartial;

    /**
     * @var        ObjectCollection|ChildEvent[] Collection to store aggregation of ChildEvent objects.
     */
    protected $collEvents;
    protected $collEventsPartial;

    /**
     * @var        ObjectCollection|ChildUnavailable[] Collection to store aggregation of ChildUnavailable objects.
     */
    protected $collUnavailables;
    protected $collUnavailablesPartial;

    /**
     * @var        ObjectCollection|ChildNotification[] Collection to store aggregation of ChildNotification objects.
     */
    protected $collNotifications;
    protected $collNotificationsPartial;

    /**
     * @var        ObjectCollection|ChildSocialAuth[] Collection to store aggregation of ChildSocialAuth objects.
     */
    protected $collSocialAuths;
    protected $collSocialAuthsPartial;

    /**
     * @var        ObjectCollection|ChildStatistic[] Collection to store aggregation of ChildStatistic objects.
     */
    protected $collStatistics;
    protected $collStatisticsPartial;

    /**
     * @var        ObjectCollection|ChildSwap[] Collection to store aggregation of ChildSwap objects.
     */
    protected $collSwaps;
    protected $collSwapsPartial;

    /**
     * @var        ObjectCollection|ChildUserRole[] Collection to store aggregation of ChildUserRole objects.
     */
    protected $collUserRoles;
    protected $collUserRolesPartial;

    /**
     * @var        ObjectCollection|ChildUserPermission[] Collection to store aggregation of ChildUserPermission objects.
     */
    protected $collUserPermissions;
    protected $collUserPermissionsPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildCalendarToken[]
     */
    protected $calendarTokensScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildEvent[]
     */
    protected $eventsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildUnavailable[]
     */
    protected $unavailablesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildNotification[]
     */
    protected $notificationsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildSocialAuth[]
     */
    protected $socialAuthsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildStatistic[]
     */
    protected $statisticsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildSwap[]
     */
    protected $swapsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildUserRole[]
     */
    protected $userRolesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildUserPermission[]
     */
    protected $userPermissionsScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see __construct()
     */
    public function applyDefaultValues()
    {
        $this->firstname = '';
        $this->lastname = '';
        $this->username = '';
        $this->password = '';
        $this->isadmin = '0';
        $this->mobile = '';
        $this->isoverviewrecipient = '0';
        $this->recievereminderemails = true;
        $this->isbandadmin = '0';
        $this->iseventeditor = '0';
    }

    /**
     * Initializes internal state of TechWilk\Rota\Base\User object.
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
     * Compares this with another <code>User</code> instance.  If
     * <code>obj</code> is an instance of <code>User</code>, delegates to
     * <code>equals(User)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|User The current object, for fluid interface
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
     * Get the [id] column value.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the [firstname] column value.
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstname;
    }

    /**
     * Get the [lastname] column value.
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastname;
    }

    /**
     * Get the [username] column value.
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Get the [password] column value.
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Get the [isadmin] column value.
     *
     * @return string
     */
    public function getIsAdmin()
    {
        return $this->isadmin;
    }

    /**
     * Get the [email] column value.
     *
     * @return \TechWilk\Rota\EmailAddress
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Get the [mobile] column value.
     *
     * @return string
     */
    public function getMobile()
    {
        return $this->mobile;
    }

    /**
     * Get the [isoverviewrecipient] column value.
     *
     * @return string
     */
    public function getIsOverviewRecipient()
    {
        return $this->isoverviewrecipient;
    }

    /**
     * Get the [recievereminderemails] column value.
     *
     * @return boolean
     */
    public function getRecieveReminderEmails()
    {
        return $this->recievereminderemails;
    }

    /**
     * Get the [recievereminderemails] column value.
     *
     * @return boolean
     */
    public function isRecieveReminderEmails()
    {
        return $this->getRecieveReminderEmails();
    }

    /**
     * Get the [isbandadmin] column value.
     *
     * @return string
     */
    public function getIsBandAdmin()
    {
        return $this->isbandadmin;
    }

    /**
     * Get the [iseventeditor] column value.
     *
     * @return string
     */
    public function getIsEventEditor()
    {
        return $this->iseventeditor;
    }

    /**
     * Get the [optionally formatted] temporal [lastlogin] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getLastLogin($format = null)
    {
        if ($format === null) {
            return $this->lastlogin;
        } else {
            return $this->lastlogin instanceof \DateTimeInterface ? $this->lastlogin->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [passwordchanged] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getPasswordChanged($format = null)
    {
        if ($format === null) {
            return $this->passwordchanged;
        } else {
            return $this->passwordchanged instanceof \DateTimeInterface ? $this->passwordchanged->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [created] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getCreated($format = null)
    {
        if ($format === null) {
            return $this->created;
        } else {
            return $this->created instanceof \DateTimeInterface ? $this->created->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [updated] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getUpdated($format = null)
    {
        if ($format === null) {
            return $this->updated;
        } else {
            return $this->updated instanceof \DateTimeInterface ? $this->updated->format($format) : null;
        }
    }

    /**
     * Set the value of [id] column.
     *
     * @param int $v new value
     * @return $this|\TechWilk\Rota\User The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[UserTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [firstname] column.
     *
     * @param string $v new value
     * @return $this|\TechWilk\Rota\User The current object (for fluent API support)
     */
    public function setFirstName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->firstname !== $v) {
            $this->firstname = $v;
            $this->modifiedColumns[UserTableMap::COL_FIRSTNAME] = true;
        }

        return $this;
    } // setFirstName()

    /**
     * Set the value of [lastname] column.
     *
     * @param string $v new value
     * @return $this|\TechWilk\Rota\User The current object (for fluent API support)
     */
    public function setLastName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->lastname !== $v) {
            $this->lastname = $v;
            $this->modifiedColumns[UserTableMap::COL_LASTNAME] = true;
        }

        return $this;
    } // setLastName()

    /**
     * Set the value of [username] column.
     *
     * @param string $v new value
     * @return $this|\TechWilk\Rota\User The current object (for fluent API support)
     */
    public function setUsername($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->username !== $v) {
            $this->username = $v;
            $this->modifiedColumns[UserTableMap::COL_USERNAME] = true;
        }

        return $this;
    } // setUsername()

    /**
     * Set the value of [password] column.
     *
     * @param string $v new value
     * @return $this|\TechWilk\Rota\User The current object (for fluent API support)
     */
    public function setPassword($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->password !== $v) {
            $this->password = $v;
            $this->modifiedColumns[UserTableMap::COL_PASSWORD] = true;
        }

        return $this;
    } // setPassword()

    /**
     * Set the value of [isadmin] column.
     *
     * @param string $v new value
     * @return $this|\TechWilk\Rota\User The current object (for fluent API support)
     */
    public function setIsAdmin($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->isadmin !== $v) {
            $this->isadmin = $v;
            $this->modifiedColumns[UserTableMap::COL_ISADMIN] = true;
        }

        return $this;
    } // setIsAdmin()

    /**
     * Set the value of [email] column.
     *
     * @param \TechWilk\Rota\EmailAddress $v new value
     * @return $this|\TechWilk\Rota\User The current object (for fluent API support)
     */
    public function setEmail($v)
    {
        if ($this->email !== $v) {
            $this->email = $v;
            $this->modifiedColumns[UserTableMap::COL_EMAIL] = true;
        }

        return $this;
    } // setEmail()

    /**
     * Set the value of [mobile] column.
     *
     * @param string $v new value
     * @return $this|\TechWilk\Rota\User The current object (for fluent API support)
     */
    public function setMobile($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->mobile !== $v) {
            $this->mobile = $v;
            $this->modifiedColumns[UserTableMap::COL_MOBILE] = true;
        }

        return $this;
    } // setMobile()

    /**
     * Set the value of [isoverviewrecipient] column.
     *
     * @param string $v new value
     * @return $this|\TechWilk\Rota\User The current object (for fluent API support)
     */
    public function setIsOverviewRecipient($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->isoverviewrecipient !== $v) {
            $this->isoverviewrecipient = $v;
            $this->modifiedColumns[UserTableMap::COL_ISOVERVIEWRECIPIENT] = true;
        }

        return $this;
    } // setIsOverviewRecipient()

    /**
     * Sets the value of the [recievereminderemails] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param  boolean|integer|string $v The new value
     * @return $this|\TechWilk\Rota\User The current object (for fluent API support)
     */
    public function setRecieveReminderEmails($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->recievereminderemails !== $v) {
            $this->recievereminderemails = $v;
            $this->modifiedColumns[UserTableMap::COL_RECIEVEREMINDEREMAILS] = true;
        }

        return $this;
    } // setRecieveReminderEmails()

    /**
     * Set the value of [isbandadmin] column.
     *
     * @param string $v new value
     * @return $this|\TechWilk\Rota\User The current object (for fluent API support)
     */
    public function setIsBandAdmin($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->isbandadmin !== $v) {
            $this->isbandadmin = $v;
            $this->modifiedColumns[UserTableMap::COL_ISBANDADMIN] = true;
        }

        return $this;
    } // setIsBandAdmin()

    /**
     * Set the value of [iseventeditor] column.
     *
     * @param string $v new value
     * @return $this|\TechWilk\Rota\User The current object (for fluent API support)
     */
    public function setIsEventEditor($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->iseventeditor !== $v) {
            $this->iseventeditor = $v;
            $this->modifiedColumns[UserTableMap::COL_ISEVENTEDITOR] = true;
        }

        return $this;
    } // setIsEventEditor()

    /**
     * Sets the value of [lastlogin] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\TechWilk\Rota\User The current object (for fluent API support)
     */
    public function setLastLogin($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->lastlogin !== null || $dt !== null) {
            if ($this->lastlogin === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->lastlogin->format("Y-m-d H:i:s.u")) {
                $this->lastlogin = $dt === null ? null : clone $dt;
                $this->modifiedColumns[UserTableMap::COL_LASTLOGIN] = true;
            }
        } // if either are not null

        return $this;
    } // setLastLogin()

    /**
     * Sets the value of [passwordchanged] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\TechWilk\Rota\User The current object (for fluent API support)
     */
    public function setPasswordChanged($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->passwordchanged !== null || $dt !== null) {
            if ($this->passwordchanged === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->passwordchanged->format("Y-m-d H:i:s.u")) {
                $this->passwordchanged = $dt === null ? null : clone $dt;
                $this->modifiedColumns[UserTableMap::COL_PASSWORDCHANGED] = true;
            }
        } // if either are not null

        return $this;
    } // setPasswordChanged()

    /**
     * Sets the value of [created] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\TechWilk\Rota\User The current object (for fluent API support)
     */
    public function setCreated($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created !== null || $dt !== null) {
            if ($this->created === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->created->format("Y-m-d H:i:s.u")) {
                $this->created = $dt === null ? null : clone $dt;
                $this->modifiedColumns[UserTableMap::COL_CREATED] = true;
            }
        } // if either are not null

        return $this;
    } // setCreated()

    /**
     * Sets the value of [updated] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\TechWilk\Rota\User The current object (for fluent API support)
     */
    public function setUpdated($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated !== null || $dt !== null) {
            if ($this->updated === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->updated->format("Y-m-d H:i:s.u")) {
                $this->updated = $dt === null ? null : clone $dt;
                $this->modifiedColumns[UserTableMap::COL_UPDATED] = true;
            }
        } // if either are not null

        return $this;
    } // setUpdated()

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
        if ($this->firstname !== '') {
            return false;
        }

        if ($this->lastname !== '') {
            return false;
        }

        if ($this->username !== '') {
            return false;
        }

        if ($this->password !== '') {
            return false;
        }

        if ($this->isadmin !== '0') {
            return false;
        }

        if ($this->mobile !== '') {
            return false;
        }

        if ($this->isoverviewrecipient !== '0') {
            return false;
        }

        if ($this->recievereminderemails !== true) {
            return false;
        }

        if ($this->isbandadmin !== '0') {
            return false;
        }

        if ($this->iseventeditor !== '0') {
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
            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : UserTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : UserTableMap::translateFieldName('FirstName', TableMap::TYPE_PHPNAME, $indexType)];
            $this->firstname = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : UserTableMap::translateFieldName('LastName', TableMap::TYPE_PHPNAME, $indexType)];
            $this->lastname = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : UserTableMap::translateFieldName('Username', TableMap::TYPE_PHPNAME, $indexType)];
            $this->username = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : UserTableMap::translateFieldName('Password', TableMap::TYPE_PHPNAME, $indexType)];
            $this->password = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : UserTableMap::translateFieldName('IsAdmin', TableMap::TYPE_PHPNAME, $indexType)];
            $this->isadmin = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : UserTableMap::translateFieldName('Email', TableMap::TYPE_PHPNAME, $indexType)];
            $this->email = (null !== $col) ? new \TechWilk\Rota\EmailAddress($col) : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : UserTableMap::translateFieldName('Mobile', TableMap::TYPE_PHPNAME, $indexType)];
            $this->mobile = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : UserTableMap::translateFieldName('IsOverviewRecipient', TableMap::TYPE_PHPNAME, $indexType)];
            $this->isoverviewrecipient = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 9 + $startcol : UserTableMap::translateFieldName('RecieveReminderEmails', TableMap::TYPE_PHPNAME, $indexType)];
            $this->recievereminderemails = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 10 + $startcol : UserTableMap::translateFieldName('IsBandAdmin', TableMap::TYPE_PHPNAME, $indexType)];
            $this->isbandadmin = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 11 + $startcol : UserTableMap::translateFieldName('IsEventEditor', TableMap::TYPE_PHPNAME, $indexType)];
            $this->iseventeditor = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 12 + $startcol : UserTableMap::translateFieldName('LastLogin', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->lastlogin = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 13 + $startcol : UserTableMap::translateFieldName('PasswordChanged', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->passwordchanged = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 14 + $startcol : UserTableMap::translateFieldName('Created', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->created = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 15 + $startcol : UserTableMap::translateFieldName('Updated', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->updated = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 16; // 16 = UserTableMap::NUM_HYDRATE_COLUMNS.
        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\TechWilk\\Rota\\User'), 0, $e);
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
            $con = Propel::getServiceContainer()->getReadConnection(UserTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildUserQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collCalendarTokens = null;

            $this->collEvents = null;

            $this->collUnavailables = null;

            $this->collNotifications = null;

            $this->collSocialAuths = null;

            $this->collStatistics = null;

            $this->collSwaps = null;

            $this->collUserRoles = null;

            $this->collUserPermissions = null;
        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see User::setDeleted()
     * @see User::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(UserTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildUserQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(UserTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $ret = $this->preSave($con);
            $isInsert = $this->isNew();
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior

                if (!$this->isColumnModified(UserTableMap::COL_CREATED)) {
                    $this->setCreated(\Propel\Runtime\Util\PropelDateTime::createHighPrecision());
                }
                if (!$this->isColumnModified(UserTableMap::COL_UPDATED)) {
                    $this->setUpdated(\Propel\Runtime\Util\PropelDateTime::createHighPrecision());
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(UserTableMap::COL_UPDATED)) {
                    $this->setUpdated(\Propel\Runtime\Util\PropelDateTime::createHighPrecision());
                }
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                UserTableMap::addInstanceToPool($this);
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

            if ($this->calendarTokensScheduledForDeletion !== null) {
                if (!$this->calendarTokensScheduledForDeletion->isEmpty()) {
                    \TechWilk\Rota\CalendarTokenQuery::create()
                        ->filterByPrimaryKeys($this->calendarTokensScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->calendarTokensScheduledForDeletion = null;
                }
            }

            if ($this->collCalendarTokens !== null) {
                foreach ($this->collCalendarTokens as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->eventsScheduledForDeletion !== null) {
                if (!$this->eventsScheduledForDeletion->isEmpty()) {
                    \TechWilk\Rota\EventQuery::create()
                        ->filterByPrimaryKeys($this->eventsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->eventsScheduledForDeletion = null;
                }
            }

            if ($this->collEvents !== null) {
                foreach ($this->collEvents as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->unavailablesScheduledForDeletion !== null) {
                if (!$this->unavailablesScheduledForDeletion->isEmpty()) {
                    \TechWilk\Rota\UnavailableQuery::create()
                        ->filterByPrimaryKeys($this->unavailablesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->unavailablesScheduledForDeletion = null;
                }
            }

            if ($this->collUnavailables !== null) {
                foreach ($this->collUnavailables as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->notificationsScheduledForDeletion !== null) {
                if (!$this->notificationsScheduledForDeletion->isEmpty()) {
                    \TechWilk\Rota\NotificationQuery::create()
                        ->filterByPrimaryKeys($this->notificationsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->notificationsScheduledForDeletion = null;
                }
            }

            if ($this->collNotifications !== null) {
                foreach ($this->collNotifications as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->socialAuthsScheduledForDeletion !== null) {
                if (!$this->socialAuthsScheduledForDeletion->isEmpty()) {
                    \TechWilk\Rota\SocialAuthQuery::create()
                        ->filterByPrimaryKeys($this->socialAuthsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->socialAuthsScheduledForDeletion = null;
                }
            }

            if ($this->collSocialAuths !== null) {
                foreach ($this->collSocialAuths as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->statisticsScheduledForDeletion !== null) {
                if (!$this->statisticsScheduledForDeletion->isEmpty()) {
                    foreach ($this->statisticsScheduledForDeletion as $statistic) {
                        // need to save related object because we set the relation to null
                        $statistic->save($con);
                    }
                    $this->statisticsScheduledForDeletion = null;
                }
            }

            if ($this->collStatistics !== null) {
                foreach ($this->collStatistics as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->swapsScheduledForDeletion !== null) {
                if (!$this->swapsScheduledForDeletion->isEmpty()) {
                    \TechWilk\Rota\SwapQuery::create()
                        ->filterByPrimaryKeys($this->swapsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->swapsScheduledForDeletion = null;
                }
            }

            if ($this->collSwaps !== null) {
                foreach ($this->collSwaps as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->userRolesScheduledForDeletion !== null) {
                if (!$this->userRolesScheduledForDeletion->isEmpty()) {
                    \TechWilk\Rota\UserRoleQuery::create()
                        ->filterByPrimaryKeys($this->userRolesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->userRolesScheduledForDeletion = null;
                }
            }

            if ($this->collUserRoles !== null) {
                foreach ($this->collUserRoles as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->userPermissionsScheduledForDeletion !== null) {
                if (!$this->userPermissionsScheduledForDeletion->isEmpty()) {
                    \TechWilk\Rota\UserPermissionQuery::create()
                        ->filterByPrimaryKeys($this->userPermissionsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->userPermissionsScheduledForDeletion = null;
                }
            }

            if ($this->collUserPermissions !== null) {
                foreach ($this->collUserPermissions as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
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

        $this->modifiedColumns[UserTableMap::COL_ID] = true;

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(UserTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = 'id';
        }
        if ($this->isColumnModified(UserTableMap::COL_FIRSTNAME)) {
            $modifiedColumns[':p' . $index++]  = 'firstName';
        }
        if ($this->isColumnModified(UserTableMap::COL_LASTNAME)) {
            $modifiedColumns[':p' . $index++]  = 'lastName';
        }
        if ($this->isColumnModified(UserTableMap::COL_USERNAME)) {
            $modifiedColumns[':p' . $index++]  = 'username';
        }
        if ($this->isColumnModified(UserTableMap::COL_PASSWORD)) {
            $modifiedColumns[':p' . $index++]  = 'password';
        }
        if ($this->isColumnModified(UserTableMap::COL_ISADMIN)) {
            $modifiedColumns[':p' . $index++]  = 'isAdmin';
        }
        if ($this->isColumnModified(UserTableMap::COL_EMAIL)) {
            $modifiedColumns[':p' . $index++]  = 'email';
        }
        if ($this->isColumnModified(UserTableMap::COL_MOBILE)) {
            $modifiedColumns[':p' . $index++]  = 'mobile';
        }
        if ($this->isColumnModified(UserTableMap::COL_ISOVERVIEWRECIPIENT)) {
            $modifiedColumns[':p' . $index++]  = 'isOverviewRecipient';
        }
        if ($this->isColumnModified(UserTableMap::COL_RECIEVEREMINDEREMAILS)) {
            $modifiedColumns[':p' . $index++]  = 'recieveReminderEmails';
        }
        if ($this->isColumnModified(UserTableMap::COL_ISBANDADMIN)) {
            $modifiedColumns[':p' . $index++]  = 'isBandAdmin';
        }
        if ($this->isColumnModified(UserTableMap::COL_ISEVENTEDITOR)) {
            $modifiedColumns[':p' . $index++]  = 'isEventEditor';
        }
        if ($this->isColumnModified(UserTableMap::COL_LASTLOGIN)) {
            $modifiedColumns[':p' . $index++]  = 'lastLogin';
        }
        if ($this->isColumnModified(UserTableMap::COL_PASSWORDCHANGED)) {
            $modifiedColumns[':p' . $index++]  = 'passwordChanged';
        }
        if ($this->isColumnModified(UserTableMap::COL_CREATED)) {
            $modifiedColumns[':p' . $index++]  = 'created';
        }
        if ($this->isColumnModified(UserTableMap::COL_UPDATED)) {
            $modifiedColumns[':p' . $index++]  = 'updated';
        }

        $sql = sprintf(
            'INSERT INTO cr_users (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'id':
                        $stmt->bindValue($identifier, $this->id, PDO::PARAM_INT);
                        break;
                    case 'firstName':
                        $stmt->bindValue($identifier, $this->firstname, PDO::PARAM_STR);
                        break;
                    case 'lastName':
                        $stmt->bindValue($identifier, $this->lastname, PDO::PARAM_STR);
                        break;
                    case 'username':
                        $stmt->bindValue($identifier, $this->username, PDO::PARAM_STR);
                        break;
                    case 'password':
                        $stmt->bindValue($identifier, $this->password, PDO::PARAM_STR);
                        break;
                    case 'isAdmin':
                        $stmt->bindValue($identifier, $this->isadmin, PDO::PARAM_STR);
                        break;
                    case 'email':
                        $stmt->bindValue($identifier, $this->email, PDO::PARAM_STR);
                        break;
                    case 'mobile':
                        $stmt->bindValue($identifier, $this->mobile, PDO::PARAM_STR);
                        break;
                    case 'isOverviewRecipient':
                        $stmt->bindValue($identifier, $this->isoverviewrecipient, PDO::PARAM_STR);
                        break;
                    case 'recieveReminderEmails':
                        $stmt->bindValue($identifier, (int) $this->recievereminderemails, PDO::PARAM_INT);
                        break;
                    case 'isBandAdmin':
                        $stmt->bindValue($identifier, $this->isbandadmin, PDO::PARAM_STR);
                        break;
                    case 'isEventEditor':
                        $stmt->bindValue($identifier, $this->iseventeditor, PDO::PARAM_STR);
                        break;
                    case 'lastLogin':
                        $stmt->bindValue($identifier, $this->lastlogin ? $this->lastlogin->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);
                        break;
                    case 'passwordChanged':
                        $stmt->bindValue($identifier, $this->passwordchanged ? $this->passwordchanged->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);
                        break;
                    case 'created':
                        $stmt->bindValue($identifier, $this->created ? $this->created->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);
                        break;
                    case 'updated':
                        $stmt->bindValue($identifier, $this->updated ? $this->updated->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), 0, $e);
        }

        try {
            $pk = $con->lastInsertId();
        } catch (Exception $e) {
            throw new PropelException('Unable to get autoincrement id.', 0, $e);
        }
        if ($pk !== null) {
            $this->setId($pk);
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
        $pos = UserTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getId();
                break;
            case 1:
                return $this->getFirstName();
                break;
            case 2:
                return $this->getLastName();
                break;
            case 3:
                return $this->getUsername();
                break;
            case 4:
                return $this->getPassword();
                break;
            case 5:
                return $this->getIsAdmin();
                break;
            case 6:
                return $this->getEmail();
                break;
            case 7:
                return $this->getMobile();
                break;
            case 8:
                return $this->getIsOverviewRecipient();
                break;
            case 9:
                return $this->getRecieveReminderEmails();
                break;
            case 10:
                return $this->getIsBandAdmin();
                break;
            case 11:
                return $this->getIsEventEditor();
                break;
            case 12:
                return $this->getLastLogin();
                break;
            case 13:
                return $this->getPasswordChanged();
                break;
            case 14:
                return $this->getCreated();
                break;
            case 15:
                return $this->getUpdated();
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
     * @param     boolean $includeForeignObjects (optional) Whether to include hydrated related objects. Default to FALSE.
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = TableMap::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array(), $includeForeignObjects = false)
    {
        if (isset($alreadyDumpedObjects['User'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['User'][$this->hashCode()] = true;
        $keys = UserTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getFirstName(),
            $keys[2] => $this->getLastName(),
            $keys[3] => $this->getUsername(),
            $keys[4] => $this->getPassword(),
            $keys[5] => $this->getIsAdmin(),
            $keys[6] => $this->getEmail(),
            $keys[7] => $this->getMobile(),
            $keys[8] => $this->getIsOverviewRecipient(),
            $keys[9] => $this->getRecieveReminderEmails(),
            $keys[10] => $this->getIsBandAdmin(),
            $keys[11] => $this->getIsEventEditor(),
            $keys[12] => $this->getLastLogin(),
            $keys[13] => $this->getPasswordChanged(),
            $keys[14] => $this->getCreated(),
            $keys[15] => $this->getUpdated(),
        );
        if ($result[$keys[12]] instanceof \DateTimeInterface) {
            $result[$keys[12]] = $result[$keys[12]]->format('c');
        }

        if ($result[$keys[13]] instanceof \DateTimeInterface) {
            $result[$keys[13]] = $result[$keys[13]]->format('c');
        }

        if ($result[$keys[14]] instanceof \DateTimeInterface) {
            $result[$keys[14]] = $result[$keys[14]]->format('c');
        }

        if ($result[$keys[15]] instanceof \DateTimeInterface) {
            $result[$keys[15]] = $result[$keys[15]]->format('c');
        }

        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->collCalendarTokens) {
                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'calendarTokens';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'cr_calendarTokenss';
                        break;
                    default:
                        $key = 'CalendarTokens';
                }

                $result[$key] = $this->collCalendarTokens->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collEvents) {
                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'events';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'cr_eventss';
                        break;
                    default:
                        $key = 'Events';
                }

                $result[$key] = $this->collEvents->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collUnavailables) {
                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'unavailables';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'cr_unavailables';
                        break;
                    default:
                        $key = 'Unavailables';
                }

                $result[$key] = $this->collUnavailables->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collNotifications) {
                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'notifications';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'cr_notificationss';
                        break;
                    default:
                        $key = 'Notifications';
                }

                $result[$key] = $this->collNotifications->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collSocialAuths) {
                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'socialAuths';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'cr_socialAuths';
                        break;
                    default:
                        $key = 'SocialAuths';
                }

                $result[$key] = $this->collSocialAuths->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collStatistics) {
                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'statistics';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'cr_statisticss';
                        break;
                    default:
                        $key = 'Statistics';
                }

                $result[$key] = $this->collStatistics->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collSwaps) {
                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'swaps';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'cr_swapss';
                        break;
                    default:
                        $key = 'Swaps';
                }

                $result[$key] = $this->collSwaps->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collUserRoles) {
                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'userRoles';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'cr_userRoless';
                        break;
                    default:
                        $key = 'UserRoles';
                }

                $result[$key] = $this->collUserRoles->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collUserPermissions) {
                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'userPermissions';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'cr_userPermissionss';
                        break;
                    default:
                        $key = 'UserPermissions';
                }

                $result[$key] = $this->collUserPermissions->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
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
     * @return $this|\TechWilk\Rota\User
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = UserTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\TechWilk\Rota\User
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setFirstName($value);
                break;
            case 2:
                $this->setLastName($value);
                break;
            case 3:
                $this->setUsername($value);
                break;
            case 4:
                $this->setPassword($value);
                break;
            case 5:
                $this->setIsAdmin($value);
                break;
            case 6:
                $this->setEmail($value);
                break;
            case 7:
                $this->setMobile($value);
                break;
            case 8:
                $this->setIsOverviewRecipient($value);
                break;
            case 9:
                $this->setRecieveReminderEmails($value);
                break;
            case 10:
                $this->setIsBandAdmin($value);
                break;
            case 11:
                $this->setIsEventEditor($value);
                break;
            case 12:
                $this->setLastLogin($value);
                break;
            case 13:
                $this->setPasswordChanged($value);
                break;
            case 14:
                $this->setCreated($value);
                break;
            case 15:
                $this->setUpdated($value);
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
        $keys = UserTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setFirstName($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setLastName($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setUsername($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setPassword($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setIsAdmin($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setEmail($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setMobile($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->setIsOverviewRecipient($arr[$keys[8]]);
        }
        if (array_key_exists($keys[9], $arr)) {
            $this->setRecieveReminderEmails($arr[$keys[9]]);
        }
        if (array_key_exists($keys[10], $arr)) {
            $this->setIsBandAdmin($arr[$keys[10]]);
        }
        if (array_key_exists($keys[11], $arr)) {
            $this->setIsEventEditor($arr[$keys[11]]);
        }
        if (array_key_exists($keys[12], $arr)) {
            $this->setLastLogin($arr[$keys[12]]);
        }
        if (array_key_exists($keys[13], $arr)) {
            $this->setPasswordChanged($arr[$keys[13]]);
        }
        if (array_key_exists($keys[14], $arr)) {
            $this->setCreated($arr[$keys[14]]);
        }
        if (array_key_exists($keys[15], $arr)) {
            $this->setUpdated($arr[$keys[15]]);
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
     * @return $this|\TechWilk\Rota\User The current object, for fluid interface
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
        $criteria = new Criteria(UserTableMap::DATABASE_NAME);

        if ($this->isColumnModified(UserTableMap::COL_ID)) {
            $criteria->add(UserTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(UserTableMap::COL_FIRSTNAME)) {
            $criteria->add(UserTableMap::COL_FIRSTNAME, $this->firstname);
        }
        if ($this->isColumnModified(UserTableMap::COL_LASTNAME)) {
            $criteria->add(UserTableMap::COL_LASTNAME, $this->lastname);
        }
        if ($this->isColumnModified(UserTableMap::COL_USERNAME)) {
            $criteria->add(UserTableMap::COL_USERNAME, $this->username);
        }
        if ($this->isColumnModified(UserTableMap::COL_PASSWORD)) {
            $criteria->add(UserTableMap::COL_PASSWORD, $this->password);
        }
        if ($this->isColumnModified(UserTableMap::COL_ISADMIN)) {
            $criteria->add(UserTableMap::COL_ISADMIN, $this->isadmin);
        }
        if ($this->isColumnModified(UserTableMap::COL_EMAIL)) {
            $criteria->add(UserTableMap::COL_EMAIL, $this->email);
        }
        if ($this->isColumnModified(UserTableMap::COL_MOBILE)) {
            $criteria->add(UserTableMap::COL_MOBILE, $this->mobile);
        }
        if ($this->isColumnModified(UserTableMap::COL_ISOVERVIEWRECIPIENT)) {
            $criteria->add(UserTableMap::COL_ISOVERVIEWRECIPIENT, $this->isoverviewrecipient);
        }
        if ($this->isColumnModified(UserTableMap::COL_RECIEVEREMINDEREMAILS)) {
            $criteria->add(UserTableMap::COL_RECIEVEREMINDEREMAILS, $this->recievereminderemails);
        }
        if ($this->isColumnModified(UserTableMap::COL_ISBANDADMIN)) {
            $criteria->add(UserTableMap::COL_ISBANDADMIN, $this->isbandadmin);
        }
        if ($this->isColumnModified(UserTableMap::COL_ISEVENTEDITOR)) {
            $criteria->add(UserTableMap::COL_ISEVENTEDITOR, $this->iseventeditor);
        }
        if ($this->isColumnModified(UserTableMap::COL_LASTLOGIN)) {
            $criteria->add(UserTableMap::COL_LASTLOGIN, $this->lastlogin);
        }
        if ($this->isColumnModified(UserTableMap::COL_PASSWORDCHANGED)) {
            $criteria->add(UserTableMap::COL_PASSWORDCHANGED, $this->passwordchanged);
        }
        if ($this->isColumnModified(UserTableMap::COL_CREATED)) {
            $criteria->add(UserTableMap::COL_CREATED, $this->created);
        }
        if ($this->isColumnModified(UserTableMap::COL_UPDATED)) {
            $criteria->add(UserTableMap::COL_UPDATED, $this->updated);
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
        $criteria = ChildUserQuery::create();
        $criteria->add(UserTableMap::COL_ID, $this->id);

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
        $validPk = null !== $this->getId();

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
     * Returns the primary key for this object (row).
     * @return int
     */
    public function getPrimaryKey()
    {
        return $this->getId();
    }

    /**
     * Generic method to set the primary key (id column).
     *
     * @param       int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setId($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {
        return null === $this->getId();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of \TechWilk\Rota\User (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setFirstName($this->getFirstName());
        $copyObj->setLastName($this->getLastName());
        $copyObj->setUsername($this->getUsername());
        $copyObj->setPassword($this->getPassword());
        $copyObj->setIsAdmin($this->getIsAdmin());
        $copyObj->setEmail($this->getEmail());
        $copyObj->setMobile($this->getMobile());
        $copyObj->setIsOverviewRecipient($this->getIsOverviewRecipient());
        $copyObj->setRecieveReminderEmails($this->getRecieveReminderEmails());
        $copyObj->setIsBandAdmin($this->getIsBandAdmin());
        $copyObj->setIsEventEditor($this->getIsEventEditor());
        $copyObj->setLastLogin($this->getLastLogin());
        $copyObj->setPasswordChanged($this->getPasswordChanged());
        $copyObj->setCreated($this->getCreated());
        $copyObj->setUpdated($this->getUpdated());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getCalendarTokens() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addCalendarToken($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getEvents() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addEvent($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getUnavailables() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addUnavailable($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getNotifications() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addNotification($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getSocialAuths() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addSocialAuth($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getStatistics() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addStatistic($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getSwaps() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addSwap($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getUserRoles() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addUserRole($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getUserPermissions() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addUserPermission($relObj->copy($deepCopy));
                }
            }
        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setId(null); // this is a auto-increment column, so set to default value
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
     * @return \TechWilk\Rota\User Clone of current object.
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
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param      string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('CalendarToken' == $relationName) {
            $this->initCalendarTokens();
            return;
        }
        if ('Event' == $relationName) {
            $this->initEvents();
            return;
        }
        if ('Unavailable' == $relationName) {
            $this->initUnavailables();
            return;
        }
        if ('Notification' == $relationName) {
            $this->initNotifications();
            return;
        }
        if ('SocialAuth' == $relationName) {
            $this->initSocialAuths();
            return;
        }
        if ('Statistic' == $relationName) {
            $this->initStatistics();
            return;
        }
        if ('Swap' == $relationName) {
            $this->initSwaps();
            return;
        }
        if ('UserRole' == $relationName) {
            $this->initUserRoles();
            return;
        }
        if ('UserPermission' == $relationName) {
            $this->initUserPermissions();
            return;
        }
    }

    /**
     * Clears out the collCalendarTokens collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addCalendarTokens()
     */
    public function clearCalendarTokens()
    {
        $this->collCalendarTokens = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collCalendarTokens collection loaded partially.
     */
    public function resetPartialCalendarTokens($v = true)
    {
        $this->collCalendarTokensPartial = $v;
    }

    /**
     * Initializes the collCalendarTokens collection.
     *
     * By default this just sets the collCalendarTokens collection to an empty array (like clearcollCalendarTokens());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initCalendarTokens($overrideExisting = true)
    {
        if (null !== $this->collCalendarTokens && !$overrideExisting) {
            return;
        }

        $collectionClassName = CalendarTokenTableMap::getTableMap()->getCollectionClassName();

        $this->collCalendarTokens = new $collectionClassName;
        $this->collCalendarTokens->setModel('\TechWilk\Rota\CalendarToken');
    }

    /**
     * Gets an array of ChildCalendarToken objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildCalendarToken[] List of ChildCalendarToken objects
     * @throws PropelException
     */
    public function getCalendarTokens(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collCalendarTokensPartial && !$this->isNew();
        if (null === $this->collCalendarTokens || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collCalendarTokens) {
                // return empty collection
                $this->initCalendarTokens();
            } else {
                $collCalendarTokens = ChildCalendarTokenQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collCalendarTokensPartial && count($collCalendarTokens)) {
                        $this->initCalendarTokens(false);

                        foreach ($collCalendarTokens as $obj) {
                            if (false == $this->collCalendarTokens->contains($obj)) {
                                $this->collCalendarTokens->append($obj);
                            }
                        }

                        $this->collCalendarTokensPartial = true;
                    }

                    return $collCalendarTokens;
                }

                if ($partial && $this->collCalendarTokens) {
                    foreach ($this->collCalendarTokens as $obj) {
                        if ($obj->isNew()) {
                            $collCalendarTokens[] = $obj;
                        }
                    }
                }

                $this->collCalendarTokens = $collCalendarTokens;
                $this->collCalendarTokensPartial = false;
            }
        }

        return $this->collCalendarTokens;
    }

    /**
     * Sets a collection of ChildCalendarToken objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $calendarTokens A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function setCalendarTokens(Collection $calendarTokens, ConnectionInterface $con = null)
    {
        /** @var ChildCalendarToken[] $calendarTokensToDelete */
        $calendarTokensToDelete = $this->getCalendarTokens(new Criteria(), $con)->diff($calendarTokens);


        $this->calendarTokensScheduledForDeletion = $calendarTokensToDelete;

        foreach ($calendarTokensToDelete as $calendarTokenRemoved) {
            $calendarTokenRemoved->setUser(null);
        }

        $this->collCalendarTokens = null;
        foreach ($calendarTokens as $calendarToken) {
            $this->addCalendarToken($calendarToken);
        }

        $this->collCalendarTokens = $calendarTokens;
        $this->collCalendarTokensPartial = false;

        return $this;
    }

    /**
     * Returns the number of related CalendarToken objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related CalendarToken objects.
     * @throws PropelException
     */
    public function countCalendarTokens(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collCalendarTokensPartial && !$this->isNew();
        if (null === $this->collCalendarTokens || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collCalendarTokens) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getCalendarTokens());
            }

            $query = ChildCalendarTokenQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collCalendarTokens);
    }

    /**
     * Method called to associate a ChildCalendarToken object to this object
     * through the ChildCalendarToken foreign key attribute.
     *
     * @param  ChildCalendarToken $l ChildCalendarToken
     * @return $this|\TechWilk\Rota\User The current object (for fluent API support)
     */
    public function addCalendarToken(ChildCalendarToken $l)
    {
        if ($this->collCalendarTokens === null) {
            $this->initCalendarTokens();
            $this->collCalendarTokensPartial = true;
        }

        if (!$this->collCalendarTokens->contains($l)) {
            $this->doAddCalendarToken($l);

            if ($this->calendarTokensScheduledForDeletion and $this->calendarTokensScheduledForDeletion->contains($l)) {
                $this->calendarTokensScheduledForDeletion->remove($this->calendarTokensScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildCalendarToken $calendarToken The ChildCalendarToken object to add.
     */
    protected function doAddCalendarToken(ChildCalendarToken $calendarToken)
    {
        $this->collCalendarTokens[]= $calendarToken;
        $calendarToken->setUser($this);
    }

    /**
     * @param  ChildCalendarToken $calendarToken The ChildCalendarToken object to remove.
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function removeCalendarToken(ChildCalendarToken $calendarToken)
    {
        if ($this->getCalendarTokens()->contains($calendarToken)) {
            $pos = $this->collCalendarTokens->search($calendarToken);
            $this->collCalendarTokens->remove($pos);
            if (null === $this->calendarTokensScheduledForDeletion) {
                $this->calendarTokensScheduledForDeletion = clone $this->collCalendarTokens;
                $this->calendarTokensScheduledForDeletion->clear();
            }
            $this->calendarTokensScheduledForDeletion[]= clone $calendarToken;
            $calendarToken->setUser(null);
        }

        return $this;
    }

    /**
     * Clears out the collEvents collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addEvents()
     */
    public function clearEvents()
    {
        $this->collEvents = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collEvents collection loaded partially.
     */
    public function resetPartialEvents($v = true)
    {
        $this->collEventsPartial = $v;
    }

    /**
     * Initializes the collEvents collection.
     *
     * By default this just sets the collEvents collection to an empty array (like clearcollEvents());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initEvents($overrideExisting = true)
    {
        if (null !== $this->collEvents && !$overrideExisting) {
            return;
        }

        $collectionClassName = EventTableMap::getTableMap()->getCollectionClassName();

        $this->collEvents = new $collectionClassName;
        $this->collEvents->setModel('\TechWilk\Rota\Event');
    }

    /**
     * Gets an array of ChildEvent objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildEvent[] List of ChildEvent objects
     * @throws PropelException
     */
    public function getEvents(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collEventsPartial && !$this->isNew();
        if (null === $this->collEvents || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collEvents) {
                // return empty collection
                $this->initEvents();
            } else {
                $collEvents = ChildEventQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collEventsPartial && count($collEvents)) {
                        $this->initEvents(false);

                        foreach ($collEvents as $obj) {
                            if (false == $this->collEvents->contains($obj)) {
                                $this->collEvents->append($obj);
                            }
                        }

                        $this->collEventsPartial = true;
                    }

                    return $collEvents;
                }

                if ($partial && $this->collEvents) {
                    foreach ($this->collEvents as $obj) {
                        if ($obj->isNew()) {
                            $collEvents[] = $obj;
                        }
                    }
                }

                $this->collEvents = $collEvents;
                $this->collEventsPartial = false;
            }
        }

        return $this->collEvents;
    }

    /**
     * Sets a collection of ChildEvent objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $events A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function setEvents(Collection $events, ConnectionInterface $con = null)
    {
        /** @var ChildEvent[] $eventsToDelete */
        $eventsToDelete = $this->getEvents(new Criteria(), $con)->diff($events);


        $this->eventsScheduledForDeletion = $eventsToDelete;

        foreach ($eventsToDelete as $eventRemoved) {
            $eventRemoved->setUser(null);
        }

        $this->collEvents = null;
        foreach ($events as $event) {
            $this->addEvent($event);
        }

        $this->collEvents = $events;
        $this->collEventsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Event objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Event objects.
     * @throws PropelException
     */
    public function countEvents(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collEventsPartial && !$this->isNew();
        if (null === $this->collEvents || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collEvents) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getEvents());
            }

            $query = ChildEventQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collEvents);
    }

    /**
     * Method called to associate a ChildEvent object to this object
     * through the ChildEvent foreign key attribute.
     *
     * @param  ChildEvent $l ChildEvent
     * @return $this|\TechWilk\Rota\User The current object (for fluent API support)
     */
    public function addEvent(ChildEvent $l)
    {
        if ($this->collEvents === null) {
            $this->initEvents();
            $this->collEventsPartial = true;
        }

        if (!$this->collEvents->contains($l)) {
            $this->doAddEvent($l);

            if ($this->eventsScheduledForDeletion and $this->eventsScheduledForDeletion->contains($l)) {
                $this->eventsScheduledForDeletion->remove($this->eventsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildEvent $event The ChildEvent object to add.
     */
    protected function doAddEvent(ChildEvent $event)
    {
        $this->collEvents[]= $event;
        $event->setUser($this);
    }

    /**
     * @param  ChildEvent $event The ChildEvent object to remove.
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function removeEvent(ChildEvent $event)
    {
        if ($this->getEvents()->contains($event)) {
            $pos = $this->collEvents->search($event);
            $this->collEvents->remove($pos);
            if (null === $this->eventsScheduledForDeletion) {
                $this->eventsScheduledForDeletion = clone $this->collEvents;
                $this->eventsScheduledForDeletion->clear();
            }
            $this->eventsScheduledForDeletion[]= clone $event;
            $event->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related Events from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildEvent[] List of ChildEvent objects
     */
    public function getEventsJoinEventType(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildEventQuery::create(null, $criteria);
        $query->joinWith('EventType', $joinBehavior);

        return $this->getEvents($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related Events from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildEvent[] List of ChildEvent objects
     */
    public function getEventsJoinEventSubType(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildEventQuery::create(null, $criteria);
        $query->joinWith('EventSubType', $joinBehavior);

        return $this->getEvents($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related Events from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildEvent[] List of ChildEvent objects
     */
    public function getEventsJoinLocation(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildEventQuery::create(null, $criteria);
        $query->joinWith('Location', $joinBehavior);

        return $this->getEvents($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related Events from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildEvent[] List of ChildEvent objects
     */
    public function getEventsJoinEventGroup(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildEventQuery::create(null, $criteria);
        $query->joinWith('EventGroup', $joinBehavior);

        return $this->getEvents($query, $con);
    }

    /**
     * Clears out the collUnavailables collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addUnavailables()
     */
    public function clearUnavailables()
    {
        $this->collUnavailables = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collUnavailables collection loaded partially.
     */
    public function resetPartialUnavailables($v = true)
    {
        $this->collUnavailablesPartial = $v;
    }

    /**
     * Initializes the collUnavailables collection.
     *
     * By default this just sets the collUnavailables collection to an empty array (like clearcollUnavailables());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initUnavailables($overrideExisting = true)
    {
        if (null !== $this->collUnavailables && !$overrideExisting) {
            return;
        }

        $collectionClassName = UnavailableTableMap::getTableMap()->getCollectionClassName();

        $this->collUnavailables = new $collectionClassName;
        $this->collUnavailables->setModel('\TechWilk\Rota\Unavailable');
    }

    /**
     * Gets an array of ChildUnavailable objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildUnavailable[] List of ChildUnavailable objects
     * @throws PropelException
     */
    public function getUnavailables(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collUnavailablesPartial && !$this->isNew();
        if (null === $this->collUnavailables || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collUnavailables) {
                // return empty collection
                $this->initUnavailables();
            } else {
                $collUnavailables = ChildUnavailableQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collUnavailablesPartial && count($collUnavailables)) {
                        $this->initUnavailables(false);

                        foreach ($collUnavailables as $obj) {
                            if (false == $this->collUnavailables->contains($obj)) {
                                $this->collUnavailables->append($obj);
                            }
                        }

                        $this->collUnavailablesPartial = true;
                    }

                    return $collUnavailables;
                }

                if ($partial && $this->collUnavailables) {
                    foreach ($this->collUnavailables as $obj) {
                        if ($obj->isNew()) {
                            $collUnavailables[] = $obj;
                        }
                    }
                }

                $this->collUnavailables = $collUnavailables;
                $this->collUnavailablesPartial = false;
            }
        }

        return $this->collUnavailables;
    }

    /**
     * Sets a collection of ChildUnavailable objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $unavailables A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function setUnavailables(Collection $unavailables, ConnectionInterface $con = null)
    {
        /** @var ChildUnavailable[] $unavailablesToDelete */
        $unavailablesToDelete = $this->getUnavailables(new Criteria(), $con)->diff($unavailables);


        $this->unavailablesScheduledForDeletion = $unavailablesToDelete;

        foreach ($unavailablesToDelete as $unavailableRemoved) {
            $unavailableRemoved->setUser(null);
        }

        $this->collUnavailables = null;
        foreach ($unavailables as $unavailable) {
            $this->addUnavailable($unavailable);
        }

        $this->collUnavailables = $unavailables;
        $this->collUnavailablesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Unavailable objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Unavailable objects.
     * @throws PropelException
     */
    public function countUnavailables(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collUnavailablesPartial && !$this->isNew();
        if (null === $this->collUnavailables || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collUnavailables) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getUnavailables());
            }

            $query = ChildUnavailableQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collUnavailables);
    }

    /**
     * Method called to associate a ChildUnavailable object to this object
     * through the ChildUnavailable foreign key attribute.
     *
     * @param  ChildUnavailable $l ChildUnavailable
     * @return $this|\TechWilk\Rota\User The current object (for fluent API support)
     */
    public function addUnavailable(ChildUnavailable $l)
    {
        if ($this->collUnavailables === null) {
            $this->initUnavailables();
            $this->collUnavailablesPartial = true;
        }

        if (!$this->collUnavailables->contains($l)) {
            $this->doAddUnavailable($l);

            if ($this->unavailablesScheduledForDeletion and $this->unavailablesScheduledForDeletion->contains($l)) {
                $this->unavailablesScheduledForDeletion->remove($this->unavailablesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildUnavailable $unavailable The ChildUnavailable object to add.
     */
    protected function doAddUnavailable(ChildUnavailable $unavailable)
    {
        $this->collUnavailables[]= $unavailable;
        $unavailable->setUser($this);
    }

    /**
     * @param  ChildUnavailable $unavailable The ChildUnavailable object to remove.
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function removeUnavailable(ChildUnavailable $unavailable)
    {
        if ($this->getUnavailables()->contains($unavailable)) {
            $pos = $this->collUnavailables->search($unavailable);
            $this->collUnavailables->remove($pos);
            if (null === $this->unavailablesScheduledForDeletion) {
                $this->unavailablesScheduledForDeletion = clone $this->collUnavailables;
                $this->unavailablesScheduledForDeletion->clear();
            }
            $this->unavailablesScheduledForDeletion[]= clone $unavailable;
            $unavailable->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related Unavailables from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildUnavailable[] List of ChildUnavailable objects
     */
    public function getUnavailablesJoinEvent(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildUnavailableQuery::create(null, $criteria);
        $query->joinWith('Event', $joinBehavior);

        return $this->getUnavailables($query, $con);
    }

    /**
     * Clears out the collNotifications collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addNotifications()
     */
    public function clearNotifications()
    {
        $this->collNotifications = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collNotifications collection loaded partially.
     */
    public function resetPartialNotifications($v = true)
    {
        $this->collNotificationsPartial = $v;
    }

    /**
     * Initializes the collNotifications collection.
     *
     * By default this just sets the collNotifications collection to an empty array (like clearcollNotifications());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initNotifications($overrideExisting = true)
    {
        if (null !== $this->collNotifications && !$overrideExisting) {
            return;
        }

        $collectionClassName = NotificationTableMap::getTableMap()->getCollectionClassName();

        $this->collNotifications = new $collectionClassName;
        $this->collNotifications->setModel('\TechWilk\Rota\Notification');
    }

    /**
     * Gets an array of ChildNotification objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildNotification[] List of ChildNotification objects
     * @throws PropelException
     */
    public function getNotifications(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collNotificationsPartial && !$this->isNew();
        if (null === $this->collNotifications || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collNotifications) {
                // return empty collection
                $this->initNotifications();
            } else {
                $collNotifications = ChildNotificationQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collNotificationsPartial && count($collNotifications)) {
                        $this->initNotifications(false);

                        foreach ($collNotifications as $obj) {
                            if (false == $this->collNotifications->contains($obj)) {
                                $this->collNotifications->append($obj);
                            }
                        }

                        $this->collNotificationsPartial = true;
                    }

                    return $collNotifications;
                }

                if ($partial && $this->collNotifications) {
                    foreach ($this->collNotifications as $obj) {
                        if ($obj->isNew()) {
                            $collNotifications[] = $obj;
                        }
                    }
                }

                $this->collNotifications = $collNotifications;
                $this->collNotificationsPartial = false;
            }
        }

        return $this->collNotifications;
    }

    /**
     * Sets a collection of ChildNotification objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $notifications A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function setNotifications(Collection $notifications, ConnectionInterface $con = null)
    {
        /** @var ChildNotification[] $notificationsToDelete */
        $notificationsToDelete = $this->getNotifications(new Criteria(), $con)->diff($notifications);


        $this->notificationsScheduledForDeletion = $notificationsToDelete;

        foreach ($notificationsToDelete as $notificationRemoved) {
            $notificationRemoved->setUser(null);
        }

        $this->collNotifications = null;
        foreach ($notifications as $notification) {
            $this->addNotification($notification);
        }

        $this->collNotifications = $notifications;
        $this->collNotificationsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Notification objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Notification objects.
     * @throws PropelException
     */
    public function countNotifications(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collNotificationsPartial && !$this->isNew();
        if (null === $this->collNotifications || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collNotifications) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getNotifications());
            }

            $query = ChildNotificationQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collNotifications);
    }

    /**
     * Method called to associate a ChildNotification object to this object
     * through the ChildNotification foreign key attribute.
     *
     * @param  ChildNotification $l ChildNotification
     * @return $this|\TechWilk\Rota\User The current object (for fluent API support)
     */
    public function addNotification(ChildNotification $l)
    {
        if ($this->collNotifications === null) {
            $this->initNotifications();
            $this->collNotificationsPartial = true;
        }

        if (!$this->collNotifications->contains($l)) {
            $this->doAddNotification($l);

            if ($this->notificationsScheduledForDeletion and $this->notificationsScheduledForDeletion->contains($l)) {
                $this->notificationsScheduledForDeletion->remove($this->notificationsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildNotification $notification The ChildNotification object to add.
     */
    protected function doAddNotification(ChildNotification $notification)
    {
        $this->collNotifications[]= $notification;
        $notification->setUser($this);
    }

    /**
     * @param  ChildNotification $notification The ChildNotification object to remove.
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function removeNotification(ChildNotification $notification)
    {
        if ($this->getNotifications()->contains($notification)) {
            $pos = $this->collNotifications->search($notification);
            $this->collNotifications->remove($pos);
            if (null === $this->notificationsScheduledForDeletion) {
                $this->notificationsScheduledForDeletion = clone $this->collNotifications;
                $this->notificationsScheduledForDeletion->clear();
            }
            $this->notificationsScheduledForDeletion[]= clone $notification;
            $notification->setUser(null);
        }

        return $this;
    }

    /**
     * Clears out the collSocialAuths collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addSocialAuths()
     */
    public function clearSocialAuths()
    {
        $this->collSocialAuths = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collSocialAuths collection loaded partially.
     */
    public function resetPartialSocialAuths($v = true)
    {
        $this->collSocialAuthsPartial = $v;
    }

    /**
     * Initializes the collSocialAuths collection.
     *
     * By default this just sets the collSocialAuths collection to an empty array (like clearcollSocialAuths());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initSocialAuths($overrideExisting = true)
    {
        if (null !== $this->collSocialAuths && !$overrideExisting) {
            return;
        }

        $collectionClassName = SocialAuthTableMap::getTableMap()->getCollectionClassName();

        $this->collSocialAuths = new $collectionClassName;
        $this->collSocialAuths->setModel('\TechWilk\Rota\SocialAuth');
    }

    /**
     * Gets an array of ChildSocialAuth objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildSocialAuth[] List of ChildSocialAuth objects
     * @throws PropelException
     */
    public function getSocialAuths(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collSocialAuthsPartial && !$this->isNew();
        if (null === $this->collSocialAuths || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collSocialAuths) {
                // return empty collection
                $this->initSocialAuths();
            } else {
                $collSocialAuths = ChildSocialAuthQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collSocialAuthsPartial && count($collSocialAuths)) {
                        $this->initSocialAuths(false);

                        foreach ($collSocialAuths as $obj) {
                            if (false == $this->collSocialAuths->contains($obj)) {
                                $this->collSocialAuths->append($obj);
                            }
                        }

                        $this->collSocialAuthsPartial = true;
                    }

                    return $collSocialAuths;
                }

                if ($partial && $this->collSocialAuths) {
                    foreach ($this->collSocialAuths as $obj) {
                        if ($obj->isNew()) {
                            $collSocialAuths[] = $obj;
                        }
                    }
                }

                $this->collSocialAuths = $collSocialAuths;
                $this->collSocialAuthsPartial = false;
            }
        }

        return $this->collSocialAuths;
    }

    /**
     * Sets a collection of ChildSocialAuth objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $socialAuths A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function setSocialAuths(Collection $socialAuths, ConnectionInterface $con = null)
    {
        /** @var ChildSocialAuth[] $socialAuthsToDelete */
        $socialAuthsToDelete = $this->getSocialAuths(new Criteria(), $con)->diff($socialAuths);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->socialAuthsScheduledForDeletion = clone $socialAuthsToDelete;

        foreach ($socialAuthsToDelete as $socialAuthRemoved) {
            $socialAuthRemoved->setUser(null);
        }

        $this->collSocialAuths = null;
        foreach ($socialAuths as $socialAuth) {
            $this->addSocialAuth($socialAuth);
        }

        $this->collSocialAuths = $socialAuths;
        $this->collSocialAuthsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related SocialAuth objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related SocialAuth objects.
     * @throws PropelException
     */
    public function countSocialAuths(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collSocialAuthsPartial && !$this->isNew();
        if (null === $this->collSocialAuths || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collSocialAuths) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getSocialAuths());
            }

            $query = ChildSocialAuthQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collSocialAuths);
    }

    /**
     * Method called to associate a ChildSocialAuth object to this object
     * through the ChildSocialAuth foreign key attribute.
     *
     * @param  ChildSocialAuth $l ChildSocialAuth
     * @return $this|\TechWilk\Rota\User The current object (for fluent API support)
     */
    public function addSocialAuth(ChildSocialAuth $l)
    {
        if ($this->collSocialAuths === null) {
            $this->initSocialAuths();
            $this->collSocialAuthsPartial = true;
        }

        if (!$this->collSocialAuths->contains($l)) {
            $this->doAddSocialAuth($l);

            if ($this->socialAuthsScheduledForDeletion and $this->socialAuthsScheduledForDeletion->contains($l)) {
                $this->socialAuthsScheduledForDeletion->remove($this->socialAuthsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildSocialAuth $socialAuth The ChildSocialAuth object to add.
     */
    protected function doAddSocialAuth(ChildSocialAuth $socialAuth)
    {
        $this->collSocialAuths[]= $socialAuth;
        $socialAuth->setUser($this);
    }

    /**
     * @param  ChildSocialAuth $socialAuth The ChildSocialAuth object to remove.
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function removeSocialAuth(ChildSocialAuth $socialAuth)
    {
        if ($this->getSocialAuths()->contains($socialAuth)) {
            $pos = $this->collSocialAuths->search($socialAuth);
            $this->collSocialAuths->remove($pos);
            if (null === $this->socialAuthsScheduledForDeletion) {
                $this->socialAuthsScheduledForDeletion = clone $this->collSocialAuths;
                $this->socialAuthsScheduledForDeletion->clear();
            }
            $this->socialAuthsScheduledForDeletion[]= clone $socialAuth;
            $socialAuth->setUser(null);
        }

        return $this;
    }

    /**
     * Clears out the collStatistics collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addStatistics()
     */
    public function clearStatistics()
    {
        $this->collStatistics = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collStatistics collection loaded partially.
     */
    public function resetPartialStatistics($v = true)
    {
        $this->collStatisticsPartial = $v;
    }

    /**
     * Initializes the collStatistics collection.
     *
     * By default this just sets the collStatistics collection to an empty array (like clearcollStatistics());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initStatistics($overrideExisting = true)
    {
        if (null !== $this->collStatistics && !$overrideExisting) {
            return;
        }

        $collectionClassName = StatisticTableMap::getTableMap()->getCollectionClassName();

        $this->collStatistics = new $collectionClassName;
        $this->collStatistics->setModel('\TechWilk\Rota\Statistic');
    }

    /**
     * Gets an array of ChildStatistic objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildStatistic[] List of ChildStatistic objects
     * @throws PropelException
     */
    public function getStatistics(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collStatisticsPartial && !$this->isNew();
        if (null === $this->collStatistics || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collStatistics) {
                // return empty collection
                $this->initStatistics();
            } else {
                $collStatistics = ChildStatisticQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collStatisticsPartial && count($collStatistics)) {
                        $this->initStatistics(false);

                        foreach ($collStatistics as $obj) {
                            if (false == $this->collStatistics->contains($obj)) {
                                $this->collStatistics->append($obj);
                            }
                        }

                        $this->collStatisticsPartial = true;
                    }

                    return $collStatistics;
                }

                if ($partial && $this->collStatistics) {
                    foreach ($this->collStatistics as $obj) {
                        if ($obj->isNew()) {
                            $collStatistics[] = $obj;
                        }
                    }
                }

                $this->collStatistics = $collStatistics;
                $this->collStatisticsPartial = false;
            }
        }

        return $this->collStatistics;
    }

    /**
     * Sets a collection of ChildStatistic objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $statistics A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function setStatistics(Collection $statistics, ConnectionInterface $con = null)
    {
        /** @var ChildStatistic[] $statisticsToDelete */
        $statisticsToDelete = $this->getStatistics(new Criteria(), $con)->diff($statistics);


        $this->statisticsScheduledForDeletion = $statisticsToDelete;

        foreach ($statisticsToDelete as $statisticRemoved) {
            $statisticRemoved->setUser(null);
        }

        $this->collStatistics = null;
        foreach ($statistics as $statistic) {
            $this->addStatistic($statistic);
        }

        $this->collStatistics = $statistics;
        $this->collStatisticsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Statistic objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Statistic objects.
     * @throws PropelException
     */
    public function countStatistics(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collStatisticsPartial && !$this->isNew();
        if (null === $this->collStatistics || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collStatistics) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getStatistics());
            }

            $query = ChildStatisticQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collStatistics);
    }

    /**
     * Method called to associate a ChildStatistic object to this object
     * through the ChildStatistic foreign key attribute.
     *
     * @param  ChildStatistic $l ChildStatistic
     * @return $this|\TechWilk\Rota\User The current object (for fluent API support)
     */
    public function addStatistic(ChildStatistic $l)
    {
        if ($this->collStatistics === null) {
            $this->initStatistics();
            $this->collStatisticsPartial = true;
        }

        if (!$this->collStatistics->contains($l)) {
            $this->doAddStatistic($l);

            if ($this->statisticsScheduledForDeletion and $this->statisticsScheduledForDeletion->contains($l)) {
                $this->statisticsScheduledForDeletion->remove($this->statisticsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildStatistic $statistic The ChildStatistic object to add.
     */
    protected function doAddStatistic(ChildStatistic $statistic)
    {
        $this->collStatistics[]= $statistic;
        $statistic->setUser($this);
    }

    /**
     * @param  ChildStatistic $statistic The ChildStatistic object to remove.
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function removeStatistic(ChildStatistic $statistic)
    {
        if ($this->getStatistics()->contains($statistic)) {
            $pos = $this->collStatistics->search($statistic);
            $this->collStatistics->remove($pos);
            if (null === $this->statisticsScheduledForDeletion) {
                $this->statisticsScheduledForDeletion = clone $this->collStatistics;
                $this->statisticsScheduledForDeletion->clear();
            }
            $this->statisticsScheduledForDeletion[]= $statistic;
            $statistic->setUser(null);
        }

        return $this;
    }

    /**
     * Clears out the collSwaps collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addSwaps()
     */
    public function clearSwaps()
    {
        $this->collSwaps = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collSwaps collection loaded partially.
     */
    public function resetPartialSwaps($v = true)
    {
        $this->collSwapsPartial = $v;
    }

    /**
     * Initializes the collSwaps collection.
     *
     * By default this just sets the collSwaps collection to an empty array (like clearcollSwaps());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initSwaps($overrideExisting = true)
    {
        if (null !== $this->collSwaps && !$overrideExisting) {
            return;
        }

        $collectionClassName = SwapTableMap::getTableMap()->getCollectionClassName();

        $this->collSwaps = new $collectionClassName;
        $this->collSwaps->setModel('\TechWilk\Rota\Swap');
    }

    /**
     * Gets an array of ChildSwap objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildSwap[] List of ChildSwap objects
     * @throws PropelException
     */
    public function getSwaps(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collSwapsPartial && !$this->isNew();
        if (null === $this->collSwaps || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collSwaps) {
                // return empty collection
                $this->initSwaps();
            } else {
                $collSwaps = ChildSwapQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collSwapsPartial && count($collSwaps)) {
                        $this->initSwaps(false);

                        foreach ($collSwaps as $obj) {
                            if (false == $this->collSwaps->contains($obj)) {
                                $this->collSwaps->append($obj);
                            }
                        }

                        $this->collSwapsPartial = true;
                    }

                    return $collSwaps;
                }

                if ($partial && $this->collSwaps) {
                    foreach ($this->collSwaps as $obj) {
                        if ($obj->isNew()) {
                            $collSwaps[] = $obj;
                        }
                    }
                }

                $this->collSwaps = $collSwaps;
                $this->collSwapsPartial = false;
            }
        }

        return $this->collSwaps;
    }

    /**
     * Sets a collection of ChildSwap objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $swaps A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function setSwaps(Collection $swaps, ConnectionInterface $con = null)
    {
        /** @var ChildSwap[] $swapsToDelete */
        $swapsToDelete = $this->getSwaps(new Criteria(), $con)->diff($swaps);


        $this->swapsScheduledForDeletion = $swapsToDelete;

        foreach ($swapsToDelete as $swapRemoved) {
            $swapRemoved->setUser(null);
        }

        $this->collSwaps = null;
        foreach ($swaps as $swap) {
            $this->addSwap($swap);
        }

        $this->collSwaps = $swaps;
        $this->collSwapsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Swap objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Swap objects.
     * @throws PropelException
     */
    public function countSwaps(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collSwapsPartial && !$this->isNew();
        if (null === $this->collSwaps || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collSwaps) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getSwaps());
            }

            $query = ChildSwapQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collSwaps);
    }

    /**
     * Method called to associate a ChildSwap object to this object
     * through the ChildSwap foreign key attribute.
     *
     * @param  ChildSwap $l ChildSwap
     * @return $this|\TechWilk\Rota\User The current object (for fluent API support)
     */
    public function addSwap(ChildSwap $l)
    {
        if ($this->collSwaps === null) {
            $this->initSwaps();
            $this->collSwapsPartial = true;
        }

        if (!$this->collSwaps->contains($l)) {
            $this->doAddSwap($l);

            if ($this->swapsScheduledForDeletion and $this->swapsScheduledForDeletion->contains($l)) {
                $this->swapsScheduledForDeletion->remove($this->swapsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildSwap $swap The ChildSwap object to add.
     */
    protected function doAddSwap(ChildSwap $swap)
    {
        $this->collSwaps[]= $swap;
        $swap->setUser($this);
    }

    /**
     * @param  ChildSwap $swap The ChildSwap object to remove.
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function removeSwap(ChildSwap $swap)
    {
        if ($this->getSwaps()->contains($swap)) {
            $pos = $this->collSwaps->search($swap);
            $this->collSwaps->remove($pos);
            if (null === $this->swapsScheduledForDeletion) {
                $this->swapsScheduledForDeletion = clone $this->collSwaps;
                $this->swapsScheduledForDeletion->clear();
            }
            $this->swapsScheduledForDeletion[]= clone $swap;
            $swap->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related Swaps from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildSwap[] List of ChildSwap objects
     */
    public function getSwapsJoinEventPerson(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildSwapQuery::create(null, $criteria);
        $query->joinWith('EventPerson', $joinBehavior);

        return $this->getSwaps($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related Swaps from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildSwap[] List of ChildSwap objects
     */
    public function getSwapsJoinOldUserRole(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildSwapQuery::create(null, $criteria);
        $query->joinWith('OldUserRole', $joinBehavior);

        return $this->getSwaps($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related Swaps from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildSwap[] List of ChildSwap objects
     */
    public function getSwapsJoinNewUserRole(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildSwapQuery::create(null, $criteria);
        $query->joinWith('NewUserRole', $joinBehavior);

        return $this->getSwaps($query, $con);
    }

    /**
     * Clears out the collUserRoles collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addUserRoles()
     */
    public function clearUserRoles()
    {
        $this->collUserRoles = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collUserRoles collection loaded partially.
     */
    public function resetPartialUserRoles($v = true)
    {
        $this->collUserRolesPartial = $v;
    }

    /**
     * Initializes the collUserRoles collection.
     *
     * By default this just sets the collUserRoles collection to an empty array (like clearcollUserRoles());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initUserRoles($overrideExisting = true)
    {
        if (null !== $this->collUserRoles && !$overrideExisting) {
            return;
        }

        $collectionClassName = UserRoleTableMap::getTableMap()->getCollectionClassName();

        $this->collUserRoles = new $collectionClassName;
        $this->collUserRoles->setModel('\TechWilk\Rota\UserRole');
    }

    /**
     * Gets an array of ChildUserRole objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildUserRole[] List of ChildUserRole objects
     * @throws PropelException
     */
    public function getUserRoles(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collUserRolesPartial && !$this->isNew();
        if (null === $this->collUserRoles || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collUserRoles) {
                // return empty collection
                $this->initUserRoles();
            } else {
                $collUserRoles = ChildUserRoleQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collUserRolesPartial && count($collUserRoles)) {
                        $this->initUserRoles(false);

                        foreach ($collUserRoles as $obj) {
                            if (false == $this->collUserRoles->contains($obj)) {
                                $this->collUserRoles->append($obj);
                            }
                        }

                        $this->collUserRolesPartial = true;
                    }

                    return $collUserRoles;
                }

                if ($partial && $this->collUserRoles) {
                    foreach ($this->collUserRoles as $obj) {
                        if ($obj->isNew()) {
                            $collUserRoles[] = $obj;
                        }
                    }
                }

                $this->collUserRoles = $collUserRoles;
                $this->collUserRolesPartial = false;
            }
        }

        return $this->collUserRoles;
    }

    /**
     * Sets a collection of ChildUserRole objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $userRoles A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function setUserRoles(Collection $userRoles, ConnectionInterface $con = null)
    {
        /** @var ChildUserRole[] $userRolesToDelete */
        $userRolesToDelete = $this->getUserRoles(new Criteria(), $con)->diff($userRoles);


        $this->userRolesScheduledForDeletion = $userRolesToDelete;

        foreach ($userRolesToDelete as $userRoleRemoved) {
            $userRoleRemoved->setUser(null);
        }

        $this->collUserRoles = null;
        foreach ($userRoles as $userRole) {
            $this->addUserRole($userRole);
        }

        $this->collUserRoles = $userRoles;
        $this->collUserRolesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related UserRole objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related UserRole objects.
     * @throws PropelException
     */
    public function countUserRoles(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collUserRolesPartial && !$this->isNew();
        if (null === $this->collUserRoles || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collUserRoles) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getUserRoles());
            }

            $query = ChildUserRoleQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collUserRoles);
    }

    /**
     * Method called to associate a ChildUserRole object to this object
     * through the ChildUserRole foreign key attribute.
     *
     * @param  ChildUserRole $l ChildUserRole
     * @return $this|\TechWilk\Rota\User The current object (for fluent API support)
     */
    public function addUserRole(ChildUserRole $l)
    {
        if ($this->collUserRoles === null) {
            $this->initUserRoles();
            $this->collUserRolesPartial = true;
        }

        if (!$this->collUserRoles->contains($l)) {
            $this->doAddUserRole($l);

            if ($this->userRolesScheduledForDeletion and $this->userRolesScheduledForDeletion->contains($l)) {
                $this->userRolesScheduledForDeletion->remove($this->userRolesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildUserRole $userRole The ChildUserRole object to add.
     */
    protected function doAddUserRole(ChildUserRole $userRole)
    {
        $this->collUserRoles[]= $userRole;
        $userRole->setUser($this);
    }

    /**
     * @param  ChildUserRole $userRole The ChildUserRole object to remove.
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function removeUserRole(ChildUserRole $userRole)
    {
        if ($this->getUserRoles()->contains($userRole)) {
            $pos = $this->collUserRoles->search($userRole);
            $this->collUserRoles->remove($pos);
            if (null === $this->userRolesScheduledForDeletion) {
                $this->userRolesScheduledForDeletion = clone $this->collUserRoles;
                $this->userRolesScheduledForDeletion->clear();
            }
            $this->userRolesScheduledForDeletion[]= clone $userRole;
            $userRole->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related UserRoles from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildUserRole[] List of ChildUserRole objects
     */
    public function getUserRolesJoinRole(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildUserRoleQuery::create(null, $criteria);
        $query->joinWith('Role', $joinBehavior);

        return $this->getUserRoles($query, $con);
    }

    /**
     * Clears out the collUserPermissions collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addUserPermissions()
     */
    public function clearUserPermissions()
    {
        $this->collUserPermissions = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collUserPermissions collection loaded partially.
     */
    public function resetPartialUserPermissions($v = true)
    {
        $this->collUserPermissionsPartial = $v;
    }

    /**
     * Initializes the collUserPermissions collection.
     *
     * By default this just sets the collUserPermissions collection to an empty array (like clearcollUserPermissions());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initUserPermissions($overrideExisting = true)
    {
        if (null !== $this->collUserPermissions && !$overrideExisting) {
            return;
        }

        $collectionClassName = UserPermissionTableMap::getTableMap()->getCollectionClassName();

        $this->collUserPermissions = new $collectionClassName;
        $this->collUserPermissions->setModel('\TechWilk\Rota\UserPermission');
    }

    /**
     * Gets an array of ChildUserPermission objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildUserPermission[] List of ChildUserPermission objects
     * @throws PropelException
     */
    public function getUserPermissions(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collUserPermissionsPartial && !$this->isNew();
        if (null === $this->collUserPermissions || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collUserPermissions) {
                // return empty collection
                $this->initUserPermissions();
            } else {
                $collUserPermissions = ChildUserPermissionQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collUserPermissionsPartial && count($collUserPermissions)) {
                        $this->initUserPermissions(false);

                        foreach ($collUserPermissions as $obj) {
                            if (false == $this->collUserPermissions->contains($obj)) {
                                $this->collUserPermissions->append($obj);
                            }
                        }

                        $this->collUserPermissionsPartial = true;
                    }

                    return $collUserPermissions;
                }

                if ($partial && $this->collUserPermissions) {
                    foreach ($this->collUserPermissions as $obj) {
                        if ($obj->isNew()) {
                            $collUserPermissions[] = $obj;
                        }
                    }
                }

                $this->collUserPermissions = $collUserPermissions;
                $this->collUserPermissionsPartial = false;
            }
        }

        return $this->collUserPermissions;
    }

    /**
     * Sets a collection of ChildUserPermission objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $userPermissions A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function setUserPermissions(Collection $userPermissions, ConnectionInterface $con = null)
    {
        /** @var ChildUserPermission[] $userPermissionsToDelete */
        $userPermissionsToDelete = $this->getUserPermissions(new Criteria(), $con)->diff($userPermissions);


        $this->userPermissionsScheduledForDeletion = $userPermissionsToDelete;

        foreach ($userPermissionsToDelete as $userPermissionRemoved) {
            $userPermissionRemoved->setUser(null);
        }

        $this->collUserPermissions = null;
        foreach ($userPermissions as $userPermission) {
            $this->addUserPermission($userPermission);
        }

        $this->collUserPermissions = $userPermissions;
        $this->collUserPermissionsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related UserPermission objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related UserPermission objects.
     * @throws PropelException
     */
    public function countUserPermissions(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collUserPermissionsPartial && !$this->isNew();
        if (null === $this->collUserPermissions || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collUserPermissions) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getUserPermissions());
            }

            $query = ChildUserPermissionQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collUserPermissions);
    }

    /**
     * Method called to associate a ChildUserPermission object to this object
     * through the ChildUserPermission foreign key attribute.
     *
     * @param  ChildUserPermission $l ChildUserPermission
     * @return $this|\TechWilk\Rota\User The current object (for fluent API support)
     */
    public function addUserPermission(ChildUserPermission $l)
    {
        if ($this->collUserPermissions === null) {
            $this->initUserPermissions();
            $this->collUserPermissionsPartial = true;
        }

        if (!$this->collUserPermissions->contains($l)) {
            $this->doAddUserPermission($l);

            if ($this->userPermissionsScheduledForDeletion and $this->userPermissionsScheduledForDeletion->contains($l)) {
                $this->userPermissionsScheduledForDeletion->remove($this->userPermissionsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildUserPermission $userPermission The ChildUserPermission object to add.
     */
    protected function doAddUserPermission(ChildUserPermission $userPermission)
    {
        $this->collUserPermissions[]= $userPermission;
        $userPermission->setUser($this);
    }

    /**
     * @param  ChildUserPermission $userPermission The ChildUserPermission object to remove.
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function removeUserPermission(ChildUserPermission $userPermission)
    {
        if ($this->getUserPermissions()->contains($userPermission)) {
            $pos = $this->collUserPermissions->search($userPermission);
            $this->collUserPermissions->remove($pos);
            if (null === $this->userPermissionsScheduledForDeletion) {
                $this->userPermissionsScheduledForDeletion = clone $this->collUserPermissions;
                $this->userPermissionsScheduledForDeletion->clear();
            }
            $this->userPermissionsScheduledForDeletion[]= clone $userPermission;
            $userPermission->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related UserPermissions from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildUserPermission[] List of ChildUserPermission objects
     */
    public function getUserPermissionsJoinPermission(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildUserPermissionQuery::create(null, $criteria);
        $query->joinWith('Permission', $joinBehavior);

        return $this->getUserPermissions($query, $con);
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        $this->id = null;
        $this->firstname = null;
        $this->lastname = null;
        $this->username = null;
        $this->password = null;
        $this->isadmin = null;
        $this->email = null;
        $this->mobile = null;
        $this->isoverviewrecipient = null;
        $this->recievereminderemails = null;
        $this->isbandadmin = null;
        $this->iseventeditor = null;
        $this->lastlogin = null;
        $this->passwordchanged = null;
        $this->created = null;
        $this->updated = null;
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
            if ($this->collCalendarTokens) {
                foreach ($this->collCalendarTokens as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collEvents) {
                foreach ($this->collEvents as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collUnavailables) {
                foreach ($this->collUnavailables as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collNotifications) {
                foreach ($this->collNotifications as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collSocialAuths) {
                foreach ($this->collSocialAuths as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collStatistics) {
                foreach ($this->collStatistics as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collSwaps) {
                foreach ($this->collSwaps as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collUserRoles) {
                foreach ($this->collUserRoles as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collUserPermissions) {
                foreach ($this->collUserPermissions as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collCalendarTokens = null;
        $this->collEvents = null;
        $this->collUnavailables = null;
        $this->collNotifications = null;
        $this->collSocialAuths = null;
        $this->collStatistics = null;
        $this->collSwaps = null;
        $this->collUserRoles = null;
        $this->collUserPermissions = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(UserTableMap::DEFAULT_STRING_FORMAT);
    }

    // timestampable behavior

    /**
     * Mark the current object so that the update date doesn't get updated during next save
     *
     * @return     $this|ChildUser The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[UserTableMap::COL_UPDATED] = true;

        return $this;
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
