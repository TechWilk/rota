<?php

namespace TechWilk\Rota\Base;

use \Exception;
use \PDO;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;
use TechWilk\Rota\Notification as ChildNotification;
use TechWilk\Rota\NotificationQuery as ChildNotificationQuery;
use TechWilk\Rota\Map\NotificationTableMap;

/**
 * Base class that represents a query for the `notifications` table.
 *
 * @method     ChildNotificationQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildNotificationQuery orderByTimestamp($order = Criteria::ASC) Order by the timestamp column
 * @method     ChildNotificationQuery orderByUserId($order = Criteria::ASC) Order by the userId column
 * @method     ChildNotificationQuery orderBySummary($order = Criteria::ASC) Order by the summary column
 * @method     ChildNotificationQuery orderByBody($order = Criteria::ASC) Order by the body column
 * @method     ChildNotificationQuery orderByLink($order = Criteria::ASC) Order by the link column
 * @method     ChildNotificationQuery orderByType($order = Criteria::ASC) Order by the type column
 * @method     ChildNotificationQuery orderBySeen($order = Criteria::ASC) Order by the seen column
 * @method     ChildNotificationQuery orderByDismissed($order = Criteria::ASC) Order by the dismissed column
 * @method     ChildNotificationQuery orderByArchived($order = Criteria::ASC) Order by the archived column
 *
 * @method     ChildNotificationQuery groupById() Group by the id column
 * @method     ChildNotificationQuery groupByTimestamp() Group by the timestamp column
 * @method     ChildNotificationQuery groupByUserId() Group by the userId column
 * @method     ChildNotificationQuery groupBySummary() Group by the summary column
 * @method     ChildNotificationQuery groupByBody() Group by the body column
 * @method     ChildNotificationQuery groupByLink() Group by the link column
 * @method     ChildNotificationQuery groupByType() Group by the type column
 * @method     ChildNotificationQuery groupBySeen() Group by the seen column
 * @method     ChildNotificationQuery groupByDismissed() Group by the dismissed column
 * @method     ChildNotificationQuery groupByArchived() Group by the archived column
 *
 * @method     ChildNotificationQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildNotificationQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildNotificationQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildNotificationQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildNotificationQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildNotificationQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildNotificationQuery leftJoinUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the User relation
 * @method     ChildNotificationQuery rightJoinUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the User relation
 * @method     ChildNotificationQuery innerJoinUser($relationAlias = null) Adds a INNER JOIN clause to the query using the User relation
 *
 * @method     ChildNotificationQuery joinWithUser($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the User relation
 *
 * @method     ChildNotificationQuery leftJoinWithUser() Adds a LEFT JOIN clause and with to the query using the User relation
 * @method     ChildNotificationQuery rightJoinWithUser() Adds a RIGHT JOIN clause and with to the query using the User relation
 * @method     ChildNotificationQuery innerJoinWithUser() Adds a INNER JOIN clause and with to the query using the User relation
 *
 * @method     ChildNotificationQuery leftJoinNotificationClick($relationAlias = null) Adds a LEFT JOIN clause to the query using the NotificationClick relation
 * @method     ChildNotificationQuery rightJoinNotificationClick($relationAlias = null) Adds a RIGHT JOIN clause to the query using the NotificationClick relation
 * @method     ChildNotificationQuery innerJoinNotificationClick($relationAlias = null) Adds a INNER JOIN clause to the query using the NotificationClick relation
 *
 * @method     ChildNotificationQuery joinWithNotificationClick($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the NotificationClick relation
 *
 * @method     ChildNotificationQuery leftJoinWithNotificationClick() Adds a LEFT JOIN clause and with to the query using the NotificationClick relation
 * @method     ChildNotificationQuery rightJoinWithNotificationClick() Adds a RIGHT JOIN clause and with to the query using the NotificationClick relation
 * @method     ChildNotificationQuery innerJoinWithNotificationClick() Adds a INNER JOIN clause and with to the query using the NotificationClick relation
 *
 * @method     \TechWilk\Rota\UserQuery|\TechWilk\Rota\NotificationClickQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildNotification|null findOne(?ConnectionInterface $con = null) Return the first ChildNotification matching the query
 * @method     ChildNotification findOneOrCreate(?ConnectionInterface $con = null) Return the first ChildNotification matching the query, or a new ChildNotification object populated from the query conditions when no match is found
 *
 * @method     ChildNotification|null findOneById(int $id) Return the first ChildNotification filtered by the id column
 * @method     ChildNotification|null findOneByTimestamp(string $timestamp) Return the first ChildNotification filtered by the timestamp column
 * @method     ChildNotification|null findOneByUserId(int $userId) Return the first ChildNotification filtered by the userId column
 * @method     ChildNotification|null findOneBySummary(string $summary) Return the first ChildNotification filtered by the summary column
 * @method     ChildNotification|null findOneByBody(string $body) Return the first ChildNotification filtered by the body column
 * @method     ChildNotification|null findOneByLink(string $link) Return the first ChildNotification filtered by the link column
 * @method     ChildNotification|null findOneByType(int $type) Return the first ChildNotification filtered by the type column
 * @method     ChildNotification|null findOneBySeen(boolean $seen) Return the first ChildNotification filtered by the seen column
 * @method     ChildNotification|null findOneByDismissed(boolean $dismissed) Return the first ChildNotification filtered by the dismissed column
 * @method     ChildNotification|null findOneByArchived(boolean $archived) Return the first ChildNotification filtered by the archived column
 *
 * @method     ChildNotification requirePk($key, ?ConnectionInterface $con = null) Return the ChildNotification by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildNotification requireOne(?ConnectionInterface $con = null) Return the first ChildNotification matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildNotification requireOneById(int $id) Return the first ChildNotification filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildNotification requireOneByTimestamp(string $timestamp) Return the first ChildNotification filtered by the timestamp column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildNotification requireOneByUserId(int $userId) Return the first ChildNotification filtered by the userId column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildNotification requireOneBySummary(string $summary) Return the first ChildNotification filtered by the summary column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildNotification requireOneByBody(string $body) Return the first ChildNotification filtered by the body column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildNotification requireOneByLink(string $link) Return the first ChildNotification filtered by the link column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildNotification requireOneByType(int $type) Return the first ChildNotification filtered by the type column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildNotification requireOneBySeen(boolean $seen) Return the first ChildNotification filtered by the seen column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildNotification requireOneByDismissed(boolean $dismissed) Return the first ChildNotification filtered by the dismissed column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildNotification requireOneByArchived(boolean $archived) Return the first ChildNotification filtered by the archived column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildNotification[]|Collection find(?ConnectionInterface $con = null) Return ChildNotification objects based on current ModelCriteria
 * @psalm-method Collection&\Traversable<ChildNotification> find(?ConnectionInterface $con = null) Return ChildNotification objects based on current ModelCriteria
 *
 * @method     ChildNotification[]|Collection findById(int|array<int> $id) Return ChildNotification objects filtered by the id column
 * @psalm-method Collection&\Traversable<ChildNotification> findById(int|array<int> $id) Return ChildNotification objects filtered by the id column
 * @method     ChildNotification[]|Collection findByTimestamp(string|array<string> $timestamp) Return ChildNotification objects filtered by the timestamp column
 * @psalm-method Collection&\Traversable<ChildNotification> findByTimestamp(string|array<string> $timestamp) Return ChildNotification objects filtered by the timestamp column
 * @method     ChildNotification[]|Collection findByUserId(int|array<int> $userId) Return ChildNotification objects filtered by the userId column
 * @psalm-method Collection&\Traversable<ChildNotification> findByUserId(int|array<int> $userId) Return ChildNotification objects filtered by the userId column
 * @method     ChildNotification[]|Collection findBySummary(string|array<string> $summary) Return ChildNotification objects filtered by the summary column
 * @psalm-method Collection&\Traversable<ChildNotification> findBySummary(string|array<string> $summary) Return ChildNotification objects filtered by the summary column
 * @method     ChildNotification[]|Collection findByBody(string|array<string> $body) Return ChildNotification objects filtered by the body column
 * @psalm-method Collection&\Traversable<ChildNotification> findByBody(string|array<string> $body) Return ChildNotification objects filtered by the body column
 * @method     ChildNotification[]|Collection findByLink(string|array<string> $link) Return ChildNotification objects filtered by the link column
 * @psalm-method Collection&\Traversable<ChildNotification> findByLink(string|array<string> $link) Return ChildNotification objects filtered by the link column
 * @method     ChildNotification[]|Collection findByType(int|array<int> $type) Return ChildNotification objects filtered by the type column
 * @psalm-method Collection&\Traversable<ChildNotification> findByType(int|array<int> $type) Return ChildNotification objects filtered by the type column
 * @method     ChildNotification[]|Collection findBySeen(boolean|array<boolean> $seen) Return ChildNotification objects filtered by the seen column
 * @psalm-method Collection&\Traversable<ChildNotification> findBySeen(boolean|array<boolean> $seen) Return ChildNotification objects filtered by the seen column
 * @method     ChildNotification[]|Collection findByDismissed(boolean|array<boolean> $dismissed) Return ChildNotification objects filtered by the dismissed column
 * @psalm-method Collection&\Traversable<ChildNotification> findByDismissed(boolean|array<boolean> $dismissed) Return ChildNotification objects filtered by the dismissed column
 * @method     ChildNotification[]|Collection findByArchived(boolean|array<boolean> $archived) Return ChildNotification objects filtered by the archived column
 * @psalm-method Collection&\Traversable<ChildNotification> findByArchived(boolean|array<boolean> $archived) Return ChildNotification objects filtered by the archived column
 *
 * @method     ChildNotification[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 * @psalm-method \Propel\Runtime\Util\PropelModelPager&\Traversable<ChildNotification> paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 */
