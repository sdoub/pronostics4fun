<?php

namespace Map;

use \Groups;
use \GroupsQuery;
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
 * This class defines the structure of the 'groups' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class GroupsTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = '.Map.GroupsTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'groups';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\Groups';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Groups';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 9;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 9;

    /**
     * the column name for the PrimaryKey field
     */
    const COL_PRIMARYKEY = 'groups.PrimaryKey';

    /**
     * the column name for the Description field
     */
    const COL_DESCRIPTION = 'groups.Description';

    /**
     * the column name for the Code field
     */
    const COL_CODE = 'groups.Code';

    /**
     * the column name for the CompetitionKey field
     */
    const COL_COMPETITIONKEY = 'groups.CompetitionKey';

    /**
     * the column name for the BeginDate field
     */
    const COL_BEGINDATE = 'groups.BeginDate';

    /**
     * the column name for the EndDate field
     */
    const COL_ENDDATE = 'groups.EndDate';

    /**
     * the column name for the Status field
     */
    const COL_STATUS = 'groups.Status';

    /**
     * the column name for the IsCompleted field
     */
    const COL_ISCOMPLETED = 'groups.IsCompleted';

    /**
     * the column name for the DayKey field
     */
    const COL_DAYKEY = 'groups.DayKey';

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
        self::TYPE_PHPNAME       => array('GroupPK', 'Description', 'Code', 'Competitionkey', 'Begindate', 'Enddate', 'Status', 'Iscompleted', 'Daykey', ),
        self::TYPE_CAMELNAME     => array('groupPK', 'description', 'code', 'competitionkey', 'begindate', 'enddate', 'status', 'iscompleted', 'daykey', ),
        self::TYPE_COLNAME       => array(GroupsTableMap::COL_PRIMARYKEY, GroupsTableMap::COL_DESCRIPTION, GroupsTableMap::COL_CODE, GroupsTableMap::COL_COMPETITIONKEY, GroupsTableMap::COL_BEGINDATE, GroupsTableMap::COL_ENDDATE, GroupsTableMap::COL_STATUS, GroupsTableMap::COL_ISCOMPLETED, GroupsTableMap::COL_DAYKEY, ),
        self::TYPE_FIELDNAME     => array('PrimaryKey', 'Description', 'Code', 'CompetitionKey', 'BeginDate', 'EndDate', 'Status', 'IsCompleted', 'DayKey', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('GroupPK' => 0, 'Description' => 1, 'Code' => 2, 'Competitionkey' => 3, 'Begindate' => 4, 'Enddate' => 5, 'Status' => 6, 'Iscompleted' => 7, 'Daykey' => 8, ),
        self::TYPE_CAMELNAME     => array('groupPK' => 0, 'description' => 1, 'code' => 2, 'competitionkey' => 3, 'begindate' => 4, 'enddate' => 5, 'status' => 6, 'iscompleted' => 7, 'daykey' => 8, ),
        self::TYPE_COLNAME       => array(GroupsTableMap::COL_PRIMARYKEY => 0, GroupsTableMap::COL_DESCRIPTION => 1, GroupsTableMap::COL_CODE => 2, GroupsTableMap::COL_COMPETITIONKEY => 3, GroupsTableMap::COL_BEGINDATE => 4, GroupsTableMap::COL_ENDDATE => 5, GroupsTableMap::COL_STATUS => 6, GroupsTableMap::COL_ISCOMPLETED => 7, GroupsTableMap::COL_DAYKEY => 8, ),
        self::TYPE_FIELDNAME     => array('PrimaryKey' => 0, 'Description' => 1, 'Code' => 2, 'CompetitionKey' => 3, 'BeginDate' => 4, 'EndDate' => 5, 'Status' => 6, 'IsCompleted' => 7, 'DayKey' => 8, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, )
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
        $this->setName('groups');
        $this->setPhpName('Groups');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Groups');
        $this->setPackage('');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('PrimaryKey', 'GroupPK', 'INTEGER', true, null, null);
        $this->addColumn('Description', 'Description', 'VARCHAR', true, 30, null);
        $this->addColumn('Code', 'Code', 'VARCHAR', true, 10, null);
        $this->addColumn('CompetitionKey', 'Competitionkey', 'INTEGER', true, null, null);
        $this->addColumn('BeginDate', 'Begindate', 'TIMESTAMP', false, null, null);
        $this->addColumn('EndDate', 'Enddate', 'TIMESTAMP', false, null, null);
        $this->addColumn('Status', 'Status', 'BOOLEAN', true, 1, null);
        $this->addColumn('IsCompleted', 'Iscompleted', 'BOOLEAN', true, 1, false);
        $this->addColumn('DayKey', 'Daykey', 'INTEGER', false, null, null);
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
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('GroupPK', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('GroupPK', TableMap::TYPE_PHPNAME, $indexType)];
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
                : self::translateFieldName('GroupPK', TableMap::TYPE_PHPNAME, $indexType)
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
        return $withPrefix ? GroupsTableMap::CLASS_DEFAULT : GroupsTableMap::OM_CLASS;
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
     * @return array           (Groups object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = GroupsTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = GroupsTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + GroupsTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = GroupsTableMap::OM_CLASS;
            /** @var Groups $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            GroupsTableMap::addInstanceToPool($obj, $key);
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
            $key = GroupsTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = GroupsTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Groups $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                GroupsTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(GroupsTableMap::COL_PRIMARYKEY);
            $criteria->addSelectColumn(GroupsTableMap::COL_DESCRIPTION);
            $criteria->addSelectColumn(GroupsTableMap::COL_CODE);
            $criteria->addSelectColumn(GroupsTableMap::COL_COMPETITIONKEY);
            $criteria->addSelectColumn(GroupsTableMap::COL_BEGINDATE);
            $criteria->addSelectColumn(GroupsTableMap::COL_ENDDATE);
            $criteria->addSelectColumn(GroupsTableMap::COL_STATUS);
            $criteria->addSelectColumn(GroupsTableMap::COL_ISCOMPLETED);
            $criteria->addSelectColumn(GroupsTableMap::COL_DAYKEY);
        } else {
            $criteria->addSelectColumn($alias . '.PrimaryKey');
            $criteria->addSelectColumn($alias . '.Description');
            $criteria->addSelectColumn($alias . '.Code');
            $criteria->addSelectColumn($alias . '.CompetitionKey');
            $criteria->addSelectColumn($alias . '.BeginDate');
            $criteria->addSelectColumn($alias . '.EndDate');
            $criteria->addSelectColumn($alias . '.Status');
            $criteria->addSelectColumn($alias . '.IsCompleted');
            $criteria->addSelectColumn($alias . '.DayKey');
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
        return Propel::getServiceContainer()->getDatabaseMap(GroupsTableMap::DATABASE_NAME)->getTable(GroupsTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(GroupsTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(GroupsTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new GroupsTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a Groups or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Groups object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(GroupsTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Groups) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(GroupsTableMap::DATABASE_NAME);
            $criteria->add(GroupsTableMap::COL_PRIMARYKEY, (array) $values, Criteria::IN);
        }

        $query = GroupsQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            GroupsTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                GroupsTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the groups table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return GroupsQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Groups or Criteria object.
     *
     * @param mixed               $criteria Criteria or Groups object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(GroupsTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Groups object
        }

        if ($criteria->containsKey(GroupsTableMap::COL_PRIMARYKEY) && $criteria->keyContainsValue(GroupsTableMap::COL_PRIMARYKEY) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.GroupsTableMap::COL_PRIMARYKEY.')');
        }


        // Set the correct dbName
        $query = GroupsQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // GroupsTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
GroupsTableMap::buildTableMap();
