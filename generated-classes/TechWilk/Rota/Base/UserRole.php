<?php

namespace TechWilk\Rota\Base;

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
use TechWilk\Rota\EventPerson as ChildEventPerson;
use TechWilk\Rota\EventPersonQuery as ChildEventPersonQuery;
use TechWilk\Rota\Role as ChildRole;
use TechWilk\Rota\RoleQuery as ChildRoleQuery;
use TechWilk\Rota\Swap as ChildSwap;
use TechWilk\Rota\SwapQuery as ChildSwapQuery;
use TechWilk\Rota\User as ChildUser;
use TechWilk\Rota\UserQuery as ChildUserQuery;
use TechWilk\Rota\UserRole as ChildUserRole;
use TechWilk\Rota\UserRoleQuery as ChildUserRoleQuery;
use TechWilk\Rota\Map\EventPersonTableMap;
use TechWilk\Rota\Map\SwapTableMap;
use TechWilk\Rota\Map\UserRoleTableMap;

/**
 * Base class that represents a row from the 'userRoles' table.
 *
 *
 *
 * @package    propel.generator.TechWilk.Rota.Base
 */
abstract class UserRole implements ActiveRecordInterface
{
    /**
     * TableMap class name
     *
     * @var string
     */
    public const TABLE_MAP = '\\TechWilk\\Rota\\Map\\UserRoleTableMap';


    /**
     * attribute to determine if this object has previously been saved.
     * @var bool
     */
    protected $new = true;

    /**
     * attribute to determine whether this object has been deleted.
     * @var bool
     */
    protected $deleted = false;

    /**
     * The columns that have been modified in current object.
     * Tracking modified columns allows us to only update modified columns.
     * @var array
     */
    protected $modifiedColumns = [];

    /**
     * The (virtual) columns that are added at runtime
     * The formatters can add supplementary columns based on a resultset
     * @var array
     */
    protected $virtualColumns = [];

    /**
     * The value for the id field.
     *
     * @var        int
     */
    protected $id;

    /**
     * The value for the userid field.
     *
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $userid;

    /**
     * The value for the roleid field.
     *
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $roleid;

    /**
     * The value for the reserve field.
     *
     * Note: this column has a database default value of: false
     * @var        boolean
     */
    protected $reserve;

    /**
     * @var        ChildUser
     */
    protected $aUser;

    /**
     * @var        ChildRole
     */
    protected $aRole;

    /**
     * @var        ObjectCollection|ChildEventPerson[] Collection to store aggregation of ChildEventPerson objects.
     * @phpstan-var ObjectCollection&\Traversable<ChildEventPerson> Collection to store aggregation of ChildEventPerson objects.
     */
    protected $collEventpeople;
    protected $collEventpeoplePartial;

    /**
     * @var        ObjectCollection|ChildSwap[] Collection to store aggregation of ChildSwap objects.
     * @phpstan-var ObjectCollection&\Traversable<ChildSwap> Collection to store aggregation of ChildSwap objects.
     */
    protected $collSwapsRelatedByOldUserRoleId;
    protected $collSwapsRelatedByOldUserRoleIdPartial;

    /**
     * @var        ObjectCollection|ChildSwap[] Collection to store aggregation of ChildSwap objects.
     * @phpstan-var ObjectCollection&\Traversable<ChildSwap> Collection to store aggregation of ChildSwap objects.
     */
    protected $collSwapsRelatedByNewUserRoleId;
    protected $collSwapsRelatedByNewUserRoleIdPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var bool
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildEventPerson[]
     * @phpstan-var ObjectCollection&\Traversable<ChildEventPerson>
     */
    protected $eventpeopleScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildSwap[]
     * @phpstan-var ObjectCollection&\Traversable<ChildSwap>
     */
    protected $swapsRelatedByOldUserRoleIdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildSwap[]
     * @phpstan-var ObjectCollection&\Traversable<ChildSwap>
     */
    protected $swapsRelatedByNewUserRoleIdScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see __construct()
     */
    public function applyDefaultValues(): void
    {
        $this->userid = 0;
        $this->roleid = 0;
        $this->reserve = false;
    }

    /**
     * Initializes internal state of TechWilk\Rota\Base\UserRole object.
     * @see applyDefaults()
     */
    public function __construct()
    {
        $this->applyDefaultValues();
    }

    /**
     * Returns whether the object has been modified.
     *
     * @return bool True if the object has been modified.
     */
    public function isModified(): bool
    {
        return !!$this->modifiedColumns;
    }

    /**
     * Has specified column been modified?
     *
     * @param string $col column fully qualified name (TableMap::TYPE_COLNAME), e.g. Book::AUTHOR_ID
     * @return bool True if $col has been modified.
     */
    public function isColumnModified(string $col): bool
    {
        return $this->modifiedColumns && isset($this->modifiedColumns[$col]);
    }

    /**
     * Get the columns that have been modified in this object.
     * @return array A unique list of the modified column names for this object.
     */
    public function getModifiedColumns(): array
    {
        return $this->modifiedColumns ? array_keys($this->modifiedColumns) : [];
    }

    /**
     * Returns whether the object has ever been saved.  This will
     * be false, if the object was retrieved from storage or was created
     * and then saved.
     *
     * @return bool True, if the object has never been persisted.
     */
    public function isNew(): bool
    {
        return $this->new;
    }

    /**
     * Setter for the isNew attribute.  This method will be called
     * by Propel-generated children and objects.
     *
     * @param bool $b the state of the object.
     */
    public function setNew(bool $b): void
    {
        $this->new = $b;
    }

    /**
     * Whether this object has been deleted.
     * @return bool The deleted state of this object.
     */
    public function isDeleted(): bool
    {
        return $this->deleted;
    }

    /**
     * Specify whether this object has been deleted.
     * @param bool $b The deleted state of this object.
     * @return void
     */
    public function setDeleted(bool $b): void
    {
        $this->deleted = $b;
    }

    /**
     * Sets the modified state for the object to be false.
     * @param string $col If supplied, only the specified column is reset.
     * @return void
     */
    public function resetModified(?string $col = null): void
    {
        if (null !== $col) {
            unset($this->modifiedColumns[$col]);
        } else {
            $this->modifiedColumns = [];
        }
    }

    /**
     * Compares this with another <code>UserRole</code> instance.  If
     * <code>obj</code> is an instance of <code>UserRole</code>, delegates to
     * <code>equals(UserRole)</code>.  Otherwise, returns <code>false</code>.
     *
     * @param mixed $obj The object to compare to.
     * @return bool Whether equal to the object specified.
     */
    public function equals($obj): bool
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
    public function getVirtualColumns(): array
    {
        return $this->virtualColumns;
    }

