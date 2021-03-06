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
use TechWilk\Rota\Discussion;
use TechWilk\Rota\DiscussionQuery;

/**
 * This class defines the structure of the 'discussion' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class DiscussionTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'TechWilk.Rota.Map.DiscussionTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'discussion';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\TechWilk\\Rota\\Discussion';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'TechWilk.Rota.Discussion';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 7;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 7;

    /**
     * the column name for the id field
     */
    const COL_ID = 'discussion.id';

    /**
     * the column name for the topicParent field
     */
    const COL_TOPICPARENT = 'discussion.topicParent';

    /**
     * the column name for the CategoryParent field
     */
    const COL_CATEGORYPARENT = 'discussion.CategoryParent';

    /**
     * the column name for the userID field
     */
    const COL_USERID = 'discussion.userID';

    /**
     * the column name for the topic field
     */
    const COL_TOPIC = 'discussion.topic';

    /**
     * the column name for the topicName field
     */
    const COL_TOPICNAME = 'discussion.topicName';

    /**
     * the column name for the date field
     */
    const COL_DATE = 'discussion.date';

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
        self::TYPE_PHPNAME       => array('Id', 'Topicparent', 'Categoryparent', 'Userid', 'Topic', 'Topicname', 'Date', ),
        self::TYPE_CAMELNAME     => array('id', 'topicparent', 'categoryparent', 'userid', 'topic', 'topicname', 'date', ),
        self::TYPE_COLNAME       => array(DiscussionTableMap::COL_ID, DiscussionTableMap::COL_TOPICPARENT, DiscussionTableMap::COL_CATEGORYPARENT, DiscussionTableMap::COL_USERID, DiscussionTableMap::COL_TOPIC, DiscussionTableMap::COL_TOPICNAME, DiscussionTableMap::COL_DATE, ),
        self::TYPE_FIELDNAME     => array('id', 'topicParent', 'CategoryParent', 'userID', 'topic', 'topicName', 'date', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array(
        self::TYPE_PHPNAME       => array('Id' => 0, 'Topicparent' => 1, 'Categoryparent' => 2, 'Userid' => 3, 'Topic' => 4, 'Topicname' => 5, 'Date' => 6, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'topicparent' => 1, 'categoryparent' => 2, 'userid' => 3, 'topic' => 4, 'topicname' => 5, 'date' => 6, ),
        self::TYPE_COLNAME       => array(DiscussionTableMap::COL_ID => 0, DiscussionTableMap::COL_TOPICPARENT => 1, DiscussionTableMap::COL_CATEGORYPARENT => 2, DiscussionTableMap::COL_USERID => 3, DiscussionTableMap::COL_TOPIC => 4, DiscussionTableMap::COL_TOPICNAME => 5, DiscussionTableMap::COL_DATE => 6, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'topicParent' => 1, 'CategoryParent' => 2, 'userID' => 3, 'topic' => 4, 'topicName' => 5, 'date' => 6, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, )
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
        $this->setName('discussion');
        $this->setPhpName('Discussion');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\TechWilk\\Rota\\Discussion');
        $this->setPackage('TechWilk.Rota');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('topicParent', 'Topicparent', 'INTEGER', true, 6, 0);
        $this->addColumn('CategoryParent', 'Categoryparent', 'INTEGER', true, 6, 0);
        $this->addColumn('userID', 'Userid', 'INTEGER', true, 6, 0);
        $this->addColumn('topic', 'Topic', 'LONGVARCHAR', true, null, null);
        $this->addColumn('topicName', 'Topicname', 'LONGVARCHAR', true, null, null);
        $this->addColumn('date', 'Date', 'TIMESTAMP', true, null, '0000-00-00 00:00:00');
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
        return $withPrefix ? DiscussionTableMap::CLASS_DEFAULT : DiscussionTableMap::OM_CLASS;
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
     * @return array           (Discussion object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = DiscussionTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = DiscussionTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + DiscussionTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = DiscussionTableMap::OM_CLASS;
            /** @var Discussion $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            DiscussionTableMap::addInstanceToPool($obj, $key);
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
            $key = DiscussionTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = DiscussionTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Discussion $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                DiscussionTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(DiscussionTableMap::COL_ID);
            $criteria->addSelectColumn(DiscussionTableMap::COL_TOPICPARENT);
            $criteria->addSelectColumn(DiscussionTableMap::COL_CATEGORYPARENT);
            $criteria->addSelectColumn(DiscussionTableMap::COL_USERID);
            $criteria->addSelectColumn(DiscussionTableMap::COL_TOPIC);
            $criteria->addSelectColumn(DiscussionTableMap::COL_TOPICNAME);
            $criteria->addSelectColumn(DiscussionTableMap::COL_DATE);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.topicParent');
            $criteria->addSelectColumn($alias . '.CategoryParent');
            $criteria->addSelectColumn($alias . '.userID');
            $criteria->addSelectColumn($alias . '.topic');
            $criteria->addSelectColumn($alias . '.topicName');
            $criteria->addSelectColumn($alias . '.date');
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
        return Propel::getServiceContainer()->getDatabaseMap(DiscussionTableMap::DATABASE_NAME)->getTable(DiscussionTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(DiscussionTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(DiscussionTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new DiscussionTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a Discussion or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Discussion object or primary key or array of primary keys
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
             $con = Propel::getServiceContainer()->getWriteConnection(DiscussionTableMap::DATABASE_NAME);
         }

         if ($values instanceof Criteria) {
             // rename for clarity
            $criteria = $values;
         } elseif ($values instanceof \TechWilk\Rota\Discussion) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
         } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(DiscussionTableMap::DATABASE_NAME);
             $criteria->add(DiscussionTableMap::COL_ID, (array) $values, Criteria::IN);
         }

         $query = DiscussionQuery::create()->mergeWith($criteria);

         if ($values instanceof Criteria) {
             DiscussionTableMap::clearInstancePool();
         } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                DiscussionTableMap::removeInstanceFromPool($singleval);
            }
         }

         return $query->delete($con);
     }

    /**
     * Deletes all rows from the discussion table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return DiscussionQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Discussion or Criteria object.
     *
     * @param mixed               $criteria Criteria or Discussion object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(DiscussionTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Discussion object
        }

        if ($criteria->containsKey(DiscussionTableMap::COL_ID) && $criteria->keyContainsValue(DiscussionTableMap::COL_ID)) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.DiscussionTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = DiscussionQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }
} // DiscussionTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
DiscussionTableMap::buildTableMap();
