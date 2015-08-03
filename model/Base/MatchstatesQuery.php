<?php

namespace Base;

use \Matchstates as ChildMatchstates;
use \MatchstatesQuery as ChildMatchstatesQuery;
use \Exception;
use \PDO;
use Map\MatchstatesTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'matchstates' table.
 *
 *
 *
 * @method     ChildMatchstatesQuery orderByMatchStatePK($order = Criteria::ASC) Order by the PrimaryKey column
 * @method     ChildMatchstatesQuery orderByMatchkey($order = Criteria::ASC) Order by the MatchKey column
 * @method     ChildMatchstatesQuery orderByStatedate($order = Criteria::ASC) Order by the StateDate column
 * @method     ChildMatchstatesQuery orderByEventkey($order = Criteria::ASC) Order by the EventKey column
 * @method     ChildMatchstatesQuery orderByTeamhomescore($order = Criteria::ASC) Order by the TeamHomeScore column
 * @method     ChildMatchstatesQuery orderByTeamawayscore($order = Criteria::ASC) Order by the TeamAwayScore column
 *
 * @method     ChildMatchstatesQuery groupByMatchStatePK() Group by the PrimaryKey column
 * @method     ChildMatchstatesQuery groupByMatchkey() Group by the MatchKey column
 * @method     ChildMatchstatesQuery groupByStatedate() Group by the StateDate column
 * @method     ChildMatchstatesQuery groupByEventkey() Group by the EventKey column
 * @method     ChildMatchstatesQuery groupByTeamhomescore() Group by the TeamHomeScore column
 * @method     ChildMatchstatesQuery groupByTeamawayscore() Group by the TeamAwayScore column
 *
 * @method     ChildMatchstatesQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildMatchstatesQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildMatchstatesQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildMatchstatesQuery leftJoinMatchState($relationAlias = null) Adds a LEFT JOIN clause to the query using the MatchState relation
 * @method     ChildMatchstatesQuery rightJoinMatchState($relationAlias = null) Adds a RIGHT JOIN clause to the query using the MatchState relation
 * @method     ChildMatchstatesQuery innerJoinMatchState($relationAlias = null) Adds a INNER JOIN clause to the query using the MatchState relation
 *
 * @method     ChildMatchstatesQuery leftJoinEvents($relationAlias = null) Adds a LEFT JOIN clause to the query using the Events relation
 * @method     ChildMatchstatesQuery rightJoinEvents($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Events relation
 * @method     ChildMatchstatesQuery innerJoinEvents($relationAlias = null) Adds a INNER JOIN clause to the query using the Events relation
 *
 * @method     ChildMatchstatesQuery leftJoinPlayermatchstates($relationAlias = null) Adds a LEFT JOIN clause to the query using the Playermatchstates relation
 * @method     ChildMatchstatesQuery rightJoinPlayermatchstates($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Playermatchstates relation
 * @method     ChildMatchstatesQuery innerJoinPlayermatchstates($relationAlias = null) Adds a INNER JOIN clause to the query using the Playermatchstates relation
 *
 * @method     \MatchesQuery|\EventsQuery|\PlayermatchstatesQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildMatchstates findOne(ConnectionInterface $con = null) Return the first ChildMatchstates matching the query
 * @method     ChildMatchstates findOneOrCreate(ConnectionInterface $con = null) Return the first ChildMatchstates matching the query, or a new ChildMatchstates object populated from the query conditions when no match is found
 *
 * @method     ChildMatchstates findOneByMatchStatePK(int $PrimaryKey) Return the first ChildMatchstates filtered by the PrimaryKey column
 * @method     ChildMatchstates findOneByMatchkey(int $MatchKey) Return the first ChildMatchstates filtered by the MatchKey column
 * @method     ChildMatchstates findOneByStatedate(string $StateDate) Return the first ChildMatchstates filtered by the StateDate column
 * @method     ChildMatchstates findOneByEventkey(int $EventKey) Return the first ChildMatchstates filtered by the EventKey column
 * @method     ChildMatchstates findOneByTeamhomescore(int $TeamHomeScore) Return the first ChildMatchstates filtered by the TeamHomeScore column
 * @method     ChildMatchstates findOneByTeamawayscore(int $TeamAwayScore) Return the first ChildMatchstates filtered by the TeamAwayScore column *

 * @method     ChildMatchstates requirePk($key, ConnectionInterface $con = null) Return the ChildMatchstates by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMatchstates requireOne(ConnectionInterface $con = null) Return the first ChildMatchstates matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildMatchstates requireOneByMatchStatePK(int $PrimaryKey) Return the first ChildMatchstates filtered by the PrimaryKey column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMatchstates requireOneByMatchkey(int $MatchKey) Return the first ChildMatchstates filtered by the MatchKey column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMatchstates requireOneByStatedate(string $StateDate) Return the first ChildMatchstates filtered by the StateDate column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMatchstates requireOneByEventkey(int $EventKey) Return the first ChildMatchstates filtered by the EventKey column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMatchstates requireOneByTeamhomescore(int $TeamHomeScore) Return the first ChildMatchstates filtered by the TeamHomeScore column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMatchstates requireOneByTeamawayscore(int $TeamAwayScore) Return the first ChildMatchstates filtered by the TeamAwayScore column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildMatchstates[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildMatchstates objects based on current ModelCriteria
 * @method     ChildMatchstates[]|ObjectCollection findByMatchStatePK(int $PrimaryKey) Return ChildMatchstates objects filtered by the PrimaryKey column
 * @method     ChildMatchstates[]|ObjectCollection findByMatchkey(int $MatchKey) Return ChildMatchstates objects filtered by the MatchKey column
 * @method     ChildMatchstates[]|ObjectCollection findByStatedate(string $StateDate) Return ChildMatchstates objects filtered by the StateDate column
 * @method     ChildMatchstates[]|ObjectCollection findByEventkey(int $EventKey) Return ChildMatchstates objects filtered by the EventKey column
 * @method     ChildMatchstates[]|ObjectCollection findByTeamhomescore(int $TeamHomeScore) Return ChildMatchstates objects filtered by the TeamHomeScore column
 * @method     ChildMatchstates[]|ObjectCollection findByTeamawayscore(int $TeamAwayScore) Return ChildMatchstates objects filtered by the TeamAwayScore column
 * @method     ChildMatchstates[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class MatchstatesQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Base\MatchstatesQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Matchstates', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildMatchstatesQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildMatchstatesQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildMatchstatesQuery) {
            return $criteria;
        }
        $query = new ChildMatchstatesQuery();
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
     * @return ChildMatchstates|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = MatchstatesTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(MatchstatesTableMap::DATABASE_NAME);
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
     * @return ChildMatchstates A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT PrimaryKey, MatchKey, StateDate, EventKey, TeamHomeScore, TeamAwayScore FROM matchstates WHERE PrimaryKey = :p0';
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
            /** @var ChildMatchstates $obj */
            $obj = new ChildMatchstates();
            $obj->hydrate($row);
            MatchstatesTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildMatchstates|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildMatchstatesQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(MatchstatesTableMap::COL_PRIMARYKEY, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildMatchstatesQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(MatchstatesTableMap::COL_PRIMARYKEY, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the PrimaryKey column
     *
     * Example usage:
     * <code>
     * $query->filterByMatchStatePK(1234); // WHERE PrimaryKey = 1234
     * $query->filterByMatchStatePK(array(12, 34)); // WHERE PrimaryKey IN (12, 34)
     * $query->filterByMatchStatePK(array('min' => 12)); // WHERE PrimaryKey > 12
     * </code>
     *
     * @param     mixed $matchStatePK The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMatchstatesQuery The current query, for fluid interface
     */
    public function filterByMatchStatePK($matchStatePK = null, $comparison = null)
    {
        if (is_array($matchStatePK)) {
            $useMinMax = false;
            if (isset($matchStatePK['min'])) {
                $this->addUsingAlias(MatchstatesTableMap::COL_PRIMARYKEY, $matchStatePK['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($matchStatePK['max'])) {
                $this->addUsingAlias(MatchstatesTableMap::COL_PRIMARYKEY, $matchStatePK['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MatchstatesTableMap::COL_PRIMARYKEY, $matchStatePK, $comparison);
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
     * @see       filterByMatchState()
     *
     * @param     mixed $matchkey The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMatchstatesQuery The current query, for fluid interface
     */
    public function filterByMatchkey($matchkey = null, $comparison = null)
    {
        if (is_array($matchkey)) {
            $useMinMax = false;
            if (isset($matchkey['min'])) {
                $this->addUsingAlias(MatchstatesTableMap::COL_MATCHKEY, $matchkey['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($matchkey['max'])) {
                $this->addUsingAlias(MatchstatesTableMap::COL_MATCHKEY, $matchkey['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MatchstatesTableMap::COL_MATCHKEY, $matchkey, $comparison);
    }

    /**
     * Filter the query on the StateDate column
     *
     * Example usage:
     * <code>
     * $query->filterByStatedate('2011-03-14'); // WHERE StateDate = '2011-03-14'
     * $query->filterByStatedate('now'); // WHERE StateDate = '2011-03-14'
     * $query->filterByStatedate(array('max' => 'yesterday')); // WHERE StateDate > '2011-03-13'
     * </code>
     *
     * @param     mixed $statedate The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMatchstatesQuery The current query, for fluid interface
     */
    public function filterByStatedate($statedate = null, $comparison = null)
    {
        if (is_array($statedate)) {
            $useMinMax = false;
            if (isset($statedate['min'])) {
                $this->addUsingAlias(MatchstatesTableMap::COL_STATEDATE, $statedate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($statedate['max'])) {
                $this->addUsingAlias(MatchstatesTableMap::COL_STATEDATE, $statedate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MatchstatesTableMap::COL_STATEDATE, $statedate, $comparison);
    }

    /**
     * Filter the query on the EventKey column
     *
     * Example usage:
     * <code>
     * $query->filterByEventkey(1234); // WHERE EventKey = 1234
     * $query->filterByEventkey(array(12, 34)); // WHERE EventKey IN (12, 34)
     * $query->filterByEventkey(array('min' => 12)); // WHERE EventKey > 12
     * </code>
     *
     * @see       filterByEvents()
     *
     * @param     mixed $eventkey The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMatchstatesQuery The current query, for fluid interface
     */
    public function filterByEventkey($eventkey = null, $comparison = null)
    {
        if (is_array($eventkey)) {
            $useMinMax = false;
            if (isset($eventkey['min'])) {
                $this->addUsingAlias(MatchstatesTableMap::COL_EVENTKEY, $eventkey['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($eventkey['max'])) {
                $this->addUsingAlias(MatchstatesTableMap::COL_EVENTKEY, $eventkey['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MatchstatesTableMap::COL_EVENTKEY, $eventkey, $comparison);
    }

    /**
     * Filter the query on the TeamHomeScore column
     *
     * Example usage:
     * <code>
     * $query->filterByTeamhomescore(1234); // WHERE TeamHomeScore = 1234
     * $query->filterByTeamhomescore(array(12, 34)); // WHERE TeamHomeScore IN (12, 34)
     * $query->filterByTeamhomescore(array('min' => 12)); // WHERE TeamHomeScore > 12
     * </code>
     *
     * @param     mixed $teamhomescore The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMatchstatesQuery The current query, for fluid interface
     */
    public function filterByTeamhomescore($teamhomescore = null, $comparison = null)
    {
        if (is_array($teamhomescore)) {
            $useMinMax = false;
            if (isset($teamhomescore['min'])) {
                $this->addUsingAlias(MatchstatesTableMap::COL_TEAMHOMESCORE, $teamhomescore['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($teamhomescore['max'])) {
                $this->addUsingAlias(MatchstatesTableMap::COL_TEAMHOMESCORE, $teamhomescore['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MatchstatesTableMap::COL_TEAMHOMESCORE, $teamhomescore, $comparison);
    }

    /**
     * Filter the query on the TeamAwayScore column
     *
     * Example usage:
     * <code>
     * $query->filterByTeamawayscore(1234); // WHERE TeamAwayScore = 1234
     * $query->filterByTeamawayscore(array(12, 34)); // WHERE TeamAwayScore IN (12, 34)
     * $query->filterByTeamawayscore(array('min' => 12)); // WHERE TeamAwayScore > 12
     * </code>
     *
     * @param     mixed $teamawayscore The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMatchstatesQuery The current query, for fluid interface
     */
    public function filterByTeamawayscore($teamawayscore = null, $comparison = null)
    {
        if (is_array($teamawayscore)) {
            $useMinMax = false;
            if (isset($teamawayscore['min'])) {
                $this->addUsingAlias(MatchstatesTableMap::COL_TEAMAWAYSCORE, $teamawayscore['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($teamawayscore['max'])) {
                $this->addUsingAlias(MatchstatesTableMap::COL_TEAMAWAYSCORE, $teamawayscore['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MatchstatesTableMap::COL_TEAMAWAYSCORE, $teamawayscore, $comparison);
    }

    /**
     * Filter the query by a related \Matches object
     *
     * @param \Matches|ObjectCollection $matches The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildMatchstatesQuery The current query, for fluid interface
     */
    public function filterByMatchState($matches, $comparison = null)
    {
        if ($matches instanceof \Matches) {
            return $this
                ->addUsingAlias(MatchstatesTableMap::COL_MATCHKEY, $matches->getMatchPK(), $comparison);
        } elseif ($matches instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(MatchstatesTableMap::COL_MATCHKEY, $matches->toKeyValue('PrimaryKey', 'MatchPK'), $comparison);
        } else {
            throw new PropelException('filterByMatchState() only accepts arguments of type \Matches or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the MatchState relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildMatchstatesQuery The current query, for fluid interface
     */
    public function joinMatchState($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('MatchState');

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
            $this->addJoinObject($join, 'MatchState');
        }

        return $this;
    }

    /**
     * Use the MatchState relation Matches object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \MatchesQuery A secondary query class using the current class as primary query
     */
    public function useMatchStateQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinMatchState($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'MatchState', '\MatchesQuery');
    }

    /**
     * Filter the query by a related \Events object
     *
     * @param \Events|ObjectCollection $events The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildMatchstatesQuery The current query, for fluid interface
     */
    public function filterByEvents($events, $comparison = null)
    {
        if ($events instanceof \Events) {
            return $this
                ->addUsingAlias(MatchstatesTableMap::COL_EVENTKEY, $events->getEventPK(), $comparison);
        } elseif ($events instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(MatchstatesTableMap::COL_EVENTKEY, $events->toKeyValue('PrimaryKey', 'EventPK'), $comparison);
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
     * @return $this|ChildMatchstatesQuery The current query, for fluid interface
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
     * Filter the query by a related \Playermatchstates object
     *
     * @param \Playermatchstates|ObjectCollection $playermatchstates the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildMatchstatesQuery The current query, for fluid interface
     */
    public function filterByPlayermatchstates($playermatchstates, $comparison = null)
    {
        if ($playermatchstates instanceof \Playermatchstates) {
            return $this
                ->addUsingAlias(MatchstatesTableMap::COL_PRIMARYKEY, $playermatchstates->getMatchstatekey(), $comparison);
        } elseif ($playermatchstates instanceof ObjectCollection) {
            return $this
                ->usePlayermatchstatesQuery()
                ->filterByPrimaryKeys($playermatchstates->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPlayermatchstates() only accepts arguments of type \Playermatchstates or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Playermatchstates relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildMatchstatesQuery The current query, for fluid interface
     */
    public function joinPlayermatchstates($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Playermatchstates');

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
            $this->addJoinObject($join, 'Playermatchstates');
        }

        return $this;
    }

    /**
     * Use the Playermatchstates relation Playermatchstates object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \PlayermatchstatesQuery A secondary query class using the current class as primary query
     */
    public function usePlayermatchstatesQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPlayermatchstates($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Playermatchstates', '\PlayermatchstatesQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildMatchstates $matchstates Object to remove from the list of results
     *
     * @return $this|ChildMatchstatesQuery The current query, for fluid interface
     */
    public function prune($matchstates = null)
    {
        if ($matchstates) {
            $this->addUsingAlias(MatchstatesTableMap::COL_PRIMARYKEY, $matchstates->getMatchStatePK(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the matchstates table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(MatchstatesTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            MatchstatesTableMap::clearInstancePool();
            MatchstatesTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(MatchstatesTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(MatchstatesTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            MatchstatesTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            MatchstatesTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // MatchstatesQuery
