<?php

namespace Base;

use \PendingUser as ChildPendingUser;
use \PendingUserQuery as ChildPendingUserQuery;
use \Exception;
use \PDO;
use Map\PendingUserTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'cr_pendingUsers' table.
 *
 *
 *
 * @method     ChildPendingUserQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildPendingUserQuery orderBySocialId($order = Criteria::ASC) Order by the socialId column
 * @method     ChildPendingUserQuery orderByFirstName($order = Criteria::ASC) Order by the firstName column
 * @method     ChildPendingUserQuery orderByLastName($order = Criteria::ASC) Order by the lastName column
 * @method     ChildPendingUserQuery orderByEmail($order = Criteria::ASC) Order by the email column
 * @method     ChildPendingUserQuery orderByApproved($order = Criteria::ASC) Order by the approved column
 * @method     ChildPendingUserQuery orderByDeclined($order = Criteria::ASC) Order by the declined column
 * @method     ChildPendingUserQuery orderBySource($order = Criteria::ASC) Order by the source column
 *
 * @method     ChildPendingUserQuery groupById() Group by the id column
 * @method     ChildPendingUserQuery groupBySocialId() Group by the socialId column
 * @method     ChildPendingUserQuery groupByFirstName() Group by the firstName column
 * @method     ChildPendingUserQuery groupByLastName() Group by the lastName column
 * @method     ChildPendingUserQuery groupByEmail() Group by the email column
 * @method     ChildPendingUserQuery groupByApproved() Group by the approved column
 * @method     ChildPendingUserQuery groupByDeclined() Group by the declined column
 * @method     ChildPendingUserQuery groupBySource() Group by the source column
 *
 * @method     ChildPendingUserQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildPendingUserQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildPendingUserQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildPendingUserQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildPendingUserQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildPendingUserQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildPendingUser findOne(ConnectionInterface $con = null) Return the first ChildPendingUser matching the query
 * @method     ChildPendingUser findOneOrCreate(ConnectionInterface $con = null) Return the first ChildPendingUser matching the query, or a new ChildPendingUser object populated from the query conditions when no match is found
 *
 * @method     ChildPendingUser findOneById(int $id) Return the first ChildPendingUser filtered by the id column
 * @method     ChildPendingUser findOneBySocialId(string $socialId) Return the first ChildPendingUser filtered by the socialId column
 * @method     ChildPendingUser findOneByFirstName(string $firstName) Return the first ChildPendingUser filtered by the firstName column
 * @method     ChildPendingUser findOneByLastName(string $lastName) Return the first ChildPendingUser filtered by the lastName column
 * @method     ChildPendingUser findOneByEmail(string $email) Return the first ChildPendingUser filtered by the email column
 * @method     ChildPendingUser findOneByApproved(boolean $approved) Return the first ChildPendingUser filtered by the approved column
 * @method     ChildPendingUser findOneByDeclined(boolean $declined) Return the first ChildPendingUser filtered by the declined column
 * @method     ChildPendingUser findOneBySource(string $source) Return the first ChildPendingUser filtered by the source column *

 * @method     ChildPendingUser requirePk($key, ConnectionInterface $con = null) Return the ChildPendingUser by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPendingUser requireOne(ConnectionInterface $con = null) Return the first ChildPendingUser matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPendingUser requireOneById(int $id) Return the first ChildPendingUser filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPendingUser requireOneBySocialId(string $socialId) Return the first ChildPendingUser filtered by the socialId column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPendingUser requireOneByFirstName(string $firstName) Return the first ChildPendingUser filtered by the firstName column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPendingUser requireOneByLastName(string $lastName) Return the first ChildPendingUser filtered by the lastName column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPendingUser requireOneByEmail(string $email) Return the first ChildPendingUser filtered by the email column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPendingUser requireOneByApproved(boolean $approved) Return the first ChildPendingUser filtered by the approved column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPendingUser requireOneByDeclined(boolean $declined) Return the first ChildPendingUser filtered by the declined column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPendingUser requireOneBySource(string $source) Return the first ChildPendingUser filtered by the source column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPendingUser[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildPendingUser objects based on current ModelCriteria
 * @method     ChildPendingUser[]|ObjectCollection findById(int $id) Return ChildPendingUser objects filtered by the id column
 * @method     ChildPendingUser[]|ObjectCollection findBySocialId(string $socialId) Return ChildPendingUser objects filtered by the socialId column
 * @method     ChildPendingUser[]|ObjectCollection findByFirstName(string $firstName) Return ChildPendingUser objects filtered by the firstName column
 * @method     ChildPendingUser[]|ObjectCollection findByLastName(string $lastName) Return ChildPendingUser objects filtered by the lastName column
 * @method     ChildPendingUser[]|ObjectCollection findByEmail(string $email) Return ChildPendingUser objects filtered by the email column
 * @method     ChildPendingUser[]|ObjectCollection findByApproved(boolean $approved) Return ChildPendingUser objects filtered by the approved column
 * @method     ChildPendingUser[]|ObjectCollection findByDeclined(boolean $declined) Return ChildPendingUser objects filtered by the declined column
 * @method     ChildPendingUser[]|ObjectCollection findBySource(string $source) Return ChildPendingUser objects filtered by the source column
 * @method     ChildPendingUser[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class PendingUserQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Base\PendingUserQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\PendingUser', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildPendingUserQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildPendingUserQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildPendingUserQuery) {
            return $criteria;
        }
        $query = new ChildPendingUserQuery();
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
     * @return ChildPendingUser|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(PendingUserTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = PendingUserTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildPendingUser A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, socialId, firstName, lastName, email, approved, declined, source FROM cr_pendingUsers WHERE id = :p0';
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
            /** @var ChildPendingUser $obj */
            $obj = new ChildPendingUser();
            $obj->hydrate($row);
            PendingUserTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildPendingUser|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildPendingUserQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        return $this->addUsingAlias(PendingUserTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildPendingUserQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        return $this->addUsingAlias(PendingUserTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildPendingUserQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(PendingUserTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(PendingUserTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PendingUserTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the socialId column
     *
     * Example usage:
     * <code>
     * $query->filterBySocialId(1234); // WHERE socialId = 1234
     * $query->filterBySocialId(array(12, 34)); // WHERE socialId IN (12, 34)
     * $query->filterBySocialId(array('min' => 12)); // WHERE socialId > 12
     * </code>
     *
     * @param     mixed $socialId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPendingUserQuery The current query, for fluid interface
     */
    public function filterBySocialId($socialId = null, $comparison = null)
    {
        if (is_array($socialId)) {
            $useMinMax = false;
            if (isset($socialId['min'])) {
                $this->addUsingAlias(PendingUserTableMap::COL_SOCIALID, $socialId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($socialId['max'])) {
                $this->addUsingAlias(PendingUserTableMap::COL_SOCIALID, $socialId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PendingUserTableMap::COL_SOCIALID, $socialId, $comparison);
    }

    /**
     * Filter the query on the firstName column
     *
     * Example usage:
     * <code>
     * $query->filterByFirstName('fooValue');   // WHERE firstName = 'fooValue'
     * $query->filterByFirstName('%fooValue%', Criteria::LIKE); // WHERE firstName LIKE '%fooValue%'
     * </code>
     *
     * @param     string $firstName The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPendingUserQuery The current query, for fluid interface
     */
    public function filterByFirstName($firstName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($firstName)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PendingUserTableMap::COL_FIRSTNAME, $firstName, $comparison);
    }

    /**
     * Filter the query on the lastName column
     *
     * Example usage:
     * <code>
     * $query->filterByLastName('fooValue');   // WHERE lastName = 'fooValue'
     * $query->filterByLastName('%fooValue%', Criteria::LIKE); // WHERE lastName LIKE '%fooValue%'
     * </code>
     *
     * @param     string $lastName The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPendingUserQuery The current query, for fluid interface
     */
    public function filterByLastName($lastName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($lastName)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PendingUserTableMap::COL_LASTNAME, $lastName, $comparison);
    }

    /**
     * Filter the query on the email column
     *
     * Example usage:
     * <code>
     * $query->filterByEmail('fooValue');   // WHERE email = 'fooValue'
     * $query->filterByEmail('%fooValue%', Criteria::LIKE); // WHERE email LIKE '%fooValue%'
     * </code>
     *
     * @param     string $email The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPendingUserQuery The current query, for fluid interface
     */
    public function filterByEmail($email = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($email)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PendingUserTableMap::COL_EMAIL, $email, $comparison);
    }

    /**
     * Filter the query on the approved column
     *
     * Example usage:
     * <code>
     * $query->filterByApproved(true); // WHERE approved = true
     * $query->filterByApproved('yes'); // WHERE approved = true
     * </code>
     *
     * @param     boolean|string $approved The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPendingUserQuery The current query, for fluid interface
     */
    public function filterByApproved($approved = null, $comparison = null)
    {
        if (is_string($approved)) {
            $approved = in_array(strtolower($approved), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(PendingUserTableMap::COL_APPROVED, $approved, $comparison);
    }

    /**
     * Filter the query on the declined column
     *
     * Example usage:
     * <code>
     * $query->filterByDeclined(true); // WHERE declined = true
     * $query->filterByDeclined('yes'); // WHERE declined = true
     * </code>
     *
     * @param     boolean|string $declined The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPendingUserQuery The current query, for fluid interface
     */
    public function filterByDeclined($declined = null, $comparison = null)
    {
        if (is_string($declined)) {
            $declined = in_array(strtolower($declined), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(PendingUserTableMap::COL_DECLINED, $declined, $comparison);
    }

    /**
     * Filter the query on the source column
     *
     * Example usage:
     * <code>
     * $query->filterBySource('fooValue');   // WHERE source = 'fooValue'
     * $query->filterBySource('%fooValue%', Criteria::LIKE); // WHERE source LIKE '%fooValue%'
     * </code>
     *
     * @param     string $source The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPendingUserQuery The current query, for fluid interface
     */
    public function filterBySource($source = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($source)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PendingUserTableMap::COL_SOURCE, $source, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   ChildPendingUser $pendingUser Object to remove from the list of results
     *
     * @return $this|ChildPendingUserQuery The current query, for fluid interface
     */
    public function prune($pendingUser = null)
    {
        if ($pendingUser) {
            $this->addUsingAlias(PendingUserTableMap::COL_ID, $pendingUser->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the cr_pendingUsers table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PendingUserTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            PendingUserTableMap::clearInstancePool();
            PendingUserTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(PendingUserTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(PendingUserTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            PendingUserTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            PendingUserTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }
} // PendingUserQuery
