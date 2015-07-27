<?php

namespace Base;

use \Forecasts as ChildForecasts;
use \ForecastsQuery as ChildForecastsQuery;
use \Exception;
use \PDO;
use Map\ForecastsTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'forecasts' table.
 *
 *
 *
 * @method     ChildForecastsQuery orderByPrimarykey($order = Criteria::ASC) Order by the PrimaryKey column
 * @method     ChildForecastsQuery orderByMatchkey($order = Criteria::ASC) Order by the MatchKey column
 * @method     ChildForecastsQuery orderByPlayerkey($order = Criteria::ASC) Order by the PlayerKey column
 * @method     ChildForecastsQuery orderByTeamhomescore($order = Criteria::ASC) Order by the TeamHomeScore column
 * @method     ChildForecastsQuery orderByTeamawayscore($order = Criteria::ASC) Order by the TeamAwayScore column
 * @method     ChildForecastsQuery orderByForecastdate($order = Criteria::ASC) Order by the ForecastDate column
 *
 * @method     ChildForecastsQuery groupByPrimarykey() Group by the PrimaryKey column
 * @method     ChildForecastsQuery groupByMatchkey() Group by the MatchKey column
 * @method     ChildForecastsQuery groupByPlayerkey() Group by the PlayerKey column
 * @method     ChildForecastsQuery groupByTeamhomescore() Group by the TeamHomeScore column
 * @method     ChildForecastsQuery groupByTeamawayscore() Group by the TeamAwayScore column
 * @method     ChildForecastsQuery groupByForecastdate() Group by the ForecastDate column
 *
 * @method     ChildForecastsQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildForecastsQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildForecastsQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildForecasts findOne(ConnectionInterface $con = null) Return the first ChildForecasts matching the query
 * @method     ChildForecasts findOneOrCreate(ConnectionInterface $con = null) Return the first ChildForecasts matching the query, or a new ChildForecasts object populated from the query conditions when no match is found
 *
 * @method     ChildForecasts findOneByPrimarykey(int $PrimaryKey) Return the first ChildForecasts filtered by the PrimaryKey column
 * @method     ChildForecasts findOneByMatchkey(int $MatchKey) Return the first ChildForecasts filtered by the MatchKey column
 * @method     ChildForecasts findOneByPlayerkey(int $PlayerKey) Return the first ChildForecasts filtered by the PlayerKey column
 * @method     ChildForecasts findOneByTeamhomescore(int $TeamHomeScore) Return the first ChildForecasts filtered by the TeamHomeScore column
 * @method     ChildForecasts findOneByTeamawayscore(int $TeamAwayScore) Return the first ChildForecasts filtered by the TeamAwayScore column
 * @method     ChildForecasts findOneByForecastdate(string $ForecastDate) Return the first ChildForecasts filtered by the ForecastDate column *

 * @method     ChildForecasts requirePk($key, ConnectionInterface $con = null) Return the ChildForecasts by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildForecasts requireOne(ConnectionInterface $con = null) Return the first ChildForecasts matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildForecasts requireOneByPrimarykey(int $PrimaryKey) Return the first ChildForecasts filtered by the PrimaryKey column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildForecasts requireOneByMatchkey(int $MatchKey) Return the first ChildForecasts filtered by the MatchKey column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildForecasts requireOneByPlayerkey(int $PlayerKey) Return the first ChildForecasts filtered by the PlayerKey column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildForecasts requireOneByTeamhomescore(int $TeamHomeScore) Return the first ChildForecasts filtered by the TeamHomeScore column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildForecasts requireOneByTeamawayscore(int $TeamAwayScore) Return the first ChildForecasts filtered by the TeamAwayScore column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildForecasts requireOneByForecastdate(string $ForecastDate) Return the first ChildForecasts filtered by the ForecastDate column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildForecasts[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildForecasts objects based on current ModelCriteria
 * @method     ChildForecasts[]|ObjectCollection findByPrimarykey(int $PrimaryKey) Return ChildForecasts objects filtered by the PrimaryKey column
 * @method     ChildForecasts[]|ObjectCollection findByMatchkey(int $MatchKey) Return ChildForecasts objects filtered by the MatchKey column
 * @method     ChildForecasts[]|ObjectCollection findByPlayerkey(int $PlayerKey) Return ChildForecasts objects filtered by the PlayerKey column
 * @method     ChildForecasts[]|ObjectCollection findByTeamhomescore(int $TeamHomeScore) Return ChildForecasts objects filtered by the TeamHomeScore column
 * @method     ChildForecasts[]|ObjectCollection findByTeamawayscore(int $TeamAwayScore) Return ChildForecasts objects filtered by the TeamAwayScore column
 * @method     ChildForecasts[]|ObjectCollection findByForecastdate(string $ForecastDate) Return ChildForecasts objects filtered by the ForecastDate column
 * @method     ChildForecasts[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class ForecastsQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Base\ForecastsQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Forecasts', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildForecastsQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildForecastsQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildForecastsQuery) {
            return $criteria;
        }
        $query = new ChildForecastsQuery();
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
     * @return ChildForecasts|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = ForecastsTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(ForecastsTableMap::DATABASE_NAME);
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
     * @return ChildForecasts A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT PrimaryKey, MatchKey, PlayerKey, TeamHomeScore, TeamAwayScore, ForecastDate FROM forecasts WHERE PrimaryKey = :p0';
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
            /** @var ChildForecasts $obj */
            $obj = new ChildForecasts();
            $obj->hydrate($row);
            ForecastsTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildForecasts|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildForecastsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(ForecastsTableMap::COL_PRIMARYKEY, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildForecastsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(ForecastsTableMap::COL_PRIMARYKEY, $keys, Criteria::IN);
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
     * @return $this|ChildForecastsQuery The current query, for fluid interface
     */
    public function filterByPrimarykey($primarykey = null, $comparison = null)
    {
        if (is_array($primarykey)) {
            $useMinMax = false;
            if (isset($primarykey['min'])) {
                $this->addUsingAlias(ForecastsTableMap::COL_PRIMARYKEY, $primarykey['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($primarykey['max'])) {
                $this->addUsingAlias(ForecastsTableMap::COL_PRIMARYKEY, $primarykey['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ForecastsTableMap::COL_PRIMARYKEY, $primarykey, $comparison);
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
     * @param     mixed $matchkey The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildForecastsQuery The current query, for fluid interface
     */
    public function filterByMatchkey($matchkey = null, $comparison = null)
    {
        if (is_array($matchkey)) {
            $useMinMax = false;
            if (isset($matchkey['min'])) {
                $this->addUsingAlias(ForecastsTableMap::COL_MATCHKEY, $matchkey['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($matchkey['max'])) {
                $this->addUsingAlias(ForecastsTableMap::COL_MATCHKEY, $matchkey['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ForecastsTableMap::COL_MATCHKEY, $matchkey, $comparison);
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
     * @return $this|ChildForecastsQuery The current query, for fluid interface
     */
    public function filterByPlayerkey($playerkey = null, $comparison = null)
    {
        if (is_array($playerkey)) {
            $useMinMax = false;
            if (isset($playerkey['min'])) {
                $this->addUsingAlias(ForecastsTableMap::COL_PLAYERKEY, $playerkey['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($playerkey['max'])) {
                $this->addUsingAlias(ForecastsTableMap::COL_PLAYERKEY, $playerkey['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ForecastsTableMap::COL_PLAYERKEY, $playerkey, $comparison);
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
     * @return $this|ChildForecastsQuery The current query, for fluid interface
     */
    public function filterByTeamhomescore($teamhomescore = null, $comparison = null)
    {
        if (is_array($teamhomescore)) {
            $useMinMax = false;
            if (isset($teamhomescore['min'])) {
                $this->addUsingAlias(ForecastsTableMap::COL_TEAMHOMESCORE, $teamhomescore['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($teamhomescore['max'])) {
                $this->addUsingAlias(ForecastsTableMap::COL_TEAMHOMESCORE, $teamhomescore['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ForecastsTableMap::COL_TEAMHOMESCORE, $teamhomescore, $comparison);
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
     * @return $this|ChildForecastsQuery The current query, for fluid interface
     */
    public function filterByTeamawayscore($teamawayscore = null, $comparison = null)
    {
        if (is_array($teamawayscore)) {
            $useMinMax = false;
            if (isset($teamawayscore['min'])) {
                $this->addUsingAlias(ForecastsTableMap::COL_TEAMAWAYSCORE, $teamawayscore['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($teamawayscore['max'])) {
                $this->addUsingAlias(ForecastsTableMap::COL_TEAMAWAYSCORE, $teamawayscore['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ForecastsTableMap::COL_TEAMAWAYSCORE, $teamawayscore, $comparison);
    }

    /**
     * Filter the query on the ForecastDate column
     *
     * Example usage:
     * <code>
     * $query->filterByForecastdate('2011-03-14'); // WHERE ForecastDate = '2011-03-14'
     * $query->filterByForecastdate('now'); // WHERE ForecastDate = '2011-03-14'
     * $query->filterByForecastdate(array('max' => 'yesterday')); // WHERE ForecastDate > '2011-03-13'
     * </code>
     *
     * @param     mixed $forecastdate The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildForecastsQuery The current query, for fluid interface
     */
    public function filterByForecastdate($forecastdate = null, $comparison = null)
    {
        if (is_array($forecastdate)) {
            $useMinMax = false;
            if (isset($forecastdate['min'])) {
                $this->addUsingAlias(ForecastsTableMap::COL_FORECASTDATE, $forecastdate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($forecastdate['max'])) {
                $this->addUsingAlias(ForecastsTableMap::COL_FORECASTDATE, $forecastdate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ForecastsTableMap::COL_FORECASTDATE, $forecastdate, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   ChildForecasts $forecasts Object to remove from the list of results
     *
     * @return $this|ChildForecastsQuery The current query, for fluid interface
     */
    public function prune($forecasts = null)
    {
        if ($forecasts) {
            $this->addUsingAlias(ForecastsTableMap::COL_PRIMARYKEY, $forecasts->getPrimarykey(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the forecasts table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ForecastsTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            ForecastsTableMap::clearInstancePool();
            ForecastsTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(ForecastsTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(ForecastsTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            ForecastsTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            ForecastsTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // ForecastsQuery
