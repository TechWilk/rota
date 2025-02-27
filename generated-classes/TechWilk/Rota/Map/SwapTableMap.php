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
use TechWilk\Rota\Swap;
use TechWilk\Rota\SwapQuery;


/**
 * This class defines the structure of the 'swaps' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 */
class SwapTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    public const CLASS_NAME = 'TechWilk.Rota.Map.SwapTableMap';

    /**
     * The default database name for this class
     */
    public const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    public const TABLE_NAME = 'swaps';

    /**
     * The PHP name of this class (PascalCase)
     */
    public const TABLE_PHP_NAME = 'Swap';

    /**
     * The related Propel class for this table
     */
    public const OM_CLASS = '\\TechWilk\\Rota\\Swap';

    /**
     * A class that can be returned by this tableMap
     */
    public const CLASS_DEFAULT = 'TechWilk.Rota.Swap';

    /**
     * The total number of columns
     */
    public const NUM_COLUMNS = 10;

    /**
     * The number of lazy-loaded columns
     */
    public const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    public const NUM_HYDRATE_COLUMNS = 10;

    /**
     * the column name for the id field
     */
    public const COL_ID = 'swaps.id';

    /**
     * the column name for the eventPersonId field
     */
    public const COL_EVENTPERSONID = 'swaps.eventPersonId';

    /**
     * the column name for the oldUserRoleId field
     */
    public const COL_OLDUSERROLEID = 'swaps.oldUserRoleId';

    /**
     * the column name for the newUserRoleId field
     */
    public const COL_NEWUSERROLEID = 'swaps.newUserRoleId';

    /**
     * the column name for the accepted field
     */
    public const COL_ACCEPTED = 'swaps.accepted';

    /**
     * the column name for the declined field
     */
    public const COL_DECLINED = 'swaps.declined';

    /**
     * the column name for the requestedBy field
     */
    public const COL_REQUESTEDBY = 'swaps.requestedBy';

    /**
     * the column name for the verificationCode field
     */
    public const COL_VERIFICATIONCODE = 'swaps.verificationCode';

    /**
     * the column name for the created field
     */
    public const COL_CREATED = 'swaps.created';

    /**
     * the column name for the updated field
     */
    public const COL_UPDATED = 'swaps.updated';

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
        self::TYPE_PHPNAME       => ['Id', 'EventPersonId', 'OldUserRoleId', 'NewUserRoleId', 'Accepted', 'Declined', 'RequestedBy', 'VerificationCode', 'Created', 'Updated', ],
        self::TYPE_CAMELNAME     => ['id', 'eventPersonId', 'oldUserRoleId', 'newUserRoleId', 'accepted', 'declined', 'requestedBy', 'verificationCode', 'created', 'updated', ],
        self::TYPE_COLNAME       => [SwapTableMap::COL_ID, SwapTableMap::COL_EVENTPERSONID, SwapTableMap::COL_OLDUSERROLEID, SwapTableMap::COL_NEWUSERROLEID, SwapTableMap::COL_ACCEPTED, SwapTableMap::COL_DECLINED, SwapTableMap::COL_REQUESTEDBY, SwapTableMap::COL_VERIFICATIONCODE, SwapTableMap::COL_CREATED, SwapTableMap::COL_UPDATED, ],
        self::TYPE_FIELDNAME     => ['id', 'eventPersonId', 'oldUserRoleId', 'newUserRoleId', 'accepted', 'declined', 'requestedBy', 'verificationCode', 'created', 'updated', ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, ]
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
        self::TYPE_PHPNAME       => ['Id' => 0, 'EventPersonId' => 1, 'OldUserRoleId' => 2, 'NewUserRoleId' => 3, 'Accepted' => 4, 'Declined' => 5, 'RequestedBy' => 6, 'VerificationCode' => 7, 'Created' => 8, 'Updated' => 9, ],
        self::TYPE_CAMELNAME     => ['id' => 0, 'eventPersonId' => 1, 'oldUserRoleId' => 2, 'newUserRoleId' => 3, 'accepted' => 4, 'declined' => 5, 'requestedBy' => 6, 'verificationCode' => 7, 'created' => 8, 'updated' => 9, ],
        self::TYPE_COLNAME       => [SwapTableMap::COL_ID => 0, SwapTableMap::COL_EVENTPERSONID => 1, SwapTableMap::COL_OLDUSERROLEID => 2, SwapTableMap::COL_NEWUSERROLEID => 3, SwapTableMap::COL_ACCEPTED => 4, SwapTableMap::COL_DECLINED => 5, SwapTableMap::COL_REQUESTEDBY => 6, SwapTableMap::COL_VERIFICATIONCODE => 7, SwapTableMap::COL_CREATED => 8, SwapTableMap::COL_UPDATED => 9, ],
        self::TYPE_FIELDNAME     => ['id' => 0, 'eventPersonId' => 1, 'oldUserRoleId' => 2, 'newUserRoleId' => 3, 'accepted' => 4, 'declined' => 5, 'requestedBy' => 6, 'verificationCode' => 7, 'created' => 8, 'updated' => 9, ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, ]
    ];

    /**
     * Holds a list of column names and their normalized version.
     *
     * @var array<string>
     */
    protected $normalizedColumnNameMap = [
        'Id' => 'ID',
        'Swap.Id' => 'ID',
        'id' => 'ID',
        'swap.id' => 'ID',
        'SwapTableMap::COL_ID' => 'ID',
        'COL_ID' => 'ID',
        'swaps.id' => 'ID',
        'EventPersonId' => 'EVENTPERSONID',
        'Swap.EventPersonId' => 'EVENTPERSONID',
        'eventPersonId' => 'EVENTPERSONID',
        'swap.eventPersonId' => 'EVENTPERSONID',
        'SwapTableMap::COL_EVENTPERSONID' => 'EVENTPERSONID',
        'COL_EVENTPERSONID' => 'EVENTPERSONID',
        'swaps.eventPersonId' => 'EVENTPERSONID',
        'OldUserRoleId' => 'OLDUSERROLEID',
        'Swap.OldUserRoleId' => 'OLDUSERROLEID',
        'oldUserRoleId' => 'OLDUSERROLEID',
        'swap.oldUserRoleId' => 'OLDUSERROLEID',
        'SwapTableMap::COL_OLDUSERROLEID' => 'OLDUSERROLEID',
        'COL_OLDUSERROLEID' => 'OLDUSERROLEID',
        'swaps.oldUserRoleId' => 'OLDUSERROLEID',
        'NewUserRoleId' => 'NEWUSERROLEID',
        'Swap.NewUserRoleId' => 'NEWUSERROLEID',
        'newUserRoleId' => 'NEWUSERROLEID',
        'swap.newUserRoleId' => 'NEWUSERROLEID',
        'SwapTableMap::COL_NEWUSERROLEID' => 'NEWUSERROLEID',
        'COL_NEWUSERROLEID' => 'NEWUSERROLEID',
        'swaps.newUserRoleId' => 'NEWUSERROLEID',
        'Accepted' => 'ACCEPTED',
        'Swap.Accepted' => 'ACCEPTED',
        'accepted' => 'ACCEPTED',
        'swap.accepted' => 'ACCEPTED',
        'SwapTableMap::COL_ACCEPTED' => 'ACCEPTED',
        'COL_ACCEPTED' => 'ACCEPTED',
        'swaps.accepted' => 'ACCEPTED',
        'Declined' => 'DECLINED',
        'Swap.Declined' => 'DECLINED',
        'declined' => 'DECLINED',
        'swap.declined' => 'DECLINED',
        'SwapTableMap::COL_DECLINED' => 'DECLINED',
        'COL_DECLINED' => 'DECLINED',
        'swaps.declined' => 'DECLINED',
        'RequestedBy' => 'REQUESTEDBY',
        'Swap.RequestedBy' => 'REQUESTEDBY',
        'requestedBy' => 'REQUESTEDBY',
        'swap.requestedBy' => 'REQUESTEDBY',
        'SwapTableMap::COL_REQUESTEDBY' => 'REQUESTEDBY',
        'COL_REQUESTEDBY' => 'REQUESTEDBY',
        'swaps.requestedBy' => 'REQUESTEDBY',
        'VerificationCode' => 'VERIFICATIONCODE',
        'Swap.VerificationCode' => 'VERIFICATIONCODE',
        'verificationCode' => 'VERIFICATIONCODE',
        'swap.verificationCode' => 'VERIFICATIONCODE',
        'SwapTableMap::COL_VERIFICATIONCODE' => 'VERIFICATIONCODE',
        'COL_VERIFICATIONCODE' => 'VERIFICATIONCODE',
        'swaps.verificationCode' => 'VERIFICATIONCODE',
        'Created' => 'CREATED',
        'Swap.Created' => 'CREATED',
        'created' => 'CREATED',
        'swap.created' => 'CREATED',
        'SwapTableMap::COL_CREATED' => 'CREATED',
        'COL_CREATED' => 'CREATED',
        'swaps.created' => 'CREATED',
        'Updated' => 'UPDATED',
        'Swap.Updated' => 'UPDATED',
        'updated' => 'UPDATED',
        'swap.updated' => 'UPDATED',
        'SwapTableMap::COL_UPDATED' => 'UPDATED',
        'COL_UPDATED' => 'UPDATED',
        'swaps.updated' => 'UPDATED',
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
        $this->setName('swaps');
        $this->setPhpName('Swap');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\TechWilk\\Rota\\Swap');
        $this->setPackage('TechWilk.Rota');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('eventPersonId', 'EventPersonId', 'INTEGER', 'eventPeople', 'id', true, null, 0);
        $this->addForeignKey('oldUserRoleId', 'OldUserRoleId', 'INTEGER', 'userRoles', 'id', true, null, 0);
        $this->addForeignKey('newUserRoleId', 'NewUserRoleId', 'INTEGER', 'userRoles', 'id', true, null, 0);
        $this->addColumn('accepted', 'Accepted', 'INTEGER', true, 1, 0);
        $this->addColumn('declined', 'Declined', 'INTEGER', true, 1, 0);
        $this->addForeignKey('requestedBy', 'RequestedBy', 'INTEGER', 'users', 'id', true, null, null);
        $this->addColumn('verificationCode', 'VerificationCode', 'VARCHAR', true, 18, null);
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
        $this->addRelation('EventPerson', '\\TechWilk\\Rota\\EventPerson', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':eventPersonId',
    1 => ':id',
  ),
), null, null, null, false);
        $this->addRelation('OldUserRole', '\\TechWilk\\Rota\\UserRole', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':oldUserRoleId',
    1 => ':id',
  ),
), null, null, null, false);
        $this->addRelation('NewUserRole', '\\TechWilk\\Rota\\UserRole', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':newUserRoleId',
    1 => ':id',
  ),
), null, null, null, false);
        $this->addRelation('User', '\\TechWilk\\Rota\\User', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':requestedBy',
    1 => ':id',
  ),
), null, null, null, false);
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
        return $withPrefix ? SwapTableMap::CLASS_DEFAULT : SwapTableMap::OM_CLASS;
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
     * @return array (Swap object, last column rank)
     */
    public static function populateObject(array $row, int $offset = 0, string $indexType = TableMap::TYPE_NUM): array
    {
        $key = SwapTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = SwapTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + SwapTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = SwapTableMap::OM_CLASS;
            /** @var Swap $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            SwapTableMap::addInstanceToPool($obj, $key);
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
            $key = SwapTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = SwapTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Swap $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                SwapTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(SwapTableMap::COL_ID);
            $criteria->addSelectColumn(SwapTableMap::COL_EVENTPERSONID);
            $criteria->addSelectColumn(SwapTableMap::COL_OLDUSERROLEID);
            $criteria->addSelectColumn(SwapTableMap::COL_NEWUSERROLEID);
            $criteria->addSelectColumn(SwapTableMap::COL_ACCEPTED);
            $criteria->addSelectColumn(SwapTableMap::COL_DECLINED);
            $criteria->addSelectColumn(SwapTableMap::COL_REQUESTEDBY);
            $criteria->addSelectColumn(SwapTableMap::COL_VERIFICATIONCODE);
            $criteria->addSelectColumn(SwapTableMap::COL_CREATED);
            $criteria->addSelectColumn(SwapTableMap::COL_UPDATED);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.eventPersonId');
            $criteria->addSelectColumn($alias . '.oldUserRoleId');
            $criteria->addSelectColumn($alias . '.newUserRoleId');
            $criteria->addSelectColumn($alias . '.accepted');
            $criteria->addSelectColumn($alias . '.declined');
            $criteria->addSelectColumn($alias . '.requestedBy');
            $criteria->addSelectColumn($alias . '.verificationCode');
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
            $criteria->removeSelectColumn(SwapTableMap::COL_ID);
            $criteria->removeSelectColumn(SwapTableMap::COL_EVENTPERSONID);
            $criteria->removeSelectColumn(SwapTableMap::COL_OLDUSERROLEID);
            $criteria->removeSelectColumn(SwapTableMap::COL_NEWUSERROLEID);
            $criteria->removeSelectColumn(SwapTableMap::COL_ACCEPTED);
            $criteria->removeSelectColumn(SwapTableMap::COL_DECLINED);
            $criteria->removeSelectColumn(SwapTableMap::COL_REQUESTEDBY);
            $criteria->removeSelectColumn(SwapTableMap::COL_VERIFICATIONCODE);
            $criteria->removeSelectColumn(SwapTableMap::COL_CREATED);
            $criteria->removeSelectColumn(SwapTableMap::COL_UPDATED);
        } else {
            $criteria->removeSelectColumn($alias . '.id');
            $criteria->removeSelectColumn($alias . '.eventPersonId');
            $criteria->removeSelectColumn($alias . '.oldUserRoleId');
            $criteria->removeSelectColumn($alias . '.newUserRoleId');
            $criteria->removeSelectColumn($alias . '.accepted');
            $criteria->removeSelectColumn($alias . '.declined');
            $criteria->removeSelectColumn($alias . '.requestedBy');
            $criteria->removeSelectColumn($alias . '.verificationCode');
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
        return Propel::getServiceContainer()->getDatabaseMap(SwapTableMap::DATABASE_NAME)->getTable(SwapTableMap::TABLE_NAME);
    }

    /**
     * Performs a DELETE on the database, given a Swap or Criteria object OR a primary key value.
     *
     * @param mixed $values Criteria or Swap object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(SwapTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \TechWilk\Rota\Swap) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(SwapTableMap::DATABASE_NAME);
            $criteria->add(SwapTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = SwapQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            SwapTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                SwapTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the swaps table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(?ConnectionInterface $con = null): int
    {
        return SwapQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Swap or Criteria object.
     *
     * @param mixed $criteria Criteria or Swap object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed The new primary key.
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ?ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(SwapTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Swap object
        }

        if ($criteria->containsKey(SwapTableMap::COL_ID) && $criteria->keyContainsValue(SwapTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.SwapTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = SwapQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

}
