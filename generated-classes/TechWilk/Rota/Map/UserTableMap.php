<?php

namespace TechWilk\Rota\Map;

use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\InstancePoolTrait;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\DataFetcher\DataFetcherInterface;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\RelationMap;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Map\TableMapTrait;
use TechWilk\Rota\User;
use TechWilk\Rota\UserQuery;


/**
 * This class defines the structure of the 'users' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 */
class UserTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    public const CLASS_NAME = 'TechWilk.Rota.Map.UserTableMap';

    /**
     * The default database name for this class
     */
    public const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    public const TABLE_NAME = 'users';

    /**
     * The PHP name of this class (PascalCase)
     */
    public const TABLE_PHP_NAME = 'User';

    /**
     * The related Propel class for this table
     */
    public const OM_CLASS = '\\TechWilk\\Rota\\User';

    /**
     * A class that can be returned by this tableMap
     */
    public const CLASS_DEFAULT = 'TechWilk.Rota.User';

    /**
     * The total number of columns
     */
    public const NUM_COLUMNS = 16;

    /**
     * The number of lazy-loaded columns
     */
    public const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    public const NUM_HYDRATE_COLUMNS = 16;

    /**
     * the column name for the id field
     */
    public const COL_ID = 'users.id';

    /**
     * the column name for the firstName field
     */
    public const COL_FIRSTNAME = 'users.firstName';

    /**
     * the column name for the lastName field
     */
    public const COL_LASTNAME = 'users.lastName';

    /**
     * the column name for the username field
     */
    public const COL_USERNAME = 'users.username';

    /**
     * the column name for the password field
     */
    public const COL_PASSWORD = 'users.password';

    /**
     * the column name for the isAdmin field
     */
    public const COL_ISADMIN = 'users.isAdmin';

    /**
     * the column name for the email field
     */
    public const COL_EMAIL = 'users.email';

    /**
     * the column name for the mobile field
     */
    public const COL_MOBILE = 'users.mobile';

    /**
     * the column name for the isOverviewRecipient field
     */
    public const COL_ISOVERVIEWRECIPIENT = 'users.isOverviewRecipient';

    /**
     * the column name for the recieveReminderEmails field
     */
    public const COL_RECIEVEREMINDEREMAILS = 'users.recieveReminderEmails';

    /**
     * the column name for the isBandAdmin field
     */
    public const COL_ISBANDADMIN = 'users.isBandAdmin';

    /**
     * the column name for the isEventEditor field
     */
    public const COL_ISEVENTEDITOR = 'users.isEventEditor';

    /**
     * the column name for the lastLogin field
     */
    public const COL_LASTLOGIN = 'users.lastLogin';

    /**
     * the column name for the passwordChanged field
     */
    public const COL_PASSWORDCHANGED = 'users.passwordChanged';

    /**
     * the column name for the created field
     */
    public const COL_CREATED = 'users.created';

    /**
     * the column name for the updated field
     */
    public const COL_UPDATED = 'users.updated';

    /**
     * The default string format for model objects of the related table
     */
    public const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
     *
     * @var array<string, mixed>
     */
    protected static $fieldNames = [
        self::TYPE_PHPNAME       => ['Id', 'FirstName', 'LastName', 'Username', 'Password', 'IsAdmin', 'Email', 'Mobile', 'IsOverviewRecipient', 'RecieveReminderEmails', 'IsBandAdmin', 'IsEventEditor', 'LastLogin', 'PasswordChanged', 'Created', 'Updated', ],
        self::TYPE_CAMELNAME     => ['id', 'firstName', 'lastName', 'username', 'password', 'isAdmin', 'email', 'mobile', 'isOverviewRecipient', 'recieveReminderEmails', 'isBandAdmin', 'isEventEditor', 'lastLogin', 'passwordChanged', 'created', 'updated', ],
        self::TYPE_COLNAME       => [UserTableMap::COL_ID, UserTableMap::COL_FIRSTNAME, UserTableMap::COL_LASTNAME, UserTableMap::COL_USERNAME, UserTableMap::COL_PASSWORD, UserTableMap::COL_ISADMIN, UserTableMap::COL_EMAIL, UserTableMap::COL_MOBILE, UserTableMap::COL_ISOVERVIEWRECIPIENT, UserTableMap::COL_RECIEVEREMINDEREMAILS, UserTableMap::COL_ISBANDADMIN, UserTableMap::COL_ISEVENTEDITOR, UserTableMap::COL_LASTLOGIN, UserTableMap::COL_PASSWORDCHANGED, UserTableMap::COL_CREATED, UserTableMap::COL_UPDATED, ],
        self::TYPE_FIELDNAME     => ['id', 'firstName', 'lastName', 'username', 'password', 'isAdmin', 'email', 'mobile', 'isOverviewRecipient', 'recieveReminderEmails', 'isBandAdmin', 'isEventEditor', 'lastLogin', 'passwordChanged', 'created', 'updated', ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, ]
    ];

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     *
     * @var array<string, mixed>
     */
    protected static $fieldKeys = [
        self::TYPE_PHPNAME       => ['Id' => 0, 'FirstName' => 1, 'LastName' => 2, 'Username' => 3, 'Password' => 4, 'IsAdmin' => 5, 'Email' => 6, 'Mobile' => 7, 'IsOverviewRecipient' => 8, 'RecieveReminderEmails' => 9, 'IsBandAdmin' => 10, 'IsEventEditor' => 11, 'LastLogin' => 12, 'PasswordChanged' => 13, 'Created' => 14, 'Updated' => 15, ],
        self::TYPE_CAMELNAME     => ['id' => 0, 'firstName' => 1, 'lastName' => 2, 'username' => 3, 'password' => 4, 'isAdmin' => 5, 'email' => 6, 'mobile' => 7, 'isOverviewRecipient' => 8, 'recieveReminderEmails' => 9, 'isBandAdmin' => 10, 'isEventEditor' => 11, 'lastLogin' => 12, 'passwordChanged' => 13, 'created' => 14, 'updated' => 15, ],
        self::TYPE_COLNAME       => [UserTableMap::COL_ID => 0, UserTableMap::COL_FIRSTNAME => 1, UserTableMap::COL_LASTNAME => 2, UserTableMap::COL_USERNAME => 3, UserTableMap::COL_PASSWORD => 4, UserTableMap::COL_ISADMIN => 5, UserTableMap::COL_EMAIL => 6, UserTableMap::COL_MOBILE => 7, UserTableMap::COL_ISOVERVIEWRECIPIENT => 8, UserTableMap::COL_RECIEVEREMINDEREMAILS => 9, UserTableMap::COL_ISBANDADMIN => 10, UserTableMap::COL_ISEVENTEDITOR => 11, UserTableMap::COL_LASTLOGIN => 12, UserTableMap::COL_PASSWORDCHANGED => 13, UserTableMap::COL_CREATED => 14, UserTableMap::COL_UPDATED => 15, ],
        self::TYPE_FIELDNAME     => ['id' => 0, 'firstName' => 1, 'lastName' => 2, 'username' => 3, 'password' => 4, 'isAdmin' => 5, 'email' => 6, 'mobile' => 7, 'isOverviewRecipient' => 8, 'recieveReminderEmails' => 9, 'isBandAdmin' => 10, 'isEventEditor' => 11, 'lastLogin' => 12, 'passwordChanged' => 13, 'created' => 14, 'updated' => 15, ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, ]
    ];

    /**
     * Holds a list of column names and their normalized version.
     *
     * @var array<string>
     */
    protected $normalizedColumnNameMap = [
        'Id' => 'ID',
        'User.Id' => 'ID',
        'id' => 'ID',
        'user.id' => 'ID',
        'UserTableMap::COL_ID' => 'ID',
        'COL_ID' => 'ID',
        'users.id' => 'ID',
        'FirstName' => 'FIRSTNAME',
        'User.FirstName' => 'FIRSTNAME',
        'firstName' => 'FIRSTNAME',
        'user.firstName' => 'FIRSTNAME',
        'UserTableMap::COL_FIRSTNAME' => 'FIRSTNAME',
        'COL_FIRSTNAME' => 'FIRSTNAME',
        'users.firstName' => 'FIRSTNAME',
        'LastName' => 'LASTNAME',
        'User.LastName' => 'LASTNAME',
        'lastName' => 'LASTNAME',
        'user.lastName' => 'LASTNAME',
        'UserTableMap::COL_LASTNAME' => 'LASTNAME',
        'COL_LASTNAME' => 'LASTNAME',
        'users.lastName' => 'LASTNAME',
        'Username' => 'USERNAME',
        'User.Username' => 'USERNAME',
        'username' => 'USERNAME',
        'user.username' => 'USERNAME',
        'UserTableMap::COL_USERNAME' => 'USERNAME',
        'COL_USERNAME' => 'USERNAME',
        'users.username' => 'USERNAME',
        'Password' => 'PASSWORD',
        'User.Password' => 'PASSWORD',
        'password' => 'PASSWORD',
        'user.password' => 'PASSWORD',
        'UserTableMap::COL_PASSWORD' => 'PASSWORD',
        'COL_PASSWORD' => 'PASSWORD',
        'users.password' => 'PASSWORD',
        'IsAdmin' => 'ISADMIN',
        'User.IsAdmin' => 'ISADMIN',
        'isAdmin' => 'ISADMIN',
        'user.isAdmin' => 'ISADMIN',
        'UserTableMap::COL_ISADMIN' => 'ISADMIN',
        'COL_ISADMIN' => 'ISADMIN',
        'users.isAdmin' => 'ISADMIN',
        'Email' => 'EMAIL',
        'User.Email' => 'EMAIL',
        'email' => 'EMAIL',
        'user.email' => 'EMAIL',
        'UserTableMap::COL_EMAIL' => 'EMAIL',
        'COL_EMAIL' => 'EMAIL',
        'users.email' => 'EMAIL',
        'Mobile' => 'MOBILE',
        'User.Mobile' => 'MOBILE',
        'mobile' => 'MOBILE',
        'user.mobile' => 'MOBILE',
        'UserTableMap::COL_MOBILE' => 'MOBILE',
        'COL_MOBILE' => 'MOBILE',
        'users.mobile' => 'MOBILE',
        'IsOverviewRecipient' => 'ISOVERVIEWRECIPIENT',
        'User.IsOverviewRecipient' => 'ISOVERVIEWRECIPIENT',
        'isOverviewRecipient' => 'ISOVERVIEWRECIPIENT',
        'user.isOverviewRecipient' => 'ISOVERVIEWRECIPIENT',
        'UserTableMap::COL_ISOVERVIEWRECIPIENT' => 'ISOVERVIEWRECIPIENT',
        'COL_ISOVERVIEWRECIPIENT' => 'ISOVERVIEWRECIPIENT',
        'users.isOverviewRecipient' => 'ISOVERVIEWRECIPIENT',
        'RecieveReminderEmails' => 'RECIEVEREMINDEREMAILS',
        'User.RecieveReminderEmails' => 'RECIEVEREMINDEREMAILS',
        'recieveReminderEmails' => 'RECIEVEREMINDEREMAILS',
        'user.recieveReminderEmails' => 'RECIEVEREMINDEREMAILS',
        'UserTableMap::COL_RECIEVEREMINDEREMAILS' => 'RECIEVEREMINDEREMAILS',
        'COL_RECIEVEREMINDEREMAILS' => 'RECIEVEREMINDEREMAILS',
        'users.recieveReminderEmails' => 'RECIEVEREMINDEREMAILS',
        'IsBandAdmin' => 'ISBANDADMIN',
        'User.IsBandAdmin' => 'ISBANDADMIN',
        'isBandAdmin' => 'ISBANDADMIN',
        'user.isBandAdmin' => 'ISBANDADMIN',
        'UserTableMap::COL_ISBANDADMIN' => 'ISBANDADMIN',
        'COL_ISBANDADMIN' => 'ISBANDADMIN',
        'users.isBandAdmin' => 'ISBANDADMIN',
        'IsEventEditor' => 'ISEVENTEDITOR',
        'User.IsEventEditor' => 'ISEVENTEDITOR',
        'isEventEditor' => 'ISEVENTEDITOR',
        'user.isEventEditor' => 'ISEVENTEDITOR',
        'UserTableMap::COL_ISEVENTEDITOR' => 'ISEVENTEDITOR',
        'COL_ISEVENTEDITOR' => 'ISEVENTEDITOR',
        'users.isEventEditor' => 'ISEVENTEDITOR',
        'LastLogin' => 'LASTLOGIN',
        'User.LastLogin' => 'LASTLOGIN',
        'lastLogin' => 'LASTLOGIN',
        'user.lastLogin' => 'LASTLOGIN',
        'UserTableMap::COL_LASTLOGIN' => 'LASTLOGIN',
        'COL_LASTLOGIN' => 'LASTLOGIN',
        'users.lastLogin' => 'LASTLOGIN',
        'PasswordChanged' => 'PASSWORDCHANGED',
        'User.PasswordChanged' => 'PASSWORDCHANGED',
        'passwordChanged' => 'PASSWORDCHANGED',
        'user.passwordChanged' => 'PASSWORDCHANGED',
        'UserTableMap::COL_PASSWORDCHANGED' => 'PASSWORDCHANGED',
        'COL_PASSWORDCHANGED' => 'PASSWORDCHANGED',
        'users.passwordChanged' => 'PASSWORDCHANGED',
        'Created' => 'CREATED',
        'User.Created' => 'CREATED',
        'created' => 'CREATED',
        'user.created' => 'CREATED',
        'UserTableMap::COL_CREATED' => 'CREATED',
        'COL_CREATED' => 'CREATED',
        'users.created' => 'CREATED',
        'Updated' => 'UPDATED',
        'User.Updated' => 'UPDATED',
        'updated' => 'UPDATED',
        'user.updated' => 'UPDATED',
        'UserTableMap::COL_UPDATED' => 'UPDATED',
        'COL_UPDATED' => 'UPDATED',
        'users.updated' => 'UPDATED',
    ];

    /**
     * Initialize the table attributes and columns
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function initialize(): void
    {
        // attributes
        $this->setName('users');
        $this->setPhpName('User');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\TechWilk\\Rota\\User');
        $this->setPackage('TechWilk.Rota');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, 30, null);
        $this->addColumn('firstName', 'FirstName', 'VARCHAR', true, 30, '');
        $this->addColumn('lastName', 'LastName', 'VARCHAR', true, 30, '');
        $this->addColumn('username', 'Username', 'VARCHAR', true, 30, '');
        $this->addColumn('password', 'Password', 'VARCHAR', true, 200, '');
        $this->addColumn('isAdmin', 'IsAdmin', 'CHAR', true, 2, '0');
        $this->addColumn('email', 'Email', 'VARCHAR', false, 255, null);
        $this->addColumn('mobile', 'Mobile', 'VARCHAR', true, 15, '');
        $this->addColumn('isOverviewRecipient', 'IsOverviewRecipient', 'CHAR', true, 2, '0');
        $this->addColumn('recieveReminderEmails', 'RecieveReminderEmails', 'BOOLEAN', true, 1, true);
        $this->addColumn('isBandAdmin', 'IsBandAdmin', 'CHAR', true, 2, '0');
        $this->addColumn('isEventEditor', 'IsEventEditor', 'CHAR', true, 2, '0');
        $this->addColumn('lastLogin', 'LastLogin', 'TIMESTAMP', false, null, null);
        $this->addColumn('passwordChanged', 'PasswordChanged', 'TIMESTAMP', false, null, null);
        $this->addColumn('created', 'Created', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated', 'Updated', 'TIMESTAMP', false, null, null);
    }

    /**
     * Build the RelationMap objects for this table relationships
     *
     * @return void
     */
    public function buildRelations(): void
    {
        $this->addRelation('CalendarToken', '\\TechWilk\\Rota\\CalendarToken', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':userId',
    1 => ':id',
  ),
), null, null, 'CalendarTokens', false);
        $this->addRelation('Comment', '\\TechWilk\\Rota\\Comment', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':userId',
    1 => ':id',
  ),
), null, null, 'Comments', false);
        $this->addRelation('Event', '\\TechWilk\\Rota\\Event', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':createdBy',
    1 => ':id',
  ),
), null, null, 'Events', false);
        $this->addRelation('Availability', '\\TechWilk\\Rota\\Availability', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':userId',
    1 => ':id',
  ),
), null, null, 'Availabilities', false);
        $this->addRelation('Notification', '\\TechWilk\\Rota\\Notification', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':userId',
    1 => ':id',
  ),
), null, null, 'Notifications', false);
        $this->addRelation('SocialAuth', '\\TechWilk\\Rota\\SocialAuth', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':userId',
    1 => ':id',
  ),
), null, null, 'SocialAuths', false);
        $this->addRelation('Statistic', '\\TechWilk\\Rota\\Statistic', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':userid',
    1 => ':id',
  ),
), null, null, 'Statistics', false);
        $this->addRelation('Swap', '\\TechWilk\\Rota\\Swap', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':requestedBy',
    1 => ':id',
  ),
), null, null, 'Swaps', false);
        $this->addRelation('UserRole', '\\TechWilk\\Rota\\UserRole', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':userId',
    1 => ':id',
  ),
), null, null, 'UserRoles', false);
        $this->addRelation('UserPermission', '\\TechWilk\\Rota\\UserPermission', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':userId',
    1 => ':id',
  ),
), null, null, 'UserPermissions', false);
    }

    /**
     *
     * Gets the list of behaviors registered for this table
     *
     * @return array<string, array> Associative array (name => parameters) of behaviors
     */
    public function getBehaviors(): array
    {
        return [
            'timestampable' => ['create_column' => 'created', 'update_column' => 'updated', 'disable_created_at' => 'false', 'disable_updated_at' => 'false'],
        ];
    }

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param array $row Resultset row.
     * @param int $offset The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return string|null The primary key hash of the row
     */
    public static function getPrimaryKeyHashFromRow(array $row, int $offset = 0, string $indexType = TableMap::TYPE_NUM): ?string
    {
        // If the PK cannot be derived from the row, return NULL.
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return null === $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] || is_scalar($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)]) || is_callable([$row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)], '__toString']) ? (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] : $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
    }

    /**
     * Retrieves the primary key from the DB resultset row
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, an array of the primary key columns will be returned.
     *
     * @param array $row Resultset row.
     * @param int $offset The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return mixed The primary key of the row
     */
    public static function getPrimaryKeyFromRow(array $row, int $offset = 0, string $indexType = TableMap::TYPE_NUM)
    {
        return (int) $row[
            $indexType == TableMap::TYPE_NUM
                ? 0 + $offset
                : self::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)
        ];
    }

    /**
     * The class that the tableMap will make instances of.
     *
     * If $withPrefix is true, the returned path
     * uses a dot-path notation which is translated into a path
     * relative to a location on the PHP include_path.
     * (e.g. path.to.MyClass -> 'path/to/MyClass.php')
     *
     * @param bool $withPrefix Whether to return the path with the class name
     * @return string path.to.ClassName
     */
    public static function getOMClass(bool $withPrefix = true): string
    {
        return $withPrefix ? UserTableMap::CLASS_DEFAULT : UserTableMap::OM_CLASS;
    }

    /**
     * Populates an object of the default type or an object that inherit from the default.
     *
     * @param array $row Row returned by DataFetcher->fetch().
     * @param int $offset The 0-based offset for reading from the resultset row.
     * @param string $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                 One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     * @return array (User object, last column rank)
     */
    public static function populateObject(array $row, int $offset = 0, string $indexType = TableMap::TYPE_NUM): array
    {
        $key = UserTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = UserTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + UserTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = UserTableMap::OM_CLASS;
            /** @var User $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            UserTableMap::addInstanceToPool($obj, $key);
        }

        return [$obj, $col];
    }

    /**
     * The returned array will contain objects of the default type or
     * objects that inherit from the default.
     *
     * @param DataFetcherInterface $dataFetcher
     * @return array<object>
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function populateObjects(DataFetcherInterface $dataFetcher): array
    {
        $results = [];

        // set the class once to avoid overhead in the loop
        $cls = static::getOMClass(false);
        // populate the object(s)
        while ($row = $dataFetcher->fetch()) {
            $key = UserTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = UserTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var User $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                UserTableMap::addInstanceToPool($obj, $key);
            } // if key exists
        }

        return $results;
    }
    /**
     * Add all the columns needed to create a new object.
     *
     * Note: any columns that were marked with lazyLoad="true" in the
     * XML schema will not be added to the select list and only loaded
     * on demand.
     *
     * @param Criteria $criteria Object containing the columns to add.
     * @param string|null $alias Optional table alias
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     * @return void
     */
    public static function addSelectColumns(Criteria $criteria, ?string $alias = null): void
    {
        if (null === $alias) {
            $criteria->addSelectColumn(UserTableMap::COL_ID);
            $criteria->addSelectColumn(UserTableMap::COL_FIRSTNAME);
            $criteria->addSelectColumn(UserTableMap::COL_LASTNAME);
            $criteria->addSelectColumn(UserTableMap::COL_USERNAME);
            $criteria->addSelectColumn(UserTableMap::COL_PASSWORD);
            $criteria->addSelectColumn(UserTableMap::COL_ISADMIN);
            $criteria->addSelectColumn(UserTableMap::COL_EMAIL);
            $criteria->addSelectColumn(UserTableMap::COL_MOBILE);
            $criteria->addSelectColumn(UserTableMap::COL_ISOVERVIEWRECIPIENT);
            $criteria->addSelectColumn(UserTableMap::COL_RECIEVEREMINDEREMAILS);
            $criteria->addSelectColumn(UserTableMap::COL_ISBANDADMIN);
            $criteria->addSelectColumn(UserTableMap::COL_ISEVENTEDITOR);
            $criteria->addSelectColumn(UserTableMap::COL_LASTLOGIN);
            $criteria->addSelectColumn(UserTableMap::COL_PASSWORDCHANGED);
            $criteria->addSelectColumn(UserTableMap::COL_CREATED);
            $criteria->addSelectColumn(UserTableMap::COL_UPDATED);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.firstName');
            $criteria->addSelectColumn($alias . '.lastName');
            $criteria->addSelectColumn($alias . '.username');
            $criteria->addSelectColumn($alias . '.password');
            $criteria->addSelectColumn($alias . '.isAdmin');
            $criteria->addSelectColumn($alias . '.email');
            $criteria->addSelectColumn($alias . '.mobile');
            $criteria->addSelectColumn($alias . '.isOverviewRecipient');
            $criteria->addSelectColumn($alias . '.recieveReminderEmails');
            $criteria->addSelectColumn($alias . '.isBandAdmin');
            $criteria->addSelectColumn($alias . '.isEventEditor');
            $criteria->addSelectColumn($alias . '.lastLogin');
            $criteria->addSelectColumn($alias . '.passwordChanged');
            $criteria->addSelectColumn($alias . '.created');
            $criteria->addSelectColumn($alias . '.updated');
        }
    }

    /**
     * Remove all the columns needed to create a new object.
     *
     * Note: any columns that were marked with lazyLoad="true" in the
     * XML schema will not be removed as they are only loaded on demand.
     *
     * @param Criteria $criteria Object containing the columns to remove.
     * @param string|null $alias Optional table alias
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     * @return void
     */
    public static function removeSelectColumns(Criteria $criteria, ?string $alias = null): void
    {
        if (null === $alias) {
            $criteria->removeSelectColumn(UserTableMap::COL_ID);
            $criteria->removeSelectColumn(UserTableMap::COL_FIRSTNAME);
            $criteria->removeSelectColumn(UserTableMap::COL_LASTNAME);
            $criteria->removeSelectColumn(UserTableMap::COL_USERNAME);
            $criteria->removeSelectColumn(UserTableMap::COL_PASSWORD);
            $criteria->removeSelectColumn(UserTableMap::COL_ISADMIN);
            $criteria->removeSelectColumn(UserTableMap::COL_EMAIL);
            $criteria->removeSelectColumn(UserTableMap::COL_MOBILE);
            $criteria->removeSelectColumn(UserTableMap::COL_ISOVERVIEWRECIPIENT);
            $criteria->removeSelectColumn(UserTableMap::COL_RECIEVEREMINDEREMAILS);
            $criteria->removeSelectColumn(UserTableMap::COL_ISBANDADMIN);
            $criteria->removeSelectColumn(UserTableMap::COL_ISEVENTEDITOR);
            $criteria->removeSelectColumn(UserTableMap::COL_LASTLOGIN);
            $criteria->removeSelectColumn(UserTableMap::COL_PASSWORDCHANGED);
            $criteria->removeSelectColumn(UserTableMap::COL_CREATED);
            $criteria->removeSelectColumn(UserTableMap::COL_UPDATED);
        } else {
            $criteria->removeSelectColumn($alias . '.id');
            $criteria->removeSelectColumn($alias . '.firstName');
            $criteria->removeSelectColumn($alias . '.lastName');
            $criteria->removeSelectColumn($alias . '.username');
            $criteria->removeSelectColumn($alias . '.password');
            $criteria->removeSelectColumn($alias . '.isAdmin');
            $criteria->removeSelectColumn($alias . '.email');
            $criteria->removeSelectColumn($alias . '.mobile');
            $criteria->removeSelectColumn($alias . '.isOverviewRecipient');
            $criteria->removeSelectColumn($alias . '.recieveReminderEmails');
            $criteria->removeSelectColumn($alias . '.isBandAdmin');
            $criteria->removeSelectColumn($alias . '.isEventEditor');
            $criteria->removeSelectColumn($alias . '.lastLogin');
            $criteria->removeSelectColumn($alias . '.passwordChanged');
            $criteria->removeSelectColumn($alias . '.created');
            $criteria->removeSelectColumn($alias . '.updated');
        }
    }

    /**
     * Returns the TableMap related to this object.
     * This method is not needed for general use but a specific application could have a need.
     * @return TableMap
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function getTableMap(): TableMap
    {
        return Propel::getServiceContainer()->getDatabaseMap(UserTableMap::DATABASE_NAME)->getTable(UserTableMap::TABLE_NAME);
    }

    /**
     * Performs a DELETE on the database, given a User or Criteria object OR a primary key value.
     *
     * @param mixed $values Criteria or User object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
     public static function doDelete($values, ?ConnectionInterface $con = null): int
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(UserTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \TechWilk\Rota\User) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(UserTableMap::DATABASE_NAME);
            $criteria->add(UserTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = UserQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            UserTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                UserTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the users table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(?ConnectionInterface $con = null): int
    {
        return UserQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a User or Criteria object.
     *
     * @param mixed $criteria Criteria or User object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed The new primary key.
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ?ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(UserTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from User object
        }


        // Set the correct dbName
        $query = UserQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

}
