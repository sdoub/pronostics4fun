<?php

namespace Base;

use \Teams as ChildTeams;
use \TeamsQuery as ChildTeamsQuery;
use \Exception;
use \PDO;
use Map\TeamsTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'teams' table.
 *
 *
 *
 * @method     ChildTeamsQuery orderByTeamPK($order = Criteria::ASC) Order by the PrimaryKey column
 * @method     ChildTeamsQuery orderByName($order = Criteria::ASC) Order by the Name column
 * @method     ChildTeamsQuery orderByCode($order = Criteria::ASC) Order by the Code column
 *
 * @method     ChildTeamsQuery groupByTeamPK() Group by the PrimaryKey column
 * @method     ChildTeamsQuery groupByName() Group by the Name column
 * @method     ChildTeamsQuery groupByCode() Group by the Code column
 *
 * @method     ChildTeamsQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildTeamsQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildTeamsQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildTeamsQuery leftJoinEvents($relationAlias = null) Adds a LEFT JOIN clause to the query using the Events relation
 * @method     ChildTeamsQuery rightJoinEvents($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Events relation
 * @method     ChildTeamsQuery innerJoinEvents($relationAlias = null) Adds a INNER JOIN clause to the query using the Events relation
 *
 * @method     ChildTeamsQuery leftJoinLineups($relationAlias = null) Adds a LEFT JOIN clause to the query using the Lineups relation
 * @method     ChildTeamsQuery rightJoinLineups($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Lineups relation
 * @method     ChildTeamsQuery innerJoinLineups($relationAlias = null) Adds a INNER JOIN clause to the query using the Lineups relation
 *
 * @method     ChildTeamsQuery leftJoinMatchesRelatedByTeamhomekey($relationAlias = null) Adds a LEFT JOIN clause to the query using the MatchesRelatedByTeamhomekey relation
 * @method     ChildTeamsQuery rightJoinMatchesRelatedByTeamhomekey($relationAlias = null) Adds a RIGHT JOIN clause to the query using the MatchesRelatedByTeamhomekey relation
 * @method     ChildTeamsQuery innerJoinMatchesRelatedByTeamhomekey($relationAlias = null) Adds a INNER JOIN clause to the query using the MatchesRelatedByTeamhomekey relation
 *
 * @method     ChildTeamsQuery leftJoinMatchesRelatedByTeamawaykey($relationAlias = null) Adds a LEFT JOIN clause to the query using the MatchesRelatedByTeamawaykey relation
 * @method     ChildTeamsQuery rightJoinMatchesRelatedByTeamawaykey($relationAlias = null) Adds a RIGHT JOIN clause to the query using the MatchesRelatedByTeamawaykey relation
 * @method     ChildTeamsQuery innerJoinMatchesRelatedByTeamawaykey($relationAlias = null) Adds a INNER JOIN clause to the query using the MatchesRelatedByTeamawaykey relation
 *
 * @method     \EventsQuery|\LineupsQuery|\MatchesQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildTeams findOne(ConnectionInterface $con = null) Return the first ChildTeams matching the query
 * @method     ChildTeams findOneOrCreate(ConnectionInterface $con = null) Return the first ChildTeams matching the query, or a new ChildTeams object populated from the query conditions when no match is found
 *
 * @method     ChildTeams findOneByTeamPK(int $PrimaryKey) Return the first ChildTeams filtered by the PrimaryKey column
 * @method     ChildTeams findOneByName(string $Name) Return the first ChildTeams filtered by the Name column
 * @method     ChildTeams findOneByCode(string $Code) Return the first ChildTeams filtered by the Code column *

 * @method     ChildTeams requirePk($key, ConnectionInterface $con = null) Return the ChildTeams by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildTeams requireOne(ConnectionInterface $con = null) Return the first ChildTeams matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildTeams requireOneByTeamPK(int $PrimaryKey) Return the first ChildTeams filtered by the PrimaryKey column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildTeams requireOneByName(string $Name) Return the first ChildTeams filtered by the Name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildTeams requireOneByCode(string $Code) Return the first ChildTeams filtered by the Code column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildTeams[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildTeams objects based on current ModelCriteria
 * @method     ChildTeams[]|ObjectCollection findByTeamPK(int $PrimaryKey) Return ChildTeams objects filtered by the PrimaryKey column
 * @method     ChildTeams[]|ObjectCollection findByName(string $Name) Return ChildTeams objects filtered by the Name column
 * @method     ChildTeams[]|ObjectCollection findByCode(string $Code) Return ChildTeams objects filtered by the Code column
 * @method     ChildTeams[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class TeamsQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Base\TeamsQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Teams', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildTeamsQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildTeamsQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildTeamsQuery) {
            return $criteria;
        }
        $query = new ChildTeamsQuery();
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
     * @return ChildTeams|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = TeamsTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(TeamsTableMap::DATABASE_NAME);
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
     * @return ChildTeams A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT PrimaryKey, Name, Code FROM teams WHERE PrimaryKey = :p0';
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
            /** @var ChildTeams $obj */
            $obj = new ChildTeams();
            $obj->hydrate($row);
            TeamsTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildTeams|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildTeamsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(TeamsTableMap::COL_PRIMARYKEY, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildTeamsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(TeamsTableMap::COL_PRIMARYKEY, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the PrimaryKey column
     *
     * Example usage:
     * <code>
     * $query->filterByTeamPK(1234); // WHERE PrimaryKey = 1234
     * $query->filterByTeamPK(array(12, 34)); // WHERE PrimaryKey IN (12, 34)
     * $query->filterByTeamPK(array('min' => 12)); // WHERE PrimaryKey > 12
     * </code>
     *
     * @param     mixed $teamPK The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildTeamsQuery The current query, for fluid interface
     */
    public function filterByTeamPK($teamPK = null, $comparison = null)
    {
        if (is_array($teamPK)) {
            $useMinMax = false;
            if (isset($teamPK['min'])) {
                $this->addUsingAlias(TeamsTableMap::COL_PRIMARYKEY, $teamPK['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($teamPK['max'])) {
                $this->addUsingAlias(TeamsTableMap::COL_PRIMARYKEY, $teamPK['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TeamsTableMap::COL_PRIMARYKEY, $teamPK, $comparison);
    }

    /**
     * Filter the query on the Name column
     *
     * Example usage:
     * <code>
     * $query->filterByName('fooValue');   // WHERE Name = 'fooValue'
     * $query->filterByName('%fooValue%'); // WHERE Name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $name The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildTeamsQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $name)) {
                $name = str_replace('*', '%', $name);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(TeamsTableMap::COL_NAME, $name, $comparison);
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
     * @return $this|ChildTeamsQuery The current query, for fluid interface
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

        return $this->addUsingAlias(TeamsTableMap::COL_CODE, $code, $comparison);
    }

    /**
     * Filter the query by a related \Events object
     *
     * @param \Events|ObjectCollection $events the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildTeamsQuery The current query, for fluid interface
     */
    public function filterByEvents($events, $comparison = null)
    {
        if ($events instanceof \Events) {
            return $this
                ->addUsingAlias(TeamsTableMap::COL_PRIMARYKEY, $events->getTeamkey(), $comparison);
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
     * @return $this|ChildTeamsQuery The current query, for fluid interface
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
     * Filter the query by a related \Lineups object
     *
     * @param \Lineups|ObjectCollection $lineups the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildTeamsQuery The current query, for fluid interface
     */
    public function filterByLineups($lineups, $comparison = null)
    {
        if ($lineups instanceof \Lineups) {
            return $this
                ->addUsingAlias(TeamsTableMap::COL_PRIMARYKEY, $lineups->getTeamkey(), $comparison);
        } elseif ($lineups instanceof ObjectCollection) {
            return $this
                ->useLineupsQuery()
                ->filterByPrimaryKeys($lineups->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByLineups() only accepts arguments of type \Lineups or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Lineups relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildTeamsQuery The current query, for fluid interface
     */
    public function joinLineups($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Lineups');

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
            $this->addJoinObject($join, 'Lineups');
        }

        return $this;
    }

    /**
     * Use the Lineups relation Lineups object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \LineupsQuery A secondary query class using the current class as primary query
     */
    public function useLineupsQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinLineups($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Lineups', '\LineupsQuery');
    }

    /**
     * Filter the query by a related \Matches object
     *
     * @param \Matches|ObjectCollection $matches the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildTeamsQuery The current query, for fluid interface
     */
    public function filterByMatchesRelatedByTeamhomekey($matches, $comparison = null)
    {
        if ($matches instanceof \Matches) {
            return $this
                ->addUsingAlias(TeamsTableMap::COL_PRIMARYKEY, $matches->getTeamhomekey(), $comparison);
        } elseif ($matches instanceof ObjectCollection) {
            return $this
                ->useMatchesRelatedByTeamhomekeyQuery()
                ->filterByPrimaryKeys($matches->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByMatchesRelatedByTeamhomekey() only accepts arguments of type \Matches or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the MatchesRelatedByTeamhomekey relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildTeamsQuery The current query, for fluid interface
     */
    public function joinMatchesRelatedByTeamhomekey($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('MatchesRelatedByTeamhomekey');

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
            $this->addJoinObject($join, 'MatchesRelatedByTeamhomekey');
        }

        return $this;
    }

    /**
     * Use the MatchesRelatedByTeamhomekey relation Matches object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \MatchesQuery A secondary query class using the current class as primary query
     */
    public function useMatchesRelatedByTeamhomekeyQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinMatchesRelatedByTeamhomekey($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'MatchesRelatedByTeamhomekey', '\MatchesQuery');
    }

    /**
     * Filter the query by a related \Matches object
     *
     * @param \Matches|ObjectCollection $matches the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildTeamsQuery The current query, for fluid interface
     */
    public function filterByMatchesRelatedByTeamawaykey($matches, $comparison = null)
    {
        if ($matches instanceof \Matches) {
            return $this
                ->addUsingAlias(TeamsTableMap::COL_PRIMARYKEY, $matches->getTeamawaykey(), $comparison);
        } elseif ($matches instanceof ObjectCollection) {
            return $this
                ->useMatchesRelatedByTeamawaykeyQuery()
                ->filterByPrimaryKeys($matches->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByMatchesRelatedByTeamawaykey() only accepts arguments of type \Matches or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the MatchesRelatedByTeamawaykey relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildTeamsQuery The current query, for fluid interface
     */
    public function joinMatchesRelatedByTeamawaykey($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('MatchesRelatedByTeamawaykey');

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
            $this->addJoinObject($join, 'MatchesRelatedByTeamawaykey');
        }

        return $this;
    }

    /**
     * Use the MatchesRelatedByTeamawaykey relation Matches object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \MatchesQuery A secondary query class using the current class as primary query
     */
    public function useMatchesRelatedByTeamawaykeyQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinMatchesRelatedByTeamawaykey($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'MatchesRelatedByTeamawaykey', '\MatchesQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildTeams $teams Object to remove from the list of results
     *
     * @return $this|ChildTeamsQuery The current query, for fluid interface
     */
    public function prune($teams = null)
    {
        if ($teams) {
            $this->addUsingAlias(TeamsTableMap::COL_PRIMARYKEY, $teams->getTeamPK(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the teams table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(TeamsTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            TeamsTableMap::clearInstancePool();
            TeamsTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(TeamsTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(TeamsTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            TeamsTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            TeamsTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // TeamsQuery
