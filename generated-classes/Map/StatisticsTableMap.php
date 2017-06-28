<?php

namespace Map;

use \Statistics;
use \StatisticsQuery;
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
 * This class defines the structure of the 'cr_statistics' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class StatisticsTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = '.Map.StatisticsTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'cr_statistics';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\Statistics';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Statistics';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 8;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 8;

    /**
     * the column name for the id field
     */
    const COL_ID = 'cr_statistics.id';

    /**
     * the column name for the userid field
     */
    const COL_USERID = 'cr_statistics.userid';

    /**
     * the column name for the date field
     */
    const COL_DATE = 'cr_statistics.date';

    /**
     * the column name for the type field
     */
    const COL_TYPE = 'cr_statistics.type';

    /**
     * the column name for the detail1 field
     */
    const COL_DETAIL1 = 'cr_statistics.detail1';

    /**
     * the column name for the detail2 field
     */
    const COL_DETAIL2 = 'cr_statistics.detail2';

    /**
     * the column name for the detail3 field
     */
    const COL_DETAIL3 = 'cr_statistics.detail3';

    /**
     * the column name for the script field
     */
    const COL_SCRIPT = 'cr_statistics.script';

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
        self::TYPE_PHPNAME       => array('Id', 'UserId', 'Date', 'Type', 'Detail1', 'Detail2', 'Detail3', 'Script', ),
        self::TYPE_CAMELNAME     => array('id', 'userId', 'date', 'type', 'detail1', 'detail2', 'detail3', 'script', ),
        self::TYPE_COLNAME       => array(StatisticsTableMap::COL_ID, StatisticsTableMap::COL_USERID, StatisticsTableMap::COL_DATE, StatisticsTableMap::COL_TYPE, StatisticsTableMap::COL_DETAIL1, StatisticsTableMap::COL_DETAIL2, StatisticsTableMap::COL_DETAIL3, StatisticsTableMap::COL_SCRIPT, ),
        self::TYPE_FIELDNAME     => array('id', 'userid', 'date', 'type', 'detail1', 'detail2', 'detail3', 'script', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array(
        self::TYPE_PHPNAME       => array('Id' => 0, 'UserId' => 1, 'Date' => 2, 'Type' => 3, 'Detail1' => 4, 'Detail2' => 5, 'Detail3' => 6, 'Script' => 7, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'userId' => 1, 'date' => 2, 'type' => 3, 'detail1' => 4, 'detail2' => 5, 'detail3' => 6, 'script' => 7, ),
        self::TYPE_COLNAME       => array(StatisticsTableMap::COL_ID => 0, StatisticsTableMap::COL_USERID => 1, StatisticsTableMap::COL_DATE => 2, StatisticsTableMap::COL_TYPE => 3, StatisticsTableMap::COL_DETAIL1 => 4, StatisticsTableMap::COL_DETAIL2 => 5, StatisticsTableMap::COL_DETAIL3 => 6, StatisticsTableMap::COL_SCRIPT => 7, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'userid' => 1, 'date' => 2, 'type' => 3, 'detail1' => 4, 'detail2' => 5, 'detail3' => 6, 'script' => 7, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, )
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
        $this->setName('cr_statistics');
        $this->setPhpName('Statistics');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Statistics');
        $this->setPackage('');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('userid', 'UserId', 'INTEGER', 'cr_users', 'id', true, 6, 0);
        $this->addColumn('date', 'Date', 'TIMESTAMP', true, null, '0000-00-00 00:00:00');
        $this->addColumn('type', 'Type', 'LONGVARCHAR', true, null, null);
        $this->addColumn('detail1', 'Detail1', 'LONGVARCHAR', true, null, null);
        $this->addColumn('detail2', 'Detail2', 'LONGVARCHAR', true, null, null);
        $this->addColumn('detail3', 'Detail3', 'LONGVARCHAR', true, null, null);
        $this->addColumn('script', 'Script', 'LONGVARCHAR', true, null, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('User', '\\User', RelationMap::MANY_TO_ONE, array(
  0 =>
  array(
    0 => ':userid',
    1 => ':id',
  ),
), null, null, null, false);
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
        return $withPrefix ? StatisticsTableMap::CLASS_DEFAULT : StatisticsTableMap::OM_CLASS;
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
     * @return array           (Statistics object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = StatisticsTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = StatisticsTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + StatisticsTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = StatisticsTableMap::OM_CLASS;
            /** @var Statistics $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            StatisticsTableMap::addInstanceToPool($obj, $key);
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
            $key = StatisticsTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = StatisticsTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Statistics $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                StatisticsTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(StatisticsTableMap::COL_ID);
            $criteria->addSelectColumn(StatisticsTableMap::COL_USERID);
            $criteria->addSelectColumn(StatisticsTableMap::COL_DATE);
            $criteria->addSelectColumn(StatisticsTableMap::COL_TYPE);
            $criteria->addSelectColumn(StatisticsTableMap::COL_DETAIL1);
            $criteria->addSelectColumn(StatisticsTableMap::COL_DETAIL2);
            $criteria->addSelectColumn(StatisticsTableMap::COL_DETAIL3);
            $criteria->addSelectColumn(StatisticsTableMap::COL_SCRIPT);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.userid');
            $criteria->addSelectColumn($alias . '.date');
            $criteria->addSelectColumn($alias . '.type');
            $criteria->addSelectColumn($alias . '.detail1');
            $criteria->addSelectColumn($alias . '.detail2');
            $criteria->addSelectColumn($alias . '.detail3');
            $criteria->addSelectColumn($alias . '.script');
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
        return Propel::getServiceContainer()->getDatabaseMap(StatisticsTableMap::DATABASE_NAME)->getTable(StatisticsTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(StatisticsTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(StatisticsTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new StatisticsTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a Statistics or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Statistics object or primary key or array of primary keys
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
             $con = Propel::getServiceContainer()->getWriteConnection(StatisticsTableMap::DATABASE_NAME);
         }

         if ($values instanceof Criteria) {
             // rename for clarity
            $criteria = $values;
         } elseif ($values instanceof \Statistics) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
         } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(StatisticsTableMap::DATABASE_NAME);
             $criteria->add(StatisticsTableMap::COL_ID, (array) $values, Criteria::IN);
         }

         $query = StatisticsQuery::create()->mergeWith($criteria);

         if ($values instanceof Criteria) {
             StatisticsTableMap::clearInstancePool();
         } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                StatisticsTableMap::removeInstanceFromPool($singleval);
            }
         }

         return $query->delete($con);
     }

    /**
     * Deletes all rows from the cr_statistics table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return StatisticsQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Statistics or Criteria object.
     *
     * @param mixed               $criteria Criteria or Statistics object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(StatisticsTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Statistics object
        }

        if ($criteria->containsKey(StatisticsTableMap::COL_ID) && $criteria->keyContainsValue(StatisticsTableMap::COL_ID)) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.StatisticsTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = StatisticsQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }
} // StatisticsTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
StatisticsTableMap::buildTableMap();
