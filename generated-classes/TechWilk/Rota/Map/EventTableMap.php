<?php

namespace TechWilk\Rota\Map;

use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\InstancePoolTrait;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\DataFetcher\DataFetcherInterface;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\RelationMap;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Map\TableMapTrait;
use TechWilk\Rota\Event;
use TechWilk\Rota\EventQuery;


/**
 * This class defines the structure of the 'events' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 */
class EventTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    public const CLASS_NAME = 'TechWilk.Rota.Map.EventTableMap';

    /**
     * The default database name for this class
     */
    public const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    public const TABLE_NAME = 'events';

    /**
     * The PHP name of this class (PascalCase)
     */
    public const TABLE_PHP_NAME = 'Event';

    /**
     * The related Propel class for this table
     */
    public const OM_CLASS = '\\TechWilk\\Rota\\Event';

    /**
     * A class that can be returned by this tableMap
     */
    public const CLASS_DEFAULT = 'TechWilk.Rota.Event';

    /**
     * The total number of columns
     */
    public const NUM_COLUMNS = 16;

    /**
     * The number of lazy-loaded columns
     */
    public const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    public const NUM_HYDRATE_COLUMNS = 16;

    /**
     * the column name for the id field
     */
    public const COL_ID = 'events.id';

    /**
     * the column name for the date field
     */
    public const COL_DATE = 'events.date';

    /**
     * the column name for the name field
     */
    public const COL_NAME = 'events.name';

    /**
     * the column name for the createdBy field
     */
    public const COL_CREATEDBY = 'events.createdBy';

    /**
     * the column name for the rehearsalDate field
     */
    public const COL_REHEARSALDATE = 'events.rehearsalDate';

    /**
     * the column name for the type field
     */
    public const COL_TYPE = 'events.type';

    /**
     * the column name for the subType field
     */
    public const COL_SUBTYPE = 'events.subType';

    /**
     * the column name for the location field
     */
    public const COL_LOCATION = 'events.location';

    /**
     * the column name for the notified field
     */
    public const COL_NOTIFIED = 'events.notified';

    /**
     * the column name for the rehearsal field
     */
    public const COL_REHEARSAL = 'events.rehearsal';

    /**
     * the column name for the removed field
     */
    public const COL_REMOVED = 'events.removed';

    /**
     * the column name for the eventGroup field
     */
    public const COL_EVENTGROUP = 'events.eventGroup';

    /**
     * the column name for the sermonTitle field
     */
    public const COL_SERMONTITLE = 'events.sermonTitle';

    /**
     * the column name for the bibleVerse field
     */
    public const COL_BIBLEVERSE = 'events.bibleVerse';

    /**
     * the column name for the created field
     */
    public const COL_CREATED = 'events.created';

    /**
     * the column name for the updated field
     */
    public const COL_UPDATED = 'events.updated';

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
        self::TYPE_PHPNAME       => ['Id', 'Date', 'Name', 'CreatedBy', 'RehearsalDate', 'EventTypeId', 'EventSubTypeId', 'LocationId', 'Notified', 'Rehearsal', 'Removed', 'EventGroupId', 'SermonTitle', 'BibleVerse', 'Created', 'Updated', ],
        self::TYPE_CAMELNAME     => ['id', 'date', 'name', 'createdBy', 'rehearsalDate', 'eventTypeId', 'eventSubTypeId', 'locationId', 'notified', 'rehearsal', 'removed', 'eventGroupId', 'sermonTitle', 'bibleVerse', 'created', 'updated', ],
        self::TYPE_COLNAME       => [EventTableMap::COL_ID, EventTableMap::COL_DATE, EventTableMap::COL_NAME, EventTableMap::COL_CREATEDBY, EventTableMap::COL_REHEARSALDATE, EventTableMap::COL_TYPE, EventTableMap::COL_SUBTYPE, EventTableMap::COL_LOCATION, EventTableMap::COL_NOTIFIED, EventTableMap::COL_REHEARSAL, EventTableMap::COL_REMOVED, EventTableMap::COL_EVENTGROUP, EventTableMap::COL_SERMONTITLE, EventTableMap::COL_BIBLEVERSE, EventTableMap::COL_CREATED, EventTableMap::COL_UPDATED, ],
        self::TYPE_FIELDNAME     => ['id', 'date', 'name', 'createdBy', 'rehearsalDate', 'type', 'subType', 'location', 'notified', 'rehearsal', 'removed', 'eventGroup', 'sermonTitle', 'bibleVerse', 'created', 'updated', ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, ]
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
        self::TYPE_PHPNAME       => ['Id' => 0, 'Date' => 1, 'Name' => 2, 'CreatedBy' => 3, 'RehearsalDate' => 4, 'EventTypeId' => 5, 'EventSubTypeId' => 6, 'LocationId' => 7, 'Notified' => 8, 'Rehearsal' => 9, 'Removed' => 10, 'EventGroupId' => 11, 'SermonTitle' => 12, 'BibleVerse' => 13, 'Created' => 14, 'Updated' => 15, ],
        self::TYPE_CAMELNAME     => ['id' => 0, 'date' => 1, 'name' => 2, 'createdBy' => 3, 'rehearsalDate' => 4, 'eventTypeId' => 5, 'eventSubTypeId' => 6, 'locationId' => 7, 'notified' => 8, 'rehearsal' => 9, 'removed' => 10, 'eventGroupId' => 11, 'sermonTitle' => 12, 'bibleVerse' => 13, 'created' => 14, 'updated' => 15, ],
        self::TYPE_COLNAME       => [EventTableMap::COL_ID => 0, EventTableMap::COL_DATE => 1, EventTableMap::COL_NAME => 2, EventTableMap::COL_CREATEDBY => 3, EventTableMap::COL_REHEARSALDATE => 4, EventTableMap::COL_TYPE => 5, EventTableMap::COL_SUBTYPE => 6, EventTableMap::COL_LOCATION => 7, EventTableMap::COL_NOTIFIED => 8, EventTableMap::COL_REHEARSAL => 9, EventTableMap::COL_REMOVED => 10, EventTableMap::COL_EVENTGROUP => 11, EventTableMap::COL_SERMONTITLE => 12, EventTableMap::COL_BIBLEVERSE => 13, EventTableMap::COL_CREATED => 14, EventTableMap::COL_UPDATED => 15, ],
        self::TYPE_FIELDNAME     => ['id' => 0, 'date' => 1, 'name' => 2, 'createdBy' => 3, 'rehearsalDate' => 4, 'type' => 5, 'subType' => 6, 'location' => 7, 'notified' => 8, 'rehearsal' => 9, 'removed' => 10, 'eventGroup' => 11, 'sermonTitle' => 12, 'bibleVerse' => 13, 'created' => 14, 'updated' => 15, ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, ]
    ];

    /**
     * Holds a list of column names and their normalized version.
     *
     * @var array<string>
     */
    protected $normalizedColumnNameMap = [
        'Id' => 'ID',
        'Event.Id' => 'ID',
        'id' => 'ID',
        'event.id' => 'ID',
        'EventTableMap::COL_ID' => 'ID',
        'COL_ID' => 'ID',
        'events.id' => 'ID',
        'Date' => 'DATE',
        'Event.Date' => 'DATE',
        'date' => 'DATE',
        'event.date' => 'DATE',
        'EventTableMap::COL_DATE' => 'DATE',
        'COL_DATE' => 'DATE',
        'events.date' => 'DATE',
        'Name' => 'NAME',
        'Event.Name' => 'NAME',
        'name' => 'NAME',
        'event.name' => 'NAME',
        'EventTableMap::COL_NAME' => 'NAME',
        'COL_NAME' => 'NAME',
        'events.name' => 'NAME',
        'CreatedBy' => 'CREATEDBY',
        'Event.CreatedBy' => 'CREATEDBY',
        'createdBy' => 'CREATEDBY',
        'event.createdBy' => 'CREATEDBY',
        'EventTableMap::COL_CREATEDBY' => 'CREATEDBY',
        'COL_CREATEDBY' => 'CREATEDBY',
        'events.createdBy' => 'CREATEDBY',
        'RehearsalDate' => 'REHEARSALDATE',
        'Event.RehearsalDate' => 'REHEARSALDATE',
        'rehearsalDate' => 'REHEARSALDATE',
        'event.rehearsalDate' => 'REHEARSALDATE',
        'EventTableMap::COL_REHEARSALDATE' => 'REHEARSALDATE',
        'COL_REHEARSALDATE' => 'REHEARSALDATE',
        'events.rehearsalDate' => 'REHEARSALDATE',
        'EventTypeId' => 'TYPE',
        'Event.EventTypeId' => 'TYPE',
        'eventTypeId' => 'TYPE',
        'event.eventTypeId' => 'TYPE',
        'EventTableMap::COL_TYPE' => 'TYPE',
        'COL_TYPE' => 'TYPE',
        'type' => 'TYPE',
        'events.type' => 'TYPE',
        'EventSubTypeId' => 'SUBTYPE',
        'Event.EventSubTypeId' => 'SUBTYPE',
        'eventSubTypeId' => 'SUBTYPE',
        'event.eventSubTypeId' => 'SUBTYPE',
        'EventTableMap::COL_SUBTYPE' => 'SUBTYPE',
        'COL_SUBTYPE' => 'SUBTYPE',
        'subType' => 'SUBTYPE',
        'events.subType' => 'SUBTYPE',
        'LocationId' => 'LOCATION',
        'Event.LocationId' => 'LOCATION',
        'locationId' => 'LOCATION',
        'event.locationId' => 'LOCATION',
        'EventTableMap::COL_LOCATION' => 'LOCATION',
        'COL_LOCATION' => 'LOCATION',
        'location' => 'LOCATION',
        'events.location' => 'LOCATION',
        'Notified' => 'NOTIFIED',
        'Event.Notified' => 'NOTIFIED',
        'notified' => 'NOTIFIED',
        'event.notified' => 'NOTIFIED',
        'EventTableMap::COL_NOTIFIED' => 'NOTIFIED',
        'COL_NOTIFIED' => 'NOTIFIED',
        'events.notified' => 'NOTIFIED',
        'Rehearsal' => 'REHEARSAL',
        'Event.Rehearsal' => 'REHEARSAL',
        'rehearsal' => 'REHEARSAL',
        'event.rehearsal' => 'REHEARSAL',
        'EventTableMap::COL_REHEARSAL' => 'REHEARSAL',
        'COL_REHEARSAL' => 'REHEARSAL',
        'events.rehearsal' => 'REHEARSAL',
        'Removed' => 'REMOVED',
        'Event.Removed' => 'REMOVED',
        'removed' => 'REMOVED',
        'event.removed' => 'REMOVED',
        'EventTableMap::COL_REMOVED' => 'REMOVED',
        'COL_REMOVED' => 'REMOVED',
        'events.removed' => 'REMOVED',
        'EventGroupId' => 'EVENTGROUP',
        'Event.EventGroupId' => 'EVENTGROUP',
        'eventGroupId' => 'EVENTGROUP',
        'event.eventGroupId' => 'EVENTGROUP',
        'EventTableMap::COL_EVENTGROUP' => 'EVENTGROUP',
        'COL_EVENTGROUP' => 'EVENTGROUP',
        'eventGroup' => 'EVENTGROUP',
        'events.eventGroup' => 'EVENTGROUP',
        'SermonTitle' => 'SERMONTITLE',
        'Event.SermonTitle' => 'SERMONTITLE',
        'sermonTitle' => 'SERMONTITLE',
        'event.sermonTitle' => 'SERMONTITLE',
        'EventTableMap::COL_SERMONTITLE' => 'SERMONTITLE',
        'COL_SERMONTITLE' => 'SERMONTITLE',
        'events.sermonTitle' => 'SERMONTITLE',
        'BibleVerse' => 'BIBLEVERSE',
        'Event.BibleVerse' => 'BIBLEVERSE',
        'bibleVerse' => 'BIBLEVERSE',
        'event.bibleVerse' => 'BIBLEVERSE',
        'EventTableMap::COL_BIBLEVERSE' => 'BIBLEVERSE',
        'COL_BIBLEVERSE' => 'BIBLEVERSE',
        'events.bibleVerse' => 'BIBLEVERSE',
        'Created' => 'CREATED',
        'Event.Created' => 'CREATED',
        'created' => 'CREATED',
        'event.created' => 'CREATED',
        'EventTableMap::COL_CREATED' => 'CREATED',
        'COL_CREATED' => 'CREATED',
        'events.created' => 'CREATED',
        'Updated' => 'UPDATED',
        'Event.Updated' => 'UPDATED',
        'updated' => 'UPDATED',
        'event.updated' => 'UPDATED',
        'EventTableMap::COL_UPDATED' => 'UPDATED',
        'COL_UPDATED' => 'UPDATED',
        'events.updated' => 'UPDATED',
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
        $this->setName('events');
        $this->setPhpName('Event');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\TechWilk\\Rota\\Event');
        $this->setPackage('TechWilk.Rota');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, 6, null);
        $this->addColumn('date', 'Date', 'TIMESTAMP', true, null, '0000-00-00 00:00:00');
        $this->addColumn('name', 'Name', 'LONGVARCHAR', true, null, null);
        $this->addForeignKey('createdBy', 'CreatedBy', 'INTEGER', 'users', 'id', true, null, 0);
        $this->addColumn('rehearsalDate', 'RehearsalDate', 'TIMESTAMP', true, null, '0000-00-00 00:00:00');
        $this->addForeignKey('type', 'EventTypeId', 'INTEGER', 'eventTypes', 'id', true, 30, 0);
        $this->addForeignKey('subType', 'EventSubTypeId', 'INTEGER', 'eventSubTypes', 'id', true, 30, 0);
        $this->addForeignKey('location', 'LocationId', 'INTEGER', 'locations', 'id', true, null, 0);
        $this->addColumn('notified', 'Notified', 'INTEGER', true, 2, 0);
        $this->addColumn('rehearsal', 'Rehearsal', 'INTEGER', true, null, 0);
        $this->addColumn('removed', 'Removed', 'SMALLINT', false, 1, 0);
        $this->addForeignKey('eventGroup', 'EventGroupId', 'INTEGER', 'eventGroups', 'id', false, 30, null);
        $this->addColumn('sermonTitle', 'SermonTitle', 'VARCHAR', false, 64, null);
        $this->addColumn('bibleVerse', 'BibleVerse', 'VARCHAR', false, 64, null);
        $this->addColumn('created', 'Created', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated', 'Updated', 'TIMESTAMP', false, null, null);
    }

    /**
     * Build the RelationMap objects for this table relationships
     *
     * @return void
     */
    public function buildRelations(): void
    {
        $this->addRelation('User', '\\TechWilk\\Rota\\User', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':createdBy',
    1 => ':id',
  ),
), null, null, null, false);
        $this->addRelation('EventType', '\\TechWilk\\Rota\\EventType', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':type',
    1 => ':id',
  ),
), null, null, null, false);
        $this->addRelation('EventSubType', '\\TechWilk\\Rota\\EventSubType', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':subType',
    1 => ':id',
  ),
), null, null, null, false);
        $this->addRelation('Location', '\\TechWilk\\Rota\\Location', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':location',
    1 => ':id',
  ),
), null, null, null, false);
        $this->addRelation('EventGroup', '\\TechWilk\\Rota\\EventGroup', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':eventGroup',
    1 => ':id',
  ),
), null, null, null, false);
        $this->addRelation('Comment', '\\TechWilk\\Rota\\Comment', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':eventId',
    1 => ':id',
  ),
), null, null, 'Comments', false);
        $this->addRelation('EventPerson', '\\TechWilk\\Rota\\EventPerson', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':eventId',
    1 => ':id',
  ),
), null, null, 'Eventpeople', false);
        $this->addRelation('Availability', '\\TechWilk\\Rota\\Availability', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':eventId',
    1 => ':id',
  ),
), null, null, 'Availabilities', false);
    }

    /**
     *
     * Gets the list of behaviors registered for this table
     *
     * @return array<string, array> Associative array (name => parameters) of behaviors
     */
    public function getBehaviors(): array
    {
        return [
            'timestampable' => ['create_column' => 'created', 'update_column' => 'updated', 'disable_created_at' => 'false', 'disable_updated_at' => 'false'],
        ];
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
        // If the PK cannot be derived from the row, return NULL.
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return null === $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] || is_scalar($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)]) || is_callable([$row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)], '__toString']) ? (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] : $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
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
        return (int) $row[
            $indexType == TableMap::TYPE_NUM
                ? 0 + $offset
                : self::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)
        ];
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
        return $withPrefix ? EventTableMap::CLASS_DEFAULT : EventTableMap::OM_CLASS;
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
     * @return array (Event object, last column rank)
     */
    public static function populateObject(array $row, int $offset = 0, string $indexType = TableMap::TYPE_NUM): array
    {
        $key = EventTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = EventTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + EventTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = EventTableMap::OM_CLASS;
            /** @var Event $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            EventTableMap::addInstanceToPool($obj, $key);
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
            $key = EventTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = EventTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Event $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                EventTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(EventTableMap::COL_ID);
            $criteria->addSelectColumn(EventTableMap::COL_DATE);
            $criteria->addSelectColumn(EventTableMap::COL_NAME);
            $criteria->addSelectColumn(EventTableMap::COL_CREATEDBY);
            $criteria->addSelectColumn(EventTableMap::COL_REHEARSALDATE);
            $criteria->addSelectColumn(EventTableMap::COL_TYPE);
            $criteria->addSelectColumn(EventTableMap::COL_SUBTYPE);
            $criteria->addSelectColumn(EventTableMap::COL_LOCATION);
            $criteria->addSelectColumn(EventTableMap::COL_NOTIFIED);
            $criteria->addSelectColumn(EventTableMap::COL_REHEARSAL);
            $criteria->addSelectColumn(EventTableMap::COL_REMOVED);
            $criteria->addSelectColumn(EventTableMap::COL_EVENTGROUP);
            $criteria->addSelectColumn(EventTableMap::COL_SERMONTITLE);
            $criteria->addSelectColumn(EventTableMap::COL_BIBLEVERSE);
            $criteria->addSelectColumn(EventTableMap::COL_CREATED);
            $criteria->addSelectColumn(EventTableMap::COL_UPDATED);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.date');
            $criteria->addSelectColumn($alias . '.name');
            $criteria->addSelectColumn($alias . '.createdBy');
            $criteria->addSelectColumn($alias . '.rehearsalDate');
            $criteria->addSelectColumn($alias . '.type');
            $criteria->addSelectColumn($alias . '.subType');
            $criteria->addSelectColumn($alias . '.location');
            $criteria->addSelectColumn($alias . '.notified');
            $criteria->addSelectColumn($alias . '.rehearsal');
            $criteria->addSelectColumn($alias . '.removed');
            $criteria->addSelectColumn($alias . '.eventGroup');
            $criteria->addSelectColumn($alias . '.sermonTitle');
            $criteria->addSelectColumn($alias . '.bibleVerse');
            $criteria->addSelectColumn($alias . '.created');
            $criteria->addSelectColumn($alias . '.updated');
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
            $criteria->removeSelectColumn(EventTableMap::COL_ID);
            $criteria->removeSelectColumn(EventTableMap::COL_DATE);
            $criteria->removeSelectColumn(EventTableMap::COL_NAME);
            $criteria->removeSelectColumn(EventTableMap::COL_CREATEDBY);
            $criteria->removeSelectColumn(EventTableMap::COL_REHEARSALDATE);
            $criteria->removeSelectColumn(EventTableMap::COL_TYPE);
            $criteria->removeSelectColumn(EventTableMap::COL_SUBTYPE);
            $criteria->removeSelectColumn(EventTableMap::COL_LOCATION);
            $criteria->removeSelectColumn(EventTableMap::COL_NOTIFIED);
            $criteria->removeSelectColumn(EventTableMap::COL_REHEARSAL);
            $criteria->removeSelectColumn(EventTableMap::COL_REMOVED);
            $criteria->removeSelectColumn(EventTableMap::COL_EVENTGROUP);
            $criteria->removeSelectColumn(EventTableMap::COL_SERMONTITLE);
            $criteria->removeSelectColumn(EventTableMap::COL_BIBLEVERSE);
            $criteria->removeSelectColumn(EventTableMap::COL_CREATED);
            $criteria->removeSelectColumn(EventTableMap::COL_UPDATED);
        } else {
            $criteria->removeSelectColumn($alias . '.id');
            $criteria->removeSelectColumn($alias . '.date');
            $criteria->removeSelectColumn($alias . '.name');
            $criteria->removeSelectColumn($alias . '.createdBy');
            $criteria->removeSelectColumn($alias . '.rehearsalDate');
            $criteria->removeSelectColumn($alias . '.type');
            $criteria->removeSelectColumn($alias . '.subType');
            $criteria->removeSelectColumn($alias . '.location');
            $criteria->removeSelectColumn($alias . '.notified');
            $criteria->removeSelectColumn($alias . '.rehearsal');
            $criteria->removeSelectColumn($alias . '.removed');
            $criteria->removeSelectColumn($alias . '.eventGroup');
            $criteria->removeSelectColumn($alias . '.sermonTitle');
            $criteria->removeSelectColumn($alias . '.bibleVerse');
            $criteria->removeSelectColumn($alias . '.created');
            $criteria->removeSelectColumn($alias . '.updated');
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
        return Propel::getServiceContainer()->getDatabaseMap(EventTableMap::DATABASE_NAME)->getTable(EventTableMap::TABLE_NAME);
    }

    /**
     * Performs a DELETE on the database, given a Event or Criteria object OR a primary key value.
     *
     * @param mixed $values Criteria or Event object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(EventTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \TechWilk\Rota\Event) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(EventTableMap::DATABASE_NAME);
            $criteria->add(EventTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = EventQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            EventTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                EventTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the events table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(?ConnectionInterface $con = null): int
    {
        return EventQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Event or Criteria object.
     *
     * @param mixed $criteria Criteria or Event object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed The new primary key.
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ?ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(EventTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Event object
        }


        // Set the correct dbName
        $query = EventQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

}
