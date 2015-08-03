<?php

namespace Base;

use \Playermatchresults as ChildPlayermatchresults;
use \PlayermatchresultsQuery as ChildPlayermatchresultsQuery;
use \Exception;
use \PDO;
use Map\PlayermatchresultsTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'playermatchresults' table.
 *
 *
 *
 * @method     ChildPlayermatchresultsQuery orderByPlayerkey($order = Criteria::ASC) Order by the PlayerKey column
 * @method     ChildPlayermatchresultsQuery orderByMatchkey($order = Criteria::ASC) Order by the MatchKey column
 * @method     ChildPlayermatchresultsQuery orderByScore($order = Criteria::ASC) Order by the Score column
 * @method     ChildPlayermatchresultsQuery orderByIsperfect($order = Criteria::ASC) Order by the IsPerfect column
 *
 * @method     ChildPlayermatchresultsQuery groupByPlayerkey() Group by the PlayerKey column
 * @method     ChildPlayermatchresultsQuery groupByMatchkey() Group by the MatchKey column
 * @method     ChildPlayermatchresultsQuery groupByScore() Group by the Score column
 * @method     ChildPlayermatchresultsQuery groupByIsperfect() Group by the IsPerfect column
 *
 * @method     ChildPlayermatchresultsQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildPlayermatchresultsQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildPlayermatchresultsQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildPlayermatchresultsQuery leftJoinPlayerResult($relationAlias = null) Adds a LEFT JOIN clause to the query using the PlayerResult relation
 * @method     ChildPlayermatchresultsQuery rightJoinPlayerResult($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PlayerResult relation
 * @method     ChildPlayermatchresultsQuery innerJoinPlayerResult($relationAlias = null) Adds a INNER JOIN clause to the query using the PlayerResult relation
 *
 * @method     ChildPlayermatchresultsQuery leftJoinMatchPlayerResult($relationAlias = null) Adds a LEFT JOIN clause to the query using the MatchPlayerResult relation
 * @method     ChildPlayermatchresultsQuery rightJoinMatchPlayerResult($relationAlias = null) Adds a RIGHT JOIN clause to the query using the MatchPlayerResult relation
 * @method     ChildPlayermatchresultsQuery innerJoinMatchPlayerResult($relationAlias = null) Adds a INNER JOIN clause to the query using the MatchPlayerResult relation
 *
 * @method     \PlayersQuery|\MatchesQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildPlayermatchresults findOne(ConnectionInterface $con = null) Return the first ChildPlayermatchresults matching the query
 * @method     ChildPlayermatchresults findOneOrCreate(ConnectionInterface $con = null) Return the first ChildPlayermatchresults matching the query, or a new ChildPlayermatchresults object populated from the query conditions when no match is found
 *
 * @method     ChildPlayermatchresults findOneByPlayerkey(int $PlayerKey) Return the first ChildPlayermatchresults filtered by the PlayerKey column
 * @method     ChildPlayermatchresults findOneByMatchkey(int $MatchKey) Return the first ChildPlayermatchresults filtered by the MatchKey column
 * @method     ChildPlayermatchresults findOneByScore(int $Score) Return the first ChildPlayermatchresults filtered by the Score column
 * @method     ChildPlayermatchresults findOneByIsperfect(boolean $IsPerfect) Return the first ChildPlayermatchresults filtered by the IsPerfect column *

 * @method     ChildPlayermatchresults requirePk($key, ConnectionInterface $con = null) Return the ChildPlayermatchresults by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayermatchresults requireOne(ConnectionInterface $con = null) Return the first ChildPlayermatchresults matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPlayermatchresults requireOneByPlayerkey(int $PlayerKey) Return the first ChildPlayermatchresults filtered by the PlayerKey column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayermatchresults requireOneByMatchkey(int $MatchKey) Return the first ChildPlayermatchresults filtered by the MatchKey column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayermatchresults requireOneByScore(int $Score) Return the first ChildPlayermatchresults filtered by the Score column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayermatchresults requireOneByIsperfect(boolean $IsPerfect) Return the first ChildPlayermatchresults filtered by the IsPerfect column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPlayermatchresults[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildPlayermatchresults objects based on current ModelCriteria
 * @method     ChildPlayermatchresults[]|ObjectCollection findByPlayerkey(int $PlayerKey) Return ChildPlayermatchresults objects filtered by the PlayerKey column
 * @method     ChildPlayermatchresults[]|ObjectCollection findByMatchkey(int $MatchKey) Return ChildPlayermatchresults objects filtered by the MatchKey column
 * @method     ChildPlayermatchresults[]|ObjectCollection findByScore(int $Score) Return ChildPlayermatchresults objects filtered by the Score column
 * @method     ChildPlayermatchresults[]|ObjectCollection findByIsperfect(boolean $IsPerfect) Return ChildPlayermatchresults objects filtered by the IsPerfect column
 * @method     ChildPlayermatchresults[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class PlayermatchresultsQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Base\PlayermatchresultsQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Playermatchresults', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildPlayermatchresultsQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildPlayermatchresultsQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildPlayermatchresultsQuery) {
            return $criteria;
        }
        $query = new ChildPlayermatchresultsQuery();
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
     * $obj = $c->findPk(array(12, 34), $con);
     * </code>
     *
     * @param array[$PlayerKey, $MatchKey] $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildPlayermatchresults|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = PlayermatchresultsTableMap::getInstanceFromPool(serialize(array((string) $key[0], (string) $key[1]))))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(PlayermatchresultsTableMap::DATABASE_NAME);
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
     * @return ChildPlayermatchresults A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT PlayerKey, MatchKey, Score, IsPerfect FROM playermatchresults WHERE PlayerKey = :p0 AND MatchKey = :p1';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key[0], PDO::PARAM_INT);
            $stmt->bindValue(':p1', $key[1], PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildPlayermatchresults $obj */
            $obj = new ChildPlayermatchresults();
            $obj->hydrate($row);
            PlayermatchresultsTableMap::addInstanceToPool($obj, serialize(array((string) $key[0], (string) $key[1])));
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
     * @return ChildPlayermatchresults|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildPlayermatchresultsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(PlayermatchresultsTableMap::COL_PLAYERKEY, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(PlayermatchresultsTableMap::COL_MATCHKEY, $key[1], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildPlayermatchresultsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(PlayermatchresultsTableMap::COL_PLAYERKEY, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(PlayermatchresultsTableMap::COL_MATCHKEY, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
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
     * @see       filterByPlayerResult()
     *
     * @param     mixed $playerkey The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlayermatchresultsQuery The current query, for fluid interface
     */
    public function filterByPlayerkey($playerkey = null, $comparison = null)
    {
        if (is_array($playerkey)) {
            $useMinMax = false;
            if (isset($playerkey['min'])) {
                $this->addUsingAlias(PlayermatchresultsTableMap::COL_PLAYERKEY, $playerkey['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($playerkey['max'])) {
                $this->addUsingAlias(PlayermatchresultsTableMap::COL_PLAYERKEY, $playerkey['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayermatchresultsTableMap::COL_PLAYERKEY, $playerkey, $comparison);
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
     * @see       filterByMatchPlayerResult()
     *
     * @param     mixed $matchkey The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlayermatchresultsQuery The current query, for fluid interface
     */
    public function filterByMatchkey($matchkey = null, $comparison = null)
    {
        if (is_array($matchkey)) {
            $useMinMax = false;
            if (isset($matchkey['min'])) {
                $this->addUsingAlias(PlayermatchresultsTableMap::COL_MATCHKEY, $matchkey['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($matchkey['max'])) {
                $this->addUsingAlias(PlayermatchresultsTableMap::COL_MATCHKEY, $matchkey['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayermatchresultsTableMap::COL_MATCHKEY, $matchkey, $comparison);
    }

    /**
     * Filter the query on the Score column
     *
     * Example usage:
     * <code>
     * $query->filterByScore(1234); // WHERE Score = 1234
     * $query->filterByScore(array(12, 34)); // WHERE Score IN (12, 34)
     * $query->filterByScore(array('min' => 12)); // WHERE Score > 12
     * </code>
     *
     * @param     mixed $score The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlayermatchresultsQuery The current query, for fluid interface
     */
    public function filterByScore($score = null, $comparison = null)
    {
        if (is_array($score)) {
            $useMinMax = false;
            if (isset($score['min'])) {
                $this->addUsingAlias(PlayermatchresultsTableMap::COL_SCORE, $score['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($score['max'])) {
                $this->addUsingAlias(PlayermatchresultsTableMap::COL_SCORE, $score['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayermatchresultsTableMap::COL_SCORE, $score, $comparison);
    }

    /**
     * Filter the query on the IsPerfect column
     *
     * Example usage:
     * <code>
     * $query->filterByIsperfect(true); // WHERE IsPerfect = true
     * $query->filterByIsperfect('yes'); // WHERE IsPerfect = true
     * </code>
     *
     * @param     boolean|string $isperfect The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlayermatchresultsQuery The current query, for fluid interface
     */
    public function filterByIsperfect($isperfect = null, $comparison = null)
    {
        if (is_string($isperfect)) {
            $isperfect = in_array(strtolower($isperfect), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(PlayermatchresultsTableMap::COL_ISPERFECT, $isperfect, $comparison);
    }

    /**
     * Filter the query by a related \Players object
     *
     * @param \Players|ObjectCollection $players The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildPlayermatchresultsQuery The current query, for fluid interface
     */
    public function filterByPlayerResult($players, $comparison = null)
    {
        if ($players instanceof \Players) {
            return $this
                ->addUsingAlias(PlayermatchresultsTableMap::COL_PLAYERKEY, $players->getPlayerPK(), $comparison);
        } elseif ($players instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PlayermatchresultsTableMap::COL_PLAYERKEY, $players->toKeyValue('PrimaryKey', 'PlayerPK'), $comparison);
        } else {
            throw new PropelException('filterByPlayerResult() only accepts arguments of type \Players or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PlayerResult relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPlayermatchresultsQuery The current query, for fluid interface
     */
    public function joinPlayerResult($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PlayerResult');

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
            $this->addJoinObject($join, 'PlayerResult');
        }

        return $this;
    }

    /**
     * Use the PlayerResult relation Players object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \PlayersQuery A secondary query class using the current class as primary query
     */
    public function usePlayerResultQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPlayerResult($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PlayerResult', '\PlayersQuery');
    }

    /**
     * Filter the query by a related \Matches object
     *
     * @param \Matches|ObjectCollection $matches The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildPlayermatchresultsQuery The current query, for fluid interface
     */
    public function filterByMatchPlayerResult($matches, $comparison = null)
    {
        if ($matches instanceof \Matches) {
            return $this
                ->addUsingAlias(PlayermatchresultsTableMap::COL_MATCHKEY, $matches->getMatchPK(), $comparison);
        } elseif ($matches instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PlayermatchresultsTableMap::COL_MATCHKEY, $matches->toKeyValue('PrimaryKey', 'MatchPK'), $comparison);
        } else {
            throw new PropelException('filterByMatchPlayerResult() only accepts arguments of type \Matches or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the MatchPlayerResult relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPlayermatchresultsQuery The current query, for fluid interface
     */
    public function joinMatchPlayerResult($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('MatchPlayerResult');

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
            $this->addJoinObject($join, 'MatchPlayerResult');
        }

        return $this;
    }

    /**
     * Use the MatchPlayerResult relation Matches object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \MatchesQuery A secondary query class using the current class as primary query
     */
    public function useMatchPlayerResultQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinMatchPlayerResult($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'MatchPlayerResult', '\MatchesQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildPlayermatchresults $playermatchresults Object to remove from the list of results
     *
     * @return $this|ChildPlayermatchresultsQuery The current query, for fluid interface
     */
    public function prune($playermatchresults = null)
    {
        if ($playermatchresults) {
            $this->addCond('pruneCond0', $this->getAliasedColName(PlayermatchresultsTableMap::COL_PLAYERKEY), $playermatchresults->getPlayerkey(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(PlayermatchresultsTableMap::COL_MATCHKEY), $playermatchresults->getMatchkey(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

    /**
     * Deletes all rows from the playermatchresults table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PlayermatchresultsTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            PlayermatchresultsTableMap::clearInstancePool();
            PlayermatchresultsTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(PlayermatchresultsTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(PlayermatchresultsTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            PlayermatchresultsTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            PlayermatchresultsTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // PlayermatchresultsQuery
