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
use TechWilk\Rota\CalendarToken;
use TechWilk\Rota\CalendarTokenQuery;

/**
 * This class defines the structure of the 'cr_calendarTokens' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class CalendarTokenTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'TechWilk.Rota.Map.CalendarTokenTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'cr_calendarTokens';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\TechWilk\\Rota\\CalendarToken';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'TechWilk.Rota.CalendarToken';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 10;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 10;

    /**
     * the column name for the id field
     */
    const COL_ID = 'cr_calendarTokens.id';

    /**
     * the column name for the token field
     */
    const COL_TOKEN = 'cr_calendarTokens.token';

    /**
     * the column name for the userId field
     */
    const COL_USERID = 'cr_calendarTokens.userId';

    /**
     * the column name for the format field
     */
    const COL_FORMAT = 'cr_calendarTokens.format';

    /**
     * the column name for the description field
     */
    const COL_DESCRIPTION = 'cr_calendarTokens.description';

    /**
     * the column name for the revoked field
     */
    const COL_REVOKED = 'cr_calendarTokens.revoked';

    /**
     * the column name for the revokedDate field
     */
    const COL_REVOKEDDATE = 'cr_calendarTokens.revokedDate';

    /**
     * the column name for the lastFetched field
     */
    const COL_LASTFETCHED = 'cr_calendarTokens.lastFetched';

    /**
     * the column name for the created field
     */
    const COL_CREATED = 'cr_calendarTokens.created';

    /**
     * the column name for the updated field
     */
    const COL_UPDATED = 'cr_calendarTokens.updated';

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
        self::TYPE_PHPNAME       => array('Id', 'Token', 'Userid', 'Format', 'Description', 'Revoked', 'RevokedDate', 'LastFetched', 'Created', 'Updated', ),
        self::TYPE_CAMELNAME     => array('id', 'token', 'userid', 'format', 'description', 'revoked', 'revokedDate', 'lastFetched', 'created', 'updated', ),
        self::TYPE_COLNAME       => array(CalendarTokenTableMap::COL_ID, CalendarTokenTableMap::COL_TOKEN, CalendarTokenTableMap::COL_USERID, CalendarTokenTableMap::COL_FORMAT, CalendarTokenTableMap::COL_DESCRIPTION, CalendarTokenTableMap::COL_REVOKED, CalendarTokenTableMap::COL_REVOKEDDATE, CalendarTokenTableMap::COL_LASTFETCHED, CalendarTokenTableMap::COL_CREATED, CalendarTokenTableMap::COL_UPDATED, ),
        self::TYPE_FIELDNAME     => array('id', 'token', 'userId', 'format', 'description', 'revoked', 'revokedDate', 'lastFetched', 'created', 'updated', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array(
        self::TYPE_PHPNAME       => array('Id' => 0, 'Token' => 1, 'Userid' => 2, 'Format' => 3, 'Description' => 4, 'Revoked' => 5, 'RevokedDate' => 6, 'LastFetched' => 7, 'Created' => 8, 'Updated' => 9, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'token' => 1, 'userid' => 2, 'format' => 3, 'description' => 4, 'revoked' => 5, 'revokedDate' => 6, 'lastFetched' => 7, 'created' => 8, 'updated' => 9, ),
        self::TYPE_COLNAME       => array(CalendarTokenTableMap::COL_ID => 0, CalendarTokenTableMap::COL_TOKEN => 1, CalendarTokenTableMap::COL_USERID => 2, CalendarTokenTableMap::COL_FORMAT => 3, CalendarTokenTableMap::COL_DESCRIPTION => 4, CalendarTokenTableMap::COL_REVOKED => 5, CalendarTokenTableMap::COL_REVOKEDDATE => 6, CalendarTokenTableMap::COL_LASTFETCHED => 7, CalendarTokenTableMap::COL_CREATED => 8, CalendarTokenTableMap::COL_UPDATED => 9, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'token' => 1, 'userId' => 2, 'format' => 3, 'description' => 4, 'revoked' => 5, 'revokedDate' => 6, 'lastFetched' => 7, 'created' => 8, 'updated' => 9, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, )
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
        $this->setName('cr_calendarTokens');
        $this->setPhpName('CalendarToken');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\TechWilk\\Rota\\CalendarToken');
        $this->setPackage('TechWilk.Rota');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('token', 'Token', 'VARCHAR', true, 30, null);
        $this->addForeignKey('userId', 'Userid', 'INTEGER', 'cr_users', 'id', true, 30, null);
        $this->addColumn('format', 'Format', 'VARCHAR', true, 5, null);
        $this->addColumn('description', 'Description', 'VARCHAR', false, 100, null);
        $this->addColumn('revoked', 'Revoked', 'BOOLEAN', true, 1, false);
        $this->addColumn('revokedDate', 'RevokedDate', 'TIMESTAMP', false, null, null);
        $this->addColumn('lastFetched', 'LastFetched', 'TIMESTAMP', false, null, null);
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
    0 => ':userId',
    1 => ':id',
  ),
), null, null, null, false);
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
        return $withPrefix ? CalendarTokenTableMap::CLASS_DEFAULT : CalendarTokenTableMap::OM_CLASS;
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
     * @return array           (CalendarToken object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = CalendarTokenTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = CalendarTokenTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + CalendarTokenTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = CalendarTokenTableMap::OM_CLASS;
            /** @var CalendarToken $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            CalendarTokenTableMap::addInstanceToPool($obj, $key);
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
            $key = CalendarTokenTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = CalendarTokenTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var CalendarToken $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                CalendarTokenTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(CalendarTokenTableMap::COL_ID);
            $criteria->addSelectColumn(CalendarTokenTableMap::COL_TOKEN);
            $criteria->addSelectColumn(CalendarTokenTableMap::COL_USERID);
            $criteria->addSelectColumn(CalendarTokenTableMap::COL_FORMAT);
            $criteria->addSelectColumn(CalendarTokenTableMap::COL_DESCRIPTION);
            $criteria->addSelectColumn(CalendarTokenTableMap::COL_REVOKED);
            $criteria->addSelectColumn(CalendarTokenTableMap::COL_REVOKEDDATE);
            $criteria->addSelectColumn(CalendarTokenTableMap::COL_LASTFETCHED);
            $criteria->addSelectColumn(CalendarTokenTableMap::COL_CREATED);
            $criteria->addSelectColumn(CalendarTokenTableMap::COL_UPDATED);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.token');
            $criteria->addSelectColumn($alias . '.userId');
            $criteria->addSelectColumn($alias . '.format');
            $criteria->addSelectColumn($alias . '.description');
            $criteria->addSelectColumn($alias . '.revoked');
            $criteria->addSelectColumn($alias . '.revokedDate');
            $criteria->addSelectColumn($alias . '.lastFetched');
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
        return Propel::getServiceContainer()->getDatabaseMap(CalendarTokenTableMap::DATABASE_NAME)->getTable(CalendarTokenTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(CalendarTokenTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(CalendarTokenTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new CalendarTokenTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a CalendarToken or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or CalendarToken object or primary key or array of primary keys
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
             $con = Propel::getServiceContainer()->getWriteConnection(CalendarTokenTableMap::DATABASE_NAME);
         }

         if ($values instanceof Criteria) {
             // rename for clarity
            $criteria = $values;
         } elseif ($values instanceof \TechWilk\Rota\CalendarToken) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
         } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(CalendarTokenTableMap::DATABASE_NAME);
             $criteria->add(CalendarTokenTableMap::COL_ID, (array) $values, Criteria::IN);
         }

         $query = CalendarTokenQuery::create()->mergeWith($criteria);

         if ($values instanceof Criteria) {
             CalendarTokenTableMap::clearInstancePool();
         } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                CalendarTokenTableMap::removeInstanceFromPool($singleval);
            }
         }

         return $query->delete($con);
     }

    /**
     * Deletes all rows from the cr_calendarTokens table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return CalendarTokenQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a CalendarToken or Criteria object.
     *
     * @param mixed               $criteria Criteria or CalendarToken object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(CalendarTokenTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from CalendarToken object
        }

        if ($criteria->containsKey(CalendarTokenTableMap::COL_ID) && $criteria->keyContainsValue(CalendarTokenTableMap::COL_ID)) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.CalendarTokenTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = CalendarTokenQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }
} // CalendarTokenTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
CalendarTokenTableMap::buildTableMap();
