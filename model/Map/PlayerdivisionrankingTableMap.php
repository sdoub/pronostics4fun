<?php

namespace Map;

use \Playerdivisionranking;
use \PlayerdivisionrankingQuery;
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
 * This class defines the structure of the 'playerdivisionranking' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class PlayerdivisionrankingTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = '.Map.PlayerdivisionrankingTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'playerdivisionranking';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\Playerdivisionranking';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Playerdivisionranking';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 12;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 12;

    /**
     * the column name for the PlayerKey field
     */
    const COL_PLAYERKEY = 'playerdivisionranking.PlayerKey';

    /**
     * the column name for the SeasonKey field
     */
    const COL_SEASONKEY = 'playerdivisionranking.SeasonKey';

    /**
     * the column name for the DivisionKey field
     */
    const COL_DIVISIONKEY = 'playerdivisionranking.DivisionKey';

    /**
     * the column name for the Score field
     */
    const COL_SCORE = 'playerdivisionranking.Score';

    /**
     * the column name for the RankDate field
     */
    const COL_RANKDATE = 'playerdivisionranking.RankDate';

    /**
     * the column name for the Rank field
     */
    const COL_RANK = 'playerdivisionranking.Rank';

    /**
     * the column name for the Won field
     */
    const COL_WON = 'playerdivisionranking.Won';

    /**
     * the column name for the Drawn field
     */
    const COL_DRAWN = 'playerdivisionranking.Drawn';

    /**
     * the column name for the Lost field
     */
    const COL_LOST = 'playerdivisionranking.Lost';

    /**
     * the column name for the PointsFor field
     */
    const COL_POINTSFOR = 'playerdivisionranking.PointsFor';

    /**
     * the column name for the PointsAgainst field
     */
    const COL_POINTSAGAINST = 'playerdivisionranking.PointsAgainst';

    /**
     * the column name for the PointsDifference field
     */
    const COL_POINTSDIFFERENCE = 'playerdivisionranking.PointsDifference';

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
        self::TYPE_PHPNAME       => array('Playerkey', 'Seasonkey', 'Divisionkey', 'Score', 'Rankdate', 'Rank', 'Won', 'Drawn', 'Lost', 'Pointsfor', 'Pointsagainst', 'Pointsdifference', ),
        self::TYPE_CAMELNAME     => array('playerkey', 'seasonkey', 'divisionkey', 'score', 'rankdate', 'rank', 'won', 'drawn', 'lost', 'pointsfor', 'pointsagainst', 'pointsdifference', ),
        self::TYPE_COLNAME       => array(PlayerdivisionrankingTableMap::COL_PLAYERKEY, PlayerdivisionrankingTableMap::COL_SEASONKEY, PlayerdivisionrankingTableMap::COL_DIVISIONKEY, PlayerdivisionrankingTableMap::COL_SCORE, PlayerdivisionrankingTableMap::COL_RANKDATE, PlayerdivisionrankingTableMap::COL_RANK, PlayerdivisionrankingTableMap::COL_WON, PlayerdivisionrankingTableMap::COL_DRAWN, PlayerdivisionrankingTableMap::COL_LOST, PlayerdivisionrankingTableMap::COL_POINTSFOR, PlayerdivisionrankingTableMap::COL_POINTSAGAINST, PlayerdivisionrankingTableMap::COL_POINTSDIFFERENCE, ),
        self::TYPE_FIELDNAME     => array('PlayerKey', 'SeasonKey', 'DivisionKey', 'Score', 'RankDate', 'Rank', 'Won', 'Drawn', 'Lost', 'PointsFor', 'PointsAgainst', 'PointsDifference', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Playerkey' => 0, 'Seasonkey' => 1, 'Divisionkey' => 2, 'Score' => 3, 'Rankdate' => 4, 'Rank' => 5, 'Won' => 6, 'Drawn' => 7, 'Lost' => 8, 'Pointsfor' => 9, 'Pointsagainst' => 10, 'Pointsdifference' => 11, ),
        self::TYPE_CAMELNAME     => array('playerkey' => 0, 'seasonkey' => 1, 'divisionkey' => 2, 'score' => 3, 'rankdate' => 4, 'rank' => 5, 'won' => 6, 'drawn' => 7, 'lost' => 8, 'pointsfor' => 9, 'pointsagainst' => 10, 'pointsdifference' => 11, ),
        self::TYPE_COLNAME       => array(PlayerdivisionrankingTableMap::COL_PLAYERKEY => 0, PlayerdivisionrankingTableMap::COL_SEASONKEY => 1, PlayerdivisionrankingTableMap::COL_DIVISIONKEY => 2, PlayerdivisionrankingTableMap::COL_SCORE => 3, PlayerdivisionrankingTableMap::COL_RANKDATE => 4, PlayerdivisionrankingTableMap::COL_RANK => 5, PlayerdivisionrankingTableMap::COL_WON => 6, PlayerdivisionrankingTableMap::COL_DRAWN => 7, PlayerdivisionrankingTableMap::COL_LOST => 8, PlayerdivisionrankingTableMap::COL_POINTSFOR => 9, PlayerdivisionrankingTableMap::COL_POINTSAGAINST => 10, PlayerdivisionrankingTableMap::COL_POINTSDIFFERENCE => 11, ),
        self::TYPE_FIELDNAME     => array('PlayerKey' => 0, 'SeasonKey' => 1, 'DivisionKey' => 2, 'Score' => 3, 'RankDate' => 4, 'Rank' => 5, 'Won' => 6, 'Drawn' => 7, 'Lost' => 8, 'PointsFor' => 9, 'PointsAgainst' => 10, 'PointsDifference' => 11, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, )
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
        $this->setName('playerdivisionranking');
        $this->setPhpName('Playerdivisionranking');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Playerdivisionranking');
        $this->setPackage('');
        $this->setUseIdGenerator(false);
        // columns
        $this->addPrimaryKey('PlayerKey', 'Playerkey', 'INTEGER', true, null, null);
        $this->addPrimaryKey('SeasonKey', 'Seasonkey', 'INTEGER', true, null, null);
        $this->addPrimaryKey('DivisionKey', 'Divisionkey', 'INTEGER', true, null, null);
        $this->addColumn('Score', 'Score', 'TINYINT', true, 2, null);
        $this->addPrimaryKey('RankDate', 'Rankdate', 'TIMESTAMP', true, null, 'CURRENT_TIMESTAMP');
        $this->addColumn('Rank', 'Rank', 'TINYINT', true, 2, null);
        $this->addColumn('Won', 'Won', 'TINYINT', true, 2, null);
        $this->addColumn('Drawn', 'Drawn', 'TINYINT', true, 2, null);
        $this->addColumn('Lost', 'Lost', 'TINYINT', true, 2, null);
        $this->addColumn('PointsFor', 'Pointsfor', 'SMALLINT', true, 4, null);
        $this->addColumn('PointsAgainst', 'Pointsagainst', 'SMALLINT', true, 4, null);
        $this->addColumn('PointsDifference', 'Pointsdifference', 'SMALLINT', true, 4, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
    } // buildRelations()

    /**
     * Adds an object to the instance pool.
     *
     * Propel keeps cached copies of objects in an instance pool when they are retrieved
     * from the database. In some cases you may need to explicitly add objects
     * to the cache in order to ensure that the same objects are always returned by find*()
     * and findPk*() calls.
     *
     * @param \Playerdivisionranking $obj A \Playerdivisionranking object.
     * @param string $key             (optional) key to use for instance map (for performance boost if key was already calculated externally).
     */
    public static function addInstanceToPool($obj, $key = null)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if (null === $key) {
                $key = serialize(array((string) $obj->getPlayerkey(), (string) $obj->getSeasonkey(), (string) $obj->getDivisionkey(), (string) $obj->getRankdate()));
            } // if key === null
            self::$instances[$key] = $obj;
        }
    }

    /**
     * Removes an object from the instance pool.
     *
     * Propel keeps cached copies of objects in an instance pool when they are retrieved
     * from the database.  In some cases -- especially when you override doDelete
     * methods in your stub classes -- you may need to explicitly remove objects
     * from the cache in order to prevent returning objects that no longer exist.
     *
     * @param mixed $value A \Playerdivisionranking object or a primary key value.
     */
    public static function removeInstanceFromPool($value)
    {
        if (Propel::isInstancePoolingEnabled() && null !== $value) {
            if (is_object($value) && $value instanceof \Playerdivisionranking) {
                $key = serialize(array((string) $value->getPlayerkey(), (string) $value->getSeasonkey(), (string) $value->getDivisionkey(), (string) $value->getRankdate()));

            } elseif (is_array($value) && count($value) === 4) {
                // assume we've been passed a primary key";
                $key = serialize(array((string) $value[0], (string) $value[1], (string) $value[2], (string) $value[3]));
            } elseif ($value instanceof Criteria) {
                self::$instances = [];

                return;
            } else {
                $e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or \Playerdivisionranking object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value, true)));
                throw $e;
            }

            unset(self::$instances[$key]);
        }
    }

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
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Playerkey', TableMap::TYPE_PHPNAME, $indexType)] === null && $row[TableMap::TYPE_NUM == $indexType ? 1 + $offset : static::translateFieldName('Seasonkey', TableMap::TYPE_PHPNAME, $indexType)] === null && $row[TableMap::TYPE_NUM == $indexType ? 2 + $offset : static::translateFieldName('Divisionkey', TableMap::TYPE_PHPNAME, $indexType)] === null && $row[TableMap::TYPE_NUM == $indexType ? 4 + $offset : static::translateFieldName('Rankdate', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return serialize(array((string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Playerkey', TableMap::TYPE_PHPNAME, $indexType)], (string) $row[TableMap::TYPE_NUM == $indexType ? 1 + $offset : static::translateFieldName('Seasonkey', TableMap::TYPE_PHPNAME, $indexType)], (string) $row[TableMap::TYPE_NUM == $indexType ? 2 + $offset : static::translateFieldName('Divisionkey', TableMap::TYPE_PHPNAME, $indexType)], (string) $row[TableMap::TYPE_NUM == $indexType ? 4 + $offset : static::translateFieldName('Rankdate', TableMap::TYPE_PHPNAME, $indexType)]));
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
            $pks = [];

        $pks[] = (int) $row[
            $indexType == TableMap::TYPE_NUM
                ? 0 + $offset
                : self::translateFieldName('Playerkey', TableMap::TYPE_PHPNAME, $indexType)
        ];
        $pks[] = (int) $row[
            $indexType == TableMap::TYPE_NUM
                ? 1 + $offset
                : self::translateFieldName('Seasonkey', TableMap::TYPE_PHPNAME, $indexType)
        ];
        $pks[] = (int) $row[
            $indexType == TableMap::TYPE_NUM
                ? 2 + $offset
                : self::translateFieldName('Divisionkey', TableMap::TYPE_PHPNAME, $indexType)
        ];
        $pks[] = (string) $row[
            $indexType == TableMap::TYPE_NUM
                ? 4 + $offset
                : self::translateFieldName('Rankdate', TableMap::TYPE_PHPNAME, $indexType)
        ];

        return $pks;
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
        return $withPrefix ? PlayerdivisionrankingTableMap::CLASS_DEFAULT : PlayerdivisionrankingTableMap::OM_CLASS;
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
     * @return array           (Playerdivisionranking object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = PlayerdivisionrankingTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = PlayerdivisionrankingTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + PlayerdivisionrankingTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = PlayerdivisionrankingTableMap::OM_CLASS;
            /** @var Playerdivisionranking $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            PlayerdivisionrankingTableMap::addInstanceToPool($obj, $key);
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
            $key = PlayerdivisionrankingTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = PlayerdivisionrankingTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Playerdivisionranking $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                PlayerdivisionrankingTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(PlayerdivisionrankingTableMap::COL_PLAYERKEY);
            $criteria->addSelectColumn(PlayerdivisionrankingTableMap::COL_SEASONKEY);
            $criteria->addSelectColumn(PlayerdivisionrankingTableMap::COL_DIVISIONKEY);
            $criteria->addSelectColumn(PlayerdivisionrankingTableMap::COL_SCORE);
            $criteria->addSelectColumn(PlayerdivisionrankingTableMap::COL_RANKDATE);
            $criteria->addSelectColumn(PlayerdivisionrankingTableMap::COL_RANK);
            $criteria->addSelectColumn(PlayerdivisionrankingTableMap::COL_WON);
            $criteria->addSelectColumn(PlayerdivisionrankingTableMap::COL_DRAWN);
            $criteria->addSelectColumn(PlayerdivisionrankingTableMap::COL_LOST);
            $criteria->addSelectColumn(PlayerdivisionrankingTableMap::COL_POINTSFOR);
            $criteria->addSelectColumn(PlayerdivisionrankingTableMap::COL_POINTSAGAINST);
            $criteria->addSelectColumn(PlayerdivisionrankingTableMap::COL_POINTSDIFFERENCE);
        } else {
            $criteria->addSelectColumn($alias . '.PlayerKey');
            $criteria->addSelectColumn($alias . '.SeasonKey');
            $criteria->addSelectColumn($alias . '.DivisionKey');
            $criteria->addSelectColumn($alias . '.Score');
            $criteria->addSelectColumn($alias . '.RankDate');
            $criteria->addSelectColumn($alias . '.Rank');
            $criteria->addSelectColumn($alias . '.Won');
            $criteria->addSelectColumn($alias . '.Drawn');
            $criteria->addSelectColumn($alias . '.Lost');
            $criteria->addSelectColumn($alias . '.PointsFor');
            $criteria->addSelectColumn($alias . '.PointsAgainst');
            $criteria->addSelectColumn($alias . '.PointsDifference');
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
        return Propel::getServiceContainer()->getDatabaseMap(PlayerdivisionrankingTableMap::DATABASE_NAME)->getTable(PlayerdivisionrankingTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(PlayerdivisionrankingTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(PlayerdivisionrankingTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new PlayerdivisionrankingTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a Playerdivisionranking or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Playerdivisionranking object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(PlayerdivisionrankingTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Playerdivisionranking) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(PlayerdivisionrankingTableMap::DATABASE_NAME);
            // primary key is composite; we therefore, expect
            // the primary key passed to be an array of pkey values
            if (count($values) == count($values, COUNT_RECURSIVE)) {
                // array is not multi-dimensional
                $values = array($values);
            }
            foreach ($values as $value) {
                $criterion = $criteria->getNewCriterion(PlayerdivisionrankingTableMap::COL_PLAYERKEY, $value[0]);
                $criterion->addAnd($criteria->getNewCriterion(PlayerdivisionrankingTableMap::COL_SEASONKEY, $value[1]));
                $criterion->addAnd($criteria->getNewCriterion(PlayerdivisionrankingTableMap::COL_DIVISIONKEY, $value[2]));
                $criterion->addAnd($criteria->getNewCriterion(PlayerdivisionrankingTableMap::COL_RANKDATE, $value[3]));
                $criteria->addOr($criterion);
            }
        }

        $query = PlayerdivisionrankingQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            PlayerdivisionrankingTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                PlayerdivisionrankingTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the playerdivisionranking table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return PlayerdivisionrankingQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Playerdivisionranking or Criteria object.
     *
     * @param mixed               $criteria Criteria or Playerdivisionranking object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PlayerdivisionrankingTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Playerdivisionranking object
        }


        // Set the correct dbName
        $query = PlayerdivisionrankingQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // PlayerdivisionrankingTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
PlayerdivisionrankingTableMap::buildTableMap();
