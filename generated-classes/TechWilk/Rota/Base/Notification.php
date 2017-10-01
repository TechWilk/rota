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
use TechWilk\Rota\Notification as ChildNotification;
use TechWilk\Rota\NotificationClick as ChildNotificationClick;
use TechWilk\Rota\NotificationClickQuery as ChildNotificationClickQuery;
use TechWilk\Rota\NotificationQuery as ChildNotificationQuery;
use TechWilk\Rota\User as ChildUser;
use TechWilk\Rota\UserQuery as ChildUserQuery;
use TechWilk\Rota\Map\NotificationClickTableMap;
use TechWilk\Rota\Map\NotificationTableMap;

/**
 * Base class that represents a row from the 'cr_notifications' table.
 *
 *
 *
 * @package    propel.generator.TechWilk.Rota.Base
 */
abstract class Notification implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\TechWilk\\Rota\\Map\\NotificationTableMap';


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
     * The value for the timestamp field.
     *
     * Note: this column has a database default value of: (expression) CURRENT_TIMESTAMP
     * @var        DateTime
     */
    protected $timestamp;

    /**
     * The value for the userid field.
     *
     * @var        int
     */
    protected $userid;

    /**
     * The value for the summary field.
     *
     * @var        string
     */
    protected $summary;

    /**
     * The value for the body field.
     *
     * @var        string
     */
    protected $body;

    /**
     * The value for the link field.
     *
     * @var        string
     */
    protected $link;

    /**
     * The value for the type field.
     *
     * @var        int
     */
    protected $type;

    /**
     * The value for the seen field.
     *
     * Note: this column has a database default value of: false
     * @var        boolean
     */
    protected $seen;

    /**
     * The value for the dismissed field.
     *
     * Note: this column has a database default value of: false
     * @var        boolean
     */
    protected $dismissed;

    /**
     * The value for the archived field.
     *
     * Note: this column has a database default value of: false
     * @var        boolean
     */
    protected $archived;

    /**
     * @var        ChildUser
     */
    protected $aUser;

    /**
     * @var        ObjectCollection|ChildNotificationClick[] Collection to store aggregation of ChildNotificationClick objects.
     */
    protected $collNotificationClicks;
    protected $collNotificationClicksPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildNotificationClick[]
     */
    protected $notificationClicksScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see __construct()
     */
    public function applyDefaultValues()
    {
        $this->seen = false;
        $this->dismissed = false;
        $this->archived = false;
    }

    /**
     * Initializes internal state of TechWilk\Rota\Base\Notification object.
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
     * Compares this with another <code>Notification</code> instance.  If
     * <code>obj</code> is an instance of <code>Notification</code>, delegates to
     * <code>equals(Notification)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|Notification The current object, for fluid interface
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
     * Get the [optionally formatted] temporal [timestamp] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getTimestamp($format = null)
    {
        if ($format === null) {
            return $this->timestamp;
        } else {
            return $this->timestamp instanceof \DateTimeInterface ? $this->timestamp->format($format) : null;
        }
    }

    /**
     * Get the [userid] column value.
     *
     * @return int
     */
    public function getUserId()
    {
        return $this->userid;
    }

    /**
     * Get the [summary] column value.
     *
     * @return string
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * Get the [body] column value.
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Get the [link] column value.
     *
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Get the [type] column value.
     *
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Get the [seen] column value.
     *
     * @return boolean
     */
    public function getSeen()
    {
        return $this->seen;
    }

    /**
     * Get the [seen] column value.
     *
     * @return boolean
     */
    public function isSeen()
    {
        return $this->getSeen();
    }

    /**
     * Get the [dismissed] column value.
     *
     * @return boolean
     */
    public function getDismissed()
    {
        return $this->dismissed;
    }

    /**
     * Get the [dismissed] column value.
     *
     * @return boolean
     */
    public function isDismissed()
    {
        return $this->getDismissed();
    }

    /**
     * Get the [archived] column value.
     *
     * @return boolean
     */
    public function getArchived()
    {
        return $this->archived;
    }

    /**
     * Get the [archived] column value.
     *
     * @return boolean
     */
    public function isArchived()
    {
        return $this->getArchived();
    }

    /**
     * Set the value of [id] column.
     *
     * @param int $v new value
     * @return $this|\TechWilk\Rota\Notification The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[NotificationTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Sets the value of [timestamp] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\TechWilk\Rota\Notification The current object (for fluent API support)
     */
    public function setTimestamp($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->timestamp !== null || $dt !== null) {
            if ($this->timestamp === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->timestamp->format("Y-m-d H:i:s.u")) {
                $this->timestamp = $dt === null ? null : clone $dt;
                $this->modifiedColumns[NotificationTableMap::COL_TIMESTAMP] = true;
            }
        } // if either are not null

        return $this;
    } // setTimestamp()

    /**
     * Set the value of [userid] column.
     *
     * @param int $v new value
     * @return $this|\TechWilk\Rota\Notification The current object (for fluent API support)
     */
    public function setUserId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->userid !== $v) {
            $this->userid = $v;
            $this->modifiedColumns[NotificationTableMap::COL_USERID] = true;
        }

        if ($this->aUser !== null && $this->aUser->getId() !== $v) {
            $this->aUser = null;
        }

        return $this;
    } // setUserId()

    /**
     * Set the value of [summary] column.
     *
     * @param string $v new value
     * @return $this|\TechWilk\Rota\Notification The current object (for fluent API support)
     */
    public function setSummary($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->summary !== $v) {
            $this->summary = $v;
            $this->modifiedColumns[NotificationTableMap::COL_SUMMARY] = true;
        }

        return $this;
    } // setSummary()

    /**
     * Set the value of [body] column.
     *
     * @param string $v new value
     * @return $this|\TechWilk\Rota\Notification The current object (for fluent API support)
     */
    public function setBody($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->body !== $v) {
            $this->body = $v;
            $this->modifiedColumns[NotificationTableMap::COL_BODY] = true;
        }

        return $this;
    } // setBody()

    /**
     * Set the value of [link] column.
     *
     * @param string $v new value
     * @return $this|\TechWilk\Rota\Notification The current object (for fluent API support)
     */
    public function setLink($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->link !== $v) {
            $this->link = $v;
            $this->modifiedColumns[NotificationTableMap::COL_LINK] = true;
        }

        return $this;
    } // setLink()

    /**
     * Set the value of [type] column.
     *
     * @param int $v new value
     * @return $this|\TechWilk\Rota\Notification The current object (for fluent API support)
     */
    public function setType($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->type !== $v) {
            $this->type = $v;
            $this->modifiedColumns[NotificationTableMap::COL_TYPE] = true;
        }

        return $this;
    } // setType()

    /**
     * Sets the value of the [seen] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param  boolean|integer|string $v The new value
     * @return $this|\TechWilk\Rota\Notification The current object (for fluent API support)
     */
    public function setSeen($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->seen !== $v) {
            $this->seen = $v;
            $this->modifiedColumns[NotificationTableMap::COL_SEEN] = true;
        }

        return $this;
    } // setSeen()

    /**
     * Sets the value of the [dismissed] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param  boolean|integer|string $v The new value
     * @return $this|\TechWilk\Rota\Notification The current object (for fluent API support)
     */
    public function setDismissed($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->dismissed !== $v) {
            $this->dismissed = $v;
            $this->modifiedColumns[NotificationTableMap::COL_DISMISSED] = true;
        }

        return $this;
    } // setDismissed()

    /**
     * Sets the value of the [archived] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param  boolean|integer|string $v The new value
     * @return $this|\TechWilk\Rota\Notification The current object (for fluent API support)
     */
    public function setArchived($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->archived !== $v) {
            $this->archived = $v;
            $this->modifiedColumns[NotificationTableMap::COL_ARCHIVED] = true;
        }

        return $this;
    } // setArchived()

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
        if ($this->seen !== false) {
            return false;
        }

        if ($this->dismissed !== false) {
            return false;
        }

        if ($this->archived !== false) {
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
            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : NotificationTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : NotificationTableMap::translateFieldName('Timestamp', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->timestamp = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : NotificationTableMap::translateFieldName('UserId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->userid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : NotificationTableMap::translateFieldName('Summary', TableMap::TYPE_PHPNAME, $indexType)];
            $this->summary = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : NotificationTableMap::translateFieldName('Body', TableMap::TYPE_PHPNAME, $indexType)];
            $this->body = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : NotificationTableMap::translateFieldName('Link', TableMap::TYPE_PHPNAME, $indexType)];
            $this->link = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : NotificationTableMap::translateFieldName('Type', TableMap::TYPE_PHPNAME, $indexType)];
            $this->type = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : NotificationTableMap::translateFieldName('Seen', TableMap::TYPE_PHPNAME, $indexType)];
            $this->seen = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : NotificationTableMap::translateFieldName('Dismissed', TableMap::TYPE_PHPNAME, $indexType)];
            $this->dismissed = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 9 + $startcol : NotificationTableMap::translateFieldName('Archived', TableMap::TYPE_PHPNAME, $indexType)];
            $this->archived = (null !== $col) ? (boolean) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 10; // 10 = NotificationTableMap::NUM_HYDRATE_COLUMNS.
        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\TechWilk\\Rota\\Notification'), 0, $e);
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
        if ($this->aUser !== null && $this->userid !== $this->aUser->getId()) {
            $this->aUser = null;
        }
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
            $con = Propel::getServiceContainer()->getReadConnection(NotificationTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildNotificationQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aUser = null;
            $this->collNotificationClicks = null;
        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Notification::setDeleted()
     * @see Notification::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(NotificationTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildNotificationQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(NotificationTableMap::DATABASE_NAME);
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
                NotificationTableMap::addInstanceToPool($this);
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

            // We call the save method on the following object(s) if they
            // were passed to this object by their corresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aUser !== null) {
                if ($this->aUser->isModified() || $this->aUser->isNew()) {
                    $affectedRows += $this->aUser->save($con);
                }
                $this->setUser($this->aUser);
            }

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

            if ($this->notificationClicksScheduledForDeletion !== null) {
                if (!$this->notificationClicksScheduledForDeletion->isEmpty()) {
                    \TechWilk\Rota\NotificationClickQuery::create()
                        ->filterByPrimaryKeys($this->notificationClicksScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->notificationClicksScheduledForDeletion = null;
                }
            }

            if ($this->collNotificationClicks !== null) {
                foreach ($this->collNotificationClicks as $referrerFK) {
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

        $this->modifiedColumns[NotificationTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . NotificationTableMap::COL_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(NotificationTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = 'id';
        }
        if ($this->isColumnModified(NotificationTableMap::COL_TIMESTAMP)) {
            $modifiedColumns[':p' . $index++]  = 'timestamp';
        }
        if ($this->isColumnModified(NotificationTableMap::COL_USERID)) {
            $modifiedColumns[':p' . $index++]  = 'userId';
        }
        if ($this->isColumnModified(NotificationTableMap::COL_SUMMARY)) {
            $modifiedColumns[':p' . $index++]  = 'summary';
        }
        if ($this->isColumnModified(NotificationTableMap::COL_BODY)) {
            $modifiedColumns[':p' . $index++]  = 'body';
        }
        if ($this->isColumnModified(NotificationTableMap::COL_LINK)) {
            $modifiedColumns[':p' . $index++]  = 'link';
        }
        if ($this->isColumnModified(NotificationTableMap::COL_TYPE)) {
            $modifiedColumns[':p' . $index++]  = 'type';
        }
        if ($this->isColumnModified(NotificationTableMap::COL_SEEN)) {
            $modifiedColumns[':p' . $index++]  = 'seen';
        }
        if ($this->isColumnModified(NotificationTableMap::COL_DISMISSED)) {
            $modifiedColumns[':p' . $index++]  = 'dismissed';
        }
        if ($this->isColumnModified(NotificationTableMap::COL_ARCHIVED)) {
            $modifiedColumns[':p' . $index++]  = 'archived';
        }

        $sql = sprintf(
            'INSERT INTO cr_notifications (%s) VALUES (%s)',
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
                    case 'timestamp':
                        $stmt->bindValue($identifier, $this->timestamp ? $this->timestamp->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);
                        break;
                    case 'userId':
                        $stmt->bindValue($identifier, $this->userid, PDO::PARAM_INT);
                        break;
                    case 'summary':
                        $stmt->bindValue($identifier, $this->summary, PDO::PARAM_STR);
                        break;
                    case 'body':
                        $stmt->bindValue($identifier, $this->body, PDO::PARAM_STR);
                        break;
                    case 'link':
                        $stmt->bindValue($identifier, $this->link, PDO::PARAM_STR);
                        break;
                    case 'type':
                        $stmt->bindValue($identifier, $this->type, PDO::PARAM_INT);
                        break;
                    case 'seen':
                        $stmt->bindValue($identifier, (int) $this->seen, PDO::PARAM_INT);
                        break;
                    case 'dismissed':
                        $stmt->bindValue($identifier, (int) $this->dismissed, PDO::PARAM_INT);
                        break;
                    case 'archived':
                        $stmt->bindValue($identifier, (int) $this->archived, PDO::PARAM_INT);
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
        $this->setId($pk);

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
        $pos = NotificationTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getTimestamp();
                break;
            case 2:
                return $this->getUserId();
                break;
            case 3:
                return $this->getSummary();
                break;
            case 4:
                return $this->getBody();
                break;
            case 5:
                return $this->getLink();
                break;
            case 6:
                return $this->getType();
                break;
            case 7:
                return $this->getSeen();
                break;
            case 8:
                return $this->getDismissed();
                break;
            case 9:
                return $this->getArchived();
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
        if (isset($alreadyDumpedObjects['Notification'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Notification'][$this->hashCode()] = true;
        $keys = NotificationTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getTimestamp(),
            $keys[2] => $this->getUserId(),
            $keys[3] => $this->getSummary(),
            $keys[4] => $this->getBody(),
            $keys[5] => $this->getLink(),
            $keys[6] => $this->getType(),
            $keys[7] => $this->getSeen(),
            $keys[8] => $this->getDismissed(),
            $keys[9] => $this->getArchived(),
        );
        if ($result[$keys[1]] instanceof \DateTimeInterface) {
            $result[$keys[1]] = $result[$keys[1]]->format('c');
        }

        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aUser) {
                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'user';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'cr_users';
                        break;
                    default:
                        $key = 'User';
                }

                $result[$key] = $this->aUser->toArray($keyType, $includeLazyLoadColumns, $alreadyDumpedObjects, true);
            }
            if (null !== $this->collNotificationClicks) {
                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'notificationClicks';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'cr_notificationClickss';
                        break;
                    default:
                        $key = 'NotificationClicks';
                }

                $result[$key] = $this->collNotificationClicks->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
     * @return $this|\TechWilk\Rota\Notification
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = NotificationTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\TechWilk\Rota\Notification
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setTimestamp($value);
                break;
            case 2:
                $this->setUserId($value);
                break;
            case 3:
                $this->setSummary($value);
                break;
            case 4:
                $this->setBody($value);
                break;
            case 5:
                $this->setLink($value);
                break;
            case 6:
                $this->setType($value);
                break;
            case 7:
                $this->setSeen($value);
                break;
            case 8:
                $this->setDismissed($value);
                break;
            case 9:
                $this->setArchived($value);
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
        $keys = NotificationTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setTimestamp($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setUserId($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setSummary($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setBody($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setLink($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setType($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setSeen($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->setDismissed($arr[$keys[8]]);
        }
        if (array_key_exists($keys[9], $arr)) {
            $this->setArchived($arr[$keys[9]]);
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
     * @return $this|\TechWilk\Rota\Notification The current object, for fluid interface
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
        $criteria = new Criteria(NotificationTableMap::DATABASE_NAME);

        if ($this->isColumnModified(NotificationTableMap::COL_ID)) {
            $criteria->add(NotificationTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(NotificationTableMap::COL_TIMESTAMP)) {
            $criteria->add(NotificationTableMap::COL_TIMESTAMP, $this->timestamp);
        }
        if ($this->isColumnModified(NotificationTableMap::COL_USERID)) {
            $criteria->add(NotificationTableMap::COL_USERID, $this->userid);
        }
        if ($this->isColumnModified(NotificationTableMap::COL_SUMMARY)) {
            $criteria->add(NotificationTableMap::COL_SUMMARY, $this->summary);
        }
        if ($this->isColumnModified(NotificationTableMap::COL_BODY)) {
            $criteria->add(NotificationTableMap::COL_BODY, $this->body);
        }
        if ($this->isColumnModified(NotificationTableMap::COL_LINK)) {
            $criteria->add(NotificationTableMap::COL_LINK, $this->link);
        }
        if ($this->isColumnModified(NotificationTableMap::COL_TYPE)) {
            $criteria->add(NotificationTableMap::COL_TYPE, $this->type);
        }
        if ($this->isColumnModified(NotificationTableMap::COL_SEEN)) {
            $criteria->add(NotificationTableMap::COL_SEEN, $this->seen);
        }
        if ($this->isColumnModified(NotificationTableMap::COL_DISMISSED)) {
            $criteria->add(NotificationTableMap::COL_DISMISSED, $this->dismissed);
        }
        if ($this->isColumnModified(NotificationTableMap::COL_ARCHIVED)) {
            $criteria->add(NotificationTableMap::COL_ARCHIVED, $this->archived);
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
        $criteria = ChildNotificationQuery::create();
        $criteria->add(NotificationTableMap::COL_ID, $this->id);

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
     * @param      object $copyObj An object of \TechWilk\Rota\Notification (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setTimestamp($this->getTimestamp());
        $copyObj->setUserId($this->getUserId());
        $copyObj->setSummary($this->getSummary());
        $copyObj->setBody($this->getBody());
        $copyObj->setLink($this->getLink());
        $copyObj->setType($this->getType());
        $copyObj->setSeen($this->getSeen());
        $copyObj->setDismissed($this->getDismissed());
        $copyObj->setArchived($this->getArchived());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getNotificationClicks() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addNotificationClick($relObj->copy($deepCopy));
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
     * @return \TechWilk\Rota\Notification Clone of current object.
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
     * Declares an association between this object and a ChildUser object.
     *
     * @param  ChildUser $v
     * @return $this|\TechWilk\Rota\Notification The current object (for fluent API support)
     * @throws PropelException
     */
    public function setUser(ChildUser $v = null)
    {
        if ($v === null) {
            $this->setUserId(null);
        } else {
            $this->setUserId($v->getId());
        }

        $this->aUser = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildUser object, it will not be re-added.
        if ($v !== null) {
            $v->addNotification($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildUser object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildUser The associated ChildUser object.
     * @throws PropelException
     */
    public function getUser(ConnectionInterface $con = null)
    {
        if ($this->aUser === null && ($this->userid != 0)) {
            $this->aUser = ChildUserQuery::create()->findPk($this->userid, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aUser->addNotifications($this);
             */
        }

        return $this->aUser;
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
        if ('NotificationClick' == $relationName) {
            $this->initNotificationClicks();
            return;
        }
    }

    /**
     * Clears out the collNotificationClicks collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addNotificationClicks()
     */
    public function clearNotificationClicks()
    {
        $this->collNotificationClicks = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collNotificationClicks collection loaded partially.
     */
    public function resetPartialNotificationClicks($v = true)
    {
        $this->collNotificationClicksPartial = $v;
    }

    /**
     * Initializes the collNotificationClicks collection.
     *
     * By default this just sets the collNotificationClicks collection to an empty array (like clearcollNotificationClicks());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initNotificationClicks($overrideExisting = true)
    {
        if (null !== $this->collNotificationClicks && !$overrideExisting) {
            return;
        }

        $collectionClassName = NotificationClickTableMap::getTableMap()->getCollectionClassName();

        $this->collNotificationClicks = new $collectionClassName;
        $this->collNotificationClicks->setModel('\TechWilk\Rota\NotificationClick');
    }

    /**
     * Gets an array of ChildNotificationClick objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildNotification is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildNotificationClick[] List of ChildNotificationClick objects
     * @throws PropelException
     */
    public function getNotificationClicks(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collNotificationClicksPartial && !$this->isNew();
        if (null === $this->collNotificationClicks || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collNotificationClicks) {
                // return empty collection
                $this->initNotificationClicks();
            } else {
                $collNotificationClicks = ChildNotificationClickQuery::create(null, $criteria)
                    ->filterByNotification($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collNotificationClicksPartial && count($collNotificationClicks)) {
                        $this->initNotificationClicks(false);

                        foreach ($collNotificationClicks as $obj) {
                            if (false == $this->collNotificationClicks->contains($obj)) {
                                $this->collNotificationClicks->append($obj);
                            }
                        }

                        $this->collNotificationClicksPartial = true;
                    }

                    return $collNotificationClicks;
                }

                if ($partial && $this->collNotificationClicks) {
                    foreach ($this->collNotificationClicks as $obj) {
                        if ($obj->isNew()) {
                            $collNotificationClicks[] = $obj;
                        }
                    }
                }

                $this->collNotificationClicks = $collNotificationClicks;
                $this->collNotificationClicksPartial = false;
            }
        }

        return $this->collNotificationClicks;
    }

    /**
     * Sets a collection of ChildNotificationClick objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $notificationClicks A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildNotification The current object (for fluent API support)
     */
    public function setNotificationClicks(Collection $notificationClicks, ConnectionInterface $con = null)
    {
        /** @var ChildNotificationClick[] $notificationClicksToDelete */
        $notificationClicksToDelete = $this->getNotificationClicks(new Criteria(), $con)->diff($notificationClicks);


        $this->notificationClicksScheduledForDeletion = $notificationClicksToDelete;

        foreach ($notificationClicksToDelete as $notificationClickRemoved) {
            $notificationClickRemoved->setNotification(null);
        }

        $this->collNotificationClicks = null;
        foreach ($notificationClicks as $notificationClick) {
            $this->addNotificationClick($notificationClick);
        }

        $this->collNotificationClicks = $notificationClicks;
        $this->collNotificationClicksPartial = false;

        return $this;
    }

    /**
     * Returns the number of related NotificationClick objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related NotificationClick objects.
     * @throws PropelException
     */
    public function countNotificationClicks(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collNotificationClicksPartial && !$this->isNew();
        if (null === $this->collNotificationClicks || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collNotificationClicks) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getNotificationClicks());
            }

            $query = ChildNotificationClickQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByNotification($this)
                ->count($con);
        }

        return count($this->collNotificationClicks);
    }

    /**
     * Method called to associate a ChildNotificationClick object to this object
     * through the ChildNotificationClick foreign key attribute.
     *
     * @param  ChildNotificationClick $l ChildNotificationClick
     * @return $this|\TechWilk\Rota\Notification The current object (for fluent API support)
     */
    public function addNotificationClick(ChildNotificationClick $l)
    {
        if ($this->collNotificationClicks === null) {
            $this->initNotificationClicks();
            $this->collNotificationClicksPartial = true;
        }

        if (!$this->collNotificationClicks->contains($l)) {
            $this->doAddNotificationClick($l);

            if ($this->notificationClicksScheduledForDeletion and $this->notificationClicksScheduledForDeletion->contains($l)) {
                $this->notificationClicksScheduledForDeletion->remove($this->notificationClicksScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildNotificationClick $notificationClick The ChildNotificationClick object to add.
     */
    protected function doAddNotificationClick(ChildNotificationClick $notificationClick)
    {
        $this->collNotificationClicks[]= $notificationClick;
        $notificationClick->setNotification($this);
    }

    /**
     * @param  ChildNotificationClick $notificationClick The ChildNotificationClick object to remove.
     * @return $this|ChildNotification The current object (for fluent API support)
     */
    public function removeNotificationClick(ChildNotificationClick $notificationClick)
    {
        if ($this->getNotificationClicks()->contains($notificationClick)) {
            $pos = $this->collNotificationClicks->search($notificationClick);
            $this->collNotificationClicks->remove($pos);
            if (null === $this->notificationClicksScheduledForDeletion) {
                $this->notificationClicksScheduledForDeletion = clone $this->collNotificationClicks;
                $this->notificationClicksScheduledForDeletion->clear();
            }
            $this->notificationClicksScheduledForDeletion[]= clone $notificationClick;
            $notificationClick->setNotification(null);
        }

        return $this;
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        if (null !== $this->aUser) {
            $this->aUser->removeNotification($this);
        }
        $this->id = null;
        $this->timestamp = null;
        $this->userid = null;
        $this->summary = null;
        $this->body = null;
        $this->link = null;
        $this->type = null;
        $this->seen = null;
        $this->dismissed = null;
        $this->archived = null;
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
            if ($this->collNotificationClicks) {
                foreach ($this->collNotificationClicks as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collNotificationClicks = null;
        $this->aUser = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(NotificationTableMap::DEFAULT_STRING_FORMAT);
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
