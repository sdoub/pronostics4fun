<?php

namespace Base;

use \Results as ChildResults;
use \ResultsQuery as ChildResultsQuery;
use \Exception;
use \PDO;
use Map\ResultsTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'results' table.
 *
 *
 *
 * @method     ChildResultsQuery orderByResultPK($order = Criteria::ASC) Order by the PrimaryKey column
 * @method     ChildResultsQuery orderByMatchkey($order = Criteria::ASC) Order by the MatchKey column
 * @method     ChildResultsQuery orderByLivestatus($order = Criteria::ASC) Order by the LiveStatus column
 * @method     ChildResultsQuery orderByActualtime($order = Criteria::ASC) Order by the ActualTime column
 * @method     ChildResultsQuery orderByResultdate($order = Criteria::ASC) Order by the ResultDate column
 *
 * @method     ChildResultsQuery groupByResultPK() Group by the PrimaryKey column
 * @method     ChildResultsQuery groupByMatchkey() Group by the MatchKey column
 * @method     ChildResultsQuery groupByLivestatus() Group by the LiveStatus column
 * @method     ChildResultsQuery groupByActualtime() Group by the ActualTime column
 * @method     ChildResultsQuery groupByResultdate() Group by the ResultDate column
 *
 * @method     ChildResultsQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildResultsQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildResultsQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildResultsQuery leftJoinMatches($relationAlias = null) Adds a LEFT JOIN clause to the query using the Matches relation
 * @method     ChildResultsQuery rightJoinMatches($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Matches relation
 * @method     ChildResultsQuery innerJoinMatches($relationAlias = null) Adds a INNER JOIN clause to the query using the Matches relation
 *
 * @method     ChildResultsQuery leftJoinEvents($relationAlias = null) Adds a LEFT JOIN clause to the query using the Events relation
 * @method     ChildResultsQuery rightJoinEvents($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Events relation
 * @method     ChildResultsQuery innerJoinEvents($relationAlias = null) Adds a INNER JOIN clause to the query using the Events relation
 *
 * @method     \MatchesQuery|\EventsQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildResults findOne(ConnectionInterface $con = null) Return the first ChildResults matching the query
 * @method     ChildResults findOneOrCreate(ConnectionInterface $con = null) Return the first ChildResults matching the query, or a new ChildResults object populated from the query conditions when no match is found
 *
 * @method     ChildResults findOneByResultPK(int $PrimaryKey) Return the first ChildResults filtered by the PrimaryKey column
 * @method     ChildResults findOneByMatchkey(int $MatchKey) Return the first ChildResults filtered by the MatchKey column
 * @method     ChildResults findOneByLivestatus(int $LiveStatus) Return the first ChildResults filtered by the LiveStatus column
 * @method     ChildResults findOneByActualtime(int $ActualTime) Return the first ChildResults filtered by the ActualTime column
 * @method     ChildResults findOneByResultdate(string $ResultDate) Return the first ChildResults filtered by the ResultDate column *

 * @method     ChildResults requirePk($key, ConnectionInterface $con = null) Return the ChildResults by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildResults requireOne(ConnectionInterface $con = null) Return the first ChildResults matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildResults requireOneByResultPK(int $PrimaryKey) Return the first ChildResults filtered by the PrimaryKey column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildResults requireOneByMatchkey(int $MatchKey) Return the first ChildResults filtered by the MatchKey column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildResults requireOneByLivestatus(int $LiveStatus) Return the first ChildResults filtered by the LiveStatus column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildResults requireOneByActualtime(int $ActualTime) Return the first ChildResults filtered by the ActualTime column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildResults requireOneByResultdate(string $ResultDate) Return the first ChildResults filtered by the ResultDate column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildResults[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildResults objects based on current ModelCriteria
 * @method     ChildResults[]|ObjectCollection findByResultPK(int $PrimaryKey) Return ChildResults objects filtered by the PrimaryKey column
 * @method     ChildResults[]|ObjectCollection findByMatchkey(int $MatchKey) Return ChildResults objects filtered by the MatchKey column
 * @method     ChildResults[]|ObjectCollection findByLivestatus(int $LiveStatus) Return ChildResults objects filtered by the LiveStatus column
 * @method     ChildResults[]|ObjectCollection findByActualtime(int $ActualTime) Return ChildResults objects filtered by the ActualTime column
 * @method     ChildResults[]|ObjectCollection findByResultdate(string $ResultDate) Return ChildResults objects filtered by the ResultDate column
 * @method     ChildResults[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class ResultsQuery extends ModelCriteria
{

    // delegate behavior

    protected $delegatedFields = [
        'Groupkey' => 'Matches',
        'Teamhomekey' => 'Matches',
        'Teamawaykey' => 'Matches',
        'Scheduledate' => 'Matches',
        'Isbonusmatch' => 'Matches',
        'Status' => 'Matches',
        'Externalkey' => 'Matches',
    ];

protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Base\ResultsQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Results', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildResultsQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildResultsQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildResultsQuery) {
            return $criteria;
        }
        $query = new ChildResultsQuery();
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
     * @return ChildResults|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = ResultsTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(ResultsTableMap::DATABASE_NAME);
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
     * @return ChildResults A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT PrimaryKey, MatchKey, LiveStatus, ActualTime, ResultDate FROM results WHERE PrimaryKey = :p0';
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
            /** @var ChildResults $obj */
            $obj = new ChildResults();
            $obj->hydrate($row);
            ResultsTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildResults|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildResultsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(ResultsTableMap::COL_PRIMARYKEY, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildResultsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(ResultsTableMap::COL_PRIMARYKEY, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the PrimaryKey column
     *
     * Example usage:
     * <code>
     * $query->filterByResultPK(1234); // WHERE PrimaryKey = 1234
     * $query->filterByResultPK(array(12, 34)); // WHERE PrimaryKey IN (12, 34)
     * $query->filterByResultPK(array('min' => 12)); // WHERE PrimaryKey > 12
     * </code>
     *
     * @param     mixed $resultPK The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildResultsQuery The current query, for fluid interface
     */
    public function filterByResultPK($resultPK = null, $comparison = null)
    {
        if (is_array($resultPK)) {
            $useMinMax = false;
            if (isset($resultPK['min'])) {
                $this->addUsingAlias(ResultsTableMap::COL_PRIMARYKEY, $resultPK['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($resultPK['max'])) {
                $this->addUsingAlias(ResultsTableMap::COL_PRIMARYKEY, $resultPK['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ResultsTableMap::COL_PRIMARYKEY, $resultPK, $comparison);
    }

    /**
     * Filter the query on the MatchKey column
     *
     * Example usage:
     * <code>
     * $query->filterByMatchkey(1234); // WHERE MatchKey = 1234
     * $query->filterByMatchkey(array(12, 34)); // WHERE MatchKey IN (12, 34)
     * $query->filterByMatchkey(array('min' => 12)); // WHERE MatchKey > 12
     * </code>
     *
     * @see       filterByMatches()
     *
     * @param     mixed $matchkey The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildResultsQuery The current query, for fluid interface
     */
    public function filterByMatchkey($matchkey = null, $comparison = null)
    {
        if (is_array($matchkey)) {
            $useMinMax = false;
            if (isset($matchkey['min'])) {
                $this->addUsingAlias(ResultsTableMap::COL_MATCHKEY, $matchkey['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($matchkey['max'])) {
                $this->addUsingAlias(ResultsTableMap::COL_MATCHKEY, $matchkey['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ResultsTableMap::COL_MATCHKEY, $matchkey, $comparison);
    }

    /**
     * Filter the query on the LiveStatus column
     *
     * Example usage:
     * <code>
     * $query->filterByLivestatus(1234); // WHERE LiveStatus = 1234
     * $query->filterByLivestatus(array(12, 34)); // WHERE LiveStatus IN (12, 34)
     * $query->filterByLivestatus(array('min' => 12)); // WHERE LiveStatus > 12
     * </code>
     *
     * @param     mixed $livestatus The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildResultsQuery The current query, for fluid interface
     */
    public function filterByLivestatus($livestatus = null, $comparison = null)
    {
        if (is_array($livestatus)) {
            $useMinMax = false;
            if (isset($livestatus['min'])) {
                $this->addUsingAlias(ResultsTableMap::COL_LIVESTATUS, $livestatus['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($livestatus['max'])) {
                $this->addUsingAlias(ResultsTableMap::COL_LIVESTATUS, $livestatus['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ResultsTableMap::COL_LIVESTATUS, $livestatus, $comparison);
    }

    /**
     * Filter the query on the ActualTime column
     *
     * Example usage:
     * <code>
     * $query->filterByActualtime(1234); // WHERE ActualTime = 1234
     * $query->filterByActualtime(array(12, 34)); // WHERE ActualTime IN (12, 34)
     * $query->filterByActualtime(array('min' => 12)); // WHERE ActualTime > 12
     * </code>
     *
     * @param     mixed $actualtime The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildResultsQuery The current query, for fluid interface
     */
    public function filterByActualtime($actualtime = null, $comparison = null)
    {
        if (is_array($actualtime)) {
            $useMinMax = false;
            if (isset($actualtime['min'])) {
                $this->addUsingAlias(ResultsTableMap::COL_ACTUALTIME, $actualtime['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($actualtime['max'])) {
                $this->addUsingAlias(ResultsTableMap::COL_ACTUALTIME, $actualtime['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ResultsTableMap::COL_ACTUALTIME, $actualtime, $comparison);
    }

    /**
     * Filter the query on the ResultDate column
     *
     * Example usage:
     * <code>
     * $query->filterByResultdate('2011-03-14'); // WHERE ResultDate = '2011-03-14'
     * $query->filterByResultdate('now'); // WHERE ResultDate = '2011-03-14'
     * $query->filterByResultdate(array('max' => 'yesterday')); // WHERE ResultDate > '2011-03-13'
     * </code>
     *
     * @param     mixed $resultdate The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildResultsQuery The current query, for fluid interface
     */
    public function filterByResultdate($resultdate = null, $comparison = null)
    {
        if (is_array($resultdate)) {
            $useMinMax = false;
            if (isset($resultdate['min'])) {
                $this->addUsingAlias(ResultsTableMap::COL_RESULTDATE, $resultdate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($resultdate['max'])) {
                $this->addUsingAlias(ResultsTableMap::COL_RESULTDATE, $resultdate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ResultsTableMap::COL_RESULTDATE, $resultdate, $comparison);
    }

    /**
     * Filter the query by a related \Matches object
     *
     * @param \Matches|ObjectCollection $matches The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildResultsQuery The current query, for fluid interface
     */
    public function filterByMatches($matches, $comparison = null)
    {
        if ($matches instanceof \Matches) {
            return $this
                ->addUsingAlias(ResultsTableMap::COL_MATCHKEY, $matches->getMatchPK(), $comparison);
        } elseif ($matches instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ResultsTableMap::COL_MATCHKEY, $matches->toKeyValue('PrimaryKey', 'MatchPK'), $comparison);
        } else {
            throw new PropelException('filterByMatches() only accepts arguments of type \Matches or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Matches relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildResultsQuery The current query, for fluid interface
     */
    public function joinMatches($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Matches');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Matches');
        }

        return $this;
    }

    /**
     * Use the Matches relation Matches object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \MatchesQuery A secondary query class using the current class as primary query
     */
    public function useMatchesQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinMatches($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Matches', '\MatchesQuery');
    }

    /**
     * Filter the query by a related \Events object
     *
     * @param \Events|ObjectCollection $events the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildResultsQuery The current query, for fluid interface
     */
    public function filterByEvents($events, $comparison = null)
    {
        if ($events instanceof \Events) {
            return $this
                ->addUsingAlias(ResultsTableMap::COL_PRIMARYKEY, $events->getResultkey(), $comparison);
        } elseif ($events instanceof ObjectCollection) {
            return $this
                ->useEventsQuery()
                ->filterByPrimaryKeys($events->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByEvents() only accepts arguments of type \Events or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Events relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildResultsQuery The current query, for fluid interface
     */
    public function joinEvents($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Events');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Events');
        }

        return $this;
    }

    /**
     * Use the Events relation Events object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \EventsQuery A secondary query class using the current class as primary query
     */
    public function useEventsQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinEvents($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Events', '\EventsQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildResults $results Object to remove from the list of results
     *
     * @return $this|ChildResultsQuery The current query, for fluid interface
     */
    public function prune($results = null)
    {
        if ($results) {
            $this->addUsingAlias(ResultsTableMap::COL_PRIMARYKEY, $results->getResultPK(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the results table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ResultsTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            ResultsTableMap::clearInstancePool();
            ResultsTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(ResultsTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(ResultsTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            ResultsTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            ResultsTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    // delegate behavior
    /**
    * Filter the query by GroupKey column
    *
    * Example usage:
    * <code>
        * $query->filterByGroupkey(1234); // WHERE GroupKey = 1234
        * $query->filterByGroupkey(array(12, 34)); // WHERE GroupKey IN (12, 34)
        * $query->filterByGroupkey(array('min' => 12)); // WHERE GroupKey > 12
        * </code>
    *
    * @param     mixed $value The value to use as filter.
    *              Use scalar values for equality.
    *              Use array values for in_array() equivalent.
    *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
    * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
    *
    * @return $this|ChildResultsQuery The current query, for fluid interface
    */
    public function filterByGroupkey($value = null, $comparison = null)
    {
        return $this->useMatchesQuery()->filterByGroupkey($value, $comparison)->endUse();
    }

    /**
    * Adds an ORDER BY clause to the query
    * Usability layer on top of Criteria::addAscendingOrderByColumn() and Criteria::addDescendingOrderByColumn()
    * Infers $column and $order from $columnName and some optional arguments
    * Examples:
    *   $c->orderBy('Book.CreatedAt')
    *    => $c->addAscendingOrderByColumn(BookTableMap::CREATED_AT)
    *   $c->orderBy('Book.CategoryId', 'desc')
    *    => $c->addDescendingOrderByColumn(BookTableMap::CATEGORY_ID)
    *
    * @param string $order      The sorting order. Criteria::ASC by default, also accepts Criteria::DESC
    *
    * @return $this|ModelCriteria The current object, for fluid interface
    */
    public function orderByGroupkey($order = Criteria::ASC)
    {
        return $this->useMatchesQuery()->orderByGroupkey($order)->endUse();
    }
    /**
    * Filter the query by TeamHomeKey column
    *
    * Example usage:
    * <code>
        * $query->filterByTeamhomekey(1234); // WHERE TeamHomeKey = 1234
        * $query->filterByTeamhomekey(array(12, 34)); // WHERE TeamHomeKey IN (12, 34)
        * $query->filterByTeamhomekey(array('min' => 12)); // WHERE TeamHomeKey > 12
        * </code>
    *
    * @param     mixed $value The value to use as filter.
    *              Use scalar values for equality.
    *              Use array values for in_array() equivalent.
    *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
    * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
    *
    * @return $this|ChildResultsQuery The current query, for fluid interface
    */
    public function filterByTeamhomekey($value = null, $comparison = null)
    {
        return $this->useMatchesQuery()->filterByTeamhomekey($value, $comparison)->endUse();
    }

    /**
    * Adds an ORDER BY clause to the query
    * Usability layer on top of Criteria::addAscendingOrderByColumn() and Criteria::addDescendingOrderByColumn()
    * Infers $column and $order from $columnName and some optional arguments
    * Examples:
    *   $c->orderBy('Book.CreatedAt')
    *    => $c->addAscendingOrderByColumn(BookTableMap::CREATED_AT)
    *   $c->orderBy('Book.CategoryId', 'desc')
    *    => $c->addDescendingOrderByColumn(BookTableMap::CATEGORY_ID)
    *
    * @param string $order      The sorting order. Criteria::ASC by default, also accepts Criteria::DESC
    *
    * @return $this|ModelCriteria The current object, for fluid interface
    */
    public function orderByTeamhomekey($order = Criteria::ASC)
    {
        return $this->useMatchesQuery()->orderByTeamhomekey($order)->endUse();
    }
    /**
    * Filter the query by TeamAwayKey column
    *
    * Example usage:
    * <code>
        * $query->filterByTeamawaykey(1234); // WHERE TeamAwayKey = 1234
        * $query->filterByTeamawaykey(array(12, 34)); // WHERE TeamAwayKey IN (12, 34)
        * $query->filterByTeamawaykey(array('min' => 12)); // WHERE TeamAwayKey > 12
        * </code>
    *
    * @param     mixed $value The value to use as filter.
    *              Use scalar values for equality.
    *              Use array values for in_array() equivalent.
    *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
    * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
    *
    * @return $this|ChildResultsQuery The current query, for fluid interface
    */
    public function filterByTeamawaykey($value = null, $comparison = null)
    {
        return $this->useMatchesQuery()->filterByTeamawaykey($value, $comparison)->endUse();
    }

    /**
    * Adds an ORDER BY clause to the query
    * Usability layer on top of Criteria::addAscendingOrderByColumn() and Criteria::addDescendingOrderByColumn()
    * Infers $column and $order from $columnName and some optional arguments
    * Examples:
    *   $c->orderBy('Book.CreatedAt')
    *    => $c->addAscendingOrderByColumn(BookTableMap::CREATED_AT)
    *   $c->orderBy('Book.CategoryId', 'desc')
    *    => $c->addDescendingOrderByColumn(BookTableMap::CATEGORY_ID)
    *
    * @param string $order      The sorting order. Criteria::ASC by default, also accepts Criteria::DESC
    *
    * @return $this|ModelCriteria The current object, for fluid interface
    */
    public function orderByTeamawaykey($order = Criteria::ASC)
    {
        return $this->useMatchesQuery()->orderByTeamawaykey($order)->endUse();
    }
    /**
    * Filter the query by ScheduleDate column
    *
    * Example usage:
    * <code>
        * $query->filterByScheduledate(1234); // WHERE ScheduleDate = 1234
        * $query->filterByScheduledate(array(12, 34)); // WHERE ScheduleDate IN (12, 34)
        * $query->filterByScheduledate(array('min' => 12)); // WHERE ScheduleDate > 12
        * </code>
    *
    * @param     mixed $value The value to use as filter.
    *              Use scalar values for equality.
    *              Use array values for in_array() equivalent.
    *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
    * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
    *
    * @return $this|ChildResultsQuery The current query, for fluid interface
    */
    public function filterByScheduledate($value = null, $comparison = null)
    {
        return $this->useMatchesQuery()->filterByScheduledate($value, $comparison)->endUse();
    }

    /**
    * Adds an ORDER BY clause to the query
    * Usability layer on top of Criteria::addAscendingOrderByColumn() and Criteria::addDescendingOrderByColumn()
    * Infers $column and $order from $columnName and some optional arguments
    * Examples:
    *   $c->orderBy('Book.CreatedAt')
    *    => $c->addAscendingOrderByColumn(BookTableMap::CREATED_AT)
    *   $c->orderBy('Book.CategoryId', 'desc')
    *    => $c->addDescendingOrderByColumn(BookTableMap::CATEGORY_ID)
    *
    * @param string $order      The sorting order. Criteria::ASC by default, also accepts Criteria::DESC
    *
    * @return $this|ModelCriteria The current object, for fluid interface
    */
    public function orderByScheduledate($order = Criteria::ASC)
    {
        return $this->useMatchesQuery()->orderByScheduledate($order)->endUse();
    }
    /**
    * Filter the query by IsBonusMatch column
    *
    * Example usage:
    * <code>
        * $query->filterByIsbonusmatch(1234); // WHERE IsBonusMatch = 1234
        * $query->filterByIsbonusmatch(array(12, 34)); // WHERE IsBonusMatch IN (12, 34)
        * $query->filterByIsbonusmatch(array('min' => 12)); // WHERE IsBonusMatch > 12
        * </code>
    *
    * @param     mixed $value The value to use as filter.
    *              Use scalar values for equality.
    *              Use array values for in_array() equivalent.
    *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
    * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
    *
    * @return $this|ChildResultsQuery The current query, for fluid interface
    */
    public function filterByIsbonusmatch($value = null, $comparison = null)
    {
        return $this->useMatchesQuery()->filterByIsbonusmatch($value, $comparison)->endUse();
    }

    /**
    * Adds an ORDER BY clause to the query
    * Usability layer on top of Criteria::addAscendingOrderByColumn() and Criteria::addDescendingOrderByColumn()
    * Infers $column and $order from $columnName and some optional arguments
    * Examples:
    *   $c->orderBy('Book.CreatedAt')
    *    => $c->addAscendingOrderByColumn(BookTableMap::CREATED_AT)
    *   $c->orderBy('Book.CategoryId', 'desc')
    *    => $c->addDescendingOrderByColumn(BookTableMap::CATEGORY_ID)
    *
    * @param string $order      The sorting order. Criteria::ASC by default, also accepts Criteria::DESC
    *
    * @return $this|ModelCriteria The current object, for fluid interface
    */
    public function orderByIsbonusmatch($order = Criteria::ASC)
    {
        return $this->useMatchesQuery()->orderByIsbonusmatch($order)->endUse();
    }
    /**
    * Filter the query by Status column
    *
    * Example usage:
    * <code>
        * $query->filterByStatus(1234); // WHERE Status = 1234
        * $query->filterByStatus(array(12, 34)); // WHERE Status IN (12, 34)
        * $query->filterByStatus(array('min' => 12)); // WHERE Status > 12
        * </code>
    *
    * @param     mixed $value The value to use as filter.
    *              Use scalar values for equality.
    *              Use array values for in_array() equivalent.
    *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
    * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
    *
    * @return $this|ChildResultsQuery The current query, for fluid interface
    */
    public function filterByStatus($value = null, $comparison = null)
    {
        return $this->useMatchesQuery()->filterByStatus($value, $comparison)->endUse();
    }

    /**
    * Adds an ORDER BY clause to the query
    * Usability layer on top of Criteria::addAscendingOrderByColumn() and Criteria::addDescendingOrderByColumn()
    * Infers $column and $order from $columnName and some optional arguments
    * Examples:
    *   $c->orderBy('Book.CreatedAt')
    *    => $c->addAscendingOrderByColumn(BookTableMap::CREATED_AT)
    *   $c->orderBy('Book.CategoryId', 'desc')
    *    => $c->addDescendingOrderByColumn(BookTableMap::CATEGORY_ID)
    *
    * @param string $order      The sorting order. Criteria::ASC by default, also accepts Criteria::DESC
    *
    * @return $this|ModelCriteria The current object, for fluid interface
    */
    public function orderByStatus($order = Criteria::ASC)
    {
        return $this->useMatchesQuery()->orderByStatus($order)->endUse();
    }
    /**
    * Filter the query by ExternalKey column
    *
    * Example usage:
    * <code>
        * $query->filterByExternalkey(1234); // WHERE ExternalKey = 1234
        * $query->filterByExternalkey(array(12, 34)); // WHERE ExternalKey IN (12, 34)
        * $query->filterByExternalkey(array('min' => 12)); // WHERE ExternalKey > 12
        * </code>
    *
    * @param     mixed $value The value to use as filter.
    *              Use scalar values for equality.
    *              Use array values for in_array() equivalent.
    *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
    * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
    *
    * @return $this|ChildResultsQuery The current query, for fluid interface
    */
    public function filterByExternalkey($value = null, $comparison = null)
    {
        return $this->useMatchesQuery()->filterByExternalkey($value, $comparison)->endUse();
    }

    /**
    * Adds an ORDER BY clause to the query
    * Usability layer on top of Criteria::addAscendingOrderByColumn() and Criteria::addDescendingOrderByColumn()
    * Infers $column and $order from $columnName and some optional arguments
    * Examples:
    *   $c->orderBy('Book.CreatedAt')
    *    => $c->addAscendingOrderByColumn(BookTableMap::CREATED_AT)
    *   $c->orderBy('Book.CategoryId', 'desc')
    *    => $c->addDescendingOrderByColumn(BookTableMap::CATEGORY_ID)
    *
    * @param string $order      The sorting order. Criteria::ASC by default, also accepts Criteria::DESC
    *
    * @return $this|ModelCriteria The current object, for fluid interface
    */
    public function orderByExternalkey($order = Criteria::ASC)
    {
        return $this->useMatchesQuery()->orderByExternalkey($order)->endUse();
    }

    /**
     * Adds a condition on a column based on a column phpName and a value
     * Uses introspection to translate the column phpName into a fully qualified name
     * Warning: recognizes only the phpNames of the main Model (not joined tables)
     * <code>
     * $c->filterBy('Title', 'foo');
     * </code>
     *
     * @see Criteria::add()
     *
     * @param string $column     A string representing thecolumn phpName, e.g. 'AuthorId'
     * @param mixed  $value      A value for the condition
     * @param string $comparison What to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ModelCriteria The current object, for fluid interface
     */
    public function filterBy($column, $value, $comparison = Criteria::EQUAL)
    {
        if (isset($this->delegatedFields[$column])) {
            $methodUse = "use{$this->delegatedFields[$column]}Query";

            return $this->{$methodUse}()->filterBy($column, $value, $comparison)->endUse();
        } else {
            return $this->add($this->getRealColumnName($column), $value, $comparison);
        }
    }

} // ResultsQuery
