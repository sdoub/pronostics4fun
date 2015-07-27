<?php

namespace Map;

use \Matches;
use \MatchesQuery;
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
 * This class defines the structure of the 'matches' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class MatchesTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = '.Map.MatchesTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'matches';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\Matches';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Matches';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 8;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 8;

    /**
     * the column name for the PrimaryKey field
     */
    const COL_PRIMARYKEY = 'matches.PrimaryKey';

    /**
     * the column name for the GroupKey field
     */
    const COL_GROUPKEY = 'matches.GroupKey';

    /**
     * the column name for the TeamHomeKey field
     */
    const COL_TEAMHOMEKEY = 'matches.TeamHomeKey';

    /**
     * the column name for the TeamAwayKey field
     */
    const COL_TEAMAWAYKEY = 'matches.TeamAwayKey';

    /**
     * the column name for the ScheduleDate field
     */
    const COL_SCHEDULEDATE = 'matches.ScheduleDate';

    /**
     * the column name for the IsBonusMatch field
     */
    const COL_ISBONUSMATCH = 'matches.IsBonusMatch';

    /**
     * the column name for the Status field
     */
    const COL_STATUS = 'matches.Status';

    /**
     * the column name for the ExternalKey field
     */
    const COL_EXTERNALKEY = 'matches.ExternalKey';

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
        self::TYPE_PHPNAME       => array('Primarykey', 'Groupkey', 'Teamhomekey', 'Teamawaykey', 'Scheduledate', 'Isbonusmatch', 'Status', 'Externalkey', ),
        self::TYPE_CAMELNAME     => array('primarykey', 'groupkey', 'teamhomekey', 'teamawaykey', 'scheduledate', 'isbonusmatch', 'status', 'externalkey', ),
        self::TYPE_COLNAME       => array(MatchesTableMap::COL_PRIMARYKEY, MatchesTableMap::COL_GROUPKEY, MatchesTableMap::COL_TEAMHOMEKEY, MatchesTableMap::COL_TEAMAWAYKEY, MatchesTableMap::COL_SCHEDULEDATE, MatchesTableMap::COL_ISBONUSMATCH, MatchesTableMap::COL_STATUS, MatchesTableMap::COL_EXTERNALKEY, ),
        self::TYPE_FIELDNAME     => array('PrimaryKey', 'GroupKey', 'TeamHomeKey', 'TeamAwayKey', 'ScheduleDate', 'IsBonusMatch', 'Status', 'ExternalKey', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Primarykey' => 0, 'Groupkey' => 1, 'Teamhomekey' => 2, 'Teamawaykey' => 3, 'Scheduledate' => 4, 'Isbonusmatch' => 5, 'Status' => 6, 'Externalkey' => 7, ),
        self::TYPE_CAMELNAME     => array('primarykey' => 0, 'groupkey' => 1, 'teamhomekey' => 2, 'teamawaykey' => 3, 'scheduledate' => 4, 'isbonusmatch' => 5, 'status' => 6, 'externalkey' => 7, ),
        self::TYPE_COLNAME       => array(MatchesTableMap::COL_PRIMARYKEY => 0, MatchesTableMap::COL_GROUPKEY => 1, MatchesTableMap::COL_TEAMHOMEKEY => 2, MatchesTableMap::COL_TEAMAWAYKEY => 3, MatchesTableMap::COL_SCHEDULEDATE => 4, MatchesTableMap::COL_ISBONUSMATCH => 5, MatchesTableMap::COL_STATUS => 6, MatchesTableMap::COL_EXTERNALKEY => 7, ),
        self::TYPE_FIELDNAME     => array('PrimaryKey' => 0, 'GroupKey' => 1, 'TeamHomeKey' => 2, 'TeamAwayKey' => 3, 'ScheduleDate' => 4, 'IsBonusMatch' => 5, 'Status' => 6, 'ExternalKey' => 7, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, )
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
        $this->setName('matches');
        $this->setPhpName('Matches');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Matches');
        $this->setPackage('');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('PrimaryKey', 'Primarykey', 'INTEGER', true, null, null);
        $this->addColumn('GroupKey', 'Groupkey', 'INTEGER', true, null, null);
        $this->addColumn('TeamHomeKey', 'Teamhomekey', 'INTEGER', true, null, null);
        $this->addColumn('TeamAwayKey', 'Teamawaykey', 'INTEGER', true, null, null);
        $this->addColumn('ScheduleDate', 'Scheduledate', 'TIMESTAMP', true, null, 'CURRENT_TIMESTAMP');
        $this->addColumn('IsBonusMatch', 'Isbonusmatch', 'BOOLEAN', true, 1, false);
        $this->addColumn('Status', 'Status', 'INTEGER', true, null, 0);
        $this->addColumn('ExternalKey', 'Externalkey', 'INTEGER', false, null, null);
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
        return $withPrefix ? MatchesTableMap::CLASS_DEFAULT : MatchesTableMap::OM_CLASS;
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
     * @return array           (Matches object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = MatchesTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = MatchesTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + MatchesTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = MatchesTableMap::OM_CLASS;
            /** @var Matches $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            MatchesTableMap::addInstanceToPool($obj, $key);
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
            $key = MatchesTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = MatchesTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Matches $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                MatchesTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(MatchesTableMap::COL_PRIMARYKEY);
            $criteria->addSelectColumn(MatchesTableMap::COL_GROUPKEY);
            $criteria->addSelectColumn(MatchesTableMap::COL_TEAMHOMEKEY);
            $criteria->addSelectColumn(MatchesTableMap::COL_TEAMAWAYKEY);
            $criteria->addSelectColumn(MatchesTableMap::COL_SCHEDULEDATE);
            $criteria->addSelectColumn(MatchesTableMap::COL_ISBONUSMATCH);
            $criteria->addSelectColumn(MatchesTableMap::COL_STATUS);
            $criteria->addSelectColumn(MatchesTableMap::COL_EXTERNALKEY);
        } else {
            $criteria->addSelectColumn($alias . '.PrimaryKey');
            $criteria->addSelectColumn($alias . '.GroupKey');
            $criteria->addSelectColumn($alias . '.TeamHomeKey');
            $criteria->addSelectColumn($alias . '.TeamAwayKey');
            $criteria->addSelectColumn($alias . '.ScheduleDate');
            $criteria->addSelectColumn($alias . '.IsBonusMatch');
            $criteria->addSelectColumn($alias . '.Status');
            $criteria->addSelectColumn($alias . '.ExternalKey');
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
        return Propel::getServiceContainer()->getDatabaseMap(MatchesTableMap::DATABASE_NAME)->getTable(MatchesTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(MatchesTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(MatchesTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new MatchesTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a Matches or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Matches object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(MatchesTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Matches) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(MatchesTableMap::DATABASE_NAME);
            $criteria->add(MatchesTableMap::COL_PRIMARYKEY, (array) $values, Criteria::IN);
        }

        $query = MatchesQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            MatchesTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                MatchesTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the matches table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return MatchesQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Matches or Criteria object.
     *
     * @param mixed               $criteria Criteria or Matches object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(MatchesTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Matches object
        }

        if ($criteria->containsKey(MatchesTableMap::COL_PRIMARYKEY) && $criteria->keyContainsValue(MatchesTableMap::COL_PRIMARYKEY) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.MatchesTableMap::COL_PRIMARYKEY.')');
        }


        // Set the correct dbName
        $query = MatchesQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // MatchesTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
MatchesTableMap::buildTableMap();
