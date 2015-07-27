<?php

namespace Base;

use \Matches as ChildMatches;
use \MatchesQuery as ChildMatchesQuery;
use \Exception;
use \PDO;
use Map\MatchesTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'matches' table.
 *
 *
 *
 * @method     ChildMatchesQuery orderByPrimarykey($order = Criteria::ASC) Order by the PrimaryKey column
 * @method     ChildMatchesQuery orderByGroupkey($order = Criteria::ASC) Order by the GroupKey column
 * @method     ChildMatchesQuery orderByTeamhomekey($order = Criteria::ASC) Order by the TeamHomeKey column
 * @method     ChildMatchesQuery orderByTeamawaykey($order = Criteria::ASC) Order by the TeamAwayKey column
 * @method     ChildMatchesQuery orderByScheduledate($order = Criteria::ASC) Order by the ScheduleDate column
 * @method     ChildMatchesQuery orderByIsbonusmatch($order = Criteria::ASC) Order by the IsBonusMatch column
 * @method     ChildMatchesQuery orderByStatus($order = Criteria::ASC) Order by the Status column
 * @method     ChildMatchesQuery orderByExternalkey($order = Criteria::ASC) Order by the ExternalKey column
 *
 * @method     ChildMatchesQuery groupByPrimarykey() Group by the PrimaryKey column
 * @method     ChildMatchesQuery groupByGroupkey() Group by the GroupKey column
 * @method     ChildMatchesQuery groupByTeamhomekey() Group by the TeamHomeKey column
 * @method     ChildMatchesQuery groupByTeamawaykey() Group by the TeamAwayKey column
 * @method     ChildMatchesQuery groupByScheduledate() Group by the ScheduleDate column
 * @method     ChildMatchesQuery groupByIsbonusmatch() Group by the IsBonusMatch column
 * @method     ChildMatchesQuery groupByStatus() Group by the Status column
 * @method     ChildMatchesQuery groupByExternalkey() Group by the ExternalKey column
 *
 * @method     ChildMatchesQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildMatchesQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildMatchesQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildMatches findOne(ConnectionInterface $con = null) Return the first ChildMatches matching the query
 * @method     ChildMatches findOneOrCreate(ConnectionInterface $con = null) Return the first ChildMatches matching the query, or a new ChildMatches object populated from the query conditions when no match is found
 *
 * @method     ChildMatches findOneByPrimarykey(int $PrimaryKey) Return the first ChildMatches filtered by the PrimaryKey column
 * @method     ChildMatches findOneByGroupkey(int $GroupKey) Return the first ChildMatches filtered by the GroupKey column
 * @method     ChildMatches findOneByTeamhomekey(int $TeamHomeKey) Return the first ChildMatches filtered by the TeamHomeKey column
 * @method     ChildMatches findOneByTeamawaykey(int $TeamAwayKey) Return the first ChildMatches filtered by the TeamAwayKey column
 * @method     ChildMatches findOneByScheduledate(string $ScheduleDate) Return the first ChildMatches filtered by the ScheduleDate column
 * @method     ChildMatches findOneByIsbonusmatch(boolean $IsBonusMatch) Return the first ChildMatches filtered by the IsBonusMatch column
 * @method     ChildMatches findOneByStatus(int $Status) Return the first ChildMatches filtered by the Status column
 * @method     ChildMatches findOneByExternalkey(int $ExternalKey) Return the first ChildMatches filtered by the ExternalKey column *

 * @method     ChildMatches requirePk($key, ConnectionInterface $con = null) Return the ChildMatches by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMatches requireOne(ConnectionInterface $con = null) Return the first ChildMatches matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildMatches requireOneByPrimarykey(int $PrimaryKey) Return the first ChildMatches filtered by the PrimaryKey column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMatches requireOneByGroupkey(int $GroupKey) Return the first ChildMatches filtered by the GroupKey column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMatches requireOneByTeamhomekey(int $TeamHomeKey) Return the first ChildMatches filtered by the TeamHomeKey column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMatches requireOneByTeamawaykey(int $TeamAwayKey) Return the first ChildMatches filtered by the TeamAwayKey column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMatches requireOneByScheduledate(string $ScheduleDate) Return the first ChildMatches filtered by the ScheduleDate column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMatches requireOneByIsbonusmatch(boolean $IsBonusMatch) Return the first ChildMatches filtered by the IsBonusMatch column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMatches requireOneByStatus(int $Status) Return the first ChildMatches filtered by the Status column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMatches requireOneByExternalkey(int $ExternalKey) Return the first ChildMatches filtered by the ExternalKey column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildMatches[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildMatches objects based on current ModelCriteria
 * @method     ChildMatches[]|ObjectCollection findByPrimarykey(int $PrimaryKey) Return ChildMatches objects filtered by the PrimaryKey column
 * @method     ChildMatches[]|ObjectCollection findByGroupkey(int $GroupKey) Return ChildMatches objects filtered by the GroupKey column
 * @method     ChildMatches[]|ObjectCollection findByTeamhomekey(int $TeamHomeKey) Return ChildMatches objects filtered by the TeamHomeKey column
 * @method     ChildMatches[]|ObjectCollection findByTeamawaykey(int $TeamAwayKey) Return ChildMatches objects filtered by the TeamAwayKey column
 * @method     ChildMatches[]|ObjectCollection findByScheduledate(string $ScheduleDate) Return ChildMatches objects filtered by the ScheduleDate column
 * @method     ChildMatches[]|ObjectCollection findByIsbonusmatch(boolean $IsBonusMatch) Return ChildMatches objects filtered by the IsBonusMatch column
 * @method     ChildMatches[]|ObjectCollection findByStatus(int $Status) Return ChildMatches objects filtered by the Status column
 * @method     ChildMatches[]|ObjectCollection findByExternalkey(int $ExternalKey) Return ChildMatches objects filtered by the ExternalKey column
 * @method     ChildMatches[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class MatchesQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Base\MatchesQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Matches', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildMatchesQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildMatchesQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildMatchesQuery) {
            return $criteria;
        }
        $query = new ChildMatchesQuery();
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
     * @return ChildMatches|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = MatchesTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(MatchesTableMap::DATABASE_NAME);
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
     * @return ChildMatches A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT PrimaryKey, GroupKey, TeamHomeKey, TeamAwayKey, ScheduleDate, IsBonusMatch, Status, ExternalKey FROM matches WHERE PrimaryKey = :p0';
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
            /** @var ChildMatches $obj */
            $obj = new ChildMatches();
            $obj->hydrate($row);
            MatchesTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildMatches|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildMatchesQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(MatchesTableMap::COL_PRIMARYKEY, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildMatchesQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(MatchesTableMap::COL_PRIMARYKEY, $keys, Criteria::IN);
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
     * @return $this|ChildMatchesQuery The current query, for fluid interface
     */
    public function filterByPrimarykey($primarykey = null, $comparison = null)
    {
        if (is_array($primarykey)) {
            $useMinMax = false;
            if (isset($primarykey['min'])) {
                $this->addUsingAlias(MatchesTableMap::COL_PRIMARYKEY, $primarykey['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($primarykey['max'])) {
                $this->addUsingAlias(MatchesTableMap::COL_PRIMARYKEY, $primarykey['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MatchesTableMap::COL_PRIMARYKEY, $primarykey, $comparison);
    }

    /**
     * Filter the query on the GroupKey column
     *
     * Example usage:
     * <code>
     * $query->filterByGroupkey(1234); // WHERE GroupKey = 1234
     * $query->filterByGroupkey(array(12, 34)); // WHERE GroupKey IN (12, 34)
     * $query->filterByGroupkey(array('min' => 12)); // WHERE GroupKey > 12
     * </code>
     *
     * @param     mixed $groupkey The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMatchesQuery The current query, for fluid interface
     */
    public function filterByGroupkey($groupkey = null, $comparison = null)
    {
        if (is_array($groupkey)) {
            $useMinMax = false;
            if (isset($groupkey['min'])) {
                $this->addUsingAlias(MatchesTableMap::COL_GROUPKEY, $groupkey['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($groupkey['max'])) {
                $this->addUsingAlias(MatchesTableMap::COL_GROUPKEY, $groupkey['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MatchesTableMap::COL_GROUPKEY, $groupkey, $comparison);
    }

    /**
     * Filter the query on the TeamHomeKey column
     *
     * Example usage:
     * <code>
     * $query->filterByTeamhomekey(1234); // WHERE TeamHomeKey = 1234
     * $query->filterByTeamhomekey(array(12, 34)); // WHERE TeamHomeKey IN (12, 34)
     * $query->filterByTeamhomekey(array('min' => 12)); // WHERE TeamHomeKey > 12
     * </code>
     *
     * @param     mixed $teamhomekey The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMatchesQuery The current query, for fluid interface
     */
    public function filterByTeamhomekey($teamhomekey = null, $comparison = null)
    {
        if (is_array($teamhomekey)) {
            $useMinMax = false;
            if (isset($teamhomekey['min'])) {
                $this->addUsingAlias(MatchesTableMap::COL_TEAMHOMEKEY, $teamhomekey['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($teamhomekey['max'])) {
                $this->addUsingAlias(MatchesTableMap::COL_TEAMHOMEKEY, $teamhomekey['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MatchesTableMap::COL_TEAMHOMEKEY, $teamhomekey, $comparison);
    }

    /**
     * Filter the query on the TeamAwayKey column
     *
     * Example usage:
     * <code>
     * $query->filterByTeamawaykey(1234); // WHERE TeamAwayKey = 1234
     * $query->filterByTeamawaykey(array(12, 34)); // WHERE TeamAwayKey IN (12, 34)
     * $query->filterByTeamawaykey(array('min' => 12)); // WHERE TeamAwayKey > 12
     * </code>
     *
     * @param     mixed $teamawaykey The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMatchesQuery The current query, for fluid interface
     */
    public function filterByTeamawaykey($teamawaykey = null, $comparison = null)
    {
        if (is_array($teamawaykey)) {
            $useMinMax = false;
            if (isset($teamawaykey['min'])) {
                $this->addUsingAlias(MatchesTableMap::COL_TEAMAWAYKEY, $teamawaykey['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($teamawaykey['max'])) {
                $this->addUsingAlias(MatchesTableMap::COL_TEAMAWAYKEY, $teamawaykey['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MatchesTableMap::COL_TEAMAWAYKEY, $teamawaykey, $comparison);
    }

    /**
     * Filter the query on the ScheduleDate column
     *
     * Example usage:
     * <code>
     * $query->filterByScheduledate('2011-03-14'); // WHERE ScheduleDate = '2011-03-14'
     * $query->filterByScheduledate('now'); // WHERE ScheduleDate = '2011-03-14'
     * $query->filterByScheduledate(array('max' => 'yesterday')); // WHERE ScheduleDate > '2011-03-13'
     * </code>
     *
     * @param     mixed $scheduledate The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMatchesQuery The current query, for fluid interface
     */
    public function filterByScheduledate($scheduledate = null, $comparison = null)
    {
        if (is_array($scheduledate)) {
            $useMinMax = false;
            if (isset($scheduledate['min'])) {
                $this->addUsingAlias(MatchesTableMap::COL_SCHEDULEDATE, $scheduledate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($scheduledate['max'])) {
                $this->addUsingAlias(MatchesTableMap::COL_SCHEDULEDATE, $scheduledate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MatchesTableMap::COL_SCHEDULEDATE, $scheduledate, $comparison);
    }

    /**
     * Filter the query on the IsBonusMatch column
     *
     * Example usage:
     * <code>
     * $query->filterByIsbonusmatch(true); // WHERE IsBonusMatch = true
     * $query->filterByIsbonusmatch('yes'); // WHERE IsBonusMatch = true
     * </code>
     *
     * @param     boolean|string $isbonusmatch The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMatchesQuery The current query, for fluid interface
     */
    public function filterByIsbonusmatch($isbonusmatch = null, $comparison = null)
    {
        if (is_string($isbonusmatch)) {
            $isbonusmatch = in_array(strtolower($isbonusmatch), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(MatchesTableMap::COL_ISBONUSMATCH, $isbonusmatch, $comparison);
    }

    /**
     * Filter the query on the Status column
     *
     * Example usage:
     * <code>
     * $query->filterByStatus(1234); // WHERE Status = 1234
     * $query->filterByStatus(array(12, 34)); // WHERE Status IN (12, 34)
     * $query->filterByStatus(array('min' => 12)); // WHERE Status > 12
     * </code>
     *
     * @param     mixed $status The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMatchesQuery The current query, for fluid interface
     */
    public function filterByStatus($status = null, $comparison = null)
    {
        if (is_array($status)) {
            $useMinMax = false;
            if (isset($status['min'])) {
                $this->addUsingAlias(MatchesTableMap::COL_STATUS, $status['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($status['max'])) {
                $this->addUsingAlias(MatchesTableMap::COL_STATUS, $status['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MatchesTableMap::COL_STATUS, $status, $comparison);
    }

    /**
     * Filter the query on the ExternalKey column
     *
     * Example usage:
     * <code>
     * $query->filterByExternalkey(1234); // WHERE ExternalKey = 1234
     * $query->filterByExternalkey(array(12, 34)); // WHERE ExternalKey IN (12, 34)
     * $query->filterByExternalkey(array('min' => 12)); // WHERE ExternalKey > 12
     * </code>
     *
     * @param     mixed $externalkey The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMatchesQuery The current query, for fluid interface
     */
    public function filterByExternalkey($externalkey = null, $comparison = null)
    {
        if (is_array($externalkey)) {
            $useMinMax = false;
            if (isset($externalkey['min'])) {
                $this->addUsingAlias(MatchesTableMap::COL_EXTERNALKEY, $externalkey['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($externalkey['max'])) {
                $this->addUsingAlias(MatchesTableMap::COL_EXTERNALKEY, $externalkey['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MatchesTableMap::COL_EXTERNALKEY, $externalkey, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   ChildMatches $matches Object to remove from the list of results
     *
     * @return $this|ChildMatchesQuery The current query, for fluid interface
     */
    public function prune($matches = null)
    {
        if ($matches) {
            $this->addUsingAlias(MatchesTableMap::COL_PRIMARYKEY, $matches->getPrimarykey(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the matches table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(MatchesTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            MatchesTableMap::clearInstancePool();
            MatchesTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(MatchesTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(MatchesTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            MatchesTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            MatchesTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // MatchesQuery
