<?php

namespace Base;

use \Playerdivisionmatches as ChildPlayerdivisionmatches;
use \PlayerdivisionmatchesQuery as ChildPlayerdivisionmatchesQuery;
use \Exception;
use \PDO;
use Map\PlayerdivisionmatchesTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'playerdivisionmatches' table.
 *
 *
 *
 * @method     ChildPlayerdivisionmatchesQuery orderByPlayerDivisionMatchPK($order = Criteria::ASC) Order by the PrimaryKey column
 * @method     ChildPlayerdivisionmatchesQuery orderByPlayerhomekey($order = Criteria::ASC) Order by the PlayerHomeKey column
 * @method     ChildPlayerdivisionmatchesQuery orderByPlayerawaykey($order = Criteria::ASC) Order by the PlayerAwayKey column
 * @method     ChildPlayerdivisionmatchesQuery orderBySeasonkey($order = Criteria::ASC) Order by the SeasonKey column
 * @method     ChildPlayerdivisionmatchesQuery orderByDivisionkey($order = Criteria::ASC) Order by the DivisionKey column
 * @method     ChildPlayerdivisionmatchesQuery orderByGroupkey($order = Criteria::ASC) Order by the GroupKey column
 * @method     ChildPlayerdivisionmatchesQuery orderByHomescore($order = Criteria::ASC) Order by the HomeScore column
 * @method     ChildPlayerdivisionmatchesQuery orderByAwayscore($order = Criteria::ASC) Order by the AwayScore column
 * @method     ChildPlayerdivisionmatchesQuery orderByScheduledate($order = Criteria::ASC) Order by the ScheduleDate column
 * @method     ChildPlayerdivisionmatchesQuery orderByResultdate($order = Criteria::ASC) Order by the ResultDate column
 *
 * @method     ChildPlayerdivisionmatchesQuery groupByPlayerDivisionMatchPK() Group by the PrimaryKey column
 * @method     ChildPlayerdivisionmatchesQuery groupByPlayerhomekey() Group by the PlayerHomeKey column
 * @method     ChildPlayerdivisionmatchesQuery groupByPlayerawaykey() Group by the PlayerAwayKey column
 * @method     ChildPlayerdivisionmatchesQuery groupBySeasonkey() Group by the SeasonKey column
 * @method     ChildPlayerdivisionmatchesQuery groupByDivisionkey() Group by the DivisionKey column
 * @method     ChildPlayerdivisionmatchesQuery groupByGroupkey() Group by the GroupKey column
 * @method     ChildPlayerdivisionmatchesQuery groupByHomescore() Group by the HomeScore column
 * @method     ChildPlayerdivisionmatchesQuery groupByAwayscore() Group by the AwayScore column
 * @method     ChildPlayerdivisionmatchesQuery groupByScheduledate() Group by the ScheduleDate column
 * @method     ChildPlayerdivisionmatchesQuery groupByResultdate() Group by the ResultDate column
 *
 * @method     ChildPlayerdivisionmatchesQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildPlayerdivisionmatchesQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildPlayerdivisionmatchesQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildPlayerdivisionmatchesQuery leftJoinDivisionMatchesPlayerHome($relationAlias = null) Adds a LEFT JOIN clause to the query using the DivisionMatchesPlayerHome relation
 * @method     ChildPlayerdivisionmatchesQuery rightJoinDivisionMatchesPlayerHome($relationAlias = null) Adds a RIGHT JOIN clause to the query using the DivisionMatchesPlayerHome relation
 * @method     ChildPlayerdivisionmatchesQuery innerJoinDivisionMatchesPlayerHome($relationAlias = null) Adds a INNER JOIN clause to the query using the DivisionMatchesPlayerHome relation
 *
 * @method     ChildPlayerdivisionmatchesQuery leftJoinDivisionMatchesPlayerAway($relationAlias = null) Adds a LEFT JOIN clause to the query using the DivisionMatchesPlayerAway relation
 * @method     ChildPlayerdivisionmatchesQuery rightJoinDivisionMatchesPlayerAway($relationAlias = null) Adds a RIGHT JOIN clause to the query using the DivisionMatchesPlayerAway relation
 * @method     ChildPlayerdivisionmatchesQuery innerJoinDivisionMatchesPlayerAway($relationAlias = null) Adds a INNER JOIN clause to the query using the DivisionMatchesPlayerAway relation
 *
 * @method     ChildPlayerdivisionmatchesQuery leftJoinDivisionMatchesDivision($relationAlias = null) Adds a LEFT JOIN clause to the query using the DivisionMatchesDivision relation
 * @method     ChildPlayerdivisionmatchesQuery rightJoinDivisionMatchesDivision($relationAlias = null) Adds a RIGHT JOIN clause to the query using the DivisionMatchesDivision relation
 * @method     ChildPlayerdivisionmatchesQuery innerJoinDivisionMatchesDivision($relationAlias = null) Adds a INNER JOIN clause to the query using the DivisionMatchesDivision relation
 *
 * @method     ChildPlayerdivisionmatchesQuery leftJoinDivisionMatchesGroup($relationAlias = null) Adds a LEFT JOIN clause to the query using the DivisionMatchesGroup relation
 * @method     ChildPlayerdivisionmatchesQuery rightJoinDivisionMatchesGroup($relationAlias = null) Adds a RIGHT JOIN clause to the query using the DivisionMatchesGroup relation
 * @method     ChildPlayerdivisionmatchesQuery innerJoinDivisionMatchesGroup($relationAlias = null) Adds a INNER JOIN clause to the query using the DivisionMatchesGroup relation
 *
 * @method     ChildPlayerdivisionmatchesQuery leftJoinDivisionMatchesSeason($relationAlias = null) Adds a LEFT JOIN clause to the query using the DivisionMatchesSeason relation
 * @method     ChildPlayerdivisionmatchesQuery rightJoinDivisionMatchesSeason($relationAlias = null) Adds a RIGHT JOIN clause to the query using the DivisionMatchesSeason relation
 * @method     ChildPlayerdivisionmatchesQuery innerJoinDivisionMatchesSeason($relationAlias = null) Adds a INNER JOIN clause to the query using the DivisionMatchesSeason relation
 *
 * @method     \PlayersQuery|\DivisionsQuery|\GroupsQuery|\SeasonsQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildPlayerdivisionmatches findOne(ConnectionInterface $con = null) Return the first ChildPlayerdivisionmatches matching the query
 * @method     ChildPlayerdivisionmatches findOneOrCreate(ConnectionInterface $con = null) Return the first ChildPlayerdivisionmatches matching the query, or a new ChildPlayerdivisionmatches object populated from the query conditions when no match is found
 *
 * @method     ChildPlayerdivisionmatches findOneByPlayerDivisionMatchPK(int $PrimaryKey) Return the first ChildPlayerdivisionmatches filtered by the PrimaryKey column
 * @method     ChildPlayerdivisionmatches findOneByPlayerhomekey(int $PlayerHomeKey) Return the first ChildPlayerdivisionmatches filtered by the PlayerHomeKey column
 * @method     ChildPlayerdivisionmatches findOneByPlayerawaykey(int $PlayerAwayKey) Return the first ChildPlayerdivisionmatches filtered by the PlayerAwayKey column
 * @method     ChildPlayerdivisionmatches findOneBySeasonkey(int $SeasonKey) Return the first ChildPlayerdivisionmatches filtered by the SeasonKey column
 * @method     ChildPlayerdivisionmatches findOneByDivisionkey(int $DivisionKey) Return the first ChildPlayerdivisionmatches filtered by the DivisionKey column
 * @method     ChildPlayerdivisionmatches findOneByGroupkey(int $GroupKey) Return the first ChildPlayerdivisionmatches filtered by the GroupKey column
 * @method     ChildPlayerdivisionmatches findOneByHomescore(int $HomeScore) Return the first ChildPlayerdivisionmatches filtered by the HomeScore column
 * @method     ChildPlayerdivisionmatches findOneByAwayscore(int $AwayScore) Return the first ChildPlayerdivisionmatches filtered by the AwayScore column
 * @method     ChildPlayerdivisionmatches findOneByScheduledate(string $ScheduleDate) Return the first ChildPlayerdivisionmatches filtered by the ScheduleDate column
 * @method     ChildPlayerdivisionmatches findOneByResultdate(string $ResultDate) Return the first ChildPlayerdivisionmatches filtered by the ResultDate column *

 * @method     ChildPlayerdivisionmatches requirePk($key, ConnectionInterface $con = null) Return the ChildPlayerdivisionmatches by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayerdivisionmatches requireOne(ConnectionInterface $con = null) Return the first ChildPlayerdivisionmatches matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPlayerdivisionmatches requireOneByPlayerDivisionMatchPK(int $PrimaryKey) Return the first ChildPlayerdivisionmatches filtered by the PrimaryKey column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayerdivisionmatches requireOneByPlayerhomekey(int $PlayerHomeKey) Return the first ChildPlayerdivisionmatches filtered by the PlayerHomeKey column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayerdivisionmatches requireOneByPlayerawaykey(int $PlayerAwayKey) Return the first ChildPlayerdivisionmatches filtered by the PlayerAwayKey column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayerdivisionmatches requireOneBySeasonkey(int $SeasonKey) Return the first ChildPlayerdivisionmatches filtered by the SeasonKey column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayerdivisionmatches requireOneByDivisionkey(int $DivisionKey) Return the first ChildPlayerdivisionmatches filtered by the DivisionKey column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayerdivisionmatches requireOneByGroupkey(int $GroupKey) Return the first ChildPlayerdivisionmatches filtered by the GroupKey column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayerdivisionmatches requireOneByHomescore(int $HomeScore) Return the first ChildPlayerdivisionmatches filtered by the HomeScore column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayerdivisionmatches requireOneByAwayscore(int $AwayScore) Return the first ChildPlayerdivisionmatches filtered by the AwayScore column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayerdivisionmatches requireOneByScheduledate(string $ScheduleDate) Return the first ChildPlayerdivisionmatches filtered by the ScheduleDate column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayerdivisionmatches requireOneByResultdate(string $ResultDate) Return the first ChildPlayerdivisionmatches filtered by the ResultDate column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPlayerdivisionmatches[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildPlayerdivisionmatches objects based on current ModelCriteria
 * @method     ChildPlayerdivisionmatches[]|ObjectCollection findByPlayerDivisionMatchPK(int $PrimaryKey) Return ChildPlayerdivisionmatches objects filtered by the PrimaryKey column
 * @method     ChildPlayerdivisionmatches[]|ObjectCollection findByPlayerhomekey(int $PlayerHomeKey) Return ChildPlayerdivisionmatches objects filtered by the PlayerHomeKey column
 * @method     ChildPlayerdivisionmatches[]|ObjectCollection findByPlayerawaykey(int $PlayerAwayKey) Return ChildPlayerdivisionmatches objects filtered by the PlayerAwayKey column
 * @method     ChildPlayerdivisionmatches[]|ObjectCollection findBySeasonkey(int $SeasonKey) Return ChildPlayerdivisionmatches objects filtered by the SeasonKey column
 * @method     ChildPlayerdivisionmatches[]|ObjectCollection findByDivisionkey(int $DivisionKey) Return ChildPlayerdivisionmatches objects filtered by the DivisionKey column
 * @method     ChildPlayerdivisionmatches[]|ObjectCollection findByGroupkey(int $GroupKey) Return ChildPlayerdivisionmatches objects filtered by the GroupKey column
 * @method     ChildPlayerdivisionmatches[]|ObjectCollection findByHomescore(int $HomeScore) Return ChildPlayerdivisionmatches objects filtered by the HomeScore column
 * @method     ChildPlayerdivisionmatches[]|ObjectCollection findByAwayscore(int $AwayScore) Return ChildPlayerdivisionmatches objects filtered by the AwayScore column
 * @method     ChildPlayerdivisionmatches[]|ObjectCollection findByScheduledate(string $ScheduleDate) Return ChildPlayerdivisionmatches objects filtered by the ScheduleDate column
 * @method     ChildPlayerdivisionmatches[]|ObjectCollection findByResultdate(string $ResultDate) Return ChildPlayerdivisionmatches objects filtered by the ResultDate column
 * @method     ChildPlayerdivisionmatches[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class PlayerdivisionmatchesQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Base\PlayerdivisionmatchesQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Playerdivisionmatches', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildPlayerdivisionmatchesQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildPlayerdivisionmatchesQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildPlayerdivisionmatchesQuery) {
            return $criteria;
        }
        $query = new ChildPlayerdivisionmatchesQuery();
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
     * @return ChildPlayerdivisionmatches|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = PlayerdivisionmatchesTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(PlayerdivisionmatchesTableMap::DATABASE_NAME);
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
     * @return ChildPlayerdivisionmatches A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT PrimaryKey, PlayerHomeKey, PlayerAwayKey, SeasonKey, DivisionKey, GroupKey, HomeScore, AwayScore, ScheduleDate, ResultDate FROM playerdivisionmatches WHERE PrimaryKey = :p0';
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
            /** @var ChildPlayerdivisionmatches $obj */
            $obj = new ChildPlayerdivisionmatches();
            $obj->hydrate($row);
            PlayerdivisionmatchesTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildPlayerdivisionmatches|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildPlayerdivisionmatchesQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(PlayerdivisionmatchesTableMap::COL_PRIMARYKEY, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildPlayerdivisionmatchesQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(PlayerdivisionmatchesTableMap::COL_PRIMARYKEY, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the PrimaryKey column
     *
     * Example usage:
     * <code>
     * $query->filterByPlayerDivisionMatchPK(1234); // WHERE PrimaryKey = 1234
     * $query->filterByPlayerDivisionMatchPK(array(12, 34)); // WHERE PrimaryKey IN (12, 34)
     * $query->filterByPlayerDivisionMatchPK(array('min' => 12)); // WHERE PrimaryKey > 12
     * </code>
     *
     * @param     mixed $playerDivisionMatchPK The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlayerdivisionmatchesQuery The current query, for fluid interface
     */
    public function filterByPlayerDivisionMatchPK($playerDivisionMatchPK = null, $comparison = null)
    {
        if (is_array($playerDivisionMatchPK)) {
            $useMinMax = false;
            if (isset($playerDivisionMatchPK['min'])) {
                $this->addUsingAlias(PlayerdivisionmatchesTableMap::COL_PRIMARYKEY, $playerDivisionMatchPK['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($playerDivisionMatchPK['max'])) {
                $this->addUsingAlias(PlayerdivisionmatchesTableMap::COL_PRIMARYKEY, $playerDivisionMatchPK['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayerdivisionmatchesTableMap::COL_PRIMARYKEY, $playerDivisionMatchPK, $comparison);
    }

    /**
     * Filter the query on the PlayerHomeKey column
     *
     * Example usage:
     * <code>
     * $query->filterByPlayerhomekey(1234); // WHERE PlayerHomeKey = 1234
     * $query->filterByPlayerhomekey(array(12, 34)); // WHERE PlayerHomeKey IN (12, 34)
     * $query->filterByPlayerhomekey(array('min' => 12)); // WHERE PlayerHomeKey > 12
     * </code>
     *
     * @see       filterByDivisionMatchesPlayerHome()
     *
     * @param     mixed $playerhomekey The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlayerdivisionmatchesQuery The current query, for fluid interface
     */
    public function filterByPlayerhomekey($playerhomekey = null, $comparison = null)
    {
        if (is_array($playerhomekey)) {
            $useMinMax = false;
            if (isset($playerhomekey['min'])) {
                $this->addUsingAlias(PlayerdivisionmatchesTableMap::COL_PLAYERHOMEKEY, $playerhomekey['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($playerhomekey['max'])) {
                $this->addUsingAlias(PlayerdivisionmatchesTableMap::COL_PLAYERHOMEKEY, $playerhomekey['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayerdivisionmatchesTableMap::COL_PLAYERHOMEKEY, $playerhomekey, $comparison);
    }

    /**
     * Filter the query on the PlayerAwayKey column
     *
     * Example usage:
     * <code>
     * $query->filterByPlayerawaykey(1234); // WHERE PlayerAwayKey = 1234
     * $query->filterByPlayerawaykey(array(12, 34)); // WHERE PlayerAwayKey IN (12, 34)
     * $query->filterByPlayerawaykey(array('min' => 12)); // WHERE PlayerAwayKey > 12
     * </code>
     *
     * @see       filterByDivisionMatchesPlayerAway()
     *
     * @param     mixed $playerawaykey The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlayerdivisionmatchesQuery The current query, for fluid interface
     */
    public function filterByPlayerawaykey($playerawaykey = null, $comparison = null)
    {
        if (is_array($playerawaykey)) {
            $useMinMax = false;
            if (isset($playerawaykey['min'])) {
                $this->addUsingAlias(PlayerdivisionmatchesTableMap::COL_PLAYERAWAYKEY, $playerawaykey['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($playerawaykey['max'])) {
                $this->addUsingAlias(PlayerdivisionmatchesTableMap::COL_PLAYERAWAYKEY, $playerawaykey['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayerdivisionmatchesTableMap::COL_PLAYERAWAYKEY, $playerawaykey, $comparison);
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
     * @see       filterByDivisionMatchesSeason()
     *
     * @param     mixed $seasonkey The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlayerdivisionmatchesQuery The current query, for fluid interface
     */
    public function filterBySeasonkey($seasonkey = null, $comparison = null)
    {
        if (is_array($seasonkey)) {
            $useMinMax = false;
            if (isset($seasonkey['min'])) {
                $this->addUsingAlias(PlayerdivisionmatchesTableMap::COL_SEASONKEY, $seasonkey['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($seasonkey['max'])) {
                $this->addUsingAlias(PlayerdivisionmatchesTableMap::COL_SEASONKEY, $seasonkey['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayerdivisionmatchesTableMap::COL_SEASONKEY, $seasonkey, $comparison);
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
     * @see       filterByDivisionMatchesDivision()
     *
     * @param     mixed $divisionkey The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlayerdivisionmatchesQuery The current query, for fluid interface
     */
    public function filterByDivisionkey($divisionkey = null, $comparison = null)
    {
        if (is_array($divisionkey)) {
            $useMinMax = false;
            if (isset($divisionkey['min'])) {
                $this->addUsingAlias(PlayerdivisionmatchesTableMap::COL_DIVISIONKEY, $divisionkey['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($divisionkey['max'])) {
                $this->addUsingAlias(PlayerdivisionmatchesTableMap::COL_DIVISIONKEY, $divisionkey['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayerdivisionmatchesTableMap::COL_DIVISIONKEY, $divisionkey, $comparison);
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
     * @see       filterByDivisionMatchesGroup()
     *
     * @param     mixed $groupkey The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlayerdivisionmatchesQuery The current query, for fluid interface
     */
    public function filterByGroupkey($groupkey = null, $comparison = null)
    {
        if (is_array($groupkey)) {
            $useMinMax = false;
            if (isset($groupkey['min'])) {
                $this->addUsingAlias(PlayerdivisionmatchesTableMap::COL_GROUPKEY, $groupkey['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($groupkey['max'])) {
                $this->addUsingAlias(PlayerdivisionmatchesTableMap::COL_GROUPKEY, $groupkey['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayerdivisionmatchesTableMap::COL_GROUPKEY, $groupkey, $comparison);
    }

    /**
     * Filter the query on the HomeScore column
     *
     * Example usage:
     * <code>
     * $query->filterByHomescore(1234); // WHERE HomeScore = 1234
     * $query->filterByHomescore(array(12, 34)); // WHERE HomeScore IN (12, 34)
     * $query->filterByHomescore(array('min' => 12)); // WHERE HomeScore > 12
     * </code>
     *
     * @param     mixed $homescore The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlayerdivisionmatchesQuery The current query, for fluid interface
     */
    public function filterByHomescore($homescore = null, $comparison = null)
    {
        if (is_array($homescore)) {
            $useMinMax = false;
            if (isset($homescore['min'])) {
                $this->addUsingAlias(PlayerdivisionmatchesTableMap::COL_HOMESCORE, $homescore['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($homescore['max'])) {
                $this->addUsingAlias(PlayerdivisionmatchesTableMap::COL_HOMESCORE, $homescore['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayerdivisionmatchesTableMap::COL_HOMESCORE, $homescore, $comparison);
    }

    /**
     * Filter the query on the AwayScore column
     *
     * Example usage:
     * <code>
     * $query->filterByAwayscore(1234); // WHERE AwayScore = 1234
     * $query->filterByAwayscore(array(12, 34)); // WHERE AwayScore IN (12, 34)
     * $query->filterByAwayscore(array('min' => 12)); // WHERE AwayScore > 12
     * </code>
     *
     * @param     mixed $awayscore The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlayerdivisionmatchesQuery The current query, for fluid interface
     */
    public function filterByAwayscore($awayscore = null, $comparison = null)
    {
        if (is_array($awayscore)) {
            $useMinMax = false;
            if (isset($awayscore['min'])) {
                $this->addUsingAlias(PlayerdivisionmatchesTableMap::COL_AWAYSCORE, $awayscore['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($awayscore['max'])) {
                $this->addUsingAlias(PlayerdivisionmatchesTableMap::COL_AWAYSCORE, $awayscore['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayerdivisionmatchesTableMap::COL_AWAYSCORE, $awayscore, $comparison);
    }

    /**
     * Filter the query on the ScheduleDate column
     *
     * Example usage:
     * <code>
     * $query->filterByScheduledate('2011-03-14'); // WHERE ScheduleDate = '2011-03-14'
     * $query->filterByScheduledate('now'); // WHERE ScheduleDate = '2011-03-14'
     * $query->filterByScheduledate(array('max' => 'yesterday')); // WHERE ScheduleDate > '2011-03-13'
     * </code>
     *
     * @param     mixed $scheduledate The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlayerdivisionmatchesQuery The current query, for fluid interface
     */
    public function filterByScheduledate($scheduledate = null, $comparison = null)
    {
        if (is_array($scheduledate)) {
            $useMinMax = false;
            if (isset($scheduledate['min'])) {
                $this->addUsingAlias(PlayerdivisionmatchesTableMap::COL_SCHEDULEDATE, $scheduledate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($scheduledate['max'])) {
                $this->addUsingAlias(PlayerdivisionmatchesTableMap::COL_SCHEDULEDATE, $scheduledate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayerdivisionmatchesTableMap::COL_SCHEDULEDATE, $scheduledate, $comparison);
    }

    /**
     * Filter the query on the ResultDate column
     *
     * Example usage:
     * <code>
     * $query->filterByResultdate('2011-03-14'); // WHERE ResultDate = '2011-03-14'
     * $query->filterByResultdate('now'); // WHERE ResultDate = '2011-03-14'
     * $query->filterByResultdate(array('max' => 'yesterday')); // WHERE ResultDate > '2011-03-13'
     * </code>
     *
     * @param     mixed $resultdate The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlayerdivisionmatchesQuery The current query, for fluid interface
     */
    public function filterByResultdate($resultdate = null, $comparison = null)
    {
        if (is_array($resultdate)) {
            $useMinMax = false;
            if (isset($resultdate['min'])) {
                $this->addUsingAlias(PlayerdivisionmatchesTableMap::COL_RESULTDATE, $resultdate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($resultdate['max'])) {
                $this->addUsingAlias(PlayerdivisionmatchesTableMap::COL_RESULTDATE, $resultdate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayerdivisionmatchesTableMap::COL_RESULTDATE, $resultdate, $comparison);
    }

    /**
     * Filter the query by a related \Players object
     *
     * @param \Players|ObjectCollection $players The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildPlayerdivisionmatchesQuery The current query, for fluid interface
     */
    public function filterByDivisionMatchesPlayerHome($players, $comparison = null)
    {
        if ($players instanceof \Players) {
            return $this
                ->addUsingAlias(PlayerdivisionmatchesTableMap::COL_PLAYERHOMEKEY, $players->getPlayerPK(), $comparison);
        } elseif ($players instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PlayerdivisionmatchesTableMap::COL_PLAYERHOMEKEY, $players->toKeyValue('PrimaryKey', 'PlayerPK'), $comparison);
        } else {
            throw new PropelException('filterByDivisionMatchesPlayerHome() only accepts arguments of type \Players or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the DivisionMatchesPlayerHome relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPlayerdivisionmatchesQuery The current query, for fluid interface
     */
    public function joinDivisionMatchesPlayerHome($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('DivisionMatchesPlayerHome');

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
            $this->addJoinObject($join, 'DivisionMatchesPlayerHome');
        }

        return $this;
    }

    /**
     * Use the DivisionMatchesPlayerHome relation Players object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \PlayersQuery A secondary query class using the current class as primary query
     */
    public function useDivisionMatchesPlayerHomeQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinDivisionMatchesPlayerHome($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'DivisionMatchesPlayerHome', '\PlayersQuery');
    }

    /**
     * Filter the query by a related \Players object
     *
     * @param \Players|ObjectCollection $players The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildPlayerdivisionmatchesQuery The current query, for fluid interface
     */
    public function filterByDivisionMatchesPlayerAway($players, $comparison = null)
    {
        if ($players instanceof \Players) {
            return $this
                ->addUsingAlias(PlayerdivisionmatchesTableMap::COL_PLAYERAWAYKEY, $players->getPlayerPK(), $comparison);
        } elseif ($players instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PlayerdivisionmatchesTableMap::COL_PLAYERAWAYKEY, $players->toKeyValue('PrimaryKey', 'PlayerPK'), $comparison);
        } else {
            throw new PropelException('filterByDivisionMatchesPlayerAway() only accepts arguments of type \Players or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the DivisionMatchesPlayerAway relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPlayerdivisionmatchesQuery The current query, for fluid interface
     */
    public function joinDivisionMatchesPlayerAway($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('DivisionMatchesPlayerAway');

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
            $this->addJoinObject($join, 'DivisionMatchesPlayerAway');
        }

        return $this;
    }

    /**
     * Use the DivisionMatchesPlayerAway relation Players object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \PlayersQuery A secondary query class using the current class as primary query
     */
    public function useDivisionMatchesPlayerAwayQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinDivisionMatchesPlayerAway($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'DivisionMatchesPlayerAway', '\PlayersQuery');
    }

    /**
     * Filter the query by a related \Divisions object
     *
     * @param \Divisions|ObjectCollection $divisions The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildPlayerdivisionmatchesQuery The current query, for fluid interface
     */
    public function filterByDivisionMatchesDivision($divisions, $comparison = null)
    {
        if ($divisions instanceof \Divisions) {
            return $this
                ->addUsingAlias(PlayerdivisionmatchesTableMap::COL_DIVISIONKEY, $divisions->getDivisionPK(), $comparison);
        } elseif ($divisions instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PlayerdivisionmatchesTableMap::COL_DIVISIONKEY, $divisions->toKeyValue('PrimaryKey', 'DivisionPK'), $comparison);
        } else {
            throw new PropelException('filterByDivisionMatchesDivision() only accepts arguments of type \Divisions or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the DivisionMatchesDivision relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPlayerdivisionmatchesQuery The current query, for fluid interface
     */
    public function joinDivisionMatchesDivision($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('DivisionMatchesDivision');

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
            $this->addJoinObject($join, 'DivisionMatchesDivision');
        }

        return $this;
    }

    /**
     * Use the DivisionMatchesDivision relation Divisions object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \DivisionsQuery A secondary query class using the current class as primary query
     */
    public function useDivisionMatchesDivisionQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinDivisionMatchesDivision($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'DivisionMatchesDivision', '\DivisionsQuery');
    }

    /**
     * Filter the query by a related \Groups object
     *
     * @param \Groups|ObjectCollection $groups The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildPlayerdivisionmatchesQuery The current query, for fluid interface
     */
    public function filterByDivisionMatchesGroup($groups, $comparison = null)
    {
        if ($groups instanceof \Groups) {
            return $this
                ->addUsingAlias(PlayerdivisionmatchesTableMap::COL_GROUPKEY, $groups->getGroupPK(), $comparison);
        } elseif ($groups instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PlayerdivisionmatchesTableMap::COL_GROUPKEY, $groups->toKeyValue('PrimaryKey', 'GroupPK'), $comparison);
        } else {
            throw new PropelException('filterByDivisionMatchesGroup() only accepts arguments of type \Groups or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the DivisionMatchesGroup relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPlayerdivisionmatchesQuery The current query, for fluid interface
     */
    public function joinDivisionMatchesGroup($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('DivisionMatchesGroup');

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
            $this->addJoinObject($join, 'DivisionMatchesGroup');
        }

        return $this;
    }

    /**
     * Use the DivisionMatchesGroup relation Groups object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \GroupsQuery A secondary query class using the current class as primary query
     */
    public function useDivisionMatchesGroupQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinDivisionMatchesGroup($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'DivisionMatchesGroup', '\GroupsQuery');
    }

    /**
     * Filter the query by a related \Seasons object
     *
     * @param \Seasons|ObjectCollection $seasons The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildPlayerdivisionmatchesQuery The current query, for fluid interface
     */
    public function filterByDivisionMatchesSeason($seasons, $comparison = null)
    {
        if ($seasons instanceof \Seasons) {
            return $this
                ->addUsingAlias(PlayerdivisionmatchesTableMap::COL_SEASONKEY, $seasons->getSeasonPK(), $comparison);
        } elseif ($seasons instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PlayerdivisionmatchesTableMap::COL_SEASONKEY, $seasons->toKeyValue('PrimaryKey', 'SeasonPK'), $comparison);
        } else {
            throw new PropelException('filterByDivisionMatchesSeason() only accepts arguments of type \Seasons or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the DivisionMatchesSeason relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPlayerdivisionmatchesQuery The current query, for fluid interface
     */
    public function joinDivisionMatchesSeason($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('DivisionMatchesSeason');

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
            $this->addJoinObject($join, 'DivisionMatchesSeason');
        }

        return $this;
    }

    /**
     * Use the DivisionMatchesSeason relation Seasons object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \SeasonsQuery A secondary query class using the current class as primary query
     */
    public function useDivisionMatchesSeasonQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinDivisionMatchesSeason($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'DivisionMatchesSeason', '\SeasonsQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildPlayerdivisionmatches $playerdivisionmatches Object to remove from the list of results
     *
     * @return $this|ChildPlayerdivisionmatchesQuery The current query, for fluid interface
     */
    public function prune($playerdivisionmatches = null)
    {
        if ($playerdivisionmatches) {
            $this->addUsingAlias(PlayerdivisionmatchesTableMap::COL_PRIMARYKEY, $playerdivisionmatches->getPlayerDivisionMatchPK(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the playerdivisionmatches table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PlayerdivisionmatchesTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            PlayerdivisionmatchesTableMap::clearInstancePool();
            PlayerdivisionmatchesTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(PlayerdivisionmatchesTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(PlayerdivisionmatchesTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            PlayerdivisionmatchesTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            PlayerdivisionmatchesTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // PlayerdivisionmatchesQuery
