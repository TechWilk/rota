<?php

namespace TechWilk\Rota\Base;

use \Exception;
use \PDO;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;
use TechWilk\Rota\Discussion as ChildDiscussion;
use TechWilk\Rota\DiscussionQuery as ChildDiscussionQuery;
use TechWilk\Rota\Map\DiscussionTableMap;

/**
 * Base class that represents a query for the 'cr_discussion' table.
 *
 *
 *
 * @method     ChildDiscussionQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildDiscussionQuery orderByTopicparent($order = Criteria::ASC) Order by the topicParent column
 * @method     ChildDiscussionQuery orderByCategoryparent($order = Criteria::ASC) Order by the CategoryParent column
 * @method     ChildDiscussionQuery orderByUserid($order = Criteria::ASC) Order by the userID column
 * @method     ChildDiscussionQuery orderByTopic($order = Criteria::ASC) Order by the topic column
 * @method     ChildDiscussionQuery orderByTopicname($order = Criteria::ASC) Order by the topicName column
 * @method     ChildDiscussionQuery orderByDate($order = Criteria::ASC) Order by the date column
 *
 * @method     ChildDiscussionQuery groupById() Group by the id column
 * @method     ChildDiscussionQuery groupByTopicparent() Group by the topicParent column
 * @method     ChildDiscussionQuery groupByCategoryparent() Group by the CategoryParent column
 * @method     ChildDiscussionQuery groupByUserid() Group by the userID column
 * @method     ChildDiscussionQuery groupByTopic() Group by the topic column
 * @method     ChildDiscussionQuery groupByTopicname() Group by the topicName column
 * @method     ChildDiscussionQuery groupByDate() Group by the date column
 *
 * @method     ChildDiscussionQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildDiscussionQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildDiscussionQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildDiscussionQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildDiscussionQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildDiscussionQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildDiscussion findOne(ConnectionInterface $con = null) Return the first ChildDiscussion matching the query
 * @method     ChildDiscussion findOneOrCreate(ConnectionInterface $con = null) Return the first ChildDiscussion matching the query, or a new ChildDiscussion object populated from the query conditions when no match is found
 *
 * @method     ChildDiscussion findOneById(int $id) Return the first ChildDiscussion filtered by the id column
 * @method     ChildDiscussion findOneByTopicparent(int $topicParent) Return the first ChildDiscussion filtered by the topicParent column
 * @method     ChildDiscussion findOneByCategoryparent(int $CategoryParent) Return the first ChildDiscussion filtered by the CategoryParent column
 * @method     ChildDiscussion findOneByUserid(int $userID) Return the first ChildDiscussion filtered by the userID column
 * @method     ChildDiscussion findOneByTopic(string $topic) Return the first ChildDiscussion filtered by the topic column
 * @method     ChildDiscussion findOneByTopicname(string $topicName) Return the first ChildDiscussion filtered by the topicName column
 * @method     ChildDiscussion findOneByDate(string $date) Return the first ChildDiscussion filtered by the date column *

 * @method     ChildDiscussion requirePk($key, ConnectionInterface $con = null) Return the ChildDiscussion by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildDiscussion requireOne(ConnectionInterface $con = null) Return the first ChildDiscussion matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildDiscussion requireOneById(int $id) Return the first ChildDiscussion filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildDiscussion requireOneByTopicparent(int $topicParent) Return the first ChildDiscussion filtered by the topicParent column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildDiscussion requireOneByCategoryparent(int $CategoryParent) Return the first ChildDiscussion filtered by the CategoryParent column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildDiscussion requireOneByUserid(int $userID) Return the first ChildDiscussion filtered by the userID column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildDiscussion requireOneByTopic(string $topic) Return the first ChildDiscussion filtered by the topic column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildDiscussion requireOneByTopicname(string $topicName) Return the first ChildDiscussion filtered by the topicName column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildDiscussion requireOneByDate(string $date) Return the first ChildDiscussion filtered by the date column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildDiscussion[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildDiscussion objects based on current ModelCriteria
 * @method     ChildDiscussion[]|ObjectCollection findById(int $id) Return ChildDiscussion objects filtered by the id column
 * @method     ChildDiscussion[]|ObjectCollection findByTopicparent(int $topicParent) Return ChildDiscussion objects filtered by the topicParent column
 * @method     ChildDiscussion[]|ObjectCollection findByCategoryparent(int $CategoryParent) Return ChildDiscussion objects filtered by the CategoryParent column
 * @method     ChildDiscussion[]|ObjectCollection findByUserid(int $userID) Return ChildDiscussion objects filtered by the userID column
 * @method     ChildDiscussion[]|ObjectCollection findByTopic(string $topic) Return ChildDiscussion objects filtered by the topic column
 * @method     ChildDiscussion[]|ObjectCollection findByTopicname(string $topicName) Return ChildDiscussion objects filtered by the topicName column
 * @method     ChildDiscussion[]|ObjectCollection findByDate(string $date) Return ChildDiscussion objects filtered by the date column
 * @method     ChildDiscussion[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class DiscussionQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \TechWilk\Rota\Base\DiscussionQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\TechWilk\\Rota\\Discussion', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildDiscussionQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildDiscussionQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildDiscussionQuery) {
            return $criteria;
        }
        $query = new ChildDiscussionQuery();
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
     * @return ChildDiscussion|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(DiscussionTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = DiscussionTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildDiscussion A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, topicParent, CategoryParent, userID, topic, topicName, date FROM cr_discussion WHERE id = :p0';
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
            /** @var ChildDiscussion $obj */
            $obj = new ChildDiscussion();
            $obj->hydrate($row);
            DiscussionTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildDiscussion|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildDiscussionQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        return $this->addUsingAlias(DiscussionTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildDiscussionQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        return $this->addUsingAlias(DiscussionTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildDiscussionQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(DiscussionTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(DiscussionTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DiscussionTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the topicParent column
     *
     * Example usage:
     * <code>
     * $query->filterByTopicparent(1234); // WHERE topicParent = 1234
     * $query->filterByTopicparent(array(12, 34)); // WHERE topicParent IN (12, 34)
     * $query->filterByTopicparent(array('min' => 12)); // WHERE topicParent > 12
     * </code>
     *
     * @param     mixed $topicparent The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildDiscussionQuery The current query, for fluid interface
     */
    public function filterByTopicparent($topicparent = null, $comparison = null)
    {
        if (is_array($topicparent)) {
            $useMinMax = false;
            if (isset($topicparent['min'])) {
                $this->addUsingAlias(DiscussionTableMap::COL_TOPICPARENT, $topicparent['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($topicparent['max'])) {
                $this->addUsingAlias(DiscussionTableMap::COL_TOPICPARENT, $topicparent['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DiscussionTableMap::COL_TOPICPARENT, $topicparent, $comparison);
    }

    /**
     * Filter the query on the CategoryParent column
     *
     * Example usage:
     * <code>
     * $query->filterByCategoryparent(1234); // WHERE CategoryParent = 1234
     * $query->filterByCategoryparent(array(12, 34)); // WHERE CategoryParent IN (12, 34)
     * $query->filterByCategoryparent(array('min' => 12)); // WHERE CategoryParent > 12
     * </code>
     *
     * @param     mixed $categoryparent The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildDiscussionQuery The current query, for fluid interface
     */
    public function filterByCategoryparent($categoryparent = null, $comparison = null)
    {
        if (is_array($categoryparent)) {
            $useMinMax = false;
            if (isset($categoryparent['min'])) {
                $this->addUsingAlias(DiscussionTableMap::COL_CATEGORYPARENT, $categoryparent['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($categoryparent['max'])) {
                $this->addUsingAlias(DiscussionTableMap::COL_CATEGORYPARENT, $categoryparent['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DiscussionTableMap::COL_CATEGORYPARENT, $categoryparent, $comparison);
    }

    /**
     * Filter the query on the userID column
     *
     * Example usage:
     * <code>
     * $query->filterByUserid(1234); // WHERE userID = 1234
     * $query->filterByUserid(array(12, 34)); // WHERE userID IN (12, 34)
     * $query->filterByUserid(array('min' => 12)); // WHERE userID > 12
     * </code>
     *
     * @param     mixed $userid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildDiscussionQuery The current query, for fluid interface
     */
    public function filterByUserid($userid = null, $comparison = null)
    {
        if (is_array($userid)) {
            $useMinMax = false;
            if (isset($userid['min'])) {
                $this->addUsingAlias(DiscussionTableMap::COL_USERID, $userid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($userid['max'])) {
                $this->addUsingAlias(DiscussionTableMap::COL_USERID, $userid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DiscussionTableMap::COL_USERID, $userid, $comparison);
    }

    /**
     * Filter the query on the topic column
     *
     * Example usage:
     * <code>
     * $query->filterByTopic('fooValue');   // WHERE topic = 'fooValue'
     * $query->filterByTopic('%fooValue%', Criteria::LIKE); // WHERE topic LIKE '%fooValue%'
     * </code>
     *
     * @param     string $topic The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildDiscussionQuery The current query, for fluid interface
     */
    public function filterByTopic($topic = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($topic)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DiscussionTableMap::COL_TOPIC, $topic, $comparison);
    }

    /**
     * Filter the query on the topicName column
     *
     * Example usage:
     * <code>
     * $query->filterByTopicname('fooValue');   // WHERE topicName = 'fooValue'
     * $query->filterByTopicname('%fooValue%', Criteria::LIKE); // WHERE topicName LIKE '%fooValue%'
     * </code>
     *
     * @param     string $topicname The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildDiscussionQuery The current query, for fluid interface
     */
    public function filterByTopicname($topicname = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($topicname)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DiscussionTableMap::COL_TOPICNAME, $topicname, $comparison);
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
     * @return $this|ChildDiscussionQuery The current query, for fluid interface
     */
    public function filterByDate($date = null, $comparison = null)
    {
        if (is_array($date)) {
            $useMinMax = false;
            if (isset($date['min'])) {
                $this->addUsingAlias(DiscussionTableMap::COL_DATE, $date['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($date['max'])) {
                $this->addUsingAlias(DiscussionTableMap::COL_DATE, $date['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DiscussionTableMap::COL_DATE, $date, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   ChildDiscussion $discussion Object to remove from the list of results
     *
     * @return $this|ChildDiscussionQuery The current query, for fluid interface
     */
    public function prune($discussion = null)
    {
        if ($discussion) {
            $this->addUsingAlias(DiscussionTableMap::COL_ID, $discussion->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the cr_discussion table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(DiscussionTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            DiscussionTableMap::clearInstancePool();
            DiscussionTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(DiscussionTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(DiscussionTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            DiscussionTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            DiscussionTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }
} // DiscussionQuery
