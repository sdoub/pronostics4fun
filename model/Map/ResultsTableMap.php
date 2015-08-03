<?php

namespace Map;

use \Results;
use \ResultsQuery;
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
 * This class defines the structure of the 'results' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class ResultsTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = '.Map.ResultsTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'results';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\Results';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Results';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 5;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 5;

    /**
     * the column name for the PrimaryKey field
     */
    const COL_PRIMARYKEY = 'results.PrimaryKey';

    /**
     * the column name for the MatchKey field
     */
    const COL_MATCHKEY = 'results.MatchKey';

    /**
     * the column name for the LiveStatus field
     */
    const COL_LIVESTATUS = 'results.LiveStatus';

    /**
     * the column name for the ActualTime field
     */
    const COL_ACTUALTIME = 'results.ActualTime';

    /**
     * the column name for the ResultDate field
     */
    const COL_RESULTDATE = 'results.ResultDate';

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
        self::TYPE_PHPNAME       => array('ResultPK', 'Matchkey', 'Livestatus', 'Actualtime', 'Resultdate', ),
        self::TYPE_CAMELNAME     => array('resultPK', 'matchkey', 'livestatus', 'actualtime', 'resultdate', ),
        self::TYPE_COLNAME       => array(ResultsTableMap::COL_PRIMARYKEY, ResultsTableMap::COL_MATCHKEY, ResultsTableMap::COL_LIVESTATUS, ResultsTableMap::COL_ACTUALTIME, ResultsTableMap::COL_RESULTDATE, ),
        self::TYPE_FIELDNAME     => array('PrimaryKey', 'MatchKey', 'LiveStatus', 'ActualTime', 'ResultDate', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('ResultPK' => 0, 'Matchkey' => 1, 'Livestatus' => 2, 'Actualtime' => 3, 'Resultdate' => 4, ),
        self::TYPE_CAMELNAME     => array('resultPK' => 0, 'matchkey' => 1, 'livestatus' => 2, 'actualtime' => 3, 'resultdate' => 4, ),
        self::TYPE_COLNAME       => array(ResultsTableMap::COL_PRIMARYKEY => 0, ResultsTableMap::COL_MATCHKEY => 1, ResultsTableMap::COL_LIVESTATUS => 2, ResultsTableMap::COL_ACTUALTIME => 3, ResultsTableMap::COL_RESULTDATE => 4, ),
        self::TYPE_FIELDNAME     => array('PrimaryKey' => 0, 'MatchKey' => 1, 'LiveStatus' => 2, 'ActualTime' => 3, 'ResultDate' => 4, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, )
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
        $this->setName('results');
        $this->setPhpName('Results');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Results');
        $this->setPackage('');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('PrimaryKey', 'ResultPK', 'INTEGER', true, null, null);
        $this->addForeignKey('MatchKey', 'Matchkey', 'INTEGER', 'matches', 'PrimaryKey', true, null, null);
        $this->addColumn('LiveStatus', 'Livestatus', 'INTEGER', true, null, null);
        $this->addColumn('ActualTime', 'Actualtime', 'INTEGER', true, null, null);
        $this->addColumn('ResultDate', 'Resultdate', 'TIMESTAMP', true, null, 'CURRENT_TIMESTAMP');
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Matches', '\\Matches', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':MatchKey',
    1 => ':PrimaryKey',
  ),
), null, null, null, false);
        $this->addRelation('Events', '\\Events', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':ResultKey',
    1 => ':PrimaryKey',
  ),
), null, null, 'Eventss', false);
    } // buildRelations()

    /**
     *
     * Gets the list of behaviors registered for this table
     *
     * @return array Associative array (name => parameters) of behaviors
     */
    public function getBehaviors()
    {
        return array(
            'delegate' => array('to' => 'matches', ),
        );
    } // getBehaviors()

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
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('ResultPK', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('ResultPK', TableMap::TYPE_PHPNAME, $indexType)];
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
                : self::translateFieldName('ResultPK', TableMap::TYPE_PHPNAME, $indexType)
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
        return $withPrefix ? ResultsTableMap::CLASS_DEFAULT : ResultsTableMap::OM_CLASS;
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
     * @return array           (Results object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = ResultsTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = ResultsTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + ResultsTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = ResultsTableMap::OM_CLASS;
            /** @var Results $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            ResultsTableMap::addInstanceToPool($obj, $key);
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
            $key = ResultsTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = ResultsTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Results $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                ResultsTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(ResultsTableMap::COL_PRIMARYKEY);
            $criteria->addSelectColumn(ResultsTableMap::COL_MATCHKEY);
            $criteria->addSelectColumn(ResultsTableMap::COL_LIVESTATUS);
            $criteria->addSelectColumn(ResultsTableMap::COL_ACTUALTIME);
            $criteria->addSelectColumn(ResultsTableMap::COL_RESULTDATE);
        } else {
            $criteria->addSelectColumn($alias . '.PrimaryKey');
            $criteria->addSelectColumn($alias . '.MatchKey');
            $criteria->addSelectColumn($alias . '.LiveStatus');
            $criteria->addSelectColumn($alias . '.ActualTime');
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
        return Propel::getServiceContainer()->getDatabaseMap(ResultsTableMap::DATABASE_NAME)->getTable(ResultsTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(ResultsTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(ResultsTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new ResultsTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a Results or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Results object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(ResultsTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Results) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(ResultsTableMap::DATABASE_NAME);
            $criteria->add(ResultsTableMap::COL_PRIMARYKEY, (array) $values, Criteria::IN);
        }

        $query = ResultsQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            ResultsTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                ResultsTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the results table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return ResultsQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Results or Criteria object.
     *
     * @param mixed               $criteria Criteria or Results object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ResultsTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Results object
        }

        if ($criteria->containsKey(ResultsTableMap::COL_PRIMARYKEY) && $criteria->keyContainsValue(ResultsTableMap::COL_PRIMARYKEY) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.ResultsTableMap::COL_PRIMARYKEY.')');
        }


        // Set the correct dbName
        $query = ResultsQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // ResultsTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
ResultsTableMap::buildTableMap();