abstract class NotificationQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \TechWilk\Rota\Base\NotificationQuery object.
     *
     * @param string $dbName The database name
     * @param string $modelName The phpName of a model, e.g. 'Book'
     * @param string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\TechWilk\\Rota\\Notification', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildNotificationQuery object.
     *
     * @param string $modelAlias The alias of a model in the query
     * @param Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildNotificationQuery
     */
    public static function create(?string $modelAlias = null, ?Criteria $criteria = null): Criteria
    {
        if ($criteria instanceof ChildNotificationQuery) {
            return $criteria;
        }
        $query = new ChildNotificationQuery();
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
     * @return ChildNotification|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ?ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(NotificationTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = NotificationTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
            // the object is already in the instance pool
            return $obj;
        }

        return $this->findPkSimple($key, $con);
    }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con A connection object
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildNotification A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, timestamp, userId, summary, body, link, type, seen, dismissed, archived FROM notifications WHERE id = :p0';
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
            /** @var ChildNotification $obj */
            $obj = new ChildNotification();
            $obj->hydrate($row);
            NotificationTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con A connection object
     *
     * @return ChildNotification|array|mixed the result, formatted by the current formatter
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
     * @param array $keys Primary keys to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return Collection|array|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, ?ConnectionInterface $con = null)
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
     * @param mixed $key Primary key to use for the query
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        $this->addUsingAlias(NotificationTableMap::COL_ID, $key, Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param array|int $keys The list of primary key to use for the query
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        $this->addUsingAlias(NotificationTableMap::COL_ID, $keys, Criteria::IN);

        return $this;
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
     * @param mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterById($id = null, ?string $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(NotificationTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(NotificationTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(NotificationTableMap::COL_ID, $id, $comparison);

        return $this;
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
     * @param mixed $timestamp The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByTimestamp($timestamp = null, ?string $comparison = null)
    {
        if (is_array($timestamp)) {
            $useMinMax = false;
            if (isset($timestamp['min'])) {
                $this->addUsingAlias(NotificationTableMap::COL_TIMESTAMP, $timestamp['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($timestamp['max'])) {
                $this->addUsingAlias(NotificationTableMap::COL_TIMESTAMP, $timestamp['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(NotificationTableMap::COL_TIMESTAMP, $timestamp, $comparison);

        return $this;
    }

    /**
     * Filter the query on the userId column
     *
     * Example usage:
     * <code>
     * $query->filterByUserId(1234); // WHERE userId = 1234
     * $query->filterByUserId(array(12, 34)); // WHERE userId IN (12, 34)
     * $query->filterByUserId(array('min' => 12)); // WHERE userId > 12
     * </code>
     *
     * @see       filterByUser()
     *
     * @param mixed $userId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByUserId($userId = null, ?string $comparison = null)
    {
        if (is_array($userId)) {
            $useMinMax = false;
            if (isset($userId['min'])) {
                $this->addUsingAlias(NotificationTableMap::COL_USERID, $userId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($userId['max'])) {
                $this->addUsingAlias(NotificationTableMap::COL_USERID, $userId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(NotificationTableMap::COL_USERID, $userId, $comparison);

        return $this;
    }

    /**
     * Filter the query on the summary column
     *
     * Example usage:
     * <code>
     * $query->filterBySummary('fooValue');   // WHERE summary = 'fooValue'
     * $query->filterBySummary('%fooValue%', Criteria::LIKE); // WHERE summary LIKE '%fooValue%'
     * $query->filterBySummary(['foo', 'bar']); // WHERE summary IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $summary The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterBySummary($summary = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($summary)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(NotificationTableMap::COL_SUMMARY, $summary, $comparison);

        return $this;
    }

    /**
     * Filter the query on the body column
     *
     * Example usage:
     * <code>
     * $query->filterByBody('fooValue');   // WHERE body = 'fooValue'
     * $query->filterByBody('%fooValue%', Criteria::LIKE); // WHERE body LIKE '%fooValue%'
     * $query->filterByBody(['foo', 'bar']); // WHERE body IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $body The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByBody($body = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($body)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(NotificationTableMap::COL_BODY, $body, $comparison);

        return $this;
    }

    /**
     * Filter the query on the link column
     *
     * Example usage:
     * <code>
     * $query->filterByLink('fooValue');   // WHERE link = 'fooValue'
     * $query->filterByLink('%fooValue%', Criteria::LIKE); // WHERE link LIKE '%fooValue%'
     * $query->filterByLink(['foo', 'bar']); // WHERE link IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $link The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByLink($link = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($link)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(NotificationTableMap::COL_LINK, $link, $comparison);

        return $this;
    }

    /**
     * Filter the query on the type column
     *
     * Example usage:
     * <code>
     * $query->filterByType(1234); // WHERE type = 1234
     * $query->filterByType(array(12, 34)); // WHERE type IN (12, 34)
     * $query->filterByType(array('min' => 12)); // WHERE type > 12
     * </code>
     *
     * @param mixed $type The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByType($type = null, ?string $comparison = null)
    {
        if (is_array($type)) {
            $useMinMax = false;
            if (isset($type['min'])) {
                $this->addUsingAlias(NotificationTableMap::COL_TYPE, $type['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($type['max'])) {
                $this->addUsingAlias(NotificationTableMap::COL_TYPE, $type['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(NotificationTableMap::COL_TYPE, $type, $comparison);

        return $this;
    }

    /**
     * Filter the query on the seen column
     *
     * Example usage:
     * <code>
     * $query->filterBySeen(true); // WHERE seen = true
     * $query->filterBySeen('yes'); // WHERE seen = true
     * </code>
     *
     * @param bool|string $seen The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterBySeen($seen = null, ?string $comparison = null)
    {
        if (is_string($seen)) {
            $seen = in_array(strtolower($seen), array('false', 'off', '-', 'no', 'n', '0', ''), true) ? false : true;
        }

        $this->addUsingAlias(NotificationTableMap::COL_SEEN, $seen, $comparison);

        return $this;
    }

    /**
     * Filter the query on the dismissed column
     *
     * Example usage:
     * <code>
     * $query->filterByDismissed(true); // WHERE dismissed = true
     * $query->filterByDismissed('yes'); // WHERE dismissed = true
     * </code>
     *
     * @param bool|string $dismissed The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByDismissed($dismissed = null, ?string $comparison = null)
    {
        if (is_string($dismissed)) {
            $dismissed = in_array(strtolower($dismissed), array('false', 'off', '-', 'no', 'n', '0', ''), true) ? false : true;
        }

        $this->addUsingAlias(NotificationTableMap::COL_DISMISSED, $dismissed, $comparison);

        return $this;
    }

    /**
     * Filter the query on the archived column
     *
     * Example usage:
     * <code>
     * $query->filterByArchived(true); // WHERE archived = true
     * $query->filterByArchived('yes'); // WHERE archived = true
     * </code>
     *
     * @param bool|string $archived The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByArchived($archived = null, ?string $comparison = null)
    {
        if (is_string($archived)) {
            $archived = in_array(strtolower($archived), array('false', 'off', '-', 'no', 'n', '0', ''), true) ? false : true;
        }

        $this->addUsingAlias(NotificationTableMap::COL_ARCHIVED, $archived, $comparison);

        return $this;
    }

    /**
     * Filter the query by a related \TechWilk\Rota\User object
     *
     * @param \TechWilk\Rota\User|ObjectCollection $user The related object(s) to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByUser($user, ?string $comparison = null)
    {
        if ($user instanceof \TechWilk\Rota\User) {
            return $this
                ->addUsingAlias(NotificationTableMap::COL_USERID, $user->getId(), $comparison);
        } elseif ($user instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            $this
                ->addUsingAlias(NotificationTableMap::COL_USERID, $user->toKeyValue('PrimaryKey', 'Id'), $comparison);

            return $this;
        } else {
            throw new PropelException('filterByUser() only accepts arguments of type \TechWilk\Rota\User or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the User relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinUser(?string $relationAlias = null, ?string $joinType = Criteria::INNER_JOIN)
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
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \TechWilk\Rota\UserQuery A secondary query class using the current class as primary query
     */
    public function useUserQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinUser($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'User', '\TechWilk\Rota\UserQuery');
    }

    /**
     * Use the User relation User object
     *
     * @param callable(\TechWilk\Rota\UserQuery):\TechWilk\Rota\UserQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withUserQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::INNER_JOIN
    ) {
        $relatedQuery = $this->useUserQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to User table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \TechWilk\Rota\UserQuery The inner query object of the EXISTS statement
     */
    public function useUserExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \TechWilk\Rota\UserQuery */
        $q = $this->useExistsQuery('User', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to User table for a NOT EXISTS query.
     *
     * @see useUserExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \TechWilk\Rota\UserQuery The inner query object of the NOT EXISTS statement
     */
    public function useUserNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \TechWilk\Rota\UserQuery */
        $q = $this->useExistsQuery('User', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to User table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \TechWilk\Rota\UserQuery The inner query object of the IN statement
     */
    public function useInUserQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \TechWilk\Rota\UserQuery */
        $q = $this->useInQuery('User', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to User table for a NOT IN query.
     *
     * @see useUserInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \TechWilk\Rota\UserQuery The inner query object of the NOT IN statement
     */
    public function useNotInUserQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \TechWilk\Rota\UserQuery */
        $q = $this->useInQuery('User', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Filter the query by a related \TechWilk\Rota\NotificationClick object
     *
     * @param \TechWilk\Rota\NotificationClick|ObjectCollection $notificationClick the related object to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByNotificationClick($notificationClick, ?string $comparison = null)
    {
        if ($notificationClick instanceof \TechWilk\Rota\NotificationClick) {
            $this
                ->addUsingAlias(NotificationTableMap::COL_ID, $notificationClick->getNotificationId(), $comparison);

            return $this;
        } elseif ($notificationClick instanceof ObjectCollection) {
            $this
                ->useNotificationClickQuery()
                ->filterByPrimaryKeys($notificationClick->getPrimaryKeys())
                ->endUse();

            return $this;
        } else {
            throw new PropelException('filterByNotificationClick() only accepts arguments of type \TechWilk\Rota\NotificationClick or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the NotificationClick relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinNotificationClick(?string $relationAlias = null, ?string $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('NotificationClick');

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
            $this->addJoinObject($join, 'NotificationClick');
        }

        return $this;
    }

    /**
     * Use the NotificationClick relation NotificationClick object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \TechWilk\Rota\NotificationClickQuery A secondary query class using the current class as primary query
     */
    public function useNotificationClickQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinNotificationClick($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'NotificationClick', '\TechWilk\Rota\NotificationClickQuery');
    }

    /**
     * Use the NotificationClick relation NotificationClick object
     *
     * @param callable(\TechWilk\Rota\NotificationClickQuery):\TechWilk\Rota\NotificationClickQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withNotificationClickQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::INNER_JOIN
    ) {
        $relatedQuery = $this->useNotificationClickQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to NotificationClick table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \TechWilk\Rota\NotificationClickQuery The inner query object of the EXISTS statement
     */
    public function useNotificationClickExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \TechWilk\Rota\NotificationClickQuery */
        $q = $this->useExistsQuery('NotificationClick', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to NotificationClick table for a NOT EXISTS query.
     *
     * @see useNotificationClickExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \TechWilk\Rota\NotificationClickQuery The inner query object of the NOT EXISTS statement
     */
    public function useNotificationClickNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \TechWilk\Rota\NotificationClickQuery */
        $q = $this->useExistsQuery('NotificationClick', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to NotificationClick table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \TechWilk\Rota\NotificationClickQuery The inner query object of the IN statement
     */
    public function useInNotificationClickQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \TechWilk\Rota\NotificationClickQuery */
        $q = $this->useInQuery('NotificationClick', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to NotificationClick table for a NOT IN query.
     *
     * @see useNotificationClickInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \TechWilk\Rota\NotificationClickQuery The inner query object of the NOT IN statement
     */
    public function useNotInNotificationClickQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \TechWilk\Rota\NotificationClickQuery */
        $q = $this->useInQuery('NotificationClick', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Exclude object from result
     *
     * @param ChildNotification $notification Object to remove from the list of results
     *
     * @return $this The current query, for fluid interface
     */
    public function prune($notification = null)
    {
        if ($notification) {
            $this->addUsingAlias(NotificationTableMap::COL_ID, $notification->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the notifications table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(?ConnectionInterface $con = null): int
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(NotificationTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            NotificationTableMap::clearInstancePool();
            NotificationTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    /**
     * Performs a DELETE on the database based on the current ModelCriteria
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public function delete(?ConnectionInterface $con = null): int
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(NotificationTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(NotificationTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            NotificationTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            NotificationTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

}
