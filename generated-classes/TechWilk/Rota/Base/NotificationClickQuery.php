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
use TechWilk\Rota\NotificationClick as ChildNotificationClick;
use TechWilk\Rota\NotificationClickQuery as ChildNotificationClickQuery;
use TechWilk\Rota\Map\NotificationClickTableMap;

/**
 * Base class that represents a query for the 'notificationClicks' table.
 *
 *
 *
 * @method     ChildNotificationClickQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildNotificationClickQuery orderByNotificationId($order = Criteria::ASC) Order by the notificationId column
 * @method     ChildNotificationClickQuery orderByReferer($order = Criteria::ASC) Order by the referer column
 * @method     ChildNotificationClickQuery orderByTimestamp($order = Criteria::ASC) Order by the timestamp column
 *
 * @method     ChildNotificationClickQuery groupById() Group by the id column
 * @method     ChildNotificationClickQuery groupByNotificationId() Group by the notificationId column
 * @method     ChildNotificationClickQuery groupByReferer() Group by the referer column
 * @method     ChildNotificationClickQuery groupByTimestamp() Group by the timestamp column
 *
 * @method     ChildNotificationClickQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildNotificationClickQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildNotificationClickQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildNotificationClickQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildNotificationClickQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildNotificationClickQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildNotificationClickQuery leftJoinNotification($relationAlias = null) Adds a LEFT JOIN clause to the query using the Notification relation
 * @method     ChildNotificationClickQuery rightJoinNotification($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Notification relation
 * @method     ChildNotificationClickQuery innerJoinNotification($relationAlias = null) Adds a INNER JOIN clause to the query using the Notification relation
 *
 * @method     ChildNotificationClickQuery joinWithNotification($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Notification relation
 *
 * @method     ChildNotificationClickQuery leftJoinWithNotification() Adds a LEFT JOIN clause and with to the query using the Notification relation
 * @method     ChildNotificationClickQuery rightJoinWithNotification() Adds a RIGHT JOIN clause and with to the query using the Notification relation
 * @method     ChildNotificationClickQuery innerJoinWithNotification() Adds a INNER JOIN clause and with to the query using the Notification relation
 *
 * @method     \TechWilk\Rota\NotificationQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildNotificationClick findOne(ConnectionInterface $con = null) Return the first ChildNotificationClick matching the query
 * @method     ChildNotificationClick findOneOrCreate(ConnectionInterface $con = null) Return the first ChildNotificationClick matching the query, or a new ChildNotificationClick object populated from the query conditions when no match is found
 *
 * @method     ChildNotificationClick findOneById(int $id) Return the first ChildNotificationClick filtered by the id column
 * @method     ChildNotificationClick findOneByNotificationId(int $notificationId) Return the first ChildNotificationClick filtered by the notificationId column
 * @method     ChildNotificationClick findOneByReferer(string $referer) Return the first ChildNotificationClick filtered by the referer column
 * @method     ChildNotificationClick findOneByTimestamp(string $timestamp) Return the first ChildNotificationClick filtered by the timestamp column *

 * @method     ChildNotificationClick requirePk($key, ConnectionInterface $con = null) Return the ChildNotificationClick by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildNotificationClick requireOne(ConnectionInterface $con = null) Return the first ChildNotificationClick matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildNotificationClick requireOneById(int $id) Return the first ChildNotificationClick filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildNotificationClick requireOneByNotificationId(int $notificationId) Return the first ChildNotificationClick filtered by the notificationId column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildNotificationClick requireOneByReferer(string $referer) Return the first ChildNotificationClick filtered by the referer column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildNotificationClick requireOneByTimestamp(string $timestamp) Return the first ChildNotificationClick filtered by the timestamp column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildNotificationClick[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildNotificationClick objects based on current ModelCriteria
 * @method     ChildNotificationClick[]|ObjectCollection findById(int $id) Return ChildNotificationClick objects filtered by the id column
 * @method     ChildNotificationClick[]|ObjectCollection findByNotificationId(int $notificationId) Return ChildNotificationClick objects filtered by the notificationId column
 * @method     ChildNotificationClick[]|ObjectCollection findByReferer(string $referer) Return ChildNotificationClick objects filtered by the referer column
 * @method     ChildNotificationClick[]|ObjectCollection findByTimestamp(string $timestamp) Return ChildNotificationClick objects filtered by the timestamp column
 * @method     ChildNotificationClick[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class NotificationClickQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \TechWilk\Rota\Base\NotificationClickQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\TechWilk\\Rota\\NotificationClick', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildNotificationClickQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildNotificationClickQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildNotificationClickQuery) {
            return $criteria;
        }
        $query = new ChildNotificationClickQuery();
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
     * @return ChildNotificationClick|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(NotificationClickTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = NotificationClickTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildNotificationClick A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, notificationId, referer, timestamp FROM notificationClicks WHERE id = :p0';
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
            /** @var ChildNotificationClick $obj */
            $obj = new ChildNotificationClick();
            $obj->hydrate($row);
            NotificationClickTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildNotificationClick|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildNotificationClickQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        return $this->addUsingAlias(NotificationClickTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildNotificationClickQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        return $this->addUsingAlias(NotificationClickTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildNotificationClickQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(NotificationClickTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(NotificationClickTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(NotificationClickTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the notificationId column
     *
     * Example usage:
     * <code>
     * $query->filterByNotificationId(1234); // WHERE notificationId = 1234
     * $query->filterByNotificationId(array(12, 34)); // WHERE notificationId IN (12, 34)
     * $query->filterByNotificationId(array('min' => 12)); // WHERE notificationId > 12
     * </code>
     *
     * @see       filterByNotification()
     *
     * @param     mixed $notificationId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildNotificationClickQuery The current query, for fluid interface
     */
    public function filterByNotificationId($notificationId = null, $comparison = null)
    {
        if (is_array($notificationId)) {
            $useMinMax = false;
            if (isset($notificationId['min'])) {
                $this->addUsingAlias(NotificationClickTableMap::COL_NOTIFICATIONID, $notificationId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($notificationId['max'])) {
                $this->addUsingAlias(NotificationClickTableMap::COL_NOTIFICATIONID, $notificationId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(NotificationClickTableMap::COL_NOTIFICATIONID, $notificationId, $comparison);
    }

    /**
     * Filter the query on the referer column
     *
     * Example usage:
     * <code>
     * $query->filterByReferer('fooValue');   // WHERE referer = 'fooValue'
     * $query->filterByReferer('%fooValue%', Criteria::LIKE); // WHERE referer LIKE '%fooValue%'
     * </code>
     *
     * @param     string $referer The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildNotificationClickQuery The current query, for fluid interface
     */
    public function filterByReferer($referer = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($referer)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(NotificationClickTableMap::COL_REFERER, $referer, $comparison);
    }

    /**
     * Filter the query on the timestamp column
     *
     * Example usage:
     * <code>
     * $query->filterByTimestamp('2011-03-14'); // WHERE timestamp = '2011-03-14'
     * $query->filterByTimestamp('now'); // WHERE timestamp = '2011-03-14'
     * $query->filterByTimestamp(array('max' => 'yesterday')); // WHERE timestamp > '2011-03-13'
     * </code>
     *
     * @param     mixed $timestamp The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildNotificationClickQuery The current query, for fluid interface
     */
    public function filterByTimestamp($timestamp = null, $comparison = null)
    {
        if (is_array($timestamp)) {
            $useMinMax = false;
            if (isset($timestamp['min'])) {
                $this->addUsingAlias(NotificationClickTableMap::COL_TIMESTAMP, $timestamp['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($timestamp['max'])) {
                $this->addUsingAlias(NotificationClickTableMap::COL_TIMESTAMP, $timestamp['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(NotificationClickTableMap::COL_TIMESTAMP, $timestamp, $comparison);
    }

    /**
     * Filter the query by a related \TechWilk\Rota\Notification object
     *
     * @param \TechWilk\Rota\Notification|ObjectCollection $notification The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildNotificationClickQuery The current query, for fluid interface
     */
    public function filterByNotification($notification, $comparison = null)
    {
        if ($notification instanceof \TechWilk\Rota\Notification) {
            return $this
                ->addUsingAlias(NotificationClickTableMap::COL_NOTIFICATIONID, $notification->getId(), $comparison);
        } elseif ($notification instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(NotificationClickTableMap::COL_NOTIFICATIONID, $notification->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByNotification() only accepts arguments of type \TechWilk\Rota\Notification or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Notification relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildNotificationClickQuery The current query, for fluid interface
     */
    public function joinNotification($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Notification');

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
            $this->addJoinObject($join, 'Notification');
        }

        return $this;
    }

    /**
     * Use the Notification relation Notification object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \TechWilk\Rota\NotificationQuery A secondary query class using the current class as primary query
     */
    public function useNotificationQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinNotification($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Notification', '\TechWilk\Rota\NotificationQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildNotificationClick $notificationClick Object to remove from the list of results
     *
     * @return $this|ChildNotificationClickQuery The current query, for fluid interface
     */
    public function prune($notificationClick = null)
    {
        if ($notificationClick) {
            $this->addUsingAlias(NotificationClickTableMap::COL_ID, $notificationClick->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the notificationClicks table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(NotificationClickTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            NotificationClickTableMap::clearInstancePool();
            NotificationClickTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(NotificationClickTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(NotificationClickTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            NotificationClickTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            NotificationClickTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    // timestampable behavior

    /**
     * Order by create date desc
     *
     * @return     $this|ChildNotificationClickQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(NotificationClickTableMap::COL_TIMESTAMP);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     $this|ChildNotificationClickQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(NotificationClickTableMap::COL_TIMESTAMP, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date asc
     *
     * @return     $this|ChildNotificationClickQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(NotificationClickTableMap::COL_TIMESTAMP);
    }
} // NotificationClickQuery
