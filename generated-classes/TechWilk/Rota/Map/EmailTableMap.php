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
use TechWilk\Rota\Email;
use TechWilk\Rota\EmailQuery;


/**
 * This class defines the structure of the 'emails' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 */
class EmailTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    public const CLASS_NAME = 'TechWilk.Rota.Map.EmailTableMap';

    /**
     * The default database name for this class
     */
    public const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    public const TABLE_NAME = 'emails';

    /**
     * The PHP name of this class (PascalCase)
     */
    public const TABLE_PHP_NAME = 'Email';

    /**
     * The related Propel class for this table
     */
    public const OM_CLASS = '\\TechWilk\\Rota\\Email';

    /**
     * A class that can be returned by this tableMap
     */
    public const CLASS_DEFAULT = 'TechWilk.Rota.Email';

    /**
     * The total number of columns
     */
    public const NUM_COLUMNS = 7;

    /**
     * The number of lazy-loaded columns
     */
    public const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    public const NUM_HYDRATE_COLUMNS = 7;

    /**
     * the column name for the id field
     */
    public const COL_ID = 'emails.id';

    /**
     * the column name for the emailTo field
     */
    public const COL_EMAILTO = 'emails.emailTo';

    /**
     * the column name for the emailBcc field
     */
    public const COL_EMAILBCC = 'emails.emailBcc';

    /**
     * the column name for the emailFrom field
     */
    public const COL_EMAILFROM = 'emails.emailFrom';

    /**
     * the column name for the subject field
     */
    public const COL_SUBJECT = 'emails.subject';

    /**
     * the column name for the message field
     */
    public const COL_MESSAGE = 'emails.message';

    /**
     * the column name for the error field
     */
    public const COL_ERROR = 'emails.error';

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
        self::TYPE_PHPNAME       => ['Id', 'EmailTo', 'EmailBcc', 'EmailFrom', 'Subject', 'Message', 'Error', ],
        self::TYPE_CAMELNAME     => ['id', 'emailTo', 'emailBcc', 'emailFrom', 'subject', 'message', 'error', ],
        self::TYPE_COLNAME       => [EmailTableMap::COL_ID, EmailTableMap::COL_EMAILTO, EmailTableMap::COL_EMAILBCC, EmailTableMap::COL_EMAILFROM, EmailTableMap::COL_SUBJECT, EmailTableMap::COL_MESSAGE, EmailTableMap::COL_ERROR, ],
        self::TYPE_FIELDNAME     => ['id', 'emailTo', 'emailBcc', 'emailFrom', 'subject', 'message', 'error', ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, ]
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
        self::TYPE_PHPNAME       => ['Id' => 0, 'EmailTo' => 1, 'EmailBcc' => 2, 'EmailFrom' => 3, 'Subject' => 4, 'Message' => 5, 'Error' => 6, ],
        self::TYPE_CAMELNAME     => ['id' => 0, 'emailTo' => 1, 'emailBcc' => 2, 'emailFrom' => 3, 'subject' => 4, 'message' => 5, 'error' => 6, ],
        self::TYPE_COLNAME       => [EmailTableMap::COL_ID => 0, EmailTableMap::COL_EMAILTO => 1, EmailTableMap::COL_EMAILBCC => 2, EmailTableMap::COL_EMAILFROM => 3, EmailTableMap::COL_SUBJECT => 4, EmailTableMap::COL_MESSAGE => 5, EmailTableMap::COL_ERROR => 6, ],
        self::TYPE_FIELDNAME     => ['id' => 0, 'emailTo' => 1, 'emailBcc' => 2, 'emailFrom' => 3, 'subject' => 4, 'message' => 5, 'error' => 6, ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, ]
    ];

    /**
     * Holds a list of column names and their normalized version.
     *
     * @var array<string>
     */
    protected $normalizedColumnNameMap = [
        'Id' => 'ID',
        'Email.Id' => 'ID',
        'id' => 'ID',
        'email.id' => 'ID',
        'EmailTableMap::COL_ID' => 'ID',
        'COL_ID' => 'ID',
        'emails.id' => 'ID',
        'EmailTo' => 'EMAILTO',
        'Email.EmailTo' => 'EMAILTO',
        'emailTo' => 'EMAILTO',
        'email.emailTo' => 'EMAILTO',
        'EmailTableMap::COL_EMAILTO' => 'EMAILTO',
        'COL_EMAILTO' => 'EMAILTO',
        'emails.emailTo' => 'EMAILTO',
        'EmailBcc' => 'EMAILBCC',
        'Email.EmailBcc' => 'EMAILBCC',
        'emailBcc' => 'EMAILBCC',
        'email.emailBcc' => 'EMAILBCC',
        'EmailTableMap::COL_EMAILBCC' => 'EMAILBCC',
        'COL_EMAILBCC' => 'EMAILBCC',
        'emails.emailBcc' => 'EMAILBCC',
        'EmailFrom' => 'EMAILFROM',
        'Email.EmailFrom' => 'EMAILFROM',
        'emailFrom' => 'EMAILFROM',
        'email.emailFrom' => 'EMAILFROM',
        'EmailTableMap::COL_EMAILFROM' => 'EMAILFROM',
        'COL_EMAILFROM' => 'EMAILFROM',
        'emails.emailFrom' => 'EMAILFROM',
        'Subject' => 'SUBJECT',
        'Email.Subject' => 'SUBJECT',
        'subject' => 'SUBJECT',
        'email.subject' => 'SUBJECT',
        'EmailTableMap::COL_SUBJECT' => 'SUBJECT',
        'COL_SUBJECT' => 'SUBJECT',
        'emails.subject' => 'SUBJECT',
        'Message' => 'MESSAGE',
        'Email.Message' => 'MESSAGE',
        'message' => 'MESSAGE',
        'email.message' => 'MESSAGE',
        'EmailTableMap::COL_MESSAGE' => 'MESSAGE',
        'COL_MESSAGE' => 'MESSAGE',
        'emails.message' => 'MESSAGE',
        'Error' => 'ERROR',
        'Email.Error' => 'ERROR',
        'error' => 'ERROR',
        'email.error' => 'ERROR',
        'EmailTableMap::COL_ERROR' => 'ERROR',
        'COL_ERROR' => 'ERROR',
        'emails.error' => 'ERROR',
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
        $this->setName('emails');
        $this->setPhpName('Email');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\TechWilk\\Rota\\Email');
        $this->setPackage('TechWilk.Rota');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('emailTo', 'EmailTo', 'VARCHAR', true, 100, '');
        $this->addColumn('emailBcc', 'EmailBcc', 'VARCHAR', true, 100, '');
        $this->addColumn('emailFrom', 'EmailFrom', 'VARCHAR', true, 100, null);
        $this->addColumn('subject', 'Subject', 'VARCHAR', true, 150, null);
        $this->addColumn('message', 'Message', 'LONGVARCHAR', true, null, null);
        $this->addColumn('error', 'Error', 'LONGVARCHAR', false, null, null);
    }

    /**
     * Build the RelationMap objects for this table relationships
     *
     * @return void
     */
    public function buildRelations(): void
    {
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
        return $withPrefix ? EmailTableMap::CLASS_DEFAULT : EmailTableMap::OM_CLASS;
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
     * @return array (Email object, last column rank)
     */
    public static function populateObject(array $row, int $offset = 0, string $indexType = TableMap::TYPE_NUM): array
    {
        $key = EmailTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = EmailTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + EmailTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = EmailTableMap::OM_CLASS;
            /** @var Email $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            EmailTableMap::addInstanceToPool($obj, $key);
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
            $key = EmailTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = EmailTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Email $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                EmailTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(EmailTableMap::COL_ID);
            $criteria->addSelectColumn(EmailTableMap::COL_EMAILTO);
            $criteria->addSelectColumn(EmailTableMap::COL_EMAILBCC);
            $criteria->addSelectColumn(EmailTableMap::COL_EMAILFROM);
            $criteria->addSelectColumn(EmailTableMap::COL_SUBJECT);
            $criteria->addSelectColumn(EmailTableMap::COL_MESSAGE);
            $criteria->addSelectColumn(EmailTableMap::COL_ERROR);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.emailTo');
            $criteria->addSelectColumn($alias . '.emailBcc');
            $criteria->addSelectColumn($alias . '.emailFrom');
            $criteria->addSelectColumn($alias . '.subject');
            $criteria->addSelectColumn($alias . '.message');
            $criteria->addSelectColumn($alias . '.error');
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
            $criteria->removeSelectColumn(EmailTableMap::COL_ID);
            $criteria->removeSelectColumn(EmailTableMap::COL_EMAILTO);
            $criteria->removeSelectColumn(EmailTableMap::COL_EMAILBCC);
            $criteria->removeSelectColumn(EmailTableMap::COL_EMAILFROM);
            $criteria->removeSelectColumn(EmailTableMap::COL_SUBJECT);
            $criteria->removeSelectColumn(EmailTableMap::COL_MESSAGE);
            $criteria->removeSelectColumn(EmailTableMap::COL_ERROR);
        } else {
            $criteria->removeSelectColumn($alias . '.id');
            $criteria->removeSelectColumn($alias . '.emailTo');
            $criteria->removeSelectColumn($alias . '.emailBcc');
            $criteria->removeSelectColumn($alias . '.emailFrom');
            $criteria->removeSelectColumn($alias . '.subject');
            $criteria->removeSelectColumn($alias . '.message');
            $criteria->removeSelectColumn($alias . '.error');
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
        return Propel::getServiceContainer()->getDatabaseMap(EmailTableMap::DATABASE_NAME)->getTable(EmailTableMap::TABLE_NAME);
    }

    /**
     * Performs a DELETE on the database, given a Email or Criteria object OR a primary key value.
     *
     * @param mixed $values Criteria or Email object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(EmailTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \TechWilk\Rota\Email) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(EmailTableMap::DATABASE_NAME);
            $criteria->add(EmailTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = EmailQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            EmailTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                EmailTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the emails table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(?ConnectionInterface $con = null): int
    {
        return EmailQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Email or Criteria object.
     *
     * @param mixed $criteria Criteria or Email object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed The new primary key.
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ?ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(EmailTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Email object
        }

        if ($criteria->containsKey(EmailTableMap::COL_ID) && $criteria->keyContainsValue(EmailTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.EmailTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = EmailQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

}
