<?php

namespace Map;

use \Matchstates;
use \MatchstatesQuery;
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
 * This class defines the structure of the 'matchstates' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class MatchstatesTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = '.Map.MatchstatesTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'matchstates';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\Matchstates';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Matchstates';

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
    const COL_PRIMARYKEY = 'matchstates.PrimaryKey';

    /**
     * the column name for the MatchKey field
     */
    const COL_MATCHKEY = 'matchstates.MatchKey';

    /**
     * the column name for the StateDate field
     */
    const COL_STATEDATE = 'matchstates.StateDate';

    /**
     * the column name for the EventKey field
     */
    const COL_EVENTKEY = 'matchstates.EventKey';

    /**
     * the column name for the TeamHomeScore field
     */
    const COL_TEAMHOMESCORE = 'matchstates.TeamHomeScore';

    /**
     * the column name for the TeamAwayScore field
     */
    const COL_TEAMAWAYSCORE = 'matchstates.TeamAwayScore';

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
        self::TYPE_PHPNAME       => array('MatchStatePK', 'Matchkey', 'Statedate', 'Eventkey', 'Teamhomescore', 'Teamawayscore', ),
        self::TYPE_CAMELNAME     => array('matchStatePK', 'matchkey', 'statedate', 'eventkey', 'teamhomescore', 'teamawayscore', ),
        self::TYPE_COLNAME       => array(MatchstatesTableMap::COL_PRIMARYKEY, MatchstatesTableMap::COL_MATCHKEY, MatchstatesTableMap::COL_STATEDATE, MatchstatesTableMap::COL_EVENTKEY, MatchstatesTableMap::COL_TEAMHOMESCORE, MatchstatesTableMap::COL_TEAMAWAYSCORE, ),
        self::TYPE_FIELDNAME     => array('PrimaryKey', 'MatchKey', 'StateDate', 'EventKey', 'TeamHomeScore', 'TeamAwayScore', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('MatchStatePK' => 0, 'Matchkey' => 1, 'Statedate' => 2, 'Eventkey' => 3, 'Teamhomescore' => 4, 'Teamawayscore' => 5, ),
        self::TYPE_CAMELNAME     => array('matchStatePK' => 0, 'matchkey' => 1, 'statedate' => 2, 'eventkey' => 3, 'teamhomescore' => 4, 'teamawayscore' => 5, ),
        self::TYPE_COLNAME       => array(MatchstatesTableMap::COL_PRIMARYKEY => 0, MatchstatesTableMap::COL_MATCHKEY => 1, MatchstatesTableMap::COL_STATEDATE => 2, MatchstatesTableMap::COL_EVENTKEY => 3, MatchstatesTableMap::COL_TEAMHOMESCORE => 4, MatchstatesTableMap::COL_TEAMAWAYSCORE => 5, ),
        self::TYPE_FIELDNAME     => array('PrimaryKey' => 0, 'MatchKey' => 1, 'StateDate' => 2, 'EventKey' => 3, 'TeamHomeScore' => 4, 'TeamAwayScore' => 5, ),
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
        $this->setName('matchstates');
        $this->setPhpName('Matchstates');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Matchstates');
        $this->setPackage('');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('PrimaryKey', 'MatchStatePK', 'INTEGER', true, null, null);
        $this->addColumn('MatchKey', 'Matchkey', 'INTEGER', true, null, null);
        $this->addColumn('StateDate', 'Statedate', 'TIMESTAMP', true, null, 'CURRENT_TIMESTAMP');
        $this->addColumn('EventKey', 'Eventkey', 'INTEGER', true, null, null);
        $this->addColumn('TeamHomeScore', 'Teamhomescore', 'INTEGER', true, null, null);
        $this->addColumn('TeamAwayScore', 'Teamawayscore', 'INTEGER', true, null, null);
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
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('MatchStatePK', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('MatchStatePK', TableMap::TYPE_PHPNAME, $indexType)];
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
                : self::translateFieldName('MatchStatePK', TableMap::TYPE_PHPNAME, $indexType)
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
        return $withPrefix ? MatchstatesTableMap::CLASS_DEFAULT : MatchstatesTableMap::OM_CLASS;
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
     * @return array           (Matchstates object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = MatchstatesTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = MatchstatesTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + MatchstatesTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = MatchstatesTableMap::OM_CLASS;
            /** @var Matchstates $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            MatchstatesTableMap::addInstanceToPool($obj, $key);
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
            $key = MatchstatesTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = MatchstatesTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Matchstates $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                MatchstatesTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(MatchstatesTableMap::COL_PRIMARYKEY);
            $criteria->addSelectColumn(MatchstatesTableMap::COL_MATCHKEY);
            $criteria->addSelectColumn(MatchstatesTableMap::COL_STATEDATE);
            $criteria->addSelectColumn(MatchstatesTableMap::COL_EVENTKEY);
            $criteria->addSelectColumn(MatchstatesTableMap::COL_TEAMHOMESCORE);
            $criteria->addSelectColumn(MatchstatesTableMap::COL_TEAMAWAYSCORE);
        } else {
            $criteria->addSelectColumn($alias . '.PrimaryKey');
            $criteria->addSelectColumn($alias . '.MatchKey');
            $criteria->addSelectColumn($alias . '.StateDate');
            $criteria->addSelectColumn($alias . '.EventKey');
            $criteria->addSelectColumn($alias . '.TeamHomeScore');
            $criteria->addSelectColumn($alias . '.TeamAwayScore');
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
        return Propel::getServiceContainer()->getDatabaseMap(MatchstatesTableMap::DATABASE_NAME)->getTable(MatchstatesTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(MatchstatesTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(MatchstatesTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new MatchstatesTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a Matchstates or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Matchstates object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(MatchstatesTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Matchstates) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(MatchstatesTableMap::DATABASE_NAME);
            $criteria->add(MatchstatesTableMap::COL_PRIMARYKEY, (array) $values, Criteria::IN);
        }

        $query = MatchstatesQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            MatchstatesTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                MatchstatesTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the matchstates table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return MatchstatesQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Matchstates or Criteria object.
     *
     * @param mixed               $criteria Criteria or Matchstates object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(MatchstatesTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Matchstates object
        }

        if ($criteria->containsKey(MatchstatesTableMap::COL_PRIMARYKEY) && $criteria->keyContainsValue(MatchstatesTableMap::COL_PRIMARYKEY) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.MatchstatesTableMap::COL_PRIMARYKEY.')');
        }


        // Set the correct dbName
        $query = MatchstatesQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // MatchstatesTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
MatchstatesTableMap::buildTableMap();
