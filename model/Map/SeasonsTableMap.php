<?php

namespace Map;

use \Seasons;
use \SeasonsQuery;
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
 * This class defines the structure of the 'seasons' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class SeasonsTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = '.Map.SeasonsTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'seasons';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\Seasons';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Seasons';

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
    const COL_PRIMARYKEY = 'seasons.PrimaryKey';

    /**
     * the column name for the Description field
     */
    const COL_DESCRIPTION = 'seasons.Description';

    /**
     * the column name for the Code field
     */
    const COL_CODE = 'seasons.Code';

    /**
     * the column name for the Order field
     */
    const COL_ORDER = 'seasons.Order';

    /**
     * the column name for the CompetitionKey field
     */
    const COL_COMPETITIONKEY = 'seasons.CompetitionKey';

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
        self::TYPE_PHPNAME       => array('Primarykey', 'Description', 'Code', 'Order', 'Competitionkey', ),
        self::TYPE_CAMELNAME     => array('primarykey', 'description', 'code', 'order', 'competitionkey', ),
        self::TYPE_COLNAME       => array(SeasonsTableMap::COL_PRIMARYKEY, SeasonsTableMap::COL_DESCRIPTION, SeasonsTableMap::COL_CODE, SeasonsTableMap::COL_ORDER, SeasonsTableMap::COL_COMPETITIONKEY, ),
        self::TYPE_FIELDNAME     => array('PrimaryKey', 'Description', 'Code', 'Order', 'CompetitionKey', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Primarykey' => 0, 'Description' => 1, 'Code' => 2, 'Order' => 3, 'Competitionkey' => 4, ),
        self::TYPE_CAMELNAME     => array('primarykey' => 0, 'description' => 1, 'code' => 2, 'order' => 3, 'competitionkey' => 4, ),
        self::TYPE_COLNAME       => array(SeasonsTableMap::COL_PRIMARYKEY => 0, SeasonsTableMap::COL_DESCRIPTION => 1, SeasonsTableMap::COL_CODE => 2, SeasonsTableMap::COL_ORDER => 3, SeasonsTableMap::COL_COMPETITIONKEY => 4, ),
        self::TYPE_FIELDNAME     => array('PrimaryKey' => 0, 'Description' => 1, 'Code' => 2, 'Order' => 3, 'CompetitionKey' => 4, ),
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
        $this->setName('seasons');
        $this->setPhpName('Seasons');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Seasons');
        $this->setPackage('');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('PrimaryKey', 'Primarykey', 'INTEGER', true, null, null);
        $this->addColumn('Description', 'Description', 'VARCHAR', true, 50, null);
        $this->addColumn('Code', 'Code', 'VARCHAR', true, 3, null);
        $this->addColumn('Order', 'Order', 'TINYINT', true, 2, null);
        $this->addColumn('CompetitionKey', 'Competitionkey', 'INTEGER', true, null, null);
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
        return $withPrefix ? SeasonsTableMap::CLASS_DEFAULT : SeasonsTableMap::OM_CLASS;
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
     * @return array           (Seasons object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = SeasonsTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = SeasonsTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + SeasonsTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = SeasonsTableMap::OM_CLASS;
            /** @var Seasons $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            SeasonsTableMap::addInstanceToPool($obj, $key);
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
            $key = SeasonsTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = SeasonsTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Seasons $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                SeasonsTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(SeasonsTableMap::COL_PRIMARYKEY);
            $criteria->addSelectColumn(SeasonsTableMap::COL_DESCRIPTION);
            $criteria->addSelectColumn(SeasonsTableMap::COL_CODE);
            $criteria->addSelectColumn(SeasonsTableMap::COL_ORDER);
            $criteria->addSelectColumn(SeasonsTableMap::COL_COMPETITIONKEY);
        } else {
            $criteria->addSelectColumn($alias . '.PrimaryKey');
            $criteria->addSelectColumn($alias . '.Description');
            $criteria->addSelectColumn($alias . '.Code');
            $criteria->addSelectColumn($alias . '.Order');
            $criteria->addSelectColumn($alias . '.CompetitionKey');
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
        return Propel::getServiceContainer()->getDatabaseMap(SeasonsTableMap::DATABASE_NAME)->getTable(SeasonsTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(SeasonsTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(SeasonsTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new SeasonsTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a Seasons or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Seasons object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(SeasonsTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Seasons) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(SeasonsTableMap::DATABASE_NAME);
            $criteria->add(SeasonsTableMap::COL_PRIMARYKEY, (array) $values, Criteria::IN);
        }

        $query = SeasonsQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            SeasonsTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                SeasonsTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the seasons table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return SeasonsQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Seasons or Criteria object.
     *
     * @param mixed               $criteria Criteria or Seasons object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(SeasonsTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Seasons object
        }

        if ($criteria->containsKey(SeasonsTableMap::COL_PRIMARYKEY) && $criteria->keyContainsValue(SeasonsTableMap::COL_PRIMARYKEY) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.SeasonsTableMap::COL_PRIMARYKEY.')');
        }


        // Set the correct dbName
        $query = SeasonsQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // SeasonsTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
SeasonsTableMap::buildTableMap();
