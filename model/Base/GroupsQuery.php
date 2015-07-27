<?php

namespace Base;

use \Groups as ChildGroups;
use \GroupsQuery as ChildGroupsQuery;
use \Exception;
use \PDO;
use Map\GroupsTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'groups' table.
 *
 *
 *
 * @method     ChildGroupsQuery orderByPrimarykey($order = Criteria::ASC) Order by the PrimaryKey column
 * @method     ChildGroupsQuery orderByDescription($order = Criteria::ASC) Order by the Description column
 * @method     ChildGroupsQuery orderByCode($order = Criteria::ASC) Order by the Code column
 * @method     ChildGroupsQuery orderByCompetitionkey($order = Criteria::ASC) Order by the CompetitionKey column
 * @method     ChildGroupsQuery orderByBegindate($order = Criteria::ASC) Order by the BeginDate column
 * @method     ChildGroupsQuery orderByEnddate($order = Criteria::ASC) Order by the EndDate column
 * @method     ChildGroupsQuery orderByStatus($order = Criteria::ASC) Order by the Status column
 * @method     ChildGroupsQuery orderByIscompleted($order = Criteria::ASC) Order by the IsCompleted column
 * @method     ChildGroupsQuery orderByDaykey($order = Criteria::ASC) Order by the DayKey column
 *
 * @method     ChildGroupsQuery groupByPrimarykey() Group by the PrimaryKey column
 * @method     ChildGroupsQuery groupByDescription() Group by the Description column
 * @method     ChildGroupsQuery groupByCode() Group by the Code column
 * @method     ChildGroupsQuery groupByCompetitionkey() Group by the CompetitionKey column
 * @method     ChildGroupsQuery groupByBegindate() Group by the BeginDate column
 * @method     ChildGroupsQuery groupByEnddate() Group by the EndDate column
 * @method     ChildGroupsQuery groupByStatus() Group by the Status column
 * @method     ChildGroupsQuery groupByIscompleted() Group by the IsCompleted column
 * @method     ChildGroupsQuery groupByDaykey() Group by the DayKey column
 *
 * @method     ChildGroupsQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildGroupsQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildGroupsQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildGroups findOne(ConnectionInterface $con = null) Return the first ChildGroups matching the query
 * @method     ChildGroups findOneOrCreate(ConnectionInterface $con = null) Return the first ChildGroups matching the query, or a new ChildGroups object populated from the query conditions when no match is found
 *
 * @method     ChildGroups findOneByPrimarykey(int $PrimaryKey) Return the first ChildGroups filtered by the PrimaryKey column
 * @method     ChildGroups findOneByDescription(string $Description) Return the first ChildGroups filtered by the Description column
 * @method     ChildGroups findOneByCode(string $Code) Return the first ChildGroups filtered by the Code column
 * @method     ChildGroups findOneByCompetitionkey(int $CompetitionKey) Return the first ChildGroups filtered by the CompetitionKey column
 * @method     ChildGroups findOneByBegindate(string $BeginDate) Return the first ChildGroups filtered by the BeginDate column
 * @method     ChildGroups findOneByEnddate(string $EndDate) Return the first ChildGroups filtered by the EndDate column
 * @method     ChildGroups findOneByStatus(boolean $Status) Return the first ChildGroups filtered by the Status column
 * @method     ChildGroups findOneByIscompleted(boolean $IsCompleted) Return the first ChildGroups filtered by the IsCompleted column
 * @method     ChildGroups findOneByDaykey(int $DayKey) Return the first ChildGroups filtered by the DayKey column *

 * @method     ChildGroups requirePk($key, ConnectionInterface $con = null) Return the ChildGroups by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildGroups requireOne(ConnectionInterface $con = null) Return the first ChildGroups matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildGroups requireOneByPrimarykey(int $PrimaryKey) Return the first ChildGroups filtered by the PrimaryKey column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildGroups requireOneByDescription(string $Description) Return the first ChildGroups filtered by the Description column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildGroups requireOneByCode(string $Code) Return the first ChildGroups filtered by the Code column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildGroups requireOneByCompetitionkey(int $CompetitionKey) Return the first ChildGroups filtered by the CompetitionKey column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildGroups requireOneByBegindate(string $BeginDate) Return the first ChildGroups filtered by the BeginDate column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildGroups requireOneByEnddate(string $EndDate) Return the first ChildGroups filtered by the EndDate column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildGroups requireOneByStatus(boolean $Status) Return the first ChildGroups filtered by the Status column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildGroups requireOneByIscompleted(boolean $IsCompleted) Return the first ChildGroups filtered by the IsCompleted column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildGroups requireOneByDaykey(int $DayKey) Return the first ChildGroups filtered by the DayKey column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildGroups[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildGroups objects based on current ModelCriteria
 * @method     ChildGroups[]|ObjectCollection findByPrimarykey(int $PrimaryKey) Return ChildGroups objects filtered by the PrimaryKey column
 * @method     ChildGroups[]|ObjectCollection findByDescription(string $Description) Return ChildGroups objects filtered by the Description column
 * @method     ChildGroups[]|ObjectCollection findByCode(string $Code) Return ChildGroups objects filtered by the Code column
 * @method     ChildGroups[]|ObjectCollection findByCompetitionkey(int $CompetitionKey) Return ChildGroups objects filtered by the CompetitionKey column
 * @method     ChildGroups[]|ObjectCollection findByBegindate(string $BeginDate) Return ChildGroups objects filtered by the BeginDate column
 * @method     ChildGroups[]|ObjectCollection findByEnddate(string $EndDate) Return ChildGroups objects filtered by the EndDate column
 * @method     ChildGroups[]|ObjectCollection findByStatus(boolean $Status) Return ChildGroups objects filtered by the Status column
 * @method     ChildGroups[]|ObjectCollection findByIscompleted(boolean $IsCompleted) Return ChildGroups objects filtered by the IsCompleted column
 * @method     ChildGroups[]|ObjectCollection findByDaykey(int $DayKey) Return ChildGroups objects filtered by the DayKey column
 * @method     ChildGroups[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class GroupsQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Base\GroupsQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Groups', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildGroupsQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildGroupsQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildGroupsQuery) {
            return $criteria;
        }
        $query = new ChildGroupsQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildGroups|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = GroupsTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(GroupsTableMap::DATABASE_NAME);
        }
        $this->basePreSelect($con);
        if ($this->formatter || $this->modelAlias || $this->with || $this->select
         || $this->selectColumns || $this->asColumns || $this->selectModifiers
         || $this->map || $this->having || $this->joins) {
            return $this->findPkComplex($key, $con);
        } else {
            return $this->findPkSimple($key, $con);
        }
    }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildGroups A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT PrimaryKey, Description, Code, CompetitionKey, BeginDate, EndDate, Status, IsCompleted, DayKey FROM groups WHERE PrimaryKey = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildGroups $obj */
            $obj = new ChildGroups();
            $obj->hydrate($row);
            GroupsTableMap::addInstanceToPool($obj, (string) $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return ChildGroups|array|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, ConnectionInterface $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($dataFetcher);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return ObjectCollection|array|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection($this->getDbName());
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($dataFetcher);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return $this|ChildGroupsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(GroupsTableMap::COL_PRIMARYKEY, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildGroupsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(GroupsTableMap::COL_PRIMARYKEY, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the PrimaryKey column
     *
     * Example usage:
     * <code>
     * $query->filterByPrimarykey(1234); // WHERE PrimaryKey = 1234
     * $query->filterByPrimarykey(array(12, 34)); // WHERE PrimaryKey IN (12, 34)
     * $query->filterByPrimarykey(array('min' => 12)); // WHERE PrimaryKey > 12
     * </code>
     *
     * @param     mixed $primarykey The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildGroupsQuery The current query, for fluid interface
     */
    public function filterByPrimarykey($primarykey = null, $comparison = null)
    {
        if (is_array($primarykey)) {
            $useMinMax = false;
            if (isset($primarykey['min'])) {
                $this->addUsingAlias(GroupsTableMap::COL_PRIMARYKEY, $primarykey['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($primarykey['max'])) {
                $this->addUsingAlias(GroupsTableMap::COL_PRIMARYKEY, $primarykey['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(GroupsTableMap::COL_PRIMARYKEY, $primarykey, $comparison);
    }

    /**
     * Filter the query on the Description column
     *
     * Example usage:
     * <code>
     * $query->filterByDescription('fooValue');   // WHERE Description = 'fooValue'
     * $query->filterByDescription('%fooValue%'); // WHERE Description LIKE '%fooValue%'
     * </code>
     *
     * @param     string $description The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildGroupsQuery The current query, for fluid interface
     */
    public function filterByDescription($description = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($description)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $description)) {
                $description = str_replace('*', '%', $description);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(GroupsTableMap::COL_DESCRIPTION, $description, $comparison);
    }

    /**
     * Filter the query on the Code column
     *
     * Example usage:
     * <code>
     * $query->filterByCode('fooValue');   // WHERE Code = 'fooValue'
     * $query->filterByCode('%fooValue%'); // WHERE Code LIKE '%fooValue%'
     * </code>
     *
     * @param     string $code The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildGroupsQuery The current query, for fluid interface
     */
    public function filterByCode($code = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($code)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $code)) {
                $code = str_replace('*', '%', $code);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(GroupsTableMap::COL_CODE, $code, $comparison);
    }

    /**
     * Filter the query on the CompetitionKey column
     *
     * Example usage:
     * <code>
     * $query->filterByCompetitionkey(1234); // WHERE CompetitionKey = 1234
     * $query->filterByCompetitionkey(array(12, 34)); // WHERE CompetitionKey IN (12, 34)
     * $query->filterByCompetitionkey(array('min' => 12)); // WHERE CompetitionKey > 12
     * </code>
     *
     * @param     mixed $competitionkey The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildGroupsQuery The current query, for fluid interface
     */
    public function filterByCompetitionkey($competitionkey = null, $comparison = null)
    {
        if (is_array($competitionkey)) {
            $useMinMax = false;
            if (isset($competitionkey['min'])) {
                $this->addUsingAlias(GroupsTableMap::COL_COMPETITIONKEY, $competitionkey['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($competitionkey['max'])) {
                $this->addUsingAlias(GroupsTableMap::COL_COMPETITIONKEY, $competitionkey['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(GroupsTableMap::COL_COMPETITIONKEY, $competitionkey, $comparison);
    }

    /**
     * Filter the query on the BeginDate column
     *
     * Example usage:
     * <code>
     * $query->filterByBegindate('2011-03-14'); // WHERE BeginDate = '2011-03-14'
     * $query->filterByBegindate('now'); // WHERE BeginDate = '2011-03-14'
     * $query->filterByBegindate(array('max' => 'yesterday')); // WHERE BeginDate > '2011-03-13'
     * </code>
     *
     * @param     mixed $begindate The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildGroupsQuery The current query, for fluid interface
     */
    public function filterByBegindate($begindate = null, $comparison = null)
    {
        if (is_array($begindate)) {
            $useMinMax = false;
            if (isset($begindate['min'])) {
                $this->addUsingAlias(GroupsTableMap::COL_BEGINDATE, $begindate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($begindate['max'])) {
                $this->addUsingAlias(GroupsTableMap::COL_BEGINDATE, $begindate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(GroupsTableMap::COL_BEGINDATE, $begindate, $comparison);
    }

    /**
     * Filter the query on the EndDate column
     *
     * Example usage:
     * <code>
     * $query->filterByEnddate('2011-03-14'); // WHERE EndDate = '2011-03-14'
     * $query->filterByEnddate('now'); // WHERE EndDate = '2011-03-14'
     * $query->filterByEnddate(array('max' => 'yesterday')); // WHERE EndDate > '2011-03-13'
     * </code>
     *
     * @param     mixed $enddate The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildGroupsQuery The current query, for fluid interface
     */
    public function filterByEnddate($enddate = null, $comparison = null)
    {
        if (is_array($enddate)) {
            $useMinMax = false;
            if (isset($enddate['min'])) {
                $this->addUsingAlias(GroupsTableMap::COL_ENDDATE, $enddate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($enddate['max'])) {
                $this->addUsingAlias(GroupsTableMap::COL_ENDDATE, $enddate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(GroupsTableMap::COL_ENDDATE, $enddate, $comparison);
    }

    /**
     * Filter the query on the Status column
     *
     * Example usage:
     * <code>
     * $query->filterByStatus(true); // WHERE Status = true
     * $query->filterByStatus('yes'); // WHERE Status = true
     * </code>
     *
     * @param     boolean|string $status The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildGroupsQuery The current query, for fluid interface
     */
    public function filterByStatus($status = null, $comparison = null)
    {
        if (is_string($status)) {
            $status = in_array(strtolower($status), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(GroupsTableMap::COL_STATUS, $status, $comparison);
    }

    /**
     * Filter the query on the IsCompleted column
     *
     * Example usage:
     * <code>
     * $query->filterByIscompleted(true); // WHERE IsCompleted = true
     * $query->filterByIscompleted('yes'); // WHERE IsCompleted = true
     * </code>
     *
     * @param     boolean|string $iscompleted The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildGroupsQuery The current query, for fluid interface
     */
    public function filterByIscompleted($iscompleted = null, $comparison = null)
    {
        if (is_string($iscompleted)) {
            $iscompleted = in_array(strtolower($iscompleted), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(GroupsTableMap::COL_ISCOMPLETED, $iscompleted, $comparison);
    }

    /**
     * Filter the query on the DayKey column
     *
     * Example usage:
     * <code>
     * $query->filterByDaykey(1234); // WHERE DayKey = 1234
     * $query->filterByDaykey(array(12, 34)); // WHERE DayKey IN (12, 34)
     * $query->filterByDaykey(array('min' => 12)); // WHERE DayKey > 12
     * </code>
     *
     * @param     mixed $daykey The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildGroupsQuery The current query, for fluid interface
     */
    public function filterByDaykey($daykey = null, $comparison = null)
    {
        if (is_array($daykey)) {
            $useMinMax = false;
            if (isset($daykey['min'])) {
                $this->addUsingAlias(GroupsTableMap::COL_DAYKEY, $daykey['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($daykey['max'])) {
                $this->addUsingAlias(GroupsTableMap::COL_DAYKEY, $daykey['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(GroupsTableMap::COL_DAYKEY, $daykey, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   ChildGroups $groups Object to remove from the list of results
     *
     * @return $this|ChildGroupsQuery The current query, for fluid interface
     */
    public function prune($groups = null)
    {
        if ($groups) {
            $this->addUsingAlias(GroupsTableMap::COL_PRIMARYKEY, $groups->getPrimarykey(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the groups table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(GroupsTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            GroupsTableMap::clearInstancePool();
            GroupsTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    /**
     * Performs a DELETE on the database based on the current ModelCriteria
     *
     * @param ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public function delete(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(GroupsTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(GroupsTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            GroupsTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            GroupsTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // GroupsQuery
