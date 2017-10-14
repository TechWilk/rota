<?php

namespace TechWilk\Rota\Base;

use \Exception;
use \PDO;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;
use TechWilk\Rota\PermissionGroupPermission as ChildPermissionGroupPermission;
use TechWilk\Rota\PermissionGroupPermissionQuery as ChildPermissionGroupPermissionQuery;
use TechWilk\Rota\Map\PermissionGroupPermissionTableMap;

/**
 * Base class that represents a query for the 'permissionGroupPermissions' table.
 *
 *
 *
 * @method     ChildPermissionGroupPermissionQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildPermissionGroupPermissionQuery orderByUserId($order = Criteria::ASC) Order by the permissionId column
 * @method     ChildPermissionGroupPermissionQuery orderByPermissionId($order = Criteria::ASC) Order by the permissionGroupId column
 *
 * @method     ChildPermissionGroupPermissionQuery groupById() Group by the id column
 * @method     ChildPermissionGroupPermissionQuery groupByUserId() Group by the permissionId column
 * @method     ChildPermissionGroupPermissionQuery groupByPermissionId() Group by the permissionGroupId column
 *
 * @method     ChildPermissionGroupPermissionQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildPermissionGroupPermissionQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildPermissionGroupPermissionQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildPermissionGroupPermissionQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildPermissionGroupPermissionQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildPermissionGroupPermissionQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildPermissionGroupPermissionQuery leftJoinPermission($relationAlias = null) Adds a LEFT JOIN clause to the query using the Permission relation
 * @method     ChildPermissionGroupPermissionQuery rightJoinPermission($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Permission relation
 * @method     ChildPermissionGroupPermissionQuery innerJoinPermission($relationAlias = null) Adds a INNER JOIN clause to the query using the Permission relation
 *
 * @method     ChildPermissionGroupPermissionQuery joinWithPermission($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Permission relation
 *
 * @method     ChildPermissionGroupPermissionQuery leftJoinWithPermission() Adds a LEFT JOIN clause and with to the query using the Permission relation
 * @method     ChildPermissionGroupPermissionQuery rightJoinWithPermission() Adds a RIGHT JOIN clause and with to the query using the Permission relation
 * @method     ChildPermissionGroupPermissionQuery innerJoinWithPermission() Adds a INNER JOIN clause and with to the query using the Permission relation
 *
 * @method     ChildPermissionGroupPermissionQuery leftJoinPermissionGroup($relationAlias = null) Adds a LEFT JOIN clause to the query using the PermissionGroup relation
 * @method     ChildPermissionGroupPermissionQuery rightJoinPermissionGroup($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PermissionGroup relation
 * @method     ChildPermissionGroupPermissionQuery innerJoinPermissionGroup($relationAlias = null) Adds a INNER JOIN clause to the query using the PermissionGroup relation
 *
 * @method     ChildPermissionGroupPermissionQuery joinWithPermissionGroup($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the PermissionGroup relation
 *
 * @method     ChildPermissionGroupPermissionQuery leftJoinWithPermissionGroup() Adds a LEFT JOIN clause and with to the query using the PermissionGroup relation
 * @method     ChildPermissionGroupPermissionQuery rightJoinWithPermissionGroup() Adds a RIGHT JOIN clause and with to the query using the PermissionGroup relation
 * @method     ChildPermissionGroupPermissionQuery innerJoinWithPermissionGroup() Adds a INNER JOIN clause and with to the query using the PermissionGroup relation
 *
 * @method     \TechWilk\Rota\PermissionQuery|\TechWilk\Rota\PermissionGroupQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildPermissionGroupPermission findOne(ConnectionInterface $con = null) Return the first ChildPermissionGroupPermission matching the query
 * @method     ChildPermissionGroupPermission findOneOrCreate(ConnectionInterface $con = null) Return the first ChildPermissionGroupPermission matching the query, or a new ChildPermissionGroupPermission object populated from the query conditions when no match is found
 *
 * @method     ChildPermissionGroupPermission findOneById(int $id) Return the first ChildPermissionGroupPermission filtered by the id column
 * @method     ChildPermissionGroupPermission findOneByUserId(int $permissionId) Return the first ChildPermissionGroupPermission filtered by the permissionId column
 * @method     ChildPermissionGroupPermission findOneByPermissionId(int $permissionGroupId) Return the first ChildPermissionGroupPermission filtered by the permissionGroupId column *

 * @method     ChildPermissionGroupPermission requirePk($key, ConnectionInterface $con = null) Return the ChildPermissionGroupPermission by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPermissionGroupPermission requireOne(ConnectionInterface $con = null) Return the first ChildPermissionGroupPermission matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPermissionGroupPermission requireOneById(int $id) Return the first ChildPermissionGroupPermission filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPermissionGroupPermission requireOneByUserId(int $permissionId) Return the first ChildPermissionGroupPermission filtered by the permissionId column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPermissionGroupPermission requireOneByPermissionId(int $permissionGroupId) Return the first ChildPermissionGroupPermission filtered by the permissionGroupId column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPermissionGroupPermission[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildPermissionGroupPermission objects based on current ModelCriteria
 * @method     ChildPermissionGroupPermission[]|ObjectCollection findById(int $id) Return ChildPermissionGroupPermission objects filtered by the id column
 * @method     ChildPermissionGroupPermission[]|ObjectCollection findByUserId(int $permissionId) Return ChildPermissionGroupPermission objects filtered by the permissionId column
 * @method     ChildPermissionGroupPermission[]|ObjectCollection findByPermissionId(int $permissionGroupId) Return ChildPermissionGroupPermission objects filtered by the permissionGroupId column
 * @method     ChildPermissionGroupPermission[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class PermissionGroupPermissionQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \TechWilk\Rota\Base\PermissionGroupPermissionQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\TechWilk\\Rota\\PermissionGroupPermission', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildPermissionGroupPermissionQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildPermissionGroupPermissionQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildPermissionGroupPermissionQuery) {
            return $criteria;
        }
        $query = new ChildPermissionGroupPermissionQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildPermissionGroupPermission|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(PermissionGroupPermissionTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = PermissionGroupPermissionTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
            // the object is already in the instance pool
            return $obj;
        }

        return $this->findPkSimple($key, $con);
    }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildPermissionGroupPermission A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, permissionId, permissionGroupId FROM permissionGroupPermissions WHERE id = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildPermissionGroupPermission $obj */
            $obj = new ChildPermissionGroupPermission();
            $obj->hydrate($row);
            PermissionGroupPermissionTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return ChildPermissionGroupPermission|array|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, ConnectionInterface $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($dataFetcher);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return ObjectCollection|array|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection($this->getDbName());
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($dataFetcher);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return $this|ChildPermissionGroupPermissionQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        return $this->addUsingAlias(PermissionGroupPermissionTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildPermissionGroupPermissionQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        return $this->addUsingAlias(PermissionGroupPermissionTableMap::COL_ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPermissionGroupPermissionQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(PermissionGroupPermissionTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(PermissionGroupPermissionTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PermissionGroupPermissionTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the permissionId column
     *
     * Example usage:
     * <code>
     * $query->filterByUserId(1234); // WHERE permissionId = 1234
     * $query->filterByUserId(array(12, 34)); // WHERE permissionId IN (12, 34)
     * $query->filterByUserId(array('min' => 12)); // WHERE permissionId > 12
     * </code>
     *
     * @see       filterByPermission()
     *
     * @param     mixed $userId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPermissionGroupPermissionQuery The current query, for fluid interface
     */
    public function filterByUserId($userId = null, $comparison = null)
    {
        if (is_array($userId)) {
            $useMinMax = false;
            if (isset($userId['min'])) {
                $this->addUsingAlias(PermissionGroupPermissionTableMap::COL_PERMISSIONID, $userId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($userId['max'])) {
                $this->addUsingAlias(PermissionGroupPermissionTableMap::COL_PERMISSIONID, $userId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PermissionGroupPermissionTableMap::COL_PERMISSIONID, $userId, $comparison);
    }

    /**
     * Filter the query on the permissionGroupId column
     *
     * Example usage:
     * <code>
     * $query->filterByPermissionId(1234); // WHERE permissionGroupId = 1234
     * $query->filterByPermissionId(array(12, 34)); // WHERE permissionGroupId IN (12, 34)
     * $query->filterByPermissionId(array('min' => 12)); // WHERE permissionGroupId > 12
     * </code>
     *
     * @see       filterByPermissionGroup()
     *
     * @param     mixed $permissionId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPermissionGroupPermissionQuery The current query, for fluid interface
     */
    public function filterByPermissionId($permissionId = null, $comparison = null)
    {
        if (is_array($permissionId)) {
            $useMinMax = false;
            if (isset($permissionId['min'])) {
                $this->addUsingAlias(PermissionGroupPermissionTableMap::COL_PERMISSIONGROUPID, $permissionId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($permissionId['max'])) {
                $this->addUsingAlias(PermissionGroupPermissionTableMap::COL_PERMISSIONGROUPID, $permissionId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PermissionGroupPermissionTableMap::COL_PERMISSIONGROUPID, $permissionId, $comparison);
    }

    /**
     * Filter the query by a related \TechWilk\Rota\Permission object
     *
     * @param \TechWilk\Rota\Permission|ObjectCollection $permission The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildPermissionGroupPermissionQuery The current query, for fluid interface
     */
    public function filterByPermission($permission, $comparison = null)
    {
        if ($permission instanceof \TechWilk\Rota\Permission) {
            return $this
                ->addUsingAlias(PermissionGroupPermissionTableMap::COL_PERMISSIONID, $permission->getId(), $comparison);
        } elseif ($permission instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PermissionGroupPermissionTableMap::COL_PERMISSIONID, $permission->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPermission() only accepts arguments of type \TechWilk\Rota\Permission or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Permission relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPermissionGroupPermissionQuery The current query, for fluid interface
     */
    public function joinPermission($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Permission');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Permission');
        }

        return $this;
    }

    /**
     * Use the Permission relation Permission object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \TechWilk\Rota\PermissionQuery A secondary query class using the current class as primary query
     */
    public function usePermissionQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPermission($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Permission', '\TechWilk\Rota\PermissionQuery');
    }

    /**
     * Filter the query by a related \TechWilk\Rota\PermissionGroup object
     *
     * @param \TechWilk\Rota\PermissionGroup|ObjectCollection $permissionGroup The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildPermissionGroupPermissionQuery The current query, for fluid interface
     */
    public function filterByPermissionGroup($permissionGroup, $comparison = null)
    {
        if ($permissionGroup instanceof \TechWilk\Rota\PermissionGroup) {
            return $this
                ->addUsingAlias(PermissionGroupPermissionTableMap::COL_PERMISSIONGROUPID, $permissionGroup->getId(), $comparison);
        } elseif ($permissionGroup instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PermissionGroupPermissionTableMap::COL_PERMISSIONGROUPID, $permissionGroup->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPermissionGroup() only accepts arguments of type \TechWilk\Rota\PermissionGroup or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PermissionGroup relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPermissionGroupPermissionQuery The current query, for fluid interface
     */
    public function joinPermissionGroup($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PermissionGroup');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'PermissionGroup');
        }

        return $this;
    }

    /**
     * Use the PermissionGroup relation PermissionGroup object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \TechWilk\Rota\PermissionGroupQuery A secondary query class using the current class as primary query
     */
    public function usePermissionGroupQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPermissionGroup($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PermissionGroup', '\TechWilk\Rota\PermissionGroupQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildPermissionGroupPermission $permissionGroupPermission Object to remove from the list of results
     *
     * @return $this|ChildPermissionGroupPermissionQuery The current query, for fluid interface
     */
    public function prune($permissionGroupPermission = null)
    {
        if ($permissionGroupPermission) {
            $this->addUsingAlias(PermissionGroupPermissionTableMap::COL_ID, $permissionGroupPermission->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the permissionGroupPermissions table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PermissionGroupPermissionTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            PermissionGroupPermissionTableMap::clearInstancePool();
            PermissionGroupPermissionTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    /**
     * Performs a DELETE on the database based on the current ModelCriteria
     *
     * @param ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public function delete(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PermissionGroupPermissionTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(PermissionGroupPermissionTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            PermissionGroupPermissionTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            PermissionGroupPermissionTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }
} // PermissionGroupPermissionQuery
