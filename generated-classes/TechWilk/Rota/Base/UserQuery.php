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
use TechWilk\Rota\User as ChildUser;
use TechWilk\Rota\UserQuery as ChildUserQuery;
use TechWilk\Rota\Map\UserTableMap;

/**
 * Base class that represents a query for the 'users' table.
 *
 *
 *
 * @method     ChildUserQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildUserQuery orderByFirstName($order = Criteria::ASC) Order by the firstName column
 * @method     ChildUserQuery orderByLastName($order = Criteria::ASC) Order by the lastName column
 * @method     ChildUserQuery orderByUsername($order = Criteria::ASC) Order by the username column
 * @method     ChildUserQuery orderByPassword($order = Criteria::ASC) Order by the password column
 * @method     ChildUserQuery orderByIsAdmin($order = Criteria::ASC) Order by the isAdmin column
 * @method     ChildUserQuery orderByEmail($order = Criteria::ASC) Order by the email column
 * @method     ChildUserQuery orderByMobile($order = Criteria::ASC) Order by the mobile column
 * @method     ChildUserQuery orderByIsOverviewRecipient($order = Criteria::ASC) Order by the isOverviewRecipient column
 * @method     ChildUserQuery orderByRecieveReminderEmails($order = Criteria::ASC) Order by the recieveReminderEmails column
 * @method     ChildUserQuery orderByIsBandAdmin($order = Criteria::ASC) Order by the isBandAdmin column
 * @method     ChildUserQuery orderByIsEventEditor($order = Criteria::ASC) Order by the isEventEditor column
 * @method     ChildUserQuery orderByLastLogin($order = Criteria::ASC) Order by the lastLogin column
 * @method     ChildUserQuery orderByPasswordChanged($order = Criteria::ASC) Order by the passwordChanged column
 * @method     ChildUserQuery orderByCreated($order = Criteria::ASC) Order by the created column
 * @method     ChildUserQuery orderByUpdated($order = Criteria::ASC) Order by the updated column
 *
 * @method     ChildUserQuery groupById() Group by the id column
 * @method     ChildUserQuery groupByFirstName() Group by the firstName column
 * @method     ChildUserQuery groupByLastName() Group by the lastName column
 * @method     ChildUserQuery groupByUsername() Group by the username column
 * @method     ChildUserQuery groupByPassword() Group by the password column
 * @method     ChildUserQuery groupByIsAdmin() Group by the isAdmin column
 * @method     ChildUserQuery groupByEmail() Group by the email column
 * @method     ChildUserQuery groupByMobile() Group by the mobile column
 * @method     ChildUserQuery groupByIsOverviewRecipient() Group by the isOverviewRecipient column
 * @method     ChildUserQuery groupByRecieveReminderEmails() Group by the recieveReminderEmails column
 * @method     ChildUserQuery groupByIsBandAdmin() Group by the isBandAdmin column
 * @method     ChildUserQuery groupByIsEventEditor() Group by the isEventEditor column
 * @method     ChildUserQuery groupByLastLogin() Group by the lastLogin column
 * @method     ChildUserQuery groupByPasswordChanged() Group by the passwordChanged column
 * @method     ChildUserQuery groupByCreated() Group by the created column
 * @method     ChildUserQuery groupByUpdated() Group by the updated column
 *
 * @method     ChildUserQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildUserQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildUserQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildUserQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildUserQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildUserQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildUserQuery leftJoinCalendarToken($relationAlias = null) Adds a LEFT JOIN clause to the query using the CalendarToken relation
 * @method     ChildUserQuery rightJoinCalendarToken($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CalendarToken relation
 * @method     ChildUserQuery innerJoinCalendarToken($relationAlias = null) Adds a INNER JOIN clause to the query using the CalendarToken relation
 *
 * @method     ChildUserQuery joinWithCalendarToken($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the CalendarToken relation
 *
 * @method     ChildUserQuery leftJoinWithCalendarToken() Adds a LEFT JOIN clause and with to the query using the CalendarToken relation
 * @method     ChildUserQuery rightJoinWithCalendarToken() Adds a RIGHT JOIN clause and with to the query using the CalendarToken relation
 * @method     ChildUserQuery innerJoinWithCalendarToken() Adds a INNER JOIN clause and with to the query using the CalendarToken relation
 *
 * @method     ChildUserQuery leftJoinComment($relationAlias = null) Adds a LEFT JOIN clause to the query using the Comment relation
 * @method     ChildUserQuery rightJoinComment($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Comment relation
 * @method     ChildUserQuery innerJoinComment($relationAlias = null) Adds a INNER JOIN clause to the query using the Comment relation
 *
 * @method     ChildUserQuery joinWithComment($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Comment relation
 *
 * @method     ChildUserQuery leftJoinWithComment() Adds a LEFT JOIN clause and with to the query using the Comment relation
 * @method     ChildUserQuery rightJoinWithComment() Adds a RIGHT JOIN clause and with to the query using the Comment relation
 * @method     ChildUserQuery innerJoinWithComment() Adds a INNER JOIN clause and with to the query using the Comment relation
 *
 * @method     ChildUserQuery leftJoinEvent($relationAlias = null) Adds a LEFT JOIN clause to the query using the Event relation
 * @method     ChildUserQuery rightJoinEvent($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Event relation
 * @method     ChildUserQuery innerJoinEvent($relationAlias = null) Adds a INNER JOIN clause to the query using the Event relation
 *
 * @method     ChildUserQuery joinWithEvent($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Event relation
 *
 * @method     ChildUserQuery leftJoinWithEvent() Adds a LEFT JOIN clause and with to the query using the Event relation
 * @method     ChildUserQuery rightJoinWithEvent() Adds a RIGHT JOIN clause and with to the query using the Event relation
 * @method     ChildUserQuery innerJoinWithEvent() Adds a INNER JOIN clause and with to the query using the Event relation
 *
 * @method     ChildUserQuery leftJoinAvailability($relationAlias = null) Adds a LEFT JOIN clause to the query using the Availability relation
 * @method     ChildUserQuery rightJoinAvailability($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Availability relation
 * @method     ChildUserQuery innerJoinAvailability($relationAlias = null) Adds a INNER JOIN clause to the query using the Availability relation
 *
 * @method     ChildUserQuery joinWithAvailability($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Availability relation
 *
 * @method     ChildUserQuery leftJoinWithAvailability() Adds a LEFT JOIN clause and with to the query using the Availability relation
 * @method     ChildUserQuery rightJoinWithAvailability() Adds a RIGHT JOIN clause and with to the query using the Availability relation
 * @method     ChildUserQuery innerJoinWithAvailability() Adds a INNER JOIN clause and with to the query using the Availability relation
 *
 * @method     ChildUserQuery leftJoinNotification($relationAlias = null) Adds a LEFT JOIN clause to the query using the Notification relation
 * @method     ChildUserQuery rightJoinNotification($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Notification relation
 * @method     ChildUserQuery innerJoinNotification($relationAlias = null) Adds a INNER JOIN clause to the query using the Notification relation
 *
 * @method     ChildUserQuery joinWithNotification($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Notification relation
 *
 * @method     ChildUserQuery leftJoinWithNotification() Adds a LEFT JOIN clause and with to the query using the Notification relation
 * @method     ChildUserQuery rightJoinWithNotification() Adds a RIGHT JOIN clause and with to the query using the Notification relation
 * @method     ChildUserQuery innerJoinWithNotification() Adds a INNER JOIN clause and with to the query using the Notification relation
 *
 * @method     ChildUserQuery leftJoinSocialAuth($relationAlias = null) Adds a LEFT JOIN clause to the query using the SocialAuth relation
 * @method     ChildUserQuery rightJoinSocialAuth($relationAlias = null) Adds a RIGHT JOIN clause to the query using the SocialAuth relation
 * @method     ChildUserQuery innerJoinSocialAuth($relationAlias = null) Adds a INNER JOIN clause to the query using the SocialAuth relation
 *
 * @method     ChildUserQuery joinWithSocialAuth($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the SocialAuth relation
 *
 * @method     ChildUserQuery leftJoinWithSocialAuth() Adds a LEFT JOIN clause and with to the query using the SocialAuth relation
 * @method     ChildUserQuery rightJoinWithSocialAuth() Adds a RIGHT JOIN clause and with to the query using the SocialAuth relation
 * @method     ChildUserQuery innerJoinWithSocialAuth() Adds a INNER JOIN clause and with to the query using the SocialAuth relation
 *
 * @method     ChildUserQuery leftJoinStatistic($relationAlias = null) Adds a LEFT JOIN clause to the query using the Statistic relation
 * @method     ChildUserQuery rightJoinStatistic($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Statistic relation
 * @method     ChildUserQuery innerJoinStatistic($relationAlias = null) Adds a INNER JOIN clause to the query using the Statistic relation
 *
 * @method     ChildUserQuery joinWithStatistic($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Statistic relation
 *
 * @method     ChildUserQuery leftJoinWithStatistic() Adds a LEFT JOIN clause and with to the query using the Statistic relation
 * @method     ChildUserQuery rightJoinWithStatistic() Adds a RIGHT JOIN clause and with to the query using the Statistic relation
 * @method     ChildUserQuery innerJoinWithStatistic() Adds a INNER JOIN clause and with to the query using the Statistic relation
 *
 * @method     ChildUserQuery leftJoinSwap($relationAlias = null) Adds a LEFT JOIN clause to the query using the Swap relation
 * @method     ChildUserQuery rightJoinSwap($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Swap relation
 * @method     ChildUserQuery innerJoinSwap($relationAlias = null) Adds a INNER JOIN clause to the query using the Swap relation
 *
 * @method     ChildUserQuery joinWithSwap($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Swap relation
 *
 * @method     ChildUserQuery leftJoinWithSwap() Adds a LEFT JOIN clause and with to the query using the Swap relation
 * @method     ChildUserQuery rightJoinWithSwap() Adds a RIGHT JOIN clause and with to the query using the Swap relation
 * @method     ChildUserQuery innerJoinWithSwap() Adds a INNER JOIN clause and with to the query using the Swap relation
 *
 * @method     ChildUserQuery leftJoinUserRole($relationAlias = null) Adds a LEFT JOIN clause to the query using the UserRole relation
 * @method     ChildUserQuery rightJoinUserRole($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UserRole relation
 * @method     ChildUserQuery innerJoinUserRole($relationAlias = null) Adds a INNER JOIN clause to the query using the UserRole relation
 *
 * @method     ChildUserQuery joinWithUserRole($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the UserRole relation
 *
 * @method     ChildUserQuery leftJoinWithUserRole() Adds a LEFT JOIN clause and with to the query using the UserRole relation
 * @method     ChildUserQuery rightJoinWithUserRole() Adds a RIGHT JOIN clause and with to the query using the UserRole relation
 * @method     ChildUserQuery innerJoinWithUserRole() Adds a INNER JOIN clause and with to the query using the UserRole relation
 *
 * @method     ChildUserQuery leftJoinUserPermission($relationAlias = null) Adds a LEFT JOIN clause to the query using the UserPermission relation
 * @method     ChildUserQuery rightJoinUserPermission($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UserPermission relation
 * @method     ChildUserQuery innerJoinUserPermission($relationAlias = null) Adds a INNER JOIN clause to the query using the UserPermission relation
 *
 * @method     ChildUserQuery joinWithUserPermission($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the UserPermission relation
 *
 * @method     ChildUserQuery leftJoinWithUserPermission() Adds a LEFT JOIN clause and with to the query using the UserPermission relation
 * @method     ChildUserQuery rightJoinWithUserPermission() Adds a RIGHT JOIN clause and with to the query using the UserPermission relation
 * @method     ChildUserQuery innerJoinWithUserPermission() Adds a INNER JOIN clause and with to the query using the UserPermission relation
 *
 * @method     \TechWilk\Rota\CalendarTokenQuery|\TechWilk\Rota\CommentQuery|\TechWilk\Rota\EventQuery|\TechWilk\Rota\AvailabilityQuery|\TechWilk\Rota\NotificationQuery|\TechWilk\Rota\SocialAuthQuery|\TechWilk\Rota\StatisticQuery|\TechWilk\Rota\SwapQuery|\TechWilk\Rota\UserRoleQuery|\TechWilk\Rota\UserPermissionQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildUser findOne(ConnectionInterface $con = null) Return the first ChildUser matching the query
 * @method     ChildUser findOneOrCreate(ConnectionInterface $con = null) Return the first ChildUser matching the query, or a new ChildUser object populated from the query conditions when no match is found
 *
 * @method     ChildUser findOneById(int $id) Return the first ChildUser filtered by the id column
 * @method     ChildUser findOneByFirstName(string $firstName) Return the first ChildUser filtered by the firstName column
 * @method     ChildUser findOneByLastName(string $lastName) Return the first ChildUser filtered by the lastName column
 * @method     ChildUser findOneByUsername(string $username) Return the first ChildUser filtered by the username column
 * @method     ChildUser findOneByPassword(string $password) Return the first ChildUser filtered by the password column
 * @method     ChildUser findOneByIsAdmin(string $isAdmin) Return the first ChildUser filtered by the isAdmin column
 * @method     ChildUser findOneByEmail(\TechWilk\Rota\EmailAddress $email) Return the first ChildUser filtered by the email column
 * @method     ChildUser findOneByMobile(string $mobile) Return the first ChildUser filtered by the mobile column
 * @method     ChildUser findOneByIsOverviewRecipient(string $isOverviewRecipient) Return the first ChildUser filtered by the isOverviewRecipient column
 * @method     ChildUser findOneByRecieveReminderEmails(boolean $recieveReminderEmails) Return the first ChildUser filtered by the recieveReminderEmails column
 * @method     ChildUser findOneByIsBandAdmin(string $isBandAdmin) Return the first ChildUser filtered by the isBandAdmin column
 * @method     ChildUser findOneByIsEventEditor(string $isEventEditor) Return the first ChildUser filtered by the isEventEditor column
 * @method     ChildUser findOneByLastLogin(string $lastLogin) Return the first ChildUser filtered by the lastLogin column
 * @method     ChildUser findOneByPasswordChanged(string $passwordChanged) Return the first ChildUser filtered by the passwordChanged column
 * @method     ChildUser findOneByCreated(string $created) Return the first ChildUser filtered by the created column
 * @method     ChildUser findOneByUpdated(string $updated) Return the first ChildUser filtered by the updated column *

 * @method     ChildUser requirePk($key, ConnectionInterface $con = null) Return the ChildUser by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOne(ConnectionInterface $con = null) Return the first ChildUser matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildUser requireOneById(int $id) Return the first ChildUser filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByFirstName(string $firstName) Return the first ChildUser filtered by the firstName column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByLastName(string $lastName) Return the first ChildUser filtered by the lastName column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByUsername(string $username) Return the first ChildUser filtered by the username column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByPassword(string $password) Return the first ChildUser filtered by the password column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByIsAdmin(string $isAdmin) Return the first ChildUser filtered by the isAdmin column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByEmail(\TechWilk\Rota\EmailAddress $email) Return the first ChildUser filtered by the email column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByMobile(string $mobile) Return the first ChildUser filtered by the mobile column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByIsOverviewRecipient(string $isOverviewRecipient) Return the first ChildUser filtered by the isOverviewRecipient column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByRecieveReminderEmails(boolean $recieveReminderEmails) Return the first ChildUser filtered by the recieveReminderEmails column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByIsBandAdmin(string $isBandAdmin) Return the first ChildUser filtered by the isBandAdmin column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByIsEventEditor(string $isEventEditor) Return the first ChildUser filtered by the isEventEditor column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByLastLogin(string $lastLogin) Return the first ChildUser filtered by the lastLogin column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByPasswordChanged(string $passwordChanged) Return the first ChildUser filtered by the passwordChanged column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByCreated(string $created) Return the first ChildUser filtered by the created column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByUpdated(string $updated) Return the first ChildUser filtered by the updated column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildUser[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildUser objects based on current ModelCriteria
 * @method     ChildUser[]|ObjectCollection findById(int $id) Return ChildUser objects filtered by the id column
 * @method     ChildUser[]|ObjectCollection findByFirstName(string $firstName) Return ChildUser objects filtered by the firstName column
 * @method     ChildUser[]|ObjectCollection findByLastName(string $lastName) Return ChildUser objects filtered by the lastName column
 * @method     ChildUser[]|ObjectCollection findByUsername(string $username) Return ChildUser objects filtered by the username column
 * @method     ChildUser[]|ObjectCollection findByPassword(string $password) Return ChildUser objects filtered by the password column
 * @method     ChildUser[]|ObjectCollection findByIsAdmin(string $isAdmin) Return ChildUser objects filtered by the isAdmin column
 * @method     ChildUser[]|ObjectCollection findByEmail(\TechWilk\Rota\EmailAddress $email) Return ChildUser objects filtered by the email column
 * @method     ChildUser[]|ObjectCollection findByMobile(string $mobile) Return ChildUser objects filtered by the mobile column
 * @method     ChildUser[]|ObjectCollection findByIsOverviewRecipient(string $isOverviewRecipient) Return ChildUser objects filtered by the isOverviewRecipient column
 * @method     ChildUser[]|ObjectCollection findByRecieveReminderEmails(boolean $recieveReminderEmails) Return ChildUser objects filtered by the recieveReminderEmails column
 * @method     ChildUser[]|ObjectCollection findByIsBandAdmin(string $isBandAdmin) Return ChildUser objects filtered by the isBandAdmin column
 * @method     ChildUser[]|ObjectCollection findByIsEventEditor(string $isEventEditor) Return ChildUser objects filtered by the isEventEditor column
 * @method     ChildUser[]|ObjectCollection findByLastLogin(string $lastLogin) Return ChildUser objects filtered by the lastLogin column
 * @method     ChildUser[]|ObjectCollection findByPasswordChanged(string $passwordChanged) Return ChildUser objects filtered by the passwordChanged column
 * @method     ChildUser[]|ObjectCollection findByCreated(string $created) Return ChildUser objects filtered by the created column
 * @method     ChildUser[]|ObjectCollection findByUpdated(string $updated) Return ChildUser objects filtered by the updated column
 * @method     ChildUser[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class UserQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \TechWilk\Rota\Base\UserQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\TechWilk\\Rota\\User', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildUserQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildUserQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildUserQuery) {
            return $criteria;
        }
        $query = new ChildUserQuery();
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
     * @return ChildUser|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(UserTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = UserTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildUser A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, firstName, lastName, username, password, isAdmin, email, mobile, isOverviewRecipient, recieveReminderEmails, isBandAdmin, isEventEditor, lastLogin, passwordChanged, created, updated FROM users WHERE id = :p0';
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
            /** @var ChildUser $obj */
            $obj = new ChildUser();
            $obj->hydrate($row);
            UserTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildUser|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        return $this->addUsingAlias(UserTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        return $this->addUsingAlias(UserTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(UserTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(UserTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_ID, $id, $comparison);
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
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByFirstName($firstName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($firstName)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_FIRSTNAME, $firstName, $comparison);
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
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByLastName($lastName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($lastName)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_LASTNAME, $lastName, $comparison);
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
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByUsername($username = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($username)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_USERNAME, $username, $comparison);
    }

    /**
     * Filter the query on the password column
     *
     * Example usage:
     * <code>
     * $query->filterByPassword('fooValue');   // WHERE password = 'fooValue'
     * $query->filterByPassword('%fooValue%', Criteria::LIKE); // WHERE password LIKE '%fooValue%'
     * </code>
     *
     * @param     string $password The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByPassword($password = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($password)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_PASSWORD, $password, $comparison);
    }

    /**
     * Filter the query on the isAdmin column
     *
     * Example usage:
     * <code>
     * $query->filterByIsAdmin('fooValue');   // WHERE isAdmin = 'fooValue'
     * $query->filterByIsAdmin('%fooValue%', Criteria::LIKE); // WHERE isAdmin LIKE '%fooValue%'
     * </code>
     *
     * @param     string $isAdmin The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByIsAdmin($isAdmin = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($isAdmin)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_ISADMIN, $isAdmin, $comparison);
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
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByEmail($email = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($email)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_EMAIL, $email, $comparison);
    }

    /**
     * Filter the query on the mobile column
     *
     * Example usage:
     * <code>
     * $query->filterByMobile('fooValue');   // WHERE mobile = 'fooValue'
     * $query->filterByMobile('%fooValue%', Criteria::LIKE); // WHERE mobile LIKE '%fooValue%'
     * </code>
     *
     * @param     string $mobile The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByMobile($mobile = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($mobile)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_MOBILE, $mobile, $comparison);
    }

    /**
     * Filter the query on the isOverviewRecipient column
     *
     * Example usage:
     * <code>
     * $query->filterByIsOverviewRecipient('fooValue');   // WHERE isOverviewRecipient = 'fooValue'
     * $query->filterByIsOverviewRecipient('%fooValue%', Criteria::LIKE); // WHERE isOverviewRecipient LIKE '%fooValue%'
     * </code>
     *
     * @param     string $isOverviewRecipient The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByIsOverviewRecipient($isOverviewRecipient = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($isOverviewRecipient)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_ISOVERVIEWRECIPIENT, $isOverviewRecipient, $comparison);
    }

    /**
     * Filter the query on the recieveReminderEmails column
     *
     * Example usage:
     * <code>
     * $query->filterByRecieveReminderEmails(true); // WHERE recieveReminderEmails = true
     * $query->filterByRecieveReminderEmails('yes'); // WHERE recieveReminderEmails = true
     * </code>
     *
     * @param     boolean|string $recieveReminderEmails The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByRecieveReminderEmails($recieveReminderEmails = null, $comparison = null)
    {
        if (is_string($recieveReminderEmails)) {
            $recieveReminderEmails = in_array(strtolower($recieveReminderEmails), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(UserTableMap::COL_RECIEVEREMINDEREMAILS, $recieveReminderEmails, $comparison);
    }

    /**
     * Filter the query on the isBandAdmin column
     *
     * Example usage:
     * <code>
     * $query->filterByIsBandAdmin('fooValue');   // WHERE isBandAdmin = 'fooValue'
     * $query->filterByIsBandAdmin('%fooValue%', Criteria::LIKE); // WHERE isBandAdmin LIKE '%fooValue%'
     * </code>
     *
     * @param     string $isBandAdmin The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByIsBandAdmin($isBandAdmin = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($isBandAdmin)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_ISBANDADMIN, $isBandAdmin, $comparison);
    }

    /**
     * Filter the query on the isEventEditor column
     *
     * Example usage:
     * <code>
     * $query->filterByIsEventEditor('fooValue');   // WHERE isEventEditor = 'fooValue'
     * $query->filterByIsEventEditor('%fooValue%', Criteria::LIKE); // WHERE isEventEditor LIKE '%fooValue%'
     * </code>
     *
     * @param     string $isEventEditor The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByIsEventEditor($isEventEditor = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($isEventEditor)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_ISEVENTEDITOR, $isEventEditor, $comparison);
    }

    /**
     * Filter the query on the lastLogin column
     *
     * Example usage:
     * <code>
     * $query->filterByLastLogin('2011-03-14'); // WHERE lastLogin = '2011-03-14'
     * $query->filterByLastLogin('now'); // WHERE lastLogin = '2011-03-14'
     * $query->filterByLastLogin(array('max' => 'yesterday')); // WHERE lastLogin > '2011-03-13'
     * </code>
     *
     * @param     mixed $lastLogin The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByLastLogin($lastLogin = null, $comparison = null)
    {
        if (is_array($lastLogin)) {
            $useMinMax = false;
            if (isset($lastLogin['min'])) {
                $this->addUsingAlias(UserTableMap::COL_LASTLOGIN, $lastLogin['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($lastLogin['max'])) {
                $this->addUsingAlias(UserTableMap::COL_LASTLOGIN, $lastLogin['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_LASTLOGIN, $lastLogin, $comparison);
    }

    /**
     * Filter the query on the passwordChanged column
     *
     * Example usage:
     * <code>
     * $query->filterByPasswordChanged('2011-03-14'); // WHERE passwordChanged = '2011-03-14'
     * $query->filterByPasswordChanged('now'); // WHERE passwordChanged = '2011-03-14'
     * $query->filterByPasswordChanged(array('max' => 'yesterday')); // WHERE passwordChanged > '2011-03-13'
     * </code>
     *
     * @param     mixed $passwordChanged The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByPasswordChanged($passwordChanged = null, $comparison = null)
    {
        if (is_array($passwordChanged)) {
            $useMinMax = false;
            if (isset($passwordChanged['min'])) {
                $this->addUsingAlias(UserTableMap::COL_PASSWORDCHANGED, $passwordChanged['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($passwordChanged['max'])) {
                $this->addUsingAlias(UserTableMap::COL_PASSWORDCHANGED, $passwordChanged['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_PASSWORDCHANGED, $passwordChanged, $comparison);
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
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByCreated($created = null, $comparison = null)
    {
        if (is_array($created)) {
            $useMinMax = false;
            if (isset($created['min'])) {
                $this->addUsingAlias(UserTableMap::COL_CREATED, $created['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($created['max'])) {
                $this->addUsingAlias(UserTableMap::COL_CREATED, $created['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_CREATED, $created, $comparison);
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
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByUpdated($updated = null, $comparison = null)
    {
        if (is_array($updated)) {
            $useMinMax = false;
            if (isset($updated['min'])) {
                $this->addUsingAlias(UserTableMap::COL_UPDATED, $updated['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updated['max'])) {
                $this->addUsingAlias(UserTableMap::COL_UPDATED, $updated['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_UPDATED, $updated, $comparison);
    }

    /**
     * Filter the query by a related \TechWilk\Rota\CalendarToken object
     *
     * @param \TechWilk\Rota\CalendarToken|ObjectCollection $calendarToken the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterByCalendarToken($calendarToken, $comparison = null)
    {
        if ($calendarToken instanceof \TechWilk\Rota\CalendarToken) {
            return $this
                ->addUsingAlias(UserTableMap::COL_ID, $calendarToken->getUserid(), $comparison);
        } elseif ($calendarToken instanceof ObjectCollection) {
            return $this
                ->useCalendarTokenQuery()
                ->filterByPrimaryKeys($calendarToken->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByCalendarToken() only accepts arguments of type \TechWilk\Rota\CalendarToken or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CalendarToken relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function joinCalendarToken($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CalendarToken');

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
            $this->addJoinObject($join, 'CalendarToken');
        }

        return $this;
    }

    /**
     * Use the CalendarToken relation CalendarToken object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \TechWilk\Rota\CalendarTokenQuery A secondary query class using the current class as primary query
     */
    public function useCalendarTokenQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinCalendarToken($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CalendarToken', '\TechWilk\Rota\CalendarTokenQuery');
    }

    /**
     * Filter the query by a related \TechWilk\Rota\Comment object
     *
     * @param \TechWilk\Rota\Comment|ObjectCollection $comment the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterByComment($comment, $comparison = null)
    {
        if ($comment instanceof \TechWilk\Rota\Comment) {
            return $this
                ->addUsingAlias(UserTableMap::COL_ID, $comment->getUserId(), $comparison);
        } elseif ($comment instanceof ObjectCollection) {
            return $this
                ->useCommentQuery()
                ->filterByPrimaryKeys($comment->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByComment() only accepts arguments of type \TechWilk\Rota\Comment or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Comment relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function joinComment($relationAlias = null, $joinType = Criteria::INNER_JOIN)
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
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
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
     * Filter the query by a related \TechWilk\Rota\Event object
     *
     * @param \TechWilk\Rota\Event|ObjectCollection $event the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterByEvent($event, $comparison = null)
    {
        if ($event instanceof \TechWilk\Rota\Event) {
            return $this
                ->addUsingAlias(UserTableMap::COL_ID, $event->getCreatedBy(), $comparison);
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
     * @return $this|ChildUserQuery The current query, for fluid interface
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
     * Filter the query by a related \TechWilk\Rota\Availability object
     *
     * @param \TechWilk\Rota\Availability|ObjectCollection $availability the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterByAvailability($availability, $comparison = null)
    {
        if ($availability instanceof \TechWilk\Rota\Availability) {
            return $this
                ->addUsingAlias(UserTableMap::COL_ID, $availability->getUserId(), $comparison);
        } elseif ($availability instanceof ObjectCollection) {
            return $this
                ->useAvailabilityQuery()
                ->filterByPrimaryKeys($availability->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByAvailability() only accepts arguments of type \TechWilk\Rota\Availability or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Availability relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function joinAvailability($relationAlias = null, $joinType = Criteria::INNER_JOIN)
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
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
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
     * Filter the query by a related \TechWilk\Rota\Notification object
     *
     * @param \TechWilk\Rota\Notification|ObjectCollection $notification the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterByNotification($notification, $comparison = null)
    {
        if ($notification instanceof \TechWilk\Rota\Notification) {
            return $this
                ->addUsingAlias(UserTableMap::COL_ID, $notification->getUserId(), $comparison);
        } elseif ($notification instanceof ObjectCollection) {
            return $this
                ->useNotificationQuery()
                ->filterByPrimaryKeys($notification->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByNotification() only accepts arguments of type \TechWilk\Rota\Notification or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Notification relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function joinNotification($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Notification');

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
            $this->addJoinObject($join, 'Notification');
        }

        return $this;
    }

    /**
     * Use the Notification relation Notification object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \TechWilk\Rota\NotificationQuery A secondary query class using the current class as primary query
     */
    public function useNotificationQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinNotification($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Notification', '\TechWilk\Rota\NotificationQuery');
    }

    /**
     * Filter the query by a related \TechWilk\Rota\SocialAuth object
     *
     * @param \TechWilk\Rota\SocialAuth|ObjectCollection $socialAuth the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterBySocialAuth($socialAuth, $comparison = null)
    {
        if ($socialAuth instanceof \TechWilk\Rota\SocialAuth) {
            return $this
                ->addUsingAlias(UserTableMap::COL_ID, $socialAuth->getUserId(), $comparison);
        } elseif ($socialAuth instanceof ObjectCollection) {
            return $this
                ->useSocialAuthQuery()
                ->filterByPrimaryKeys($socialAuth->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterBySocialAuth() only accepts arguments of type \TechWilk\Rota\SocialAuth or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the SocialAuth relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function joinSocialAuth($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('SocialAuth');

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
            $this->addJoinObject($join, 'SocialAuth');
        }

        return $this;
    }

    /**
     * Use the SocialAuth relation SocialAuth object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \TechWilk\Rota\SocialAuthQuery A secondary query class using the current class as primary query
     */
    public function useSocialAuthQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinSocialAuth($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'SocialAuth', '\TechWilk\Rota\SocialAuthQuery');
    }

    /**
     * Filter the query by a related \TechWilk\Rota\Statistic object
     *
     * @param \TechWilk\Rota\Statistic|ObjectCollection $statistic the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterByStatistic($statistic, $comparison = null)
    {
        if ($statistic instanceof \TechWilk\Rota\Statistic) {
            return $this
                ->addUsingAlias(UserTableMap::COL_ID, $statistic->getUserId(), $comparison);
        } elseif ($statistic instanceof ObjectCollection) {
            return $this
                ->useStatisticQuery()
                ->filterByPrimaryKeys($statistic->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByStatistic() only accepts arguments of type \TechWilk\Rota\Statistic or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Statistic relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function joinStatistic($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Statistic');

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
            $this->addJoinObject($join, 'Statistic');
        }

        return $this;
    }

    /**
     * Use the Statistic relation Statistic object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \TechWilk\Rota\StatisticQuery A secondary query class using the current class as primary query
     */
    public function useStatisticQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinStatistic($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Statistic', '\TechWilk\Rota\StatisticQuery');
    }

    /**
     * Filter the query by a related \TechWilk\Rota\Swap object
     *
     * @param \TechWilk\Rota\Swap|ObjectCollection $swap the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterBySwap($swap, $comparison = null)
    {
        if ($swap instanceof \TechWilk\Rota\Swap) {
            return $this
                ->addUsingAlias(UserTableMap::COL_ID, $swap->getRequestedBy(), $comparison);
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
     * @return $this|ChildUserQuery The current query, for fluid interface
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
     * Filter the query by a related \TechWilk\Rota\UserRole object
     *
     * @param \TechWilk\Rota\UserRole|ObjectCollection $userRole the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterByUserRole($userRole, $comparison = null)
    {
        if ($userRole instanceof \TechWilk\Rota\UserRole) {
            return $this
                ->addUsingAlias(UserTableMap::COL_ID, $userRole->getUserId(), $comparison);
        } elseif ($userRole instanceof ObjectCollection) {
            return $this
                ->useUserRoleQuery()
                ->filterByPrimaryKeys($userRole->getPrimaryKeys())
                ->endUse();
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
     * @return $this|ChildUserQuery The current query, for fluid interface
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
     * Filter the query by a related \TechWilk\Rota\UserPermission object
     *
     * @param \TechWilk\Rota\UserPermission|ObjectCollection $userPermission the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterByUserPermission($userPermission, $comparison = null)
    {
        if ($userPermission instanceof \TechWilk\Rota\UserPermission) {
            return $this
                ->addUsingAlias(UserTableMap::COL_ID, $userPermission->getUserId(), $comparison);
        } elseif ($userPermission instanceof ObjectCollection) {
            return $this
                ->useUserPermissionQuery()
                ->filterByPrimaryKeys($userPermission->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByUserPermission() only accepts arguments of type \TechWilk\Rota\UserPermission or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the UserPermission relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function joinUserPermission($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('UserPermission');

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
            $this->addJoinObject($join, 'UserPermission');
        }

        return $this;
    }

    /**
     * Use the UserPermission relation UserPermission object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \TechWilk\Rota\UserPermissionQuery A secondary query class using the current class as primary query
     */
    public function useUserPermissionQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinUserPermission($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'UserPermission', '\TechWilk\Rota\UserPermissionQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildUser $user Object to remove from the list of results
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function prune($user = null)
    {
        if ($user) {
            $this->addUsingAlias(UserTableMap::COL_ID, $user->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the users table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(UserTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            UserTableMap::clearInstancePool();
            UserTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(UserTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(UserTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            UserTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            UserTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     $this|ChildUserQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(UserTableMap::COL_UPDATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     $this|ChildUserQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(UserTableMap::COL_UPDATED);
    }

    /**
     * Order by update date asc
     *
     * @return     $this|ChildUserQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(UserTableMap::COL_UPDATED);
    }

    /**
     * Order by create date desc
     *
     * @return     $this|ChildUserQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(UserTableMap::COL_CREATED);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     $this|ChildUserQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(UserTableMap::COL_CREATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date asc
     *
     * @return     $this|ChildUserQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(UserTableMap::COL_CREATED);
    }
} // UserQuery
