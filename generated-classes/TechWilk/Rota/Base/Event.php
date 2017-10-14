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
use TechWilk\Rota\Availability as ChildAvailability;
use TechWilk\Rota\AvailabilityQuery as ChildAvailabilityQuery;
use TechWilk\Rota\Comment as ChildComment;
use TechWilk\Rota\CommentQuery as ChildCommentQuery;
use TechWilk\Rota\Event as ChildEvent;
use TechWilk\Rota\EventGroup as ChildEventGroup;
use TechWilk\Rota\EventGroupQuery as ChildEventGroupQuery;
use TechWilk\Rota\EventPerson as ChildEventPerson;
use TechWilk\Rota\EventPersonQuery as ChildEventPersonQuery;
use TechWilk\Rota\EventQuery as ChildEventQuery;
use TechWilk\Rota\EventSubType as ChildEventSubType;
use TechWilk\Rota\EventSubTypeQuery as ChildEventSubTypeQuery;
use TechWilk\Rota\EventType as ChildEventType;
use TechWilk\Rota\EventTypeQuery as ChildEventTypeQuery;
use TechWilk\Rota\Location as ChildLocation;
use TechWilk\Rota\LocationQuery as ChildLocationQuery;
use TechWilk\Rota\User as ChildUser;
use TechWilk\Rota\UserQuery as ChildUserQuery;
use TechWilk\Rota\Map\AvailabilityTableMap;
use TechWilk\Rota\Map\CommentTableMap;
use TechWilk\Rota\Map\EventPersonTableMap;
use TechWilk\Rota\Map\EventTableMap;

/**
 * Base class that represents a row from the 'events' table.
 *
 *
 *
 * @package    propel.generator.TechWilk.Rota.Base
 */
