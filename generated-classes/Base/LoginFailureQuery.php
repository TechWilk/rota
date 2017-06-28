<?php

namespace Base;

use \LoginFailure as ChildLoginFailure;
use \LoginFailureQuery as ChildLoginFailureQuery;
use \Exception;
use Map\LoginFailureTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\LogicException;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'cr_loginFailures' table.
 *
 *
 *
 * @method     ChildLoginFailureQuery orderByUsername($order = Criteria::ASC) Order by the username column
 * @method     ChildLoginFailureQuery orderByipAddress($order = Criteria::ASC) Order by the ipAddress column
 * @method     ChildLoginFailureQuery orderByTimestamp($order = Criteria::ASC) Order by the timestamp column
 *
 * @method     ChildLoginFailureQuery groupByUsername() Group by the username column
 * @method     ChildLoginFailureQuery groupByipAddress() Group by the ipAddress column
 * @method     ChildLoginFailureQuery groupByTimestamp() Group by the timestamp column
 *
 * @method     ChildLoginFailureQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildLoginFailureQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildLoginFailureQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildLoginFailureQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildLoginFailureQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildLoginFailureQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildLoginFailure findOne(ConnectionInterface $con = null) Return the first ChildLoginFailure matching the query
 * @method     ChildLoginFailure findOneOrCreate(ConnectionInterface $con = null) Return the first ChildLoginFailure matching the query, or a new ChildLoginFailure object populated from the query conditions when no match is found
 *
 * @method     ChildLoginFailure findOneByUsername(string $username) Return the first ChildLoginFailure filtered by the username column
 * @method     ChildLoginFailure findOneByipAddress(string $ipAddress) Return the first ChildLoginFailure filtered by the ipAddress column
 * @method     ChildLoginFailure findOneByTimestamp(string $timestamp) Return the first ChildLoginFailure filtered by the timestamp column *

 * @method     ChildLoginFailure requirePk($key, ConnectionInterface $con = null) Return the ChildLoginFailure by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLoginFailure requireOne(ConnectionInterface $con = null) Return the first ChildLoginFailure matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildLoginFailure requireOneByUsername(string $username) Return the first ChildLoginFailure filtered by the username column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLoginFailure requireOneByipAddress(string $ipAddress) Return the first ChildLoginFailure filtered by the ipAddress column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLoginFailure requireOneByTimestamp(string $timestamp) Return the first ChildLoginFailure filtered by the timestamp column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildLoginFailure[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildLoginFailure objects based on current ModelCriteria
 * @method     ChildLoginFailure[]|ObjectCollection findByUsername(string $username) Return ChildLoginFailure objects filtered by the username column
 * @method     ChildLoginFailure[]|ObjectCollection findByipAddress(string $ipAddress) Return ChildLoginFailure objects filtered by the ipAddress column
 * @method     ChildLoginFailure[]|ObjectCollection findByTimestamp(string $timestamp) Return ChildLoginFailure objects filtered by the timestamp column
 * @method     ChildLoginFailure[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class LoginFailureQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Base\LoginFailureQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\LoginFailure', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildLoginFailureQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildLoginFailureQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildLoginFailureQuery) {
            return $criteria;
        }
        $query = new ChildLoginFailureQuery();
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
     * @return ChildLoginFailure|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        throw new LogicException('The LoginFailure object has no primary key');
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(array(12, 56), array(832, 123), array(123, 456)), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return ObjectCollection|array|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, ConnectionInterface $con = null)
    {
        throw new LogicException('The LoginFailure object has no primary key');
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return $this|ChildLoginFailureQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        throw new LogicException('The LoginFailure object has no primary key');
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildLoginFailureQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        throw new LogicException('The LoginFailure object has no primary key');
    }

    /**
     * Filter the query on the username column
     *
     * Example usage:
     * <code>
     * $query->filterByUsername('fooValue');   // WHERE username = 'fooValue'
     * $query->filterByUsername('%fooValue%', Criteria::LIKE); // WHERE username LIKE '%fooValue%'
     * </code>
     *
     * @param     string $username The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLoginFailureQuery The current query, for fluid interface
     */
    public function filterByUsername($username = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($username)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LoginFailureTableMap::COL_USERNAME, $username, $comparison);
    }

    /**
     * Filter the query on the ipAddress column
     *
     * Example usage:
     * <code>
     * $query->filterByipAddress('fooValue');   // WHERE ipAddress = 'fooValue'
     * $query->filterByipAddress('%fooValue%', Criteria::LIKE); // WHERE ipAddress LIKE '%fooValue%'
     * </code>
     *
     * @param     string $ipAddress The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLoginFailureQuery The current query, for fluid interface
     */
    public function filterByipAddress($ipAddress = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($ipAddress)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LoginFailureTableMap::COL_IPADDRESS, $ipAddress, $comparison);
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
     * @return $this|ChildLoginFailureQuery The current query, for fluid interface
     */
    public function filterByTimestamp($timestamp = null, $comparison = null)
    {
        if (is_array($timestamp)) {
            $useMinMax = false;
            if (isset($timestamp['min'])) {
                $this->addUsingAlias(LoginFailureTableMap::COL_TIMESTAMP, $timestamp['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($timestamp['max'])) {
                $this->addUsingAlias(LoginFailureTableMap::COL_TIMESTAMP, $timestamp['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LoginFailureTableMap::COL_TIMESTAMP, $timestamp, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   ChildLoginFailure $loginFailure Object to remove from the list of results
     *
     * @return $this|ChildLoginFailureQuery The current query, for fluid interface
     */
    public function prune($loginFailure = null)
    {
        if ($loginFailure) {
            throw new LogicException('LoginFailure object has no primary key');
        }

        return $this;
    }

    /**
     * Deletes all rows from the cr_loginFailures table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(LoginFailureTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            LoginFailureTableMap::clearInstancePool();
            LoginFailureTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(LoginFailureTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(LoginFailureTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            LoginFailureTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            LoginFailureTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }
} // LoginFailureQuery
