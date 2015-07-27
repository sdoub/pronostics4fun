<?php

namespace Base;

use \Playermatchstates as ChildPlayermatchstates;
use \PlayermatchstatesQuery as ChildPlayermatchstatesQuery;
use \Exception;
use \PDO;
use Map\PlayermatchstatesTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'playermatchstates' table.
 *
 *
 *
 * @method     ChildPlayermatchstatesQuery orderByPlayerkey($order = Criteria::ASC) Order by the PlayerKey column
 * @method     ChildPlayermatchstatesQuery orderByMatchstatekey($order = Criteria::ASC) Order by the MatchStateKey column
 * @method     ChildPlayermatchstatesQuery orderByScore($order = Criteria::ASC) Order by the Score column
 *
 * @method     ChildPlayermatchstatesQuery groupByPlayerkey() Group by the PlayerKey column
 * @method     ChildPlayermatchstatesQuery groupByMatchstatekey() Group by the MatchStateKey column
 * @method     ChildPlayermatchstatesQuery groupByScore() Group by the Score column
 *
 * @method     ChildPlayermatchstatesQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildPlayermatchstatesQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildPlayermatchstatesQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildPlayermatchstates findOne(ConnectionInterface $con = null) Return the first ChildPlayermatchstates matching the query
 * @method     ChildPlayermatchstates findOneOrCreate(ConnectionInterface $con = null) Return the first ChildPlayermatchstates matching the query, or a new ChildPlayermatchstates object populated from the query conditions when no match is found
 *
 * @method     ChildPlayermatchstates findOneByPlayerkey(int $PlayerKey) Return the first ChildPlayermatchstates filtered by the PlayerKey column
 * @method     ChildPlayermatchstates findOneByMatchstatekey(int $MatchStateKey) Return the first ChildPlayermatchstates filtered by the MatchStateKey column
 * @method     ChildPlayermatchstates findOneByScore(int $Score) Return the first ChildPlayermatchstates filtered by the Score column *

 * @method     ChildPlayermatchstates requirePk($key, ConnectionInterface $con = null) Return the ChildPlayermatchstates by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayermatchstates requireOne(ConnectionInterface $con = null) Return the first ChildPlayermatchstates matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPlayermatchstates requireOneByPlayerkey(int $PlayerKey) Return the first ChildPlayermatchstates filtered by the PlayerKey column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayermatchstates requireOneByMatchstatekey(int $MatchStateKey) Return the first ChildPlayermatchstates filtered by the MatchStateKey column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayermatchstates requireOneByScore(int $Score) Return the first ChildPlayermatchstates filtered by the Score column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPlayermatchstates[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildPlayermatchstates objects based on current ModelCriteria
 * @method     ChildPlayermatchstates[]|ObjectCollection findByPlayerkey(int $PlayerKey) Return ChildPlayermatchstates objects filtered by the PlayerKey column
 * @method     ChildPlayermatchstates[]|ObjectCollection findByMatchstatekey(int $MatchStateKey) Return ChildPlayermatchstates objects filtered by the MatchStateKey column
 * @method     ChildPlayermatchstates[]|ObjectCollection findByScore(int $Score) Return ChildPlayermatchstates objects filtered by the Score column
 * @method     ChildPlayermatchstates[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class PlayermatchstatesQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Base\PlayermatchstatesQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Playermatchstates', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildPlayermatchstatesQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildPlayermatchstatesQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildPlayermatchstatesQuery) {
            return $criteria;
        }
        $query = new ChildPlayermatchstatesQuery();
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
     * @param array[$PlayerKey, $MatchStateKey] $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildPlayermatchstates|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = PlayermatchstatesTableMap::getInstanceFromPool(serialize(array((string) $key[0], (string) $key[1]))))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(PlayermatchstatesTableMap::DATABASE_NAME);
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
     * @return ChildPlayermatchstates A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT PlayerKey, MatchStateKey, Score FROM playermatchstates WHERE PlayerKey = :p0 AND MatchStateKey = :p1';
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
            /** @var ChildPlayermatchstates $obj */
            $obj = new ChildPlayermatchstates();
            $obj->hydrate($row);
            PlayermatchstatesTableMap::addInstanceToPool($obj, serialize(array((string) $key[0], (string) $key[1])));
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
     * @return ChildPlayermatchstates|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildPlayermatchstatesQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(PlayermatchstatesTableMap::COL_PLAYERKEY, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(PlayermatchstatesTableMap::COL_MATCHSTATEKEY, $key[1], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildPlayermatchstatesQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(PlayermatchstatesTableMap::COL_PLAYERKEY, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(PlayermatchstatesTableMap::COL_MATCHSTATEKEY, $key[1], Criteria::EQUAL);
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
     * @param     mixed $playerkey The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlayermatchstatesQuery The current query, for fluid interface
     */
    public function filterByPlayerkey($playerkey = null, $comparison = null)
    {
        if (is_array($playerkey)) {
            $useMinMax = false;
            if (isset($playerkey['min'])) {
                $this->addUsingAlias(PlayermatchstatesTableMap::COL_PLAYERKEY, $playerkey['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($playerkey['max'])) {
                $this->addUsingAlias(PlayermatchstatesTableMap::COL_PLAYERKEY, $playerkey['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayermatchstatesTableMap::COL_PLAYERKEY, $playerkey, $comparison);
    }

    /**
     * Filter the query on the MatchStateKey column
     *
     * Example usage:
     * <code>
     * $query->filterByMatchstatekey(1234); // WHERE MatchStateKey = 1234
     * $query->filterByMatchstatekey(array(12, 34)); // WHERE MatchStateKey IN (12, 34)
     * $query->filterByMatchstatekey(array('min' => 12)); // WHERE MatchStateKey > 12
     * </code>
     *
     * @param     mixed $matchstatekey The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlayermatchstatesQuery The current query, for fluid interface
     */
    public function filterByMatchstatekey($matchstatekey = null, $comparison = null)
    {
        if (is_array($matchstatekey)) {
            $useMinMax = false;
            if (isset($matchstatekey['min'])) {
                $this->addUsingAlias(PlayermatchstatesTableMap::COL_MATCHSTATEKEY, $matchstatekey['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($matchstatekey['max'])) {
                $this->addUsingAlias(PlayermatchstatesTableMap::COL_MATCHSTATEKEY, $matchstatekey['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayermatchstatesTableMap::COL_MATCHSTATEKEY, $matchstatekey, $comparison);
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
     * @return $this|ChildPlayermatchstatesQuery The current query, for fluid interface
     */
    public function filterByScore($score = null, $comparison = null)
    {
        if (is_array($score)) {
            $useMinMax = false;
            if (isset($score['min'])) {
                $this->addUsingAlias(PlayermatchstatesTableMap::COL_SCORE, $score['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($score['max'])) {
                $this->addUsingAlias(PlayermatchstatesTableMap::COL_SCORE, $score['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayermatchstatesTableMap::COL_SCORE, $score, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   ChildPlayermatchstates $playermatchstates Object to remove from the list of results
     *
     * @return $this|ChildPlayermatchstatesQuery The current query, for fluid interface
     */
    public function prune($playermatchstates = null)
    {
        if ($playermatchstates) {
            $this->addCond('pruneCond0', $this->getAliasedColName(PlayermatchstatesTableMap::COL_PLAYERKEY), $playermatchstates->getPlayerkey(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(PlayermatchstatesTableMap::COL_MATCHSTATEKEY), $playermatchstates->getMatchstatekey(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

    /**
     * Deletes all rows from the playermatchstates table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PlayermatchstatesTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            PlayermatchstatesTableMap::clearInstancePool();
            PlayermatchstatesTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(PlayermatchstatesTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(PlayermatchstatesTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            PlayermatchstatesTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            PlayermatchstatesTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // PlayermatchstatesQuery
