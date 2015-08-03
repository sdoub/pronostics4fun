<?php

namespace Base;

use \Playergroupranking as ChildPlayergroupranking;
use \PlayergrouprankingQuery as ChildPlayergrouprankingQuery;
use \Exception;
use \PDO;
use Map\PlayergrouprankingTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'playergroupranking' table.
 *
 *
 *
 * @method     ChildPlayergrouprankingQuery orderByPlayerkey($order = Criteria::ASC) Order by the PlayerKey column
 * @method     ChildPlayergrouprankingQuery orderByGroupkey($order = Criteria::ASC) Order by the GroupKey column
 * @method     ChildPlayergrouprankingQuery orderByRankdate($order = Criteria::ASC) Order by the RankDate column
 * @method     ChildPlayergrouprankingQuery orderByRank($order = Criteria::ASC) Order by the Rank column
 *
 * @method     ChildPlayergrouprankingQuery groupByPlayerkey() Group by the PlayerKey column
 * @method     ChildPlayergrouprankingQuery groupByGroupkey() Group by the GroupKey column
 * @method     ChildPlayergrouprankingQuery groupByRankdate() Group by the RankDate column
 * @method     ChildPlayergrouprankingQuery groupByRank() Group by the Rank column
 *
 * @method     ChildPlayergrouprankingQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildPlayergrouprankingQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildPlayergrouprankingQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildPlayergrouprankingQuery leftJoinPlayerRanking($relationAlias = null) Adds a LEFT JOIN clause to the query using the PlayerRanking relation
 * @method     ChildPlayergrouprankingQuery rightJoinPlayerRanking($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PlayerRanking relation
 * @method     ChildPlayergrouprankingQuery innerJoinPlayerRanking($relationAlias = null) Adds a INNER JOIN clause to the query using the PlayerRanking relation
 *
 * @method     ChildPlayergrouprankingQuery leftJoinGroupRanking($relationAlias = null) Adds a LEFT JOIN clause to the query using the GroupRanking relation
 * @method     ChildPlayergrouprankingQuery rightJoinGroupRanking($relationAlias = null) Adds a RIGHT JOIN clause to the query using the GroupRanking relation
 * @method     ChildPlayergrouprankingQuery innerJoinGroupRanking($relationAlias = null) Adds a INNER JOIN clause to the query using the GroupRanking relation
 *
 * @method     \PlayersQuery|\GroupsQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildPlayergroupranking findOne(ConnectionInterface $con = null) Return the first ChildPlayergroupranking matching the query
 * @method     ChildPlayergroupranking findOneOrCreate(ConnectionInterface $con = null) Return the first ChildPlayergroupranking matching the query, or a new ChildPlayergroupranking object populated from the query conditions when no match is found
 *
 * @method     ChildPlayergroupranking findOneByPlayerkey(int $PlayerKey) Return the first ChildPlayergroupranking filtered by the PlayerKey column
 * @method     ChildPlayergroupranking findOneByGroupkey(int $GroupKey) Return the first ChildPlayergroupranking filtered by the GroupKey column
 * @method     ChildPlayergroupranking findOneByRankdate(string $RankDate) Return the first ChildPlayergroupranking filtered by the RankDate column
 * @method     ChildPlayergroupranking findOneByRank(int $Rank) Return the first ChildPlayergroupranking filtered by the Rank column *

 * @method     ChildPlayergroupranking requirePk($key, ConnectionInterface $con = null) Return the ChildPlayergroupranking by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayergroupranking requireOne(ConnectionInterface $con = null) Return the first ChildPlayergroupranking matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPlayergroupranking requireOneByPlayerkey(int $PlayerKey) Return the first ChildPlayergroupranking filtered by the PlayerKey column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayergroupranking requireOneByGroupkey(int $GroupKey) Return the first ChildPlayergroupranking filtered by the GroupKey column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayergroupranking requireOneByRankdate(string $RankDate) Return the first ChildPlayergroupranking filtered by the RankDate column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayergroupranking requireOneByRank(int $Rank) Return the first ChildPlayergroupranking filtered by the Rank column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPlayergroupranking[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildPlayergroupranking objects based on current ModelCriteria
 * @method     ChildPlayergroupranking[]|ObjectCollection findByPlayerkey(int $PlayerKey) Return ChildPlayergroupranking objects filtered by the PlayerKey column
 * @method     ChildPlayergroupranking[]|ObjectCollection findByGroupkey(int $GroupKey) Return ChildPlayergroupranking objects filtered by the GroupKey column
 * @method     ChildPlayergroupranking[]|ObjectCollection findByRankdate(string $RankDate) Return ChildPlayergroupranking objects filtered by the RankDate column
 * @method     ChildPlayergroupranking[]|ObjectCollection findByRank(int $Rank) Return ChildPlayergroupranking objects filtered by the Rank column
 * @method     ChildPlayergroupranking[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class PlayergrouprankingQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Base\PlayergrouprankingQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Playergroupranking', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildPlayergrouprankingQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildPlayergrouprankingQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildPlayergrouprankingQuery) {
            return $criteria;
        }
        $query = new ChildPlayergrouprankingQuery();
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
     * @param array[$PlayerKey, $GroupKey, $RankDate] $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildPlayergroupranking|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = PlayergrouprankingTableMap::getInstanceFromPool(serialize(array((string) $key[0], (string) $key[1], (string) $key[2]))))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(PlayergrouprankingTableMap::DATABASE_NAME);
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
     * @return ChildPlayergroupranking A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT PlayerKey, GroupKey, RankDate, Rank FROM playergroupranking WHERE PlayerKey = :p0 AND GroupKey = :p1 AND RankDate = :p2';
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
            /** @var ChildPlayergroupranking $obj */
            $obj = new ChildPlayergroupranking();
            $obj->hydrate($row);
            PlayergrouprankingTableMap::addInstanceToPool($obj, serialize(array((string) $key[0], (string) $key[1], (string) $key[2])));
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
     * @return ChildPlayergroupranking|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildPlayergrouprankingQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(PlayergrouprankingTableMap::COL_PLAYERKEY, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(PlayergrouprankingTableMap::COL_GROUPKEY, $key[1], Criteria::EQUAL);
        $this->addUsingAlias(PlayergrouprankingTableMap::COL_RANKDATE, $key[2], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildPlayergrouprankingQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(PlayergrouprankingTableMap::COL_PLAYERKEY, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(PlayergrouprankingTableMap::COL_GROUPKEY, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $cton2 = $this->getNewCriterion(PlayergrouprankingTableMap::COL_RANKDATE, $key[2], Criteria::EQUAL);
            $cton0->addAnd($cton2);
            $this->addOr($cton0);
        }

        return $this;
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
     * @see       filterByPlayerRanking()
     *
     * @param     mixed $playerkey The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlayergrouprankingQuery The current query, for fluid interface
     */
    public function filterByPlayerkey($playerkey = null, $comparison = null)
    {
        if (is_array($playerkey)) {
            $useMinMax = false;
            if (isset($playerkey['min'])) {
                $this->addUsingAlias(PlayergrouprankingTableMap::COL_PLAYERKEY, $playerkey['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($playerkey['max'])) {
                $this->addUsingAlias(PlayergrouprankingTableMap::COL_PLAYERKEY, $playerkey['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayergrouprankingTableMap::COL_PLAYERKEY, $playerkey, $comparison);
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
     * @see       filterByGroupRanking()
     *
     * @param     mixed $groupkey The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlayergrouprankingQuery The current query, for fluid interface
     */
    public function filterByGroupkey($groupkey = null, $comparison = null)
    {
        if (is_array($groupkey)) {
            $useMinMax = false;
            if (isset($groupkey['min'])) {
                $this->addUsingAlias(PlayergrouprankingTableMap::COL_GROUPKEY, $groupkey['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($groupkey['max'])) {
                $this->addUsingAlias(PlayergrouprankingTableMap::COL_GROUPKEY, $groupkey['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayergrouprankingTableMap::COL_GROUPKEY, $groupkey, $comparison);
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
     * @return $this|ChildPlayergrouprankingQuery The current query, for fluid interface
     */
    public function filterByRankdate($rankdate = null, $comparison = null)
    {
        if (is_array($rankdate)) {
            $useMinMax = false;
            if (isset($rankdate['min'])) {
                $this->addUsingAlias(PlayergrouprankingTableMap::COL_RANKDATE, $rankdate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($rankdate['max'])) {
                $this->addUsingAlias(PlayergrouprankingTableMap::COL_RANKDATE, $rankdate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayergrouprankingTableMap::COL_RANKDATE, $rankdate, $comparison);
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
     * @return $this|ChildPlayergrouprankingQuery The current query, for fluid interface
     */
    public function filterByRank($rank = null, $comparison = null)
    {
        if (is_array($rank)) {
            $useMinMax = false;
            if (isset($rank['min'])) {
                $this->addUsingAlias(PlayergrouprankingTableMap::COL_RANK, $rank['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($rank['max'])) {
                $this->addUsingAlias(PlayergrouprankingTableMap::COL_RANK, $rank['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayergrouprankingTableMap::COL_RANK, $rank, $comparison);
    }

    /**
     * Filter the query by a related \Players object
     *
     * @param \Players|ObjectCollection $players The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildPlayergrouprankingQuery The current query, for fluid interface
     */
    public function filterByPlayerRanking($players, $comparison = null)
    {
        if ($players instanceof \Players) {
            return $this
                ->addUsingAlias(PlayergrouprankingTableMap::COL_PLAYERKEY, $players->getPlayerPK(), $comparison);
        } elseif ($players instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PlayergrouprankingTableMap::COL_PLAYERKEY, $players->toKeyValue('PrimaryKey', 'PlayerPK'), $comparison);
        } else {
            throw new PropelException('filterByPlayerRanking() only accepts arguments of type \Players or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PlayerRanking relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPlayergrouprankingQuery The current query, for fluid interface
     */
    public function joinPlayerRanking($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PlayerRanking');

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
            $this->addJoinObject($join, 'PlayerRanking');
        }

        return $this;
    }

    /**
     * Use the PlayerRanking relation Players object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \PlayersQuery A secondary query class using the current class as primary query
     */
    public function usePlayerRankingQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPlayerRanking($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PlayerRanking', '\PlayersQuery');
    }

    /**
     * Filter the query by a related \Groups object
     *
     * @param \Groups|ObjectCollection $groups The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildPlayergrouprankingQuery The current query, for fluid interface
     */
    public function filterByGroupRanking($groups, $comparison = null)
    {
        if ($groups instanceof \Groups) {
            return $this
                ->addUsingAlias(PlayergrouprankingTableMap::COL_GROUPKEY, $groups->getGroupPK(), $comparison);
        } elseif ($groups instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PlayergrouprankingTableMap::COL_GROUPKEY, $groups->toKeyValue('PrimaryKey', 'GroupPK'), $comparison);
        } else {
            throw new PropelException('filterByGroupRanking() only accepts arguments of type \Groups or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the GroupRanking relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPlayergrouprankingQuery The current query, for fluid interface
     */
    public function joinGroupRanking($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('GroupRanking');

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
            $this->addJoinObject($join, 'GroupRanking');
        }

        return $this;
    }

    /**
     * Use the GroupRanking relation Groups object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \GroupsQuery A secondary query class using the current class as primary query
     */
    public function useGroupRankingQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinGroupRanking($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'GroupRanking', '\GroupsQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildPlayergroupranking $playergroupranking Object to remove from the list of results
     *
     * @return $this|ChildPlayergrouprankingQuery The current query, for fluid interface
     */
    public function prune($playergroupranking = null)
    {
        if ($playergroupranking) {
            $this->addCond('pruneCond0', $this->getAliasedColName(PlayergrouprankingTableMap::COL_PLAYERKEY), $playergroupranking->getPlayerkey(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(PlayergrouprankingTableMap::COL_GROUPKEY), $playergroupranking->getGroupkey(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond2', $this->getAliasedColName(PlayergrouprankingTableMap::COL_RANKDATE), $playergroupranking->getRankdate(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1', 'pruneCond2'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

    /**
     * Deletes all rows from the playergroupranking table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PlayergrouprankingTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            PlayergrouprankingTableMap::clearInstancePool();
            PlayergrouprankingTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(PlayergrouprankingTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(PlayergrouprankingTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            PlayergrouprankingTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            PlayergrouprankingTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // PlayergrouprankingQuery
