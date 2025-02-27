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
use TechWilk\Rota\Event as ChildEvent;
use TechWilk\Rota\EventQuery as ChildEventQuery;
use TechWilk\Rota\Map\EventTableMap;

/**
 * Base class that represents a query for the `events` table.
 *
 * @method     ChildEventQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildEventQuery orderByDate($order = Criteria::ASC) Order by the date column
 * @method     ChildEventQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method     ChildEventQuery orderByCreatedBy($order = Criteria::ASC) Order by the createdBy column
 * @method     ChildEventQuery orderByRehearsalDate($order = Criteria::ASC) Order by the rehearsalDate column
 * @method     ChildEventQuery orderByEventTypeId($order = Criteria::ASC) Order by the type column
 * @method     ChildEventQuery orderByEventSubTypeId($order = Criteria::ASC) Order by the subType column
 * @method     ChildEventQuery orderByLocationId($order = Criteria::ASC) Order by the location column
 * @method     ChildEventQuery orderByNotified($order = Criteria::ASC) Order by the notified column
 * @method     ChildEventQuery orderByRehearsal($order = Criteria::ASC) Order by the rehearsal column
 * @method     ChildEventQuery orderByRemoved($order = Criteria::ASC) Order by the removed column
 * @method     ChildEventQuery orderByEventGroupId($order = Criteria::ASC) Order by the eventGroup column
 * @method     ChildEventQuery orderBySermonTitle($order = Criteria::ASC) Order by the sermonTitle column
 * @method     ChildEventQuery orderByBibleVerse($order = Criteria::ASC) Order by the bibleVerse column
 * @method     ChildEventQuery orderByCreated($order = Criteria::ASC) Order by the created column
 * @method     ChildEventQuery orderByUpdated($order = Criteria::ASC) Order by the updated column
 *
 * @method     ChildEventQuery groupById() Group by the id column
 * @method     ChildEventQuery groupByDate() Group by the date column
 * @method     ChildEventQuery groupByName() Group by the name column
 * @method     ChildEventQuery groupByCreatedBy() Group by the createdBy column
 * @method     ChildEventQuery groupByRehearsalDate() Group by the rehearsalDate column
 * @method     ChildEventQuery groupByEventTypeId() Group by the type column
 * @method     ChildEventQuery groupByEventSubTypeId() Group by the subType column
 * @method     ChildEventQuery groupByLocationId() Group by the location column
 * @method     ChildEventQuery groupByNotified() Group by the notified column
 * @method     ChildEventQuery groupByRehearsal() Group by the rehearsal column
 * @method     ChildEventQuery groupByRemoved() Group by the removed column
 * @method     ChildEventQuery groupByEventGroupId() Group by the eventGroup column
 * @method     ChildEventQuery groupBySermonTitle() Group by the sermonTitle column
 * @method     ChildEventQuery groupByBibleVerse() Group by the bibleVerse column
 * @method     ChildEventQuery groupByCreated() Group by the created column
 * @method     ChildEventQuery groupByUpdated() Group by the updated column
 *
 * @method     ChildEventQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildEventQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildEventQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildEventQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildEventQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildEventQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildEventQuery leftJoinUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the User relation
 * @method     ChildEventQuery rightJoinUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the User relation
 * @method     ChildEventQuery innerJoinUser($relationAlias = null) Adds a INNER JOIN clause to the query using the User relation
 *
 * @method     ChildEventQuery joinWithUser($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the User relation
 *
 * @method     ChildEventQuery leftJoinWithUser() Adds a LEFT JOIN clause and with to the query using the User relation
 * @method     ChildEventQuery rightJoinWithUser() Adds a RIGHT JOIN clause and with to the query using the User relation
 * @method     ChildEventQuery innerJoinWithUser() Adds a INNER JOIN clause and with to the query using the User relation
 *
 * @method     ChildEventQuery leftJoinEventType($relationAlias = null) Adds a LEFT JOIN clause to the query using the EventType relation
 * @method     ChildEventQuery rightJoinEventType($relationAlias = null) Adds a RIGHT JOIN clause to the query using the EventType relation
 * @method     ChildEventQuery innerJoinEventType($relationAlias = null) Adds a INNER JOIN clause to the query using the EventType relation
 *
 * @method     ChildEventQuery joinWithEventType($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the EventType relation
 *
 * @method     ChildEventQuery leftJoinWithEventType() Adds a LEFT JOIN clause and with to the query using the EventType relation
 * @method     ChildEventQuery rightJoinWithEventType() Adds a RIGHT JOIN clause and with to the query using the EventType relation
 * @method     ChildEventQuery innerJoinWithEventType() Adds a INNER JOIN clause and with to the query using the EventType relation
 *
 * @method     ChildEventQuery leftJoinEventSubType($relationAlias = null) Adds a LEFT JOIN clause to the query using the EventSubType relation
 * @method     ChildEventQuery rightJoinEventSubType($relationAlias = null) Adds a RIGHT JOIN clause to the query using the EventSubType relation
 * @method     ChildEventQuery innerJoinEventSubType($relationAlias = null) Adds a INNER JOIN clause to the query using the EventSubType relation
 *
 * @method     ChildEventQuery joinWithEventSubType($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the EventSubType relation
 *
 * @method     ChildEventQuery leftJoinWithEventSubType() Adds a LEFT JOIN clause and with to the query using the EventSubType relation
 * @method     ChildEventQuery rightJoinWithEventSubType() Adds a RIGHT JOIN clause and with to the query using the EventSubType relation
 * @method     ChildEventQuery innerJoinWithEventSubType() Adds a INNER JOIN clause and with to the query using the EventSubType relation
 *
 * @method     ChildEventQuery leftJoinLocation($relationAlias = null) Adds a LEFT JOIN clause to the query using the Location relation
 * @method     ChildEventQuery rightJoinLocation($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Location relation
 * @method     ChildEventQuery innerJoinLocation($relationAlias = null) Adds a INNER JOIN clause to the query using the Location relation
 *
 * @method     ChildEventQuery joinWithLocation($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Location relation
 *
 * @method     ChildEventQuery leftJoinWithLocation() Adds a LEFT JOIN clause and with to the query using the Location relation
 * @method     ChildEventQuery rightJoinWithLocation() Adds a RIGHT JOIN clause and with to the query using the Location relation
 * @method     ChildEventQuery innerJoinWithLocation() Adds a INNER JOIN clause and with to the query using the Location relation
 *
 * @method     ChildEventQuery leftJoinEventGroup($relationAlias = null) Adds a LEFT JOIN clause to the query using the EventGroup relation
 * @method     ChildEventQuery rightJoinEventGroup($relationAlias = null) Adds a RIGHT JOIN clause to the query using the EventGroup relation
 * @method     ChildEventQuery innerJoinEventGroup($relationAlias = null) Adds a INNER JOIN clause to the query using the EventGroup relation
 *
 * @method     ChildEventQuery joinWithEventGroup($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the EventGroup relation
 *
 * @method     ChildEventQuery leftJoinWithEventGroup() Adds a LEFT JOIN clause and with to the query using the EventGroup relation
 * @method     ChildEventQuery rightJoinWithEventGroup() Adds a RIGHT JOIN clause and with to the query using the EventGroup relation
 * @method     ChildEventQuery innerJoinWithEventGroup() Adds a INNER JOIN clause and with to the query using the EventGroup relation
 *
 * @method     ChildEventQuery leftJoinComment($relationAlias = null) Adds a LEFT JOIN clause to the query using the Comment relation
 * @method     ChildEventQuery rightJoinComment($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Comment relation
 * @method     ChildEventQuery innerJoinComment($relationAlias = null) Adds a INNER JOIN clause to the query using the Comment relation
 *
 * @method     ChildEventQuery joinWithComment($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Comment relation
 *
 * @method     ChildEventQuery leftJoinWithComment() Adds a LEFT JOIN clause and with to the query using the Comment relation
 * @method     ChildEventQuery rightJoinWithComment() Adds a RIGHT JOIN clause and with to the query using the Comment relation
 * @method     ChildEventQuery innerJoinWithComment() Adds a INNER JOIN clause and with to the query using the Comment relation
 *
 * @method     ChildEventQuery leftJoinEventPerson($relationAlias = null) Adds a LEFT JOIN clause to the query using the EventPerson relation
 * @method     ChildEventQuery rightJoinEventPerson($relationAlias = null) Adds a RIGHT JOIN clause to the query using the EventPerson relation
 * @method     ChildEventQuery innerJoinEventPerson($relationAlias = null) Adds a INNER JOIN clause to the query using the EventPerson relation
 *
 * @method     ChildEventQuery joinWithEventPerson($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the EventPerson relation
 *
 * @method     ChildEventQuery leftJoinWithEventPerson() Adds a LEFT JOIN clause and with to the query using the EventPerson relation
 * @method     ChildEventQuery rightJoinWithEventPerson() Adds a RIGHT JOIN clause and with to the query using the EventPerson relation
 * @method     ChildEventQuery innerJoinWithEventPerson() Adds a INNER JOIN clause and with to the query using the EventPerson relation
 *
 * @method     ChildEventQuery leftJoinAvailability($relationAlias = null) Adds a LEFT JOIN clause to the query using the Availability relation
 * @method     ChildEventQuery rightJoinAvailability($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Availability relation
 * @method     ChildEventQuery innerJoinAvailability($relationAlias = null) Adds a INNER JOIN clause to the query using the Availability relation
 *
 * @method     ChildEventQuery joinWithAvailability($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Availability relation
 *
 * @method     ChildEventQuery leftJoinWithAvailability() Adds a LEFT JOIN clause and with to the query using the Availability relation
 * @method     ChildEventQuery rightJoinWithAvailability() Adds a RIGHT JOIN clause and with to the query using the Availability relation
 * @method     ChildEventQuery innerJoinWithAvailability() Adds a INNER JOIN clause and with to the query using the Availability relation
 *
 * @method     \TechWilk\Rota\UserQuery|\TechWilk\Rota\EventTypeQuery|\TechWilk\Rota\EventSubTypeQuery|\TechWilk\Rota\LocationQuery|\TechWilk\Rota\EventGroupQuery|\TechWilk\Rota\CommentQuery|\TechWilk\Rota\EventPersonQuery|\TechWilk\Rota\AvailabilityQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildEvent|null findOne(?ConnectionInterface $con = null) Return the first ChildEvent matching the query
 * @method     ChildEvent findOneOrCreate(?ConnectionInterface $con = null) Return the first ChildEvent matching the query, or a new ChildEvent object populated from the query conditions when no match is found
 *
 * @method     ChildEvent|null findOneById(int $id) Return the first ChildEvent filtered by the id column
 * @method     ChildEvent|null findOneByDate(string $date) Return the first ChildEvent filtered by the date column
 * @method     ChildEvent|null findOneByName(string $name) Return the first ChildEvent filtered by the name column
 * @method     ChildEvent|null findOneByCreatedBy(int $createdBy) Return the first ChildEvent filtered by the createdBy column
 * @method     ChildEvent|null findOneByRehearsalDate(string $rehearsalDate) Return the first ChildEvent filtered by the rehearsalDate column
 * @method     ChildEvent|null findOneByEventTypeId(int $type) Return the first ChildEvent filtered by the type column
 * @method     ChildEvent|null findOneByEventSubTypeId(int $subType) Return the first ChildEvent filtered by the subType column
 * @method     ChildEvent|null findOneByLocationId(int $location) Return the first ChildEvent filtered by the location column
 * @method     ChildEvent|null findOneByNotified(int $notified) Return the first ChildEvent filtered by the notified column
 * @method     ChildEvent|null findOneByRehearsal(int $rehearsal) Return the first ChildEvent filtered by the rehearsal column
 * @method     ChildEvent|null findOneByRemoved(int $removed) Return the first ChildEvent filtered by the removed column
 * @method     ChildEvent|null findOneByEventGroupId(int $eventGroup) Return the first ChildEvent filtered by the eventGroup column
 * @method     ChildEvent|null findOneBySermonTitle(string $sermonTitle) Return the first ChildEvent filtered by the sermonTitle column
 * @method     ChildEvent|null findOneByBibleVerse(string $bibleVerse) Return the first ChildEvent filtered by the bibleVerse column
 * @method     ChildEvent|null findOneByCreated(string $created) Return the first ChildEvent filtered by the created column
 * @method     ChildEvent|null findOneByUpdated(string $updated) Return the first ChildEvent filtered by the updated column
 *
 * @method     ChildEvent requirePk($key, ?ConnectionInterface $con = null) Return the ChildEvent by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEvent requireOne(?ConnectionInterface $con = null) Return the first ChildEvent matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildEvent requireOneById(int $id) Return the first ChildEvent filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEvent requireOneByDate(string $date) Return the first ChildEvent filtered by the date column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEvent requireOneByName(string $name) Return the first ChildEvent filtered by the name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEvent requireOneByCreatedBy(int $createdBy) Return the first ChildEvent filtered by the createdBy column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEvent requireOneByRehearsalDate(string $rehearsalDate) Return the first ChildEvent filtered by the rehearsalDate column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEvent requireOneByEventTypeId(int $type) Return the first ChildEvent filtered by the type column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEvent requireOneByEventSubTypeId(int $subType) Return the first ChildEvent filtered by the subType column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEvent requireOneByLocationId(int $location) Return the first ChildEvent filtered by the location column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEvent requireOneByNotified(int $notified) Return the first ChildEvent filtered by the notified column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEvent requireOneByRehearsal(int $rehearsal) Return the first ChildEvent filtered by the rehearsal column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEvent requireOneByRemoved(int $removed) Return the first ChildEvent filtered by the removed column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEvent requireOneByEventGroupId(int $eventGroup) Return the first ChildEvent filtered by the eventGroup column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEvent requireOneBySermonTitle(string $sermonTitle) Return the first ChildEvent filtered by the sermonTitle column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEvent requireOneByBibleVerse(string $bibleVerse) Return the first ChildEvent filtered by the bibleVerse column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEvent requireOneByCreated(string $created) Return the first ChildEvent filtered by the created column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEvent requireOneByUpdated(string $updated) Return the first ChildEvent filtered by the updated column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildEvent[]|Collection find(?ConnectionInterface $con = null) Return ChildEvent objects based on current ModelCriteria
 * @psalm-method Collection&\Traversable<ChildEvent> find(?ConnectionInterface $con = null) Return ChildEvent objects based on current ModelCriteria
 *
 * @method     ChildEvent[]|Collection findById(int|array<int> $id) Return ChildEvent objects filtered by the id column
 * @psalm-method Collection&\Traversable<ChildEvent> findById(int|array<int> $id) Return ChildEvent objects filtered by the id column
 * @method     ChildEvent[]|Collection findByDate(string|array<string> $date) Return ChildEvent objects filtered by the date column
 * @psalm-method Collection&\Traversable<ChildEvent> findByDate(string|array<string> $date) Return ChildEvent objects filtered by the date column
 * @method     ChildEvent[]|Collection findByName(string|array<string> $name) Return ChildEvent objects filtered by the name column
 * @psalm-method Collection&\Traversable<ChildEvent> findByName(string|array<string> $name) Return ChildEvent objects filtered by the name column
 * @method     ChildEvent[]|Collection findByCreatedBy(int|array<int> $createdBy) Return ChildEvent objects filtered by the createdBy column
 * @psalm-method Collection&\Traversable<ChildEvent> findByCreatedBy(int|array<int> $createdBy) Return ChildEvent objects filtered by the createdBy column
 * @method     ChildEvent[]|Collection findByRehearsalDate(string|array<string> $rehearsalDate) Return ChildEvent objects filtered by the rehearsalDate column
 * @psalm-method Collection&\Traversable<ChildEvent> findByRehearsalDate(string|array<string> $rehearsalDate) Return ChildEvent objects filtered by the rehearsalDate column
 * @method     ChildEvent[]|Collection findByEventTypeId(int|array<int> $type) Return ChildEvent objects filtered by the type column
 * @psalm-method Collection&\Traversable<ChildEvent> findByEventTypeId(int|array<int> $type) Return ChildEvent objects filtered by the type column
 * @method     ChildEvent[]|Collection findByEventSubTypeId(int|array<int> $subType) Return ChildEvent objects filtered by the subType column
 * @psalm-method Collection&\Traversable<ChildEvent> findByEventSubTypeId(int|array<int> $subType) Return ChildEvent objects filtered by the subType column
 * @method     ChildEvent[]|Collection findByLocationId(int|array<int> $location) Return ChildEvent objects filtered by the location column
 * @psalm-method Collection&\Traversable<ChildEvent> findByLocationId(int|array<int> $location) Return ChildEvent objects filtered by the location column
 * @method     ChildEvent[]|Collection findByNotified(int|array<int> $notified) Return ChildEvent objects filtered by the notified column
 * @psalm-method Collection&\Traversable<ChildEvent> findByNotified(int|array<int> $notified) Return ChildEvent objects filtered by the notified column
 * @method     ChildEvent[]|Collection findByRehearsal(int|array<int> $rehearsal) Return ChildEvent objects filtered by the rehearsal column
 * @psalm-method Collection&\Traversable<ChildEvent> findByRehearsal(int|array<int> $rehearsal) Return ChildEvent objects filtered by the rehearsal column
 * @method     ChildEvent[]|Collection findByRemoved(int|array<int> $removed) Return ChildEvent objects filtered by the removed column
 * @psalm-method Collection&\Traversable<ChildEvent> findByRemoved(int|array<int> $removed) Return ChildEvent objects filtered by the removed column
 * @method     ChildEvent[]|Collection findByEventGroupId(int|array<int> $eventGroup) Return ChildEvent objects filtered by the eventGroup column
 * @psalm-method Collection&\Traversable<ChildEvent> findByEventGroupId(int|array<int> $eventGroup) Return ChildEvent objects filtered by the eventGroup column
 * @method     ChildEvent[]|Collection findBySermonTitle(string|array<string> $sermonTitle) Return ChildEvent objects filtered by the sermonTitle column
 * @psalm-method Collection&\Traversable<ChildEvent> findBySermonTitle(string|array<string> $sermonTitle) Return ChildEvent objects filtered by the sermonTitle column
 * @method     ChildEvent[]|Collection findByBibleVerse(string|array<string> $bibleVerse) Return ChildEvent objects filtered by the bibleVerse column
 * @psalm-method Collection&\Traversable<ChildEvent> findByBibleVerse(string|array<string> $bibleVerse) Return ChildEvent objects filtered by the bibleVerse column
 * @method     ChildEvent[]|Collection findByCreated(string|array<string> $created) Return ChildEvent objects filtered by the created column
 * @psalm-method Collection&\Traversable<ChildEvent> findByCreated(string|array<string> $created) Return ChildEvent objects filtered by the created column
 * @method     ChildEvent[]|Collection findByUpdated(string|array<string> $updated) Return ChildEvent objects filtered by the updated column
 * @psalm-method Collection&\Traversable<ChildEvent> findByUpdated(string|array<string> $updated) Return ChildEvent objects filtered by the updated column
 *
 * @method     ChildEvent[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 * @psalm-method \Propel\Runtime\Util\PropelModelPager&\Traversable<ChildEvent> paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 */
