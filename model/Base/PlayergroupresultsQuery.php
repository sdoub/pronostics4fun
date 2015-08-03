<?php

namespace Base;

use \Playergroupresults as ChildPlayergroupresults;
use \PlayergroupresultsQuery as ChildPlayergroupresultsQuery;
use \Exception;
use \PDO;
use Map\PlayergroupresultsTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'playergroupresults' table.
 *
 *
 *
 * @method     ChildPlayergroupresultsQuery orderByPlayerkey($order = Criteria::ASC) Order by the PlayerKey column
 * @method     ChildPlayergroupresultsQuery orderByGroupkey($order = Criteria::ASC) Order by the GroupKey column
 * @method     ChildPlayergroupresultsQuery orderByScore($order = Criteria::ASC) Order by the Score column
 *
 * @method     ChildPlayergroupresultsQuery groupByPlayerkey() Group by the PlayerKey column
 * @method     ChildPlayergroupresultsQuery groupByGroupkey() Group by the GroupKey column
 * @method     ChildPlayergroupresultsQuery groupByScore() Group by the Score column
 *
 * @method     ChildPlayergroupresultsQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildPlayergroupresultsQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildPlayergroupresultsQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildPlayergroupresultsQuery leftJoinPlayerResult($relationAlias = null) Adds a LEFT JOIN clause to the query using the PlayerResult relation
 * @method     ChildPlayergroupresultsQuery rightJoinPlayerResult($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PlayerResult relation
 * @method     ChildPlayergroupresultsQuery innerJoinPlayerResult($relationAlias = null) Adds a INNER JOIN clause to the query using the PlayerResult relation
 *
 * @method     ChildPlayergroupresultsQuery leftJoinGroupResult($relationAlias = null) Adds a LEFT JOIN clause to the query using the GroupResult relation
 * @method     ChildPlayergroupresultsQuery rightJoinGroupResult($relationAlias = null) Adds a RIGHT JOIN clause to the query using the GroupResult relation
 * @method     ChildPlayergroupresultsQuery innerJoinGroupResult($relationAlias = null) Adds a INNER JOIN clause to the query using the GroupResult relation
 *
 * @method     \PlayersQuery|\GroupsQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildPlayergroupresults findOne(ConnectionInterface $con = null) Return the first ChildPlayergroupresults matching the query
 * @method     ChildPlayergroupresults findOneOrCreate(ConnectionInterface $con = null) Return the first ChildPlayergroupresults matching the query, or a new ChildPlayergroupresults object populated from the query conditions when no match is found
 *
 * @method     ChildPlayergroupresults findOneByPlayerkey(int $PlayerKey) Return the first ChildPlayergroupresults filtered by the PlayerKey column
 * @method     ChildPlayergroupresults findOneByGroupkey(int $GroupKey) Return the first ChildPlayergroupresults filtered by the GroupKey column
 * @method     ChildPlayergroupresults findOneByScore(int $Score) Return the first ChildPlayergroupresults filtered by the Score column *

 * @method     ChildPlayergroupresults requirePk($key, ConnectionInterface $con = null) Return the ChildPlayergroupresults by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayergroupresults requireOne(ConnectionInterface $con = null) Return the first ChildPlayergroupresults matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPlayergroupresults requireOneByPlayerkey(int $PlayerKey) Return the first ChildPlayergroupresults filtered by the PlayerKey column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayergroupresults requireOneByGroupkey(int $GroupKey) Return the first ChildPlayergroupresults filtered by the GroupKey column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayergroupresults requireOneByScore(int $Score) Return the first ChildPlayergroupresults filtered by the Score column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPlayergroupresults[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildPlayergroupresults objects based on current ModelCriteria
 * @method     ChildPlayergroupresults[]|ObjectCollection findByPlayerkey(int $PlayerKey) Return ChildPlayergroupresults objects filtered by the PlayerKey column
 * @method     ChildPlayergroupresults[]|ObjectCollection findByGroupkey(int $GroupKey) Return ChildPlayergroupresults objects filtered by the GroupKey column
 * @method     ChildPlayergroupresults[]|ObjectCollection findByScore(int $Score) Return ChildPlayergroupresults objects filtered by the Score column
 * @method     ChildPlayergroupresults[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class PlayergroupresultsQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Base\PlayergroupresultsQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Playergroupresults', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildPlayergroupresultsQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildPlayergroupresultsQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildPlayergroupresultsQuery) {
            return $criteria;
        }
        $query = new ChildPlayergroupresultsQuery();
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
     * @param array[$PlayerKey, $GroupKey] $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildPlayergroupresults|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = PlayergroupresultsTableMap::getInstanceFromPool(serialize(array((string) $key[0], (string) $key[1]))))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(PlayergroupresultsTableMap::DATABASE_NAME);
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
     * @return ChildPlayergroupresults A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT PlayerKey, GroupKey, Score FROM playergroupresults WHERE PlayerKey = :p0 AND GroupKey = :p1';
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
            /** @var ChildPlayergroupresults $obj */
            $obj = new ChildPlayergroupresults();
            $obj->hydrate($row);
            PlayergroupresultsTableMap::addInstanceToPool($obj, serialize(array((string) $key[0], (string) $key[1])));
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
     * @return ChildPlayergroupresults|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildPlayergroupresultsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(PlayergroupresultsTableMap::COL_PLAYERKEY, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(PlayergroupresultsTableMap::COL_GROUPKEY, $key[1], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildPlayergroupresultsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(PlayergroupresultsTableMap::COL_PLAYERKEY, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(PlayergroupresultsTableMap::COL_GROUPKEY, $key[1], Criteria::EQUAL);
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
     * @return $this|ChildPlayergroupresultsQuery The current query, for fluid interface
     */
    public function filterByPlayerkey($playerkey = null, $comparison = null)
    {
        if (is_array($playerkey)) {
            $useMinMax = false;
            if (isset($playerkey['min'])) {
                $this->addUsingAlias(PlayergroupresultsTableMap::COL_PLAYERKEY, $playerkey['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($playerkey['max'])) {
                $this->addUsingAlias(PlayergroupresultsTableMap::COL_PLAYERKEY, $playerkey['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayergroupresultsTableMap::COL_PLAYERKEY, $playerkey, $comparison);
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
     * @see       filterByGroupResult()
     *
     * @param     mixed $groupkey The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlayergroupresultsQuery The current query, for fluid interface
     */
    public function filterByGroupkey($groupkey = null, $comparison = null)
    {
        if (is_array($groupkey)) {
            $useMinMax = false;
            if (isset($groupkey['min'])) {
                $this->addUsingAlias(PlayergroupresultsTableMap::COL_GROUPKEY, $groupkey['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($groupkey['max'])) {
                $this->addUsingAlias(PlayergroupresultsTableMap::COL_GROUPKEY, $groupkey['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayergroupresultsTableMap::COL_GROUPKEY, $groupkey, $comparison);
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
     * @return $this|ChildPlayergroupresultsQuery The current query, for fluid interface
     */
    public function filterByScore($score = null, $comparison = null)
    {
        if (is_array($score)) {
            $useMinMax = false;
            if (isset($score['min'])) {
                $this->addUsingAlias(PlayergroupresultsTableMap::COL_SCORE, $score['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($score['max'])) {
                $this->addUsingAlias(PlayergroupresultsTableMap::COL_SCORE, $score['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayergroupresultsTableMap::COL_SCORE, $score, $comparison);
    }

    /**
     * Filter the query by a related \Players object
     *
     * @param \Players|ObjectCollection $players The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildPlayergroupresultsQuery The current query, for fluid interface
     */
    public function filterByPlayerResult($players, $comparison = null)
    {
        if ($players instanceof \Players) {
            return $this
                ->addUsingAlias(PlayergroupresultsTableMap::COL_PLAYERKEY, $players->getPlayerPK(), $comparison);
        } elseif ($players instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PlayergroupresultsTableMap::COL_PLAYERKEY, $players->toKeyValue('PrimaryKey', 'PlayerPK'), $comparison);
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
     * @return $this|ChildPlayergroupresultsQuery The current query, for fluid interface
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
     * Filter the query by a related \Groups object
     *
     * @param \Groups|ObjectCollection $groups The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildPlayergroupresultsQuery The current query, for fluid interface
     */
    public function filterByGroupResult($groups, $comparison = null)
    {
        if ($groups instanceof \Groups) {
            return $this
                ->addUsingAlias(PlayergroupresultsTableMap::COL_GROUPKEY, $groups->getGroupPK(), $comparison);
        } elseif ($groups instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PlayergroupresultsTableMap::COL_GROUPKEY, $groups->toKeyValue('PrimaryKey', 'GroupPK'), $comparison);
        } else {
            throw new PropelException('filterByGroupResult() only accepts arguments of type \Groups or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the GroupResult relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPlayergroupresultsQuery The current query, for fluid interface
     */
    public function joinGroupResult($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('GroupResult');

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
            $this->addJoinObject($join, 'GroupResult');
        }

        return $this;
    }

    /**
     * Use the GroupResult relation Groups object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \GroupsQuery A secondary query class using the current class as primary query
     */
    public function useGroupResultQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinGroupResult($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'GroupResult', '\GroupsQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildPlayergroupresults $playergroupresults Object to remove from the list of results
     *
     * @return $this|ChildPlayergroupresultsQuery The current query, for fluid interface
     */
    public function prune($playergroupresults = null)
    {
        if ($playergroupresults) {
            $this->addCond('pruneCond0', $this->getAliasedColName(PlayergroupresultsTableMap::COL_PLAYERKEY), $playergroupresults->getPlayerkey(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(PlayergroupresultsTableMap::COL_GROUPKEY), $playergroupresults->getGroupkey(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

    /**
     * Deletes all rows from the playergroupresults table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PlayergroupresultsTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            PlayergroupresultsTableMap::clearInstancePool();
            PlayergroupresultsTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(PlayergroupresultsTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(PlayergroupresultsTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            PlayergroupresultsTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            PlayergroupresultsTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // PlayergroupresultsQuery
