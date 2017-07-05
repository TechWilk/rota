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
use TechWilk\Rota\SocialAuth as ChildSocialAuth;
use TechWilk\Rota\SocialAuthQuery as ChildSocialAuthQuery;
use TechWilk\Rota\Map\SocialAuthTableMap;

/**
 * Base class that represents a query for the 'cr_socialAuth' table.
 *
 *
 *
 * @method     ChildSocialAuthQuery orderByUserId($order = Criteria::ASC) Order by the userId column
 * @method     ChildSocialAuthQuery orderByPlatform($order = Criteria::ASC) Order by the platform column
 * @method     ChildSocialAuthQuery orderBySocialId($order = Criteria::ASC) Order by the socialId column
 * @method     ChildSocialAuthQuery orderByMeta($order = Criteria::ASC) Order by the meta column
 * @method     ChildSocialAuthQuery orderByRevoked($order = Criteria::ASC) Order by the revoked column
 *
 * @method     ChildSocialAuthQuery groupByUserId() Group by the userId column
 * @method     ChildSocialAuthQuery groupByPlatform() Group by the platform column
 * @method     ChildSocialAuthQuery groupBySocialId() Group by the socialId column
 * @method     ChildSocialAuthQuery groupByMeta() Group by the meta column
 * @method     ChildSocialAuthQuery groupByRevoked() Group by the revoked column
 *
 * @method     ChildSocialAuthQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildSocialAuthQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildSocialAuthQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildSocialAuthQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildSocialAuthQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildSocialAuthQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildSocialAuthQuery leftJoinUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the User relation
 * @method     ChildSocialAuthQuery rightJoinUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the User relation
 * @method     ChildSocialAuthQuery innerJoinUser($relationAlias = null) Adds a INNER JOIN clause to the query using the User relation
 *
 * @method     ChildSocialAuthQuery joinWithUser($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the User relation
 *
 * @method     ChildSocialAuthQuery leftJoinWithUser() Adds a LEFT JOIN clause and with to the query using the User relation
 * @method     ChildSocialAuthQuery rightJoinWithUser() Adds a RIGHT JOIN clause and with to the query using the User relation
 * @method     ChildSocialAuthQuery innerJoinWithUser() Adds a INNER JOIN clause and with to the query using the User relation
 *
 * @method     \TechWilk\Rota\UserQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildSocialAuth findOne(ConnectionInterface $con = null) Return the first ChildSocialAuth matching the query
 * @method     ChildSocialAuth findOneOrCreate(ConnectionInterface $con = null) Return the first ChildSocialAuth matching the query, or a new ChildSocialAuth object populated from the query conditions when no match is found
 *
 * @method     ChildSocialAuth findOneByUserId(int $userId) Return the first ChildSocialAuth filtered by the userId column
 * @method     ChildSocialAuth findOneByPlatform(string $platform) Return the first ChildSocialAuth filtered by the platform column
 * @method     ChildSocialAuth findOneBySocialId(string $socialId) Return the first ChildSocialAuth filtered by the socialId column
 * @method     ChildSocialAuth findOneByMeta(string $meta) Return the first ChildSocialAuth filtered by the meta column
 * @method     ChildSocialAuth findOneByRevoked(boolean $revoked) Return the first ChildSocialAuth filtered by the revoked column *

 * @method     ChildSocialAuth requirePk($key, ConnectionInterface $con = null) Return the ChildSocialAuth by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSocialAuth requireOne(ConnectionInterface $con = null) Return the first ChildSocialAuth matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildSocialAuth requireOneByUserId(int $userId) Return the first ChildSocialAuth filtered by the userId column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSocialAuth requireOneByPlatform(string $platform) Return the first ChildSocialAuth filtered by the platform column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSocialAuth requireOneBySocialId(string $socialId) Return the first ChildSocialAuth filtered by the socialId column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSocialAuth requireOneByMeta(string $meta) Return the first ChildSocialAuth filtered by the meta column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSocialAuth requireOneByRevoked(boolean $revoked) Return the first ChildSocialAuth filtered by the revoked column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildSocialAuth[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildSocialAuth objects based on current ModelCriteria
 * @method     ChildSocialAuth[]|ObjectCollection findByUserId(int $userId) Return ChildSocialAuth objects filtered by the userId column
 * @method     ChildSocialAuth[]|ObjectCollection findByPlatform(string $platform) Return ChildSocialAuth objects filtered by the platform column
 * @method     ChildSocialAuth[]|ObjectCollection findBySocialId(string $socialId) Return ChildSocialAuth objects filtered by the socialId column
 * @method     ChildSocialAuth[]|ObjectCollection findByMeta(string $meta) Return ChildSocialAuth objects filtered by the meta column
 * @method     ChildSocialAuth[]|ObjectCollection findByRevoked(boolean $revoked) Return ChildSocialAuth objects filtered by the revoked column
 * @method     ChildSocialAuth[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class SocialAuthQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \TechWilk\Rota\Base\SocialAuthQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\TechWilk\\Rota\\SocialAuth', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildSocialAuthQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildSocialAuthQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildSocialAuthQuery) {
            return $criteria;
        }
        $query = new ChildSocialAuthQuery();
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
     * $obj = $c->findPk(array(12, 34, 56), $con);
     * </code>
     *
     * @param array[$userId, $platform, $socialId] $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildSocialAuth|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(SocialAuthTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = SocialAuthTableMap::getInstanceFromPool(serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1]), (null === $key[2] || is_scalar($key[2]) || is_callable([$key[2], '__toString']) ? (string) $key[2] : $key[2])]))))) {
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
     * @return ChildSocialAuth A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT userId, platform, socialId, meta, revoked FROM cr_socialAuth WHERE userId = :p0 AND platform = :p1 AND socialId = :p2';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key[0], PDO::PARAM_INT);
            $stmt->bindValue(':p1', $key[1], PDO::PARAM_STR);
            $stmt->bindValue(':p2', $key[2], PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildSocialAuth $obj */
            $obj = new ChildSocialAuth();
            $obj->hydrate($row);
            SocialAuthTableMap::addInstanceToPool($obj, serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1]), (null === $key[2] || is_scalar($key[2]) || is_callable([$key[2], '__toString']) ? (string) $key[2] : $key[2])]));
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
     * @return ChildSocialAuth|array|mixed the result, formatted by the current formatter
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
     * $objs = $c->findPks(array(array(12, 56), array(832, 123), array(123, 456)), $con);
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
     * @return $this|ChildSocialAuthQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(SocialAuthTableMap::COL_USERID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(SocialAuthTableMap::COL_PLATFORM, $key[1], Criteria::EQUAL);
        $this->addUsingAlias(SocialAuthTableMap::COL_SOCIALID, $key[2], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildSocialAuthQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(SocialAuthTableMap::COL_USERID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(SocialAuthTableMap::COL_PLATFORM, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $cton2 = $this->getNewCriterion(SocialAuthTableMap::COL_SOCIALID, $key[2], Criteria::EQUAL);
            $cton0->addAnd($cton2);
            $this->addOr($cton0);
        }

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
     * @param     mixed $userId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSocialAuthQuery The current query, for fluid interface
     */
    public function filterByUserId($userId = null, $comparison = null)
    {
        if (is_array($userId)) {
            $useMinMax = false;
            if (isset($userId['min'])) {
                $this->addUsingAlias(SocialAuthTableMap::COL_USERID, $userId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($userId['max'])) {
                $this->addUsingAlias(SocialAuthTableMap::COL_USERID, $userId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SocialAuthTableMap::COL_USERID, $userId, $comparison);
    }

    /**
     * Filter the query on the platform column
     *
     * Example usage:
     * <code>
     * $query->filterByPlatform('fooValue');   // WHERE platform = 'fooValue'
     * $query->filterByPlatform('%fooValue%', Criteria::LIKE); // WHERE platform LIKE '%fooValue%'
     * </code>
     *
     * @param     string $platform The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSocialAuthQuery The current query, for fluid interface
     */
    public function filterByPlatform($platform = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($platform)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SocialAuthTableMap::COL_PLATFORM, $platform, $comparison);
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
     * @return $this|ChildSocialAuthQuery The current query, for fluid interface
     */
    public function filterBySocialId($socialId = null, $comparison = null)
    {
        if (is_array($socialId)) {
            $useMinMax = false;
            if (isset($socialId['min'])) {
                $this->addUsingAlias(SocialAuthTableMap::COL_SOCIALID, $socialId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($socialId['max'])) {
                $this->addUsingAlias(SocialAuthTableMap::COL_SOCIALID, $socialId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SocialAuthTableMap::COL_SOCIALID, $socialId, $comparison);
    }

    /**
     * Filter the query on the meta column
     *
     * Example usage:
     * <code>
     * $query->filterByMeta('fooValue');   // WHERE meta = 'fooValue'
     * $query->filterByMeta('%fooValue%', Criteria::LIKE); // WHERE meta LIKE '%fooValue%'
     * </code>
     *
     * @param     string $meta The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSocialAuthQuery The current query, for fluid interface
     */
    public function filterByMeta($meta = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($meta)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SocialAuthTableMap::COL_META, $meta, $comparison);
    }

    /**
     * Filter the query on the revoked column
     *
     * Example usage:
     * <code>
     * $query->filterByRevoked(true); // WHERE revoked = true
     * $query->filterByRevoked('yes'); // WHERE revoked = true
     * </code>
     *
     * @param     boolean|string $revoked The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSocialAuthQuery The current query, for fluid interface
     */
    public function filterByRevoked($revoked = null, $comparison = null)
    {
        if (is_string($revoked)) {
            $revoked = in_array(strtolower($revoked), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(SocialAuthTableMap::COL_REVOKED, $revoked, $comparison);
    }

    /**
     * Filter the query by a related \TechWilk\Rota\User object
     *
     * @param \TechWilk\Rota\User|ObjectCollection $user The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildSocialAuthQuery The current query, for fluid interface
     */
    public function filterByUser($user, $comparison = null)
    {
        if ($user instanceof \TechWilk\Rota\User) {
            return $this
                ->addUsingAlias(SocialAuthTableMap::COL_USERID, $user->getId(), $comparison);
        } elseif ($user instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(SocialAuthTableMap::COL_USERID, $user->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return $this|ChildSocialAuthQuery The current query, for fluid interface
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
     * @param   ChildSocialAuth $socialAuth Object to remove from the list of results
     *
     * @return $this|ChildSocialAuthQuery The current query, for fluid interface
     */
    public function prune($socialAuth = null)
    {
        if ($socialAuth) {
            $this->addCond('pruneCond0', $this->getAliasedColName(SocialAuthTableMap::COL_USERID), $socialAuth->getUserId(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(SocialAuthTableMap::COL_PLATFORM), $socialAuth->getPlatform(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond2', $this->getAliasedColName(SocialAuthTableMap::COL_SOCIALID), $socialAuth->getSocialId(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1', 'pruneCond2'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

    /**
     * Deletes all rows from the cr_socialAuth table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(SocialAuthTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            SocialAuthTableMap::clearInstancePool();
            SocialAuthTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(SocialAuthTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(SocialAuthTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            SocialAuthTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            SocialAuthTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }
} // SocialAuthQuery
