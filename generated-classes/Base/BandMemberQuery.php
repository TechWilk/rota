<?php

namespace Base;

use \BandMember as ChildBandMember;
use \BandMemberQuery as ChildBandMemberQuery;
use \Exception;
use \PDO;
use Map\BandMemberTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'cr_bandMembers' table.
 *
 *
 *
 * @method     ChildBandMemberQuery orderByBandmembersid($order = Criteria::ASC) Order by the bandMembersID column
 * @method     ChildBandMemberQuery orderByBandId($order = Criteria::ASC) Order by the bandID column
 * @method     ChildBandMemberQuery orderBySkillId($order = Criteria::ASC) Order by the skillID column
 *
 * @method     ChildBandMemberQuery groupByBandmembersid() Group by the bandMembersID column
 * @method     ChildBandMemberQuery groupByBandId() Group by the bandID column
 * @method     ChildBandMemberQuery groupBySkillId() Group by the skillID column
 *
 * @method     ChildBandMemberQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildBandMemberQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildBandMemberQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildBandMemberQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildBandMemberQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildBandMemberQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildBandMember findOne(ConnectionInterface $con = null) Return the first ChildBandMember matching the query
 * @method     ChildBandMember findOneOrCreate(ConnectionInterface $con = null) Return the first ChildBandMember matching the query, or a new ChildBandMember object populated from the query conditions when no match is found
 *
 * @method     ChildBandMember findOneByBandmembersid(int $bandMembersID) Return the first ChildBandMember filtered by the bandMembersID column
 * @method     ChildBandMember findOneByBandId(int $bandID) Return the first ChildBandMember filtered by the bandID column
 * @method     ChildBandMember findOneBySkillId(int $skillID) Return the first ChildBandMember filtered by the skillID column *

 * @method     ChildBandMember requirePk($key, ConnectionInterface $con = null) Return the ChildBandMember by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildBandMember requireOne(ConnectionInterface $con = null) Return the first ChildBandMember matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildBandMember requireOneByBandmembersid(int $bandMembersID) Return the first ChildBandMember filtered by the bandMembersID column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildBandMember requireOneByBandId(int $bandID) Return the first ChildBandMember filtered by the bandID column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildBandMember requireOneBySkillId(int $skillID) Return the first ChildBandMember filtered by the skillID column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildBandMember[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildBandMember objects based on current ModelCriteria
 * @method     ChildBandMember[]|ObjectCollection findByBandmembersid(int $bandMembersID) Return ChildBandMember objects filtered by the bandMembersID column
 * @method     ChildBandMember[]|ObjectCollection findByBandId(int $bandID) Return ChildBandMember objects filtered by the bandID column
 * @method     ChildBandMember[]|ObjectCollection findBySkillId(int $skillID) Return ChildBandMember objects filtered by the skillID column
 * @method     ChildBandMember[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class BandMemberQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Base\BandMemberQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\BandMember', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildBandMemberQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildBandMemberQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildBandMemberQuery) {
            return $criteria;
        }
        $query = new ChildBandMemberQuery();
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
     * @return ChildBandMember|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(BandMemberTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = BandMemberTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildBandMember A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT bandMembersID, bandID, skillID FROM cr_bandMembers WHERE bandMembersID = :p0';
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
            /** @var ChildBandMember $obj */
            $obj = new ChildBandMember();
            $obj->hydrate($row);
            BandMemberTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildBandMember|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildBandMemberQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        return $this->addUsingAlias(BandMemberTableMap::COL_BANDMEMBERSID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildBandMemberQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        return $this->addUsingAlias(BandMemberTableMap::COL_BANDMEMBERSID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the bandMembersID column
     *
     * Example usage:
     * <code>
     * $query->filterByBandmembersid(1234); // WHERE bandMembersID = 1234
     * $query->filterByBandmembersid(array(12, 34)); // WHERE bandMembersID IN (12, 34)
     * $query->filterByBandmembersid(array('min' => 12)); // WHERE bandMembersID > 12
     * </code>
     *
     * @param     mixed $bandmembersid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildBandMemberQuery The current query, for fluid interface
     */
    public function filterByBandmembersid($bandmembersid = null, $comparison = null)
    {
        if (is_array($bandmembersid)) {
            $useMinMax = false;
            if (isset($bandmembersid['min'])) {
                $this->addUsingAlias(BandMemberTableMap::COL_BANDMEMBERSID, $bandmembersid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($bandmembersid['max'])) {
                $this->addUsingAlias(BandMemberTableMap::COL_BANDMEMBERSID, $bandmembersid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(BandMemberTableMap::COL_BANDMEMBERSID, $bandmembersid, $comparison);
    }

    /**
     * Filter the query on the bandID column
     *
     * Example usage:
     * <code>
     * $query->filterByBandId(1234); // WHERE bandID = 1234
     * $query->filterByBandId(array(12, 34)); // WHERE bandID IN (12, 34)
     * $query->filterByBandId(array('min' => 12)); // WHERE bandID > 12
     * </code>
     *
     * @param     mixed $bandId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildBandMemberQuery The current query, for fluid interface
     */
    public function filterByBandId($bandId = null, $comparison = null)
    {
        if (is_array($bandId)) {
            $useMinMax = false;
            if (isset($bandId['min'])) {
                $this->addUsingAlias(BandMemberTableMap::COL_BANDID, $bandId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($bandId['max'])) {
                $this->addUsingAlias(BandMemberTableMap::COL_BANDID, $bandId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(BandMemberTableMap::COL_BANDID, $bandId, $comparison);
    }

    /**
     * Filter the query on the skillID column
     *
     * Example usage:
     * <code>
     * $query->filterBySkillId(1234); // WHERE skillID = 1234
     * $query->filterBySkillId(array(12, 34)); // WHERE skillID IN (12, 34)
     * $query->filterBySkillId(array('min' => 12)); // WHERE skillID > 12
     * </code>
     *
     * @param     mixed $skillId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildBandMemberQuery The current query, for fluid interface
     */
    public function filterBySkillId($skillId = null, $comparison = null)
    {
        if (is_array($skillId)) {
            $useMinMax = false;
            if (isset($skillId['min'])) {
                $this->addUsingAlias(BandMemberTableMap::COL_SKILLID, $skillId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($skillId['max'])) {
                $this->addUsingAlias(BandMemberTableMap::COL_SKILLID, $skillId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(BandMemberTableMap::COL_SKILLID, $skillId, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   ChildBandMember $bandMember Object to remove from the list of results
     *
     * @return $this|ChildBandMemberQuery The current query, for fluid interface
     */
    public function prune($bandMember = null)
    {
        if ($bandMember) {
            $this->addUsingAlias(BandMemberTableMap::COL_BANDMEMBERSID, $bandMember->getBandmembersid(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the cr_bandMembers table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(BandMemberTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            BandMemberTableMap::clearInstancePool();
            BandMemberTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(BandMemberTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(BandMemberTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            BandMemberTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            BandMemberTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }
} // BandMemberQuery
