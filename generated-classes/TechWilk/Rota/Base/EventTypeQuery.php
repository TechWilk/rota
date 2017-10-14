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
use TechWilk\Rota\EventType as ChildEventType;
use TechWilk\Rota\EventTypeQuery as ChildEventTypeQuery;
use TechWilk\Rota\Map\EventTypeTableMap;

/**
 * Base class that represents a query for the 'eventTypes' table.
 *
 *
 *
 * @method     ChildEventTypeQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildEventTypeQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method     ChildEventTypeQuery orderByDescription($order = Criteria::ASC) Order by the description column
 * @method     ChildEventTypeQuery orderByDefaultDay($order = Criteria::ASC) Order by the defaultDay column
 * @method     ChildEventTypeQuery orderByDefaultTime($order = Criteria::ASC) Order by the defaultTime column
 * @method     ChildEventTypeQuery orderByDefaultRepitition($order = Criteria::ASC) Order by the defaultRepitition column
 * @method     ChildEventTypeQuery orderByDefaultLocationId($order = Criteria::ASC) Order by the defaultLocationId column
 * @method     ChildEventTypeQuery orderByRehearsal($order = Criteria::ASC) Order by the rehearsal column
 * @method     ChildEventTypeQuery orderByGroupFormat($order = Criteria::ASC) Order by the groupformat column
 *
 * @method     ChildEventTypeQuery groupById() Group by the id column
 * @method     ChildEventTypeQuery groupByName() Group by the name column
 * @method     ChildEventTypeQuery groupByDescription() Group by the description column
 * @method     ChildEventTypeQuery groupByDefaultDay() Group by the defaultDay column
 * @method     ChildEventTypeQuery groupByDefaultTime() Group by the defaultTime column
 * @method     ChildEventTypeQuery groupByDefaultRepitition() Group by the defaultRepitition column
 * @method     ChildEventTypeQuery groupByDefaultLocationId() Group by the defaultLocationId column
 * @method     ChildEventTypeQuery groupByRehearsal() Group by the rehearsal column
 * @method     ChildEventTypeQuery groupByGroupFormat() Group by the groupformat column
 *
 * @method     ChildEventTypeQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildEventTypeQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildEventTypeQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildEventTypeQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildEventTypeQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildEventTypeQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildEventTypeQuery leftJoinLocation($relationAlias = null) Adds a LEFT JOIN clause to the query using the Location relation
 * @method     ChildEventTypeQuery rightJoinLocation($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Location relation
 * @method     ChildEventTypeQuery innerJoinLocation($relationAlias = null) Adds a INNER JOIN clause to the query using the Location relation
 *
 * @method     ChildEventTypeQuery joinWithLocation($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Location relation
 *
 * @method     ChildEventTypeQuery leftJoinWithLocation() Adds a LEFT JOIN clause and with to the query using the Location relation
 * @method     ChildEventTypeQuery rightJoinWithLocation() Adds a RIGHT JOIN clause and with to the query using the Location relation
 * @method     ChildEventTypeQuery innerJoinWithLocation() Adds a INNER JOIN clause and with to the query using the Location relation
 *
 * @method     ChildEventTypeQuery leftJoinEvent($relationAlias = null) Adds a LEFT JOIN clause to the query using the Event relation
 * @method     ChildEventTypeQuery rightJoinEvent($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Event relation
 * @method     ChildEventTypeQuery innerJoinEvent($relationAlias = null) Adds a INNER JOIN clause to the query using the Event relation
 *
 * @method     ChildEventTypeQuery joinWithEvent($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Event relation
 *
 * @method     ChildEventTypeQuery leftJoinWithEvent() Adds a LEFT JOIN clause and with to the query using the Event relation
 * @method     ChildEventTypeQuery rightJoinWithEvent() Adds a RIGHT JOIN clause and with to the query using the Event relation
 * @method     ChildEventTypeQuery innerJoinWithEvent() Adds a INNER JOIN clause and with to the query using the Event relation
 *
 * @method     \TechWilk\Rota\LocationQuery|\TechWilk\Rota\EventQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildEventType findOne(ConnectionInterface $con = null) Return the first ChildEventType matching the query
 * @method     ChildEventType findOneOrCreate(ConnectionInterface $con = null) Return the first ChildEventType matching the query, or a new ChildEventType object populated from the query conditions when no match is found
 *
 * @method     ChildEventType findOneById(int $id) Return the first ChildEventType filtered by the id column
 * @method     ChildEventType findOneByName(string $name) Return the first ChildEventType filtered by the name column
 * @method     ChildEventType findOneByDescription(string $description) Return the first ChildEventType filtered by the description column
 * @method     ChildEventType findOneByDefaultDay(int $defaultDay) Return the first ChildEventType filtered by the defaultDay column
 * @method     ChildEventType findOneByDefaultTime(string $defaultTime) Return the first ChildEventType filtered by the defaultTime column
 * @method     ChildEventType findOneByDefaultRepitition(int $defaultRepitition) Return the first ChildEventType filtered by the defaultRepitition column
 * @method     ChildEventType findOneByDefaultLocationId(int $defaultLocationId) Return the first ChildEventType filtered by the defaultLocationId column
 * @method     ChildEventType findOneByRehearsal(int $rehearsal) Return the first ChildEventType filtered by the rehearsal column
 * @method     ChildEventType findOneByGroupFormat(int $groupformat) Return the first ChildEventType filtered by the groupformat column *

 * @method     ChildEventType requirePk($key, ConnectionInterface $con = null) Return the ChildEventType by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEventType requireOne(ConnectionInterface $con = null) Return the first ChildEventType matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildEventType requireOneById(int $id) Return the first ChildEventType filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEventType requireOneByName(string $name) Return the first ChildEventType filtered by the name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEventType requireOneByDescription(string $description) Return the first ChildEventType filtered by the description column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEventType requireOneByDefaultDay(int $defaultDay) Return the first ChildEventType filtered by the defaultDay column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEventType requireOneByDefaultTime(string $defaultTime) Return the first ChildEventType filtered by the defaultTime column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEventType requireOneByDefaultRepitition(int $defaultRepitition) Return the first ChildEventType filtered by the defaultRepitition column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEventType requireOneByDefaultLocationId(int $defaultLocationId) Return the first ChildEventType filtered by the defaultLocationId column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEventType requireOneByRehearsal(int $rehearsal) Return the first ChildEventType filtered by the rehearsal column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEventType requireOneByGroupFormat(int $groupformat) Return the first ChildEventType filtered by the groupformat column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildEventType[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildEventType objects based on current ModelCriteria
 * @method     ChildEventType[]|ObjectCollection findById(int $id) Return ChildEventType objects filtered by the id column
 * @method     ChildEventType[]|ObjectCollection findByName(string $name) Return ChildEventType objects filtered by the name column
 * @method     ChildEventType[]|ObjectCollection findByDescription(string $description) Return ChildEventType objects filtered by the description column
 * @method     ChildEventType[]|ObjectCollection findByDefaultDay(int $defaultDay) Return ChildEventType objects filtered by the defaultDay column
 * @method     ChildEventType[]|ObjectCollection findByDefaultTime(string $defaultTime) Return ChildEventType objects filtered by the defaultTime column
 * @method     ChildEventType[]|ObjectCollection findByDefaultRepitition(int $defaultRepitition) Return ChildEventType objects filtered by the defaultRepitition column
 * @method     ChildEventType[]|ObjectCollection findByDefaultLocationId(int $defaultLocationId) Return ChildEventType objects filtered by the defaultLocationId column
 * @method     ChildEventType[]|ObjectCollection findByRehearsal(int $rehearsal) Return ChildEventType objects filtered by the rehearsal column
 * @method     ChildEventType[]|ObjectCollection findByGroupFormat(int $groupformat) Return ChildEventType objects filtered by the groupformat column
 * @method     ChildEventType[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class EventTypeQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \TechWilk\Rota\Base\EventTypeQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\TechWilk\\Rota\\EventType', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildEventTypeQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildEventTypeQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildEventTypeQuery) {
            return $criteria;
        }
        $query = new ChildEventTypeQuery();
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
     * @return ChildEventType|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(EventTypeTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = EventTypeTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildEventType A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, name, description, defaultDay, defaultTime, defaultRepitition, defaultLocationId, rehearsal, groupformat FROM eventTypes WHERE id = :p0';
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
            /** @var ChildEventType $obj */
            $obj = new ChildEventType();
            $obj->hydrate($row);
            EventTypeTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildEventType|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildEventTypeQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        return $this->addUsingAlias(EventTypeTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildEventTypeQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        return $this->addUsingAlias(EventTypeTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildEventTypeQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(EventTypeTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(EventTypeTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventTypeTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the name column
     *
     * Example usage:
     * <code>
     * $query->filterByName('fooValue');   // WHERE name = 'fooValue'
     * $query->filterByName('%fooValue%', Criteria::LIKE); // WHERE name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $name The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildEventTypeQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventTypeTableMap::COL_NAME, $name, $comparison);
    }

    /**
     * Filter the query on the description column
     *
     * Example usage:
     * <code>
     * $query->filterByDescription('fooValue');   // WHERE description = 'fooValue'
     * $query->filterByDescription('%fooValue%', Criteria::LIKE); // WHERE description LIKE '%fooValue%'
     * </code>
     *
     * @param     string $description The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildEventTypeQuery The current query, for fluid interface
     */
    public function filterByDescription($description = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($description)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventTypeTableMap::COL_DESCRIPTION, $description, $comparison);
    }

    /**
     * Filter the query on the defaultDay column
     *
     * Example usage:
     * <code>
     * $query->filterByDefaultDay(1234); // WHERE defaultDay = 1234
     * $query->filterByDefaultDay(array(12, 34)); // WHERE defaultDay IN (12, 34)
     * $query->filterByDefaultDay(array('min' => 12)); // WHERE defaultDay > 12
     * </code>
     *
     * @param     mixed $defaultDay The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildEventTypeQuery The current query, for fluid interface
     */
    public function filterByDefaultDay($defaultDay = null, $comparison = null)
    {
        if (is_array($defaultDay)) {
            $useMinMax = false;
            if (isset($defaultDay['min'])) {
                $this->addUsingAlias(EventTypeTableMap::COL_DEFAULTDAY, $defaultDay['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($defaultDay['max'])) {
                $this->addUsingAlias(EventTypeTableMap::COL_DEFAULTDAY, $defaultDay['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventTypeTableMap::COL_DEFAULTDAY, $defaultDay, $comparison);
    }

    /**
     * Filter the query on the defaultTime column
     *
     * Example usage:
     * <code>
     * $query->filterByDefaultTime('2011-03-14'); // WHERE defaultTime = '2011-03-14'
     * $query->filterByDefaultTime('now'); // WHERE defaultTime = '2011-03-14'
     * $query->filterByDefaultTime(array('max' => 'yesterday')); // WHERE defaultTime > '2011-03-13'
     * </code>
     *
     * @param     mixed $defaultTime The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildEventTypeQuery The current query, for fluid interface
     */
    public function filterByDefaultTime($defaultTime = null, $comparison = null)
    {
        if (is_array($defaultTime)) {
            $useMinMax = false;
            if (isset($defaultTime['min'])) {
                $this->addUsingAlias(EventTypeTableMap::COL_DEFAULTTIME, $defaultTime['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($defaultTime['max'])) {
                $this->addUsingAlias(EventTypeTableMap::COL_DEFAULTTIME, $defaultTime['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventTypeTableMap::COL_DEFAULTTIME, $defaultTime, $comparison);
    }

    /**
     * Filter the query on the defaultRepitition column
     *
     * Example usage:
     * <code>
     * $query->filterByDefaultRepitition(1234); // WHERE defaultRepitition = 1234
     * $query->filterByDefaultRepitition(array(12, 34)); // WHERE defaultRepitition IN (12, 34)
     * $query->filterByDefaultRepitition(array('min' => 12)); // WHERE defaultRepitition > 12
     * </code>
     *
     * @param     mixed $defaultRepitition The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildEventTypeQuery The current query, for fluid interface
     */
    public function filterByDefaultRepitition($defaultRepitition = null, $comparison = null)
    {
        if (is_array($defaultRepitition)) {
            $useMinMax = false;
            if (isset($defaultRepitition['min'])) {
                $this->addUsingAlias(EventTypeTableMap::COL_DEFAULTREPITITION, $defaultRepitition['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($defaultRepitition['max'])) {
                $this->addUsingAlias(EventTypeTableMap::COL_DEFAULTREPITITION, $defaultRepitition['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventTypeTableMap::COL_DEFAULTREPITITION, $defaultRepitition, $comparison);
    }

    /**
     * Filter the query on the defaultLocationId column
     *
     * Example usage:
     * <code>
     * $query->filterByDefaultLocationId(1234); // WHERE defaultLocationId = 1234
     * $query->filterByDefaultLocationId(array(12, 34)); // WHERE defaultLocationId IN (12, 34)
     * $query->filterByDefaultLocationId(array('min' => 12)); // WHERE defaultLocationId > 12
     * </code>
     *
     * @see       filterByLocation()
     *
     * @param     mixed $defaultLocationId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildEventTypeQuery The current query, for fluid interface
     */
    public function filterByDefaultLocationId($defaultLocationId = null, $comparison = null)
    {
        if (is_array($defaultLocationId)) {
            $useMinMax = false;
            if (isset($defaultLocationId['min'])) {
                $this->addUsingAlias(EventTypeTableMap::COL_DEFAULTLOCATIONID, $defaultLocationId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($defaultLocationId['max'])) {
                $this->addUsingAlias(EventTypeTableMap::COL_DEFAULTLOCATIONID, $defaultLocationId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventTypeTableMap::COL_DEFAULTLOCATIONID, $defaultLocationId, $comparison);
    }

    /**
     * Filter the query on the rehearsal column
     *
     * Example usage:
     * <code>
     * $query->filterByRehearsal(1234); // WHERE rehearsal = 1234
     * $query->filterByRehearsal(array(12, 34)); // WHERE rehearsal IN (12, 34)
     * $query->filterByRehearsal(array('min' => 12)); // WHERE rehearsal > 12
     * </code>
     *
     * @param     mixed $rehearsal The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildEventTypeQuery The current query, for fluid interface
     */
    public function filterByRehearsal($rehearsal = null, $comparison = null)
    {
        if (is_array($rehearsal)) {
            $useMinMax = false;
            if (isset($rehearsal['min'])) {
                $this->addUsingAlias(EventTypeTableMap::COL_REHEARSAL, $rehearsal['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($rehearsal['max'])) {
                $this->addUsingAlias(EventTypeTableMap::COL_REHEARSAL, $rehearsal['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventTypeTableMap::COL_REHEARSAL, $rehearsal, $comparison);
    }

    /**
     * Filter the query on the groupformat column
     *
     * Example usage:
     * <code>
     * $query->filterByGroupFormat(1234); // WHERE groupformat = 1234
     * $query->filterByGroupFormat(array(12, 34)); // WHERE groupformat IN (12, 34)
     * $query->filterByGroupFormat(array('min' => 12)); // WHERE groupformat > 12
     * </code>
     *
     * @param     mixed $groupFormat The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildEventTypeQuery The current query, for fluid interface
     */
    public function filterByGroupFormat($groupFormat = null, $comparison = null)
    {
        if (is_array($groupFormat)) {
            $useMinMax = false;
            if (isset($groupFormat['min'])) {
                $this->addUsingAlias(EventTypeTableMap::COL_GROUPFORMAT, $groupFormat['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($groupFormat['max'])) {
                $this->addUsingAlias(EventTypeTableMap::COL_GROUPFORMAT, $groupFormat['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventTypeTableMap::COL_GROUPFORMAT, $groupFormat, $comparison);
    }

    /**
     * Filter the query by a related \TechWilk\Rota\Location object
     *
     * @param \TechWilk\Rota\Location|ObjectCollection $location The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildEventTypeQuery The current query, for fluid interface
     */
    public function filterByLocation($location, $comparison = null)
    {
        if ($location instanceof \TechWilk\Rota\Location) {
            return $this
                ->addUsingAlias(EventTypeTableMap::COL_DEFAULTLOCATIONID, $location->getId(), $comparison);
        } elseif ($location instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(EventTypeTableMap::COL_DEFAULTLOCATIONID, $location->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByLocation() only accepts arguments of type \TechWilk\Rota\Location or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Location relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildEventTypeQuery The current query, for fluid interface
     */
    public function joinLocation($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Location');

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
            $this->addJoinObject($join, 'Location');
        }

        return $this;
    }

    /**
     * Use the Location relation Location object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \TechWilk\Rota\LocationQuery A secondary query class using the current class as primary query
     */
    public function useLocationQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinLocation($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Location', '\TechWilk\Rota\LocationQuery');
    }

    /**
     * Filter the query by a related \TechWilk\Rota\Event object
     *
     * @param \TechWilk\Rota\Event|ObjectCollection $event the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildEventTypeQuery The current query, for fluid interface
     */
    public function filterByEvent($event, $comparison = null)
    {
        if ($event instanceof \TechWilk\Rota\Event) {
            return $this
                ->addUsingAlias(EventTypeTableMap::COL_ID, $event->getEventTypeId(), $comparison);
        } elseif ($event instanceof ObjectCollection) {
            return $this
                ->useEventQuery()
                ->filterByPrimaryKeys($event->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByEvent() only accepts arguments of type \TechWilk\Rota\Event or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Event relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildEventTypeQuery The current query, for fluid interface
     */
    public function joinEvent($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Event');

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
            $this->addJoinObject($join, 'Event');
        }

        return $this;
    }

    /**
     * Use the Event relation Event object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \TechWilk\Rota\EventQuery A secondary query class using the current class as primary query
     */
    public function useEventQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinEvent($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Event', '\TechWilk\Rota\EventQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildEventType $eventType Object to remove from the list of results
     *
     * @return $this|ChildEventTypeQuery The current query, for fluid interface
     */
    public function prune($eventType = null)
    {
        if ($eventType) {
            $this->addUsingAlias(EventTypeTableMap::COL_ID, $eventType->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the eventTypes table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(EventTypeTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            EventTypeTableMap::clearInstancePool();
            EventTypeTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(EventTypeTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(EventTypeTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            EventTypeTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            EventTypeTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }
} // EventTypeQuery
