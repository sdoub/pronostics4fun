<?php

namespace Map;

use \Playerdivisionmatches;
use \PlayerdivisionmatchesQuery;
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
 * This class defines the structure of the 'playerdivisionmatches' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class PlayerdivisionmatchesTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = '.Map.PlayerdivisionmatchesTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'playerdivisionmatches';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\Playerdivisionmatches';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Playerdivisionmatches';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 10;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 10;

    /**
     * the column name for the PrimaryKey field
     */
    const COL_PRIMARYKEY = 'playerdivisionmatches.PrimaryKey';

    /**
     * the column name for the PlayerHomeKey field
     */
    const COL_PLAYERHOMEKEY = 'playerdivisionmatches.PlayerHomeKey';

    /**
     * the column name for the PlayerAwayKey field
     */
    const COL_PLAYERAWAYKEY = 'playerdivisionmatches.PlayerAwayKey';

    /**
     * the column name for the SeasonKey field
     */
    const COL_SEASONKEY = 'playerdivisionmatches.SeasonKey';

    /**
     * the column name for the DivisionKey field
     */
    const COL_DIVISIONKEY = 'playerdivisionmatches.DivisionKey';

    /**
     * the column name for the GroupKey field
     */
    const COL_GROUPKEY = 'playerdivisionmatches.GroupKey';

    /**
     * the column name for the HomeScore field
     */
    const COL_HOMESCORE = 'playerdivisionmatches.HomeScore';

    /**
     * the column name for the AwayScore field
     */
    const COL_AWAYSCORE = 'playerdivisionmatches.AwayScore';

    /**
     * the column name for the ScheduleDate field
     */
    const COL_SCHEDULEDATE = 'playerdivisionmatches.ScheduleDate';

    /**
     * the column name for the ResultDate field
     */
    const COL_RESULTDATE = 'playerdivisionmatches.ResultDate';

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
        self::TYPE_PHPNAME       => array('PlayerDivisionMatchPK', 'Playerhomekey', 'Playerawaykey', 'Seasonkey', 'Divisionkey', 'Groupkey', 'Homescore', 'Awayscore', 'Scheduledate', 'Resultdate', ),
        self::TYPE_CAMELNAME     => array('playerDivisionMatchPK', 'playerhomekey', 'playerawaykey', 'seasonkey', 'divisionkey', 'groupkey', 'homescore', 'awayscore', 'scheduledate', 'resultdate', ),
        self::TYPE_COLNAME       => array(PlayerdivisionmatchesTableMap::COL_PRIMARYKEY, PlayerdivisionmatchesTableMap::COL_PLAYERHOMEKEY, PlayerdivisionmatchesTableMap::COL_PLAYERAWAYKEY, PlayerdivisionmatchesTableMap::COL_SEASONKEY, PlayerdivisionmatchesTableMap::COL_DIVISIONKEY, PlayerdivisionmatchesTableMap::COL_GROUPKEY, PlayerdivisionmatchesTableMap::COL_HOMESCORE, PlayerdivisionmatchesTableMap::COL_AWAYSCORE, PlayerdivisionmatchesTableMap::COL_SCHEDULEDATE, PlayerdivisionmatchesTableMap::COL_RESULTDATE, ),
        self::TYPE_FIELDNAME     => array('PrimaryKey', 'PlayerHomeKey', 'PlayerAwayKey', 'SeasonKey', 'DivisionKey', 'GroupKey', 'HomeScore', 'AwayScore', 'ScheduleDate', 'ResultDate', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('PlayerDivisionMatchPK' => 0, 'Playerhomekey' => 1, 'Playerawaykey' => 2, 'Seasonkey' => 3, 'Divisionkey' => 4, 'Groupkey' => 5, 'Homescore' => 6, 'Awayscore' => 7, 'Scheduledate' => 8, 'Resultdate' => 9, ),
        self::TYPE_CAMELNAME     => array('playerDivisionMatchPK' => 0, 'playerhomekey' => 1, 'playerawaykey' => 2, 'seasonkey' => 3, 'divisionkey' => 4, 'groupkey' => 5, 'homescore' => 6, 'awayscore' => 7, 'scheduledate' => 8, 'resultdate' => 9, ),
        self::TYPE_COLNAME       => array(PlayerdivisionmatchesTableMap::COL_PRIMARYKEY => 0, PlayerdivisionmatchesTableMap::COL_PLAYERHOMEKEY => 1, PlayerdivisionmatchesTableMap::COL_PLAYERAWAYKEY => 2, PlayerdivisionmatchesTableMap::COL_SEASONKEY => 3, PlayerdivisionmatchesTableMap::COL_DIVISIONKEY => 4, PlayerdivisionmatchesTableMap::COL_GROUPKEY => 5, PlayerdivisionmatchesTableMap::COL_HOMESCORE => 6, PlayerdivisionmatchesTableMap::COL_AWAYSCORE => 7, PlayerdivisionmatchesTableMap::COL_SCHEDULEDATE => 8, PlayerdivisionmatchesTableMap::COL_RESULTDATE => 9, ),
        self::TYPE_FIELDNAME     => array('PrimaryKey' => 0, 'PlayerHomeKey' => 1, 'PlayerAwayKey' => 2, 'SeasonKey' => 3, 'DivisionKey' => 4, 'GroupKey' => 5, 'HomeScore' => 6, 'AwayScore' => 7, 'ScheduleDate' => 8, 'ResultDate' => 9, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, )
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
        $this->setName('playerdivisionmatches');
        $this->setPhpName('Playerdivisionmatches');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Playerdivisionmatches');
        $this->setPackage('');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('PrimaryKey', 'PlayerDivisionMatchPK', 'INTEGER', true, null, null);
        $this->addForeignKey('PlayerHomeKey', 'Playerhomekey', 'INTEGER', 'players', 'PrimaryKey', true, null, null);
        $this->addForeignKey('PlayerAwayKey', 'Playerawaykey', 'INTEGER', 'players', 'PrimaryKey', true, null, null);
        $this->addForeignKey('SeasonKey', 'Seasonkey', 'INTEGER', 'seasons', 'PrimaryKey', true, null, null);
        $this->addForeignKey('DivisionKey', 'Divisionkey', 'INTEGER', 'players', 'PrimaryKey', true, null, null);
        $this->addForeignKey('GroupKey', 'Groupkey', 'INTEGER', 'groups', 'PrimaryKey', true, null, null);
        $this->addColumn('HomeScore', 'Homescore', 'TINYINT', false, 3, null);
        $this->addColumn('AwayScore', 'Awayscore', 'TINYINT', false, 3, null);
        $this->addColumn('ScheduleDate', 'Scheduledate', 'TIMESTAMP', false, null, null);
        $this->addColumn('ResultDate', 'Resultdate', 'DATE', false, null, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('DivisionMatchesPlayerHome', '\\Players', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':PlayerHomeKey',
    1 => ':PrimaryKey',
  ),
), null, null, null, false);
        $this->addRelation('DivisionMatchesPlayerAway', '\\Players', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':PlayerAwayKey',
    1 => ':PrimaryKey',
  ),
), null, null, null, false);
        $this->addRelation('DivisionMatchesDivision', '\\Players', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':DivisionKey',
    1 => ':PrimaryKey',
  ),
), null, null, null, false);
        $this->addRelation('DivisionMatchesGroup', '\\Groups', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':GroupKey',
    1 => ':PrimaryKey',
  ),
), null, null, null, false);
        $this->addRelation('DivisionMatchesSeason', '\\Seasons', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':SeasonKey',
    1 => ':PrimaryKey',
  ),
), null, null, null, false);
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
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('PlayerDivisionMatchPK', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('PlayerDivisionMatchPK', TableMap::TYPE_PHPNAME, $indexType)];
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
                : self::translateFieldName('PlayerDivisionMatchPK', TableMap::TYPE_PHPNAME, $indexType)
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
        return $withPrefix ? PlayerdivisionmatchesTableMap::CLASS_DEFAULT : PlayerdivisionmatchesTableMap::OM_CLASS;
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
     * @return array           (Playerdivisionmatches object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = PlayerdivisionmatchesTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = PlayerdivisionmatchesTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + PlayerdivisionmatchesTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = PlayerdivisionmatchesTableMap::OM_CLASS;
            /** @var Playerdivisionmatches $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            PlayerdivisionmatchesTableMap::addInstanceToPool($obj, $key);
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
            $key = PlayerdivisionmatchesTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = PlayerdivisionmatchesTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Playerdivisionmatches $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                PlayerdivisionmatchesTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(PlayerdivisionmatchesTableMap::COL_PRIMARYKEY);
            $criteria->addSelectColumn(PlayerdivisionmatchesTableMap::COL_PLAYERHOMEKEY);
            $criteria->addSelectColumn(PlayerdivisionmatchesTableMap::COL_PLAYERAWAYKEY);
            $criteria->addSelectColumn(PlayerdivisionmatchesTableMap::COL_SEASONKEY);
            $criteria->addSelectColumn(PlayerdivisionmatchesTableMap::COL_DIVISIONKEY);
            $criteria->addSelectColumn(PlayerdivisionmatchesTableMap::COL_GROUPKEY);
            $criteria->addSelectColumn(PlayerdivisionmatchesTableMap::COL_HOMESCORE);
            $criteria->addSelectColumn(PlayerdivisionmatchesTableMap::COL_AWAYSCORE);
            $criteria->addSelectColumn(PlayerdivisionmatchesTableMap::COL_SCHEDULEDATE);
            $criteria->addSelectColumn(PlayerdivisionmatchesTableMap::COL_RESULTDATE);
        } else {
            $criteria->addSelectColumn($alias . '.PrimaryKey');
            $criteria->addSelectColumn($alias . '.PlayerHomeKey');
            $criteria->addSelectColumn($alias . '.PlayerAwayKey');
            $criteria->addSelectColumn($alias . '.SeasonKey');
            $criteria->addSelectColumn($alias . '.DivisionKey');
            $criteria->addSelectColumn($alias . '.GroupKey');
            $criteria->addSelectColumn($alias . '.HomeScore');
            $criteria->addSelectColumn($alias . '.AwayScore');
            $criteria->addSelectColumn($alias . '.ScheduleDate');
            $criteria->addSelectColumn($alias . '.ResultDate');
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
        return Propel::getServiceContainer()->getDatabaseMap(PlayerdivisionmatchesTableMap::DATABASE_NAME)->getTable(PlayerdivisionmatchesTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(PlayerdivisionmatchesTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(PlayerdivisionmatchesTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new PlayerdivisionmatchesTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a Playerdivisionmatches or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Playerdivisionmatches object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(PlayerdivisionmatchesTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Playerdivisionmatches) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(PlayerdivisionmatchesTableMap::DATABASE_NAME);
            $criteria->add(PlayerdivisionmatchesTableMap::COL_PRIMARYKEY, (array) $values, Criteria::IN);
        }

        $query = PlayerdivisionmatchesQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            PlayerdivisionmatchesTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                PlayerdivisionmatchesTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the playerdivisionmatches table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return PlayerdivisionmatchesQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Playerdivisionmatches or Criteria object.
     *
     * @param mixed               $criteria Criteria or Playerdivisionmatches object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PlayerdivisionmatchesTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Playerdivisionmatches object
        }

        if ($criteria->containsKey(PlayerdivisionmatchesTableMap::COL_PRIMARYKEY) && $criteria->keyContainsValue(PlayerdivisionmatchesTableMap::COL_PRIMARYKEY) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.PlayerdivisionmatchesTableMap::COL_PRIMARYKEY.')');
        }


        // Set the correct dbName
        $query = PlayerdivisionmatchesQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // PlayerdivisionmatchesTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
PlayerdivisionmatchesTableMap::buildTableMap();