abstract class Event implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\TechWilk\\Rota\\Map\\EventTableMap';


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
     * The value for the date field.
     *
     * Note: this column has a database default value of: NULL
     * @var        DateTime
     */
    protected $date;

    /**
     * The value for the name field.
     *
     * @var        string
     */
    protected $name;

    /**
     * The value for the createdby field.
     *
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $createdby;

    /**
     * The value for the rehearsaldate field.
     *
     * Note: this column has a database default value of: NULL
     * @var        DateTime
     */
    protected $rehearsaldate;

    /**
     * The value for the type field.
     *
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $type;

    /**
     * The value for the subtype field.
     *
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $subtype;

    /**
     * The value for the location field.
     *
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $location;

    /**
     * The value for the notified field.
     *
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $notified;

    /**
     * The value for the rehearsal field.
     *
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $rehearsal;

    /**
     * The value for the removed field.
     *
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $removed;

    /**
     * The value for the eventgroup field.
     *
     * @var        int
     */
    protected $eventgroup;

    /**
     * The value for the sermontitle field.
     *
     * @var        string
     */
    protected $sermontitle;

    /**
     * The value for the bibleverse field.
     *
     * @var        string
     */
    protected $bibleverse;

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
     * @var        ChildUser
     */
    protected $aUser;

    /**
     * @var        ChildEventType
     */
    protected $aEventType;

    /**
     * @var        ChildEventSubType
     */
    protected $aEventSubType;

    /**
     * @var        ChildLocation
     */
    protected $aLocation;

    /**
     * @var        ChildEventGroup
     */
    protected $aEventGroup;

    /**
     * @var        ObjectCollection|ChildComment[] Collection to store aggregation of ChildComment objects.
     */
    protected $collComments;
    protected $collCommentsPartial;

    /**
     * @var        ObjectCollection|ChildEventPerson[] Collection to store aggregation of ChildEventPerson objects.
     */
    protected $collEventpeople;
    protected $collEventpeoplePartial;

    /**
     * @var        ObjectCollection|ChildAvailability[] Collection to store aggregation of ChildAvailability objects.
     */
    protected $collAvailabilities;
    protected $collAvailabilitiesPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildComment[]
     */
    protected $commentsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildEventPerson[]
     */
    protected $eventpeopleScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildAvailability[]
     */
    protected $availabilitiesScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see __construct()
     */
    public function applyDefaultValues()
    {
        $this->date = PropelDateTime::newInstance(null, null, 'DateTime');
        $this->createdby = 0;
        $this->rehearsaldate = PropelDateTime::newInstance(null, null, 'DateTime');
        $this->type = 0;
        $this->subtype = 0;
        $this->location = 0;
        $this->notified = 0;
        $this->rehearsal = 0;
        $this->removed = 0;
    }

    /**
     * Initializes internal state of TechWilk\Rota\Base\Event object.
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
     * Compares this with another <code>Event</code> instance.  If
     * <code>obj</code> is an instance of <code>Event</code>, delegates to
     * <code>equals(Event)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|Event The current object, for fluid interface
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
     * Get the [optionally formatted] temporal [date] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getDate($format = null)
    {
        if ($format === null) {
            return $this->date;
        } else {
            return $this->date instanceof \DateTimeInterface ? $this->date->format($format) : null;
        }
    }

    /**
     * Get the [name] column value.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the [createdby] column value.
     *
     * @return int
     */
    public function getCreatedBy()
    {
        return $this->createdby;
    }

    /**
     * Get the [optionally formatted] temporal [rehearsaldate] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getRehearsalDate($format = null)
    {
        if ($format === null) {
            return $this->rehearsaldate;
        } else {
            return $this->rehearsaldate instanceof \DateTimeInterface ? $this->rehearsaldate->format($format) : null;
        }
    }

    /**
     * Get the [type] column value.
     *
     * @return int
     */
    public function getEventTypeId()
    {
        return $this->type;
    }

    /**
     * Get the [subtype] column value.
     *
     * @return int
     */
    public function getEventSubTypeId()
    {
        return $this->subtype;
    }

    /**
     * Get the [location] column value.
     *
     * @return int
     */
    public function getLocationId()
    {
        return $this->location;
    }

    /**
     * Get the [notified] column value.
     *
     * @return int
     */
    public function getNotified()
    {
        return $this->notified;
    }

    /**
     * Get the [rehearsal] column value.
     *
     * @return int
     */
    public function getRehearsal()
    {
        return $this->rehearsal;
    }

    /**
     * Get the [removed] column value.
     *
     * @return int
     */
    public function getRemoved()
    {
        return $this->removed;
    }

    /**
     * Get the [eventgroup] column value.
     *
     * @return int
     */
    public function getEventGroupId()
    {
        return $this->eventgroup;
    }

    /**
     * Get the [sermontitle] column value.
     *
     * @return string
     */
    public function getSermonTitle()
    {
        return $this->sermontitle;
    }

    /**
     * Get the [bibleverse] column value.
     *
     * @return string
     */
    public function getBibleVerse()
    {
        return $this->bibleverse;
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
     * @return $this|\TechWilk\Rota\Event The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[EventTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Sets the value of [date] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\TechWilk\Rota\Event The current object (for fluent API support)
     */
    public function setDate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->date !== null || $dt !== null) {
            if (($dt != $this->date) // normalized values don't match
                || ($dt->format('Y-m-d H:i:s.u') === null) // or the entered value matches the default
                 ) {
                $this->date = $dt === null ? null : clone $dt;
                $this->modifiedColumns[EventTableMap::COL_DATE] = true;
            }
        } // if either are not null

        return $this;
    } // setDate()

    /**
     * Set the value of [name] column.
     *
     * @param string $v new value
     * @return $this|\TechWilk\Rota\Event The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[EventTableMap::COL_NAME] = true;
        }

        return $this;
    } // setName()

    /**
     * Set the value of [createdby] column.
     *
     * @param int $v new value
     * @return $this|\TechWilk\Rota\Event The current object (for fluent API support)
     */
    public function setCreatedBy($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->createdby !== $v) {
            $this->createdby = $v;
            $this->modifiedColumns[EventTableMap::COL_CREATEDBY] = true;
        }

        if ($this->aUser !== null && $this->aUser->getId() !== $v) {
            $this->aUser = null;
        }

        return $this;
    } // setCreatedBy()

    /**
     * Sets the value of [rehearsaldate] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\TechWilk\Rota\Event The current object (for fluent API support)
     */
    public function setRehearsalDate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->rehearsaldate !== null || $dt !== null) {
            if (($dt != $this->rehearsaldate) // normalized values don't match
                || ($dt->format('Y-m-d H:i:s.u') === null) // or the entered value matches the default
                 ) {
                $this->rehearsaldate = $dt === null ? null : clone $dt;
                $this->modifiedColumns[EventTableMap::COL_REHEARSALDATE] = true;
            }
        } // if either are not null

        return $this;
    } // setRehearsalDate()

    /**
     * Set the value of [type] column.
     *
     * @param int $v new value
     * @return $this|\TechWilk\Rota\Event The current object (for fluent API support)
     */
    public function setEventTypeId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->type !== $v) {
            $this->type = $v;
            $this->modifiedColumns[EventTableMap::COL_TYPE] = true;
        }

        if ($this->aEventType !== null && $this->aEventType->getId() !== $v) {
            $this->aEventType = null;
        }

        return $this;
    } // setEventTypeId()

    /**
     * Set the value of [subtype] column.
     *
     * @param int $v new value
     * @return $this|\TechWilk\Rota\Event The current object (for fluent API support)
     */
    public function setEventSubTypeId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->subtype !== $v) {
            $this->subtype = $v;
            $this->modifiedColumns[EventTableMap::COL_SUBTYPE] = true;
        }

        if ($this->aEventSubType !== null && $this->aEventSubType->getId() !== $v) {
            $this->aEventSubType = null;
        }

        return $this;
    } // setEventSubTypeId()

    /**
     * Set the value of [location] column.
     *
     * @param int $v new value
     * @return $this|\TechWilk\Rota\Event The current object (for fluent API support)
     */
    public function setLocationId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->location !== $v) {
            $this->location = $v;
            $this->modifiedColumns[EventTableMap::COL_LOCATION] = true;
        }

        if ($this->aLocation !== null && $this->aLocation->getId() !== $v) {
            $this->aLocation = null;
        }

        return $this;
    } // setLocationId()

    /**
     * Set the value of [notified] column.
     *
     * @param int $v new value
     * @return $this|\TechWilk\Rota\Event The current object (for fluent API support)
     */
    public function setNotified($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->notified !== $v) {
            $this->notified = $v;
            $this->modifiedColumns[EventTableMap::COL_NOTIFIED] = true;
        }

        return $this;
    } // setNotified()

    /**
     * Set the value of [rehearsal] column.
     *
     * @param int $v new value
     * @return $this|\TechWilk\Rota\Event The current object (for fluent API support)
     */
    public function setRehearsal($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->rehearsal !== $v) {
            $this->rehearsal = $v;
            $this->modifiedColumns[EventTableMap::COL_REHEARSAL] = true;
        }

        return $this;
    } // setRehearsal()

    /**
     * Set the value of [removed] column.
     *
     * @param int $v new value
     * @return $this|\TechWilk\Rota\Event The current object (for fluent API support)
     */
    public function setRemoved($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->removed !== $v) {
            $this->removed = $v;
            $this->modifiedColumns[EventTableMap::COL_REMOVED] = true;
        }

        return $this;
    } // setRemoved()

    /**
     * Set the value of [eventgroup] column.
     *
     * @param int $v new value
     * @return $this|\TechWilk\Rota\Event The current object (for fluent API support)
     */
    public function setEventGroupId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->eventgroup !== $v) {
            $this->eventgroup = $v;
            $this->modifiedColumns[EventTableMap::COL_EVENTGROUP] = true;
        }

        if ($this->aEventGroup !== null && $this->aEventGroup->getId() !== $v) {
            $this->aEventGroup = null;
        }

        return $this;
    } // setEventGroupId()

    /**
     * Set the value of [sermontitle] column.
     *
     * @param string $v new value
     * @return $this|\TechWilk\Rota\Event The current object (for fluent API support)
     */
    public function setSermonTitle($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->sermontitle !== $v) {
            $this->sermontitle = $v;
            $this->modifiedColumns[EventTableMap::COL_SERMONTITLE] = true;
        }

        return $this;
    } // setSermonTitle()

    /**
     * Set the value of [bibleverse] column.
     *
     * @param string $v new value
     * @return $this|\TechWilk\Rota\Event The current object (for fluent API support)
     */
    public function setBibleVerse($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->bibleverse !== $v) {
            $this->bibleverse = $v;
            $this->modifiedColumns[EventTableMap::COL_BIBLEVERSE] = true;
        }

        return $this;
    } // setBibleVerse()

    /**
     * Sets the value of [created] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\TechWilk\Rota\Event The current object (for fluent API support)
     */
    public function setCreated($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created !== null || $dt !== null) {
            if ($this->created === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->created->format("Y-m-d H:i:s.u")) {
                $this->created = $dt === null ? null : clone $dt;
                $this->modifiedColumns[EventTableMap::COL_CREATED] = true;
            }
        } // if either are not null

        return $this;
    } // setCreated()

    /**
     * Sets the value of [updated] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\TechWilk\Rota\Event The current object (for fluent API support)
     */
    public function setUpdated($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated !== null || $dt !== null) {
            if ($this->updated === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->updated->format("Y-m-d H:i:s.u")) {
                $this->updated = $dt === null ? null : clone $dt;
                $this->modifiedColumns[EventTableMap::COL_UPDATED] = true;
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
        if ($this->date && $this->date->format('Y-m-d H:i:s.u') !== null) {
            return false;
        }

        if ($this->createdby !== 0) {
            return false;
        }

        if ($this->rehearsaldate && $this->rehearsaldate->format('Y-m-d H:i:s.u') !== null) {
            return false;
        }

        if ($this->type !== 0) {
            return false;
        }

        if ($this->subtype !== 0) {
            return false;
        }

        if ($this->location !== 0) {
            return false;
        }

        if ($this->notified !== 0) {
            return false;
        }

        if ($this->rehearsal !== 0) {
            return false;
        }

        if ($this->removed !== 0) {
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
            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : EventTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : EventTableMap::translateFieldName('Date', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->date = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : EventTableMap::translateFieldName('Name', TableMap::TYPE_PHPNAME, $indexType)];
            $this->name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : EventTableMap::translateFieldName('CreatedBy', TableMap::TYPE_PHPNAME, $indexType)];
            $this->createdby = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : EventTableMap::translateFieldName('RehearsalDate', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->rehearsaldate = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : EventTableMap::translateFieldName('EventTypeId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->type = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : EventTableMap::translateFieldName('EventSubTypeId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->subtype = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : EventTableMap::translateFieldName('LocationId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->location = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : EventTableMap::translateFieldName('Notified', TableMap::TYPE_PHPNAME, $indexType)];
            $this->notified = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 9 + $startcol : EventTableMap::translateFieldName('Rehearsal', TableMap::TYPE_PHPNAME, $indexType)];
            $this->rehearsal = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 10 + $startcol : EventTableMap::translateFieldName('Removed', TableMap::TYPE_PHPNAME, $indexType)];
            $this->removed = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 11 + $startcol : EventTableMap::translateFieldName('EventGroupId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->eventgroup = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 12 + $startcol : EventTableMap::translateFieldName('SermonTitle', TableMap::TYPE_PHPNAME, $indexType)];
            $this->sermontitle = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 13 + $startcol : EventTableMap::translateFieldName('BibleVerse', TableMap::TYPE_PHPNAME, $indexType)];
            $this->bibleverse = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 14 + $startcol : EventTableMap::translateFieldName('Created', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->created = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 15 + $startcol : EventTableMap::translateFieldName('Updated', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->updated = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 16; // 16 = EventTableMap::NUM_HYDRATE_COLUMNS.
        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\TechWilk\\Rota\\Event'), 0, $e);
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
        if ($this->aUser !== null && $this->createdby !== $this->aUser->getId()) {
            $this->aUser = null;
        }
        if ($this->aEventType !== null && $this->type !== $this->aEventType->getId()) {
            $this->aEventType = null;
        }
        if ($this->aEventSubType !== null && $this->subtype !== $this->aEventSubType->getId()) {
            $this->aEventSubType = null;
        }
        if ($this->aLocation !== null && $this->location !== $this->aLocation->getId()) {
            $this->aLocation = null;
        }
        if ($this->aEventGroup !== null && $this->eventgroup !== $this->aEventGroup->getId()) {
            $this->aEventGroup = null;
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
            $con = Propel::getServiceContainer()->getReadConnection(EventTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildEventQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aUser = null;
            $this->aEventType = null;
            $this->aEventSubType = null;
            $this->aLocation = null;
            $this->aEventGroup = null;
            $this->collComments = null;

            $this->collEventpeople = null;

            $this->collAvailabilities = null;
        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Event::setDeleted()
     * @see Event::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(EventTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildEventQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(EventTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $ret = $this->preSave($con);
            $isInsert = $this->isNew();
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior

                if (!$this->isColumnModified(EventTableMap::COL_CREATED)) {
                    $this->setCreated(\Propel\Runtime\Util\PropelDateTime::createHighPrecision());
                }
                if (!$this->isColumnModified(EventTableMap::COL_UPDATED)) {
                    $this->setUpdated(\Propel\Runtime\Util\PropelDateTime::createHighPrecision());
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(EventTableMap::COL_UPDATED)) {
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
                EventTableMap::addInstanceToPool($this);
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

            if ($this->aEventType !== null) {
                if ($this->aEventType->isModified() || $this->aEventType->isNew()) {
                    $affectedRows += $this->aEventType->save($con);
                }
                $this->setEventType($this->aEventType);
            }

            if ($this->aEventSubType !== null) {
                if ($this->aEventSubType->isModified() || $this->aEventSubType->isNew()) {
                    $affectedRows += $this->aEventSubType->save($con);
                }
                $this->setEventSubType($this->aEventSubType);
            }

            if ($this->aLocation !== null) {
                if ($this->aLocation->isModified() || $this->aLocation->isNew()) {
                    $affectedRows += $this->aLocation->save($con);
                }
                $this->setLocation($this->aLocation);
            }

            if ($this->aEventGroup !== null) {
                if ($this->aEventGroup->isModified() || $this->aEventGroup->isNew()) {
                    $affectedRows += $this->aEventGroup->save($con);
                }
                $this->setEventGroup($this->aEventGroup);
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

            if ($this->commentsScheduledForDeletion !== null) {
                if (!$this->commentsScheduledForDeletion->isEmpty()) {
                    \TechWilk\Rota\CommentQuery::create()
                        ->filterByPrimaryKeys($this->commentsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->commentsScheduledForDeletion = null;
                }
            }

            if ($this->collComments !== null) {
                foreach ($this->collComments as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->eventpeopleScheduledForDeletion !== null) {
                if (!$this->eventpeopleScheduledForDeletion->isEmpty()) {
                    \TechWilk\Rota\EventPersonQuery::create()
                        ->filterByPrimaryKeys($this->eventpeopleScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->eventpeopleScheduledForDeletion = null;
                }
            }

            if ($this->collEventpeople !== null) {
                foreach ($this->collEventpeople as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->availabilitiesScheduledForDeletion !== null) {
                if (!$this->availabilitiesScheduledForDeletion->isEmpty()) {
                    \TechWilk\Rota\AvailabilityQuery::create()
                        ->filterByPrimaryKeys($this->availabilitiesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->availabilitiesScheduledForDeletion = null;
                }
            }

            if ($this->collAvailabilities !== null) {
                foreach ($this->collAvailabilities as $referrerFK) {
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

        $this->modifiedColumns[EventTableMap::COL_ID] = true;

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(EventTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = 'id';
        }
        if ($this->isColumnModified(EventTableMap::COL_DATE)) {
            $modifiedColumns[':p' . $index++]  = 'date';
        }
        if ($this->isColumnModified(EventTableMap::COL_NAME)) {
            $modifiedColumns[':p' . $index++]  = 'name';
        }
        if ($this->isColumnModified(EventTableMap::COL_CREATEDBY)) {
            $modifiedColumns[':p' . $index++]  = 'createdBy';
        }
        if ($this->isColumnModified(EventTableMap::COL_REHEARSALDATE)) {
            $modifiedColumns[':p' . $index++]  = 'rehearsalDate';
        }
        if ($this->isColumnModified(EventTableMap::COL_TYPE)) {
            $modifiedColumns[':p' . $index++]  = 'type';
        }
        if ($this->isColumnModified(EventTableMap::COL_SUBTYPE)) {
            $modifiedColumns[':p' . $index++]  = 'subType';
        }
        if ($this->isColumnModified(EventTableMap::COL_LOCATION)) {
            $modifiedColumns[':p' . $index++]  = 'location';
        }
        if ($this->isColumnModified(EventTableMap::COL_NOTIFIED)) {
            $modifiedColumns[':p' . $index++]  = 'notified';
        }
        if ($this->isColumnModified(EventTableMap::COL_REHEARSAL)) {
            $modifiedColumns[':p' . $index++]  = 'rehearsal';
        }
        if ($this->isColumnModified(EventTableMap::COL_REMOVED)) {
            $modifiedColumns[':p' . $index++]  = 'removed';
        }
        if ($this->isColumnModified(EventTableMap::COL_EVENTGROUP)) {
            $modifiedColumns[':p' . $index++]  = 'eventGroup';
        }
        if ($this->isColumnModified(EventTableMap::COL_SERMONTITLE)) {
            $modifiedColumns[':p' . $index++]  = 'sermonTitle';
        }
        if ($this->isColumnModified(EventTableMap::COL_BIBLEVERSE)) {
            $modifiedColumns[':p' . $index++]  = 'bibleVerse';
        }
        if ($this->isColumnModified(EventTableMap::COL_CREATED)) {
            $modifiedColumns[':p' . $index++]  = 'created';
        }
        if ($this->isColumnModified(EventTableMap::COL_UPDATED)) {
            $modifiedColumns[':p' . $index++]  = 'updated';
        }

        $sql = sprintf(
            'INSERT INTO events (%s) VALUES (%s)',
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
                    case 'date':
                        $stmt->bindValue($identifier, $this->date ? $this->date->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);
                        break;
                    case 'name':
                        $stmt->bindValue($identifier, $this->name, PDO::PARAM_STR);
                        break;
                    case 'createdBy':
                        $stmt->bindValue($identifier, $this->createdby, PDO::PARAM_INT);
                        break;
                    case 'rehearsalDate':
                        $stmt->bindValue($identifier, $this->rehearsaldate ? $this->rehearsaldate->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);
                        break;
                    case 'type':
                        $stmt->bindValue($identifier, $this->type, PDO::PARAM_INT);
                        break;
                    case 'subType':
                        $stmt->bindValue($identifier, $this->subtype, PDO::PARAM_INT);
                        break;
                    case 'location':
                        $stmt->bindValue($identifier, $this->location, PDO::PARAM_INT);
                        break;
                    case 'notified':
                        $stmt->bindValue($identifier, $this->notified, PDO::PARAM_INT);
                        break;
                    case 'rehearsal':
                        $stmt->bindValue($identifier, $this->rehearsal, PDO::PARAM_INT);
                        break;
                    case 'removed':
                        $stmt->bindValue($identifier, $this->removed, PDO::PARAM_INT);
                        break;
                    case 'eventGroup':
                        $stmt->bindValue($identifier, $this->eventgroup, PDO::PARAM_INT);
                        break;
                    case 'sermonTitle':
                        $stmt->bindValue($identifier, $this->sermontitle, PDO::PARAM_STR);
                        break;
                    case 'bibleVerse':
                        $stmt->bindValue($identifier, $this->bibleverse, PDO::PARAM_STR);
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
        $pos = EventTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getDate();
                break;
            case 2:
                return $this->getName();
                break;
            case 3:
                return $this->getCreatedBy();
                break;
            case 4:
                return $this->getRehearsalDate();
                break;
            case 5:
                return $this->getEventTypeId();
                break;
            case 6:
                return $this->getEventSubTypeId();
                break;
            case 7:
                return $this->getLocationId();
                break;
            case 8:
                return $this->getNotified();
                break;
            case 9:
                return $this->getRehearsal();
                break;
            case 10:
                return $this->getRemoved();
                break;
            case 11:
                return $this->getEventGroupId();
                break;
            case 12:
                return $this->getSermonTitle();
                break;
            case 13:
                return $this->getBibleVerse();
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
        if (isset($alreadyDumpedObjects['Event'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Event'][$this->hashCode()] = true;
        $keys = EventTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getDate(),
            $keys[2] => $this->getName(),
            $keys[3] => $this->getCreatedBy(),
            $keys[4] => $this->getRehearsalDate(),
            $keys[5] => $this->getEventTypeId(),
            $keys[6] => $this->getEventSubTypeId(),
            $keys[7] => $this->getLocationId(),
            $keys[8] => $this->getNotified(),
            $keys[9] => $this->getRehearsal(),
            $keys[10] => $this->getRemoved(),
            $keys[11] => $this->getEventGroupId(),
            $keys[12] => $this->getSermonTitle(),
            $keys[13] => $this->getBibleVerse(),
            $keys[14] => $this->getCreated(),
            $keys[15] => $this->getUpdated(),
        );
        if ($result[$keys[1]] instanceof \DateTimeInterface) {
            $result[$keys[1]] = $result[$keys[1]]->format('c');
        }

        if ($result[$keys[4]] instanceof \DateTimeInterface) {
            $result[$keys[4]] = $result[$keys[4]]->format('c');
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
            if (null !== $this->aUser) {
                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'user';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'users';
                        break;
                    default:
                        $key = 'User';
                }

                $result[$key] = $this->aUser->toArray($keyType, $includeLazyLoadColumns, $alreadyDumpedObjects, true);
            }
            if (null !== $this->aEventType) {
                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'eventType';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'eventTypes';
                        break;
                    default:
                        $key = 'EventType';
                }

                $result[$key] = $this->aEventType->toArray($keyType, $includeLazyLoadColumns, $alreadyDumpedObjects, true);
            }
            if (null !== $this->aEventSubType) {
                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'eventSubType';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'eventSubTypes';
                        break;
                    default:
                        $key = 'EventSubType';
                }

                $result[$key] = $this->aEventSubType->toArray($keyType, $includeLazyLoadColumns, $alreadyDumpedObjects, true);
            }
            if (null !== $this->aLocation) {
                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'location';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'locations';
                        break;
                    default:
                        $key = 'Location';
                }

                $result[$key] = $this->aLocation->toArray($keyType, $includeLazyLoadColumns, $alreadyDumpedObjects, true);
            }
            if (null !== $this->aEventGroup) {
                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'eventGroup';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'eventGroups';
                        break;
                    default:
                        $key = 'EventGroup';
                }

                $result[$key] = $this->aEventGroup->toArray($keyType, $includeLazyLoadColumns, $alreadyDumpedObjects, true);
            }
            if (null !== $this->collComments) {
                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'comments';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'commentss';
                        break;
                    default:
                        $key = 'Comments';
                }

                $result[$key] = $this->collComments->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collEventpeople) {
                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'eventpeople';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'eventPeoples';
                        break;
                    default:
                        $key = 'Eventpeople';
                }

                $result[$key] = $this->collEventpeople->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collAvailabilities) {
                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'availabilities';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'availabilities';
                        break;
                    default:
                        $key = 'Availabilities';
                }

                $result[$key] = $this->collAvailabilities->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
     * @return $this|\TechWilk\Rota\Event
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = EventTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\TechWilk\Rota\Event
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setDate($value);
                break;
            case 2:
                $this->setName($value);
                break;
            case 3:
                $this->setCreatedBy($value);
                break;
            case 4:
                $this->setRehearsalDate($value);
                break;
            case 5:
                $this->setEventTypeId($value);
                break;
            case 6:
                $this->setEventSubTypeId($value);
                break;
            case 7:
                $this->setLocationId($value);
                break;
            case 8:
                $this->setNotified($value);
                break;
            case 9:
                $this->setRehearsal($value);
                break;
            case 10:
                $this->setRemoved($value);
                break;
            case 11:
                $this->setEventGroupId($value);
                break;
            case 12:
                $this->setSermonTitle($value);
                break;
            case 13:
                $this->setBibleVerse($value);
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
        $keys = EventTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setDate($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setName($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setCreatedBy($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setRehearsalDate($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setEventTypeId($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setEventSubTypeId($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setLocationId($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->setNotified($arr[$keys[8]]);
        }
        if (array_key_exists($keys[9], $arr)) {
            $this->setRehearsal($arr[$keys[9]]);
        }
        if (array_key_exists($keys[10], $arr)) {
            $this->setRemoved($arr[$keys[10]]);
        }
        if (array_key_exists($keys[11], $arr)) {
            $this->setEventGroupId($arr[$keys[11]]);
        }
        if (array_key_exists($keys[12], $arr)) {
            $this->setSermonTitle($arr[$keys[12]]);
        }
        if (array_key_exists($keys[13], $arr)) {
            $this->setBibleVerse($arr[$keys[13]]);
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
     * @return $this|\TechWilk\Rota\Event The current object, for fluid interface
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
        $criteria = new Criteria(EventTableMap::DATABASE_NAME);

        if ($this->isColumnModified(EventTableMap::COL_ID)) {
            $criteria->add(EventTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(EventTableMap::COL_DATE)) {
            $criteria->add(EventTableMap::COL_DATE, $this->date);
        }
        if ($this->isColumnModified(EventTableMap::COL_NAME)) {
            $criteria->add(EventTableMap::COL_NAME, $this->name);
        }
        if ($this->isColumnModified(EventTableMap::COL_CREATEDBY)) {
            $criteria->add(EventTableMap::COL_CREATEDBY, $this->createdby);
        }
        if ($this->isColumnModified(EventTableMap::COL_REHEARSALDATE)) {
            $criteria->add(EventTableMap::COL_REHEARSALDATE, $this->rehearsaldate);
        }
        if ($this->isColumnModified(EventTableMap::COL_TYPE)) {
            $criteria->add(EventTableMap::COL_TYPE, $this->type);
        }
        if ($this->isColumnModified(EventTableMap::COL_SUBTYPE)) {
            $criteria->add(EventTableMap::COL_SUBTYPE, $this->subtype);
        }
        if ($this->isColumnModified(EventTableMap::COL_LOCATION)) {
            $criteria->add(EventTableMap::COL_LOCATION, $this->location);
        }
        if ($this->isColumnModified(EventTableMap::COL_NOTIFIED)) {
            $criteria->add(EventTableMap::COL_NOTIFIED, $this->notified);
        }
        if ($this->isColumnModified(EventTableMap::COL_REHEARSAL)) {
            $criteria->add(EventTableMap::COL_REHEARSAL, $this->rehearsal);
        }
        if ($this->isColumnModified(EventTableMap::COL_REMOVED)) {
            $criteria->add(EventTableMap::COL_REMOVED, $this->removed);
        }
        if ($this->isColumnModified(EventTableMap::COL_EVENTGROUP)) {
            $criteria->add(EventTableMap::COL_EVENTGROUP, $this->eventgroup);
        }
        if ($this->isColumnModified(EventTableMap::COL_SERMONTITLE)) {
            $criteria->add(EventTableMap::COL_SERMONTITLE, $this->sermontitle);
        }
        if ($this->isColumnModified(EventTableMap::COL_BIBLEVERSE)) {
            $criteria->add(EventTableMap::COL_BIBLEVERSE, $this->bibleverse);
        }
        if ($this->isColumnModified(EventTableMap::COL_CREATED)) {
            $criteria->add(EventTableMap::COL_CREATED, $this->created);
        }
        if ($this->isColumnModified(EventTableMap::COL_UPDATED)) {
            $criteria->add(EventTableMap::COL_UPDATED, $this->updated);
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
        $criteria = ChildEventQuery::create();
        $criteria->add(EventTableMap::COL_ID, $this->id);

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
     * @param      object $copyObj An object of \TechWilk\Rota\Event (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setDate($this->getDate());
        $copyObj->setName($this->getName());
        $copyObj->setCreatedBy($this->getCreatedBy());
        $copyObj->setRehearsalDate($this->getRehearsalDate());
        $copyObj->setEventTypeId($this->getEventTypeId());
        $copyObj->setEventSubTypeId($this->getEventSubTypeId());
        $copyObj->setLocationId($this->getLocationId());
        $copyObj->setNotified($this->getNotified());
        $copyObj->setRehearsal($this->getRehearsal());
        $copyObj->setRemoved($this->getRemoved());
        $copyObj->setEventGroupId($this->getEventGroupId());
        $copyObj->setSermonTitle($this->getSermonTitle());
        $copyObj->setBibleVerse($this->getBibleVerse());
        $copyObj->setCreated($this->getCreated());
        $copyObj->setUpdated($this->getUpdated());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getComments() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addComment($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getEventpeople() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addEventPerson($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getAvailabilities() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addAvailability($relObj->copy($deepCopy));
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
     * @return \TechWilk\Rota\Event Clone of current object.
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
     * @return $this|\TechWilk\Rota\Event The current object (for fluent API support)
     * @throws PropelException
     */
    public function setUser(ChildUser $v = null)
    {
        if ($v === null) {
            $this->setCreatedBy(0);
        } else {
            $this->setCreatedBy($v->getId());
        }

        $this->aUser = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildUser object, it will not be re-added.
        if ($v !== null) {
            $v->addEvent($this);
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
        if ($this->aUser === null && ($this->createdby != 0)) {
            $this->aUser = ChildUserQuery::create()->findPk($this->createdby, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aUser->addEvents($this);
             */
        }

        return $this->aUser;
    }

    /**
     * Declares an association between this object and a ChildEventType object.
     *
     * @param  ChildEventType $v
     * @return $this|\TechWilk\Rota\Event The current object (for fluent API support)
     * @throws PropelException
     */
    public function setEventType(ChildEventType $v = null)
    {
        if ($v === null) {
            $this->setEventTypeId(0);
        } else {
            $this->setEventTypeId($v->getId());
        }

        $this->aEventType = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildEventType object, it will not be re-added.
        if ($v !== null) {
            $v->addEvent($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildEventType object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildEventType The associated ChildEventType object.
     * @throws PropelException
     */
    public function getEventType(ConnectionInterface $con = null)
    {
        if ($this->aEventType === null && ($this->type != 0)) {
            $this->aEventType = ChildEventTypeQuery::create()->findPk($this->type, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aEventType->addEvents($this);
             */
        }

        return $this->aEventType;
    }

    /**
     * Declares an association between this object and a ChildEventSubType object.
     *
     * @param  ChildEventSubType $v
     * @return $this|\TechWilk\Rota\Event The current object (for fluent API support)
     * @throws PropelException
     */
    public function setEventSubType(ChildEventSubType $v = null)
    {
        if ($v === null) {
            $this->setEventSubTypeId(0);
        } else {
            $this->setEventSubTypeId($v->getId());
        }

        $this->aEventSubType = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildEventSubType object, it will not be re-added.
        if ($v !== null) {
            $v->addEvent($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildEventSubType object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildEventSubType The associated ChildEventSubType object.
     * @throws PropelException
     */
    public function getEventSubType(ConnectionInterface $con = null)
    {
        if ($this->aEventSubType === null && ($this->subtype != 0)) {
            $this->aEventSubType = ChildEventSubTypeQuery::create()->findPk($this->subtype, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aEventSubType->addEvents($this);
             */
        }

        return $this->aEventSubType;
    }

    /**
     * Declares an association between this object and a ChildLocation object.
     *
     * @param  ChildLocation $v
     * @return $this|\TechWilk\Rota\Event The current object (for fluent API support)
     * @throws PropelException
     */
    public function setLocation(ChildLocation $v = null)
    {
        if ($v === null) {
            $this->setLocationId(0);
        } else {
            $this->setLocationId($v->getId());
        }

        $this->aLocation = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildLocation object, it will not be re-added.
        if ($v !== null) {
            $v->addEvent($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildLocation object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildLocation The associated ChildLocation object.
     * @throws PropelException
     */
    public function getLocation(ConnectionInterface $con = null)
    {
        if ($this->aLocation === null && ($this->location != 0)) {
            $this->aLocation = ChildLocationQuery::create()->findPk($this->location, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aLocation->addEvents($this);
             */
        }

        return $this->aLocation;
    }

    /**
     * Declares an association between this object and a ChildEventGroup object.
     *
     * @param  ChildEventGroup $v
     * @return $this|\TechWilk\Rota\Event The current object (for fluent API support)
     * @throws PropelException
     */
    public function setEventGroup(ChildEventGroup $v = null)
    {
        if ($v === null) {
            $this->setEventGroupId(null);
        } else {
            $this->setEventGroupId($v->getId());
        }

        $this->aEventGroup = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildEventGroup object, it will not be re-added.
        if ($v !== null) {
            $v->addEvent($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildEventGroup object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildEventGroup The associated ChildEventGroup object.
     * @throws PropelException
     */
    public function getEventGroup(ConnectionInterface $con = null)
    {
        if ($this->aEventGroup === null && ($this->eventgroup != 0)) {
            $this->aEventGroup = ChildEventGroupQuery::create()->findPk($this->eventgroup, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aEventGroup->addEvents($this);
             */
        }

        return $this->aEventGroup;
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
        if ('Comment' == $relationName) {
            $this->initComments();
            return;
        }
        if ('EventPerson' == $relationName) {
            $this->initEventpeople();
            return;
        }
        if ('Availability' == $relationName) {
            $this->initAvailabilities();
            return;
        }
    }

    /**
     * Clears out the collComments collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addComments()
     */
    public function clearComments()
    {
        $this->collComments = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collComments collection loaded partially.
     */
    public function resetPartialComments($v = true)
    {
        $this->collCommentsPartial = $v;
    }

    /**
     * Initializes the collComments collection.
     *
     * By default this just sets the collComments collection to an empty array (like clearcollComments());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initComments($overrideExisting = true)
    {
        if (null !== $this->collComments && !$overrideExisting) {
            return;
        }

        $collectionClassName = CommentTableMap::getTableMap()->getCollectionClassName();

        $this->collComments = new $collectionClassName;
        $this->collComments->setModel('\TechWilk\Rota\Comment');
    }

    /**
     * Gets an array of ChildComment objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildEvent is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildComment[] List of ChildComment objects
     * @throws PropelException
     */
    public function getComments(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collCommentsPartial && !$this->isNew();
        if (null === $this->collComments || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collComments) {
                // return empty collection
                $this->initComments();
            } else {
                $collComments = ChildCommentQuery::create(null, $criteria)
                    ->filterByEvent($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collCommentsPartial && count($collComments)) {
                        $this->initComments(false);

                        foreach ($collComments as $obj) {
                            if (false == $this->collComments->contains($obj)) {
                                $this->collComments->append($obj);
                            }
                        }

                        $this->collCommentsPartial = true;
                    }

                    return $collComments;
                }

                if ($partial && $this->collComments) {
                    foreach ($this->collComments as $obj) {
                        if ($obj->isNew()) {
                            $collComments[] = $obj;
                        }
                    }
                }

                $this->collComments = $collComments;
                $this->collCommentsPartial = false;
            }
        }

        return $this->collComments;
    }

    /**
     * Sets a collection of ChildComment objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $comments A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildEvent The current object (for fluent API support)
     */
    public function setComments(Collection $comments, ConnectionInterface $con = null)
    {
        /** @var ChildComment[] $commentsToDelete */
        $commentsToDelete = $this->getComments(new Criteria(), $con)->diff($comments);


        $this->commentsScheduledForDeletion = $commentsToDelete;

        foreach ($commentsToDelete as $commentRemoved) {
            $commentRemoved->setEvent(null);
        }

        $this->collComments = null;
        foreach ($comments as $comment) {
            $this->addComment($comment);
        }

        $this->collComments = $comments;
        $this->collCommentsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Comment objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Comment objects.
     * @throws PropelException
     */
    public function countComments(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collCommentsPartial && !$this->isNew();
        if (null === $this->collComments || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collComments) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getComments());
            }

            $query = ChildCommentQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByEvent($this)
                ->count($con);
        }

        return count($this->collComments);
    }

    /**
     * Method called to associate a ChildComment object to this object
     * through the ChildComment foreign key attribute.
     *
     * @param  ChildComment $l ChildComment
     * @return $this|\TechWilk\Rota\Event The current object (for fluent API support)
     */
    public function addComment(ChildComment $l)
    {
        if ($this->collComments === null) {
            $this->initComments();
            $this->collCommentsPartial = true;
        }

        if (!$this->collComments->contains($l)) {
            $this->doAddComment($l);

            if ($this->commentsScheduledForDeletion and $this->commentsScheduledForDeletion->contains($l)) {
                $this->commentsScheduledForDeletion->remove($this->commentsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildComment $comment The ChildComment object to add.
     */
    protected function doAddComment(ChildComment $comment)
    {
        $this->collComments[]= $comment;
        $comment->setEvent($this);
    }

    /**
     * @param  ChildComment $comment The ChildComment object to remove.
     * @return $this|ChildEvent The current object (for fluent API support)
     */
    public function removeComment(ChildComment $comment)
    {
        if ($this->getComments()->contains($comment)) {
            $pos = $this->collComments->search($comment);
            $this->collComments->remove($pos);
            if (null === $this->commentsScheduledForDeletion) {
                $this->commentsScheduledForDeletion = clone $this->collComments;
                $this->commentsScheduledForDeletion->clear();
            }
            $this->commentsScheduledForDeletion[]= clone $comment;
            $comment->setEvent(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Event is new, it will return
     * an empty collection; or if this Event has previously
     * been saved, it will retrieve related Comments from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Event.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildComment[] List of ChildComment objects
     */
    public function getCommentsJoinUser(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildCommentQuery::create(null, $criteria);
        $query->joinWith('User', $joinBehavior);

        return $this->getComments($query, $con);
    }

    /**
     * Clears out the collEventpeople collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addEventpeople()
     */
    public function clearEventpeople()
    {
        $this->collEventpeople = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collEventpeople collection loaded partially.
     */
    public function resetPartialEventpeople($v = true)
    {
        $this->collEventpeoplePartial = $v;
    }

    /**
     * Initializes the collEventpeople collection.
     *
     * By default this just sets the collEventpeople collection to an empty array (like clearcollEventpeople());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initEventpeople($overrideExisting = true)
    {
        if (null !== $this->collEventpeople && !$overrideExisting) {
            return;
        }

        $collectionClassName = EventPersonTableMap::getTableMap()->getCollectionClassName();

        $this->collEventpeople = new $collectionClassName;
        $this->collEventpeople->setModel('\TechWilk\Rota\EventPerson');
    }

    /**
     * Gets an array of ChildEventPerson objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildEvent is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildEventPerson[] List of ChildEventPerson objects
     * @throws PropelException
     */
    public function getEventpeople(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collEventpeoplePartial && !$this->isNew();
        if (null === $this->collEventpeople || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collEventpeople) {
                // return empty collection
                $this->initEventpeople();
            } else {
                $collEventpeople = ChildEventPersonQuery::create(null, $criteria)
                    ->filterByEvent($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collEventpeoplePartial && count($collEventpeople)) {
                        $this->initEventpeople(false);

                        foreach ($collEventpeople as $obj) {
                            if (false == $this->collEventpeople->contains($obj)) {
                                $this->collEventpeople->append($obj);
                            }
                        }

                        $this->collEventpeoplePartial = true;
                    }

                    return $collEventpeople;
                }

                if ($partial && $this->collEventpeople) {
                    foreach ($this->collEventpeople as $obj) {
                        if ($obj->isNew()) {
                            $collEventpeople[] = $obj;
                        }
                    }
                }

                $this->collEventpeople = $collEventpeople;
                $this->collEventpeoplePartial = false;
            }
        }

        return $this->collEventpeople;
    }

    /**
     * Sets a collection of ChildEventPerson objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $eventpeople A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildEvent The current object (for fluent API support)
     */
    public function setEventpeople(Collection $eventpeople, ConnectionInterface $con = null)
    {
        /** @var ChildEventPerson[] $eventpeopleToDelete */
        $eventpeopleToDelete = $this->getEventpeople(new Criteria(), $con)->diff($eventpeople);


        $this->eventpeopleScheduledForDeletion = $eventpeopleToDelete;

        foreach ($eventpeopleToDelete as $eventPersonRemoved) {
            $eventPersonRemoved->setEvent(null);
        }

        $this->collEventpeople = null;
        foreach ($eventpeople as $eventPerson) {
            $this->addEventPerson($eventPerson);
        }

        $this->collEventpeople = $eventpeople;
        $this->collEventpeoplePartial = false;

        return $this;
    }

    /**
     * Returns the number of related EventPerson objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related EventPerson objects.
     * @throws PropelException
     */
    public function countEventpeople(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collEventpeoplePartial && !$this->isNew();
        if (null === $this->collEventpeople || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collEventpeople) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getEventpeople());
            }

            $query = ChildEventPersonQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByEvent($this)
                ->count($con);
        }

        return count($this->collEventpeople);
    }

    /**
     * Method called to associate a ChildEventPerson object to this object
     * through the ChildEventPerson foreign key attribute.
     *
     * @param  ChildEventPerson $l ChildEventPerson
     * @return $this|\TechWilk\Rota\Event The current object (for fluent API support)
     */
    public function addEventPerson(ChildEventPerson $l)
    {
        if ($this->collEventpeople === null) {
            $this->initEventpeople();
            $this->collEventpeoplePartial = true;
        }

        if (!$this->collEventpeople->contains($l)) {
            $this->doAddEventPerson($l);

            if ($this->eventpeopleScheduledForDeletion and $this->eventpeopleScheduledForDeletion->contains($l)) {
                $this->eventpeopleScheduledForDeletion->remove($this->eventpeopleScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildEventPerson $eventPerson The ChildEventPerson object to add.
     */
    protected function doAddEventPerson(ChildEventPerson $eventPerson)
    {
        $this->collEventpeople[]= $eventPerson;
        $eventPerson->setEvent($this);
    }

    /**
     * @param  ChildEventPerson $eventPerson The ChildEventPerson object to remove.
     * @return $this|ChildEvent The current object (for fluent API support)
     */
    public function removeEventPerson(ChildEventPerson $eventPerson)
    {
        if ($this->getEventpeople()->contains($eventPerson)) {
            $pos = $this->collEventpeople->search($eventPerson);
            $this->collEventpeople->remove($pos);
            if (null === $this->eventpeopleScheduledForDeletion) {
                $this->eventpeopleScheduledForDeletion = clone $this->collEventpeople;
                $this->eventpeopleScheduledForDeletion->clear();
            }
            $this->eventpeopleScheduledForDeletion[]= clone $eventPerson;
            $eventPerson->setEvent(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Event is new, it will return
     * an empty collection; or if this Event has previously
     * been saved, it will retrieve related Eventpeople from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Event.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildEventPerson[] List of ChildEventPerson objects
     */
    public function getEventpeopleJoinUserRole(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildEventPersonQuery::create(null, $criteria);
        $query->joinWith('UserRole', $joinBehavior);

        return $this->getEventpeople($query, $con);
    }

    /**
     * Clears out the collAvailabilities collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addAvailabilities()
     */
    public function clearAvailabilities()
    {
        $this->collAvailabilities = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collAvailabilities collection loaded partially.
     */
    public function resetPartialAvailabilities($v = true)
    {
        $this->collAvailabilitiesPartial = $v;
    }

    /**
     * Initializes the collAvailabilities collection.
     *
     * By default this just sets the collAvailabilities collection to an empty array (like clearcollAvailabilities());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initAvailabilities($overrideExisting = true)
    {
        if (null !== $this->collAvailabilities && !$overrideExisting) {
            return;
        }

        $collectionClassName = AvailabilityTableMap::getTableMap()->getCollectionClassName();

        $this->collAvailabilities = new $collectionClassName;
        $this->collAvailabilities->setModel('\TechWilk\Rota\Availability');
    }

    /**
     * Gets an array of ChildAvailability objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildEvent is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildAvailability[] List of ChildAvailability objects
     * @throws PropelException
     */
    public function getAvailabilities(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collAvailabilitiesPartial && !$this->isNew();
        if (null === $this->collAvailabilities || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collAvailabilities) {
                // return empty collection
                $this->initAvailabilities();
            } else {
                $collAvailabilities = ChildAvailabilityQuery::create(null, $criteria)
                    ->filterByEvent($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collAvailabilitiesPartial && count($collAvailabilities)) {
                        $this->initAvailabilities(false);

                        foreach ($collAvailabilities as $obj) {
                            if (false == $this->collAvailabilities->contains($obj)) {
                                $this->collAvailabilities->append($obj);
                            }
                        }

                        $this->collAvailabilitiesPartial = true;
                    }

                    return $collAvailabilities;
                }

                if ($partial && $this->collAvailabilities) {
                    foreach ($this->collAvailabilities as $obj) {
                        if ($obj->isNew()) {
                            $collAvailabilities[] = $obj;
                        }
                    }
                }

                $this->collAvailabilities = $collAvailabilities;
                $this->collAvailabilitiesPartial = false;
            }
        }

        return $this->collAvailabilities;
    }

    /**
     * Sets a collection of ChildAvailability objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $availabilities A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildEvent The current object (for fluent API support)
     */
    public function setAvailabilities(Collection $availabilities, ConnectionInterface $con = null)
    {
        /** @var ChildAvailability[] $availabilitiesToDelete */
        $availabilitiesToDelete = $this->getAvailabilities(new Criteria(), $con)->diff($availabilities);


        $this->availabilitiesScheduledForDeletion = $availabilitiesToDelete;

        foreach ($availabilitiesToDelete as $availabilityRemoved) {
            $availabilityRemoved->setEvent(null);
        }

        $this->collAvailabilities = null;
        foreach ($availabilities as $availability) {
            $this->addAvailability($availability);
        }

        $this->collAvailabilities = $availabilities;
        $this->collAvailabilitiesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Availability objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Availability objects.
     * @throws PropelException
     */
    public function countAvailabilities(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collAvailabilitiesPartial && !$this->isNew();
        if (null === $this->collAvailabilities || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collAvailabilities) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getAvailabilities());
            }

            $query = ChildAvailabilityQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByEvent($this)
                ->count($con);
        }

        return count($this->collAvailabilities);
    }

    /**
     * Method called to associate a ChildAvailability object to this object
     * through the ChildAvailability foreign key attribute.
     *
     * @param  ChildAvailability $l ChildAvailability
     * @return $this|\TechWilk\Rota\Event The current object (for fluent API support)
     */
    public function addAvailability(ChildAvailability $l)
    {
        if ($this->collAvailabilities === null) {
            $this->initAvailabilities();
            $this->collAvailabilitiesPartial = true;
        }

        if (!$this->collAvailabilities->contains($l)) {
            $this->doAddAvailability($l);

            if ($this->availabilitiesScheduledForDeletion and $this->availabilitiesScheduledForDeletion->contains($l)) {
                $this->availabilitiesScheduledForDeletion->remove($this->availabilitiesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildAvailability $availability The ChildAvailability object to add.
     */
    protected function doAddAvailability(ChildAvailability $availability)
    {
        $this->collAvailabilities[]= $availability;
        $availability->setEvent($this);
    }

    /**
     * @param  ChildAvailability $availability The ChildAvailability object to remove.
     * @return $this|ChildEvent The current object (for fluent API support)
     */
    public function removeAvailability(ChildAvailability $availability)
    {
        if ($this->getAvailabilities()->contains($availability)) {
            $pos = $this->collAvailabilities->search($availability);
            $this->collAvailabilities->remove($pos);
            if (null === $this->availabilitiesScheduledForDeletion) {
                $this->availabilitiesScheduledForDeletion = clone $this->collAvailabilities;
                $this->availabilitiesScheduledForDeletion->clear();
            }
            $this->availabilitiesScheduledForDeletion[]= clone $availability;
            $availability->setEvent(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Event is new, it will return
     * an empty collection; or if this Event has previously
     * been saved, it will retrieve related Availabilities from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Event.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildAvailability[] List of ChildAvailability objects
     */
    public function getAvailabilitiesJoinUser(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildAvailabilityQuery::create(null, $criteria);
        $query->joinWith('User', $joinBehavior);

        return $this->getAvailabilities($query, $con);
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        if (null !== $this->aUser) {
            $this->aUser->removeEvent($this);
        }
        if (null !== $this->aEventType) {
            $this->aEventType->removeEvent($this);
        }
        if (null !== $this->aEventSubType) {
            $this->aEventSubType->removeEvent($this);
        }
        if (null !== $this->aLocation) {
            $this->aLocation->removeEvent($this);
        }
        if (null !== $this->aEventGroup) {
            $this->aEventGroup->removeEvent($this);
        }
        $this->id = null;
        $this->date = null;
        $this->name = null;
        $this->createdby = null;
        $this->rehearsaldate = null;
        $this->type = null;
        $this->subtype = null;
        $this->location = null;
        $this->notified = null;
        $this->rehearsal = null;
        $this->removed = null;
        $this->eventgroup = null;
        $this->sermontitle = null;
        $this->bibleverse = null;
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
            if ($this->collComments) {
                foreach ($this->collComments as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collEventpeople) {
                foreach ($this->collEventpeople as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collAvailabilities) {
                foreach ($this->collAvailabilities as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collComments = null;
        $this->collEventpeople = null;
        $this->collAvailabilities = null;
        $this->aUser = null;
        $this->aEventType = null;
        $this->aEventSubType = null;
        $this->aLocation = null;
        $this->aEventGroup = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(EventTableMap::DEFAULT_STRING_FORMAT);
    }

    // timestampable behavior

    /**
     * Mark the current object so that the update date doesn't get updated during next save
     *
     * @return     $this|ChildEvent The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[EventTableMap::COL_UPDATED] = true;

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
