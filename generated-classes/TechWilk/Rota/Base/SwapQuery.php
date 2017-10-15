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
use TechWilk\Rota\Swap as ChildSwap;
use TechWilk\Rota\SwapQuery as ChildSwapQuery;
use TechWilk\Rota\Map\SwapTableMap;

/**
 * Base class that represents a query for the 'swaps' table.
 *
 *
 *
 * @method     ChildSwapQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildSwapQuery orderByEventPersonId($order = Criteria::ASC) Order by the eventPersonId column
 * @method     ChildSwapQuery orderByOldUserRoleId($order = Criteria::ASC) Order by the oldUserRoleId column
 * @method     ChildSwapQuery orderByNewUserRoleId($order = Criteria::ASC) Order by the newUserRoleId column
 * @method     ChildSwapQuery orderByAccepted($order = Criteria::ASC) Order by the accepted column
 * @method     ChildSwapQuery orderByDeclined($order = Criteria::ASC) Order by the declined column
 * @method     ChildSwapQuery orderByRequestedBy($order = Criteria::ASC) Order by the requestedBy column
 * @method     ChildSwapQuery orderByVerificationCode($order = Criteria::ASC) Order by the verificationCode column
 * @method     ChildSwapQuery orderByCreated($order = Criteria::ASC) Order by the created column
 * @method     ChildSwapQuery orderByUpdated($order = Criteria::ASC) Order by the updated column
 *
 * @method     ChildSwapQuery groupById() Group by the id column
 * @method     ChildSwapQuery groupByEventPersonId() Group by the eventPersonId column
 * @method     ChildSwapQuery groupByOldUserRoleId() Group by the oldUserRoleId column
 * @method     ChildSwapQuery groupByNewUserRoleId() Group by the newUserRoleId column
 * @method     ChildSwapQuery groupByAccepted() Group by the accepted column
 * @method     ChildSwapQuery groupByDeclined() Group by the declined column
 * @method     ChildSwapQuery groupByRequestedBy() Group by the requestedBy column
 * @method     ChildSwapQuery groupByVerificationCode() Group by the verificationCode column
 * @method     ChildSwapQuery groupByCreated() Group by the created column
 * @method     ChildSwapQuery groupByUpdated() Group by the updated column
 *
 * @method     ChildSwapQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildSwapQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildSwapQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildSwapQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildSwapQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildSwapQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildSwapQuery leftJoinEventPerson($relationAlias = null) Adds a LEFT JOIN clause to the query using the EventPerson relation
 * @method     ChildSwapQuery rightJoinEventPerson($relationAlias = null) Adds a RIGHT JOIN clause to the query using the EventPerson relation
 * @method     ChildSwapQuery innerJoinEventPerson($relationAlias = null) Adds a INNER JOIN clause to the query using the EventPerson relation
 *
 * @method     ChildSwapQuery joinWithEventPerson($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the EventPerson relation
 *
 * @method     ChildSwapQuery leftJoinWithEventPerson() Adds a LEFT JOIN clause and with to the query using the EventPerson relation
 * @method     ChildSwapQuery rightJoinWithEventPerson() Adds a RIGHT JOIN clause and with to the query using the EventPerson relation
 * @method     ChildSwapQuery innerJoinWithEventPerson() Adds a INNER JOIN clause and with to the query using the EventPerson relation
 *
 * @method     ChildSwapQuery leftJoinOldUserRole($relationAlias = null) Adds a LEFT JOIN clause to the query using the OldUserRole relation
 * @method     ChildSwapQuery rightJoinOldUserRole($relationAlias = null) Adds a RIGHT JOIN clause to the query using the OldUserRole relation
 * @method     ChildSwapQuery innerJoinOldUserRole($relationAlias = null) Adds a INNER JOIN clause to the query using the OldUserRole relation
 *
 * @method     ChildSwapQuery joinWithOldUserRole($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the OldUserRole relation
 *
 * @method     ChildSwapQuery leftJoinWithOldUserRole() Adds a LEFT JOIN clause and with to the query using the OldUserRole relation
 * @method     ChildSwapQuery rightJoinWithOldUserRole() Adds a RIGHT JOIN clause and with to the query using the OldUserRole relation
 * @method     ChildSwapQuery innerJoinWithOldUserRole() Adds a INNER JOIN clause and with to the query using the OldUserRole relation
 *
 * @method     ChildSwapQuery leftJoinNewUserRole($relationAlias = null) Adds a LEFT JOIN clause to the query using the NewUserRole relation
 * @method     ChildSwapQuery rightJoinNewUserRole($relationAlias = null) Adds a RIGHT JOIN clause to the query using the NewUserRole relation
 * @method     ChildSwapQuery innerJoinNewUserRole($relationAlias = null) Adds a INNER JOIN clause to the query using the NewUserRole relation
 *
 * @method     ChildSwapQuery joinWithNewUserRole($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the NewUserRole relation
 *
 * @method     ChildSwapQuery leftJoinWithNewUserRole() Adds a LEFT JOIN clause and with to the query using the NewUserRole relation
 * @method     ChildSwapQuery rightJoinWithNewUserRole() Adds a RIGHT JOIN clause and with to the query using the NewUserRole relation
 * @method     ChildSwapQuery innerJoinWithNewUserRole() Adds a INNER JOIN clause and with to the query using the NewUserRole relation
 *
 * @method     ChildSwapQuery leftJoinUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the User relation
 * @method     ChildSwapQuery rightJoinUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the User relation
 * @method     ChildSwapQuery innerJoinUser($relationAlias = null) Adds a INNER JOIN clause to the query using the User relation
 *
 * @method     ChildSwapQuery joinWithUser($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the User relation
 *
 * @method     ChildSwapQuery leftJoinWithUser() Adds a LEFT JOIN clause and with to the query using the User relation
 * @method     ChildSwapQuery rightJoinWithUser() Adds a RIGHT JOIN clause and with to the query using the User relation
 * @method     ChildSwapQuery innerJoinWithUser() Adds a INNER JOIN clause and with to the query using the User relation
 *
 * @method     \TechWilk\Rota\EventPersonQuery|\TechWilk\Rota\UserRoleQuery|\TechWilk\Rota\UserQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildSwap findOne(ConnectionInterface $con = null) Return the first ChildSwap matching the query
 * @method     ChildSwap findOneOrCreate(ConnectionInterface $con = null) Return the first ChildSwap matching the query, or a new ChildSwap object populated from the query conditions when no match is found
 *
 * @method     ChildSwap findOneById(int $id) Return the first ChildSwap filtered by the id column
 * @method     ChildSwap findOneByEventPersonId(int $eventPersonId) Return the first ChildSwap filtered by the eventPersonId column
 * @method     ChildSwap findOneByOldUserRoleId(int $oldUserRoleId) Return the first ChildSwap filtered by the oldUserRoleId column
 * @method     ChildSwap findOneByNewUserRoleId(int $newUserRoleId) Return the first ChildSwap filtered by the newUserRoleId column
 * @method     ChildSwap findOneByAccepted(int $accepted) Return the first ChildSwap filtered by the accepted column
 * @method     ChildSwap findOneByDeclined(int $declined) Return the first ChildSwap filtered by the declined column
 * @method     ChildSwap findOneByRequestedBy(int $requestedBy) Return the first ChildSwap filtered by the requestedBy column
 * @method     ChildSwap findOneByVerificationCode(string $verificationCode) Return the first ChildSwap filtered by the verificationCode column
 * @method     ChildSwap findOneByCreated(string $created) Return the first ChildSwap filtered by the created column
 * @method     ChildSwap findOneByUpdated(string $updated) Return the first ChildSwap filtered by the updated column *

 * @method     ChildSwap requirePk($key, ConnectionInterface $con = null) Return the ChildSwap by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSwap requireOne(ConnectionInterface $con = null) Return the first ChildSwap matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildSwap requireOneById(int $id) Return the first ChildSwap filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSwap requireOneByEventPersonId(int $eventPersonId) Return the first ChildSwap filtered by the eventPersonId column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSwap requireOneByOldUserRoleId(int $oldUserRoleId) Return the first ChildSwap filtered by the oldUserRoleId column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSwap requireOneByNewUserRoleId(int $newUserRoleId) Return the first ChildSwap filtered by the newUserRoleId column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSwap requireOneByAccepted(int $accepted) Return the first ChildSwap filtered by the accepted column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSwap requireOneByDeclined(int $declined) Return the first ChildSwap filtered by the declined column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSwap requireOneByRequestedBy(int $requestedBy) Return the first ChildSwap filtered by the requestedBy column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSwap requireOneByVerificationCode(string $verificationCode) Return the first ChildSwap filtered by the verificationCode column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSwap requireOneByCreated(string $created) Return the first ChildSwap filtered by the created column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSwap requireOneByUpdated(string $updated) Return the first ChildSwap filtered by the updated column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildSwap[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildSwap objects based on current ModelCriteria
 * @method     ChildSwap[]|ObjectCollection findById(int $id) Return ChildSwap objects filtered by the id column
 * @method     ChildSwap[]|ObjectCollection findByEventPersonId(int $eventPersonId) Return ChildSwap objects filtered by the eventPersonId column
 * @method     ChildSwap[]|ObjectCollection findByOldUserRoleId(int $oldUserRoleId) Return ChildSwap objects filtered by the oldUserRoleId column
 * @method     ChildSwap[]|ObjectCollection findByNewUserRoleId(int $newUserRoleId) Return ChildSwap objects filtered by the newUserRoleId column
 * @method     ChildSwap[]|ObjectCollection findByAccepted(int $accepted) Return ChildSwap objects filtered by the accepted column
 * @method     ChildSwap[]|ObjectCollection findByDeclined(int $declined) Return ChildSwap objects filtered by the declined column
 * @method     ChildSwap[]|ObjectCollection findByRequestedBy(int $requestedBy) Return ChildSwap objects filtered by the requestedBy column
 * @method     ChildSwap[]|ObjectCollection findByVerificationCode(string $verificationCode) Return ChildSwap objects filtered by the verificationCode column
 * @method     ChildSwap[]|ObjectCollection findByCreated(string $created) Return ChildSwap objects filtered by the created column
 * @method     ChildSwap[]|ObjectCollection findByUpdated(string $updated) Return ChildSwap objects filtered by the updated column
 * @method     ChildSwap[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class SwapQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \TechWilk\Rota\Base\SwapQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\TechWilk\\Rota\\Swap', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildSwapQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildSwapQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildSwapQuery) {
            return $criteria;
        }
        $query = new ChildSwapQuery();
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
     * @return ChildSwap|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(SwapTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = SwapTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildSwap A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, eventPersonId, oldUserRoleId, newUserRoleId, accepted, declined, requestedBy, verificationCode, created, updated FROM swaps WHERE id = :p0';
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
            /** @var ChildSwap $obj */
            $obj = new ChildSwap();
            $obj->hydrate($row);
            SwapTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildSwap|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildSwapQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        return $this->addUsingAlias(SwapTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildSwapQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        return $this->addUsingAlias(SwapTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildSwapQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(SwapTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(SwapTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SwapTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the eventPersonId column
     *
     * Example usage:
     * <code>
     * $query->filterByEventPersonId(1234); // WHERE eventPersonId = 1234
     * $query->filterByEventPersonId(array(12, 34)); // WHERE eventPersonId IN (12, 34)
     * $query->filterByEventPersonId(array('min' => 12)); // WHERE eventPersonId > 12
     * </code>
     *
     * @see       filterByEventPerson()
     *
     * @param     mixed $eventPersonId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSwapQuery The current query, for fluid interface
     */
    public function filterByEventPersonId($eventPersonId = null, $comparison = null)
    {
        if (is_array($eventPersonId)) {
            $useMinMax = false;
            if (isset($eventPersonId['min'])) {
                $this->addUsingAlias(SwapTableMap::COL_EVENTPERSONID, $eventPersonId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($eventPersonId['max'])) {
                $this->addUsingAlias(SwapTableMap::COL_EVENTPERSONID, $eventPersonId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SwapTableMap::COL_EVENTPERSONID, $eventPersonId, $comparison);
    }

    /**
     * Filter the query on the oldUserRoleId column
     *
     * Example usage:
     * <code>
     * $query->filterByOldUserRoleId(1234); // WHERE oldUserRoleId = 1234
     * $query->filterByOldUserRoleId(array(12, 34)); // WHERE oldUserRoleId IN (12, 34)
     * $query->filterByOldUserRoleId(array('min' => 12)); // WHERE oldUserRoleId > 12
     * </code>
     *
     * @see       filterByOldUserRole()
     *
     * @param     mixed $oldUserRoleId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSwapQuery The current query, for fluid interface
     */
    public function filterByOldUserRoleId($oldUserRoleId = null, $comparison = null)
    {
        if (is_array($oldUserRoleId)) {
            $useMinMax = false;
            if (isset($oldUserRoleId['min'])) {
                $this->addUsingAlias(SwapTableMap::COL_OLDUSERROLEID, $oldUserRoleId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($oldUserRoleId['max'])) {
                $this->addUsingAlias(SwapTableMap::COL_OLDUSERROLEID, $oldUserRoleId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SwapTableMap::COL_OLDUSERROLEID, $oldUserRoleId, $comparison);
    }

    /**
     * Filter the query on the newUserRoleId column
     *
     * Example usage:
     * <code>
     * $query->filterByNewUserRoleId(1234); // WHERE newUserRoleId = 1234
     * $query->filterByNewUserRoleId(array(12, 34)); // WHERE newUserRoleId IN (12, 34)
     * $query->filterByNewUserRoleId(array('min' => 12)); // WHERE newUserRoleId > 12
     * </code>
     *
     * @see       filterByNewUserRole()
     *
     * @param     mixed $newUserRoleId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSwapQuery The current query, for fluid interface
     */
    public function filterByNewUserRoleId($newUserRoleId = null, $comparison = null)
    {
        if (is_array($newUserRoleId)) {
            $useMinMax = false;
            if (isset($newUserRoleId['min'])) {
                $this->addUsingAlias(SwapTableMap::COL_NEWUSERROLEID, $newUserRoleId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($newUserRoleId['max'])) {
                $this->addUsingAlias(SwapTableMap::COL_NEWUSERROLEID, $newUserRoleId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SwapTableMap::COL_NEWUSERROLEID, $newUserRoleId, $comparison);
    }

    /**
     * Filter the query on the accepted column
     *
     * Example usage:
     * <code>
     * $query->filterByAccepted(1234); // WHERE accepted = 1234
     * $query->filterByAccepted(array(12, 34)); // WHERE accepted IN (12, 34)
     * $query->filterByAccepted(array('min' => 12)); // WHERE accepted > 12
     * </code>
     *
     * @param     mixed $accepted The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSwapQuery The current query, for fluid interface
     */
    public function filterByAccepted($accepted = null, $comparison = null)
    {
        if (is_array($accepted)) {
            $useMinMax = false;
            if (isset($accepted['min'])) {
                $this->addUsingAlias(SwapTableMap::COL_ACCEPTED, $accepted['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($accepted['max'])) {
                $this->addUsingAlias(SwapTableMap::COL_ACCEPTED, $accepted['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SwapTableMap::COL_ACCEPTED, $accepted, $comparison);
    }

    /**
     * Filter the query on the declined column
     *
     * Example usage:
     * <code>
     * $query->filterByDeclined(1234); // WHERE declined = 1234
     * $query->filterByDeclined(array(12, 34)); // WHERE declined IN (12, 34)
     * $query->filterByDeclined(array('min' => 12)); // WHERE declined > 12
     * </code>
     *
     * @param     mixed $declined The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSwapQuery The current query, for fluid interface
     */
    public function filterByDeclined($declined = null, $comparison = null)
    {
        if (is_array($declined)) {
            $useMinMax = false;
            if (isset($declined['min'])) {
                $this->addUsingAlias(SwapTableMap::COL_DECLINED, $declined['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($declined['max'])) {
                $this->addUsingAlias(SwapTableMap::COL_DECLINED, $declined['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SwapTableMap::COL_DECLINED, $declined, $comparison);
    }

    /**
     * Filter the query on the requestedBy column
     *
     * Example usage:
     * <code>
     * $query->filterByRequestedBy(1234); // WHERE requestedBy = 1234
     * $query->filterByRequestedBy(array(12, 34)); // WHERE requestedBy IN (12, 34)
     * $query->filterByRequestedBy(array('min' => 12)); // WHERE requestedBy > 12
     * </code>
     *
     * @see       filterByUser()
     *
     * @param     mixed $requestedBy The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSwapQuery The current query, for fluid interface
     */
    public function filterByRequestedBy($requestedBy = null, $comparison = null)
    {
        if (is_array($requestedBy)) {
            $useMinMax = false;
            if (isset($requestedBy['min'])) {
                $this->addUsingAlias(SwapTableMap::COL_REQUESTEDBY, $requestedBy['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($requestedBy['max'])) {
                $this->addUsingAlias(SwapTableMap::COL_REQUESTEDBY, $requestedBy['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SwapTableMap::COL_REQUESTEDBY, $requestedBy, $comparison);
    }

    /**
     * Filter the query on the verificationCode column
     *
     * Example usage:
     * <code>
     * $query->filterByVerificationCode('fooValue');   // WHERE verificationCode = 'fooValue'
     * $query->filterByVerificationCode('%fooValue%', Criteria::LIKE); // WHERE verificationCode LIKE '%fooValue%'
     * </code>
     *
     * @param     string $verificationCode The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSwapQuery The current query, for fluid interface
     */
    public function filterByVerificationCode($verificationCode = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($verificationCode)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SwapTableMap::COL_VERIFICATIONCODE, $verificationCode, $comparison);
    }

    /**
     * Filter the query on the created column
     *
     * Example usage:
     * <code>
     * $query->filterByCreated('2011-03-14'); // WHERE created = '2011-03-14'
     * $query->filterByCreated('now'); // WHERE created = '2011-03-14'
     * $query->filterByCreated(array('max' => 'yesterday')); // WHERE created > '2011-03-13'
     * </code>
     *
     * @param     mixed $created The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSwapQuery The current query, for fluid interface
     */
    public function filterByCreated($created = null, $comparison = null)
    {
        if (is_array($created)) {
            $useMinMax = false;
            if (isset($created['min'])) {
                $this->addUsingAlias(SwapTableMap::COL_CREATED, $created['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($created['max'])) {
                $this->addUsingAlias(SwapTableMap::COL_CREATED, $created['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SwapTableMap::COL_CREATED, $created, $comparison);
    }

    /**
     * Filter the query on the updated column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdated('2011-03-14'); // WHERE updated = '2011-03-14'
     * $query->filterByUpdated('now'); // WHERE updated = '2011-03-14'
     * $query->filterByUpdated(array('max' => 'yesterday')); // WHERE updated > '2011-03-13'
     * </code>
     *
     * @param     mixed $updated The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSwapQuery The current query, for fluid interface
     */
    public function filterByUpdated($updated = null, $comparison = null)
    {
        if (is_array($updated)) {
            $useMinMax = false;
            if (isset($updated['min'])) {
                $this->addUsingAlias(SwapTableMap::COL_UPDATED, $updated['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updated['max'])) {
                $this->addUsingAlias(SwapTableMap::COL_UPDATED, $updated['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SwapTableMap::COL_UPDATED, $updated, $comparison);
    }

    /**
     * Filter the query by a related \TechWilk\Rota\EventPerson object
     *
     * @param \TechWilk\Rota\EventPerson|ObjectCollection $eventPerson The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildSwapQuery The current query, for fluid interface
     */
    public function filterByEventPerson($eventPerson, $comparison = null)
    {
        if ($eventPerson instanceof \TechWilk\Rota\EventPerson) {
            return $this
                ->addUsingAlias(SwapTableMap::COL_EVENTPERSONID, $eventPerson->getId(), $comparison);
        } elseif ($eventPerson instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(SwapTableMap::COL_EVENTPERSONID, $eventPerson->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByEventPerson() only accepts arguments of type \TechWilk\Rota\EventPerson or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the EventPerson relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildSwapQuery The current query, for fluid interface
     */
    public function joinEventPerson($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('EventPerson');

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
            $this->addJoinObject($join, 'EventPerson');
        }

        return $this;
    }

    /**
     * Use the EventPerson relation EventPerson object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \TechWilk\Rota\EventPersonQuery A secondary query class using the current class as primary query
     */
    public function useEventPersonQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinEventPerson($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'EventPerson', '\TechWilk\Rota\EventPersonQuery');
    }

    /**
     * Filter the query by a related \TechWilk\Rota\UserRole object
     *
     * @param \TechWilk\Rota\UserRole|ObjectCollection $userRole The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildSwapQuery The current query, for fluid interface
     */
    public function filterByOldUserRole($userRole, $comparison = null)
    {
        if ($userRole instanceof \TechWilk\Rota\UserRole) {
            return $this
                ->addUsingAlias(SwapTableMap::COL_OLDUSERROLEID, $userRole->getId(), $comparison);
        } elseif ($userRole instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(SwapTableMap::COL_OLDUSERROLEID, $userRole->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByOldUserRole() only accepts arguments of type \TechWilk\Rota\UserRole or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the OldUserRole relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildSwapQuery The current query, for fluid interface
     */
    public function joinOldUserRole($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('OldUserRole');

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
            $this->addJoinObject($join, 'OldUserRole');
        }

        return $this;
    }

    /**
     * Use the OldUserRole relation UserRole object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \TechWilk\Rota\UserRoleQuery A secondary query class using the current class as primary query
     */
    public function useOldUserRoleQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinOldUserRole($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'OldUserRole', '\TechWilk\Rota\UserRoleQuery');
    }

    /**
     * Filter the query by a related \TechWilk\Rota\UserRole object
     *
     * @param \TechWilk\Rota\UserRole|ObjectCollection $userRole The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildSwapQuery The current query, for fluid interface
     */
    public function filterByNewUserRole($userRole, $comparison = null)
    {
        if ($userRole instanceof \TechWilk\Rota\UserRole) {
            return $this
                ->addUsingAlias(SwapTableMap::COL_NEWUSERROLEID, $userRole->getId(), $comparison);
        } elseif ($userRole instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(SwapTableMap::COL_NEWUSERROLEID, $userRole->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByNewUserRole() only accepts arguments of type \TechWilk\Rota\UserRole or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the NewUserRole relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildSwapQuery The current query, for fluid interface
     */
    public function joinNewUserRole($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('NewUserRole');

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
            $this->addJoinObject($join, 'NewUserRole');
        }

        return $this;
    }

    /**
     * Use the NewUserRole relation UserRole object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \TechWilk\Rota\UserRoleQuery A secondary query class using the current class as primary query
     */
    public function useNewUserRoleQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinNewUserRole($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'NewUserRole', '\TechWilk\Rota\UserRoleQuery');
    }

    /**
     * Filter the query by a related \TechWilk\Rota\User object
     *
     * @param \TechWilk\Rota\User|ObjectCollection $user The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildSwapQuery The current query, for fluid interface
     */
    public function filterByUser($user, $comparison = null)
    {
        if ($user instanceof \TechWilk\Rota\User) {
            return $this
                ->addUsingAlias(SwapTableMap::COL_REQUESTEDBY, $user->getId(), $comparison);
        } elseif ($user instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(SwapTableMap::COL_REQUESTEDBY, $user->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByUser() only accepts arguments of type \TechWilk\Rota\User or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the User relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildSwapQuery The current query, for fluid interface
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
     * @return \TechWilk\Rota\UserQuery A secondary query class using the current class as primary query
     */
    public function useUserQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinUser($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'User', '\TechWilk\Rota\UserQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildSwap $swap Object to remove from the list of results
     *
     * @return $this|ChildSwapQuery The current query, for fluid interface
     */
    public function prune($swap = null)
    {
        if ($swap) {
            $this->addUsingAlias(SwapTableMap::COL_ID, $swap->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the swaps table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(SwapTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            SwapTableMap::clearInstancePool();
            SwapTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(SwapTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(SwapTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            SwapTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            SwapTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     $this|ChildSwapQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(SwapTableMap::COL_UPDATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     $this|ChildSwapQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(SwapTableMap::COL_UPDATED);
    }

    /**
     * Order by update date asc
     *
     * @return     $this|ChildSwapQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(SwapTableMap::COL_UPDATED);
    }

    /**
     * Order by create date desc
     *
     * @return     $this|ChildSwapQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(SwapTableMap::COL_CREATED);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     $this|ChildSwapQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(SwapTableMap::COL_CREATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date asc
     *
     * @return     $this|ChildSwapQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(SwapTableMap::COL_CREATED);
    }
} // SwapQuery
