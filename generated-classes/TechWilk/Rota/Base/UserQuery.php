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
use TechWilk\Rota\User as ChildUser;
use TechWilk\Rota\UserQuery as ChildUserQuery;
use TechWilk\Rota\Map\UserTableMap;

/**
 * Base class that represents a query for the `users` table.
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
 * @method     ChildUser|null findOne(?ConnectionInterface $con = null) Return the first ChildUser matching the query
 * @method     ChildUser findOneOrCreate(?ConnectionInterface $con = null) Return the first ChildUser matching the query, or a new ChildUser object populated from the query conditions when no match is found
 *
 * @method     ChildUser|null findOneById(int $id) Return the first ChildUser filtered by the id column
 * @method     ChildUser|null findOneByFirstName(string $firstName) Return the first ChildUser filtered by the firstName column
 * @method     ChildUser|null findOneByLastName(string $lastName) Return the first ChildUser filtered by the lastName column
 * @method     ChildUser|null findOneByUsername(string $username) Return the first ChildUser filtered by the username column
 * @method     ChildUser|null findOneByPassword(string $password) Return the first ChildUser filtered by the password column
 * @method     ChildUser|null findOneByIsAdmin(string $isAdmin) Return the first ChildUser filtered by the isAdmin column
 * @method     ChildUser|null findOneByEmail(\TechWilk\Rota\EmailAddress $email) Return the first ChildUser filtered by the email column
 * @method     ChildUser|null findOneByMobile(string $mobile) Return the first ChildUser filtered by the mobile column
 * @method     ChildUser|null findOneByIsOverviewRecipient(string $isOverviewRecipient) Return the first ChildUser filtered by the isOverviewRecipient column
 * @method     ChildUser|null findOneByRecieveReminderEmails(boolean $recieveReminderEmails) Return the first ChildUser filtered by the recieveReminderEmails column
 * @method     ChildUser|null findOneByIsBandAdmin(string $isBandAdmin) Return the first ChildUser filtered by the isBandAdmin column
 * @method     ChildUser|null findOneByIsEventEditor(string $isEventEditor) Return the first ChildUser filtered by the isEventEditor column
 * @method     ChildUser|null findOneByLastLogin(string $lastLogin) Return the first ChildUser filtered by the lastLogin column
 * @method     ChildUser|null findOneByPasswordChanged(string $passwordChanged) Return the first ChildUser filtered by the passwordChanged column
 * @method     ChildUser|null findOneByCreated(string $created) Return the first ChildUser filtered by the created column
 * @method     ChildUser|null findOneByUpdated(string $updated) Return the first ChildUser filtered by the updated column
 *
 * @method     ChildUser requirePk($key, ?ConnectionInterface $con = null) Return the ChildUser by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOne(?ConnectionInterface $con = null) Return the first ChildUser matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
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
 * @method     ChildUser[]|Collection find(?ConnectionInterface $con = null) Return ChildUser objects based on current ModelCriteria
 * @psalm-method Collection&\Traversable<ChildUser> find(?ConnectionInterface $con = null) Return ChildUser objects based on current ModelCriteria
 *
 * @method     ChildUser[]|Collection findById(int|array<int> $id) Return ChildUser objects filtered by the id column
 * @psalm-method Collection&\Traversable<ChildUser> findById(int|array<int> $id) Return ChildUser objects filtered by the id column
 * @method     ChildUser[]|Collection findByFirstName(string|array<string> $firstName) Return ChildUser objects filtered by the firstName column
 * @psalm-method Collection&\Traversable<ChildUser> findByFirstName(string|array<string> $firstName) Return ChildUser objects filtered by the firstName column
 * @method     ChildUser[]|Collection findByLastName(string|array<string> $lastName) Return ChildUser objects filtered by the lastName column
 * @psalm-method Collection&\Traversable<ChildUser> findByLastName(string|array<string> $lastName) Return ChildUser objects filtered by the lastName column
 * @method     ChildUser[]|Collection findByUsername(string|array<string> $username) Return ChildUser objects filtered by the username column
 * @psalm-method Collection&\Traversable<ChildUser> findByUsername(string|array<string> $username) Return ChildUser objects filtered by the username column
 * @method     ChildUser[]|Collection findByPassword(string|array<string> $password) Return ChildUser objects filtered by the password column
 * @psalm-method Collection&\Traversable<ChildUser> findByPassword(string|array<string> $password) Return ChildUser objects filtered by the password column
 * @method     ChildUser[]|Collection findByIsAdmin(string|array<string> $isAdmin) Return ChildUser objects filtered by the isAdmin column
 * @psalm-method Collection&\Traversable<ChildUser> findByIsAdmin(string|array<string> $isAdmin) Return ChildUser objects filtered by the isAdmin column
 * @method     ChildUser[]|Collection findByEmail(\TechWilk\Rota\EmailAddress|array<\TechWilk\Rota\EmailAddress> $email) Return ChildUser objects filtered by the email column
 * @psalm-method Collection&\Traversable<ChildUser> findByEmail(\TechWilk\Rota\EmailAddress|array<\TechWilk\Rota\EmailAddress> $email) Return ChildUser objects filtered by the email column
 * @method     ChildUser[]|Collection findByMobile(string|array<string> $mobile) Return ChildUser objects filtered by the mobile column
 * @psalm-method Collection&\Traversable<ChildUser> findByMobile(string|array<string> $mobile) Return ChildUser objects filtered by the mobile column
 * @method     ChildUser[]|Collection findByIsOverviewRecipient(string|array<string> $isOverviewRecipient) Return ChildUser objects filtered by the isOverviewRecipient column
 * @psalm-method Collection&\Traversable<ChildUser> findByIsOverviewRecipient(string|array<string> $isOverviewRecipient) Return ChildUser objects filtered by the isOverviewRecipient column
 * @method     ChildUser[]|Collection findByRecieveReminderEmails(boolean|array<boolean> $recieveReminderEmails) Return ChildUser objects filtered by the recieveReminderEmails column
 * @psalm-method Collection&\Traversable<ChildUser> findByRecieveReminderEmails(boolean|array<boolean> $recieveReminderEmails) Return ChildUser objects filtered by the recieveReminderEmails column
 * @method     ChildUser[]|Collection findByIsBandAdmin(string|array<string> $isBandAdmin) Return ChildUser objects filtered by the isBandAdmin column
 * @psalm-method Collection&\Traversable<ChildUser> findByIsBandAdmin(string|array<string> $isBandAdmin) Return ChildUser objects filtered by the isBandAdmin column
 * @method     ChildUser[]|Collection findByIsEventEditor(string|array<string> $isEventEditor) Return ChildUser objects filtered by the isEventEditor column
 * @psalm-method Collection&\Traversable<ChildUser> findByIsEventEditor(string|array<string> $isEventEditor) Return ChildUser objects filtered by the isEventEditor column
 * @method     ChildUser[]|Collection findByLastLogin(string|array<string> $lastLogin) Return ChildUser objects filtered by the lastLogin column
 * @psalm-method Collection&\Traversable<ChildUser> findByLastLogin(string|array<string> $lastLogin) Return ChildUser objects filtered by the lastLogin column
 * @method     ChildUser[]|Collection findByPasswordChanged(string|array<string> $passwordChanged) Return ChildUser objects filtered by the passwordChanged column
 * @psalm-method Collection&\Traversable<ChildUser> findByPasswordChanged(string|array<string> $passwordChanged) Return ChildUser objects filtered by the passwordChanged column
 * @method     ChildUser[]|Collection findByCreated(string|array<string> $created) Return ChildUser objects filtered by the created column
 * @psalm-method Collection&\Traversable<ChildUser> findByCreated(string|array<string> $created) Return ChildUser objects filtered by the created column
 * @method     ChildUser[]|Collection findByUpdated(string|array<string> $updated) Return ChildUser objects filtered by the updated column
 * @psalm-method Collection&\Traversable<ChildUser> findByUpdated(string|array<string> $updated) Return ChildUser objects filtered by the updated column
 *
 * @method     ChildUser[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 * @psalm-method \Propel\Runtime\Util\PropelModelPager&\Traversable<ChildUser> paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 */
