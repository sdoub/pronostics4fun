<?php

namespace Map;

use \Playercupmatches;
use \PlayercupmatchesQuery;
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
 * This class defines the structure of the 'playercupmatches' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class PlayercupmatchesTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = '.Map.PlayercupmatchesTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'playercupmatches';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\Playercupmatches';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Playercupmatches';

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
    const COL_PRIMARYKEY = 'playercupmatches.PrimaryKey';

    /**
     * the column name for the PlayerHomeKey field
     */
    const COL_PLAYERHOMEKEY = 'playercupmatches.PlayerHomeKey';

    /**
     * the column name for the PlayerAwayKey field
     */
    const COL_PLAYERAWAYKEY = 'playercupmatches.PlayerAwayKey';

    /**
     * the column name for the CupRoundKey field
     */
    const COL_CUPROUNDKEY = 'playercupmatches.CupRoundKey';

    /**
     * the column name for the SeasonKey field
     */
    const COL_SEASONKEY = 'playercupmatches.SeasonKey';

    /**
     * the column name for the GroupKey field
     */
    const COL_GROUPKEY = 'playercupmatches.GroupKey';

    /**
     * the column name for the HomeScore field
     */
    const COL_HOMESCORE = 'playercupmatches.HomeScore';

    /**
     * the column name for the AwayScore field
     */
    const COL_AWAYSCORE = 'playercupmatches.AwayScore';

    /**
     * the column name for the ScheduleDate field
     */
    const COL_SCHEDULEDATE = 'playercupmatches.ScheduleDate';

    /**
     * the column name for the ResultDate field
     */
    const COL_RESULTDATE = 'playercupmatches.ResultDate';

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
        self::TYPE_PHPNAME       => array('PlayerCupMatchPK', 'Playerhomekey', 'Playerawaykey', 'Cuproundkey', 'Seasonkey', 'Groupkey', 'Homescore', 'Awayscore', 'Scheduledate', 'Resultdate', ),
        self::TYPE_CAMELNAME     => array('playerCupMatchPK', 'playerhomekey', 'playerawaykey', 'cuproundkey', 'seasonkey', 'groupkey', 'homescore', 'awayscore', 'scheduledate', 'resultdate', ),
        self::TYPE_COLNAME       => array(PlayercupmatchesTableMap::COL_PRIMARYKEY, PlayercupmatchesTableMap::COL_PLAYERHOMEKEY, PlayercupmatchesTableMap::COL_PLAYERAWAYKEY, PlayercupmatchesTableMap::COL_CUPROUNDKEY, PlayercupmatchesTableMap::COL_SEASONKEY, PlayercupmatchesTableMap::COL_GROUPKEY, PlayercupmatchesTableMap::COL_HOMESCORE, PlayercupmatchesTableMap::COL_AWAYSCORE, PlayercupmatchesTableMap::COL_SCHEDULEDATE, PlayercupmatchesTableMap::COL_RESULTDATE, ),
        self::TYPE_FIELDNAME     => array('PrimaryKey', 'PlayerHomeKey', 'PlayerAwayKey', 'CupRoundKey', 'SeasonKey', 'GroupKey', 'HomeScore', 'AwayScore', 'ScheduleDate', 'ResultDate', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('PlayerCupMatchPK' => 0, 'Playerhomekey' => 1, 'Playerawaykey' => 2, 'Cuproundkey' => 3, 'Seasonkey' => 4, 'Groupkey' => 5, 'Homescore' => 6, 'Awayscore' => 7, 'Scheduledate' => 8, 'Resultdate' => 9, ),
        self::TYPE_CAMELNAME     => array('playerCupMatchPK' => 0, 'playerhomekey' => 1, 'playerawaykey' => 2, 'cuproundkey' => 3, 'seasonkey' => 4, 'groupkey' => 5, 'homescore' => 6, 'awayscore' => 7, 'scheduledate' => 8, 'resultdate' => 9, ),
        self::TYPE_COLNAME       => array(PlayercupmatchesTableMap::COL_PRIMARYKEY => 0, PlayercupmatchesTableMap::COL_PLAYERHOMEKEY => 1, PlayercupmatchesTableMap::COL_PLAYERAWAYKEY => 2, PlayercupmatchesTableMap::COL_CUPROUNDKEY => 3, PlayercupmatchesTableMap::COL_SEASONKEY => 4, PlayercupmatchesTableMap::COL_GROUPKEY => 5, PlayercupmatchesTableMap::COL_HOMESCORE => 6, PlayercupmatchesTableMap::COL_AWAYSCORE => 7, PlayercupmatchesTableMap::COL_SCHEDULEDATE => 8, PlayercupmatchesTableMap::COL_RESULTDATE => 9, ),
        self::TYPE_FIELDNAME     => array('PrimaryKey' => 0, 'PlayerHomeKey' => 1, 'PlayerAwayKey' => 2, 'CupRoundKey' => 3, 'SeasonKey' => 4, 'GroupKey' => 5, 'HomeScore' => 6, 'AwayScore' => 7, 'ScheduleDate' => 8, 'ResultDate' => 9, ),
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
        $this->setName('playercupmatches');
        $this->setPhpName('Playercupmatches');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Playercupmatches');
        $this->setPackage('');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('PrimaryKey', 'PlayerCupMatchPK', 'INTEGER', true, null, null);
        $this->addForeignKey('PlayerHomeKey', 'Playerhomekey', 'INTEGER', 'players', 'PrimaryKey', true, null, null);
        $this->addForeignKey('PlayerAwayKey', 'Playerawaykey', 'INTEGER', 'players', 'PrimaryKey', true, null, null);
        $this->addForeignKey('CupRoundKey', 'Cuproundkey', 'INTEGER', 'cuprounds', 'PrimaryKey', true, null, null);
        $this->addForeignKey('SeasonKey', 'Seasonkey', 'INTEGER', 'seasons', 'PrimaryKey', true, null, null);
        $this->addForeignKey('GroupKey', 'Groupkey', 'INTEGER', 'groups', 'PrimaryKey', true, null, null);
        $this->addColumn('HomeScore', 'Homescore', 'TINYINT', false, 3, null);
        $this->addColumn('AwayScore', 'Awayscore', 'TINYINT', false, 3, null);
        $this->addColumn('ScheduleDate', 'Scheduledate', 'TIMESTAMP', true, null, 'CURRENT_TIMESTAMP');
        $this->addColumn('ResultDate', 'Resultdate', 'DATE', false, null, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('CupMatchesPlayerHome', '\\Players', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':PlayerHomeKey',
    1 => ':PrimaryKey',
  ),
), null, null, null, false);
        $this->addRelation('CupMatchesPlayerAway', '\\Players', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':PlayerAwayKey',
    1 => ':PrimaryKey',
  ),
), null, null, null, false);
        $this->addRelation('CupMatchesCupRound', '\\Cuprounds', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':CupRoundKey',
    1 => ':PrimaryKey',
  ),
), null, null, null, false);
        $this->addRelation('CupMatchesGroup', '\\Groups', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':GroupKey',
    1 => ':PrimaryKey',
  ),
), null, null, null, false);
        $this->addRelation('CupMatchesSeason', '\\Seasons', RelationMap::MANY_TO_ONE, array (
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
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('PlayerCupMatchPK', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('PlayerCupMatchPK', TableMap::TYPE_PHPNAME, $indexType)];
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
                : self::translateFieldName('PlayerCupMatchPK', TableMap::TYPE_PHPNAME, $indexType)
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
        return $withPrefix ? PlayercupmatchesTableMap::CLASS_DEFAULT : PlayercupmatchesTableMap::OM_CLASS;
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
     * @return array           (Playercupmatches object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = PlayercupmatchesTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = PlayercupmatchesTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + PlayercupmatchesTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = PlayercupmatchesTableMap::OM_CLASS;
            /** @var Playercupmatches $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            PlayercupmatchesTableMap::addInstanceToPool($obj, $key);
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
            $key = PlayercupmatchesTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = PlayercupmatchesTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Playercupmatches $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                PlayercupmatchesTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(PlayercupmatchesTableMap::COL_PRIMARYKEY);
            $criteria->addSelectColumn(PlayercupmatchesTableMap::COL_PLAYERHOMEKEY);
            $criteria->addSelectColumn(PlayercupmatchesTableMap::COL_PLAYERAWAYKEY);
            $criteria->addSelectColumn(PlayercupmatchesTableMap::COL_CUPROUNDKEY);
            $criteria->addSelectColumn(PlayercupmatchesTableMap::COL_SEASONKEY);
            $criteria->addSelectColumn(PlayercupmatchesTableMap::COL_GROUPKEY);
            $criteria->addSelectColumn(PlayercupmatchesTableMap::COL_HOMESCORE);
            $criteria->addSelectColumn(PlayercupmatchesTableMap::COL_AWAYSCORE);
            $criteria->addSelectColumn(PlayercupmatchesTableMap::COL_SCHEDULEDATE);
            $criteria->addSelectColumn(PlayercupmatchesTableMap::COL_RESULTDATE);
        } else {
            $criteria->addSelectColumn($alias . '.PrimaryKey');
            $criteria->addSelectColumn($alias . '.PlayerHomeKey');
            $criteria->addSelectColumn($alias . '.PlayerAwayKey');
            $criteria->addSelectColumn($alias . '.CupRoundKey');
            $criteria->addSelectColumn($alias . '.SeasonKey');
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
        return Propel::getServiceContainer()->getDatabaseMap(PlayercupmatchesTableMap::DATABASE_NAME)->getTable(PlayercupmatchesTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(PlayercupmatchesTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(PlayercupmatchesTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new PlayercupmatchesTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a Playercupmatches or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Playercupmatches object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(PlayercupmatchesTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Playercupmatches) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(PlayercupmatchesTableMap::DATABASE_NAME);
            $criteria->add(PlayercupmatchesTableMap::COL_PRIMARYKEY, (array) $values, Criteria::IN);
        }

        $query = PlayercupmatchesQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            PlayercupmatchesTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                PlayercupmatchesTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the playercupmatches table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return PlayercupmatchesQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Playercupmatches or Criteria object.
     *
     * @param mixed               $criteria Criteria or Playercupmatches object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PlayercupmatchesTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Playercupmatches object
        }

        if ($criteria->containsKey(PlayercupmatchesTableMap::COL_PRIMARYKEY) && $criteria->keyContainsValue(PlayercupmatchesTableMap::COL_PRIMARYKEY) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.PlayercupmatchesTableMap::COL_PRIMARYKEY.')');
        }


        // Set the correct dbName
        $query = PlayercupmatchesQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // PlayercupmatchesTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
PlayercupmatchesTableMap::buildTableMap();
