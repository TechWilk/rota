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
use TechWilk\Rota\EventPerson as ChildEventPerson;
use TechWilk\Rota\EventPersonQuery as ChildEventPersonQuery;
use TechWilk\Rota\Map\EventPersonTableMap;

/**
 * Base class that represents a query for the 'cr_eventPeople' table.
 *
 *
 *
 * @method     ChildEventPersonQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildEventPersonQuery orderByEventId($order = Criteria::ASC) Order by the eventId column
 * @method     ChildEventPersonQuery orderByUserRoleId($order = Criteria::ASC) Order by the userRoleId column
 * @method     ChildEventPersonQuery orderByNotified($order = Criteria::ASC) Order by the notified column
 * @method     ChildEventPersonQuery orderByRemoved($order = Criteria::ASC) Order by the removed column
 *
 * @method     ChildEventPersonQuery groupById() Group by the id column
 * @method     ChildEventPersonQuery groupByEventId() Group by the eventId column
 * @method     ChildEventPersonQuery groupByUserRoleId() Group by the userRoleId column
 * @method     ChildEventPersonQuery groupByNotified() Group by the notified column
 * @method     ChildEventPersonQuery groupByRemoved() Group by the removed column
 *
 * @method     ChildEventPersonQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildEventPersonQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildEventPersonQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildEventPersonQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildEventPersonQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildEventPersonQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildEventPersonQuery leftJoinEvent($relationAlias = null) Adds a LEFT JOIN clause to the query using the Event relation
 * @method     ChildEventPersonQuery rightJoinEvent($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Event relation
 * @method     ChildEventPersonQuery innerJoinEvent($relationAlias = null) Adds a INNER JOIN clause to the query using the Event relation
 *
 * @method     ChildEventPersonQuery joinWithEvent($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Event relation
 *
 * @method     ChildEventPersonQuery leftJoinWithEvent() Adds a LEFT JOIN clause and with to the query using the Event relation
 * @method     ChildEventPersonQuery rightJoinWithEvent() Adds a RIGHT JOIN clause and with to the query using the Event relation
 * @method     ChildEventPersonQuery innerJoinWithEvent() Adds a INNER JOIN clause and with to the query using the Event relation
 *
 * @method     ChildEventPersonQuery leftJoinUserRole($relationAlias = null) Adds a LEFT JOIN clause to the query using the UserRole relation
 * @method     ChildEventPersonQuery rightJoinUserRole($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UserRole relation
 * @method     ChildEventPersonQuery innerJoinUserRole($relationAlias = null) Adds a INNER JOIN clause to the query using the UserRole relation
 *
 * @method     ChildEventPersonQuery joinWithUserRole($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the UserRole relation
 *
 * @method     ChildEventPersonQuery leftJoinWithUserRole() Adds a LEFT JOIN clause and with to the query using the UserRole relation
 * @method     ChildEventPersonQuery rightJoinWithUserRole() Adds a RIGHT JOIN clause and with to the query using the UserRole relation
 * @method     ChildEventPersonQuery innerJoinWithUserRole() Adds a INNER JOIN clause and with to the query using the UserRole relation
 *
 * @method     ChildEventPersonQuery leftJoinSwap($relationAlias = null) Adds a LEFT JOIN clause to the query using the Swap relation
 * @method     ChildEventPersonQuery rightJoinSwap($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Swap relation
 * @method     ChildEventPersonQuery innerJoinSwap($relationAlias = null) Adds a INNER JOIN clause to the query using the Swap relation
 *
 * @method     ChildEventPersonQuery joinWithSwap($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Swap relation
 *
 * @method     ChildEventPersonQuery leftJoinWithSwap() Adds a LEFT JOIN clause and with to the query using the Swap relation
 * @method     ChildEventPersonQuery rightJoinWithSwap() Adds a RIGHT JOIN clause and with to the query using the Swap relation
 * @method     ChildEventPersonQuery innerJoinWithSwap() Adds a INNER JOIN clause and with to the query using the Swap relation
 *
 * @method     \TechWilk\Rota\EventQuery|\TechWilk\Rota\UserRoleQuery|\TechWilk\Rota\SwapQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildEventPerson findOne(ConnectionInterface $con = null) Return the first ChildEventPerson matching the query
 * @method     ChildEventPerson findOneOrCreate(ConnectionInterface $con = null) Return the first ChildEventPerson matching the query, or a new ChildEventPerson object populated from the query conditions when no match is found
 *
 * @method     ChildEventPerson findOneById(int $id) Return the first ChildEventPerson filtered by the id column
 * @method     ChildEventPerson findOneByEventId(int $eventId) Return the first ChildEventPerson filtered by the eventId column
 * @method     ChildEventPerson findOneByUserRoleId(int $userRoleId) Return the first ChildEventPerson filtered by the userRoleId column
 * @method     ChildEventPerson findOneByNotified(int $notified) Return the first ChildEventPerson filtered by the notified column
 * @method     ChildEventPerson findOneByRemoved(int $removed) Return the first ChildEventPerson filtered by the removed column *

 * @method     ChildEventPerson requirePk($key, ConnectionInterface $con = null) Return the ChildEventPerson by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEventPerson requireOne(ConnectionInterface $con = null) Return the first ChildEventPerson matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildEventPerson requireOneById(int $id) Return the first ChildEventPerson filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEventPerson requireOneByEventId(int $eventId) Return the first ChildEventPerson filtered by the eventId column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEventPerson requireOneByUserRoleId(int $userRoleId) Return the first ChildEventPerson filtered by the userRoleId column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEventPerson requireOneByNotified(int $notified) Return the first ChildEventPerson filtered by the notified column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEventPerson requireOneByRemoved(int $removed) Return the first ChildEventPerson filtered by the removed column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildEventPerson[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildEventPerson objects based on current ModelCriteria
 * @method     ChildEventPerson[]|ObjectCollection findById(int $id) Return ChildEventPerson objects filtered by the id column
 * @method     ChildEventPerson[]|ObjectCollection findByEventId(int $eventId) Return ChildEventPerson objects filtered by the eventId column
 * @method     ChildEventPerson[]|ObjectCollection findByUserRoleId(int $userRoleId) Return ChildEventPerson objects filtered by the userRoleId column
 * @method     ChildEventPerson[]|ObjectCollection findByNotified(int $notified) Return ChildEventPerson objects filtered by the notified column
 * @method     ChildEventPerson[]|ObjectCollection findByRemoved(int $removed) Return ChildEventPerson objects filtered by the removed column
 * @method     ChildEventPerson[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class EventPersonQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \TechWilk\Rota\Base\EventPersonQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\TechWilk\\Rota\\EventPerson', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildEventPersonQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildEventPersonQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildEventPersonQuery) {
            return $criteria;
        }
        $query = new ChildEventPersonQuery();
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
     * @return ChildEventPerson|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(EventPersonTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = EventPersonTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildEventPerson A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, eventId, userRoleId, notified, removed FROM cr_eventPeople WHERE id = :p0';
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
            /** @var ChildEventPerson $obj */
            $obj = new ChildEventPerson();
            $obj->hydrate($row);
            EventPersonTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildEventPerson|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildEventPersonQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        return $this->addUsingAlias(EventPersonTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildEventPersonQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        return $this->addUsingAlias(EventPersonTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildEventPersonQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(EventPersonTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(EventPersonTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventPersonTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the eventId column
     *
     * Example usage:
     * <code>
     * $query->filterByEventId(1234); // WHERE eventId = 1234
     * $query->filterByEventId(array(12, 34)); // WHERE eventId IN (12, 34)
     * $query->filterByEventId(array('min' => 12)); // WHERE eventId > 12
     * </code>
     *
     * @see       filterByEvent()
     *
     * @param     mixed $eventId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildEventPersonQuery The current query, for fluid interface
     */
    public function filterByEventId($eventId = null, $comparison = null)
    {
        if (is_array($eventId)) {
            $useMinMax = false;
            if (isset($eventId['min'])) {
                $this->addUsingAlias(EventPersonTableMap::COL_EVENTID, $eventId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($eventId['max'])) {
                $this->addUsingAlias(EventPersonTableMap::COL_EVENTID, $eventId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventPersonTableMap::COL_EVENTID, $eventId, $comparison);
    }

    /**
     * Filter the query on the userRoleId column
     *
     * Example usage:
     * <code>
     * $query->filterByUserRoleId(1234); // WHERE userRoleId = 1234
     * $query->filterByUserRoleId(array(12, 34)); // WHERE userRoleId IN (12, 34)
     * $query->filterByUserRoleId(array('min' => 12)); // WHERE userRoleId > 12
     * </code>
     *
     * @see       filterByUserRole()
     *
     * @param     mixed $userRoleId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildEventPersonQuery The current query, for fluid interface
     */
    public function filterByUserRoleId($userRoleId = null, $comparison = null)
    {
        if (is_array($userRoleId)) {
            $useMinMax = false;
            if (isset($userRoleId['min'])) {
                $this->addUsingAlias(EventPersonTableMap::COL_USERROLEID, $userRoleId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($userRoleId['max'])) {
                $this->addUsingAlias(EventPersonTableMap::COL_USERROLEID, $userRoleId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventPersonTableMap::COL_USERROLEID, $userRoleId, $comparison);
    }

    /**
     * Filter the query on the notified column
     *
     * Example usage:
     * <code>
     * $query->filterByNotified(1234); // WHERE notified = 1234
     * $query->filterByNotified(array(12, 34)); // WHERE notified IN (12, 34)
     * $query->filterByNotified(array('min' => 12)); // WHERE notified > 12
     * </code>
     *
     * @param     mixed $notified The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildEventPersonQuery The current query, for fluid interface
     */
    public function filterByNotified($notified = null, $comparison = null)
    {
        if (is_array($notified)) {
            $useMinMax = false;
            if (isset($notified['min'])) {
                $this->addUsingAlias(EventPersonTableMap::COL_NOTIFIED, $notified['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($notified['max'])) {
                $this->addUsingAlias(EventPersonTableMap::COL_NOTIFIED, $notified['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventPersonTableMap::COL_NOTIFIED, $notified, $comparison);
    }

    /**
     * Filter the query on the removed column
     *
     * Example usage:
     * <code>
     * $query->filterByRemoved(1234); // WHERE removed = 1234
     * $query->filterByRemoved(array(12, 34)); // WHERE removed IN (12, 34)
     * $query->filterByRemoved(array('min' => 12)); // WHERE removed > 12
     * </code>
     *
     * @param     mixed $removed The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildEventPersonQuery The current query, for fluid interface
     */
    public function filterByRemoved($removed = null, $comparison = null)
    {
        if (is_array($removed)) {
            $useMinMax = false;
            if (isset($removed['min'])) {
                $this->addUsingAlias(EventPersonTableMap::COL_REMOVED, $removed['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($removed['max'])) {
                $this->addUsingAlias(EventPersonTableMap::COL_REMOVED, $removed['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventPersonTableMap::COL_REMOVED, $removed, $comparison);
    }

    /**
     * Filter the query by a related \TechWilk\Rota\Event object
     *
     * @param \TechWilk\Rota\Event|ObjectCollection $event The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildEventPersonQuery The current query, for fluid interface
     */
    public function filterByEvent($event, $comparison = null)
    {
        if ($event instanceof \TechWilk\Rota\Event) {
            return $this
                ->addUsingAlias(EventPersonTableMap::COL_EVENTID, $event->getId(), $comparison);
        } elseif ($event instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(EventPersonTableMap::COL_EVENTID, $event->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return $this|ChildEventPersonQuery The current query, for fluid interface
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
     * Filter the query by a related \TechWilk\Rota\UserRole object
     *
     * @param \TechWilk\Rota\UserRole|ObjectCollection $userRole The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildEventPersonQuery The current query, for fluid interface
     */
    public function filterByUserRole($userRole, $comparison = null)
    {
        if ($userRole instanceof \TechWilk\Rota\UserRole) {
            return $this
                ->addUsingAlias(EventPersonTableMap::COL_USERROLEID, $userRole->getId(), $comparison);
        } elseif ($userRole instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(EventPersonTableMap::COL_USERROLEID, $userRole->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByUserRole() only accepts arguments of type \TechWilk\Rota\UserRole or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the UserRole relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildEventPersonQuery The current query, for fluid interface
     */
    public function joinUserRole($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('UserRole');

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
            $this->addJoinObject($join, 'UserRole');
        }

        return $this;
    }

    /**
     * Use the UserRole relation UserRole object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \TechWilk\Rota\UserRoleQuery A secondary query class using the current class as primary query
     */
    public function useUserRoleQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinUserRole($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'UserRole', '\TechWilk\Rota\UserRoleQuery');
    }

    /**
     * Filter the query by a related \TechWilk\Rota\Swap object
     *
     * @param \TechWilk\Rota\Swap|ObjectCollection $swap the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildEventPersonQuery The current query, for fluid interface
     */
    public function filterBySwap($swap, $comparison = null)
    {
        if ($swap instanceof \TechWilk\Rota\Swap) {
            return $this
                ->addUsingAlias(EventPersonTableMap::COL_ID, $swap->getEventPersonId(), $comparison);
        } elseif ($swap instanceof ObjectCollection) {
            return $this
                ->useSwapQuery()
                ->filterByPrimaryKeys($swap->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterBySwap() only accepts arguments of type \TechWilk\Rota\Swap or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Swap relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildEventPersonQuery The current query, for fluid interface
     */
    public function joinSwap($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Swap');

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
            $this->addJoinObject($join, 'Swap');
        }

        return $this;
    }

    /**
     * Use the Swap relation Swap object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \TechWilk\Rota\SwapQuery A secondary query class using the current class as primary query
     */
    public function useSwapQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinSwap($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Swap', '\TechWilk\Rota\SwapQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildEventPerson $eventPerson Object to remove from the list of results
     *
     * @return $this|ChildEventPersonQuery The current query, for fluid interface
     */
    public function prune($eventPerson = null)
    {
        if ($eventPerson) {
            $this->addUsingAlias(EventPersonTableMap::COL_ID, $eventPerson->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the cr_eventPeople table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(EventPersonTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            EventPersonTableMap::clearInstancePool();
            EventPersonTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(EventPersonTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(EventPersonTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            EventPersonTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            EventPersonTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }
} // EventPersonQuery
