<?php

namespace Map;

use \EventPerson;
use \EventPersonQuery;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\InstancePoolTrait;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\DataFetcher\DataFetcherInterface;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\RelationMap;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Map\TableMapTrait;

/**
 * This class defines the structure of the 'cr_eventPeople' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class EventPersonTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = '.Map.EventPersonTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'cr_eventPeople';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\EventPerson';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'EventPerson';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 5;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 5;

    /**
     * the column name for the id field
     */
    const COL_ID = 'cr_eventPeople.id';

    /**
     * the column name for the eventId field
     */
    const COL_EVENTID = 'cr_eventPeople.eventId';

    /**
     * the column name for the userRoleId field
     */
    const COL_USERROLEID = 'cr_eventPeople.userRoleId';

    /**
     * the column name for the notified field
     */
    const COL_NOTIFIED = 'cr_eventPeople.notified';

    /**
     * the column name for the removed field
     */
    const COL_REMOVED = 'cr_eventPeople.removed';

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
        self::TYPE_PHPNAME       => array('Id', 'EventId', 'UserRoleId', 'Notified', 'Removed', ),
        self::TYPE_CAMELNAME     => array('id', 'eventId', 'userRoleId', 'notified', 'removed', ),
        self::TYPE_COLNAME       => array(EventPersonTableMap::COL_ID, EventPersonTableMap::COL_EVENTID, EventPersonTableMap::COL_USERROLEID, EventPersonTableMap::COL_NOTIFIED, EventPersonTableMap::COL_REMOVED, ),
        self::TYPE_FIELDNAME     => array('id', 'eventId', 'userRoleId', 'notified', 'removed', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array(
        self::TYPE_PHPNAME       => array('Id' => 0, 'EventId' => 1, 'UserRoleId' => 2, 'Notified' => 3, 'Removed' => 4, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'eventId' => 1, 'userRoleId' => 2, 'notified' => 3, 'removed' => 4, ),
        self::TYPE_COLNAME       => array(EventPersonTableMap::COL_ID => 0, EventPersonTableMap::COL_EVENTID => 1, EventPersonTableMap::COL_USERROLEID => 2, EventPersonTableMap::COL_NOTIFIED => 3, EventPersonTableMap::COL_REMOVED => 4, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'eventId' => 1, 'userRoleId' => 2, 'notified' => 3, 'removed' => 4, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, )
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
        $this->setName('cr_eventPeople');
        $this->setPhpName('EventPerson');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\EventPerson');
        $this->setPackage('');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('eventId', 'EventId', 'INTEGER', 'cr_events', 'id', true, null, 0);
        $this->addForeignKey('userRoleId', 'UserRoleId', 'INTEGER', 'cr_userRoles', 'id', true, null, 0);
        $this->addColumn('notified', 'Notified', 'SMALLINT', true, 1, 0);
        $this->addColumn('removed', 'Removed', 'SMALLINT', false, 1, 0);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Event', '\\Event', RelationMap::MANY_TO_ONE, array(
  0 =>
  array(
    0 => ':eventId',
    1 => ':id',
  ),
), null, null, null, false);
        $this->addRelation('UserRole', '\\UserRole', RelationMap::MANY_TO_ONE, array(
  0 =>
  array(
    0 => ':userRoleId',
    1 => ':id',
  ),
), null, null, null, false);
        $this->addRelation('Swap', '\\Swap', RelationMap::ONE_TO_MANY, array(
  0 =>
  array(
    0 => ':eventPersonId',
    1 => ':id',
  ),
), null, null, 'Swaps', false);
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
        return $withPrefix ? EventPersonTableMap::CLASS_DEFAULT : EventPersonTableMap::OM_CLASS;
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
     * @return array           (EventPerson object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = EventPersonTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = EventPersonTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + EventPersonTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = EventPersonTableMap::OM_CLASS;
            /** @var EventPerson $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            EventPersonTableMap::addInstanceToPool($obj, $key);
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
            $key = EventPersonTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = EventPersonTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var EventPerson $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                EventPersonTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(EventPersonTableMap::COL_ID);
            $criteria->addSelectColumn(EventPersonTableMap::COL_EVENTID);
            $criteria->addSelectColumn(EventPersonTableMap::COL_USERROLEID);
            $criteria->addSelectColumn(EventPersonTableMap::COL_NOTIFIED);
            $criteria->addSelectColumn(EventPersonTableMap::COL_REMOVED);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.eventId');
            $criteria->addSelectColumn($alias . '.userRoleId');
            $criteria->addSelectColumn($alias . '.notified');
            $criteria->addSelectColumn($alias . '.removed');
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
        return Propel::getServiceContainer()->getDatabaseMap(EventPersonTableMap::DATABASE_NAME)->getTable(EventPersonTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(EventPersonTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(EventPersonTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new EventPersonTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a EventPerson or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or EventPerson object or primary key or array of primary keys
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
             $con = Propel::getServiceContainer()->getWriteConnection(EventPersonTableMap::DATABASE_NAME);
         }

         if ($values instanceof Criteria) {
             // rename for clarity
            $criteria = $values;
         } elseif ($values instanceof \EventPerson) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
         } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(EventPersonTableMap::DATABASE_NAME);
             $criteria->add(EventPersonTableMap::COL_ID, (array) $values, Criteria::IN);
         }

         $query = EventPersonQuery::create()->mergeWith($criteria);

         if ($values instanceof Criteria) {
             EventPersonTableMap::clearInstancePool();
         } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                EventPersonTableMap::removeInstanceFromPool($singleval);
            }
         }

         return $query->delete($con);
     }

    /**
     * Deletes all rows from the cr_eventPeople table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return EventPersonQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a EventPerson or Criteria object.
     *
     * @param mixed               $criteria Criteria or EventPerson object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(EventPersonTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from EventPerson object
        }

        if ($criteria->containsKey(EventPersonTableMap::COL_ID) && $criteria->keyContainsValue(EventPersonTableMap::COL_ID)) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.EventPersonTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = EventPersonQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }
} // EventPersonTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
EventPersonTableMap::buildTableMap();