abstract class EventQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \TechWilk\Rota\Base\EventQuery object.
     *
     * @param string $dbName The database name
     * @param string $modelName The phpName of a model, e.g. 'Book'
     * @param string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\TechWilk\\Rota\\Event', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildEventQuery object.
     *
     * @param string $modelAlias The alias of a model in the query
     * @param Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildEventQuery
     */
    public static function create(?string $modelAlias = null, ?Criteria $criteria = null): Criteria
    {
        if ($criteria instanceof ChildEventQuery) {
            return $criteria;
        }
        $query = new ChildEventQuery();
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
     * @return ChildEvent|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ?ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(EventTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = EventTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildEvent A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, date, name, createdBy, rehearsalDate, type, subType, location, notified, rehearsal, removed, eventGroup, sermonTitle, bibleVerse, created, updated FROM events WHERE id = :p0';
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
            /** @var ChildEvent $obj */
            $obj = new ChildEvent();
            $obj->hydrate($row);
            EventTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildEvent|array|mixed the result, formatted by the current formatter
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

        $this->addUsingAlias(EventTableMap::COL_ID, $key, Criteria::EQUAL);

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

        $this->addUsingAlias(EventTableMap::COL_ID, $keys, Criteria::IN);

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
                $this->addUsingAlias(EventTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(EventTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(EventTableMap::COL_ID, $id, $comparison);

        return $this;
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
     * @param mixed $date The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByDate($date = null, ?string $comparison = null)
    {
        if (is_array($date)) {
            $useMinMax = false;
            if (isset($date['min'])) {
                $this->addUsingAlias(EventTableMap::COL_DATE, $date['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($date['max'])) {
                $this->addUsingAlias(EventTableMap::COL_DATE, $date['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(EventTableMap::COL_DATE, $date, $comparison);

        return $this;
    }

    /**
     * Filter the query on the name column
     *
     * Example usage:
     * <code>
     * $query->filterByName('fooValue');   // WHERE name = 'fooValue'
     * $query->filterByName('%fooValue%', Criteria::LIKE); // WHERE name LIKE '%fooValue%'
     * $query->filterByName(['foo', 'bar']); // WHERE name IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $name The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByName($name = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(EventTableMap::COL_NAME, $name, $comparison);

        return $this;
    }

    /**
     * Filter the query on the createdBy column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedBy(1234); // WHERE createdBy = 1234
     * $query->filterByCreatedBy(array(12, 34)); // WHERE createdBy IN (12, 34)
     * $query->filterByCreatedBy(array('min' => 12)); // WHERE createdBy > 12
     * </code>
     *
     * @see       filterByUser()
     *
     * @param mixed $createdBy The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByCreatedBy($createdBy = null, ?string $comparison = null)
    {
        if (is_array($createdBy)) {
            $useMinMax = false;
            if (isset($createdBy['min'])) {
                $this->addUsingAlias(EventTableMap::COL_CREATEDBY, $createdBy['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdBy['max'])) {
                $this->addUsingAlias(EventTableMap::COL_CREATEDBY, $createdBy['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(EventTableMap::COL_CREATEDBY, $createdBy, $comparison);

        return $this;
    }

    /**
     * Filter the query on the rehearsalDate column
     *
     * Example usage:
     * <code>
     * $query->filterByRehearsalDate('2011-03-14'); // WHERE rehearsalDate = '2011-03-14'
     * $query->filterByRehearsalDate('now'); // WHERE rehearsalDate = '2011-03-14'
     * $query->filterByRehearsalDate(array('max' => 'yesterday')); // WHERE rehearsalDate > '2011-03-13'
     * </code>
     *
     * @param mixed $rehearsalDate The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByRehearsalDate($rehearsalDate = null, ?string $comparison = null)
    {
        if (is_array($rehearsalDate)) {
            $useMinMax = false;
            if (isset($rehearsalDate['min'])) {
                $this->addUsingAlias(EventTableMap::COL_REHEARSALDATE, $rehearsalDate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($rehearsalDate['max'])) {
                $this->addUsingAlias(EventTableMap::COL_REHEARSALDATE, $rehearsalDate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(EventTableMap::COL_REHEARSALDATE, $rehearsalDate, $comparison);

        return $this;
    }

    /**
     * Filter the query on the type column
     *
     * Example usage:
     * <code>
     * $query->filterByEventTypeId(1234); // WHERE type = 1234
     * $query->filterByEventTypeId(array(12, 34)); // WHERE type IN (12, 34)
     * $query->filterByEventTypeId(array('min' => 12)); // WHERE type > 12
     * </code>
     *
     * @see       filterByEventType()
     *
     * @param mixed $eventTypeId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByEventTypeId($eventTypeId = null, ?string $comparison = null)
    {
        if (is_array($eventTypeId)) {
            $useMinMax = false;
            if (isset($eventTypeId['min'])) {
                $this->addUsingAlias(EventTableMap::COL_TYPE, $eventTypeId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($eventTypeId['max'])) {
                $this->addUsingAlias(EventTableMap::COL_TYPE, $eventTypeId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(EventTableMap::COL_TYPE, $eventTypeId, $comparison);

        return $this;
    }

    /**
     * Filter the query on the subType column
     *
     * Example usage:
     * <code>
     * $query->filterByEventSubTypeId(1234); // WHERE subType = 1234
     * $query->filterByEventSubTypeId(array(12, 34)); // WHERE subType IN (12, 34)
     * $query->filterByEventSubTypeId(array('min' => 12)); // WHERE subType > 12
     * </code>
     *
     * @see       filterByEventSubType()
     *
     * @param mixed $eventSubTypeId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByEventSubTypeId($eventSubTypeId = null, ?string $comparison = null)
    {
        if (is_array($eventSubTypeId)) {
            $useMinMax = false;
            if (isset($eventSubTypeId['min'])) {
                $this->addUsingAlias(EventTableMap::COL_SUBTYPE, $eventSubTypeId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($eventSubTypeId['max'])) {
                $this->addUsingAlias(EventTableMap::COL_SUBTYPE, $eventSubTypeId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(EventTableMap::COL_SUBTYPE, $eventSubTypeId, $comparison);

        return $this;
    }

    /**
     * Filter the query on the location column
     *
     * Example usage:
     * <code>
     * $query->filterByLocationId(1234); // WHERE location = 1234
     * $query->filterByLocationId(array(12, 34)); // WHERE location IN (12, 34)
     * $query->filterByLocationId(array('min' => 12)); // WHERE location > 12
     * </code>
     *
     * @see       filterByLocation()
     *
     * @param mixed $locationId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByLocationId($locationId = null, ?string $comparison = null)
    {
        if (is_array($locationId)) {
            $useMinMax = false;
            if (isset($locationId['min'])) {
                $this->addUsingAlias(EventTableMap::COL_LOCATION, $locationId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($locationId['max'])) {
                $this->addUsingAlias(EventTableMap::COL_LOCATION, $locationId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(EventTableMap::COL_LOCATION, $locationId, $comparison);

        return $this;
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
     * @param mixed $notified The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByNotified($notified = null, ?string $comparison = null)
    {
        if (is_array($notified)) {
            $useMinMax = false;
            if (isset($notified['min'])) {
                $this->addUsingAlias(EventTableMap::COL_NOTIFIED, $notified['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($notified['max'])) {
                $this->addUsingAlias(EventTableMap::COL_NOTIFIED, $notified['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(EventTableMap::COL_NOTIFIED, $notified, $comparison);

        return $this;
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
     * @param mixed $rehearsal The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByRehearsal($rehearsal = null, ?string $comparison = null)
    {
        if (is_array($rehearsal)) {
            $useMinMax = false;
            if (isset($rehearsal['min'])) {
                $this->addUsingAlias(EventTableMap::COL_REHEARSAL, $rehearsal['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($rehearsal['max'])) {
                $this->addUsingAlias(EventTableMap::COL_REHEARSAL, $rehearsal['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(EventTableMap::COL_REHEARSAL, $rehearsal, $comparison);

        return $this;
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
     * @param mixed $removed The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByRemoved($removed = null, ?string $comparison = null)
    {
        if (is_array($removed)) {
            $useMinMax = false;
            if (isset($removed['min'])) {
                $this->addUsingAlias(EventTableMap::COL_REMOVED, $removed['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($removed['max'])) {
                $this->addUsingAlias(EventTableMap::COL_REMOVED, $removed['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(EventTableMap::COL_REMOVED, $removed, $comparison);

        return $this;
    }

    /**
     * Filter the query on the eventGroup column
     *
     * Example usage:
     * <code>
     * $query->filterByEventGroupId(1234); // WHERE eventGroup = 1234
     * $query->filterByEventGroupId(array(12, 34)); // WHERE eventGroup IN (12, 34)
     * $query->filterByEventGroupId(array('min' => 12)); // WHERE eventGroup > 12
     * </code>
     *
     * @see       filterByEventGroup()
     *
     * @param mixed $eventGroupId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByEventGroupId($eventGroupId = null, ?string $comparison = null)
    {
        if (is_array($eventGroupId)) {
            $useMinMax = false;
            if (isset($eventGroupId['min'])) {
                $this->addUsingAlias(EventTableMap::COL_EVENTGROUP, $eventGroupId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($eventGroupId['max'])) {
                $this->addUsingAlias(EventTableMap::COL_EVENTGROUP, $eventGroupId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(EventTableMap::COL_EVENTGROUP, $eventGroupId, $comparison);

        return $this;
    }

    /**
     * Filter the query on the sermonTitle column
     *
     * Example usage:
     * <code>
     * $query->filterBySermonTitle('fooValue');   // WHERE sermonTitle = 'fooValue'
     * $query->filterBySermonTitle('%fooValue%', Criteria::LIKE); // WHERE sermonTitle LIKE '%fooValue%'
     * $query->filterBySermonTitle(['foo', 'bar']); // WHERE sermonTitle IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $sermonTitle The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterBySermonTitle($sermonTitle = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($sermonTitle)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(EventTableMap::COL_SERMONTITLE, $sermonTitle, $comparison);

        return $this;
    }

    /**
     * Filter the query on the bibleVerse column
     *
     * Example usage:
     * <code>
     * $query->filterByBibleVerse('fooValue');   // WHERE bibleVerse = 'fooValue'
     * $query->filterByBibleVerse('%fooValue%', Criteria::LIKE); // WHERE bibleVerse LIKE '%fooValue%'
     * $query->filterByBibleVerse(['foo', 'bar']); // WHERE bibleVerse IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $bibleVerse The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByBibleVerse($bibleVerse = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($bibleVerse)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(EventTableMap::COL_BIBLEVERSE, $bibleVerse, $comparison);

        return $this;
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
     * @param mixed $created The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByCreated($created = null, ?string $comparison = null)
    {
        if (is_array($created)) {
            $useMinMax = false;
            if (isset($created['min'])) {
                $this->addUsingAlias(EventTableMap::COL_CREATED, $created['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($created['max'])) {
                $this->addUsingAlias(EventTableMap::COL_CREATED, $created['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(EventTableMap::COL_CREATED, $created, $comparison);

        return $this;
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
     * @param mixed $updated The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByUpdated($updated = null, ?string $comparison = null)
    {
        if (is_array($updated)) {
            $useMinMax = false;
            if (isset($updated['min'])) {
                $this->addUsingAlias(EventTableMap::COL_UPDATED, $updated['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updated['max'])) {
                $this->addUsingAlias(EventTableMap::COL_UPDATED, $updated['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(EventTableMap::COL_UPDATED, $updated, $comparison);

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
                ->addUsingAlias(EventTableMap::COL_CREATEDBY, $user->getId(), $comparison);
        } elseif ($user instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            $this
                ->addUsingAlias(EventTableMap::COL_CREATEDBY, $user->toKeyValue('PrimaryKey', 'Id'), $comparison);

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
     * Filter the query by a related \TechWilk\Rota\EventType object
     *
     * @param \TechWilk\Rota\EventType|ObjectCollection $eventType The related object(s) to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByEventType($eventType, ?string $comparison = null)
    {
        if ($eventType instanceof \TechWilk\Rota\EventType) {
            return $this
                ->addUsingAlias(EventTableMap::COL_TYPE, $eventType->getId(), $comparison);
        } elseif ($eventType instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            $this
                ->addUsingAlias(EventTableMap::COL_TYPE, $eventType->toKeyValue('PrimaryKey', 'Id'), $comparison);

            return $this;
        } else {
            throw new PropelException('filterByEventType() only accepts arguments of type \TechWilk\Rota\EventType or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the EventType relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinEventType(?string $relationAlias = null, ?string $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('EventType');

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
            $this->addJoinObject($join, 'EventType');
        }

        return $this;
    }

    /**
     * Use the EventType relation EventType object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \TechWilk\Rota\EventTypeQuery A secondary query class using the current class as primary query
     */
    public function useEventTypeQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinEventType($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'EventType', '\TechWilk\Rota\EventTypeQuery');
    }

    /**
     * Use the EventType relation EventType object
     *
     * @param callable(\TechWilk\Rota\EventTypeQuery):\TechWilk\Rota\EventTypeQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withEventTypeQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::INNER_JOIN
    ) {
        $relatedQuery = $this->useEventTypeQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to EventType table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \TechWilk\Rota\EventTypeQuery The inner query object of the EXISTS statement
     */
    public function useEventTypeExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \TechWilk\Rota\EventTypeQuery */
        $q = $this->useExistsQuery('EventType', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to EventType table for a NOT EXISTS query.
     *
     * @see useEventTypeExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \TechWilk\Rota\EventTypeQuery The inner query object of the NOT EXISTS statement
     */
    public function useEventTypeNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \TechWilk\Rota\EventTypeQuery */
        $q = $this->useExistsQuery('EventType', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to EventType table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \TechWilk\Rota\EventTypeQuery The inner query object of the IN statement
     */
    public function useInEventTypeQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \TechWilk\Rota\EventTypeQuery */
        $q = $this->useInQuery('EventType', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to EventType table for a NOT IN query.
     *
     * @see useEventTypeInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \TechWilk\Rota\EventTypeQuery The inner query object of the NOT IN statement
     */
    public function useNotInEventTypeQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \TechWilk\Rota\EventTypeQuery */
        $q = $this->useInQuery('EventType', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Filter the query by a related \TechWilk\Rota\EventSubType object
     *
     * @param \TechWilk\Rota\EventSubType|ObjectCollection $eventSubType The related object(s) to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByEventSubType($eventSubType, ?string $comparison = null)
    {
        if ($eventSubType instanceof \TechWilk\Rota\EventSubType) {
            return $this
                ->addUsingAlias(EventTableMap::COL_SUBTYPE, $eventSubType->getId(), $comparison);
        } elseif ($eventSubType instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            $this
                ->addUsingAlias(EventTableMap::COL_SUBTYPE, $eventSubType->toKeyValue('PrimaryKey', 'Id'), $comparison);

            return $this;
        } else {
            throw new PropelException('filterByEventSubType() only accepts arguments of type \TechWilk\Rota\EventSubType or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the EventSubType relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinEventSubType(?string $relationAlias = null, ?string $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('EventSubType');

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
            $this->addJoinObject($join, 'EventSubType');
        }

        return $this;
    }

    /**
     * Use the EventSubType relation EventSubType object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \TechWilk\Rota\EventSubTypeQuery A secondary query class using the current class as primary query
     */
    public function useEventSubTypeQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinEventSubType($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'EventSubType', '\TechWilk\Rota\EventSubTypeQuery');
    }

    /**
     * Use the EventSubType relation EventSubType object
     *
     * @param callable(\TechWilk\Rota\EventSubTypeQuery):\TechWilk\Rota\EventSubTypeQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withEventSubTypeQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::INNER_JOIN
    ) {
        $relatedQuery = $this->useEventSubTypeQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to EventSubType table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \TechWilk\Rota\EventSubTypeQuery The inner query object of the EXISTS statement
     */
    public function useEventSubTypeExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \TechWilk\Rota\EventSubTypeQuery */
        $q = $this->useExistsQuery('EventSubType', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to EventSubType table for a NOT EXISTS query.
     *
     * @see useEventSubTypeExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \TechWilk\Rota\EventSubTypeQuery The inner query object of the NOT EXISTS statement
     */
    public function useEventSubTypeNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \TechWilk\Rota\EventSubTypeQuery */
        $q = $this->useExistsQuery('EventSubType', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to EventSubType table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \TechWilk\Rota\EventSubTypeQuery The inner query object of the IN statement
     */
    public function useInEventSubTypeQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \TechWilk\Rota\EventSubTypeQuery */
        $q = $this->useInQuery('EventSubType', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to EventSubType table for a NOT IN query.
     *
     * @see useEventSubTypeInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \TechWilk\Rota\EventSubTypeQuery The inner query object of the NOT IN statement
     */
    public function useNotInEventSubTypeQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \TechWilk\Rota\EventSubTypeQuery */
        $q = $this->useInQuery('EventSubType', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Filter the query by a related \TechWilk\Rota\Location object
     *
     * @param \TechWilk\Rota\Location|ObjectCollection $location The related object(s) to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByLocation($location, ?string $comparison = null)
    {
        if ($location instanceof \TechWilk\Rota\Location) {
            return $this
                ->addUsingAlias(EventTableMap::COL_LOCATION, $location->getId(), $comparison);
        } elseif ($location instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            $this
                ->addUsingAlias(EventTableMap::COL_LOCATION, $location->toKeyValue('PrimaryKey', 'Id'), $comparison);

            return $this;
        } else {
            throw new PropelException('filterByLocation() only accepts arguments of type \TechWilk\Rota\Location or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Location relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinLocation(?string $relationAlias = null, ?string $joinType = Criteria::INNER_JOIN)
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
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \TechWilk\Rota\LocationQuery A secondary query class using the current class as primary query
     */
    public function useLocationQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinLocation($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Location', '\TechWilk\Rota\LocationQuery');
    }

    /**
     * Use the Location relation Location object
     *
     * @param callable(\TechWilk\Rota\LocationQuery):\TechWilk\Rota\LocationQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withLocationQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::INNER_JOIN
    ) {
        $relatedQuery = $this->useLocationQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to Location table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \TechWilk\Rota\LocationQuery The inner query object of the EXISTS statement
     */
    public function useLocationExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \TechWilk\Rota\LocationQuery */
        $q = $this->useExistsQuery('Location', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to Location table for a NOT EXISTS query.
     *
     * @see useLocationExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \TechWilk\Rota\LocationQuery The inner query object of the NOT EXISTS statement
     */
    public function useLocationNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \TechWilk\Rota\LocationQuery */
        $q = $this->useExistsQuery('Location', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to Location table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \TechWilk\Rota\LocationQuery The inner query object of the IN statement
     */
    public function useInLocationQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \TechWilk\Rota\LocationQuery */
        $q = $this->useInQuery('Location', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to Location table for a NOT IN query.
     *
     * @see useLocationInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \TechWilk\Rota\LocationQuery The inner query object of the NOT IN statement
     */
    public function useNotInLocationQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \TechWilk\Rota\LocationQuery */
        $q = $this->useInQuery('Location', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Filter the query by a related \TechWilk\Rota\EventGroup object
     *
     * @param \TechWilk\Rota\EventGroup|ObjectCollection $eventGroup The related object(s) to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByEventGroup($eventGroup, ?string $comparison = null)
    {
        if ($eventGroup instanceof \TechWilk\Rota\EventGroup) {
            return $this
                ->addUsingAlias(EventTableMap::COL_EVENTGROUP, $eventGroup->getId(), $comparison);
        } elseif ($eventGroup instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            $this
                ->addUsingAlias(EventTableMap::COL_EVENTGROUP, $eventGroup->toKeyValue('PrimaryKey', 'Id'), $comparison);

            return $this;
        } else {
            throw new PropelException('filterByEventGroup() only accepts arguments of type \TechWilk\Rota\EventGroup or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the EventGroup relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinEventGroup(?string $relationAlias = null, ?string $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('EventGroup');

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
            $this->addJoinObject($join, 'EventGroup');
        }

        return $this;
    }

    /**
     * Use the EventGroup relation EventGroup object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \TechWilk\Rota\EventGroupQuery A secondary query class using the current class as primary query
     */
    public function useEventGroupQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinEventGroup($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'EventGroup', '\TechWilk\Rota\EventGroupQuery');
    }

    /**
     * Use the EventGroup relation EventGroup object
     *
     * @param callable(\TechWilk\Rota\EventGroupQuery):\TechWilk\Rota\EventGroupQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withEventGroupQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::LEFT_JOIN
    ) {
        $relatedQuery = $this->useEventGroupQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to EventGroup table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \TechWilk\Rota\EventGroupQuery The inner query object of the EXISTS statement
     */
    public function useEventGroupExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \TechWilk\Rota\EventGroupQuery */
        $q = $this->useExistsQuery('EventGroup', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to EventGroup table for a NOT EXISTS query.
     *
     * @see useEventGroupExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \TechWilk\Rota\EventGroupQuery The inner query object of the NOT EXISTS statement
     */
    public function useEventGroupNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \TechWilk\Rota\EventGroupQuery */
        $q = $this->useExistsQuery('EventGroup', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to EventGroup table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \TechWilk\Rota\EventGroupQuery The inner query object of the IN statement
     */
    public function useInEventGroupQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \TechWilk\Rota\EventGroupQuery */
        $q = $this->useInQuery('EventGroup', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to EventGroup table for a NOT IN query.
     *
     * @see useEventGroupInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \TechWilk\Rota\EventGroupQuery The inner query object of the NOT IN statement
     */
    public function useNotInEventGroupQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \TechWilk\Rota\EventGroupQuery */
        $q = $this->useInQuery('EventGroup', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Filter the query by a related \TechWilk\Rota\Comment object
     *
     * @param \TechWilk\Rota\Comment|ObjectCollection $comment the related object to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByComment($comment, ?string $comparison = null)
    {
        if ($comment instanceof \TechWilk\Rota\Comment) {
            $this
                ->addUsingAlias(EventTableMap::COL_ID, $comment->getEventId(), $comparison);

            return $this;
        } elseif ($comment instanceof ObjectCollection) {
            $this
                ->useCommentQuery()
                ->filterByPrimaryKeys($comment->getPrimaryKeys())
                ->endUse();

            return $this;
        } else {
            throw new PropelException('filterByComment() only accepts arguments of type \TechWilk\Rota\Comment or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Comment relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinComment(?string $relationAlias = null, ?string $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Comment');

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
            $this->addJoinObject($join, 'Comment');
        }

        return $this;
    }

    /**
     * Use the Comment relation Comment object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \TechWilk\Rota\CommentQuery A secondary query class using the current class as primary query
     */
    public function useCommentQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinComment($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Comment', '\TechWilk\Rota\CommentQuery');
    }

    /**
     * Use the Comment relation Comment object
     *
     * @param callable(\TechWilk\Rota\CommentQuery):\TechWilk\Rota\CommentQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withCommentQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::INNER_JOIN
    ) {
        $relatedQuery = $this->useCommentQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to Comment table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \TechWilk\Rota\CommentQuery The inner query object of the EXISTS statement
     */
    public function useCommentExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \TechWilk\Rota\CommentQuery */
        $q = $this->useExistsQuery('Comment', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to Comment table for a NOT EXISTS query.
     *
     * @see useCommentExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \TechWilk\Rota\CommentQuery The inner query object of the NOT EXISTS statement
     */
    public function useCommentNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \TechWilk\Rota\CommentQuery */
        $q = $this->useExistsQuery('Comment', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to Comment table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \TechWilk\Rota\CommentQuery The inner query object of the IN statement
     */
    public function useInCommentQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \TechWilk\Rota\CommentQuery */
        $q = $this->useInQuery('Comment', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to Comment table for a NOT IN query.
     *
     * @see useCommentInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \TechWilk\Rota\CommentQuery The inner query object of the NOT IN statement
     */
    public function useNotInCommentQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \TechWilk\Rota\CommentQuery */
        $q = $this->useInQuery('Comment', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Filter the query by a related \TechWilk\Rota\EventPerson object
     *
     * @param \TechWilk\Rota\EventPerson|ObjectCollection $eventPerson the related object to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByEventPerson($eventPerson, ?string $comparison = null)
    {
        if ($eventPerson instanceof \TechWilk\Rota\EventPerson) {
            $this
                ->addUsingAlias(EventTableMap::COL_ID, $eventPerson->getEventId(), $comparison);

            return $this;
        } elseif ($eventPerson instanceof ObjectCollection) {
            $this
                ->useEventPersonQuery()
                ->filterByPrimaryKeys($eventPerson->getPrimaryKeys())
                ->endUse();

            return $this;
        } else {
            throw new PropelException('filterByEventPerson() only accepts arguments of type \TechWilk\Rota\EventPerson or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the EventPerson relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinEventPerson(?string $relationAlias = null, ?string $joinType = Criteria::INNER_JOIN)
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
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
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
     * Use the EventPerson relation EventPerson object
     *
     * @param callable(\TechWilk\Rota\EventPersonQuery):\TechWilk\Rota\EventPersonQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withEventPersonQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::INNER_JOIN
    ) {
        $relatedQuery = $this->useEventPersonQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to EventPerson table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \TechWilk\Rota\EventPersonQuery The inner query object of the EXISTS statement
     */
    public function useEventPersonExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \TechWilk\Rota\EventPersonQuery */
        $q = $this->useExistsQuery('EventPerson', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to EventPerson table for a NOT EXISTS query.
     *
     * @see useEventPersonExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \TechWilk\Rota\EventPersonQuery The inner query object of the NOT EXISTS statement
     */
    public function useEventPersonNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \TechWilk\Rota\EventPersonQuery */
        $q = $this->useExistsQuery('EventPerson', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to EventPerson table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \TechWilk\Rota\EventPersonQuery The inner query object of the IN statement
     */
    public function useInEventPersonQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \TechWilk\Rota\EventPersonQuery */
        $q = $this->useInQuery('EventPerson', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to EventPerson table for a NOT IN query.
     *
     * @see useEventPersonInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \TechWilk\Rota\EventPersonQuery The inner query object of the NOT IN statement
     */
    public function useNotInEventPersonQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \TechWilk\Rota\EventPersonQuery */
        $q = $this->useInQuery('EventPerson', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Filter the query by a related \TechWilk\Rota\Availability object
     *
     * @param \TechWilk\Rota\Availability|ObjectCollection $availability the related object to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByAvailability($availability, ?string $comparison = null)
    {
        if ($availability instanceof \TechWilk\Rota\Availability) {
            $this
                ->addUsingAlias(EventTableMap::COL_ID, $availability->getEventId(), $comparison);

            return $this;
        } elseif ($availability instanceof ObjectCollection) {
            $this
                ->useAvailabilityQuery()
                ->filterByPrimaryKeys($availability->getPrimaryKeys())
                ->endUse();

            return $this;
        } else {
            throw new PropelException('filterByAvailability() only accepts arguments of type \TechWilk\Rota\Availability or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Availability relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinAvailability(?string $relationAlias = null, ?string $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Availability');

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
            $this->addJoinObject($join, 'Availability');
        }

        return $this;
    }

    /**
     * Use the Availability relation Availability object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \TechWilk\Rota\AvailabilityQuery A secondary query class using the current class as primary query
     */
    public function useAvailabilityQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinAvailability($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Availability', '\TechWilk\Rota\AvailabilityQuery');
    }

    /**
     * Use the Availability relation Availability object
     *
     * @param callable(\TechWilk\Rota\AvailabilityQuery):\TechWilk\Rota\AvailabilityQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withAvailabilityQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::INNER_JOIN
    ) {
        $relatedQuery = $this->useAvailabilityQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to Availability table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \TechWilk\Rota\AvailabilityQuery The inner query object of the EXISTS statement
     */
    public function useAvailabilityExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \TechWilk\Rota\AvailabilityQuery */
        $q = $this->useExistsQuery('Availability', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to Availability table for a NOT EXISTS query.
     *
     * @see useAvailabilityExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \TechWilk\Rota\AvailabilityQuery The inner query object of the NOT EXISTS statement
     */
    public function useAvailabilityNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \TechWilk\Rota\AvailabilityQuery */
        $q = $this->useExistsQuery('Availability', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to Availability table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \TechWilk\Rota\AvailabilityQuery The inner query object of the IN statement
     */
    public function useInAvailabilityQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \TechWilk\Rota\AvailabilityQuery */
        $q = $this->useInQuery('Availability', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to Availability table for a NOT IN query.
     *
     * @see useAvailabilityInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \TechWilk\Rota\AvailabilityQuery The inner query object of the NOT IN statement
     */
    public function useNotInAvailabilityQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \TechWilk\Rota\AvailabilityQuery */
        $q = $this->useInQuery('Availability', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Exclude object from result
     *
     * @param ChildEvent $event Object to remove from the list of results
     *
     * @return $this The current query, for fluid interface
     */
    public function prune($event = null)
    {
        if ($event) {
            $this->addUsingAlias(EventTableMap::COL_ID, $event->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the events table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(?ConnectionInterface $con = null): int
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(EventTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            EventTableMap::clearInstancePool();
            EventTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(EventTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(EventTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            EventTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            EventTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param int $nbDays Maximum age of the latest update in days
     *
     * @return $this The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        $this->addUsingAlias(EventTableMap::COL_UPDATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);

        return $this;
    }

    /**
     * Order by update date desc
     *
     * @return $this The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        $this->addDescendingOrderByColumn(EventTableMap::COL_UPDATED);

        return $this;
    }

    /**
     * Order by update date asc
     *
     * @return $this The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        $this->addAscendingOrderByColumn(EventTableMap::COL_UPDATED);

        return $this;
    }

    /**
     * Order by create date desc
     *
     * @return $this The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        $this->addDescendingOrderByColumn(EventTableMap::COL_CREATED);

        return $this;
    }

    /**
     * Filter by the latest created
     *
     * @param int $nbDays Maximum age of in days
     *
     * @return $this The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        $this->addUsingAlias(EventTableMap::COL_CREATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);

        return $this;
    }

    /**
     * Order by create date asc
     *
     * @return $this The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        $this->addAscendingOrderByColumn(EventTableMap::COL_CREATED);

        return $this;
    }

}
