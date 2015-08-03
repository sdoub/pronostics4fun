<?php

namespace Map;

use \Lineups;
use \LineupsQuery;
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
 * This class defines the structure of the 'lineups' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class LineupsTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = '.Map.LineupsTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'lineups';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\Lineups';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Lineups';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 6;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 6;

    /**
     * the column name for the MatchKey field
     */
    const COL_MATCHKEY = 'lineups.MatchKey';

    /**
     * the column name for the TeamKey field
     */
    const COL_TEAMKEY = 'lineups.TeamKey';

    /**
     * the column name for the TeamPlayerKey field
     */
    const COL_TEAMPLAYERKEY = 'lineups.TeamPlayerKey';

    /**
     * the column name for the IsSubstitute field
     */
    const COL_ISSUBSTITUTE = 'lineups.IsSubstitute';

    /**
     * the column name for the TimeIn field
     */
    const COL_TIMEIN = 'lineups.TimeIn';

    /**
     * the column name for the TeamPlayerReplacedKey field
     */
    const COL_TEAMPLAYERREPLACEDKEY = 'lineups.TeamPlayerReplacedKey';

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
        self::TYPE_PHPNAME       => array('Matchkey', 'Teamkey', 'Teamplayerkey', 'Issubstitute', 'Timein', 'Teamplayerreplacedkey', ),
        self::TYPE_CAMELNAME     => array('matchkey', 'teamkey', 'teamplayerkey', 'issubstitute', 'timein', 'teamplayerreplacedkey', ),
        self::TYPE_COLNAME       => array(LineupsTableMap::COL_MATCHKEY, LineupsTableMap::COL_TEAMKEY, LineupsTableMap::COL_TEAMPLAYERKEY, LineupsTableMap::COL_ISSUBSTITUTE, LineupsTableMap::COL_TIMEIN, LineupsTableMap::COL_TEAMPLAYERREPLACEDKEY, ),
        self::TYPE_FIELDNAME     => array('MatchKey', 'TeamKey', 'TeamPlayerKey', 'IsSubstitute', 'TimeIn', 'TeamPlayerReplacedKey', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Matchkey' => 0, 'Teamkey' => 1, 'Teamplayerkey' => 2, 'Issubstitute' => 3, 'Timein' => 4, 'Teamplayerreplacedkey' => 5, ),
        self::TYPE_CAMELNAME     => array('matchkey' => 0, 'teamkey' => 1, 'teamplayerkey' => 2, 'issubstitute' => 3, 'timein' => 4, 'teamplayerreplacedkey' => 5, ),
        self::TYPE_COLNAME       => array(LineupsTableMap::COL_MATCHKEY => 0, LineupsTableMap::COL_TEAMKEY => 1, LineupsTableMap::COL_TEAMPLAYERKEY => 2, LineupsTableMap::COL_ISSUBSTITUTE => 3, LineupsTableMap::COL_TIMEIN => 4, LineupsTableMap::COL_TEAMPLAYERREPLACEDKEY => 5, ),
        self::TYPE_FIELDNAME     => array('MatchKey' => 0, 'TeamKey' => 1, 'TeamPlayerKey' => 2, 'IsSubstitute' => 3, 'TimeIn' => 4, 'TeamPlayerReplacedKey' => 5, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, )
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
        $this->setName('lineups');
        $this->setPhpName('Lineups');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Lineups');
        $this->setPackage('');
        $this->setUseIdGenerator(false);
        // columns
        $this->addForeignPrimaryKey('MatchKey', 'Matchkey', 'INTEGER' , 'matches', 'PrimaryKey', true, null, null);
        $this->addForeignPrimaryKey('TeamKey', 'Teamkey', 'INTEGER' , 'teams', 'PrimaryKey', true, null, null);
        $this->addForeignPrimaryKey('TeamPlayerKey', 'Teamplayerkey', 'INTEGER' , 'teamplayers', 'PrimaryKey', true, null, null);
        $this->addColumn('IsSubstitute', 'Issubstitute', 'BOOLEAN', true, 1, false);
        $this->addColumn('TimeIn', 'Timein', 'INTEGER', false, null, null);
        $this->addColumn('TeamPlayerReplacedKey', 'Teamplayerreplacedkey', 'INTEGER', false, null, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('LineUpMatch', '\\Matches', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':MatchKey',
    1 => ':PrimaryKey',
  ),
), null, null, null, false);
        $this->addRelation('LineUpTeam', '\\Teams', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':TeamKey',
    1 => ':PrimaryKey',
  ),
), null, null, null, false);
        $this->addRelation('LineUpTeamPlayer', '\\Teamplayers', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':TeamPlayerKey',
    1 => ':PrimaryKey',
  ),
), null, null, null, false);
    } // buildRelations()

    /**
     * Adds an object to the instance pool.
     *
     * Propel keeps cached copies of objects in an instance pool when they are retrieved
     * from the database. In some cases you may need to explicitly add objects
     * to the cache in order to ensure that the same objects are always returned by find*()
     * and findPk*() calls.
     *
     * @param \Lineups $obj A \Lineups object.
     * @param string $key             (optional) key to use for instance map (for performance boost if key was already calculated externally).
     */
    public static function addInstanceToPool($obj, $key = null)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if (null === $key) {
                $key = serialize(array((string) $obj->getMatchkey(), (string) $obj->getTeamkey(), (string) $obj->getTeamplayerkey()));
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
     * @param mixed $value A \Lineups object or a primary key value.
     */
    public static function removeInstanceFromPool($value)
    {
        if (Propel::isInstancePoolingEnabled() && null !== $value) {
            if (is_object($value) && $value instanceof \Lineups) {
                $key = serialize(array((string) $value->getMatchkey(), (string) $value->getTeamkey(), (string) $value->getTeamplayerkey()));

            } elseif (is_array($value) && count($value) === 3) {
                // assume we've been passed a primary key";
                $key = serialize(array((string) $value[0], (string) $value[1], (string) $value[2]));
            } elseif ($value instanceof Criteria) {
                self::$instances = [];

                return;
            } else {
                $e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or \Lineups object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value, true)));
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
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Matchkey', TableMap::TYPE_PHPNAME, $indexType)] === null && $row[TableMap::TYPE_NUM == $indexType ? 1 + $offset : static::translateFieldName('Teamkey', TableMap::TYPE_PHPNAME, $indexType)] === null && $row[TableMap::TYPE_NUM == $indexType ? 2 + $offset : static::translateFieldName('Teamplayerkey', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return serialize(array((string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Matchkey', TableMap::TYPE_PHPNAME, $indexType)], (string) $row[TableMap::TYPE_NUM == $indexType ? 1 + $offset : static::translateFieldName('Teamkey', TableMap::TYPE_PHPNAME, $indexType)], (string) $row[TableMap::TYPE_NUM == $indexType ? 2 + $offset : static::translateFieldName('Teamplayerkey', TableMap::TYPE_PHPNAME, $indexType)]));
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
                : self::translateFieldName('Matchkey', TableMap::TYPE_PHPNAME, $indexType)
        ];
        $pks[] = (int) $row[
            $indexType == TableMap::TYPE_NUM
                ? 1 + $offset
                : self::translateFieldName('Teamkey', TableMap::TYPE_PHPNAME, $indexType)
        ];
        $pks[] = (int) $row[
            $indexType == TableMap::TYPE_NUM
                ? 2 + $offset
                : self::translateFieldName('Teamplayerkey', TableMap::TYPE_PHPNAME, $indexType)
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
        return $withPrefix ? LineupsTableMap::CLASS_DEFAULT : LineupsTableMap::OM_CLASS;
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
     * @return array           (Lineups object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = LineupsTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = LineupsTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + LineupsTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = LineupsTableMap::OM_CLASS;
            /** @var Lineups $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            LineupsTableMap::addInstanceToPool($obj, $key);
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
            $key = LineupsTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = LineupsTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Lineups $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                LineupsTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(LineupsTableMap::COL_MATCHKEY);
            $criteria->addSelectColumn(LineupsTableMap::COL_TEAMKEY);
            $criteria->addSelectColumn(LineupsTableMap::COL_TEAMPLAYERKEY);
            $criteria->addSelectColumn(LineupsTableMap::COL_ISSUBSTITUTE);
            $criteria->addSelectColumn(LineupsTableMap::COL_TIMEIN);
            $criteria->addSelectColumn(LineupsTableMap::COL_TEAMPLAYERREPLACEDKEY);
        } else {
            $criteria->addSelectColumn($alias . '.MatchKey');
            $criteria->addSelectColumn($alias . '.TeamKey');
            $criteria->addSelectColumn($alias . '.TeamPlayerKey');
            $criteria->addSelectColumn($alias . '.IsSubstitute');
            $criteria->addSelectColumn($alias . '.TimeIn');
            $criteria->addSelectColumn($alias . '.TeamPlayerReplacedKey');
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
        return Propel::getServiceContainer()->getDatabaseMap(LineupsTableMap::DATABASE_NAME)->getTable(LineupsTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(LineupsTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(LineupsTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new LineupsTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a Lineups or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Lineups object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(LineupsTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Lineups) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(LineupsTableMap::DATABASE_NAME);
            // primary key is composite; we therefore, expect
            // the primary key passed to be an array of pkey values
            if (count($values) == count($values, COUNT_RECURSIVE)) {
                // array is not multi-dimensional
                $values = array($values);
            }
            foreach ($values as $value) {
                $criterion = $criteria->getNewCriterion(LineupsTableMap::COL_MATCHKEY, $value[0]);
                $criterion->addAnd($criteria->getNewCriterion(LineupsTableMap::COL_TEAMKEY, $value[1]));
                $criterion->addAnd($criteria->getNewCriterion(LineupsTableMap::COL_TEAMPLAYERKEY, $value[2]));
                $criteria->addOr($criterion);
            }
        }

        $query = LineupsQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            LineupsTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                LineupsTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the lineups table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return LineupsQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Lineups or Criteria object.
     *
     * @param mixed               $criteria Criteria or Lineups object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(LineupsTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Lineups object
        }


        // Set the correct dbName
        $query = LineupsQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // LineupsTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
LineupsTableMap::buildTableMap();