abstract class UserQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \TechWilk\Rota\Base\UserQuery object.
     *
     * @param string $dbName The database name
     * @param string $modelName The phpName of a model, e.g. 'Book'
     * @param string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\TechWilk\\Rota\\User', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildUserQuery object.
     *
     * @param string $modelAlias The alias of a model in the query
     * @param Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildUserQuery
     */
    public static function create(?string $modelAlias = null, ?Criteria $criteria = null): Criteria
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
    public function findPk($key, ?ConnectionInterface $con = null)
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
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con A connection object
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
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con A connection object
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

        $this->addUsingAlias(UserTableMap::COL_ID, $key, Criteria::EQUAL);

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

        $this->addUsingAlias(UserTableMap::COL_ID, $keys, Criteria::IN);

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

        $this->addUsingAlias(UserTableMap::COL_ID, $id, $comparison);

        return $this;
    }

    /**
     * Filter the query on the firstName column
     *
     * Example usage:
     * <code>
     * $query->filterByFirstName('fooValue');   // WHERE firstName = 'fooValue'
     * $query->filterByFirstName('%fooValue%', Criteria::LIKE); // WHERE firstName LIKE '%fooValue%'
     * $query->filterByFirstName(['foo', 'bar']); // WHERE firstName IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $firstName The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByFirstName($firstName = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($firstName)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(UserTableMap::COL_FIRSTNAME, $firstName, $comparison);

        return $this;
    }

    /**
     * Filter the query on the lastName column
     *
     * Example usage:
     * <code>
     * $query->filterByLastName('fooValue');   // WHERE lastName = 'fooValue'
     * $query->filterByLastName('%fooValue%', Criteria::LIKE); // WHERE lastName LIKE '%fooValue%'
     * $query->filterByLastName(['foo', 'bar']); // WHERE lastName IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $lastName The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByLastName($lastName = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($lastName)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(UserTableMap::COL_LASTNAME, $lastName, $comparison);

        return $this;
    }

    /**
     * Filter the query on the username column
     *
     * Example usage:
     * <code>
     * $query->filterByUsername('fooValue');   // WHERE username = 'fooValue'
     * $query->filterByUsername('%fooValue%', Criteria::LIKE); // WHERE username LIKE '%fooValue%'
     * $query->filterByUsername(['foo', 'bar']); // WHERE username IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $username The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByUsername($username = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($username)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(UserTableMap::COL_USERNAME, $username, $comparison);

        return $this;
    }

    /**
     * Filter the query on the password column
     *
     * Example usage:
     * <code>
     * $query->filterByPassword('fooValue');   // WHERE password = 'fooValue'
     * $query->filterByPassword('%fooValue%', Criteria::LIKE); // WHERE password LIKE '%fooValue%'
     * $query->filterByPassword(['foo', 'bar']); // WHERE password IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $password The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByPassword($password = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($password)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(UserTableMap::COL_PASSWORD, $password, $comparison);

        return $this;
    }

    /**
     * Filter the query on the isAdmin column
     *
     * Example usage:
     * <code>
     * $query->filterByIsAdmin('fooValue');   // WHERE isAdmin = 'fooValue'
     * $query->filterByIsAdmin('%fooValue%', Criteria::LIKE); // WHERE isAdmin LIKE '%fooValue%'
     * $query->filterByIsAdmin(['foo', 'bar']); // WHERE isAdmin IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $isAdmin The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByIsAdmin($isAdmin = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($isAdmin)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(UserTableMap::COL_ISADMIN, $isAdmin, $comparison);

        return $this;
    }

    /**
     * Filter the query on the email column
     *
     * Example usage:
     * <code>
     * $query->filterByEmail('fooValue');   // WHERE email = 'fooValue'
     * $query->filterByEmail('%fooValue%', Criteria::LIKE); // WHERE email LIKE '%fooValue%'
     * $query->filterByEmail(['foo', 'bar']); // WHERE email IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $email The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByEmail($email = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($email)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(UserTableMap::COL_EMAIL, $email, $comparison);

        return $this;
    }

    /**
     * Filter the query on the mobile column
     *
     * Example usage:
     * <code>
     * $query->filterByMobile('fooValue');   // WHERE mobile = 'fooValue'
     * $query->filterByMobile('%fooValue%', Criteria::LIKE); // WHERE mobile LIKE '%fooValue%'
     * $query->filterByMobile(['foo', 'bar']); // WHERE mobile IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $mobile The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByMobile($mobile = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($mobile)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(UserTableMap::COL_MOBILE, $mobile, $comparison);

        return $this;
    }

    /**
     * Filter the query on the isOverviewRecipient column
     *
     * Example usage:
     * <code>
     * $query->filterByIsOverviewRecipient('fooValue');   // WHERE isOverviewRecipient = 'fooValue'
     * $query->filterByIsOverviewRecipient('%fooValue%', Criteria::LIKE); // WHERE isOverviewRecipient LIKE '%fooValue%'
     * $query->filterByIsOverviewRecipient(['foo', 'bar']); // WHERE isOverviewRecipient IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $isOverviewRecipient The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByIsOverviewRecipient($isOverviewRecipient = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($isOverviewRecipient)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(UserTableMap::COL_ISOVERVIEWRECIPIENT, $isOverviewRecipient, $comparison);

        return $this;
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
     * @param bool|string $recieveReminderEmails The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByRecieveReminderEmails($recieveReminderEmails = null, ?string $comparison = null)
    {
        if (is_string($recieveReminderEmails)) {
            $recieveReminderEmails = in_array(strtolower($recieveReminderEmails), array('false', 'off', '-', 'no', 'n', '0', ''), true) ? false : true;
        }

        $this->addUsingAlias(UserTableMap::COL_RECIEVEREMINDEREMAILS, $recieveReminderEmails, $comparison);

        return $this;
    }

    /**
     * Filter the query on the isBandAdmin column
     *
     * Example usage:
     * <code>
     * $query->filterByIsBandAdmin('fooValue');   // WHERE isBandAdmin = 'fooValue'
     * $query->filterByIsBandAdmin('%fooValue%', Criteria::LIKE); // WHERE isBandAdmin LIKE '%fooValue%'
     * $query->filterByIsBandAdmin(['foo', 'bar']); // WHERE isBandAdmin IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $isBandAdmin The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByIsBandAdmin($isBandAdmin = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($isBandAdmin)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(UserTableMap::COL_ISBANDADMIN, $isBandAdmin, $comparison);

        return $this;
    }

    /**
     * Filter the query on the isEventEditor column
     *
     * Example usage:
     * <code>
     * $query->filterByIsEventEditor('fooValue');   // WHERE isEventEditor = 'fooValue'
     * $query->filterByIsEventEditor('%fooValue%', Criteria::LIKE); // WHERE isEventEditor LIKE '%fooValue%'
     * $query->filterByIsEventEditor(['foo', 'bar']); // WHERE isEventEditor IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $isEventEditor The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByIsEventEditor($isEventEditor = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($isEventEditor)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(UserTableMap::COL_ISEVENTEDITOR, $isEventEditor, $comparison);

        return $this;
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
     * @param mixed $lastLogin The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByLastLogin($lastLogin = null, ?string $comparison = null)
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

        $this->addUsingAlias(UserTableMap::COL_LASTLOGIN, $lastLogin, $comparison);

        return $this;
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
     * @param mixed $passwordChanged The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByPasswordChanged($passwordChanged = null, ?string $comparison = null)
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

        $this->addUsingAlias(UserTableMap::COL_PASSWORDCHANGED, $passwordChanged, $comparison);

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

        $this->addUsingAlias(UserTableMap::COL_CREATED, $created, $comparison);

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

        $this->addUsingAlias(UserTableMap::COL_UPDATED, $updated, $comparison);

        return $this;
    }

    /**
     * Filter the query by a related \TechWilk\Rota\CalendarToken object
     *
     * @param \TechWilk\Rota\CalendarToken|ObjectCollection $calendarToken the related object to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByCalendarToken($calendarToken, ?string $comparison = null)
    {
        if ($calendarToken instanceof \TechWilk\Rota\CalendarToken) {
            $this
                ->addUsingAlias(UserTableMap::COL_ID, $calendarToken->getUserid(), $comparison);

            return $this;
        } elseif ($calendarToken instanceof ObjectCollection) {
            $this
                ->useCalendarTokenQuery()
                ->filterByPrimaryKeys($calendarToken->getPrimaryKeys())
                ->endUse();

            return $this;
        } else {
            throw new PropelException('filterByCalendarToken() only accepts arguments of type \TechWilk\Rota\CalendarToken or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CalendarToken relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinCalendarToken(?string $relationAlias = null, ?string $joinType = Criteria::INNER_JOIN)
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
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
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
     * Use the CalendarToken relation CalendarToken object
     *
     * @param callable(\TechWilk\Rota\CalendarTokenQuery):\TechWilk\Rota\CalendarTokenQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withCalendarTokenQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::INNER_JOIN
    ) {
        $relatedQuery = $this->useCalendarTokenQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to CalendarToken table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \TechWilk\Rota\CalendarTokenQuery The inner query object of the EXISTS statement
     */
    public function useCalendarTokenExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \TechWilk\Rota\CalendarTokenQuery */
        $q = $this->useExistsQuery('CalendarToken', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to CalendarToken table for a NOT EXISTS query.
     *
     * @see useCalendarTokenExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \TechWilk\Rota\CalendarTokenQuery The inner query object of the NOT EXISTS statement
     */
    public function useCalendarTokenNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \TechWilk\Rota\CalendarTokenQuery */
        $q = $this->useExistsQuery('CalendarToken', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to CalendarToken table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \TechWilk\Rota\CalendarTokenQuery The inner query object of the IN statement
     */
    public function useInCalendarTokenQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \TechWilk\Rota\CalendarTokenQuery */
        $q = $this->useInQuery('CalendarToken', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to CalendarToken table for a NOT IN query.
     *
     * @see useCalendarTokenInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \TechWilk\Rota\CalendarTokenQuery The inner query object of the NOT IN statement
     */
    public function useNotInCalendarTokenQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \TechWilk\Rota\CalendarTokenQuery */
        $q = $this->useInQuery('CalendarToken', $modelAlias, $queryClass, 'NOT IN');
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
                ->addUsingAlias(UserTableMap::COL_ID, $comment->getUserId(), $comparison);

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
     * Filter the query by a related \TechWilk\Rota\Event object
     *
     * @param \TechWilk\Rota\Event|ObjectCollection $event the related object to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByEvent($event, ?string $comparison = null)
    {
        if ($event instanceof \TechWilk\Rota\Event) {
            $this
                ->addUsingAlias(UserTableMap::COL_ID, $event->getCreatedBy(), $comparison);

            return $this;
        } elseif ($event instanceof ObjectCollection) {
            $this
                ->useEventQuery()
                ->filterByPrimaryKeys($event->getPrimaryKeys())
                ->endUse();

            return $this;
        } else {
            throw new PropelException('filterByEvent() only accepts arguments of type \TechWilk\Rota\Event or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Event relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinEvent(?string $relationAlias = null, ?string $joinType = Criteria::INNER_JOIN)
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
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
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
     * Use the Event relation Event object
     *
     * @param callable(\TechWilk\Rota\EventQuery):\TechWilk\Rota\EventQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withEventQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::INNER_JOIN
    ) {
        $relatedQuery = $this->useEventQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to Event table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \TechWilk\Rota\EventQuery The inner query object of the EXISTS statement
     */
    public function useEventExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \TechWilk\Rota\EventQuery */
        $q = $this->useExistsQuery('Event', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to Event table for a NOT EXISTS query.
     *
     * @see useEventExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \TechWilk\Rota\EventQuery The inner query object of the NOT EXISTS statement
     */
    public function useEventNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \TechWilk\Rota\EventQuery */
        $q = $this->useExistsQuery('Event', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to Event table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \TechWilk\Rota\EventQuery The inner query object of the IN statement
     */
    public function useInEventQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \TechWilk\Rota\EventQuery */
        $q = $this->useInQuery('Event', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to Event table for a NOT IN query.
     *
     * @see useEventInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \TechWilk\Rota\EventQuery The inner query object of the NOT IN statement
     */
    public function useNotInEventQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \TechWilk\Rota\EventQuery */
        $q = $this->useInQuery('Event', $modelAlias, $queryClass, 'NOT IN');
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
                ->addUsingAlias(UserTableMap::COL_ID, $availability->getUserId(), $comparison);

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
     * Filter the query by a related \TechWilk\Rota\Notification object
     *
     * @param \TechWilk\Rota\Notification|ObjectCollection $notification the related object to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByNotification($notification, ?string $comparison = null)
    {
        if ($notification instanceof \TechWilk\Rota\Notification) {
            $this
                ->addUsingAlias(UserTableMap::COL_ID, $notification->getUserId(), $comparison);

            return $this;
        } elseif ($notification instanceof ObjectCollection) {
            $this
                ->useNotificationQuery()
                ->filterByPrimaryKeys($notification->getPrimaryKeys())
                ->endUse();

            return $this;
        } else {
            throw new PropelException('filterByNotification() only accepts arguments of type \TechWilk\Rota\Notification or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Notification relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinNotification(?string $relationAlias = null, ?string $joinType = Criteria::INNER_JOIN)
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
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
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
     * Use the Notification relation Notification object
     *
     * @param callable(\TechWilk\Rota\NotificationQuery):\TechWilk\Rota\NotificationQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withNotificationQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::INNER_JOIN
    ) {
        $relatedQuery = $this->useNotificationQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to Notification table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \TechWilk\Rota\NotificationQuery The inner query object of the EXISTS statement
     */
    public function useNotificationExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \TechWilk\Rota\NotificationQuery */
        $q = $this->useExistsQuery('Notification', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to Notification table for a NOT EXISTS query.
     *
     * @see useNotificationExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \TechWilk\Rota\NotificationQuery The inner query object of the NOT EXISTS statement
     */
    public function useNotificationNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \TechWilk\Rota\NotificationQuery */
        $q = $this->useExistsQuery('Notification', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to Notification table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \TechWilk\Rota\NotificationQuery The inner query object of the IN statement
     */
    public function useInNotificationQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \TechWilk\Rota\NotificationQuery */
        $q = $this->useInQuery('Notification', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to Notification table for a NOT IN query.
     *
     * @see useNotificationInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \TechWilk\Rota\NotificationQuery The inner query object of the NOT IN statement
     */
    public function useNotInNotificationQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \TechWilk\Rota\NotificationQuery */
        $q = $this->useInQuery('Notification', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Filter the query by a related \TechWilk\Rota\SocialAuth object
     *
     * @param \TechWilk\Rota\SocialAuth|ObjectCollection $socialAuth the related object to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterBySocialAuth($socialAuth, ?string $comparison = null)
    {
        if ($socialAuth instanceof \TechWilk\Rota\SocialAuth) {
            $this
                ->addUsingAlias(UserTableMap::COL_ID, $socialAuth->getUserId(), $comparison);

            return $this;
        } elseif ($socialAuth instanceof ObjectCollection) {
            $this
                ->useSocialAuthQuery()
                ->filterByPrimaryKeys($socialAuth->getPrimaryKeys())
                ->endUse();

            return $this;
        } else {
            throw new PropelException('filterBySocialAuth() only accepts arguments of type \TechWilk\Rota\SocialAuth or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the SocialAuth relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinSocialAuth(?string $relationAlias = null, ?string $joinType = Criteria::INNER_JOIN)
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
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
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
     * Use the SocialAuth relation SocialAuth object
     *
     * @param callable(\TechWilk\Rota\SocialAuthQuery):\TechWilk\Rota\SocialAuthQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withSocialAuthQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::INNER_JOIN
    ) {
        $relatedQuery = $this->useSocialAuthQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to SocialAuth table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \TechWilk\Rota\SocialAuthQuery The inner query object of the EXISTS statement
     */
    public function useSocialAuthExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \TechWilk\Rota\SocialAuthQuery */
        $q = $this->useExistsQuery('SocialAuth', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to SocialAuth table for a NOT EXISTS query.
     *
     * @see useSocialAuthExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \TechWilk\Rota\SocialAuthQuery The inner query object of the NOT EXISTS statement
     */
    public function useSocialAuthNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \TechWilk\Rota\SocialAuthQuery */
        $q = $this->useExistsQuery('SocialAuth', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to SocialAuth table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \TechWilk\Rota\SocialAuthQuery The inner query object of the IN statement
     */
    public function useInSocialAuthQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \TechWilk\Rota\SocialAuthQuery */
        $q = $this->useInQuery('SocialAuth', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to SocialAuth table for a NOT IN query.
     *
     * @see useSocialAuthInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \TechWilk\Rota\SocialAuthQuery The inner query object of the NOT IN statement
     */
    public function useNotInSocialAuthQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \TechWilk\Rota\SocialAuthQuery */
        $q = $this->useInQuery('SocialAuth', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Filter the query by a related \TechWilk\Rota\Statistic object
     *
     * @param \TechWilk\Rota\Statistic|ObjectCollection $statistic the related object to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByStatistic($statistic, ?string $comparison = null)
    {
        if ($statistic instanceof \TechWilk\Rota\Statistic) {
            $this
                ->addUsingAlias(UserTableMap::COL_ID, $statistic->getUserId(), $comparison);

            return $this;
        } elseif ($statistic instanceof ObjectCollection) {
            $this
                ->useStatisticQuery()
                ->filterByPrimaryKeys($statistic->getPrimaryKeys())
                ->endUse();

            return $this;
        } else {
            throw new PropelException('filterByStatistic() only accepts arguments of type \TechWilk\Rota\Statistic or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Statistic relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinStatistic(?string $relationAlias = null, ?string $joinType = Criteria::LEFT_JOIN)
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
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
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
     * Use the Statistic relation Statistic object
     *
     * @param callable(\TechWilk\Rota\StatisticQuery):\TechWilk\Rota\StatisticQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withStatisticQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::LEFT_JOIN
    ) {
        $relatedQuery = $this->useStatisticQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to Statistic table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \TechWilk\Rota\StatisticQuery The inner query object of the EXISTS statement
     */
    public function useStatisticExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \TechWilk\Rota\StatisticQuery */
        $q = $this->useExistsQuery('Statistic', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to Statistic table for a NOT EXISTS query.
     *
     * @see useStatisticExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \TechWilk\Rota\StatisticQuery The inner query object of the NOT EXISTS statement
     */
    public function useStatisticNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \TechWilk\Rota\StatisticQuery */
        $q = $this->useExistsQuery('Statistic', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to Statistic table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \TechWilk\Rota\StatisticQuery The inner query object of the IN statement
     */
    public function useInStatisticQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \TechWilk\Rota\StatisticQuery */
        $q = $this->useInQuery('Statistic', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to Statistic table for a NOT IN query.
     *
     * @see useStatisticInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \TechWilk\Rota\StatisticQuery The inner query object of the NOT IN statement
     */
    public function useNotInStatisticQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \TechWilk\Rota\StatisticQuery */
        $q = $this->useInQuery('Statistic', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Filter the query by a related \TechWilk\Rota\Swap object
     *
     * @param \TechWilk\Rota\Swap|ObjectCollection $swap the related object to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterBySwap($swap, ?string $comparison = null)
    {
        if ($swap instanceof \TechWilk\Rota\Swap) {
            $this
                ->addUsingAlias(UserTableMap::COL_ID, $swap->getRequestedBy(), $comparison);

            return $this;
        } elseif ($swap instanceof ObjectCollection) {
            $this
                ->useSwapQuery()
                ->filterByPrimaryKeys($swap->getPrimaryKeys())
                ->endUse();

            return $this;
        } else {
            throw new PropelException('filterBySwap() only accepts arguments of type \TechWilk\Rota\Swap or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Swap relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinSwap(?string $relationAlias = null, ?string $joinType = Criteria::INNER_JOIN)
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
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
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
     * Use the Swap relation Swap object
     *
     * @param callable(\TechWilk\Rota\SwapQuery):\TechWilk\Rota\SwapQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withSwapQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::INNER_JOIN
    ) {
        $relatedQuery = $this->useSwapQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to Swap table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \TechWilk\Rota\SwapQuery The inner query object of the EXISTS statement
     */
    public function useSwapExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \TechWilk\Rota\SwapQuery */
        $q = $this->useExistsQuery('Swap', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to Swap table for a NOT EXISTS query.
     *
     * @see useSwapExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \TechWilk\Rota\SwapQuery The inner query object of the NOT EXISTS statement
     */
    public function useSwapNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \TechWilk\Rota\SwapQuery */
        $q = $this->useExistsQuery('Swap', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to Swap table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \TechWilk\Rota\SwapQuery The inner query object of the IN statement
     */
    public function useInSwapQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \TechWilk\Rota\SwapQuery */
        $q = $this->useInQuery('Swap', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to Swap table for a NOT IN query.
     *
     * @see useSwapInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \TechWilk\Rota\SwapQuery The inner query object of the NOT IN statement
     */
    public function useNotInSwapQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \TechWilk\Rota\SwapQuery */
        $q = $this->useInQuery('Swap', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Filter the query by a related \TechWilk\Rota\UserRole object
     *
     * @param \TechWilk\Rota\UserRole|ObjectCollection $userRole the related object to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByUserRole($userRole, ?string $comparison = null)
    {
        if ($userRole instanceof \TechWilk\Rota\UserRole) {
            $this
                ->addUsingAlias(UserTableMap::COL_ID, $userRole->getUserId(), $comparison);

            return $this;
        } elseif ($userRole instanceof ObjectCollection) {
            $this
                ->useUserRoleQuery()
                ->filterByPrimaryKeys($userRole->getPrimaryKeys())
                ->endUse();

            return $this;
        } else {
            throw new PropelException('filterByUserRole() only accepts arguments of type \TechWilk\Rota\UserRole or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the UserRole relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinUserRole(?string $relationAlias = null, ?string $joinType = Criteria::INNER_JOIN)
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
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
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
     * Use the UserRole relation UserRole object
     *
     * @param callable(\TechWilk\Rota\UserRoleQuery):\TechWilk\Rota\UserRoleQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withUserRoleQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::INNER_JOIN
    ) {
        $relatedQuery = $this->useUserRoleQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to UserRole table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \TechWilk\Rota\UserRoleQuery The inner query object of the EXISTS statement
     */
    public function useUserRoleExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \TechWilk\Rota\UserRoleQuery */
        $q = $this->useExistsQuery('UserRole', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to UserRole table for a NOT EXISTS query.
     *
     * @see useUserRoleExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \TechWilk\Rota\UserRoleQuery The inner query object of the NOT EXISTS statement
     */
    public function useUserRoleNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \TechWilk\Rota\UserRoleQuery */
        $q = $this->useExistsQuery('UserRole', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to UserRole table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \TechWilk\Rota\UserRoleQuery The inner query object of the IN statement
     */
    public function useInUserRoleQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \TechWilk\Rota\UserRoleQuery */
        $q = $this->useInQuery('UserRole', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to UserRole table for a NOT IN query.
     *
     * @see useUserRoleInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \TechWilk\Rota\UserRoleQuery The inner query object of the NOT IN statement
     */
    public function useNotInUserRoleQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \TechWilk\Rota\UserRoleQuery */
        $q = $this->useInQuery('UserRole', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Filter the query by a related \TechWilk\Rota\UserPermission object
     *
     * @param \TechWilk\Rota\UserPermission|ObjectCollection $userPermission the related object to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByUserPermission($userPermission, ?string $comparison = null)
    {
        if ($userPermission instanceof \TechWilk\Rota\UserPermission) {
            $this
                ->addUsingAlias(UserTableMap::COL_ID, $userPermission->getUserId(), $comparison);

            return $this;
        } elseif ($userPermission instanceof ObjectCollection) {
            $this
                ->useUserPermissionQuery()
                ->filterByPrimaryKeys($userPermission->getPrimaryKeys())
                ->endUse();

            return $this;
        } else {
            throw new PropelException('filterByUserPermission() only accepts arguments of type \TechWilk\Rota\UserPermission or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the UserPermission relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinUserPermission(?string $relationAlias = null, ?string $joinType = Criteria::INNER_JOIN)
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
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
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
     * Use the UserPermission relation UserPermission object
     *
     * @param callable(\TechWilk\Rota\UserPermissionQuery):\TechWilk\Rota\UserPermissionQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withUserPermissionQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::INNER_JOIN
    ) {
        $relatedQuery = $this->useUserPermissionQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to UserPermission table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \TechWilk\Rota\UserPermissionQuery The inner query object of the EXISTS statement
     */
    public function useUserPermissionExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \TechWilk\Rota\UserPermissionQuery */
        $q = $this->useExistsQuery('UserPermission', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to UserPermission table for a NOT EXISTS query.
     *
     * @see useUserPermissionExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \TechWilk\Rota\UserPermissionQuery The inner query object of the NOT EXISTS statement
     */
    public function useUserPermissionNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \TechWilk\Rota\UserPermissionQuery */
        $q = $this->useExistsQuery('UserPermission', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to UserPermission table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \TechWilk\Rota\UserPermissionQuery The inner query object of the IN statement
     */
    public function useInUserPermissionQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \TechWilk\Rota\UserPermissionQuery */
        $q = $this->useInQuery('UserPermission', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to UserPermission table for a NOT IN query.
     *
     * @see useUserPermissionInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \TechWilk\Rota\UserPermissionQuery The inner query object of the NOT IN statement
     */
    public function useNotInUserPermissionQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \TechWilk\Rota\UserPermissionQuery */
        $q = $this->useInQuery('UserPermission', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Exclude object from result
     *
     * @param ChildUser $user Object to remove from the list of results
     *
     * @return $this The current query, for fluid interface
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
    public function doDeleteAll(?ConnectionInterface $con = null): int
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
     * @return int The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public function delete(?ConnectionInterface $con = null): int
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
     * @param int $nbDays Maximum age of the latest update in days
     *
     * @return $this The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        $this->addUsingAlias(UserTableMap::COL_UPDATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);

        return $this;
    }

    /**
     * Order by update date desc
     *
     * @return $this The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        $this->addDescendingOrderByColumn(UserTableMap::COL_UPDATED);

        return $this;
    }

    /**
     * Order by update date asc
     *
     * @return $this The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        $this->addAscendingOrderByColumn(UserTableMap::COL_UPDATED);

        return $this;
    }

    /**
     * Order by create date desc
     *
     * @return $this The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        $this->addDescendingOrderByColumn(UserTableMap::COL_CREATED);

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
        $this->addUsingAlias(UserTableMap::COL_CREATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);

        return $this;
    }

    /**
     * Order by create date asc
     *
     * @return $this The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        $this->addAscendingOrderByColumn(UserTableMap::COL_CREATED);

        return $this;
    }

}
