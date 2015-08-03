<?php

namespace Base;

use \Playerranking as ChildPlayerranking;
use \PlayerrankingQuery as ChildPlayerrankingQuery;
use \Exception;
use \PDO;
use Map\PlayerrankingTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'playerranking' table.
 *
 *
 *
 * @method     ChildPlayerrankingQuery orderByCompetitionkey($order = Criteria::ASC) Order by the CompetitionKey column
 * @method     ChildPlayerrankingQuery orderByPlayerkey($order = Criteria::ASC) Order by the PlayerKey column
 * @method     ChildPlayerrankingQuery orderByRankdate($order = Criteria::ASC) Order by the RankDate column
 * @method     ChildPlayerrankingQuery orderByRank($order = Criteria::ASC) Order by the Rank column
 *
 * @method     ChildPlayerrankingQuery groupByCompetitionkey() Group by the CompetitionKey column
 * @method     ChildPlayerrankingQuery groupByPlayerkey() Group by the PlayerKey column
 * @method     ChildPlayerrankingQuery groupByRankdate() Group by the RankDate column
 * @method     ChildPlayerrankingQuery groupByRank() Group by the Rank column
 *
 * @method     ChildPlayerrankingQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildPlayerrankingQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildPlayerrankingQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildPlayerrankingQuery leftJoinCompetitionRanking($relationAlias = null) Adds a LEFT JOIN clause to the query using the CompetitionRanking relation
 * @method     ChildPlayerrankingQuery rightJoinCompetitionRanking($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CompetitionRanking relation
 * @method     ChildPlayerrankingQuery innerJoinCompetitionRanking($relationAlias = null) Adds a INNER JOIN clause to the query using the CompetitionRanking relation
 *
 * @method     ChildPlayerrankingQuery leftJoinRankingPlayer($relationAlias = null) Adds a LEFT JOIN clause to the query using the RankingPlayer relation
 * @method     ChildPlayerrankingQuery rightJoinRankingPlayer($relationAlias = null) Adds a RIGHT JOIN clause to the query using the RankingPlayer relation
 * @method     ChildPlayerrankingQuery innerJoinRankingPlayer($relationAlias = null) Adds a INNER JOIN clause to the query using the RankingPlayer relation
 *
 * @method     \CompetitionsQuery|\PlayersQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildPlayerranking findOne(ConnectionInterface $con = null) Return the first ChildPlayerranking matching the query
 * @method     ChildPlayerranking findOneOrCreate(ConnectionInterface $con = null) Return the first ChildPlayerranking matching the query, or a new ChildPlayerranking object populated from the query conditions when no match is found
 *
 * @method     ChildPlayerranking findOneByCompetitionkey(int $CompetitionKey) Return the first ChildPlayerranking filtered by the CompetitionKey column
 * @method     ChildPlayerranking findOneByPlayerkey(int $PlayerKey) Return the first ChildPlayerranking filtered by the PlayerKey column
 * @method     ChildPlayerranking findOneByRankdate(string $RankDate) Return the first ChildPlayerranking filtered by the RankDate column
 * @method     ChildPlayerranking findOneByRank(int $Rank) Return the first ChildPlayerranking filtered by the Rank column *

 * @method     ChildPlayerranking requirePk($key, ConnectionInterface $con = null) Return the ChildPlayerranking by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayerranking requireOne(ConnectionInterface $con = null) Return the first ChildPlayerranking matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPlayerranking requireOneByCompetitionkey(int $CompetitionKey) Return the first ChildPlayerranking filtered by the CompetitionKey column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayerranking requireOneByPlayerkey(int $PlayerKey) Return the first ChildPlayerranking filtered by the PlayerKey column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayerranking requireOneByRankdate(string $RankDate) Return the first ChildPlayerranking filtered by the RankDate column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayerranking requireOneByRank(int $Rank) Return the first ChildPlayerranking filtered by the Rank column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPlayerranking[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildPlayerranking objects based on current ModelCriteria
 * @method     ChildPlayerranking[]|ObjectCollection findByCompetitionkey(int $CompetitionKey) Return ChildPlayerranking objects filtered by the CompetitionKey column
 * @method     ChildPlayerranking[]|ObjectCollection findByPlayerkey(int $PlayerKey) Return ChildPlayerranking objects filtered by the PlayerKey column
 * @method     ChildPlayerranking[]|ObjectCollection findByRankdate(string $RankDate) Return ChildPlayerranking objects filtered by the RankDate column
 * @method     ChildPlayerranking[]|ObjectCollection findByRank(int $Rank) Return ChildPlayerranking objects filtered by the Rank column
 * @method     ChildPlayerranking[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class PlayerrankingQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Base\PlayerrankingQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Playerranking', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildPlayerrankingQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildPlayerrankingQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildPlayerrankingQuery) {
            return $criteria;
        }
        $query = new ChildPlayerrankingQuery();
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
     * $obj = $c->findPk(array(12, 34, 56), $con);
     * </code>
     *
     * @param array[$CompetitionKey, $PlayerKey, $RankDate] $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildPlayerranking|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = PlayerrankingTableMap::getInstanceFromPool(serialize(array((string) $key[0], (string) $key[1], (string) $key[2]))))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(PlayerrankingTableMap::DATABASE_NAME);
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
     * @return ChildPlayerranking A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT CompetitionKey, PlayerKey, RankDate, Rank FROM playerranking WHERE CompetitionKey = :p0 AND PlayerKey = :p1 AND RankDate = :p2';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key[0], PDO::PARAM_INT);
            $stmt->bindValue(':p1', $key[1], PDO::PARAM_INT);
            $stmt->bindValue(':p2', $key[2] ? $key[2]->format("Y-m-d H:i:s") : null, PDO::PARAM_STR);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildPlayerranking $obj */
            $obj = new ChildPlayerranking();
            $obj->hydrate($row);
            PlayerrankingTableMap::addInstanceToPool($obj, serialize(array((string) $key[0], (string) $key[1], (string) $key[2])));
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
     * @return ChildPlayerranking|array|mixed the result, formatted by the current formatter
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
     * $objs = $c->findPks(array(array(12, 56), array(832, 123), array(123, 456)), $con);
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
     * @return $this|ChildPlayerrankingQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(PlayerrankingTableMap::COL_COMPETITIONKEY, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(PlayerrankingTableMap::COL_PLAYERKEY, $key[1], Criteria::EQUAL);
        $this->addUsingAlias(PlayerrankingTableMap::COL_RANKDATE, $key[2], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildPlayerrankingQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(PlayerrankingTableMap::COL_COMPETITIONKEY, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(PlayerrankingTableMap::COL_PLAYERKEY, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $cton2 = $this->getNewCriterion(PlayerrankingTableMap::COL_RANKDATE, $key[2], Criteria::EQUAL);
            $cton0->addAnd($cton2);
            $this->addOr($cton0);
        }

        return $this;
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
     * @see       filterByCompetitionRanking()
     *
     * @param     mixed $competitionkey The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlayerrankingQuery The current query, for fluid interface
     */
    public function filterByCompetitionkey($competitionkey = null, $comparison = null)
    {
        if (is_array($competitionkey)) {
            $useMinMax = false;
            if (isset($competitionkey['min'])) {
                $this->addUsingAlias(PlayerrankingTableMap::COL_COMPETITIONKEY, $competitionkey['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($competitionkey['max'])) {
                $this->addUsingAlias(PlayerrankingTableMap::COL_COMPETITIONKEY, $competitionkey['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayerrankingTableMap::COL_COMPETITIONKEY, $competitionkey, $comparison);
    }

    /**
     * Filter the query on the PlayerKey column
     *
     * Example usage:
     * <code>
     * $query->filterByPlayerkey(1234); // WHERE PlayerKey = 1234
     * $query->filterByPlayerkey(array(12, 34)); // WHERE PlayerKey IN (12, 34)
     * $query->filterByPlayerkey(array('min' => 12)); // WHERE PlayerKey > 12
     * </code>
     *
     * @see       filterByRankingPlayer()
     *
     * @param     mixed $playerkey The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlayerrankingQuery The current query, for fluid interface
     */
    public function filterByPlayerkey($playerkey = null, $comparison = null)
    {
        if (is_array($playerkey)) {
            $useMinMax = false;
            if (isset($playerkey['min'])) {
                $this->addUsingAlias(PlayerrankingTableMap::COL_PLAYERKEY, $playerkey['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($playerkey['max'])) {
                $this->addUsingAlias(PlayerrankingTableMap::COL_PLAYERKEY, $playerkey['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayerrankingTableMap::COL_PLAYERKEY, $playerkey, $comparison);
    }

    /**
     * Filter the query on the RankDate column
     *
     * Example usage:
     * <code>
     * $query->filterByRankdate('2011-03-14'); // WHERE RankDate = '2011-03-14'
     * $query->filterByRankdate('now'); // WHERE RankDate = '2011-03-14'
     * $query->filterByRankdate(array('max' => 'yesterday')); // WHERE RankDate > '2011-03-13'
     * </code>
     *
     * @param     mixed $rankdate The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlayerrankingQuery The current query, for fluid interface
     */
    public function filterByRankdate($rankdate = null, $comparison = null)
    {
        if (is_array($rankdate)) {
            $useMinMax = false;
            if (isset($rankdate['min'])) {
                $this->addUsingAlias(PlayerrankingTableMap::COL_RANKDATE, $rankdate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($rankdate['max'])) {
                $this->addUsingAlias(PlayerrankingTableMap::COL_RANKDATE, $rankdate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayerrankingTableMap::COL_RANKDATE, $rankdate, $comparison);
    }

    /**
     * Filter the query on the Rank column
     *
     * Example usage:
     * <code>
     * $query->filterByRank(1234); // WHERE Rank = 1234
     * $query->filterByRank(array(12, 34)); // WHERE Rank IN (12, 34)
     * $query->filterByRank(array('min' => 12)); // WHERE Rank > 12
     * </code>
     *
     * @param     mixed $rank The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlayerrankingQuery The current query, for fluid interface
     */
    public function filterByRank($rank = null, $comparison = null)
    {
        if (is_array($rank)) {
            $useMinMax = false;
            if (isset($rank['min'])) {
                $this->addUsingAlias(PlayerrankingTableMap::COL_RANK, $rank['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($rank['max'])) {
                $this->addUsingAlias(PlayerrankingTableMap::COL_RANK, $rank['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayerrankingTableMap::COL_RANK, $rank, $comparison);
    }

    /**
     * Filter the query by a related \Competitions object
     *
     * @param \Competitions|ObjectCollection $competitions The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildPlayerrankingQuery The current query, for fluid interface
     */
    public function filterByCompetitionRanking($competitions, $comparison = null)
    {
        if ($competitions instanceof \Competitions) {
            return $this
                ->addUsingAlias(PlayerrankingTableMap::COL_COMPETITIONKEY, $competitions->getCompetitionPK(), $comparison);
        } elseif ($competitions instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PlayerrankingTableMap::COL_COMPETITIONKEY, $competitions->toKeyValue('PrimaryKey', 'CompetitionPK'), $comparison);
        } else {
            throw new PropelException('filterByCompetitionRanking() only accepts arguments of type \Competitions or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CompetitionRanking relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPlayerrankingQuery The current query, for fluid interface
     */
    public function joinCompetitionRanking($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CompetitionRanking');

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
            $this->addJoinObject($join, 'CompetitionRanking');
        }

        return $this;
    }

    /**
     * Use the CompetitionRanking relation Competitions object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \CompetitionsQuery A secondary query class using the current class as primary query
     */
    public function useCompetitionRankingQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinCompetitionRanking($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CompetitionRanking', '\CompetitionsQuery');
    }

    /**
     * Filter the query by a related \Players object
     *
     * @param \Players|ObjectCollection $players The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildPlayerrankingQuery The current query, for fluid interface
     */
    public function filterByRankingPlayer($players, $comparison = null)
    {
        if ($players instanceof \Players) {
            return $this
                ->addUsingAlias(PlayerrankingTableMap::COL_PLAYERKEY, $players->getPlayerPK(), $comparison);
        } elseif ($players instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PlayerrankingTableMap::COL_PLAYERKEY, $players->toKeyValue('PrimaryKey', 'PlayerPK'), $comparison);
        } else {
            throw new PropelException('filterByRankingPlayer() only accepts arguments of type \Players or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the RankingPlayer relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPlayerrankingQuery The current query, for fluid interface
     */
    public function joinRankingPlayer($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('RankingPlayer');

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
            $this->addJoinObject($join, 'RankingPlayer');
        }

        return $this;
    }

    /**
     * Use the RankingPlayer relation Players object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \PlayersQuery A secondary query class using the current class as primary query
     */
    public function useRankingPlayerQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinRankingPlayer($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'RankingPlayer', '\PlayersQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildPlayerranking $playerranking Object to remove from the list of results
     *
     * @return $this|ChildPlayerrankingQuery The current query, for fluid interface
     */
    public function prune($playerranking = null)
    {
        if ($playerranking) {
            $this->addCond('pruneCond0', $this->getAliasedColName(PlayerrankingTableMap::COL_COMPETITIONKEY), $playerranking->getCompetitionkey(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(PlayerrankingTableMap::COL_PLAYERKEY), $playerranking->getPlayerkey(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond2', $this->getAliasedColName(PlayerrankingTableMap::COL_RANKDATE), $playerranking->getRankdate(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1', 'pruneCond2'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

    /**
     * Deletes all rows from the playerranking table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PlayerrankingTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            PlayerrankingTableMap::clearInstancePool();
            PlayerrankingTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(PlayerrankingTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(PlayerrankingTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            PlayerrankingTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            PlayerrankingTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // PlayerrankingQuery
