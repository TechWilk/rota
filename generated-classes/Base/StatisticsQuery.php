<?php

namespace Base;

use \Statistics as ChildStatistics;
use \StatisticsQuery as ChildStatisticsQuery;
use \Exception;
use \PDO;
use Map\StatisticsTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'cr_statistics' table.
 *
 *
 *
 * @method     ChildStatisticsQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildStatisticsQuery orderByUserId($order = Criteria::ASC) Order by the userid column
 * @method     ChildStatisticsQuery orderByDate($order = Criteria::ASC) Order by the date column
 * @method     ChildStatisticsQuery orderByType($order = Criteria::ASC) Order by the type column
 * @method     ChildStatisticsQuery orderByDetail1($order = Criteria::ASC) Order by the detail1 column
 * @method     ChildStatisticsQuery orderByDetail2($order = Criteria::ASC) Order by the detail2 column
 * @method     ChildStatisticsQuery orderByDetail3($order = Criteria::ASC) Order by the detail3 column
 * @method     ChildStatisticsQuery orderByScript($order = Criteria::ASC) Order by the script column
 *
 * @method     ChildStatisticsQuery groupById() Group by the id column
 * @method     ChildStatisticsQuery groupByUserId() Group by the userid column
 * @method     ChildStatisticsQuery groupByDate() Group by the date column
 * @method     ChildStatisticsQuery groupByType() Group by the type column
 * @method     ChildStatisticsQuery groupByDetail1() Group by the detail1 column
 * @method     ChildStatisticsQuery groupByDetail2() Group by the detail2 column
 * @method     ChildStatisticsQuery groupByDetail3() Group by the detail3 column
 * @method     ChildStatisticsQuery groupByScript() Group by the script column
 *
 * @method     ChildStatisticsQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildStatisticsQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildStatisticsQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildStatisticsQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildStatisticsQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildStatisticsQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildStatisticsQuery leftJoinUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the User relation
 * @method     ChildStatisticsQuery rightJoinUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the User relation
 * @method     ChildStatisticsQuery innerJoinUser($relationAlias = null) Adds a INNER JOIN clause to the query using the User relation
 *
 * @method     ChildStatisticsQuery joinWithUser($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the User relation
 *
 * @method     ChildStatisticsQuery leftJoinWithUser() Adds a LEFT JOIN clause and with to the query using the User relation
 * @method     ChildStatisticsQuery rightJoinWithUser() Adds a RIGHT JOIN clause and with to the query using the User relation
 * @method     ChildStatisticsQuery innerJoinWithUser() Adds a INNER JOIN clause and with to the query using the User relation
 *
 * @method     \UserQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildStatistics findOne(ConnectionInterface $con = null) Return the first ChildStatistics matching the query
 * @method     ChildStatistics findOneOrCreate(ConnectionInterface $con = null) Return the first ChildStatistics matching the query, or a new ChildStatistics object populated from the query conditions when no match is found
 *
 * @method     ChildStatistics findOneById(int $id) Return the first ChildStatistics filtered by the id column
 * @method     ChildStatistics findOneByUserId(int $userid) Return the first ChildStatistics filtered by the userid column
 * @method     ChildStatistics findOneByDate(string $date) Return the first ChildStatistics filtered by the date column
 * @method     ChildStatistics findOneByType(string $type) Return the first ChildStatistics filtered by the type column
 * @method     ChildStatistics findOneByDetail1(string $detail1) Return the first ChildStatistics filtered by the detail1 column
 * @method     ChildStatistics findOneByDetail2(string $detail2) Return the first ChildStatistics filtered by the detail2 column
 * @method     ChildStatistics findOneByDetail3(string $detail3) Return the first ChildStatistics filtered by the detail3 column
 * @method     ChildStatistics findOneByScript(string $script) Return the first ChildStatistics filtered by the script column *

 * @method     ChildStatistics requirePk($key, ConnectionInterface $con = null) Return the ChildStatistics by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildStatistics requireOne(ConnectionInterface $con = null) Return the first ChildStatistics matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildStatistics requireOneById(int $id) Return the first ChildStatistics filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildStatistics requireOneByUserId(int $userid) Return the first ChildStatistics filtered by the userid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildStatistics requireOneByDate(string $date) Return the first ChildStatistics filtered by the date column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildStatistics requireOneByType(string $type) Return the first ChildStatistics filtered by the type column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildStatistics requireOneByDetail1(string $detail1) Return the first ChildStatistics filtered by the detail1 column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildStatistics requireOneByDetail2(string $detail2) Return the first ChildStatistics filtered by the detail2 column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildStatistics requireOneByDetail3(string $detail3) Return the first ChildStatistics filtered by the detail3 column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildStatistics requireOneByScript(string $script) Return the first ChildStatistics filtered by the script column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildStatistics[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildStatistics objects based on current ModelCriteria
 * @method     ChildStatistics[]|ObjectCollection findById(int $id) Return ChildStatistics objects filtered by the id column
 * @method     ChildStatistics[]|ObjectCollection findByUserId(int $userid) Return ChildStatistics objects filtered by the userid column
 * @method     ChildStatistics[]|ObjectCollection findByDate(string $date) Return ChildStatistics objects filtered by the date column
 * @method     ChildStatistics[]|ObjectCollection findByType(string $type) Return ChildStatistics objects filtered by the type column
 * @method     ChildStatistics[]|ObjectCollection findByDetail1(string $detail1) Return ChildStatistics objects filtered by the detail1 column
 * @method     ChildStatistics[]|ObjectCollection findByDetail2(string $detail2) Return ChildStatistics objects filtered by the detail2 column
 * @method     ChildStatistics[]|ObjectCollection findByDetail3(string $detail3) Return ChildStatistics objects filtered by the detail3 column
 * @method     ChildStatistics[]|ObjectCollection findByScript(string $script) Return ChildStatistics objects filtered by the script column
 * @method     ChildStatistics[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class StatisticsQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Base\StatisticsQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Statistics', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildStatisticsQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildStatisticsQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildStatisticsQuery) {
            return $criteria;
        }
        $query = new ChildStatisticsQuery();
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
     * @return ChildStatistics|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(StatisticsTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = StatisticsTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildStatistics A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, userid, date, type, detail1, detail2, detail3, script FROM cr_statistics WHERE id = :p0';
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
            /** @var ChildStatistics $obj */
            $obj = new ChildStatistics();
            $obj->hydrate($row);
            StatisticsTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildStatistics|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildStatisticsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        return $this->addUsingAlias(StatisticsTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildStatisticsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        return $this->addUsingAlias(StatisticsTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildStatisticsQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(StatisticsTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(StatisticsTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StatisticsTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the userid column
     *
     * Example usage:
     * <code>
     * $query->filterByUserId(1234); // WHERE userid = 1234
     * $query->filterByUserId(array(12, 34)); // WHERE userid IN (12, 34)
     * $query->filterByUserId(array('min' => 12)); // WHERE userid > 12
     * </code>
     *
     * @see       filterByUser()
     *
     * @param     mixed $userId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildStatisticsQuery The current query, for fluid interface
     */
    public function filterByUserId($userId = null, $comparison = null)
    {
        if (is_array($userId)) {
            $useMinMax = false;
            if (isset($userId['min'])) {
                $this->addUsingAlias(StatisticsTableMap::COL_USERID, $userId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($userId['max'])) {
                $this->addUsingAlias(StatisticsTableMap::COL_USERID, $userId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StatisticsTableMap::COL_USERID, $userId, $comparison);
    }

    /**
     * Filter the query on the date column
     *
     * Example usage:
     * <code>
     * $query->filterByDate('2011-03-14'); // WHERE date = '2011-03-14'
     * $query->filterByDate('now'); // WHERE date = '2011-03-14'
     * $query->filterByDate(array('max' => 'yesterday')); // WHERE date > '2011-03-13'
     * </code>
     *
     * @param     mixed $date The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildStatisticsQuery The current query, for fluid interface
     */
    public function filterByDate($date = null, $comparison = null)
    {
        if (is_array($date)) {
            $useMinMax = false;
            if (isset($date['min'])) {
                $this->addUsingAlias(StatisticsTableMap::COL_DATE, $date['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($date['max'])) {
                $this->addUsingAlias(StatisticsTableMap::COL_DATE, $date['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StatisticsTableMap::COL_DATE, $date, $comparison);
    }

    /**
     * Filter the query on the type column
     *
     * Example usage:
     * <code>
     * $query->filterByType('fooValue');   // WHERE type = 'fooValue'
     * $query->filterByType('%fooValue%', Criteria::LIKE); // WHERE type LIKE '%fooValue%'
     * </code>
     *
     * @param     string $type The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildStatisticsQuery The current query, for fluid interface
     */
    public function filterByType($type = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($type)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StatisticsTableMap::COL_TYPE, $type, $comparison);
    }

    /**
     * Filter the query on the detail1 column
     *
     * Example usage:
     * <code>
     * $query->filterByDetail1('fooValue');   // WHERE detail1 = 'fooValue'
     * $query->filterByDetail1('%fooValue%', Criteria::LIKE); // WHERE detail1 LIKE '%fooValue%'
     * </code>
     *
     * @param     string $detail1 The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildStatisticsQuery The current query, for fluid interface
     */
    public function filterByDetail1($detail1 = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($detail1)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StatisticsTableMap::COL_DETAIL1, $detail1, $comparison);
    }

    /**
     * Filter the query on the detail2 column
     *
     * Example usage:
     * <code>
     * $query->filterByDetail2('fooValue');   // WHERE detail2 = 'fooValue'
     * $query->filterByDetail2('%fooValue%', Criteria::LIKE); // WHERE detail2 LIKE '%fooValue%'
     * </code>
     *
     * @param     string $detail2 The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildStatisticsQuery The current query, for fluid interface
     */
    public function filterByDetail2($detail2 = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($detail2)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StatisticsTableMap::COL_DETAIL2, $detail2, $comparison);
    }

    /**
     * Filter the query on the detail3 column
     *
     * Example usage:
     * <code>
     * $query->filterByDetail3('fooValue');   // WHERE detail3 = 'fooValue'
     * $query->filterByDetail3('%fooValue%', Criteria::LIKE); // WHERE detail3 LIKE '%fooValue%'
     * </code>
     *
     * @param     string $detail3 The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildStatisticsQuery The current query, for fluid interface
     */
    public function filterByDetail3($detail3 = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($detail3)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StatisticsTableMap::COL_DETAIL3, $detail3, $comparison);
    }

    /**
     * Filter the query on the script column
     *
     * Example usage:
     * <code>
     * $query->filterByScript('fooValue');   // WHERE script = 'fooValue'
     * $query->filterByScript('%fooValue%', Criteria::LIKE); // WHERE script LIKE '%fooValue%'
     * </code>
     *
     * @param     string $script The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildStatisticsQuery The current query, for fluid interface
     */
    public function filterByScript($script = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($script)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StatisticsTableMap::COL_SCRIPT, $script, $comparison);
    }

    /**
     * Filter the query by a related \User object
     *
     * @param \User|ObjectCollection $user The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildStatisticsQuery The current query, for fluid interface
     */
    public function filterByUser($user, $comparison = null)
    {
        if ($user instanceof \User) {
            return $this
                ->addUsingAlias(StatisticsTableMap::COL_USERID, $user->getId(), $comparison);
        } elseif ($user instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(StatisticsTableMap::COL_USERID, $user->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByUser() only accepts arguments of type \User or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the User relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildStatisticsQuery The current query, for fluid interface
     */
    public function joinUser($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('User');

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
            $this->addJoinObject($join, 'User');
        }

        return $this;
    }

    /**
     * Use the User relation User object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \UserQuery A secondary query class using the current class as primary query
     */
    public function useUserQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinUser($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'User', '\UserQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildStatistics $statistics Object to remove from the list of results
     *
     * @return $this|ChildStatisticsQuery The current query, for fluid interface
     */
    public function prune($statistics = null)
    {
        if ($statistics) {
            $this->addUsingAlias(StatisticsTableMap::COL_ID, $statistics->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the cr_statistics table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(StatisticsTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            StatisticsTableMap::clearInstancePool();
            StatisticsTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(StatisticsTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(StatisticsTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            StatisticsTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            StatisticsTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }
} // StatisticsQuery
