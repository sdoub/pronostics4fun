<?php

namespace Map;

use \Forecasts;
use \ForecastsQuery;
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
 * This class defines the structure of the 'forecasts' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class ForecastsTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = '.Map.ForecastsTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'forecasts';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\Forecasts';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Forecasts';

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
     * the column name for the PrimaryKey field
     */
    const COL_PRIMARYKEY = 'forecasts.PrimaryKey';

    /**
     * the column name for the MatchKey field
     */
    const COL_MATCHKEY = 'forecasts.MatchKey';

    /**
     * the column name for the PlayerKey field
     */
    const COL_PLAYERKEY = 'forecasts.PlayerKey';

    /**
     * the column name for the TeamHomeScore field
     */
    const COL_TEAMHOMESCORE = 'forecasts.TeamHomeScore';

    /**
     * the column name for the TeamAwayScore field
     */
    const COL_TEAMAWAYSCORE = 'forecasts.TeamAwayScore';

    /**
     * the column name for the ForecastDate field
     */
    const COL_FORECASTDATE = 'forecasts.ForecastDate';

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
        self::TYPE_PHPNAME       => array('Primarykey', 'Matchkey', 'Playerkey', 'Teamhomescore', 'Teamawayscore', 'Forecastdate', ),
        self::TYPE_CAMELNAME     => array('primarykey', 'matchkey', 'playerkey', 'teamhomescore', 'teamawayscore', 'forecastdate', ),
        self::TYPE_COLNAME       => array(ForecastsTableMap::COL_PRIMARYKEY, ForecastsTableMap::COL_MATCHKEY, ForecastsTableMap::COL_PLAYERKEY, ForecastsTableMap::COL_TEAMHOMESCORE, ForecastsTableMap::COL_TEAMAWAYSCORE, ForecastsTableMap::COL_FORECASTDATE, ),
        self::TYPE_FIELDNAME     => array('PrimaryKey', 'MatchKey', 'PlayerKey', 'TeamHomeScore', 'TeamAwayScore', 'ForecastDate', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Primarykey' => 0, 'Matchkey' => 1, 'Playerkey' => 2, 'Teamhomescore' => 3, 'Teamawayscore' => 4, 'Forecastdate' => 5, ),
        self::TYPE_CAMELNAME     => array('primarykey' => 0, 'matchkey' => 1, 'playerkey' => 2, 'teamhomescore' => 3, 'teamawayscore' => 4, 'forecastdate' => 5, ),
        self::TYPE_COLNAME       => array(ForecastsTableMap::COL_PRIMARYKEY => 0, ForecastsTableMap::COL_MATCHKEY => 1, ForecastsTableMap::COL_PLAYERKEY => 2, ForecastsTableMap::COL_TEAMHOMESCORE => 3, ForecastsTableMap::COL_TEAMAWAYSCORE => 4, ForecastsTableMap::COL_FORECASTDATE => 5, ),
        self::TYPE_FIELDNAME     => array('PrimaryKey' => 0, 'MatchKey' => 1, 'PlayerKey' => 2, 'TeamHomeScore' => 3, 'TeamAwayScore' => 4, 'ForecastDate' => 5, ),
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
        $this->setName('forecasts');
        $this->setPhpName('Forecasts');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Forecasts');
        $this->setPackage('');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('PrimaryKey', 'Primarykey', 'INTEGER', true, null, null);
        $this->addColumn('MatchKey', 'Matchkey', 'INTEGER', true, null, null);
        $this->addColumn('PlayerKey', 'Playerkey', 'INTEGER', true, null, null);
        $this->addColumn('TeamHomeScore', 'Teamhomescore', 'INTEGER', true, null, null);
        $this->addColumn('TeamAwayScore', 'Teamawayscore', 'INTEGER', true, null, null);
        $this->addColumn('ForecastDate', 'Forecastdate', 'TIMESTAMP', true, null, 'CURRENT_TIMESTAMP');
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
        return $withPrefix ? ForecastsTableMap::CLASS_DEFAULT : ForecastsTableMap::OM_CLASS;
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
     * @return array           (Forecasts object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = ForecastsTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = ForecastsTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + ForecastsTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = ForecastsTableMap::OM_CLASS;
            /** @var Forecasts $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            ForecastsTableMap::addInstanceToPool($obj, $key);
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
            $key = ForecastsTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = ForecastsTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Forecasts $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                ForecastsTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(ForecastsTableMap::COL_PRIMARYKEY);
            $criteria->addSelectColumn(ForecastsTableMap::COL_MATCHKEY);
            $criteria->addSelectColumn(ForecastsTableMap::COL_PLAYERKEY);
            $criteria->addSelectColumn(ForecastsTableMap::COL_TEAMHOMESCORE);
            $criteria->addSelectColumn(ForecastsTableMap::COL_TEAMAWAYSCORE);
            $criteria->addSelectColumn(ForecastsTableMap::COL_FORECASTDATE);
        } else {
            $criteria->addSelectColumn($alias . '.PrimaryKey');
            $criteria->addSelectColumn($alias . '.MatchKey');
            $criteria->addSelectColumn($alias . '.PlayerKey');
            $criteria->addSelectColumn($alias . '.TeamHomeScore');
            $criteria->addSelectColumn($alias . '.TeamAwayScore');
            $criteria->addSelectColumn($alias . '.ForecastDate');
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
        return Propel::getServiceContainer()->getDatabaseMap(ForecastsTableMap::DATABASE_NAME)->getTable(ForecastsTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(ForecastsTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(ForecastsTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new ForecastsTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a Forecasts or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Forecasts object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(ForecastsTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Forecasts) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(ForecastsTableMap::DATABASE_NAME);
            $criteria->add(ForecastsTableMap::COL_PRIMARYKEY, (array) $values, Criteria::IN);
        }

        $query = ForecastsQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            ForecastsTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                ForecastsTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the forecasts table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return ForecastsQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Forecasts or Criteria object.
     *
     * @param mixed               $criteria Criteria or Forecasts object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ForecastsTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Forecasts object
        }

        if ($criteria->containsKey(ForecastsTableMap::COL_PRIMARYKEY) && $criteria->keyContainsValue(ForecastsTableMap::COL_PRIMARYKEY) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.ForecastsTableMap::COL_PRIMARYKEY.')');
        }


        // Set the correct dbName
        $query = ForecastsQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // ForecastsTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
ForecastsTableMap::buildTableMap();
