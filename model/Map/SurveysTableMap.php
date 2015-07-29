<?php

namespace Map;

use \Surveys;
use \SurveysQuery;
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
 * This class defines the structure of the 'surveys' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class SurveysTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = '.Map.SurveysTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'surveys';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\Surveys';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Surveys';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 13;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 13;

    /**
     * the column name for the PrimaryKey field
     */
    const COL_PRIMARYKEY = 'surveys.PrimaryKey';

    /**
     * the column name for the Question field
     */
    const COL_QUESTION = 'surveys.Question';

    /**
     * the column name for the Answer1 field
     */
    const COL_ANSWER1 = 'surveys.Answer1';

    /**
     * the column name for the Answer2 field
     */
    const COL_ANSWER2 = 'surveys.Answer2';

    /**
     * the column name for the Answer3 field
     */
    const COL_ANSWER3 = 'surveys.Answer3';

    /**
     * the column name for the Answer4 field
     */
    const COL_ANSWER4 = 'surveys.Answer4';

    /**
     * the column name for the Score1 field
     */
    const COL_SCORE1 = 'surveys.Score1';

    /**
     * the column name for the Score2 field
     */
    const COL_SCORE2 = 'surveys.Score2';

    /**
     * the column name for the Score3 field
     */
    const COL_SCORE3 = 'surveys.Score3';

    /**
     * the column name for the Score4 field
     */
    const COL_SCORE4 = 'surveys.Score4';

    /**
     * the column name for the Participants field
     */
    const COL_PARTICIPANTS = 'surveys.Participants';

    /**
     * the column name for the StartDate field
     */
    const COL_STARTDATE = 'surveys.StartDate';

    /**
     * the column name for the EndDate field
     */
    const COL_ENDDATE = 'surveys.EndDate';

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
        self::TYPE_PHPNAME       => array('SurveyPK', 'Question', 'Answer1', 'Answer2', 'Answer3', 'Answer4', 'Score1', 'Score2', 'Score3', 'Score4', 'Participants', 'Startdate', 'Enddate', ),
        self::TYPE_CAMELNAME     => array('surveyPK', 'question', 'answer1', 'answer2', 'answer3', 'answer4', 'score1', 'score2', 'score3', 'score4', 'participants', 'startdate', 'enddate', ),
        self::TYPE_COLNAME       => array(SurveysTableMap::COL_PRIMARYKEY, SurveysTableMap::COL_QUESTION, SurveysTableMap::COL_ANSWER1, SurveysTableMap::COL_ANSWER2, SurveysTableMap::COL_ANSWER3, SurveysTableMap::COL_ANSWER4, SurveysTableMap::COL_SCORE1, SurveysTableMap::COL_SCORE2, SurveysTableMap::COL_SCORE3, SurveysTableMap::COL_SCORE4, SurveysTableMap::COL_PARTICIPANTS, SurveysTableMap::COL_STARTDATE, SurveysTableMap::COL_ENDDATE, ),
        self::TYPE_FIELDNAME     => array('PrimaryKey', 'Question', 'Answer1', 'Answer2', 'Answer3', 'Answer4', 'Score1', 'Score2', 'Score3', 'Score4', 'Participants', 'StartDate', 'EndDate', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('SurveyPK' => 0, 'Question' => 1, 'Answer1' => 2, 'Answer2' => 3, 'Answer3' => 4, 'Answer4' => 5, 'Score1' => 6, 'Score2' => 7, 'Score3' => 8, 'Score4' => 9, 'Participants' => 10, 'Startdate' => 11, 'Enddate' => 12, ),
        self::TYPE_CAMELNAME     => array('surveyPK' => 0, 'question' => 1, 'answer1' => 2, 'answer2' => 3, 'answer3' => 4, 'answer4' => 5, 'score1' => 6, 'score2' => 7, 'score3' => 8, 'score4' => 9, 'participants' => 10, 'startdate' => 11, 'enddate' => 12, ),
        self::TYPE_COLNAME       => array(SurveysTableMap::COL_PRIMARYKEY => 0, SurveysTableMap::COL_QUESTION => 1, SurveysTableMap::COL_ANSWER1 => 2, SurveysTableMap::COL_ANSWER2 => 3, SurveysTableMap::COL_ANSWER3 => 4, SurveysTableMap::COL_ANSWER4 => 5, SurveysTableMap::COL_SCORE1 => 6, SurveysTableMap::COL_SCORE2 => 7, SurveysTableMap::COL_SCORE3 => 8, SurveysTableMap::COL_SCORE4 => 9, SurveysTableMap::COL_PARTICIPANTS => 10, SurveysTableMap::COL_STARTDATE => 11, SurveysTableMap::COL_ENDDATE => 12, ),
        self::TYPE_FIELDNAME     => array('PrimaryKey' => 0, 'Question' => 1, 'Answer1' => 2, 'Answer2' => 3, 'Answer3' => 4, 'Answer4' => 5, 'Score1' => 6, 'Score2' => 7, 'Score3' => 8, 'Score4' => 9, 'Participants' => 10, 'StartDate' => 11, 'EndDate' => 12, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, )
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
        $this->setName('surveys');
        $this->setPhpName('Surveys');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Surveys');
        $this->setPackage('');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('PrimaryKey', 'SurveyPK', 'INTEGER', true, null, null);
        $this->addColumn('Question', 'Question', 'VARCHAR', true, 2000, null);
        $this->addColumn('Answer1', 'Answer1', 'VARCHAR', true, 200, null);
        $this->addColumn('Answer2', 'Answer2', 'VARCHAR', true, 200, null);
        $this->addColumn('Answer3', 'Answer3', 'VARCHAR', false, 200, null);
        $this->addColumn('Answer4', 'Answer4', 'VARCHAR', false, 200, null);
        $this->addColumn('Score1', 'Score1', 'INTEGER', true, 3, 0);
        $this->addColumn('Score2', 'Score2', 'INTEGER', true, 3, 0);
        $this->addColumn('Score3', 'Score3', 'INTEGER', true, 3, 0);
        $this->addColumn('Score4', 'Score4', 'INTEGER', true, 3, 0);
        $this->addColumn('Participants', 'Participants', 'VARCHAR', false, 2000, null);
        $this->addColumn('StartDate', 'Startdate', 'TIMESTAMP', true, null, 'CURRENT_TIMESTAMP');
        $this->addColumn('EndDate', 'Enddate', 'TIMESTAMP', true, null, '0000-00-00 00:00:00');
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
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('SurveyPK', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('SurveyPK', TableMap::TYPE_PHPNAME, $indexType)];
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
                : self::translateFieldName('SurveyPK', TableMap::TYPE_PHPNAME, $indexType)
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
        return $withPrefix ? SurveysTableMap::CLASS_DEFAULT : SurveysTableMap::OM_CLASS;
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
     * @return array           (Surveys object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = SurveysTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = SurveysTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + SurveysTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = SurveysTableMap::OM_CLASS;
            /** @var Surveys $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            SurveysTableMap::addInstanceToPool($obj, $key);
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
            $key = SurveysTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = SurveysTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Surveys $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                SurveysTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(SurveysTableMap::COL_PRIMARYKEY);
            $criteria->addSelectColumn(SurveysTableMap::COL_QUESTION);
            $criteria->addSelectColumn(SurveysTableMap::COL_ANSWER1);
            $criteria->addSelectColumn(SurveysTableMap::COL_ANSWER2);
            $criteria->addSelectColumn(SurveysTableMap::COL_ANSWER3);
            $criteria->addSelectColumn(SurveysTableMap::COL_ANSWER4);
            $criteria->addSelectColumn(SurveysTableMap::COL_SCORE1);
            $criteria->addSelectColumn(SurveysTableMap::COL_SCORE2);
            $criteria->addSelectColumn(SurveysTableMap::COL_SCORE3);
            $criteria->addSelectColumn(SurveysTableMap::COL_SCORE4);
            $criteria->addSelectColumn(SurveysTableMap::COL_PARTICIPANTS);
            $criteria->addSelectColumn(SurveysTableMap::COL_STARTDATE);
            $criteria->addSelectColumn(SurveysTableMap::COL_ENDDATE);
        } else {
            $criteria->addSelectColumn($alias . '.PrimaryKey');
            $criteria->addSelectColumn($alias . '.Question');
            $criteria->addSelectColumn($alias . '.Answer1');
            $criteria->addSelectColumn($alias . '.Answer2');
            $criteria->addSelectColumn($alias . '.Answer3');
            $criteria->addSelectColumn($alias . '.Answer4');
            $criteria->addSelectColumn($alias . '.Score1');
            $criteria->addSelectColumn($alias . '.Score2');
            $criteria->addSelectColumn($alias . '.Score3');
            $criteria->addSelectColumn($alias . '.Score4');
            $criteria->addSelectColumn($alias . '.Participants');
            $criteria->addSelectColumn($alias . '.StartDate');
            $criteria->addSelectColumn($alias . '.EndDate');
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
        return Propel::getServiceContainer()->getDatabaseMap(SurveysTableMap::DATABASE_NAME)->getTable(SurveysTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(SurveysTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(SurveysTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new SurveysTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a Surveys or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Surveys object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(SurveysTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Surveys) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(SurveysTableMap::DATABASE_NAME);
            $criteria->add(SurveysTableMap::COL_PRIMARYKEY, (array) $values, Criteria::IN);
        }

        $query = SurveysQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            SurveysTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                SurveysTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the surveys table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return SurveysQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Surveys or Criteria object.
     *
     * @param mixed               $criteria Criteria or Surveys object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(SurveysTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Surveys object
        }

        if ($criteria->containsKey(SurveysTableMap::COL_PRIMARYKEY) && $criteria->keyContainsValue(SurveysTableMap::COL_PRIMARYKEY) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.SurveysTableMap::COL_PRIMARYKEY.')');
        }


        // Set the correct dbName
        $query = SurveysQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // SurveysTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
SurveysTableMap::buildTableMap();
