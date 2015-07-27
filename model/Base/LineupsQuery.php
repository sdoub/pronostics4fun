<?php

namespace Base;

use \Lineups as ChildLineups;
use \LineupsQuery as ChildLineupsQuery;
use \Exception;
use \PDO;
use Map\LineupsTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'lineups' table.
 *
 *
 *
 * @method     ChildLineupsQuery orderByMatchkey($order = Criteria::ASC) Order by the MatchKey column
 * @method     ChildLineupsQuery orderByTeamkey($order = Criteria::ASC) Order by the TeamKey column
 * @method     ChildLineupsQuery orderByTeamplayerkey($order = Criteria::ASC) Order by the TeamPlayerKey column
 * @method     ChildLineupsQuery orderByIssubstitute($order = Criteria::ASC) Order by the IsSubstitute column
 * @method     ChildLineupsQuery orderByTimein($order = Criteria::ASC) Order by the TimeIn column
 * @method     ChildLineupsQuery orderByTeamplayerreplacedkey($order = Criteria::ASC) Order by the TeamPlayerReplacedKey column
 *
 * @method     ChildLineupsQuery groupByMatchkey() Group by the MatchKey column
 * @method     ChildLineupsQuery groupByTeamkey() Group by the TeamKey column
 * @method     ChildLineupsQuery groupByTeamplayerkey() Group by the TeamPlayerKey column
 * @method     ChildLineupsQuery groupByIssubstitute() Group by the IsSubstitute column
 * @method     ChildLineupsQuery groupByTimein() Group by the TimeIn column
 * @method     ChildLineupsQuery groupByTeamplayerreplacedkey() Group by the TeamPlayerReplacedKey column
 *
 * @method     ChildLineupsQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildLineupsQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildLineupsQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildLineups findOne(ConnectionInterface $con = null) Return the first ChildLineups matching the query
 * @method     ChildLineups findOneOrCreate(ConnectionInterface $con = null) Return the first ChildLineups matching the query, or a new ChildLineups object populated from the query conditions when no match is found
 *
 * @method     ChildLineups findOneByMatchkey(int $MatchKey) Return the first ChildLineups filtered by the MatchKey column
 * @method     ChildLineups findOneByTeamkey(int $TeamKey) Return the first ChildLineups filtered by the TeamKey column
 * @method     ChildLineups findOneByTeamplayerkey(int $TeamPlayerKey) Return the first ChildLineups filtered by the TeamPlayerKey column
 * @method     ChildLineups findOneByIssubstitute(boolean $IsSubstitute) Return the first ChildLineups filtered by the IsSubstitute column
 * @method     ChildLineups findOneByTimein(int $TimeIn) Return the first ChildLineups filtered by the TimeIn column
 * @method     ChildLineups findOneByTeamplayerreplacedkey(int $TeamPlayerReplacedKey) Return the first ChildLineups filtered by the TeamPlayerReplacedKey column *

 * @method     ChildLineups requirePk($key, ConnectionInterface $con = null) Return the ChildLineups by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLineups requireOne(ConnectionInterface $con = null) Return the first ChildLineups matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildLineups requireOneByMatchkey(int $MatchKey) Return the first ChildLineups filtered by the MatchKey column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLineups requireOneByTeamkey(int $TeamKey) Return the first ChildLineups filtered by the TeamKey column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLineups requireOneByTeamplayerkey(int $TeamPlayerKey) Return the first ChildLineups filtered by the TeamPlayerKey column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLineups requireOneByIssubstitute(boolean $IsSubstitute) Return the first ChildLineups filtered by the IsSubstitute column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLineups requireOneByTimein(int $TimeIn) Return the first ChildLineups filtered by the TimeIn column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLineups requireOneByTeamplayerreplacedkey(int $TeamPlayerReplacedKey) Return the first ChildLineups filtered by the TeamPlayerReplacedKey column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildLineups[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildLineups objects based on current ModelCriteria
 * @method     ChildLineups[]|ObjectCollection findByMatchkey(int $MatchKey) Return ChildLineups objects filtered by the MatchKey column
 * @method     ChildLineups[]|ObjectCollection findByTeamkey(int $TeamKey) Return ChildLineups objects filtered by the TeamKey column
 * @method     ChildLineups[]|ObjectCollection findByTeamplayerkey(int $TeamPlayerKey) Return ChildLineups objects filtered by the TeamPlayerKey column
 * @method     ChildLineups[]|ObjectCollection findByIssubstitute(boolean $IsSubstitute) Return ChildLineups objects filtered by the IsSubstitute column
 * @method     ChildLineups[]|ObjectCollection findByTimein(int $TimeIn) Return ChildLineups objects filtered by the TimeIn column
 * @method     ChildLineups[]|ObjectCollection findByTeamplayerreplacedkey(int $TeamPlayerReplacedKey) Return ChildLineups objects filtered by the TeamPlayerReplacedKey column
 * @method     ChildLineups[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class LineupsQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Base\LineupsQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Lineups', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildLineupsQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildLineupsQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildLineupsQuery) {
            return $criteria;
        }
        $query = new ChildLineupsQuery();
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
     * @param array[$MatchKey, $TeamKey, $TeamPlayerKey] $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildLineups|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = LineupsTableMap::getInstanceFromPool(serialize(array((string) $key[0], (string) $key[1], (string) $key[2]))))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(LineupsTableMap::DATABASE_NAME);
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
     * @return ChildLineups A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT MatchKey, TeamKey, TeamPlayerKey, IsSubstitute, TimeIn, TeamPlayerReplacedKey FROM lineups WHERE MatchKey = :p0 AND TeamKey = :p1 AND TeamPlayerKey = :p2';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key[0], PDO::PARAM_INT);
            $stmt->bindValue(':p1', $key[1], PDO::PARAM_INT);
            $stmt->bindValue(':p2', $key[2], PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildLineups $obj */
            $obj = new ChildLineups();
            $obj->hydrate($row);
            LineupsTableMap::addInstanceToPool($obj, serialize(array((string) $key[0], (string) $key[1], (string) $key[2])));
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
     * @return ChildLineups|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildLineupsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(LineupsTableMap::COL_MATCHKEY, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(LineupsTableMap::COL_TEAMKEY, $key[1], Criteria::EQUAL);
        $this->addUsingAlias(LineupsTableMap::COL_TEAMPLAYERKEY, $key[2], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildLineupsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(LineupsTableMap::COL_MATCHKEY, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(LineupsTableMap::COL_TEAMKEY, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $cton2 = $this->getNewCriterion(LineupsTableMap::COL_TEAMPLAYERKEY, $key[2], Criteria::EQUAL);
            $cton0->addAnd($cton2);
            $this->addOr($cton0);
        }

        return $this;
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
     * @return $this|ChildLineupsQuery The current query, for fluid interface
     */
    public function filterByMatchkey($matchkey = null, $comparison = null)
    {
        if (is_array($matchkey)) {
            $useMinMax = false;
            if (isset($matchkey['min'])) {
                $this->addUsingAlias(LineupsTableMap::COL_MATCHKEY, $matchkey['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($matchkey['max'])) {
                $this->addUsingAlias(LineupsTableMap::COL_MATCHKEY, $matchkey['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LineupsTableMap::COL_MATCHKEY, $matchkey, $comparison);
    }

    /**
     * Filter the query on the TeamKey column
     *
     * Example usage:
     * <code>
     * $query->filterByTeamkey(1234); // WHERE TeamKey = 1234
     * $query->filterByTeamkey(array(12, 34)); // WHERE TeamKey IN (12, 34)
     * $query->filterByTeamkey(array('min' => 12)); // WHERE TeamKey > 12
     * </code>
     *
     * @param     mixed $teamkey The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLineupsQuery The current query, for fluid interface
     */
    public function filterByTeamkey($teamkey = null, $comparison = null)
    {
        if (is_array($teamkey)) {
            $useMinMax = false;
            if (isset($teamkey['min'])) {
                $this->addUsingAlias(LineupsTableMap::COL_TEAMKEY, $teamkey['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($teamkey['max'])) {
                $this->addUsingAlias(LineupsTableMap::COL_TEAMKEY, $teamkey['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LineupsTableMap::COL_TEAMKEY, $teamkey, $comparison);
    }

    /**
     * Filter the query on the TeamPlayerKey column
     *
     * Example usage:
     * <code>
     * $query->filterByTeamplayerkey(1234); // WHERE TeamPlayerKey = 1234
     * $query->filterByTeamplayerkey(array(12, 34)); // WHERE TeamPlayerKey IN (12, 34)
     * $query->filterByTeamplayerkey(array('min' => 12)); // WHERE TeamPlayerKey > 12
     * </code>
     *
     * @param     mixed $teamplayerkey The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLineupsQuery The current query, for fluid interface
     */
    public function filterByTeamplayerkey($teamplayerkey = null, $comparison = null)
    {
        if (is_array($teamplayerkey)) {
            $useMinMax = false;
            if (isset($teamplayerkey['min'])) {
                $this->addUsingAlias(LineupsTableMap::COL_TEAMPLAYERKEY, $teamplayerkey['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($teamplayerkey['max'])) {
                $this->addUsingAlias(LineupsTableMap::COL_TEAMPLAYERKEY, $teamplayerkey['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LineupsTableMap::COL_TEAMPLAYERKEY, $teamplayerkey, $comparison);
    }

    /**
     * Filter the query on the IsSubstitute column
     *
     * Example usage:
     * <code>
     * $query->filterByIssubstitute(true); // WHERE IsSubstitute = true
     * $query->filterByIssubstitute('yes'); // WHERE IsSubstitute = true
     * </code>
     *
     * @param     boolean|string $issubstitute The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLineupsQuery The current query, for fluid interface
     */
    public function filterByIssubstitute($issubstitute = null, $comparison = null)
    {
        if (is_string($issubstitute)) {
            $issubstitute = in_array(strtolower($issubstitute), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(LineupsTableMap::COL_ISSUBSTITUTE, $issubstitute, $comparison);
    }

    /**
     * Filter the query on the TimeIn column
     *
     * Example usage:
     * <code>
     * $query->filterByTimein(1234); // WHERE TimeIn = 1234
     * $query->filterByTimein(array(12, 34)); // WHERE TimeIn IN (12, 34)
     * $query->filterByTimein(array('min' => 12)); // WHERE TimeIn > 12
     * </code>
     *
     * @param     mixed $timein The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLineupsQuery The current query, for fluid interface
     */
    public function filterByTimein($timein = null, $comparison = null)
    {
        if (is_array($timein)) {
            $useMinMax = false;
            if (isset($timein['min'])) {
                $this->addUsingAlias(LineupsTableMap::COL_TIMEIN, $timein['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($timein['max'])) {
                $this->addUsingAlias(LineupsTableMap::COL_TIMEIN, $timein['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LineupsTableMap::COL_TIMEIN, $timein, $comparison);
    }

    /**
     * Filter the query on the TeamPlayerReplacedKey column
     *
     * Example usage:
     * <code>
     * $query->filterByTeamplayerreplacedkey(1234); // WHERE TeamPlayerReplacedKey = 1234
     * $query->filterByTeamplayerreplacedkey(array(12, 34)); // WHERE TeamPlayerReplacedKey IN (12, 34)
     * $query->filterByTeamplayerreplacedkey(array('min' => 12)); // WHERE TeamPlayerReplacedKey > 12
     * </code>
     *
     * @param     mixed $teamplayerreplacedkey The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLineupsQuery The current query, for fluid interface
     */
    public function filterByTeamplayerreplacedkey($teamplayerreplacedkey = null, $comparison = null)
    {
        if (is_array($teamplayerreplacedkey)) {
            $useMinMax = false;
            if (isset($teamplayerreplacedkey['min'])) {
                $this->addUsingAlias(LineupsTableMap::COL_TEAMPLAYERREPLACEDKEY, $teamplayerreplacedkey['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($teamplayerreplacedkey['max'])) {
                $this->addUsingAlias(LineupsTableMap::COL_TEAMPLAYERREPLACEDKEY, $teamplayerreplacedkey['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LineupsTableMap::COL_TEAMPLAYERREPLACEDKEY, $teamplayerreplacedkey, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   ChildLineups $lineups Object to remove from the list of results
     *
     * @return $this|ChildLineupsQuery The current query, for fluid interface
     */
    public function prune($lineups = null)
    {
        if ($lineups) {
            $this->addCond('pruneCond0', $this->getAliasedColName(LineupsTableMap::COL_MATCHKEY), $lineups->getMatchkey(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(LineupsTableMap::COL_TEAMKEY), $lineups->getTeamkey(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond2', $this->getAliasedColName(LineupsTableMap::COL_TEAMPLAYERKEY), $lineups->getTeamplayerkey(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1', 'pruneCond2'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

    /**
     * Deletes all rows from the lineups table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(LineupsTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            LineupsTableMap::clearInstancePool();
            LineupsTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(LineupsTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(LineupsTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            LineupsTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            LineupsTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // LineupsQuery
