<?php

namespace TechWilk\Rota\Base;

use \Exception;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\LogicException;
use Propel\Runtime\Exception\PropelException;
use TechWilk\Rota\Settings as ChildSettings;
use TechWilk\Rota\SettingsQuery as ChildSettingsQuery;
use TechWilk\Rota\Map\SettingsTableMap;

/**
 * Base class that represents a query for the 'cr_settings' table.
 *
 *
 *
 * @method     ChildSettingsQuery orderBySiteUrl($order = Criteria::ASC) Order by the siteurl column
 * @method     ChildSettingsQuery orderByOwner($order = Criteria::ASC) Order by the owner column
 * @method     ChildSettingsQuery orderByNotificationEmail($order = Criteria::ASC) Order by the notificationemail column
 * @method     ChildSettingsQuery orderByAdminEmailAddress($order = Criteria::ASC) Order by the adminemailaddress column
 * @method     ChildSettingsQuery orderByNoRehearsalEmail($order = Criteria::ASC) Order by the norehearsalemail column
 * @method     ChildSettingsQuery orderByYesRehearsal($order = Criteria::ASC) Order by the yesrehearsal column
 * @method     ChildSettingsQuery orderByNewUserMessage($order = Criteria::ASC) Order by the newusermessage column
 * @method     ChildSettingsQuery orderByVersion($order = Criteria::ASC) Order by the version column
 * @method     ChildSettingsQuery orderByLangLocale($order = Criteria::ASC) Order by the lang_locale column
 * @method     ChildSettingsQuery orderByEventSortingLatest($order = Criteria::ASC) Order by the event_sorting_latest column
 * @method     ChildSettingsQuery orderBySnapshotShowTwoMonth($order = Criteria::ASC) Order by the snapshot_show_two_month column
 * @method     ChildSettingsQuery orderBySnapshotReduceSkillsByGroup($order = Criteria::ASC) Order by the snapshot_reduce_skills_by_group column
 * @method     ChildSettingsQuery orderByLoggedInShowSnapshotButton($order = Criteria::ASC) Order by the logged_in_show_snapshot_button column
 * @method     ChildSettingsQuery orderByTimeFormatLong($order = Criteria::ASC) Order by the time_format_long column
 * @method     ChildSettingsQuery orderByTimeFormatNormal($order = Criteria::ASC) Order by the time_format_normal column
 * @method     ChildSettingsQuery orderByTimeFormatShort($order = Criteria::ASC) Order by the time_format_short column
 * @method     ChildSettingsQuery orderByTimeOnlyFormat($order = Criteria::ASC) Order by the time_only_format column
 * @method     ChildSettingsQuery orderByDateOnlyFormat($order = Criteria::ASC) Order by the date_only_format column
 * @method     ChildSettingsQuery orderByDayOnlyFormat($order = Criteria::ASC) Order by the day_only_format column
 * @method     ChildSettingsQuery orderByUsersStartWithMyEvents($order = Criteria::ASC) Order by the users_start_with_myevents column
 * @method     ChildSettingsQuery orderByTimeZone($order = Criteria::ASC) Order by the time_zone column
 * @method     ChildSettingsQuery orderByGoogleGroupCalendar($order = Criteria::ASC) Order by the google_group_calendar column
 * @method     ChildSettingsQuery orderByOverviewEmail($order = Criteria::ASC) Order by the overviewemail column
 * @method     ChildSettingsQuery orderByGroupSortingName($order = Criteria::ASC) Order by the group_sorting_name column
 * @method     ChildSettingsQuery orderByDebugMode($order = Criteria::ASC) Order by the debug_mode column
 * @method     ChildSettingsQuery orderByDaysToAlert($order = Criteria::ASC) Order by the days_to_alert column
 * @method     ChildSettingsQuery orderByToken($order = Criteria::ASC) Order by the token column
 * @method     ChildSettingsQuery orderBySkin($order = Criteria::ASC) Order by the skin column
 *
 * @method     ChildSettingsQuery groupBySiteUrl() Group by the siteurl column
 * @method     ChildSettingsQuery groupByOwner() Group by the owner column
 * @method     ChildSettingsQuery groupByNotificationEmail() Group by the notificationemail column
 * @method     ChildSettingsQuery groupByAdminEmailAddress() Group by the adminemailaddress column
 * @method     ChildSettingsQuery groupByNoRehearsalEmail() Group by the norehearsalemail column
 * @method     ChildSettingsQuery groupByYesRehearsal() Group by the yesrehearsal column
 * @method     ChildSettingsQuery groupByNewUserMessage() Group by the newusermessage column
 * @method     ChildSettingsQuery groupByVersion() Group by the version column
 * @method     ChildSettingsQuery groupByLangLocale() Group by the lang_locale column
 * @method     ChildSettingsQuery groupByEventSortingLatest() Group by the event_sorting_latest column
 * @method     ChildSettingsQuery groupBySnapshotShowTwoMonth() Group by the snapshot_show_two_month column
 * @method     ChildSettingsQuery groupBySnapshotReduceSkillsByGroup() Group by the snapshot_reduce_skills_by_group column
 * @method     ChildSettingsQuery groupByLoggedInShowSnapshotButton() Group by the logged_in_show_snapshot_button column
 * @method     ChildSettingsQuery groupByTimeFormatLong() Group by the time_format_long column
 * @method     ChildSettingsQuery groupByTimeFormatNormal() Group by the time_format_normal column
 * @method     ChildSettingsQuery groupByTimeFormatShort() Group by the time_format_short column
 * @method     ChildSettingsQuery groupByTimeOnlyFormat() Group by the time_only_format column
 * @method     ChildSettingsQuery groupByDateOnlyFormat() Group by the date_only_format column
 * @method     ChildSettingsQuery groupByDayOnlyFormat() Group by the day_only_format column
 * @method     ChildSettingsQuery groupByUsersStartWithMyEvents() Group by the users_start_with_myevents column
 * @method     ChildSettingsQuery groupByTimeZone() Group by the time_zone column
 * @method     ChildSettingsQuery groupByGoogleGroupCalendar() Group by the google_group_calendar column
 * @method     ChildSettingsQuery groupByOverviewEmail() Group by the overviewemail column
 * @method     ChildSettingsQuery groupByGroupSortingName() Group by the group_sorting_name column
 * @method     ChildSettingsQuery groupByDebugMode() Group by the debug_mode column
 * @method     ChildSettingsQuery groupByDaysToAlert() Group by the days_to_alert column
 * @method     ChildSettingsQuery groupByToken() Group by the token column
 * @method     ChildSettingsQuery groupBySkin() Group by the skin column
 *
 * @method     ChildSettingsQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildSettingsQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildSettingsQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildSettingsQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildSettingsQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildSettingsQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildSettings findOne(ConnectionInterface $con = null) Return the first ChildSettings matching the query
 * @method     ChildSettings findOneOrCreate(ConnectionInterface $con = null) Return the first ChildSettings matching the query, or a new ChildSettings object populated from the query conditions when no match is found
 *
 * @method     ChildSettings findOneBySiteUrl(string $siteurl) Return the first ChildSettings filtered by the siteurl column
 * @method     ChildSettings findOneByOwner(string $owner) Return the first ChildSettings filtered by the owner column
 * @method     ChildSettings findOneByNotificationEmail(string $notificationemail) Return the first ChildSettings filtered by the notificationemail column
 * @method     ChildSettings findOneByAdminEmailAddress(string $adminemailaddress) Return the first ChildSettings filtered by the adminemailaddress column
 * @method     ChildSettings findOneByNoRehearsalEmail(string $norehearsalemail) Return the first ChildSettings filtered by the norehearsalemail column
 * @method     ChildSettings findOneByYesRehearsal(string $yesrehearsal) Return the first ChildSettings filtered by the yesrehearsal column
 * @method     ChildSettings findOneByNewUserMessage(string $newusermessage) Return the first ChildSettings filtered by the newusermessage column
 * @method     ChildSettings findOneByVersion(string $version) Return the first ChildSettings filtered by the version column
 * @method     ChildSettings findOneByLangLocale(string $lang_locale) Return the first ChildSettings filtered by the lang_locale column
 * @method     ChildSettings findOneByEventSortingLatest(int $event_sorting_latest) Return the first ChildSettings filtered by the event_sorting_latest column
 * @method     ChildSettings findOneBySnapshotShowTwoMonth(int $snapshot_show_two_month) Return the first ChildSettings filtered by the snapshot_show_two_month column
 * @method     ChildSettings findOneBySnapshotReduceSkillsByGroup(int $snapshot_reduce_skills_by_group) Return the first ChildSettings filtered by the snapshot_reduce_skills_by_group column
 * @method     ChildSettings findOneByLoggedInShowSnapshotButton(int $logged_in_show_snapshot_button) Return the first ChildSettings filtered by the logged_in_show_snapshot_button column
 * @method     ChildSettings findOneByTimeFormatLong(string $time_format_long) Return the first ChildSettings filtered by the time_format_long column
 * @method     ChildSettings findOneByTimeFormatNormal(string $time_format_normal) Return the first ChildSettings filtered by the time_format_normal column
 * @method     ChildSettings findOneByTimeFormatShort(string $time_format_short) Return the first ChildSettings filtered by the time_format_short column
 * @method     ChildSettings findOneByTimeOnlyFormat(string $time_only_format) Return the first ChildSettings filtered by the time_only_format column
 * @method     ChildSettings findOneByDateOnlyFormat(string $date_only_format) Return the first ChildSettings filtered by the date_only_format column
 * @method     ChildSettings findOneByDayOnlyFormat(string $day_only_format) Return the first ChildSettings filtered by the day_only_format column
 * @method     ChildSettings findOneByUsersStartWithMyEvents(int $users_start_with_myevents) Return the first ChildSettings filtered by the users_start_with_myevents column
 * @method     ChildSettings findOneByTimeZone(string $time_zone) Return the first ChildSettings filtered by the time_zone column
 * @method     ChildSettings findOneByGoogleGroupCalendar(string $google_group_calendar) Return the first ChildSettings filtered by the google_group_calendar column
 * @method     ChildSettings findOneByOverviewEmail(string $overviewemail) Return the first ChildSettings filtered by the overviewemail column
 * @method     ChildSettings findOneByGroupSortingName(int $group_sorting_name) Return the first ChildSettings filtered by the group_sorting_name column
 * @method     ChildSettings findOneByDebugMode(int $debug_mode) Return the first ChildSettings filtered by the debug_mode column
 * @method     ChildSettings findOneByDaysToAlert(int $days_to_alert) Return the first ChildSettings filtered by the days_to_alert column
 * @method     ChildSettings findOneByToken(string $token) Return the first ChildSettings filtered by the token column
 * @method     ChildSettings findOneBySkin(string $skin) Return the first ChildSettings filtered by the skin column *

 * @method     ChildSettings requirePk($key, ConnectionInterface $con = null) Return the ChildSettings by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSettings requireOne(ConnectionInterface $con = null) Return the first ChildSettings matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildSettings requireOneBySiteUrl(string $siteurl) Return the first ChildSettings filtered by the siteurl column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSettings requireOneByOwner(string $owner) Return the first ChildSettings filtered by the owner column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSettings requireOneByNotificationEmail(string $notificationemail) Return the first ChildSettings filtered by the notificationemail column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSettings requireOneByAdminEmailAddress(string $adminemailaddress) Return the first ChildSettings filtered by the adminemailaddress column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSettings requireOneByNoRehearsalEmail(string $norehearsalemail) Return the first ChildSettings filtered by the norehearsalemail column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSettings requireOneByYesRehearsal(string $yesrehearsal) Return the first ChildSettings filtered by the yesrehearsal column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSettings requireOneByNewUserMessage(string $newusermessage) Return the first ChildSettings filtered by the newusermessage column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSettings requireOneByVersion(string $version) Return the first ChildSettings filtered by the version column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSettings requireOneByLangLocale(string $lang_locale) Return the first ChildSettings filtered by the lang_locale column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSettings requireOneByEventSortingLatest(int $event_sorting_latest) Return the first ChildSettings filtered by the event_sorting_latest column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSettings requireOneBySnapshotShowTwoMonth(int $snapshot_show_two_month) Return the first ChildSettings filtered by the snapshot_show_two_month column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSettings requireOneBySnapshotReduceSkillsByGroup(int $snapshot_reduce_skills_by_group) Return the first ChildSettings filtered by the snapshot_reduce_skills_by_group column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSettings requireOneByLoggedInShowSnapshotButton(int $logged_in_show_snapshot_button) Return the first ChildSettings filtered by the logged_in_show_snapshot_button column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSettings requireOneByTimeFormatLong(string $time_format_long) Return the first ChildSettings filtered by the time_format_long column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSettings requireOneByTimeFormatNormal(string $time_format_normal) Return the first ChildSettings filtered by the time_format_normal column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSettings requireOneByTimeFormatShort(string $time_format_short) Return the first ChildSettings filtered by the time_format_short column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSettings requireOneByTimeOnlyFormat(string $time_only_format) Return the first ChildSettings filtered by the time_only_format column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSettings requireOneByDateOnlyFormat(string $date_only_format) Return the first ChildSettings filtered by the date_only_format column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSettings requireOneByDayOnlyFormat(string $day_only_format) Return the first ChildSettings filtered by the day_only_format column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSettings requireOneByUsersStartWithMyEvents(int $users_start_with_myevents) Return the first ChildSettings filtered by the users_start_with_myevents column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSettings requireOneByTimeZone(string $time_zone) Return the first ChildSettings filtered by the time_zone column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSettings requireOneByGoogleGroupCalendar(string $google_group_calendar) Return the first ChildSettings filtered by the google_group_calendar column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSettings requireOneByOverviewEmail(string $overviewemail) Return the first ChildSettings filtered by the overviewemail column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSettings requireOneByGroupSortingName(int $group_sorting_name) Return the first ChildSettings filtered by the group_sorting_name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSettings requireOneByDebugMode(int $debug_mode) Return the first ChildSettings filtered by the debug_mode column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSettings requireOneByDaysToAlert(int $days_to_alert) Return the first ChildSettings filtered by the days_to_alert column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSettings requireOneByToken(string $token) Return the first ChildSettings filtered by the token column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSettings requireOneBySkin(string $skin) Return the first ChildSettings filtered by the skin column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildSettings[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildSettings objects based on current ModelCriteria
 * @method     ChildSettings[]|ObjectCollection findBySiteUrl(string $siteurl) Return ChildSettings objects filtered by the siteurl column
 * @method     ChildSettings[]|ObjectCollection findByOwner(string $owner) Return ChildSettings objects filtered by the owner column
 * @method     ChildSettings[]|ObjectCollection findByNotificationEmail(string $notificationemail) Return ChildSettings objects filtered by the notificationemail column
 * @method     ChildSettings[]|ObjectCollection findByAdminEmailAddress(string $adminemailaddress) Return ChildSettings objects filtered by the adminemailaddress column
 * @method     ChildSettings[]|ObjectCollection findByNoRehearsalEmail(string $norehearsalemail) Return ChildSettings objects filtered by the norehearsalemail column
 * @method     ChildSettings[]|ObjectCollection findByYesRehearsal(string $yesrehearsal) Return ChildSettings objects filtered by the yesrehearsal column
 * @method     ChildSettings[]|ObjectCollection findByNewUserMessage(string $newusermessage) Return ChildSettings objects filtered by the newusermessage column
 * @method     ChildSettings[]|ObjectCollection findByVersion(string $version) Return ChildSettings objects filtered by the version column
 * @method     ChildSettings[]|ObjectCollection findByLangLocale(string $lang_locale) Return ChildSettings objects filtered by the lang_locale column
 * @method     ChildSettings[]|ObjectCollection findByEventSortingLatest(int $event_sorting_latest) Return ChildSettings objects filtered by the event_sorting_latest column
 * @method     ChildSettings[]|ObjectCollection findBySnapshotShowTwoMonth(int $snapshot_show_two_month) Return ChildSettings objects filtered by the snapshot_show_two_month column
 * @method     ChildSettings[]|ObjectCollection findBySnapshotReduceSkillsByGroup(int $snapshot_reduce_skills_by_group) Return ChildSettings objects filtered by the snapshot_reduce_skills_by_group column
 * @method     ChildSettings[]|ObjectCollection findByLoggedInShowSnapshotButton(int $logged_in_show_snapshot_button) Return ChildSettings objects filtered by the logged_in_show_snapshot_button column
 * @method     ChildSettings[]|ObjectCollection findByTimeFormatLong(string $time_format_long) Return ChildSettings objects filtered by the time_format_long column
 * @method     ChildSettings[]|ObjectCollection findByTimeFormatNormal(string $time_format_normal) Return ChildSettings objects filtered by the time_format_normal column
 * @method     ChildSettings[]|ObjectCollection findByTimeFormatShort(string $time_format_short) Return ChildSettings objects filtered by the time_format_short column
 * @method     ChildSettings[]|ObjectCollection findByTimeOnlyFormat(string $time_only_format) Return ChildSettings objects filtered by the time_only_format column
 * @method     ChildSettings[]|ObjectCollection findByDateOnlyFormat(string $date_only_format) Return ChildSettings objects filtered by the date_only_format column
 * @method     ChildSettings[]|ObjectCollection findByDayOnlyFormat(string $day_only_format) Return ChildSettings objects filtered by the day_only_format column
 * @method     ChildSettings[]|ObjectCollection findByUsersStartWithMyEvents(int $users_start_with_myevents) Return ChildSettings objects filtered by the users_start_with_myevents column
 * @method     ChildSettings[]|ObjectCollection findByTimeZone(string $time_zone) Return ChildSettings objects filtered by the time_zone column
 * @method     ChildSettings[]|ObjectCollection findByGoogleGroupCalendar(string $google_group_calendar) Return ChildSettings objects filtered by the google_group_calendar column
 * @method     ChildSettings[]|ObjectCollection findByOverviewEmail(string $overviewemail) Return ChildSettings objects filtered by the overviewemail column
 * @method     ChildSettings[]|ObjectCollection findByGroupSortingName(int $group_sorting_name) Return ChildSettings objects filtered by the group_sorting_name column
 * @method     ChildSettings[]|ObjectCollection findByDebugMode(int $debug_mode) Return ChildSettings objects filtered by the debug_mode column
 * @method     ChildSettings[]|ObjectCollection findByDaysToAlert(int $days_to_alert) Return ChildSettings objects filtered by the days_to_alert column
 * @method     ChildSettings[]|ObjectCollection findByToken(string $token) Return ChildSettings objects filtered by the token column
 * @method     ChildSettings[]|ObjectCollection findBySkin(string $skin) Return ChildSettings objects filtered by the skin column
 * @method     ChildSettings[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class SettingsQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \TechWilk\Rota\Base\SettingsQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\TechWilk\\Rota\\Settings', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildSettingsQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildSettingsQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildSettingsQuery) {
            return $criteria;
        }
        $query = new ChildSettingsQuery();
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
     * @return ChildSettings|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        throw new LogicException('The Settings object has no primary key');
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
        throw new LogicException('The Settings object has no primary key');
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return $this|ChildSettingsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        throw new LogicException('The Settings object has no primary key');
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildSettingsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        throw new LogicException('The Settings object has no primary key');
    }

    /**
     * Filter the query on the siteurl column
     *
     * Example usage:
     * <code>
     * $query->filterBySiteUrl('fooValue');   // WHERE siteurl = 'fooValue'
     * $query->filterBySiteUrl('%fooValue%', Criteria::LIKE); // WHERE siteurl LIKE '%fooValue%'
     * </code>
     *
     * @param     string $siteUrl The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSettingsQuery The current query, for fluid interface
     */
    public function filterBySiteUrl($siteUrl = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($siteUrl)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SettingsTableMap::COL_SITEURL, $siteUrl, $comparison);
    }

    /**
     * Filter the query on the owner column
     *
     * Example usage:
     * <code>
     * $query->filterByOwner('fooValue');   // WHERE owner = 'fooValue'
     * $query->filterByOwner('%fooValue%', Criteria::LIKE); // WHERE owner LIKE '%fooValue%'
     * </code>
     *
     * @param     string $owner The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSettingsQuery The current query, for fluid interface
     */
    public function filterByOwner($owner = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($owner)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SettingsTableMap::COL_OWNER, $owner, $comparison);
    }

    /**
     * Filter the query on the notificationemail column
     *
     * Example usage:
     * <code>
     * $query->filterByNotificationEmail('fooValue');   // WHERE notificationemail = 'fooValue'
     * $query->filterByNotificationEmail('%fooValue%', Criteria::LIKE); // WHERE notificationemail LIKE '%fooValue%'
     * </code>
     *
     * @param     string $notificationEmail The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSettingsQuery The current query, for fluid interface
     */
    public function filterByNotificationEmail($notificationEmail = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($notificationEmail)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SettingsTableMap::COL_NOTIFICATIONEMAIL, $notificationEmail, $comparison);
    }

    /**
     * Filter the query on the adminemailaddress column
     *
     * Example usage:
     * <code>
     * $query->filterByAdminEmailAddress('fooValue');   // WHERE adminemailaddress = 'fooValue'
     * $query->filterByAdminEmailAddress('%fooValue%', Criteria::LIKE); // WHERE adminemailaddress LIKE '%fooValue%'
     * </code>
     *
     * @param     string $adminEmailAddress The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSettingsQuery The current query, for fluid interface
     */
    public function filterByAdminEmailAddress($adminEmailAddress = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($adminEmailAddress)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SettingsTableMap::COL_ADMINEMAILADDRESS, $adminEmailAddress, $comparison);
    }

    /**
     * Filter the query on the norehearsalemail column
     *
     * Example usage:
     * <code>
     * $query->filterByNoRehearsalEmail('fooValue');   // WHERE norehearsalemail = 'fooValue'
     * $query->filterByNoRehearsalEmail('%fooValue%', Criteria::LIKE); // WHERE norehearsalemail LIKE '%fooValue%'
     * </code>
     *
     * @param     string $noRehearsalEmail The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSettingsQuery The current query, for fluid interface
     */
    public function filterByNoRehearsalEmail($noRehearsalEmail = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($noRehearsalEmail)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SettingsTableMap::COL_NOREHEARSALEMAIL, $noRehearsalEmail, $comparison);
    }

    /**
     * Filter the query on the yesrehearsal column
     *
     * Example usage:
     * <code>
     * $query->filterByYesRehearsal('fooValue');   // WHERE yesrehearsal = 'fooValue'
     * $query->filterByYesRehearsal('%fooValue%', Criteria::LIKE); // WHERE yesrehearsal LIKE '%fooValue%'
     * </code>
     *
     * @param     string $yesRehearsal The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSettingsQuery The current query, for fluid interface
     */
    public function filterByYesRehearsal($yesRehearsal = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($yesRehearsal)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SettingsTableMap::COL_YESREHEARSAL, $yesRehearsal, $comparison);
    }

    /**
     * Filter the query on the newusermessage column
     *
     * Example usage:
     * <code>
     * $query->filterByNewUserMessage('fooValue');   // WHERE newusermessage = 'fooValue'
     * $query->filterByNewUserMessage('%fooValue%', Criteria::LIKE); // WHERE newusermessage LIKE '%fooValue%'
     * </code>
     *
     * @param     string $newUserMessage The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSettingsQuery The current query, for fluid interface
     */
    public function filterByNewUserMessage($newUserMessage = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($newUserMessage)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SettingsTableMap::COL_NEWUSERMESSAGE, $newUserMessage, $comparison);
    }

    /**
     * Filter the query on the version column
     *
     * Example usage:
     * <code>
     * $query->filterByVersion('fooValue');   // WHERE version = 'fooValue'
     * $query->filterByVersion('%fooValue%', Criteria::LIKE); // WHERE version LIKE '%fooValue%'
     * </code>
     *
     * @param     string $version The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSettingsQuery The current query, for fluid interface
     */
    public function filterByVersion($version = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($version)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SettingsTableMap::COL_VERSION, $version, $comparison);
    }

    /**
     * Filter the query on the lang_locale column
     *
     * Example usage:
     * <code>
     * $query->filterByLangLocale('fooValue');   // WHERE lang_locale = 'fooValue'
     * $query->filterByLangLocale('%fooValue%', Criteria::LIKE); // WHERE lang_locale LIKE '%fooValue%'
     * </code>
     *
     * @param     string $langLocale The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSettingsQuery The current query, for fluid interface
     */
    public function filterByLangLocale($langLocale = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($langLocale)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SettingsTableMap::COL_LANG_LOCALE, $langLocale, $comparison);
    }

    /**
     * Filter the query on the event_sorting_latest column
     *
     * Example usage:
     * <code>
     * $query->filterByEventSortingLatest(1234); // WHERE event_sorting_latest = 1234
     * $query->filterByEventSortingLatest(array(12, 34)); // WHERE event_sorting_latest IN (12, 34)
     * $query->filterByEventSortingLatest(array('min' => 12)); // WHERE event_sorting_latest > 12
     * </code>
     *
     * @param     mixed $eventSortingLatest The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSettingsQuery The current query, for fluid interface
     */
    public function filterByEventSortingLatest($eventSortingLatest = null, $comparison = null)
    {
        if (is_array($eventSortingLatest)) {
            $useMinMax = false;
            if (isset($eventSortingLatest['min'])) {
                $this->addUsingAlias(SettingsTableMap::COL_EVENT_SORTING_LATEST, $eventSortingLatest['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($eventSortingLatest['max'])) {
                $this->addUsingAlias(SettingsTableMap::COL_EVENT_SORTING_LATEST, $eventSortingLatest['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SettingsTableMap::COL_EVENT_SORTING_LATEST, $eventSortingLatest, $comparison);
    }

    /**
     * Filter the query on the snapshot_show_two_month column
     *
     * Example usage:
     * <code>
     * $query->filterBySnapshotShowTwoMonth(1234); // WHERE snapshot_show_two_month = 1234
     * $query->filterBySnapshotShowTwoMonth(array(12, 34)); // WHERE snapshot_show_two_month IN (12, 34)
     * $query->filterBySnapshotShowTwoMonth(array('min' => 12)); // WHERE snapshot_show_two_month > 12
     * </code>
     *
     * @param     mixed $snapshotShowTwoMonth The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSettingsQuery The current query, for fluid interface
     */
    public function filterBySnapshotShowTwoMonth($snapshotShowTwoMonth = null, $comparison = null)
    {
        if (is_array($snapshotShowTwoMonth)) {
            $useMinMax = false;
            if (isset($snapshotShowTwoMonth['min'])) {
                $this->addUsingAlias(SettingsTableMap::COL_SNAPSHOT_SHOW_TWO_MONTH, $snapshotShowTwoMonth['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($snapshotShowTwoMonth['max'])) {
                $this->addUsingAlias(SettingsTableMap::COL_SNAPSHOT_SHOW_TWO_MONTH, $snapshotShowTwoMonth['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SettingsTableMap::COL_SNAPSHOT_SHOW_TWO_MONTH, $snapshotShowTwoMonth, $comparison);
    }

    /**
     * Filter the query on the snapshot_reduce_skills_by_group column
     *
     * Example usage:
     * <code>
     * $query->filterBySnapshotReduceSkillsByGroup(1234); // WHERE snapshot_reduce_skills_by_group = 1234
     * $query->filterBySnapshotReduceSkillsByGroup(array(12, 34)); // WHERE snapshot_reduce_skills_by_group IN (12, 34)
     * $query->filterBySnapshotReduceSkillsByGroup(array('min' => 12)); // WHERE snapshot_reduce_skills_by_group > 12
     * </code>
     *
     * @param     mixed $snapshotReduceSkillsByGroup The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSettingsQuery The current query, for fluid interface
     */
    public function filterBySnapshotReduceSkillsByGroup($snapshotReduceSkillsByGroup = null, $comparison = null)
    {
        if (is_array($snapshotReduceSkillsByGroup)) {
            $useMinMax = false;
            if (isset($snapshotReduceSkillsByGroup['min'])) {
                $this->addUsingAlias(SettingsTableMap::COL_SNAPSHOT_REDUCE_SKILLS_BY_GROUP, $snapshotReduceSkillsByGroup['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($snapshotReduceSkillsByGroup['max'])) {
                $this->addUsingAlias(SettingsTableMap::COL_SNAPSHOT_REDUCE_SKILLS_BY_GROUP, $snapshotReduceSkillsByGroup['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SettingsTableMap::COL_SNAPSHOT_REDUCE_SKILLS_BY_GROUP, $snapshotReduceSkillsByGroup, $comparison);
    }

    /**
     * Filter the query on the logged_in_show_snapshot_button column
     *
     * Example usage:
     * <code>
     * $query->filterByLoggedInShowSnapshotButton(1234); // WHERE logged_in_show_snapshot_button = 1234
     * $query->filterByLoggedInShowSnapshotButton(array(12, 34)); // WHERE logged_in_show_snapshot_button IN (12, 34)
     * $query->filterByLoggedInShowSnapshotButton(array('min' => 12)); // WHERE logged_in_show_snapshot_button > 12
     * </code>
     *
     * @param     mixed $loggedInShowSnapshotButton The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSettingsQuery The current query, for fluid interface
     */
    public function filterByLoggedInShowSnapshotButton($loggedInShowSnapshotButton = null, $comparison = null)
    {
        if (is_array($loggedInShowSnapshotButton)) {
            $useMinMax = false;
            if (isset($loggedInShowSnapshotButton['min'])) {
                $this->addUsingAlias(SettingsTableMap::COL_LOGGED_IN_SHOW_SNAPSHOT_BUTTON, $loggedInShowSnapshotButton['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($loggedInShowSnapshotButton['max'])) {
                $this->addUsingAlias(SettingsTableMap::COL_LOGGED_IN_SHOW_SNAPSHOT_BUTTON, $loggedInShowSnapshotButton['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SettingsTableMap::COL_LOGGED_IN_SHOW_SNAPSHOT_BUTTON, $loggedInShowSnapshotButton, $comparison);
    }

    /**
     * Filter the query on the time_format_long column
     *
     * Example usage:
     * <code>
     * $query->filterByTimeFormatLong('fooValue');   // WHERE time_format_long = 'fooValue'
     * $query->filterByTimeFormatLong('%fooValue%', Criteria::LIKE); // WHERE time_format_long LIKE '%fooValue%'
     * </code>
     *
     * @param     string $timeFormatLong The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSettingsQuery The current query, for fluid interface
     */
    public function filterByTimeFormatLong($timeFormatLong = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($timeFormatLong)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SettingsTableMap::COL_TIME_FORMAT_LONG, $timeFormatLong, $comparison);
    }

    /**
     * Filter the query on the time_format_normal column
     *
     * Example usage:
     * <code>
     * $query->filterByTimeFormatNormal('fooValue');   // WHERE time_format_normal = 'fooValue'
     * $query->filterByTimeFormatNormal('%fooValue%', Criteria::LIKE); // WHERE time_format_normal LIKE '%fooValue%'
     * </code>
     *
     * @param     string $timeFormatNormal The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSettingsQuery The current query, for fluid interface
     */
    public function filterByTimeFormatNormal($timeFormatNormal = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($timeFormatNormal)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SettingsTableMap::COL_TIME_FORMAT_NORMAL, $timeFormatNormal, $comparison);
    }

    /**
     * Filter the query on the time_format_short column
     *
     * Example usage:
     * <code>
     * $query->filterByTimeFormatShort('fooValue');   // WHERE time_format_short = 'fooValue'
     * $query->filterByTimeFormatShort('%fooValue%', Criteria::LIKE); // WHERE time_format_short LIKE '%fooValue%'
     * </code>
     *
     * @param     string $timeFormatShort The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSettingsQuery The current query, for fluid interface
     */
    public function filterByTimeFormatShort($timeFormatShort = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($timeFormatShort)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SettingsTableMap::COL_TIME_FORMAT_SHORT, $timeFormatShort, $comparison);
    }

    /**
     * Filter the query on the time_only_format column
     *
     * Example usage:
     * <code>
     * $query->filterByTimeOnlyFormat('fooValue');   // WHERE time_only_format = 'fooValue'
     * $query->filterByTimeOnlyFormat('%fooValue%', Criteria::LIKE); // WHERE time_only_format LIKE '%fooValue%'
     * </code>
     *
     * @param     string $timeOnlyFormat The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSettingsQuery The current query, for fluid interface
     */
    public function filterByTimeOnlyFormat($timeOnlyFormat = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($timeOnlyFormat)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SettingsTableMap::COL_TIME_ONLY_FORMAT, $timeOnlyFormat, $comparison);
    }

    /**
     * Filter the query on the date_only_format column
     *
     * Example usage:
     * <code>
     * $query->filterByDateOnlyFormat('fooValue');   // WHERE date_only_format = 'fooValue'
     * $query->filterByDateOnlyFormat('%fooValue%', Criteria::LIKE); // WHERE date_only_format LIKE '%fooValue%'
     * </code>
     *
     * @param     string $dateOnlyFormat The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSettingsQuery The current query, for fluid interface
     */
    public function filterByDateOnlyFormat($dateOnlyFormat = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($dateOnlyFormat)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SettingsTableMap::COL_DATE_ONLY_FORMAT, $dateOnlyFormat, $comparison);
    }

    /**
     * Filter the query on the day_only_format column
     *
     * Example usage:
     * <code>
     * $query->filterByDayOnlyFormat('fooValue');   // WHERE day_only_format = 'fooValue'
     * $query->filterByDayOnlyFormat('%fooValue%', Criteria::LIKE); // WHERE day_only_format LIKE '%fooValue%'
     * </code>
     *
     * @param     string $dayOnlyFormat The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSettingsQuery The current query, for fluid interface
     */
    public function filterByDayOnlyFormat($dayOnlyFormat = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($dayOnlyFormat)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SettingsTableMap::COL_DAY_ONLY_FORMAT, $dayOnlyFormat, $comparison);
    }

    /**
     * Filter the query on the users_start_with_myevents column
     *
     * Example usage:
     * <code>
     * $query->filterByUsersStartWithMyEvents(1234); // WHERE users_start_with_myevents = 1234
     * $query->filterByUsersStartWithMyEvents(array(12, 34)); // WHERE users_start_with_myevents IN (12, 34)
     * $query->filterByUsersStartWithMyEvents(array('min' => 12)); // WHERE users_start_with_myevents > 12
     * </code>
     *
     * @param     mixed $usersStartWithMyEvents The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSettingsQuery The current query, for fluid interface
     */
    public function filterByUsersStartWithMyEvents($usersStartWithMyEvents = null, $comparison = null)
    {
        if (is_array($usersStartWithMyEvents)) {
            $useMinMax = false;
            if (isset($usersStartWithMyEvents['min'])) {
                $this->addUsingAlias(SettingsTableMap::COL_USERS_START_WITH_MYEVENTS, $usersStartWithMyEvents['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($usersStartWithMyEvents['max'])) {
                $this->addUsingAlias(SettingsTableMap::COL_USERS_START_WITH_MYEVENTS, $usersStartWithMyEvents['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SettingsTableMap::COL_USERS_START_WITH_MYEVENTS, $usersStartWithMyEvents, $comparison);
    }

    /**
     * Filter the query on the time_zone column
     *
     * Example usage:
     * <code>
     * $query->filterByTimeZone('fooValue');   // WHERE time_zone = 'fooValue'
     * $query->filterByTimeZone('%fooValue%', Criteria::LIKE); // WHERE time_zone LIKE '%fooValue%'
     * </code>
     *
     * @param     string $timeZone The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSettingsQuery The current query, for fluid interface
     */
    public function filterByTimeZone($timeZone = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($timeZone)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SettingsTableMap::COL_TIME_ZONE, $timeZone, $comparison);
    }

    /**
     * Filter the query on the google_group_calendar column
     *
     * Example usage:
     * <code>
     * $query->filterByGoogleGroupCalendar('fooValue');   // WHERE google_group_calendar = 'fooValue'
     * $query->filterByGoogleGroupCalendar('%fooValue%', Criteria::LIKE); // WHERE google_group_calendar LIKE '%fooValue%'
     * </code>
     *
     * @param     string $googleGroupCalendar The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSettingsQuery The current query, for fluid interface
     */
    public function filterByGoogleGroupCalendar($googleGroupCalendar = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($googleGroupCalendar)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SettingsTableMap::COL_GOOGLE_GROUP_CALENDAR, $googleGroupCalendar, $comparison);
    }

    /**
     * Filter the query on the overviewemail column
     *
     * Example usage:
     * <code>
     * $query->filterByOverviewEmail('fooValue');   // WHERE overviewemail = 'fooValue'
     * $query->filterByOverviewEmail('%fooValue%', Criteria::LIKE); // WHERE overviewemail LIKE '%fooValue%'
     * </code>
     *
     * @param     string $overviewEmail The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSettingsQuery The current query, for fluid interface
     */
    public function filterByOverviewEmail($overviewEmail = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($overviewEmail)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SettingsTableMap::COL_OVERVIEWEMAIL, $overviewEmail, $comparison);
    }

    /**
     * Filter the query on the group_sorting_name column
     *
     * Example usage:
     * <code>
     * $query->filterByGroupSortingName(1234); // WHERE group_sorting_name = 1234
     * $query->filterByGroupSortingName(array(12, 34)); // WHERE group_sorting_name IN (12, 34)
     * $query->filterByGroupSortingName(array('min' => 12)); // WHERE group_sorting_name > 12
     * </code>
     *
     * @param     mixed $groupSortingName The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSettingsQuery The current query, for fluid interface
     */
    public function filterByGroupSortingName($groupSortingName = null, $comparison = null)
    {
        if (is_array($groupSortingName)) {
            $useMinMax = false;
            if (isset($groupSortingName['min'])) {
                $this->addUsingAlias(SettingsTableMap::COL_GROUP_SORTING_NAME, $groupSortingName['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($groupSortingName['max'])) {
                $this->addUsingAlias(SettingsTableMap::COL_GROUP_SORTING_NAME, $groupSortingName['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SettingsTableMap::COL_GROUP_SORTING_NAME, $groupSortingName, $comparison);
    }

    /**
     * Filter the query on the debug_mode column
     *
     * Example usage:
     * <code>
     * $query->filterByDebugMode(1234); // WHERE debug_mode = 1234
     * $query->filterByDebugMode(array(12, 34)); // WHERE debug_mode IN (12, 34)
     * $query->filterByDebugMode(array('min' => 12)); // WHERE debug_mode > 12
     * </code>
     *
     * @param     mixed $debugMode The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSettingsQuery The current query, for fluid interface
     */
    public function filterByDebugMode($debugMode = null, $comparison = null)
    {
        if (is_array($debugMode)) {
            $useMinMax = false;
            if (isset($debugMode['min'])) {
                $this->addUsingAlias(SettingsTableMap::COL_DEBUG_MODE, $debugMode['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($debugMode['max'])) {
                $this->addUsingAlias(SettingsTableMap::COL_DEBUG_MODE, $debugMode['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SettingsTableMap::COL_DEBUG_MODE, $debugMode, $comparison);
    }

    /**
     * Filter the query on the days_to_alert column
     *
     * Example usage:
     * <code>
     * $query->filterByDaysToAlert(1234); // WHERE days_to_alert = 1234
     * $query->filterByDaysToAlert(array(12, 34)); // WHERE days_to_alert IN (12, 34)
     * $query->filterByDaysToAlert(array('min' => 12)); // WHERE days_to_alert > 12
     * </code>
     *
     * @param     mixed $daysToAlert The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSettingsQuery The current query, for fluid interface
     */
    public function filterByDaysToAlert($daysToAlert = null, $comparison = null)
    {
        if (is_array($daysToAlert)) {
            $useMinMax = false;
            if (isset($daysToAlert['min'])) {
                $this->addUsingAlias(SettingsTableMap::COL_DAYS_TO_ALERT, $daysToAlert['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($daysToAlert['max'])) {
                $this->addUsingAlias(SettingsTableMap::COL_DAYS_TO_ALERT, $daysToAlert['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SettingsTableMap::COL_DAYS_TO_ALERT, $daysToAlert, $comparison);
    }

    /**
     * Filter the query on the token column
     *
     * Example usage:
     * <code>
     * $query->filterByToken('fooValue');   // WHERE token = 'fooValue'
     * $query->filterByToken('%fooValue%', Criteria::LIKE); // WHERE token LIKE '%fooValue%'
     * </code>
     *
     * @param     string $token The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSettingsQuery The current query, for fluid interface
     */
    public function filterByToken($token = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($token)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SettingsTableMap::COL_TOKEN, $token, $comparison);
    }

    /**
     * Filter the query on the skin column
     *
     * Example usage:
     * <code>
     * $query->filterBySkin('fooValue');   // WHERE skin = 'fooValue'
     * $query->filterBySkin('%fooValue%', Criteria::LIKE); // WHERE skin LIKE '%fooValue%'
     * </code>
     *
     * @param     string $skin The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSettingsQuery The current query, for fluid interface
     */
    public function filterBySkin($skin = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($skin)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SettingsTableMap::COL_SKIN, $skin, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   ChildSettings $settings Object to remove from the list of results
     *
     * @return $this|ChildSettingsQuery The current query, for fluid interface
     */
    public function prune($settings = null)
    {
        if ($settings) {
            throw new LogicException('Settings object has no primary key');
        }

        return $this;
    }

    /**
     * Deletes all rows from the cr_settings table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(SettingsTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            SettingsTableMap::clearInstancePool();
            SettingsTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(SettingsTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(SettingsTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            SettingsTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            SettingsTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }
} // SettingsQuery