    /**
     * Checks the existence of a virtual column in this object
     *
     * @param string $name The virtual column name
     * @return bool
     */
    public function hasVirtualColumn(string $name): bool
    {
        return array_key_exists($name, $this->virtualColumns);
    }

    /**
     * Get the value of a virtual column in this object
     *
     * @param string $name The virtual column name
     * @return mixed
     *
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getVirtualColumn(string $name)
    {
        if (!$this->hasVirtualColumn($name)) {
            throw new PropelException(sprintf('Cannot get value of nonexistent virtual column `%s`.', $name));
        }

        return $this->virtualColumns[$name];
    }

    /**
     * Set the value of a virtual column in this object
     *
     * @param string $name The virtual column name
     * @param mixed $value The value to give to the virtual column
     *
     * @return $this The current object, for fluid interface
     */
    public function setVirtualColumn(string $name, $value)
    {
        $this->virtualColumns[$name] = $value;

        return $this;
    }

    /**
     * Logs a message using Propel::log().
     *
     * @param string $msg
     * @param int $priority One of the Propel::LOG_* logging levels
     * @return void
     */
    protected function log(string $msg, int $priority = Propel::LOG_INFO): void
    {
        Propel::log(get_class($this) . ': ' . $msg, $priority);
    }

    /**
     * Export the current object properties to a string, using a given parser format
     * <code>
     * $book = BookQuery::create()->findPk(9012);
     * echo $book->exportTo('JSON');
     *  => {"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * @param \Propel\Runtime\Parser\AbstractParser|string $parser An AbstractParser instance, or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param bool $includeLazyLoadColumns (optional) Whether to include lazy load(ed) columns. Defaults to TRUE.
     * @param string $keyType (optional) One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME, TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM. Defaults to TableMap::TYPE_PHPNAME.
     * @return string The exported data
     */
    public function exportTo($parser, bool $includeLazyLoadColumns = true, string $keyType = TableMap::TYPE_PHPNAME): string
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        return $parser->fromArray($this->toArray($keyType, $includeLazyLoadColumns, array(), true));
    }

    /**
     * Clean up internal collections prior to serializing
     * Avoids recursive loops that turn into segmentation faults when serializing
     *
     * @return array<string>
     */
    public function __sleep(): array
    {
        $this->clearAllReferences();

        $cls = new \ReflectionClass($this);
        $propertyNames = [];
        $serializableProperties = array_diff($cls->getProperties(), $cls->getProperties(\ReflectionProperty::IS_STATIC));

        foreach($serializableProperties as $property) {
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
     * Get the [userid] column value.
     *
     * @return int
     */
    public function getUserId()
    {
        return $this->userid;
    }

    /**
     * Get the [roleid] column value.
     *
     * @return int
     */
    public function getRoleId()
    {
        return $this->roleid;
    }

    /**
     * Get the [reserve] column value.
     *
     * @return boolean
     */
    public function getReserve()
    {
        return $this->reserve;
    }

    /**
     * Get the [reserve] column value.
     *
     * @return boolean
     */
    public function isReserve()
    {
        return $this->getReserve();
    }

    /**
     * Set the value of [id] column.
     *
     * @param int $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[UserRoleTableMap::COL_ID] = true;
        }

        return $this;
    }

    /**
     * Set the value of [userid] column.
     *
     * @param int $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setUserId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->userid !== $v) {
            $this->userid = $v;
            $this->modifiedColumns[UserRoleTableMap::COL_USERID] = true;
        }

        if ($this->aUser !== null && $this->aUser->getId() !== $v) {
            $this->aUser = null;
        }

        return $this;
    }

    /**
     * Set the value of [roleid] column.
     *
     * @param int $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setRoleId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->roleid !== $v) {
            $this->roleid = $v;
            $this->modifiedColumns[UserRoleTableMap::COL_ROLEID] = true;
        }

        if ($this->aRole !== null && $this->aRole->getId() !== $v) {
            $this->aRole = null;
        }

        return $this;
    }

    /**
     * Sets the value of the [reserve] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param bool|integer|string $v The new value
     * @return $this The current object (for fluent API support)
     */
    public function setReserve($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->reserve !== $v) {
            $this->reserve = $v;
            $this->modifiedColumns[UserRoleTableMap::COL_RESERVE] = true;
        }

        return $this;
    }

    /**
     * Indicates whether the columns in this object are only set to default values.
     *
     * This method can be used in conjunction with isModified() to indicate whether an object is both
     * modified _and_ has some values set which are non-default.
     *
     * @return bool Whether the columns in this object are only been set with default values.
     */
    public function hasOnlyDefaultValues(): bool
    {
            if ($this->userid !== 0) {
                return false;
            }

            if ($this->roleid !== 0) {
                return false;
            }

            if ($this->reserve !== false) {
                return false;
            }

        // otherwise, everything was equal, so return TRUE
        return true;
    }

    /**
     * Hydrates (populates) the object variables with values from the database resultset.
     *
     * An offset (0-based "start column") is specified so that objects can be hydrated
     * with a subset of the columns in the resultset rows.  This is needed, for example,
     * for results of JOIN queries where the resultset row includes columns from two or
     * more tables.
     *
     * @param array $row The row returned by DataFetcher->fetch().
     * @param int $startcol 0-based offset column which indicates which resultset column to start with.
     * @param bool $rehydrate Whether this object is being re-hydrated from the database.
     * @param string $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                  One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                            TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @return int next starting column
     * @throws \Propel\Runtime\Exception\PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate(array $row, int $startcol = 0, bool $rehydrate = false, string $indexType = TableMap::TYPE_NUM): int
    {
        try {

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : UserRoleTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : UserRoleTableMap::translateFieldName('UserId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->userid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : UserRoleTableMap::translateFieldName('RoleId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->roleid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : UserRoleTableMap::translateFieldName('Reserve', TableMap::TYPE_PHPNAME, $indexType)];
            $this->reserve = (null !== $col) ? (boolean) $col : null;

            $this->resetModified();
            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 4; // 4 = UserRoleTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\TechWilk\\Rota\\UserRole'), 0, $e);
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
     * @throws \Propel\Runtime\Exception\PropelException
     * @return void
     */
    public function ensureConsistency(): void
    {
        if ($this->aUser !== null && $this->userid !== $this->aUser->getId()) {
            $this->aUser = null;
        }
        if ($this->aRole !== null && $this->roleid !== $this->aRole->getId()) {
            $this->aRole = null;
        }
    }

    /**
     * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
     *
     * This will only work if the object has been saved and has a valid primary key set.
     *
     * @param bool $deep (optional) Whether to also de-associated any related objects.
     * @param ConnectionInterface $con (optional) The ConnectionInterface connection to use.
     * @return void
     * @throws \Propel\Runtime\Exception\PropelException - if this object is deleted, unsaved or doesn't have pk match in db
     */
    public function reload(bool $deep = false, ?ConnectionInterface $con = null): void
    {
        if ($this->isDeleted()) {
            throw new PropelException("Cannot reload a deleted object.");
        }

        if ($this->isNew()) {
            throw new PropelException("Cannot reload an unsaved object.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(UserRoleTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildUserRoleQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aUser = null;
            $this->aRole = null;
            $this->collEventpeople = null;

            $this->collSwapsRelatedByOldUserRoleId = null;

            $this->collSwapsRelatedByNewUserRoleId = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param ConnectionInterface $con
     * @return void
     * @throws \Propel\Runtime\Exception\PropelException
     * @see UserRole::setDeleted()
     * @see UserRole::isDeleted()
     */
    public function delete(?ConnectionInterface $con = null): void
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(UserRoleTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildUserRoleQuery::create()
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
     * @param ConnectionInterface $con
     * @return int The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws \Propel\Runtime\Exception\PropelException
     * @see doSave()
     */
    public function save(?ConnectionInterface $con = null): int
    {
        if ($this->isDeleted()) {
            throw new PropelException("You cannot save an object that has been deleted.");
        }

        if ($this->alreadyInSave) {
            return 0;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(UserRoleTableMap::DATABASE_NAME);
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
                UserRoleTableMap::addInstanceToPool($this);
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
     * @param ConnectionInterface $con
     * @return int The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws \Propel\Runtime\Exception\PropelException
     * @see save()
     */
    protected function doSave(ConnectionInterface $con): int
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

            if ($this->aRole !== null) {
                if ($this->aRole->isModified() || $this->aRole->isNew()) {
                    $affectedRows += $this->aRole->save($con);
                }
                $this->setRole($this->aRole);
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

            if ($this->swapsRelatedByOldUserRoleIdScheduledForDeletion !== null) {
                if (!$this->swapsRelatedByOldUserRoleIdScheduledForDeletion->isEmpty()) {
                    \TechWilk\Rota\SwapQuery::create()
                        ->filterByPrimaryKeys($this->swapsRelatedByOldUserRoleIdScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->swapsRelatedByOldUserRoleIdScheduledForDeletion = null;
                }
            }

            if ($this->collSwapsRelatedByOldUserRoleId !== null) {
                foreach ($this->collSwapsRelatedByOldUserRoleId as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->swapsRelatedByNewUserRoleIdScheduledForDeletion !== null) {
                if (!$this->swapsRelatedByNewUserRoleIdScheduledForDeletion->isEmpty()) {
                    \TechWilk\Rota\SwapQuery::create()
                        ->filterByPrimaryKeys($this->swapsRelatedByNewUserRoleIdScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->swapsRelatedByNewUserRoleIdScheduledForDeletion = null;
                }
            }

            if ($this->collSwapsRelatedByNewUserRoleId !== null) {
                foreach ($this->collSwapsRelatedByNewUserRoleId as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            $this->alreadyInSave = false;

        }

        return $affectedRows;
    }

    /**
     * Insert the row in the database.
     *
     * @param ConnectionInterface $con
     *
     * @throws \Propel\Runtime\Exception\PropelException
     * @see doSave()
     */
    protected function doInsert(ConnectionInterface $con): void
    {
        $modifiedColumns = [];
        $index = 0;

        $this->modifiedColumns[UserRoleTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . UserRoleTableMap::COL_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(UserRoleTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = 'id';
        }
        if ($this->isColumnModified(UserRoleTableMap::COL_USERID)) {
            $modifiedColumns[':p' . $index++]  = 'userId';
        }
        if ($this->isColumnModified(UserRoleTableMap::COL_ROLEID)) {
            $modifiedColumns[':p' . $index++]  = 'roleId';
        }
        if ($this->isColumnModified(UserRoleTableMap::COL_RESERVE)) {
            $modifiedColumns[':p' . $index++]  = 'reserve';
        }

        $sql = sprintf(
            'INSERT INTO userRoles (%s) VALUES (%s)',
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
                    case 'userId':
                        $stmt->bindValue($identifier, $this->userid, PDO::PARAM_INT);

                        break;
                    case 'roleId':
                        $stmt->bindValue($identifier, $this->roleid, PDO::PARAM_INT);

                        break;
                    case 'reserve':
                        $stmt->bindValue($identifier, (int) $this->reserve, PDO::PARAM_INT);

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
     * @param ConnectionInterface $con
     *
     * @return int Number of updated rows
     * @see doSave()
     */
    protected function doUpdate(ConnectionInterface $con): int
    {
        $selectCriteria = $this->buildPkeyCriteria();
        $valuesCriteria = $this->buildCriteria();

        return $selectCriteria->doUpdate($valuesCriteria, $con);
    }

    /**
     * Retrieves a field from the object by name passed in as a string.
     *
     * @param string $name name
     * @param string $type The type of fieldname the $name is of:
     *                     one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                     TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                     Defaults to TableMap::TYPE_PHPNAME.
     * @return mixed Value of field.
     */
    public function getByName(string $name, string $type = TableMap::TYPE_PHPNAME)
    {
        $pos = UserRoleTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
        $field = $this->getByPosition($pos);

        return $field;
    }

    /**
     * Retrieves a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param int $pos Position in XML schema
     * @return mixed Value of field at $pos
     */
    public function getByPosition(int $pos)
    {
        switch ($pos) {
            case 0:
                return $this->getId();

            case 1:
                return $this->getUserId();

            case 2:
                return $this->getRoleId();

            case 3:
                return $this->getReserve();

            default:
                return null;
        } // switch()
    }

    /**
     * Exports the object as an array.
     *
     * You can specify the key type of the array by passing one of the class
     * type constants.
     *
     * @param string $keyType (optional) One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     *                    TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                    Defaults to TableMap::TYPE_PHPNAME.
     * @param bool $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to TRUE.
     * @param array $alreadyDumpedObjects List of objects to skip to avoid recursion
     * @param bool $includeForeignObjects (optional) Whether to include hydrated related objects. Default to FALSE.
     *
     * @return array An associative array containing the field names (as keys) and field values
     */
    public function toArray(string $keyType = TableMap::TYPE_PHPNAME, bool $includeLazyLoadColumns = true, array $alreadyDumpedObjects = [], bool $includeForeignObjects = false): array
    {
        if (isset($alreadyDumpedObjects['UserRole'][$this->hashCode()])) {
            return ['*RECURSION*'];
        }
        $alreadyDumpedObjects['UserRole'][$this->hashCode()] = true;
        $keys = UserRoleTableMap::getFieldNames($keyType);
        $result = [
            $keys[0] => $this->getId(),
            $keys[1] => $this->getUserId(),
            $keys[2] => $this->getRoleId(),
            $keys[3] => $this->getReserve(),
        ];
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

                $result[$key] = $this->aUser->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aRole) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'role';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'roles';
                        break;
                    default:
                        $key = 'Role';
                }

                $result[$key] = $this->aRole->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
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
            if (null !== $this->collSwapsRelatedByOldUserRoleId) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'swaps';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'swapss';
                        break;
                    default:
                        $key = 'Swaps';
                }

                $result[$key] = $this->collSwapsRelatedByOldUserRoleId->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collSwapsRelatedByNewUserRoleId) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'swaps';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'swapss';
                        break;
                    default:
                        $key = 'Swaps';
                }

                $result[$key] = $this->collSwapsRelatedByNewUserRoleId->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
        }

        return $result;
    }

    /**
     * Sets a field from the object by name passed in as a string.
     *
     * @param string $name
     * @param mixed $value field value
     * @param string $type The type of fieldname the $name is of:
     *                one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                Defaults to TableMap::TYPE_PHPNAME.
     * @return $this
     */
    public function setByName(string $name, $value, string $type = TableMap::TYPE_PHPNAME)
    {
        $pos = UserRoleTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        $this->setByPosition($pos, $value);

        return $this;
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param int $pos position in xml schema
     * @param mixed $value field value
     * @return $this
     */
    public function setByPosition(int $pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setUserId($value);
                break;
            case 2:
                $this->setRoleId($value);
                break;
            case 3:
                $this->setReserve($value);
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
     * @param array $arr An array to populate the object from.
     * @param string $keyType The type of keys the array uses.
     * @return $this
     */
    public function fromArray(array $arr, string $keyType = TableMap::TYPE_PHPNAME)
    {
        $keys = UserRoleTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setUserId($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setRoleId($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setReserve($arr[$keys[3]]);
        }

        return $this;
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
     * @return $this The current object, for fluid interface
     */
    public function importFrom($parser, string $data, string $keyType = TableMap::TYPE_PHPNAME)
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
     * @return \Propel\Runtime\ActiveQuery\Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria(): Criteria
    {
        $criteria = new Criteria(UserRoleTableMap::DATABASE_NAME);

        if ($this->isColumnModified(UserRoleTableMap::COL_ID)) {
            $criteria->add(UserRoleTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(UserRoleTableMap::COL_USERID)) {
            $criteria->add(UserRoleTableMap::COL_USERID, $this->userid);
        }
        if ($this->isColumnModified(UserRoleTableMap::COL_ROLEID)) {
            $criteria->add(UserRoleTableMap::COL_ROLEID, $this->roleid);
        }
        if ($this->isColumnModified(UserRoleTableMap::COL_RESERVE)) {
            $criteria->add(UserRoleTableMap::COL_RESERVE, $this->reserve);
        }

        return $criteria;
    }

    /**
     * Builds a Criteria object containing the primary key for this object.
     *
     * Unlike buildCriteria() this method includes the primary key values regardless
     * of whether they have been modified.
     *
     * @throws LogicException if no primary key is defined
     *
     * @return \Propel\Runtime\ActiveQuery\Criteria The Criteria object containing value(s) for primary key(s).
     */
    public function buildPkeyCriteria(): Criteria
    {
        $criteria = ChildUserRoleQuery::create();
        $criteria->add(UserRoleTableMap::COL_ID, $this->id);

        return $criteria;
    }

    /**
     * If the primary key is not null, return the hashcode of the
     * primary key. Otherwise, return the hash code of the object.
     *
     * @return int|string Hashcode
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
     * @param int|null $key Primary key.
     * @return void
     */
    public function setPrimaryKey(?int $key = null): void
    {
        $this->setId($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     *
     * @return bool
     */
    public function isPrimaryKeyNull(): bool
    {
        return null === $this->getId();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param object $copyObj An object of \TechWilk\Rota\UserRole (or compatible) type.
     * @param bool $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param bool $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws \Propel\Runtime\Exception\PropelException
     * @return void
     */
    public function copyInto(object $copyObj, bool $deepCopy = false, bool $makeNew = true): void
    {
        $copyObj->setUserId($this->getUserId());
        $copyObj->setRoleId($this->getRoleId());
        $copyObj->setReserve($this->getReserve());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getEventpeople() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addEventPerson($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getSwapsRelatedByOldUserRoleId() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addSwapRelatedByOldUserRoleId($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getSwapsRelatedByNewUserRoleId() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addSwapRelatedByNewUserRoleId($relObj->copy($deepCopy));
                }
            }

        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setId(NULL); // this is a auto-increment column, so set to default value
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
     * @param bool $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @return \TechWilk\Rota\UserRole Clone of current object.
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function copy(bool $deepCopy = false)
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
     * @param ChildUser $v
     * @return $this The current object (for fluent API support)
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function setUser(ChildUser $v = null)
    {
        if ($v === null) {
            $this->setUserId(0);
        } else {
            $this->setUserId($v->getId());
        }

        $this->aUser = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildUser object, it will not be re-added.
        if ($v !== null) {
            $v->addUserRole($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildUser object
     *
     * @param ConnectionInterface $con Optional Connection object.
     * @return ChildUser The associated ChildUser object.
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getUser(?ConnectionInterface $con = null)
    {
        if ($this->aUser === null && ($this->userid != 0)) {
            $this->aUser = ChildUserQuery::create()->findPk($this->userid, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aUser->addUserRoles($this);
             */
        }

        return $this->aUser;
    }

    /**
     * Declares an association between this object and a ChildRole object.
     *
     * @param ChildRole $v
     * @return $this The current object (for fluent API support)
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function setRole(ChildRole $v = null)
    {
        if ($v === null) {
            $this->setRoleId(0);
        } else {
            $this->setRoleId($v->getId());
        }

        $this->aRole = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildRole object, it will not be re-added.
        if ($v !== null) {
            $v->addUserRole($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildRole object
     *
     * @param ConnectionInterface $con Optional Connection object.
     * @return ChildRole The associated ChildRole object.
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getRole(?ConnectionInterface $con = null)
    {
        if ($this->aRole === null && ($this->roleid != 0)) {
            $this->aRole = ChildRoleQuery::create()->findPk($this->roleid, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aRole->addUserRoles($this);
             */
        }

        return $this->aRole;
    }


    /**
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName): void
    {
        if ('EventPerson' === $relationName) {
            $this->initEventpeople();
            return;
        }
        if ('SwapRelatedByOldUserRoleId' === $relationName) {
            $this->initSwapsRelatedByOldUserRoleId();
            return;
        }
        if ('SwapRelatedByNewUserRoleId' === $relationName) {
            $this->initSwapsRelatedByNewUserRoleId();
            return;
        }
    }

    /**
     * Clears out the collEventpeople collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return $this
     * @see addEventpeople()
     */
    public function clearEventpeople()
    {
        $this->collEventpeople = null; // important to set this to NULL since that means it is uninitialized

        return $this;
    }

    /**
     * Reset is the collEventpeople collection loaded partially.
     *
     * @return void
     */
    public function resetPartialEventpeople($v = true): void
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
     * @param bool $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initEventpeople(bool $overrideExisting = true): void
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
     * If this ChildUserRole is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildEventPerson[] List of ChildEventPerson objects
     * @phpstan-return ObjectCollection&\Traversable<ChildEventPerson> List of ChildEventPerson objects
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getEventpeople(?Criteria $criteria = null, ?ConnectionInterface $con = null)
    {
        $partial = $this->collEventpeoplePartial && !$this->isNew();
        if (null === $this->collEventpeople || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collEventpeople) {
                    $this->initEventpeople();
                } else {
                    $collectionClassName = EventPersonTableMap::getTableMap()->getCollectionClassName();

                    $collEventpeople = new $collectionClassName;
                    $collEventpeople->setModel('\TechWilk\Rota\EventPerson');

                    return $collEventpeople;
                }
            } else {
                $collEventpeople = ChildEventPersonQuery::create(null, $criteria)
                    ->filterByUserRole($this)
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
     * @param Collection $eventpeople A Propel collection.
     * @param ConnectionInterface $con Optional connection object
     * @return $this The current object (for fluent API support)
     */
    public function setEventpeople(Collection $eventpeople, ?ConnectionInterface $con = null)
    {
        /** @var ChildEventPerson[] $eventpeopleToDelete */
        $eventpeopleToDelete = $this->getEventpeople(new Criteria(), $con)->diff($eventpeople);


        $this->eventpeopleScheduledForDeletion = $eventpeopleToDelete;

        foreach ($eventpeopleToDelete as $eventPersonRemoved) {
            $eventPersonRemoved->setUserRole(null);
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
     * @param Criteria $criteria
     * @param bool $distinct
     * @param ConnectionInterface $con
     * @return int Count of related EventPerson objects.
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function countEventpeople(?Criteria $criteria = null, bool $distinct = false, ?ConnectionInterface $con = null): int
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
                ->filterByUserRole($this)
                ->count($con);
        }

        return count($this->collEventpeople);
    }

    /**
     * Method called to associate a ChildEventPerson object to this object
     * through the ChildEventPerson foreign key attribute.
     *
     * @param ChildEventPerson $l ChildEventPerson
     * @return $this The current object (for fluent API support)
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
    protected function doAddEventPerson(ChildEventPerson $eventPerson): void
    {
        $this->collEventpeople[]= $eventPerson;
        $eventPerson->setUserRole($this);
    }

    /**
     * @param ChildEventPerson $eventPerson The ChildEventPerson object to remove.
     * @return $this The current object (for fluent API support)
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
            $eventPerson->setUserRole(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this UserRole is new, it will return
     * an empty collection; or if this UserRole has previously
     * been saved, it will retrieve related Eventpeople from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in UserRole.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @param string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildEventPerson[] List of ChildEventPerson objects
     * @phpstan-return ObjectCollection&\Traversable<ChildEventPerson}> List of ChildEventPerson objects
     */
    public function getEventpeopleJoinEvent(?Criteria $criteria = null, ?ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildEventPersonQuery::create(null, $criteria);
        $query->joinWith('Event', $joinBehavior);

        return $this->getEventpeople($query, $con);
    }

    /**
     * Clears out the collSwapsRelatedByOldUserRoleId collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return $this
     * @see addSwapsRelatedByOldUserRoleId()
     */
    public function clearSwapsRelatedByOldUserRoleId()
    {
        $this->collSwapsRelatedByOldUserRoleId = null; // important to set this to NULL since that means it is uninitialized

        return $this;
    }

    /**
     * Reset is the collSwapsRelatedByOldUserRoleId collection loaded partially.
     *
     * @return void
     */
    public function resetPartialSwapsRelatedByOldUserRoleId($v = true): void
    {
        $this->collSwapsRelatedByOldUserRoleIdPartial = $v;
    }

    /**
     * Initializes the collSwapsRelatedByOldUserRoleId collection.
     *
     * By default this just sets the collSwapsRelatedByOldUserRoleId collection to an empty array (like clearcollSwapsRelatedByOldUserRoleId());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param bool $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initSwapsRelatedByOldUserRoleId(bool $overrideExisting = true): void
    {
        if (null !== $this->collSwapsRelatedByOldUserRoleId && !$overrideExisting) {
            return;
        }

        $collectionClassName = SwapTableMap::getTableMap()->getCollectionClassName();

        $this->collSwapsRelatedByOldUserRoleId = new $collectionClassName;
        $this->collSwapsRelatedByOldUserRoleId->setModel('\TechWilk\Rota\Swap');
    }

    /**
     * Gets an array of ChildSwap objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUserRole is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildSwap[] List of ChildSwap objects
     * @phpstan-return ObjectCollection&\Traversable<ChildSwap> List of ChildSwap objects
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getSwapsRelatedByOldUserRoleId(?Criteria $criteria = null, ?ConnectionInterface $con = null)
    {
        $partial = $this->collSwapsRelatedByOldUserRoleIdPartial && !$this->isNew();
        if (null === $this->collSwapsRelatedByOldUserRoleId || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collSwapsRelatedByOldUserRoleId) {
                    $this->initSwapsRelatedByOldUserRoleId();
                } else {
                    $collectionClassName = SwapTableMap::getTableMap()->getCollectionClassName();

                    $collSwapsRelatedByOldUserRoleId = new $collectionClassName;
                    $collSwapsRelatedByOldUserRoleId->setModel('\TechWilk\Rota\Swap');

                    return $collSwapsRelatedByOldUserRoleId;
                }
            } else {
                $collSwapsRelatedByOldUserRoleId = ChildSwapQuery::create(null, $criteria)
                    ->filterByOldUserRole($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collSwapsRelatedByOldUserRoleIdPartial && count($collSwapsRelatedByOldUserRoleId)) {
                        $this->initSwapsRelatedByOldUserRoleId(false);

                        foreach ($collSwapsRelatedByOldUserRoleId as $obj) {
                            if (false == $this->collSwapsRelatedByOldUserRoleId->contains($obj)) {
                                $this->collSwapsRelatedByOldUserRoleId->append($obj);
                            }
                        }

                        $this->collSwapsRelatedByOldUserRoleIdPartial = true;
                    }

                    return $collSwapsRelatedByOldUserRoleId;
                }

                if ($partial && $this->collSwapsRelatedByOldUserRoleId) {
                    foreach ($this->collSwapsRelatedByOldUserRoleId as $obj) {
                        if ($obj->isNew()) {
                            $collSwapsRelatedByOldUserRoleId[] = $obj;
                        }
                    }
                }

                $this->collSwapsRelatedByOldUserRoleId = $collSwapsRelatedByOldUserRoleId;
                $this->collSwapsRelatedByOldUserRoleIdPartial = false;
            }
        }

        return $this->collSwapsRelatedByOldUserRoleId;
    }

    /**
     * Sets a collection of ChildSwap objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param Collection $swapsRelatedByOldUserRoleId A Propel collection.
     * @param ConnectionInterface $con Optional connection object
     * @return $this The current object (for fluent API support)
     */
    public function setSwapsRelatedByOldUserRoleId(Collection $swapsRelatedByOldUserRoleId, ?ConnectionInterface $con = null)
    {
        /** @var ChildSwap[] $swapsRelatedByOldUserRoleIdToDelete */
        $swapsRelatedByOldUserRoleIdToDelete = $this->getSwapsRelatedByOldUserRoleId(new Criteria(), $con)->diff($swapsRelatedByOldUserRoleId);


        $this->swapsRelatedByOldUserRoleIdScheduledForDeletion = $swapsRelatedByOldUserRoleIdToDelete;

        foreach ($swapsRelatedByOldUserRoleIdToDelete as $swapRelatedByOldUserRoleIdRemoved) {
            $swapRelatedByOldUserRoleIdRemoved->setOldUserRole(null);
        }

        $this->collSwapsRelatedByOldUserRoleId = null;
        foreach ($swapsRelatedByOldUserRoleId as $swapRelatedByOldUserRoleId) {
            $this->addSwapRelatedByOldUserRoleId($swapRelatedByOldUserRoleId);
        }

        $this->collSwapsRelatedByOldUserRoleId = $swapsRelatedByOldUserRoleId;
        $this->collSwapsRelatedByOldUserRoleIdPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Swap objects.
     *
     * @param Criteria $criteria
     * @param bool $distinct
     * @param ConnectionInterface $con
     * @return int Count of related Swap objects.
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function countSwapsRelatedByOldUserRoleId(?Criteria $criteria = null, bool $distinct = false, ?ConnectionInterface $con = null): int
    {
        $partial = $this->collSwapsRelatedByOldUserRoleIdPartial && !$this->isNew();
        if (null === $this->collSwapsRelatedByOldUserRoleId || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collSwapsRelatedByOldUserRoleId) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getSwapsRelatedByOldUserRoleId());
            }

            $query = ChildSwapQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByOldUserRole($this)
                ->count($con);
        }

        return count($this->collSwapsRelatedByOldUserRoleId);
    }

    /**
     * Method called to associate a ChildSwap object to this object
     * through the ChildSwap foreign key attribute.
     *
     * @param ChildSwap $l ChildSwap
     * @return $this The current object (for fluent API support)
     */
    public function addSwapRelatedByOldUserRoleId(ChildSwap $l)
    {
        if ($this->collSwapsRelatedByOldUserRoleId === null) {
            $this->initSwapsRelatedByOldUserRoleId();
            $this->collSwapsRelatedByOldUserRoleIdPartial = true;
        }

        if (!$this->collSwapsRelatedByOldUserRoleId->contains($l)) {
            $this->doAddSwapRelatedByOldUserRoleId($l);

            if ($this->swapsRelatedByOldUserRoleIdScheduledForDeletion and $this->swapsRelatedByOldUserRoleIdScheduledForDeletion->contains($l)) {
                $this->swapsRelatedByOldUserRoleIdScheduledForDeletion->remove($this->swapsRelatedByOldUserRoleIdScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildSwap $swapRelatedByOldUserRoleId The ChildSwap object to add.
     */
    protected function doAddSwapRelatedByOldUserRoleId(ChildSwap $swapRelatedByOldUserRoleId): void
    {
        $this->collSwapsRelatedByOldUserRoleId[]= $swapRelatedByOldUserRoleId;
        $swapRelatedByOldUserRoleId->setOldUserRole($this);
    }

    /**
     * @param ChildSwap $swapRelatedByOldUserRoleId The ChildSwap object to remove.
     * @return $this The current object (for fluent API support)
     */
    public function removeSwapRelatedByOldUserRoleId(ChildSwap $swapRelatedByOldUserRoleId)
    {
        if ($this->getSwapsRelatedByOldUserRoleId()->contains($swapRelatedByOldUserRoleId)) {
            $pos = $this->collSwapsRelatedByOldUserRoleId->search($swapRelatedByOldUserRoleId);
            $this->collSwapsRelatedByOldUserRoleId->remove($pos);
            if (null === $this->swapsRelatedByOldUserRoleIdScheduledForDeletion) {
                $this->swapsRelatedByOldUserRoleIdScheduledForDeletion = clone $this->collSwapsRelatedByOldUserRoleId;
                $this->swapsRelatedByOldUserRoleIdScheduledForDeletion->clear();
            }
            $this->swapsRelatedByOldUserRoleIdScheduledForDeletion[]= clone $swapRelatedByOldUserRoleId;
            $swapRelatedByOldUserRoleId->setOldUserRole(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this UserRole is new, it will return
     * an empty collection; or if this UserRole has previously
     * been saved, it will retrieve related SwapsRelatedByOldUserRoleId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in UserRole.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @param string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildSwap[] List of ChildSwap objects
     * @phpstan-return ObjectCollection&\Traversable<ChildSwap}> List of ChildSwap objects
     */
    public function getSwapsRelatedByOldUserRoleIdJoinEventPerson(?Criteria $criteria = null, ?ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildSwapQuery::create(null, $criteria);
        $query->joinWith('EventPerson', $joinBehavior);

        return $this->getSwapsRelatedByOldUserRoleId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this UserRole is new, it will return
     * an empty collection; or if this UserRole has previously
     * been saved, it will retrieve related SwapsRelatedByOldUserRoleId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in UserRole.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @param string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildSwap[] List of ChildSwap objects
     * @phpstan-return ObjectCollection&\Traversable<ChildSwap}> List of ChildSwap objects
     */
    public function getSwapsRelatedByOldUserRoleIdJoinUser(?Criteria $criteria = null, ?ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildSwapQuery::create(null, $criteria);
        $query->joinWith('User', $joinBehavior);

        return $this->getSwapsRelatedByOldUserRoleId($query, $con);
    }

    /**
     * Clears out the collSwapsRelatedByNewUserRoleId collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return $this
     * @see addSwapsRelatedByNewUserRoleId()
     */
    public function clearSwapsRelatedByNewUserRoleId()
    {
        $this->collSwapsRelatedByNewUserRoleId = null; // important to set this to NULL since that means it is uninitialized

        return $this;
    }

    /**
     * Reset is the collSwapsRelatedByNewUserRoleId collection loaded partially.
     *
     * @return void
     */
    public function resetPartialSwapsRelatedByNewUserRoleId($v = true): void
    {
        $this->collSwapsRelatedByNewUserRoleIdPartial = $v;
    }

    /**
     * Initializes the collSwapsRelatedByNewUserRoleId collection.
     *
     * By default this just sets the collSwapsRelatedByNewUserRoleId collection to an empty array (like clearcollSwapsRelatedByNewUserRoleId());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param bool $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initSwapsRelatedByNewUserRoleId(bool $overrideExisting = true): void
    {
        if (null !== $this->collSwapsRelatedByNewUserRoleId && !$overrideExisting) {
            return;
        }

        $collectionClassName = SwapTableMap::getTableMap()->getCollectionClassName();

        $this->collSwapsRelatedByNewUserRoleId = new $collectionClassName;
        $this->collSwapsRelatedByNewUserRoleId->setModel('\TechWilk\Rota\Swap');
    }

    /**
     * Gets an array of ChildSwap objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUserRole is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildSwap[] List of ChildSwap objects
     * @phpstan-return ObjectCollection&\Traversable<ChildSwap> List of ChildSwap objects
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getSwapsRelatedByNewUserRoleId(?Criteria $criteria = null, ?ConnectionInterface $con = null)
    {
        $partial = $this->collSwapsRelatedByNewUserRoleIdPartial && !$this->isNew();
        if (null === $this->collSwapsRelatedByNewUserRoleId || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collSwapsRelatedByNewUserRoleId) {
                    $this->initSwapsRelatedByNewUserRoleId();
                } else {
                    $collectionClassName = SwapTableMap::getTableMap()->getCollectionClassName();

                    $collSwapsRelatedByNewUserRoleId = new $collectionClassName;
                    $collSwapsRelatedByNewUserRoleId->setModel('\TechWilk\Rota\Swap');

                    return $collSwapsRelatedByNewUserRoleId;
                }
            } else {
                $collSwapsRelatedByNewUserRoleId = ChildSwapQuery::create(null, $criteria)
                    ->filterByNewUserRole($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collSwapsRelatedByNewUserRoleIdPartial && count($collSwapsRelatedByNewUserRoleId)) {
                        $this->initSwapsRelatedByNewUserRoleId(false);

                        foreach ($collSwapsRelatedByNewUserRoleId as $obj) {
                            if (false == $this->collSwapsRelatedByNewUserRoleId->contains($obj)) {
                                $this->collSwapsRelatedByNewUserRoleId->append($obj);
                            }
                        }

                        $this->collSwapsRelatedByNewUserRoleIdPartial = true;
                    }

                    return $collSwapsRelatedByNewUserRoleId;
                }

                if ($partial && $this->collSwapsRelatedByNewUserRoleId) {
                    foreach ($this->collSwapsRelatedByNewUserRoleId as $obj) {
                        if ($obj->isNew()) {
                            $collSwapsRelatedByNewUserRoleId[] = $obj;
                        }
                    }
                }

                $this->collSwapsRelatedByNewUserRoleId = $collSwapsRelatedByNewUserRoleId;
                $this->collSwapsRelatedByNewUserRoleIdPartial = false;
            }
        }

        return $this->collSwapsRelatedByNewUserRoleId;
    }

    /**
     * Sets a collection of ChildSwap objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param Collection $swapsRelatedByNewUserRoleId A Propel collection.
     * @param ConnectionInterface $con Optional connection object
     * @return $this The current object (for fluent API support)
     */
    public function setSwapsRelatedByNewUserRoleId(Collection $swapsRelatedByNewUserRoleId, ?ConnectionInterface $con = null)
    {
        /** @var ChildSwap[] $swapsRelatedByNewUserRoleIdToDelete */
        $swapsRelatedByNewUserRoleIdToDelete = $this->getSwapsRelatedByNewUserRoleId(new Criteria(), $con)->diff($swapsRelatedByNewUserRoleId);


        $this->swapsRelatedByNewUserRoleIdScheduledForDeletion = $swapsRelatedByNewUserRoleIdToDelete;

        foreach ($swapsRelatedByNewUserRoleIdToDelete as $swapRelatedByNewUserRoleIdRemoved) {
            $swapRelatedByNewUserRoleIdRemoved->setNewUserRole(null);
        }

        $this->collSwapsRelatedByNewUserRoleId = null;
        foreach ($swapsRelatedByNewUserRoleId as $swapRelatedByNewUserRoleId) {
            $this->addSwapRelatedByNewUserRoleId($swapRelatedByNewUserRoleId);
        }

        $this->collSwapsRelatedByNewUserRoleId = $swapsRelatedByNewUserRoleId;
        $this->collSwapsRelatedByNewUserRoleIdPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Swap objects.
     *
     * @param Criteria $criteria
     * @param bool $distinct
     * @param ConnectionInterface $con
     * @return int Count of related Swap objects.
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function countSwapsRelatedByNewUserRoleId(?Criteria $criteria = null, bool $distinct = false, ?ConnectionInterface $con = null): int
    {
        $partial = $this->collSwapsRelatedByNewUserRoleIdPartial && !$this->isNew();
        if (null === $this->collSwapsRelatedByNewUserRoleId || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collSwapsRelatedByNewUserRoleId) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getSwapsRelatedByNewUserRoleId());
            }

            $query = ChildSwapQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByNewUserRole($this)
                ->count($con);
        }

        return count($this->collSwapsRelatedByNewUserRoleId);
    }

    /**
     * Method called to associate a ChildSwap object to this object
     * through the ChildSwap foreign key attribute.
     *
     * @param ChildSwap $l ChildSwap
     * @return $this The current object (for fluent API support)
     */
    public function addSwapRelatedByNewUserRoleId(ChildSwap $l)
    {
        if ($this->collSwapsRelatedByNewUserRoleId === null) {
            $this->initSwapsRelatedByNewUserRoleId();
            $this->collSwapsRelatedByNewUserRoleIdPartial = true;
        }

        if (!$this->collSwapsRelatedByNewUserRoleId->contains($l)) {
            $this->doAddSwapRelatedByNewUserRoleId($l);

            if ($this->swapsRelatedByNewUserRoleIdScheduledForDeletion and $this->swapsRelatedByNewUserRoleIdScheduledForDeletion->contains($l)) {
                $this->swapsRelatedByNewUserRoleIdScheduledForDeletion->remove($this->swapsRelatedByNewUserRoleIdScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildSwap $swapRelatedByNewUserRoleId The ChildSwap object to add.
     */
    protected function doAddSwapRelatedByNewUserRoleId(ChildSwap $swapRelatedByNewUserRoleId): void
    {
        $this->collSwapsRelatedByNewUserRoleId[]= $swapRelatedByNewUserRoleId;
        $swapRelatedByNewUserRoleId->setNewUserRole($this);
    }

    /**
     * @param ChildSwap $swapRelatedByNewUserRoleId The ChildSwap object to remove.
     * @return $this The current object (for fluent API support)
     */
    public function removeSwapRelatedByNewUserRoleId(ChildSwap $swapRelatedByNewUserRoleId)
    {
        if ($this->getSwapsRelatedByNewUserRoleId()->contains($swapRelatedByNewUserRoleId)) {
            $pos = $this->collSwapsRelatedByNewUserRoleId->search($swapRelatedByNewUserRoleId);
            $this->collSwapsRelatedByNewUserRoleId->remove($pos);
            if (null === $this->swapsRelatedByNewUserRoleIdScheduledForDeletion) {
                $this->swapsRelatedByNewUserRoleIdScheduledForDeletion = clone $this->collSwapsRelatedByNewUserRoleId;
                $this->swapsRelatedByNewUserRoleIdScheduledForDeletion->clear();
            }
            $this->swapsRelatedByNewUserRoleIdScheduledForDeletion[]= clone $swapRelatedByNewUserRoleId;
            $swapRelatedByNewUserRoleId->setNewUserRole(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this UserRole is new, it will return
     * an empty collection; or if this UserRole has previously
     * been saved, it will retrieve related SwapsRelatedByNewUserRoleId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in UserRole.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @param string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildSwap[] List of ChildSwap objects
     * @phpstan-return ObjectCollection&\Traversable<ChildSwap}> List of ChildSwap objects
     */
    public function getSwapsRelatedByNewUserRoleIdJoinEventPerson(?Criteria $criteria = null, ?ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildSwapQuery::create(null, $criteria);
        $query->joinWith('EventPerson', $joinBehavior);

        return $this->getSwapsRelatedByNewUserRoleId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this UserRole is new, it will return
     * an empty collection; or if this UserRole has previously
     * been saved, it will retrieve related SwapsRelatedByNewUserRoleId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in UserRole.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @param string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildSwap[] List of ChildSwap objects
     * @phpstan-return ObjectCollection&\Traversable<ChildSwap}> List of ChildSwap objects
     */
    public function getSwapsRelatedByNewUserRoleIdJoinUser(?Criteria $criteria = null, ?ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildSwapQuery::create(null, $criteria);
        $query->joinWith('User', $joinBehavior);

        return $this->getSwapsRelatedByNewUserRoleId($query, $con);
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     *
     * @return $this
     */
    public function clear()
    {
        if (null !== $this->aUser) {
            $this->aUser->removeUserRole($this);
        }
        if (null !== $this->aRole) {
            $this->aRole->removeUserRole($this);
        }
        $this->id = null;
        $this->userid = null;
        $this->roleid = null;
        $this->reserve = null;
        $this->alreadyInSave = false;
        $this->clearAllReferences();
        $this->applyDefaultValues();
        $this->resetModified();
        $this->setNew(true);
        $this->setDeleted(false);

        return $this;
    }

    /**
     * Resets all references and back-references to other model objects or collections of model objects.
     *
     * This method is used to reset all php object references (not the actual reference in the database).
     * Necessary for object serialisation.
     *
     * @param bool $deep Whether to also clear the references on all referrer objects.
     * @return $this
     */
    public function clearAllReferences(bool $deep = false)
    {
        if ($deep) {
            if ($this->collEventpeople) {
                foreach ($this->collEventpeople as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collSwapsRelatedByOldUserRoleId) {
                foreach ($this->collSwapsRelatedByOldUserRoleId as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collSwapsRelatedByNewUserRoleId) {
                foreach ($this->collSwapsRelatedByNewUserRoleId as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collEventpeople = null;
        $this->collSwapsRelatedByOldUserRoleId = null;
        $this->collSwapsRelatedByNewUserRoleId = null;
        $this->aUser = null;
        $this->aRole = null;
        return $this;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(UserRoleTableMap::DEFAULT_STRING_FORMAT);
    }

    /**
     * Code to be run before persisting the object
     * @param ConnectionInterface|null $con
     * @return bool
     */
    public function preSave(?ConnectionInterface $con = null): bool
    {
                return true;
    }

    /**
     * Code to be run after persisting the object
     * @param ConnectionInterface|null $con
     * @return void
     */
    public function postSave(?ConnectionInterface $con = null): void
    {
            }

    /**
     * Code to be run before inserting to database
     * @param ConnectionInterface|null $con
     * @return bool
     */
    public function preInsert(?ConnectionInterface $con = null): bool
    {
                return true;
    }

    /**
     * Code to be run after inserting to database
     * @param ConnectionInterface|null $con
     * @return void
     */
    public function postInsert(?ConnectionInterface $con = null): void
    {
            }

    /**
     * Code to be run before updating the object in database
     * @param ConnectionInterface|null $con
     * @return bool
     */
    public function preUpdate(?ConnectionInterface $con = null): bool
    {
                return true;
    }

    /**
     * Code to be run after updating the object in database
     * @param ConnectionInterface|null $con
     * @return void
     */
    public function postUpdate(?ConnectionInterface $con = null): void
    {
            }

    /**
     * Code to be run before deleting the object in database
     * @param ConnectionInterface|null $con
     * @return bool
     */
    public function preDelete(?ConnectionInterface $con = null): bool
    {
                return true;
    }

    /**
     * Code to be run after deleting the object in database
     * @param ConnectionInterface|null $con
     * @return void
     */
    public function postDelete(?ConnectionInterface $con = null): void
    {
            }


    /**
     * Derived method to catches calls to undefined methods.
     *
     * Provides magic import/export method support (fromXML()/toXML(), fromYAML()/toYAML(), etc.).
     * Allows to define default __call() behavior if you overwrite __call()
     *
     * @param string $name
     * @param mixed $params
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
            $inputData = $params[0];
            $keyType = $params[1] ?? TableMap::TYPE_PHPNAME;

            return $this->importFrom($format, $inputData, $keyType);
        }

        if (0 === strpos($name, 'to')) {
            $format = substr($name, 2);
            $includeLazyLoadColumns = $params[0] ?? true;
            $keyType = $params[1] ?? TableMap::TYPE_PHPNAME;

            return $this->exportTo($format, $includeLazyLoadColumns, $keyType);
        }

        throw new BadMethodCallException(sprintf('Call to undefined method: %s.', $name));
    }

}
