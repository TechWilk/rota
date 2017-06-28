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
 * This class defines the structure of the 'cr_events' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class EventTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'TechWilk.Rota.Map.EventTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'cr_events';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\TechWilk\\Rota\\Event';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'TechWilk.Rota.Event';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 17;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 17;

    /**
     * the column name for the id field
     */
    const COL_ID = 'cr_events.id';

    /**
     * the column name for the date field
     */
    const COL_DATE = 'cr_events.date';

    /**
     * the column name for the name field
     */
    const COL_NAME = 'cr_events.name';

    /**
     * the column name for the createdBy field
     */
    const COL_CREATEDBY = 'cr_events.createdBy';

    /**
     * the column name for the rehearsalDate field
     */
    const COL_REHEARSALDATE = 'cr_events.rehearsalDate';

    /**
     * the column name for the type field
     */
    const COL_TYPE = 'cr_events.type';

    /**
     * the column name for the subType field
     */
    const COL_SUBTYPE = 'cr_events.subType';

    /**
     * the column name for the location field
     */
    const COL_LOCATION = 'cr_events.location';

    /**
     * the column name for the notified field
     */
    const COL_NOTIFIED = 'cr_events.notified';

    /**
     * the column name for the rehearsal field
     */
    const COL_REHEARSAL = 'cr_events.rehearsal';

    /**
     * the column name for the comment field
     */
    const COL_COMMENT = 'cr_events.comment';

    /**
     * the column name for the removed field
     */
    const COL_REMOVED = 'cr_events.removed';

    /**
     * the column name for the eventGroup field
     */
    const COL_EVENTGROUP = 'cr_events.eventGroup';

    /**
     * the column name for the sermonTitle field
     */
    const COL_SERMONTITLE = 'cr_events.sermonTitle';

    /**
     * the column name for the bibleVerse field
     */
    const COL_BIBLEVERSE = 'cr_events.bibleVerse';

    /**
     * the column name for the created field
     */
    const COL_CREATED = 'cr_events.created';

    /**
     * the column name for the updated field
     */
    const COL_UPDATED = 'cr_events.updated';

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
        self::TYPE_PHPNAME       => array('Id', 'Date', 'Name', 'CreatedBy', 'RehearsalDate', 'EventTypeId', 'EventSubTypeId', 'LocationId', 'Notified', 'Rehearsal', 'Comment', 'Removed', 'EventGroupId', 'SermonTitle', 'BibleVerse', 'Created', 'Updated', ),
        self::TYPE_CAMELNAME     => array('id', 'date', 'name', 'createdBy', 'rehearsalDate', 'eventTypeId', 'eventSubTypeId', 'locationId', 'notified', 'rehearsal', 'comment', 'removed', 'eventGroupId', 'sermonTitle', 'bibleVerse', 'created', 'updated', ),
        self::TYPE_COLNAME       => array(EventTableMap::COL_ID, EventTableMap::COL_DATE, EventTableMap::COL_NAME, EventTableMap::COL_CREATEDBY, EventTableMap::COL_REHEARSALDATE, EventTableMap::COL_TYPE, EventTableMap::COL_SUBTYPE, EventTableMap::COL_LOCATION, EventTableMap::COL_NOTIFIED, EventTableMap::COL_REHEARSAL, EventTableMap::COL_COMMENT, EventTableMap::COL_REMOVED, EventTableMap::COL_EVENTGROUP, EventTableMap::COL_SERMONTITLE, EventTableMap::COL_BIBLEVERSE, EventTableMap::COL_CREATED, EventTableMap::COL_UPDATED, ),
        self::TYPE_FIELDNAME     => array('id', 'date', 'name', 'createdBy', 'rehearsalDate', 'type', 'subType', 'location', 'notified', 'rehearsal', 'comment', 'removed', 'eventGroup', 'sermonTitle', 'bibleVerse', 'created', 'updated', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array(
        self::TYPE_PHPNAME       => array('Id' => 0, 'Date' => 1, 'Name' => 2, 'CreatedBy' => 3, 'RehearsalDate' => 4, 'EventTypeId' => 5, 'EventSubTypeId' => 6, 'LocationId' => 7, 'Notified' => 8, 'Rehearsal' => 9, 'Comment' => 10, 'Removed' => 11, 'EventGroupId' => 12, 'SermonTitle' => 13, 'BibleVerse' => 14, 'Created' => 15, 'Updated' => 16, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'date' => 1, 'name' => 2, 'createdBy' => 3, 'rehearsalDate' => 4, 'eventTypeId' => 5, 'eventSubTypeId' => 6, 'locationId' => 7, 'notified' => 8, 'rehearsal' => 9, 'comment' => 10, 'removed' => 11, 'eventGroupId' => 12, 'sermonTitle' => 13, 'bibleVerse' => 14, 'created' => 15, 'updated' => 16, ),
        self::TYPE_COLNAME       => array(EventTableMap::COL_ID => 0, EventTableMap::COL_DATE => 1, EventTableMap::COL_NAME => 2, EventTableMap::COL_CREATEDBY => 3, EventTableMap::COL_REHEARSALDATE => 4, EventTableMap::COL_TYPE => 5, EventTableMap::COL_SUBTYPE => 6, EventTableMap::COL_LOCATION => 7, EventTableMap::COL_NOTIFIED => 8, EventTableMap::COL_REHEARSAL => 9, EventTableMap::COL_COMMENT => 10, EventTableMap::COL_REMOVED => 11, EventTableMap::COL_EVENTGROUP => 12, EventTableMap::COL_SERMONTITLE => 13, EventTableMap::COL_BIBLEVERSE => 14, EventTableMap::COL_CREATED => 15, EventTableMap::COL_UPDATED => 16, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'date' => 1, 'name' => 2, 'createdBy' => 3, 'rehearsalDate' => 4, 'type' => 5, 'subType' => 6, 'location' => 7, 'notified' => 8, 'rehearsal' => 9, 'comment' => 10, 'removed' => 11, 'eventGroup' => 12, 'sermonTitle' => 13, 'bibleVerse' => 14, 'created' => 15, 'updated' => 16, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, )
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
        $this->setName('cr_events');
        $this->setPhpName('Event');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\TechWilk\\Rota\\Event');
        $this->setPackage('TechWilk.Rota');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, 6, null);
        $this->addColumn('date', 'Date', 'TIMESTAMP', true, null, '0000-00-00 00:00:00');
        $this->addColumn('name', 'Name', 'LONGVARCHAR', true, null, null);
        $this->addForeignKey('createdBy', 'CreatedBy', 'INTEGER', 'cr_users', 'id', true, 5, 0);
        $this->addColumn('rehearsalDate', 'RehearsalDate', 'TIMESTAMP', true, null, '0000-00-00 00:00:00');
        $this->addForeignKey('type', 'EventTypeId', 'INTEGER', 'cr_eventTypes', 'id', true, 30, 0);
        $this->addForeignKey('subType', 'EventSubTypeId', 'INTEGER', 'cr_eventSubTypes', 'id', true, 30, 0);
        $this->addForeignKey('location', 'LocationId', 'INTEGER', 'cr_locations', 'id', true, null, 0);
        $this->addColumn('notified', 'Notified', 'INTEGER', true, 2, 0);
        $this->addColumn('rehearsal', 'Rehearsal', 'INTEGER', true, null, 0);
        $this->addColumn('comment', 'Comment', 'LONGVARCHAR', true, null, null);
        $this->addColumn('removed', 'Removed', 'SMALLINT', false, 1, 0);
        $this->addForeignKey('eventGroup', 'EventGroupId', 'INTEGER', 'cr_eventGroups', 'id', true, 30, null);
        $this->addColumn('sermonTitle', 'SermonTitle', 'VARCHAR', true, 64, '');
        $this->addColumn('bibleVerse', 'BibleVerse', 'VARCHAR', true, 64, '');
        $this->addColumn('created', 'Created', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated', 'Updated', 'TIMESTAMP', false, null, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('User', '\\TechWilk\\Rota\\User', RelationMap::MANY_TO_ONE, array(
  0 =>
  array(
    0 => ':createdBy',
    1 => ':id',
  ),
), null, null, null, false);
        $this->addRelation('EventType', '\\TechWilk\\Rota\\EventType', RelationMap::MANY_TO_ONE, array(
  0 =>
  array(
    0 => ':type',
    1 => ':id',
  ),
), null, null, null, false);
        $this->addRelation('EventSubType', '\\TechWilk\\Rota\\EventSubType', RelationMap::MANY_TO_ONE, array(
  0 =>
  array(
    0 => ':subType',
    1 => ':id',
  ),
), null, null, null, false);
        $this->addRelation('Location', '\\TechWilk\\Rota\\Location', RelationMap::MANY_TO_ONE, array(
  0 =>
  array(
    0 => ':location',
    1 => ':id',
  ),
), null, null, null, false);
        $this->addRelation('EventGroup', '\\TechWilk\\Rota\\EventGroup', RelationMap::MANY_TO_ONE, array(
  0 =>
  array(
    0 => ':eventGroup',
    1 => ':id',
  ),
), null, null, null, false);
        $this->addRelation('EventPerson', '\\TechWilk\\Rota\\EventPerson', RelationMap::ONE_TO_MANY, array(
  0 =>
  array(
    0 => ':eventId',
    1 => ':id',
  ),
), null, null, 'Eventpeople', false);
    } // buildRelations()

    /**
     *
     * Gets the list of behaviors registered for this table
     *
     * @return array Associative array (name => parameters) of behaviors
     */
    public function getBehaviors()
    {
        return array(
            'timestampable' => array('create_column' => 'created', 'update_column' => 'updated', 'disable_created_at' => 'false', 'disable_updated_at' => 'false', ),
        );
    } // getBehaviors()

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
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return mixed The primary key of the row
     */
    public static function getPrimaryKeyFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
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
     * @param boolean $withPrefix Whether or not to return the path with the class name
     * @return string path.to.ClassName
     */
    public static function getOMClass($withPrefix = true)
    {
        return $withPrefix ? EventTableMap::CLASS_DEFAULT : EventTableMap::OM_CLASS;
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
     * @return array           (Event object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
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
     * @param Criteria $criteria object containing the columns to add.
     * @param string   $alias    optional table alias
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function addSelectColumns(Criteria $criteria, $alias = null)
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
            $criteria->addSelectColumn(EventTableMap::COL_COMMENT);
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
            $criteria->addSelectColumn($alias . '.comment');
            $criteria->addSelectColumn($alias . '.removed');
            $criteria->addSelectColumn($alias . '.eventGroup');
            $criteria->addSelectColumn($alias . '.sermonTitle');
            $criteria->addSelectColumn($alias . '.bibleVerse');
            $criteria->addSelectColumn($alias . '.created');
            $criteria->addSelectColumn($alias . '.updated');
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
        return Propel::getServiceContainer()->getDatabaseMap(EventTableMap::DATABASE_NAME)->getTable(EventTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(EventTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(EventTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new EventTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a Event or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Event object or primary key or array of primary keys
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
     * Deletes all rows from the cr_events table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return EventQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Event or Criteria object.
     *
     * @param mixed               $criteria Criteria or Event object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(EventTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Event object
        }

        if ($criteria->containsKey(EventTableMap::COL_ID) && $criteria->keyContainsValue(EventTableMap::COL_ID)) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.EventTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = EventQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }
} // EventTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
EventTableMap::buildTableMap();
