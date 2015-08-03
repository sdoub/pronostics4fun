<?php

namespace Map;

use \Events;
use \EventsQuery;
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
 * This class defines the structure of the 'events' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class EventsTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = '.Map.EventsTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'events';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\Events';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Events';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 7;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 7;

    /**
     * the column name for the PrimaryKey field
     */
    const COL_PRIMARYKEY = 'events.PrimaryKey';

    /**
     * the column name for the ResultKey field
     */
    const COL_RESULTKEY = 'events.ResultKey';

    /**
     * the column name for the TeamPlayerKey field
     */
    const COL_TEAMPLAYERKEY = 'events.TeamPlayerKey';

    /**
     * the column name for the EventTime field
     */
    const COL_EVENTTIME = 'events.EventTime';

    /**
     * the column name for the EventType field
     */
    const COL_EVENTTYPE = 'events.EventType';

    /**
     * the column name for the Half field
     */
    const COL_HALF = 'events.Half';

    /**
     * the column name for the TeamKey field
     */
    const COL_TEAMKEY = 'events.TeamKey';

    /**
     * The default string format for model objects of the related table
     */
    const DEFAULT_STRING_FORMAT = 'YAML';

    /** A key representing a particular subclass */
    const CLASSKEY_1 = '1';

    /** A key representing a particular subclass */
    const CLASSKEY_GOAL = '\\Goal';

    /** A class that can be returned by this tableMap. */
    const CLASSNAME_1 = '\\Goal';

    /** A key representing a particular subclass */
    const CLASSKEY_2 = '2';

    /** A key representing a particular subclass */
    const CLASSKEY_PENALTY = '\\Penalty';

    /** A class that can be returned by this tableMap. */
    const CLASSNAME_2 = '\\Penalty';

    /** A key representing a particular subclass */
    const CLASSKEY_3 = '3';

    /** A key representing a particular subclass */
    const CLASSKEY_OWNGOAL = '\\OwnGoal';

    /** A class that can be returned by this tableMap. */
    const CLASSNAME_3 = '\\OwnGoal';

    /** A key representing a particular subclass */
    const CLASSKEY_4 = '4';

    /** A key representing a particular subclass */
    const CLASSKEY_YELLOWCARD = '\\YellowCard';

    /** A class that can be returned by this tableMap. */
    const CLASSNAME_4 = '\\YellowCard';

    /** A key representing a particular subclass */
    const CLASSKEY_5 = '5';

    /** A key representing a particular subclass */
    const CLASSKEY_REDCARD = '\\RedCard';

    /** A class that can be returned by this tableMap. */
    const CLASSNAME_5 = '\\RedCard';

    /** A key representing a particular subclass */
    const CLASSKEY_6 = '6';

    /** A key representing a particular subclass */
    const CLASSKEY_SUBSTITUTE = '\\Substitute';

    /** A class that can be returned by this tableMap. */
    const CLASSNAME_6 = '\\Substitute';

    /** The enumerated values for the Half field */
    const COL_HALF_NOTSTARTED = 'NotStarted';
    const COL_HALF_FIRSTHALF = 'FirstHalf';
    const COL_HALF_HALFTIME = 'HalfTime';
    const COL_HALF_SECONDHALF = 'SecondHalf';
    const COL_HALF_EXTENDEDTIME = 'ExtendedTime';
    const COL_HALF_EXTENDEDTIMEFIRSTHALF = 'ExtendedTimeFirstHalf';
    const COL_HALF_EXTENDEDTIMEHALFTIME = 'ExtendedTimeHalfTime';
    const COL_HALF_EXTENDEDTIMESECONDHALF = 'ExtendedTimeSecondHalf';
    const COL_HALF_PENALYTIME = 'PenalyTime';
    const COL_HALF_NOTUSED = 'NotUsed';
    const COL_HALF_ENDOFMATCH = 'EndOfMatch';

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        self::TYPE_PHPNAME       => array('EventPK', 'Resultkey', 'Teamplayerkey', 'Eventtime', 'Eventtype', 'Half', 'Teamkey', ),
        self::TYPE_CAMELNAME     => array('eventPK', 'resultkey', 'teamplayerkey', 'eventtime', 'eventtype', 'half', 'teamkey', ),
        self::TYPE_COLNAME       => array(EventsTableMap::COL_PRIMARYKEY, EventsTableMap::COL_RESULTKEY, EventsTableMap::COL_TEAMPLAYERKEY, EventsTableMap::COL_EVENTTIME, EventsTableMap::COL_EVENTTYPE, EventsTableMap::COL_HALF, EventsTableMap::COL_TEAMKEY, ),
        self::TYPE_FIELDNAME     => array('PrimaryKey', 'ResultKey', 'TeamPlayerKey', 'EventTime', 'EventType', 'Half', 'TeamKey', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('EventPK' => 0, 'Resultkey' => 1, 'Teamplayerkey' => 2, 'Eventtime' => 3, 'Eventtype' => 4, 'Half' => 5, 'Teamkey' => 6, ),
        self::TYPE_CAMELNAME     => array('eventPK' => 0, 'resultkey' => 1, 'teamplayerkey' => 2, 'eventtime' => 3, 'eventtype' => 4, 'half' => 5, 'teamkey' => 6, ),
        self::TYPE_COLNAME       => array(EventsTableMap::COL_PRIMARYKEY => 0, EventsTableMap::COL_RESULTKEY => 1, EventsTableMap::COL_TEAMPLAYERKEY => 2, EventsTableMap::COL_EVENTTIME => 3, EventsTableMap::COL_EVENTTYPE => 4, EventsTableMap::COL_HALF => 5, EventsTableMap::COL_TEAMKEY => 6, ),
        self::TYPE_FIELDNAME     => array('PrimaryKey' => 0, 'ResultKey' => 1, 'TeamPlayerKey' => 2, 'EventTime' => 3, 'EventType' => 4, 'Half' => 5, 'TeamKey' => 6, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, )
    );

    /** The enumerated values for this table */
    protected static $enumValueSets = array(
                EventsTableMap::COL_HALF => array(
                            self::COL_HALF_NOTSTARTED,
            self::COL_HALF_FIRSTHALF,
            self::COL_HALF_HALFTIME,
            self::COL_HALF_SECONDHALF,
            self::COL_HALF_EXTENDEDTIME,
            self::COL_HALF_EXTENDEDTIMEFIRSTHALF,
            self::COL_HALF_EXTENDEDTIMEHALFTIME,
            self::COL_HALF_EXTENDEDTIMESECONDHALF,
            self::COL_HALF_PENALYTIME,
            self::COL_HALF_NOTUSED,
            self::COL_HALF_ENDOFMATCH,
        ),
    );

    /**
     * Gets the list of values for all ENUM columns
     * @return array
     */
    public static function getValueSets()
    {
      return static::$enumValueSets;
    }

    /**
     * Gets the list of values for an ENUM column
     * @param string $colname
     * @return array list of possible values for the column
     */
    public static function getValueSet($colname)
    {
        $valueSets = self::getValueSets();

        return $valueSets[$colname];
    }

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
        $this->setName('events');
        $this->setPhpName('Events');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Events');
        $this->setPackage('');
        $this->setUseIdGenerator(true);
        $this->setSingleTableInheritance(true);
        // columns
        $this->addPrimaryKey('PrimaryKey', 'EventPK', 'INTEGER', true, null, null);
        $this->addForeignKey('ResultKey', 'Resultkey', 'INTEGER', 'results', 'PrimaryKey', true, null, null);
        $this->addForeignKey('TeamPlayerKey', 'Teamplayerkey', 'INTEGER', 'teamplayers', 'PrimaryKey', true, null, null);
        $this->addColumn('EventTime', 'Eventtime', 'INTEGER', true, null, null);
        $this->addColumn('EventType', 'Eventtype', 'INTEGER', true, null, null);
        $this->addColumn('Half', 'Half', 'ENUM', true, null, null);
        $this->getColumn('Half')->setValueSet(array (
  0 => 'NotStarted',
  1 => 'FirstHalf',
  2 => 'HalfTime',
  3 => 'SecondHalf',
  4 => 'ExtendedTime',
  5 => 'ExtendedTimeFirstHalf',
  6 => 'ExtendedTimeHalfTime',
  7 => 'ExtendedTimeSecondHalf',
  8 => 'PenalyTime',
  9 => 'NotUsed',
  10 => 'EndOfMatch',
));
        $this->addForeignKey('TeamKey', 'Teamkey', 'INTEGER', 'teams', 'PrimaryKey', true, null, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Results', '\\Results', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':ResultKey',
    1 => ':PrimaryKey',
  ),
), null, null, null, false);
        $this->addRelation('Teamplayers', '\\Teamplayers', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':TeamPlayerKey',
    1 => ':PrimaryKey',
  ),
), null, null, null, false);
        $this->addRelation('Teams', '\\Teams', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':TeamKey',
    1 => ':PrimaryKey',
  ),
), null, null, null, false);
        $this->addRelation('Matchstates', '\\Matchstates', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':EventKey',
    1 => ':PrimaryKey',
  ),
), null, null, 'Matchstatess', false);
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
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('EventPK', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('EventPK', TableMap::TYPE_PHPNAME, $indexType)];
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
                : self::translateFieldName('EventPK', TableMap::TYPE_PHPNAME, $indexType)
        ];
    }

    /**
     * The returned Class will contain objects of the default type or
     * objects that inherit from the default.
     *
     * @param array   $row ConnectionInterface result row.
     * @param int     $colnum Column to examine for OM class information (first is 0).
     * @param boolean $withPrefix Whether or not to return the path with the class name
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     *
     * @return string The OM class
     */
    public static function getOMClass($row, $colnum, $withPrefix = true)
    {
        try {

            $omClass = null;
            $classKey = $row[$colnum + 4];

            switch ($classKey) {

                case EventsTableMap::CLASSKEY_1:
                    $omClass = EventsTableMap::CLASSNAME_1;
                    break;

                case EventsTableMap::CLASSKEY_2:
                    $omClass = EventsTableMap::CLASSNAME_2;
                    break;

                case EventsTableMap::CLASSKEY_3:
                    $omClass = EventsTableMap::CLASSNAME_3;
                    break;

                case EventsTableMap::CLASSKEY_4:
                    $omClass = EventsTableMap::CLASSNAME_4;
                    break;

                case EventsTableMap::CLASSKEY_5:
                    $omClass = EventsTableMap::CLASSNAME_5;
                    break;

                case EventsTableMap::CLASSKEY_6:
                    $omClass = EventsTableMap::CLASSNAME_6;
                    break;

                default:
                    $omClass = EventsTableMap::CLASS_DEFAULT;

            } // switch
            if (!$withPrefix) {
                $omClass = preg_replace('#\.#', '\\', $omClass);
            }

        } catch (\Exception $e) {
            throw new PropelException('Unable to get OM class.', $e);
        }

        return $omClass;
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
     * @return array           (Events object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = EventsTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = EventsTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + EventsTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = static::getOMClass($row, $offset, false);
            /** @var Events $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            EventsTableMap::addInstanceToPool($obj, $key);
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

        // populate the object(s)
        while ($row = $dataFetcher->fetch()) {
            $key = EventsTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = EventsTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                // class must be set each time from the record row
                $cls = static::getOMClass($row, 0);
                $cls = preg_replace('#\.#', '\\', $cls);
                /** @var Events $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                EventsTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(EventsTableMap::COL_PRIMARYKEY);
            $criteria->addSelectColumn(EventsTableMap::COL_RESULTKEY);
            $criteria->addSelectColumn(EventsTableMap::COL_TEAMPLAYERKEY);
            $criteria->addSelectColumn(EventsTableMap::COL_EVENTTIME);
            $criteria->addSelectColumn(EventsTableMap::COL_EVENTTYPE);
            $criteria->addSelectColumn(EventsTableMap::COL_HALF);
            $criteria->addSelectColumn(EventsTableMap::COL_TEAMKEY);
        } else {
            $criteria->addSelectColumn($alias . '.PrimaryKey');
            $criteria->addSelectColumn($alias . '.ResultKey');
            $criteria->addSelectColumn($alias . '.TeamPlayerKey');
            $criteria->addSelectColumn($alias . '.EventTime');
            $criteria->addSelectColumn($alias . '.EventType');
            $criteria->addSelectColumn($alias . '.Half');
            $criteria->addSelectColumn($alias . '.TeamKey');
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
        return Propel::getServiceContainer()->getDatabaseMap(EventsTableMap::DATABASE_NAME)->getTable(EventsTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(EventsTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(EventsTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new EventsTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a Events or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Events object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(EventsTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Events) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(EventsTableMap::DATABASE_NAME);
            $criteria->add(EventsTableMap::COL_PRIMARYKEY, (array) $values, Criteria::IN);
        }

        $query = EventsQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            EventsTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                EventsTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the events table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return EventsQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Events or Criteria object.
     *
     * @param mixed               $criteria Criteria or Events object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(EventsTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Events object
        }

        if ($criteria->containsKey(EventsTableMap::COL_PRIMARYKEY) && $criteria->keyContainsValue(EventsTableMap::COL_PRIMARYKEY) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.EventsTableMap::COL_PRIMARYKEY.')');
        }


        // Set the correct dbName
        $query = EventsQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // EventsTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
EventsTableMap::buildTableMap();
