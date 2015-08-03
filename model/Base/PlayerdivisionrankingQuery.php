<?php

namespace Base;

use \Playerdivisionranking as ChildPlayerdivisionranking;
use \PlayerdivisionrankingQuery as ChildPlayerdivisionrankingQuery;
use \Exception;
use \PDO;
use Map\PlayerdivisionrankingTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'playerdivisionranking' table.
 *
 *
 *
 * @method     ChildPlayerdivisionrankingQuery orderByPlayerkey($order = Criteria::ASC) Order by the PlayerKey column
 * @method     ChildPlayerdivisionrankingQuery orderBySeasonkey($order = Criteria::ASC) Order by the SeasonKey column
 * @method     ChildPlayerdivisionrankingQuery orderByDivisionkey($order = Criteria::ASC) Order by the DivisionKey column
 * @method     ChildPlayerdivisionrankingQuery orderByScore($order = Criteria::ASC) Order by the Score column
 * @method     ChildPlayerdivisionrankingQuery orderByRankdate($order = Criteria::ASC) Order by the RankDate column
 * @method     ChildPlayerdivisionrankingQuery orderByRank($order = Criteria::ASC) Order by the Rank column
 * @method     ChildPlayerdivisionrankingQuery orderByWon($order = Criteria::ASC) Order by the Won column
 * @method     ChildPlayerdivisionrankingQuery orderByDrawn($order = Criteria::ASC) Order by the Drawn column
 * @method     ChildPlayerdivisionrankingQuery orderByLost($order = Criteria::ASC) Order by the Lost column
 * @method     ChildPlayerdivisionrankingQuery orderByPointsfor($order = Criteria::ASC) Order by the PointsFor column
 * @method     ChildPlayerdivisionrankingQuery orderByPointsagainst($order = Criteria::ASC) Order by the PointsAgainst column
 * @method     ChildPlayerdivisionrankingQuery orderByPointsdifference($order = Criteria::ASC) Order by the PointsDifference column
 *
 * @method     ChildPlayerdivisionrankingQuery groupByPlayerkey() Group by the PlayerKey column
 * @method     ChildPlayerdivisionrankingQuery groupBySeasonkey() Group by the SeasonKey column
 * @method     ChildPlayerdivisionrankingQuery groupByDivisionkey() Group by the DivisionKey column
 * @method     ChildPlayerdivisionrankingQuery groupByScore() Group by the Score column
 * @method     ChildPlayerdivisionrankingQuery groupByRankdate() Group by the RankDate column
 * @method     ChildPlayerdivisionrankingQuery groupByRank() Group by the Rank column
 * @method     ChildPlayerdivisionrankingQuery groupByWon() Group by the Won column
 * @method     ChildPlayerdivisionrankingQuery groupByDrawn() Group by the Drawn column
 * @method     ChildPlayerdivisionrankingQuery groupByLost() Group by the Lost column
 * @method     ChildPlayerdivisionrankingQuery groupByPointsfor() Group by the PointsFor column
 * @method     ChildPlayerdivisionrankingQuery groupByPointsagainst() Group by the PointsAgainst column
 * @method     ChildPlayerdivisionrankingQuery groupByPointsdifference() Group by the PointsDifference column
 *
 * @method     ChildPlayerdivisionrankingQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildPlayerdivisionrankingQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildPlayerdivisionrankingQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildPlayerdivisionrankingQuery leftJoinDivisionRankingPlayer($relationAlias = null) Adds a LEFT JOIN clause to the query using the DivisionRankingPlayer relation
 * @method     ChildPlayerdivisionrankingQuery rightJoinDivisionRankingPlayer($relationAlias = null) Adds a RIGHT JOIN clause to the query using the DivisionRankingPlayer relation
 * @method     ChildPlayerdivisionrankingQuery innerJoinDivisionRankingPlayer($relationAlias = null) Adds a INNER JOIN clause to the query using the DivisionRankingPlayer relation
 *
 * @method     ChildPlayerdivisionrankingQuery leftJoinDivisionRankingSeason($relationAlias = null) Adds a LEFT JOIN clause to the query using the DivisionRankingSeason relation
 * @method     ChildPlayerdivisionrankingQuery rightJoinDivisionRankingSeason($relationAlias = null) Adds a RIGHT JOIN clause to the query using the DivisionRankingSeason relation
 * @method     ChildPlayerdivisionrankingQuery innerJoinDivisionRankingSeason($relationAlias = null) Adds a INNER JOIN clause to the query using the DivisionRankingSeason relation
 *
 * @method     ChildPlayerdivisionrankingQuery leftJoinDivisionRankingDivision($relationAlias = null) Adds a LEFT JOIN clause to the query using the DivisionRankingDivision relation
 * @method     ChildPlayerdivisionrankingQuery rightJoinDivisionRankingDivision($relationAlias = null) Adds a RIGHT JOIN clause to the query using the DivisionRankingDivision relation
 * @method     ChildPlayerdivisionrankingQuery innerJoinDivisionRankingDivision($relationAlias = null) Adds a INNER JOIN clause to the query using the DivisionRankingDivision relation
 *
 * @method     \PlayersQuery|\SeasonsQuery|\DivisionsQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildPlayerdivisionranking findOne(ConnectionInterface $con = null) Return the first ChildPlayerdivisionranking matching the query
 * @method     ChildPlayerdivisionranking findOneOrCreate(ConnectionInterface $con = null) Return the first ChildPlayerdivisionranking matching the query, or a new ChildPlayerdivisionranking object populated from the query conditions when no match is found
 *
 * @method     ChildPlayerdivisionranking findOneByPlayerkey(int $PlayerKey) Return the first ChildPlayerdivisionranking filtered by the PlayerKey column
 * @method     ChildPlayerdivisionranking findOneBySeasonkey(int $SeasonKey) Return the first ChildPlayerdivisionranking filtered by the SeasonKey column
 * @method     ChildPlayerdivisionranking findOneByDivisionkey(int $DivisionKey) Return the first ChildPlayerdivisionranking filtered by the DivisionKey column
 * @method     ChildPlayerdivisionranking findOneByScore(int $Score) Return the first ChildPlayerdivisionranking filtered by the Score column
 * @method     ChildPlayerdivisionranking findOneByRankdate(string $RankDate) Return the first ChildPlayerdivisionranking filtered by the RankDate column
 * @method     ChildPlayerdivisionranking findOneByRank(int $Rank) Return the first ChildPlayerdivisionranking filtered by the Rank column
 * @method     ChildPlayerdivisionranking findOneByWon(int $Won) Return the first ChildPlayerdivisionranking filtered by the Won column
 * @method     ChildPlayerdivisionranking findOneByDrawn(int $Drawn) Return the first ChildPlayerdivisionranking filtered by the Drawn column
 * @method     ChildPlayerdivisionranking findOneByLost(int $Lost) Return the first ChildPlayerdivisionranking filtered by the Lost column
 * @method     ChildPlayerdivisionranking findOneByPointsfor(int $PointsFor) Return the first ChildPlayerdivisionranking filtered by the PointsFor column
 * @method     ChildPlayerdivisionranking findOneByPointsagainst(int $PointsAgainst) Return the first ChildPlayerdivisionranking filtered by the PointsAgainst column
 * @method     ChildPlayerdivisionranking findOneByPointsdifference(int $PointsDifference) Return the first ChildPlayerdivisionranking filtered by the PointsDifference column *

 * @method     ChildPlayerdivisionranking requirePk($key, ConnectionInterface $con = null) Return the ChildPlayerdivisionranking by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayerdivisionranking requireOne(ConnectionInterface $con = null) Return the first ChildPlayerdivisionranking matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPlayerdivisionranking requireOneByPlayerkey(int $PlayerKey) Return the first ChildPlayerdivisionranking filtered by the PlayerKey column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayerdivisionranking requireOneBySeasonkey(int $SeasonKey) Return the first ChildPlayerdivisionranking filtered by the SeasonKey column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayerdivisionranking requireOneByDivisionkey(int $DivisionKey) Return the first ChildPlayerdivisionranking filtered by the DivisionKey column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayerdivisionranking requireOneByScore(int $Score) Return the first ChildPlayerdivisionranking filtered by the Score column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayerdivisionranking requireOneByRankdate(string $RankDate) Return the first ChildPlayerdivisionranking filtered by the RankDate column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayerdivisionranking requireOneByRank(int $Rank) Return the first ChildPlayerdivisionranking filtered by the Rank column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayerdivisionranking requireOneByWon(int $Won) Return the first ChildPlayerdivisionranking filtered by the Won column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayerdivisionranking requireOneByDrawn(int $Drawn) Return the first ChildPlayerdivisionranking filtered by the Drawn column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayerdivisionranking requireOneByLost(int $Lost) Return the first ChildPlayerdivisionranking filtered by the Lost column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayerdivisionranking requireOneByPointsfor(int $PointsFor) Return the first ChildPlayerdivisionranking filtered by the PointsFor column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayerdivisionranking requireOneByPointsagainst(int $PointsAgainst) Return the first ChildPlayerdivisionranking filtered by the PointsAgainst column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayerdivisionranking requireOneByPointsdifference(int $PointsDifference) Return the first ChildPlayerdivisionranking filtered by the PointsDifference column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPlayerdivisionranking[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildPlayerdivisionranking objects based on current ModelCriteria
 * @method     ChildPlayerdivisionranking[]|ObjectCollection findByPlayerkey(int $PlayerKey) Return ChildPlayerdivisionranking objects filtered by the PlayerKey column
 * @method     ChildPlayerdivisionranking[]|ObjectCollection findBySeasonkey(int $SeasonKey) Return ChildPlayerdivisionranking objects filtered by the SeasonKey column
 * @method     ChildPlayerdivisionranking[]|ObjectCollection findByDivisionkey(int $DivisionKey) Return ChildPlayerdivisionranking objects filtered by the DivisionKey column
 * @method     ChildPlayerdivisionranking[]|ObjectCollection findByScore(int $Score) Return ChildPlayerdivisionranking objects filtered by the Score column
 * @method     ChildPlayerdivisionranking[]|ObjectCollection findByRankdate(string $RankDate) Return ChildPlayerdivisionranking objects filtered by the RankDate column
 * @method     ChildPlayerdivisionranking[]|ObjectCollection findByRank(int $Rank) Return ChildPlayerdivisionranking objects filtered by the Rank column
 * @method     ChildPlayerdivisionranking[]|ObjectCollection findByWon(int $Won) Return ChildPlayerdivisionranking objects filtered by the Won column
 * @method     ChildPlayerdivisionranking[]|ObjectCollection findByDrawn(int $Drawn) Return ChildPlayerdivisionranking objects filtered by the Drawn column
 * @method     ChildPlayerdivisionranking[]|ObjectCollection findByLost(int $Lost) Return ChildPlayerdivisionranking objects filtered by the Lost column
 * @method     ChildPlayerdivisionranking[]|ObjectCollection findByPointsfor(int $PointsFor) Return ChildPlayerdivisionranking objects filtered by the PointsFor column
 * @method     ChildPlayerdivisionranking[]|ObjectCollection findByPointsagainst(int $PointsAgainst) Return ChildPlayerdivisionranking objects filtered by the PointsAgainst column
 * @method     ChildPlayerdivisionranking[]|ObjectCollection findByPointsdifference(int $PointsDifference) Return ChildPlayerdivisionranking objects filtered by the PointsDifference column
 * @method     ChildPlayerdivisionranking[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class PlayerdivisionrankingQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Base\PlayerdivisionrankingQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Playerdivisionranking', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildPlayerdivisionrankingQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildPlayerdivisionrankingQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildPlayerdivisionrankingQuery) {
            return $criteria;
        }
        $query = new ChildPlayerdivisionrankingQuery();
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
     * $obj = $c->findPk(array(12, 34, 56, 78), $con);
     * </code>
     *
     * @param array[$PlayerKey, $SeasonKey, $DivisionKey, $RankDate] $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildPlayerdivisionranking|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = PlayerdivisionrankingTableMap::getInstanceFromPool(serialize(array((string) $key[0], (string) $key[1], (string) $key[2], (string) $key[3]))))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(PlayerdivisionrankingTableMap::DATABASE_NAME);
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
     * @return ChildPlayerdivisionranking A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT PlayerKey, SeasonKey, DivisionKey, Score, RankDate, Rank, Won, Drawn, Lost, PointsFor, PointsAgainst, PointsDifference FROM playerdivisionranking WHERE PlayerKey = :p0 AND SeasonKey = :p1 AND DivisionKey = :p2 AND RankDate = :p3';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key[0], PDO::PARAM_INT);
            $stmt->bindValue(':p1', $key[1], PDO::PARAM_INT);
            $stmt->bindValue(':p2', $key[2], PDO::PARAM_INT);
            $stmt->bindValue(':p3', $key[3] ? $key[3]->format("Y-m-d H:i:s") : null, PDO::PARAM_STR);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildPlayerdivisionranking $obj */
            $obj = new ChildPlayerdivisionranking();
            $obj->hydrate($row);
            PlayerdivisionrankingTableMap::addInstanceToPool($obj, serialize(array((string) $key[0], (string) $key[1], (string) $key[2], (string) $key[3])));
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
     * @return ChildPlayerdivisionranking|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildPlayerdivisionrankingQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(PlayerdivisionrankingTableMap::COL_PLAYERKEY, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(PlayerdivisionrankingTableMap::COL_SEASONKEY, $key[1], Criteria::EQUAL);
        $this->addUsingAlias(PlayerdivisionrankingTableMap::COL_DIVISIONKEY, $key[2], Criteria::EQUAL);
        $this->addUsingAlias(PlayerdivisionrankingTableMap::COL_RANKDATE, $key[3], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildPlayerdivisionrankingQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(PlayerdivisionrankingTableMap::COL_PLAYERKEY, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(PlayerdivisionrankingTableMap::COL_SEASONKEY, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $cton2 = $this->getNewCriterion(PlayerdivisionrankingTableMap::COL_DIVISIONKEY, $key[2], Criteria::EQUAL);
            $cton0->addAnd($cton2);
            $cton3 = $this->getNewCriterion(PlayerdivisionrankingTableMap::COL_RANKDATE, $key[3], Criteria::EQUAL);
            $cton0->addAnd($cton3);
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
     * @see       filterByDivisionRankingPlayer()
     *
     * @param     mixed $playerkey The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlayerdivisionrankingQuery The current query, for fluid interface
     */
    public function filterByPlayerkey($playerkey = null, $comparison = null)
    {
        if (is_array($playerkey)) {
            $useMinMax = false;
            if (isset($playerkey['min'])) {
                $this->addUsingAlias(PlayerdivisionrankingTableMap::COL_PLAYERKEY, $playerkey['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($playerkey['max'])) {
                $this->addUsingAlias(PlayerdivisionrankingTableMap::COL_PLAYERKEY, $playerkey['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayerdivisionrankingTableMap::COL_PLAYERKEY, $playerkey, $comparison);
    }

    /**
     * Filter the query on the SeasonKey column
     *
     * Example usage:
     * <code>
     * $query->filterBySeasonkey(1234); // WHERE SeasonKey = 1234
     * $query->filterBySeasonkey(array(12, 34)); // WHERE SeasonKey IN (12, 34)
     * $query->filterBySeasonkey(array('min' => 12)); // WHERE SeasonKey > 12
     * </code>
     *
     * @see       filterByDivisionRankingSeason()
     *
     * @param     mixed $seasonkey The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlayerdivisionrankingQuery The current query, for fluid interface
     */
    public function filterBySeasonkey($seasonkey = null, $comparison = null)
    {
        if (is_array($seasonkey)) {
            $useMinMax = false;
            if (isset($seasonkey['min'])) {
                $this->addUsingAlias(PlayerdivisionrankingTableMap::COL_SEASONKEY, $seasonkey['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($seasonkey['max'])) {
                $this->addUsingAlias(PlayerdivisionrankingTableMap::COL_SEASONKEY, $seasonkey['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayerdivisionrankingTableMap::COL_SEASONKEY, $seasonkey, $comparison);
    }

    /**
     * Filter the query on the DivisionKey column
     *
     * Example usage:
     * <code>
     * $query->filterByDivisionkey(1234); // WHERE DivisionKey = 1234
     * $query->filterByDivisionkey(array(12, 34)); // WHERE DivisionKey IN (12, 34)
     * $query->filterByDivisionkey(array('min' => 12)); // WHERE DivisionKey > 12
     * </code>
     *
     * @see       filterByDivisionRankingDivision()
     *
     * @param     mixed $divisionkey The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlayerdivisionrankingQuery The current query, for fluid interface
     */
    public function filterByDivisionkey($divisionkey = null, $comparison = null)
    {
        if (is_array($divisionkey)) {
            $useMinMax = false;
            if (isset($divisionkey['min'])) {
                $this->addUsingAlias(PlayerdivisionrankingTableMap::COL_DIVISIONKEY, $divisionkey['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($divisionkey['max'])) {
                $this->addUsingAlias(PlayerdivisionrankingTableMap::COL_DIVISIONKEY, $divisionkey['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayerdivisionrankingTableMap::COL_DIVISIONKEY, $divisionkey, $comparison);
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
     * @return $this|ChildPlayerdivisionrankingQuery The current query, for fluid interface
     */
    public function filterByScore($score = null, $comparison = null)
    {
        if (is_array($score)) {
            $useMinMax = false;
            if (isset($score['min'])) {
                $this->addUsingAlias(PlayerdivisionrankingTableMap::COL_SCORE, $score['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($score['max'])) {
                $this->addUsingAlias(PlayerdivisionrankingTableMap::COL_SCORE, $score['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayerdivisionrankingTableMap::COL_SCORE, $score, $comparison);
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
     * @return $this|ChildPlayerdivisionrankingQuery The current query, for fluid interface
     */
    public function filterByRankdate($rankdate = null, $comparison = null)
    {
        if (is_array($rankdate)) {
            $useMinMax = false;
            if (isset($rankdate['min'])) {
                $this->addUsingAlias(PlayerdivisionrankingTableMap::COL_RANKDATE, $rankdate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($rankdate['max'])) {
                $this->addUsingAlias(PlayerdivisionrankingTableMap::COL_RANKDATE, $rankdate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayerdivisionrankingTableMap::COL_RANKDATE, $rankdate, $comparison);
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
     * @return $this|ChildPlayerdivisionrankingQuery The current query, for fluid interface
     */
    public function filterByRank($rank = null, $comparison = null)
    {
        if (is_array($rank)) {
            $useMinMax = false;
            if (isset($rank['min'])) {
                $this->addUsingAlias(PlayerdivisionrankingTableMap::COL_RANK, $rank['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($rank['max'])) {
                $this->addUsingAlias(PlayerdivisionrankingTableMap::COL_RANK, $rank['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayerdivisionrankingTableMap::COL_RANK, $rank, $comparison);
    }

    /**
     * Filter the query on the Won column
     *
     * Example usage:
     * <code>
     * $query->filterByWon(1234); // WHERE Won = 1234
     * $query->filterByWon(array(12, 34)); // WHERE Won IN (12, 34)
     * $query->filterByWon(array('min' => 12)); // WHERE Won > 12
     * </code>
     *
     * @param     mixed $won The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlayerdivisionrankingQuery The current query, for fluid interface
     */
    public function filterByWon($won = null, $comparison = null)
    {
        if (is_array($won)) {
            $useMinMax = false;
            if (isset($won['min'])) {
                $this->addUsingAlias(PlayerdivisionrankingTableMap::COL_WON, $won['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($won['max'])) {
                $this->addUsingAlias(PlayerdivisionrankingTableMap::COL_WON, $won['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayerdivisionrankingTableMap::COL_WON, $won, $comparison);
    }

    /**
     * Filter the query on the Drawn column
     *
     * Example usage:
     * <code>
     * $query->filterByDrawn(1234); // WHERE Drawn = 1234
     * $query->filterByDrawn(array(12, 34)); // WHERE Drawn IN (12, 34)
     * $query->filterByDrawn(array('min' => 12)); // WHERE Drawn > 12
     * </code>
     *
     * @param     mixed $drawn The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlayerdivisionrankingQuery The current query, for fluid interface
     */
    public function filterByDrawn($drawn = null, $comparison = null)
    {
        if (is_array($drawn)) {
            $useMinMax = false;
            if (isset($drawn['min'])) {
                $this->addUsingAlias(PlayerdivisionrankingTableMap::COL_DRAWN, $drawn['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($drawn['max'])) {
                $this->addUsingAlias(PlayerdivisionrankingTableMap::COL_DRAWN, $drawn['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayerdivisionrankingTableMap::COL_DRAWN, $drawn, $comparison);
    }

    /**
     * Filter the query on the Lost column
     *
     * Example usage:
     * <code>
     * $query->filterByLost(1234); // WHERE Lost = 1234
     * $query->filterByLost(array(12, 34)); // WHERE Lost IN (12, 34)
     * $query->filterByLost(array('min' => 12)); // WHERE Lost > 12
     * </code>
     *
     * @param     mixed $lost The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlayerdivisionrankingQuery The current query, for fluid interface
     */
    public function filterByLost($lost = null, $comparison = null)
    {
        if (is_array($lost)) {
            $useMinMax = false;
            if (isset($lost['min'])) {
                $this->addUsingAlias(PlayerdivisionrankingTableMap::COL_LOST, $lost['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($lost['max'])) {
                $this->addUsingAlias(PlayerdivisionrankingTableMap::COL_LOST, $lost['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayerdivisionrankingTableMap::COL_LOST, $lost, $comparison);
    }

    /**
     * Filter the query on the PointsFor column
     *
     * Example usage:
     * <code>
     * $query->filterByPointsfor(1234); // WHERE PointsFor = 1234
     * $query->filterByPointsfor(array(12, 34)); // WHERE PointsFor IN (12, 34)
     * $query->filterByPointsfor(array('min' => 12)); // WHERE PointsFor > 12
     * </code>
     *
     * @param     mixed $pointsfor The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlayerdivisionrankingQuery The current query, for fluid interface
     */
    public function filterByPointsfor($pointsfor = null, $comparison = null)
    {
        if (is_array($pointsfor)) {
            $useMinMax = false;
            if (isset($pointsfor['min'])) {
                $this->addUsingAlias(PlayerdivisionrankingTableMap::COL_POINTSFOR, $pointsfor['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pointsfor['max'])) {
                $this->addUsingAlias(PlayerdivisionrankingTableMap::COL_POINTSFOR, $pointsfor['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayerdivisionrankingTableMap::COL_POINTSFOR, $pointsfor, $comparison);
    }

    /**
     * Filter the query on the PointsAgainst column
     *
     * Example usage:
     * <code>
     * $query->filterByPointsagainst(1234); // WHERE PointsAgainst = 1234
     * $query->filterByPointsagainst(array(12, 34)); // WHERE PointsAgainst IN (12, 34)
     * $query->filterByPointsagainst(array('min' => 12)); // WHERE PointsAgainst > 12
     * </code>
     *
     * @param     mixed $pointsagainst The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlayerdivisionrankingQuery The current query, for fluid interface
     */
    public function filterByPointsagainst($pointsagainst = null, $comparison = null)
    {
        if (is_array($pointsagainst)) {
            $useMinMax = false;
            if (isset($pointsagainst['min'])) {
                $this->addUsingAlias(PlayerdivisionrankingTableMap::COL_POINTSAGAINST, $pointsagainst['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pointsagainst['max'])) {
                $this->addUsingAlias(PlayerdivisionrankingTableMap::COL_POINTSAGAINST, $pointsagainst['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayerdivisionrankingTableMap::COL_POINTSAGAINST, $pointsagainst, $comparison);
    }

    /**
     * Filter the query on the PointsDifference column
     *
     * Example usage:
     * <code>
     * $query->filterByPointsdifference(1234); // WHERE PointsDifference = 1234
     * $query->filterByPointsdifference(array(12, 34)); // WHERE PointsDifference IN (12, 34)
     * $query->filterByPointsdifference(array('min' => 12)); // WHERE PointsDifference > 12
     * </code>
     *
     * @param     mixed $pointsdifference The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlayerdivisionrankingQuery The current query, for fluid interface
     */
    public function filterByPointsdifference($pointsdifference = null, $comparison = null)
    {
        if (is_array($pointsdifference)) {
            $useMinMax = false;
            if (isset($pointsdifference['min'])) {
                $this->addUsingAlias(PlayerdivisionrankingTableMap::COL_POINTSDIFFERENCE, $pointsdifference['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pointsdifference['max'])) {
                $this->addUsingAlias(PlayerdivisionrankingTableMap::COL_POINTSDIFFERENCE, $pointsdifference['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayerdivisionrankingTableMap::COL_POINTSDIFFERENCE, $pointsdifference, $comparison);
    }

    /**
     * Filter the query by a related \Players object
     *
     * @param \Players|ObjectCollection $players The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildPlayerdivisionrankingQuery The current query, for fluid interface
     */
    public function filterByDivisionRankingPlayer($players, $comparison = null)
    {
        if ($players instanceof \Players) {
            return $this
                ->addUsingAlias(PlayerdivisionrankingTableMap::COL_PLAYERKEY, $players->getPlayerPK(), $comparison);
        } elseif ($players instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PlayerdivisionrankingTableMap::COL_PLAYERKEY, $players->toKeyValue('PrimaryKey', 'PlayerPK'), $comparison);
        } else {
            throw new PropelException('filterByDivisionRankingPlayer() only accepts arguments of type \Players or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the DivisionRankingPlayer relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPlayerdivisionrankingQuery The current query, for fluid interface
     */
    public function joinDivisionRankingPlayer($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('DivisionRankingPlayer');

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
            $this->addJoinObject($join, 'DivisionRankingPlayer');
        }

        return $this;
    }

    /**
     * Use the DivisionRankingPlayer relation Players object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \PlayersQuery A secondary query class using the current class as primary query
     */
    public function useDivisionRankingPlayerQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinDivisionRankingPlayer($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'DivisionRankingPlayer', '\PlayersQuery');
    }

    /**
     * Filter the query by a related \Seasons object
     *
     * @param \Seasons|ObjectCollection $seasons The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildPlayerdivisionrankingQuery The current query, for fluid interface
     */
    public function filterByDivisionRankingSeason($seasons, $comparison = null)
    {
        if ($seasons instanceof \Seasons) {
            return $this
                ->addUsingAlias(PlayerdivisionrankingTableMap::COL_SEASONKEY, $seasons->getSeasonPK(), $comparison);
        } elseif ($seasons instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PlayerdivisionrankingTableMap::COL_SEASONKEY, $seasons->toKeyValue('PrimaryKey', 'SeasonPK'), $comparison);
        } else {
            throw new PropelException('filterByDivisionRankingSeason() only accepts arguments of type \Seasons or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the DivisionRankingSeason relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPlayerdivisionrankingQuery The current query, for fluid interface
     */
    public function joinDivisionRankingSeason($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('DivisionRankingSeason');

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
            $this->addJoinObject($join, 'DivisionRankingSeason');
        }

        return $this;
    }

    /**
     * Use the DivisionRankingSeason relation Seasons object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \SeasonsQuery A secondary query class using the current class as primary query
     */
    public function useDivisionRankingSeasonQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinDivisionRankingSeason($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'DivisionRankingSeason', '\SeasonsQuery');
    }

    /**
     * Filter the query by a related \Divisions object
     *
     * @param \Divisions|ObjectCollection $divisions The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildPlayerdivisionrankingQuery The current query, for fluid interface
     */
    public function filterByDivisionRankingDivision($divisions, $comparison = null)
    {
        if ($divisions instanceof \Divisions) {
            return $this
                ->addUsingAlias(PlayerdivisionrankingTableMap::COL_DIVISIONKEY, $divisions->getDivisionPK(), $comparison);
        } elseif ($divisions instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PlayerdivisionrankingTableMap::COL_DIVISIONKEY, $divisions->toKeyValue('PrimaryKey', 'DivisionPK'), $comparison);
        } else {
            throw new PropelException('filterByDivisionRankingDivision() only accepts arguments of type \Divisions or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the DivisionRankingDivision relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPlayerdivisionrankingQuery The current query, for fluid interface
     */
    public function joinDivisionRankingDivision($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('DivisionRankingDivision');

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
            $this->addJoinObject($join, 'DivisionRankingDivision');
        }

        return $this;
    }

    /**
     * Use the DivisionRankingDivision relation Divisions object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \DivisionsQuery A secondary query class using the current class as primary query
     */
    public function useDivisionRankingDivisionQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinDivisionRankingDivision($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'DivisionRankingDivision', '\DivisionsQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildPlayerdivisionranking $playerdivisionranking Object to remove from the list of results
     *
     * @return $this|ChildPlayerdivisionrankingQuery The current query, for fluid interface
     */
    public function prune($playerdivisionranking = null)
    {
        if ($playerdivisionranking) {
            $this->addCond('pruneCond0', $this->getAliasedColName(PlayerdivisionrankingTableMap::COL_PLAYERKEY), $playerdivisionranking->getPlayerkey(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(PlayerdivisionrankingTableMap::COL_SEASONKEY), $playerdivisionranking->getSeasonkey(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond2', $this->getAliasedColName(PlayerdivisionrankingTableMap::COL_DIVISIONKEY), $playerdivisionranking->getDivisionkey(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond3', $this->getAliasedColName(PlayerdivisionrankingTableMap::COL_RANKDATE), $playerdivisionranking->getRankdate(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1', 'pruneCond2', 'pruneCond3'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

    /**
     * Deletes all rows from the playerdivisionranking table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PlayerdivisionrankingTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            PlayerdivisionrankingTableMap::clearInstancePool();
            PlayerdivisionrankingTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(PlayerdivisionrankingTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(PlayerdivisionrankingTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            PlayerdivisionrankingTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            PlayerdivisionrankingTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // PlayerdivisionrankingQuery
