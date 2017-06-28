<?php

namespace Base;

use \Email as ChildEmail;
use \EmailQuery as ChildEmailQuery;
use \Exception;
use \PDO;
use Map\EmailTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'cr_emails' table.
 *
 *
 *
 * @method     ChildEmailQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildEmailQuery orderByEmailTo($order = Criteria::ASC) Order by the emailTo column
 * @method     ChildEmailQuery orderByEmailBcc($order = Criteria::ASC) Order by the emailBcc column
 * @method     ChildEmailQuery orderByEmailFrom($order = Criteria::ASC) Order by the emailFrom column
 * @method     ChildEmailQuery orderBySubject($order = Criteria::ASC) Order by the subject column
 * @method     ChildEmailQuery orderByMessage($order = Criteria::ASC) Order by the message column
 * @method     ChildEmailQuery orderByError($order = Criteria::ASC) Order by the error column
 *
 * @method     ChildEmailQuery groupById() Group by the id column
 * @method     ChildEmailQuery groupByEmailTo() Group by the emailTo column
 * @method     ChildEmailQuery groupByEmailBcc() Group by the emailBcc column
 * @method     ChildEmailQuery groupByEmailFrom() Group by the emailFrom column
 * @method     ChildEmailQuery groupBySubject() Group by the subject column
 * @method     ChildEmailQuery groupByMessage() Group by the message column
 * @method     ChildEmailQuery groupByError() Group by the error column
 *
 * @method     ChildEmailQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildEmailQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildEmailQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildEmailQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildEmailQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildEmailQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildEmail findOne(ConnectionInterface $con = null) Return the first ChildEmail matching the query
 * @method     ChildEmail findOneOrCreate(ConnectionInterface $con = null) Return the first ChildEmail matching the query, or a new ChildEmail object populated from the query conditions when no match is found
 *
 * @method     ChildEmail findOneById(int $id) Return the first ChildEmail filtered by the id column
 * @method     ChildEmail findOneByEmailTo(string $emailTo) Return the first ChildEmail filtered by the emailTo column
 * @method     ChildEmail findOneByEmailBcc(string $emailBcc) Return the first ChildEmail filtered by the emailBcc column
 * @method     ChildEmail findOneByEmailFrom(string $emailFrom) Return the first ChildEmail filtered by the emailFrom column
 * @method     ChildEmail findOneBySubject(string $subject) Return the first ChildEmail filtered by the subject column
 * @method     ChildEmail findOneByMessage(string $message) Return the first ChildEmail filtered by the message column
 * @method     ChildEmail findOneByError(string $error) Return the first ChildEmail filtered by the error column *

 * @method     ChildEmail requirePk($key, ConnectionInterface $con = null) Return the ChildEmail by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEmail requireOne(ConnectionInterface $con = null) Return the first ChildEmail matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildEmail requireOneById(int $id) Return the first ChildEmail filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEmail requireOneByEmailTo(string $emailTo) Return the first ChildEmail filtered by the emailTo column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEmail requireOneByEmailBcc(string $emailBcc) Return the first ChildEmail filtered by the emailBcc column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEmail requireOneByEmailFrom(string $emailFrom) Return the first ChildEmail filtered by the emailFrom column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEmail requireOneBySubject(string $subject) Return the first ChildEmail filtered by the subject column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEmail requireOneByMessage(string $message) Return the first ChildEmail filtered by the message column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEmail requireOneByError(string $error) Return the first ChildEmail filtered by the error column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildEmail[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildEmail objects based on current ModelCriteria
 * @method     ChildEmail[]|ObjectCollection findById(int $id) Return ChildEmail objects filtered by the id column
 * @method     ChildEmail[]|ObjectCollection findByEmailTo(string $emailTo) Return ChildEmail objects filtered by the emailTo column
 * @method     ChildEmail[]|ObjectCollection findByEmailBcc(string $emailBcc) Return ChildEmail objects filtered by the emailBcc column
 * @method     ChildEmail[]|ObjectCollection findByEmailFrom(string $emailFrom) Return ChildEmail objects filtered by the emailFrom column
 * @method     ChildEmail[]|ObjectCollection findBySubject(string $subject) Return ChildEmail objects filtered by the subject column
 * @method     ChildEmail[]|ObjectCollection findByMessage(string $message) Return ChildEmail objects filtered by the message column
 * @method     ChildEmail[]|ObjectCollection findByError(string $error) Return ChildEmail objects filtered by the error column
 * @method     ChildEmail[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class EmailQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Base\EmailQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Email', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildEmailQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildEmailQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildEmailQuery) {
            return $criteria;
        }
        $query = new ChildEmailQuery();
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
     * @return ChildEmail|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(EmailTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = EmailTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildEmail A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, emailTo, emailBcc, emailFrom, subject, message, error FROM cr_emails WHERE id = :p0';
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
            /** @var ChildEmail $obj */
            $obj = new ChildEmail();
            $obj->hydrate($row);
            EmailTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildEmail|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildEmailQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        return $this->addUsingAlias(EmailTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildEmailQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        return $this->addUsingAlias(EmailTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildEmailQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(EmailTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(EmailTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EmailTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the emailTo column
     *
     * Example usage:
     * <code>
     * $query->filterByEmailTo('fooValue');   // WHERE emailTo = 'fooValue'
     * $query->filterByEmailTo('%fooValue%', Criteria::LIKE); // WHERE emailTo LIKE '%fooValue%'
     * </code>
     *
     * @param     string $emailTo The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildEmailQuery The current query, for fluid interface
     */
    public function filterByEmailTo($emailTo = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($emailTo)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EmailTableMap::COL_EMAILTO, $emailTo, $comparison);
    }

    /**
     * Filter the query on the emailBcc column
     *
     * Example usage:
     * <code>
     * $query->filterByEmailBcc('fooValue');   // WHERE emailBcc = 'fooValue'
     * $query->filterByEmailBcc('%fooValue%', Criteria::LIKE); // WHERE emailBcc LIKE '%fooValue%'
     * </code>
     *
     * @param     string $emailBcc The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildEmailQuery The current query, for fluid interface
     */
    public function filterByEmailBcc($emailBcc = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($emailBcc)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EmailTableMap::COL_EMAILBCC, $emailBcc, $comparison);
    }

    /**
     * Filter the query on the emailFrom column
     *
     * Example usage:
     * <code>
     * $query->filterByEmailFrom('fooValue');   // WHERE emailFrom = 'fooValue'
     * $query->filterByEmailFrom('%fooValue%', Criteria::LIKE); // WHERE emailFrom LIKE '%fooValue%'
     * </code>
     *
     * @param     string $emailFrom The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildEmailQuery The current query, for fluid interface
     */
    public function filterByEmailFrom($emailFrom = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($emailFrom)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EmailTableMap::COL_EMAILFROM, $emailFrom, $comparison);
    }

    /**
     * Filter the query on the subject column
     *
     * Example usage:
     * <code>
     * $query->filterBySubject('fooValue');   // WHERE subject = 'fooValue'
     * $query->filterBySubject('%fooValue%', Criteria::LIKE); // WHERE subject LIKE '%fooValue%'
     * </code>
     *
     * @param     string $subject The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildEmailQuery The current query, for fluid interface
     */
    public function filterBySubject($subject = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($subject)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EmailTableMap::COL_SUBJECT, $subject, $comparison);
    }

    /**
     * Filter the query on the message column
     *
     * Example usage:
     * <code>
     * $query->filterByMessage('fooValue');   // WHERE message = 'fooValue'
     * $query->filterByMessage('%fooValue%', Criteria::LIKE); // WHERE message LIKE '%fooValue%'
     * </code>
     *
     * @param     string $message The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildEmailQuery The current query, for fluid interface
     */
    public function filterByMessage($message = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($message)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EmailTableMap::COL_MESSAGE, $message, $comparison);
    }

    /**
     * Filter the query on the error column
     *
     * Example usage:
     * <code>
     * $query->filterByError('fooValue');   // WHERE error = 'fooValue'
     * $query->filterByError('%fooValue%', Criteria::LIKE); // WHERE error LIKE '%fooValue%'
     * </code>
     *
     * @param     string $error The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildEmailQuery The current query, for fluid interface
     */
    public function filterByError($error = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($error)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EmailTableMap::COL_ERROR, $error, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   ChildEmail $email Object to remove from the list of results
     *
     * @return $this|ChildEmailQuery The current query, for fluid interface
     */
    public function prune($email = null)
    {
        if ($email) {
            $this->addUsingAlias(EmailTableMap::COL_ID, $email->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the cr_emails table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(EmailTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            EmailTableMap::clearInstancePool();
            EmailTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(EmailTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(EmailTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            EmailTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            EmailTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }
} // EmailQuery
