<?php

namespace Map;

use \Players;
use \PlayersQuery;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\InstancePoolTrait;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\DataFetcher\DataFetcherInterface;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\RelationMap;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Map\TableMapTrait;


/**
 * This class defines the structure of the 'players' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class PlayersTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = '.Map.PlayersTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'players';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\Players';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Players';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 19;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 19;

    /**
     * the column name for the PrimaryKey field
     */
    const COL_PRIMARYKEY = 'players.PrimaryKey';

    /**
     * the column name for the NickName field
     */
    const COL_NICKNAME = 'players.NickName';

    /**
     * the column name for the FirstName field
     */
    const COL_FIRSTNAME = 'players.FirstName';

    /**
     * the column name for the LastName field
     */
    const COL_LASTNAME = 'players.LastName';

    /**
     * the column name for the EmailAddress field
     */
    const COL_EMAILADDRESS = 'players.EmailAddress';

    /**
     * the column name for the Password field
     */
    const COL_PASSWORD = 'players.Password';

    /**
     * the column name for the IsAdministrator field
     */
    const COL_ISADMINISTRATOR = 'players.IsAdministrator';

    /**
     * the column name for the ActivationKey field
     */
    const COL_ACTIVATIONKEY = 'players.ActivationKey';

    /**
     * the column name for the IsEnabled field
     */
    const COL_ISENABLED = 'players.IsEnabled';

    /**
     * the column name for the LastConnection field
     */
    const COL_LASTCONNECTION = 'players.LastConnection';

    /**
     * the column name for the Token field
     */
    const COL_TOKEN = 'players.Token';

    /**
     * the column name for the AvatarName field
     */
    const COL_AVATARNAME = 'players.AvatarName';

    /**
     * the column name for the CreationDate field
     */
    const COL_CREATIONDATE = 'players.CreationDate';

    /**
     * the column name for the IsCalendarDefaultView field
     */
    const COL_ISCALENDARDEFAULTVIEW = 'players.IsCalendarDefaultView';

    /**
     * the column name for the ReceiveAlert field
     */
    const COL_RECEIVEALERT = 'players.ReceiveAlert';

    /**
     * the column name for the ReceiveNewletter field
     */
    const COL_RECEIVENEWLETTER = 'players.ReceiveNewletter';

    /**
     * the column name for the ReceiveResult field
     */
    const COL_RECEIVERESULT = 'players.ReceiveResult';

    /**
     * the column name for the IsReminderEmailSent field
     */
    const COL_ISREMINDEREMAILSENT = 'players.IsReminderEmailSent';

    /**
     * the column name for the IsResultEmailSent field
     */
    const COL_ISRESULTEMAILSENT = 'players.IsResultEmailSent';

    /**
     * The default string format for model objects of the related table
     */
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        self::TYPE_PHPNAME       => array('Primarykey', 'Nickname', 'Firstname', 'Lastname', 'Emailaddress', 'Password', 'Isadministrator', 'Activationkey', 'Isenabled', 'Lastconnection', 'Token', 'Avatarname', 'Creationdate', 'Iscalendardefaultview', 'Receivealert', 'Receivenewletter', 'Receiveresult', 'Isreminderemailsent', 'Isresultemailsent', ),
        self::TYPE_CAMELNAME     => array('primarykey', 'nickname', 'firstname', 'lastname', 'emailaddress', 'password', 'isadministrator', 'activationkey', 'isenabled', 'lastconnection', 'token', 'avatarname', 'creationdate', 'iscalendardefaultview', 'receivealert', 'receivenewletter', 'receiveresult', 'isreminderemailsent', 'isresultemailsent', ),
        self::TYPE_COLNAME       => array(PlayersTableMap::COL_PRIMARYKEY, PlayersTableMap::COL_NICKNAME, PlayersTableMap::COL_FIRSTNAME, PlayersTableMap::COL_LASTNAME, PlayersTableMap::COL_EMAILADDRESS, PlayersTableMap::COL_PASSWORD, PlayersTableMap::COL_ISADMINISTRATOR, PlayersTableMap::COL_ACTIVATIONKEY, PlayersTableMap::COL_ISENABLED, PlayersTableMap::COL_LASTCONNECTION, PlayersTableMap::COL_TOKEN, PlayersTableMap::COL_AVATARNAME, PlayersTableMap::COL_CREATIONDATE, PlayersTableMap::COL_ISCALENDARDEFAULTVIEW, PlayersTableMap::COL_RECEIVEALERT, PlayersTableMap::COL_RECEIVENEWLETTER, PlayersTableMap::COL_RECEIVERESULT, PlayersTableMap::COL_ISREMINDEREMAILSENT, PlayersTableMap::COL_ISRESULTEMAILSENT, ),
        self::TYPE_FIELDNAME     => array('PrimaryKey', 'NickName', 'FirstName', 'LastName', 'EmailAddress', 'Password', 'IsAdministrator', 'ActivationKey', 'IsEnabled', 'LastConnection', 'Token', 'AvatarName', 'CreationDate', 'IsCalendarDefaultView', 'ReceiveAlert', 'ReceiveNewletter', 'ReceiveResult', 'IsReminderEmailSent', 'IsResultEmailSent', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Primarykey' => 0, 'Nickname' => 1, 'Firstname' => 2, 'Lastname' => 3, 'Emailaddress' => 4, 'Password' => 5, 'Isadministrator' => 6, 'Activationkey' => 7, 'Isenabled' => 8, 'Lastconnection' => 9, 'Token' => 10, 'Avatarname' => 11, 'Creationdate' => 12, 'Iscalendardefaultview' => 13, 'Receivealert' => 14, 'Receivenewletter' => 15, 'Receiveresult' => 16, 'Isreminderemailsent' => 17, 'Isresultemailsent' => 18, ),
        self::TYPE_CAMELNAME     => array('primarykey' => 0, 'nickname' => 1, 'firstname' => 2, 'lastname' => 3, 'emailaddress' => 4, 'password' => 5, 'isadministrator' => 6, 'activationkey' => 7, 'isenabled' => 8, 'lastconnection' => 9, 'token' => 10, 'avatarname' => 11, 'creationdate' => 12, 'iscalendardefaultview' => 13, 'receivealert' => 14, 'receivenewletter' => 15, 'receiveresult' => 16, 'isreminderemailsent' => 17, 'isresultemailsent' => 18, ),
        self::TYPE_COLNAME       => array(PlayersTableMap::COL_PRIMARYKEY => 0, PlayersTableMap::COL_NICKNAME => 1, PlayersTableMap::COL_FIRSTNAME => 2, PlayersTableMap::COL_LASTNAME => 3, PlayersTableMap::COL_EMAILADDRESS => 4, PlayersTableMap::COL_PASSWORD => 5, PlayersTableMap::COL_ISADMINISTRATOR => 6, PlayersTableMap::COL_ACTIVATIONKEY => 7, PlayersTableMap::COL_ISENABLED => 8, PlayersTableMap::COL_LASTCONNECTION => 9, PlayersTableMap::COL_TOKEN => 10, PlayersTableMap::COL_AVATARNAME => 11, PlayersTableMap::COL_CREATIONDATE => 12, PlayersTableMap::COL_ISCALENDARDEFAULTVIEW => 13, PlayersTableMap::COL_RECEIVEALERT => 14, PlayersTableMap::COL_RECEIVENEWLETTER => 15, PlayersTableMap::COL_RECEIVERESULT => 16, PlayersTableMap::COL_ISREMINDEREMAILSENT => 17, PlayersTableMap::COL_ISRESULTEMAILSENT => 18, ),
        self::TYPE_FIELDNAME     => array('PrimaryKey' => 0, 'NickName' => 1, 'FirstName' => 2, 'LastName' => 3, 'EmailAddress' => 4, 'Password' => 5, 'IsAdministrator' => 6, 'ActivationKey' => 7, 'IsEnabled' => 8, 'LastConnection' => 9, 'Token' => 10, 'AvatarName' => 11, 'CreationDate' => 12, 'IsCalendarDefaultView' => 13, 'ReceiveAlert' => 14, 'ReceiveNewletter' => 15, 'ReceiveResult' => 16, 'IsReminderEmailSent' => 17, 'IsResultEmailSent' => 18, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, )
    );

    /**
     * Initialize the table attributes and columns
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws PropelException
     */
    public function initialize()
    {
        // attributes
        $this->setName('players');
        $this->setPhpName('Players');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Players');
        $this->setPackage('');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('PrimaryKey', 'Primarykey', 'INTEGER', true, null, null);
        $this->addColumn('NickName', 'Nickname', 'VARCHAR', true, 20, null);
        $this->addColumn('FirstName', 'Firstname', 'VARCHAR', true, 50, null);
        $this->addColumn('LastName', 'Lastname', 'VARCHAR', true, 50, null);
        $this->addColumn('EmailAddress', 'Emailaddress', 'VARCHAR', true, 80, null);
        $this->addColumn('Password', 'Password', 'VARCHAR', true, 100, null);
        $this->addColumn('IsAdministrator', 'Isadministrator', 'BOOLEAN', true, 1, false);
        $this->addColumn('ActivationKey', 'Activationkey', 'VARCHAR', false, 255, null);
        $this->addColumn('IsEnabled', 'Isenabled', 'BOOLEAN', true, 1, true);
        $this->addColumn('LastConnection', 'Lastconnection', 'DATE', true, null, null);
        $this->addColumn('Token', 'Token', 'VARCHAR', false, 50, null);
        $this->addColumn('AvatarName', 'Avatarname', 'VARCHAR', false, 20, null);
        $this->addColumn('CreationDate', 'Creationdate', 'DATE', true, null, null);
        $this->addColumn('IsCalendarDefaultView', 'Iscalendardefaultview', 'BOOLEAN', true, 1, false);
        $this->addColumn('ReceiveAlert', 'Receivealert', 'BOOLEAN', true, 1, true);
        $this->addColumn('ReceiveNewletter', 'Receivenewletter', 'BOOLEAN', true, 1, true);
        $this->addColumn('ReceiveResult', 'Receiveresult', 'BOOLEAN', true, 1, true);
        $this->addColumn('IsReminderEmailSent', 'Isreminderemailsent', 'BOOLEAN', true, 1, false);
        $this->addColumn('IsResultEmailSent', 'Isresultemailsent', 'BOOLEAN', true, 1, false);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
    } // buildRelations()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return string The primary key hash of the row
     */
    public static function getPrimaryKeyHashFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        // If the PK cannot be derived from the row, return NULL.
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Primarykey', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Primarykey', TableMap::TYPE_PHPNAME, $indexType)];
    }

    /**
     * Retrieves the primary key from the DB resultset row
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, an array of the primary key columns will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return mixed The primary key of the row
     */
    public static function getPrimaryKeyFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        return (int) $row[
            $indexType == TableMap::TYPE_NUM
                ? 0 + $offset
                : self::translateFieldName('Primarykey', TableMap::TYPE_PHPNAME, $indexType)
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
     * @param boolean $withPrefix Whether or not to return the path with the class name
     * @return string path.to.ClassName
     */
    public static function getOMClass($withPrefix = true)
    {
        return $withPrefix ? PlayersTableMap::CLASS_DEFAULT : PlayersTableMap::OM_CLASS;
    }

    /**
     * Populates an object of the default type or an object that inherit from the default.
     *
     * @param array  $row       row returned by DataFetcher->fetch().
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                 One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     * @return array           (Players object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = PlayersTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = PlayersTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + PlayersTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = PlayersTableMap::OM_CLASS;
            /** @var Players $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            PlayersTableMap::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }

    /**
     * The returned array will contain objects of the default type or
     * objects that inherit from the default.
     *
     * @param DataFetcherInterface $dataFetcher
     * @return array
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function populateObjects(DataFetcherInterface $dataFetcher)
    {
        $results = array();

        // set the class once to avoid overhead in the loop
        $cls = static::getOMClass(false);
        // populate the object(s)
        while ($row = $dataFetcher->fetch()) {
            $key = PlayersTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = PlayersTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Players $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                PlayersTableMap::addInstanceToPool($obj, $key);
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
     * @param Criteria $criteria object containing the columns to add.
     * @param string   $alias    optional table alias
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function addSelectColumns(Criteria $criteria, $alias = null)
    {
        if (null === $alias) {
            $criteria->addSelectColumn(PlayersTableMap::COL_PRIMARYKEY);
            $criteria->addSelectColumn(PlayersTableMap::COL_NICKNAME);
            $criteria->addSelectColumn(PlayersTableMap::COL_FIRSTNAME);
            $criteria->addSelectColumn(PlayersTableMap::COL_LASTNAME);
            $criteria->addSelectColumn(PlayersTableMap::COL_EMAILADDRESS);
            $criteria->addSelectColumn(PlayersTableMap::COL_PASSWORD);
            $criteria->addSelectColumn(PlayersTableMap::COL_ISADMINISTRATOR);
            $criteria->addSelectColumn(PlayersTableMap::COL_ACTIVATIONKEY);
            $criteria->addSelectColumn(PlayersTableMap::COL_ISENABLED);
            $criteria->addSelectColumn(PlayersTableMap::COL_LASTCONNECTION);
            $criteria->addSelectColumn(PlayersTableMap::COL_TOKEN);
            $criteria->addSelectColumn(PlayersTableMap::COL_AVATARNAME);
            $criteria->addSelectColumn(PlayersTableMap::COL_CREATIONDATE);
            $criteria->addSelectColumn(PlayersTableMap::COL_ISCALENDARDEFAULTVIEW);
            $criteria->addSelectColumn(PlayersTableMap::COL_RECEIVEALERT);
            $criteria->addSelectColumn(PlayersTableMap::COL_RECEIVENEWLETTER);
            $criteria->addSelectColumn(PlayersTableMap::COL_RECEIVERESULT);
            $criteria->addSelectColumn(PlayersTableMap::COL_ISREMINDEREMAILSENT);
            $criteria->addSelectColumn(PlayersTableMap::COL_ISRESULTEMAILSENT);
        } else {
            $criteria->addSelectColumn($alias . '.PrimaryKey');
            $criteria->addSelectColumn($alias . '.NickName');
            $criteria->addSelectColumn($alias . '.FirstName');
            $criteria->addSelectColumn($alias . '.LastName');
            $criteria->addSelectColumn($alias . '.EmailAddress');
            $criteria->addSelectColumn($alias . '.Password');
            $criteria->addSelectColumn($alias . '.IsAdministrator');
            $criteria->addSelectColumn($alias . '.ActivationKey');
            $criteria->addSelectColumn($alias . '.IsEnabled');
            $criteria->addSelectColumn($alias . '.LastConnection');
            $criteria->addSelectColumn($alias . '.Token');
            $criteria->addSelectColumn($alias . '.AvatarName');
            $criteria->addSelectColumn($alias . '.CreationDate');
            $criteria->addSelectColumn($alias . '.IsCalendarDefaultView');
            $criteria->addSelectColumn($alias . '.ReceiveAlert');
            $criteria->addSelectColumn($alias . '.ReceiveNewletter');
            $criteria->addSelectColumn($alias . '.ReceiveResult');
            $criteria->addSelectColumn($alias . '.IsReminderEmailSent');
            $criteria->addSelectColumn($alias . '.IsResultEmailSent');
        }
    }

    /**
     * Returns the TableMap related to this object.
     * This method is not needed for general use but a specific application could have a need.
     * @return TableMap
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function getTableMap()
    {
        return Propel::getServiceContainer()->getDatabaseMap(PlayersTableMap::DATABASE_NAME)->getTable(PlayersTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(PlayersTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(PlayersTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new PlayersTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a Players or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Players object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param  ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
     public static function doDelete($values, ConnectionInterface $con = null)
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PlayersTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Players) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(PlayersTableMap::DATABASE_NAME);
            $criteria->add(PlayersTableMap::COL_PRIMARYKEY, (array) $values, Criteria::IN);
        }

        $query = PlayersQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            PlayersTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                PlayersTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the players table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return PlayersQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Players or Criteria object.
     *
     * @param mixed               $criteria Criteria or Players object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PlayersTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Players object
        }

        if ($criteria->containsKey(PlayersTableMap::COL_PRIMARYKEY) && $criteria->keyContainsValue(PlayersTableMap::COL_PRIMARYKEY) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.PlayersTableMap::COL_PRIMARYKEY.')');
        }


        // Set the correct dbName
        $query = PlayersQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // PlayersTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
PlayersTableMap::buildTableMap();
