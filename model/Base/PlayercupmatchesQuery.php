<?php

namespace Base;

use \Playercupmatches as ChildPlayercupmatches;
use \PlayercupmatchesQuery as ChildPlayercupmatchesQuery;
use \Exception;
use \PDO;
use Map\PlayercupmatchesTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'playercupmatches' table.
 *
 *
 *
 * @method     ChildPlayercupmatchesQuery orderByPlayerCupMatchPK($order = Criteria::ASC) Order by the PrimaryKey column
 * @method     ChildPlayercupmatchesQuery orderByPlayerhomekey($order = Criteria::ASC) Order by the PlayerHomeKey column
 * @method     ChildPlayercupmatchesQuery orderByPlayerawaykey($order = Criteria::ASC) Order by the PlayerAwayKey column
 * @method     ChildPlayercupmatchesQuery orderByCuproundkey($order = Criteria::ASC) Order by the CupRoundKey column
 * @method     ChildPlayercupmatchesQuery orderBySeasonkey($order = Criteria::ASC) Order by the SeasonKey column
 * @method     ChildPlayercupmatchesQuery orderByGroupkey($order = Criteria::ASC) Order by the GroupKey column
 * @method     ChildPlayercupmatchesQuery orderByHomescore($order = Criteria::ASC) Order by the HomeScore column
 * @method     ChildPlayercupmatchesQuery orderByAwayscore($order = Criteria::ASC) Order by the AwayScore column
 * @method     ChildPlayercupmatchesQuery orderByScheduledate($order = Criteria::ASC) Order by the ScheduleDate column
 * @method     ChildPlayercupmatchesQuery orderByResultdate($order = Criteria::ASC) Order by the ResultDate column
 *
 * @method     ChildPlayercupmatchesQuery groupByPlayerCupMatchPK() Group by the PrimaryKey column
 * @method     ChildPlayercupmatchesQuery groupByPlayerhomekey() Group by the PlayerHomeKey column
 * @method     ChildPlayercupmatchesQuery groupByPlayerawaykey() Group by the PlayerAwayKey column
 * @method     ChildPlayercupmatchesQuery groupByCuproundkey() Group by the CupRoundKey column
 * @method     ChildPlayercupmatchesQuery groupBySeasonkey() Group by the SeasonKey column
 * @method     ChildPlayercupmatchesQuery groupByGroupkey() Group by the GroupKey column
 * @method     ChildPlayercupmatchesQuery groupByHomescore() Group by the HomeScore column
 * @method     ChildPlayercupmatchesQuery groupByAwayscore() Group by the AwayScore column
 * @method     ChildPlayercupmatchesQuery groupByScheduledate() Group by the ScheduleDate column
 * @method     ChildPlayercupmatchesQuery groupByResultdate() Group by the ResultDate column
 *
 * @method     ChildPlayercupmatchesQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildPlayercupmatchesQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildPlayercupmatchesQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildPlayercupmatches findOne(ConnectionInterface $con = null) Return the first ChildPlayercupmatches matching the query
 * @method     ChildPlayercupmatches findOneOrCreate(ConnectionInterface $con = null) Return the first ChildPlayercupmatches matching the query, or a new ChildPlayercupmatches object populated from the query conditions when no match is found
 *
 * @method     ChildPlayercupmatches findOneByPlayerCupMatchPK(int $PrimaryKey) Return the first ChildPlayercupmatches filtered by the PrimaryKey column
 * @method     ChildPlayercupmatches findOneByPlayerhomekey(int $PlayerHomeKey) Return the first ChildPlayercupmatches filtered by the PlayerHomeKey column
 * @method     ChildPlayercupmatches findOneByPlayerawaykey(int $PlayerAwayKey) Return the first ChildPlayercupmatches filtered by the PlayerAwayKey column
 * @method     ChildPlayercupmatches findOneByCuproundkey(int $CupRoundKey) Return the first ChildPlayercupmatches filtered by the CupRoundKey column
 * @method     ChildPlayercupmatches findOneBySeasonkey(int $SeasonKey) Return the first ChildPlayercupmatches filtered by the SeasonKey column
 * @method     ChildPlayercupmatches findOneByGroupkey(int $GroupKey) Return the first ChildPlayercupmatches filtered by the GroupKey column
 * @method     ChildPlayercupmatches findOneByHomescore(int $HomeScore) Return the first ChildPlayercupmatches filtered by the HomeScore column
 * @method     ChildPlayercupmatches findOneByAwayscore(int $AwayScore) Return the first ChildPlayercupmatches filtered by the AwayScore column
 * @method     ChildPlayercupmatches findOneByScheduledate(string $ScheduleDate) Return the first ChildPlayercupmatches filtered by the ScheduleDate column
 * @method     ChildPlayercupmatches findOneByResultdate(string $ResultDate) Return the first ChildPlayercupmatches filtered by the ResultDate column *

 * @method     ChildPlayercupmatches requirePk($key, ConnectionInterface $con = null) Return the ChildPlayercupmatches by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayercupmatches requireOne(ConnectionInterface $con = null) Return the first ChildPlayercupmatches matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPlayercupmatches requireOneByPlayerCupMatchPK(int $PrimaryKey) Return the first ChildPlayercupmatches filtered by the PrimaryKey column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayercupmatches requireOneByPlayerhomekey(int $PlayerHomeKey) Return the first ChildPlayercupmatches filtered by the PlayerHomeKey column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayercupmatches requireOneByPlayerawaykey(int $PlayerAwayKey) Return the first ChildPlayercupmatches filtered by the PlayerAwayKey column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayercupmatches requireOneByCuproundkey(int $CupRoundKey) Return the first ChildPlayercupmatches filtered by the CupRoundKey column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayercupmatches requireOneBySeasonkey(int $SeasonKey) Return the first ChildPlayercupmatches filtered by the SeasonKey column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayercupmatches requireOneByGroupkey(int $GroupKey) Return the first ChildPlayercupmatches filtered by the GroupKey column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayercupmatches requireOneByHomescore(int $HomeScore) Return the first ChildPlayercupmatches filtered by the HomeScore column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayercupmatches requireOneByAwayscore(int $AwayScore) Return the first ChildPlayercupmatches filtered by the AwayScore column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayercupmatches requireOneByScheduledate(string $ScheduleDate) Return the first ChildPlayercupmatches filtered by the ScheduleDate column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayercupmatches requireOneByResultdate(string $ResultDate) Return the first ChildPlayercupmatches filtered by the ResultDate column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPlayercupmatches[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildPlayercupmatches objects based on current ModelCriteria
 * @method     ChildPlayercupmatches[]|ObjectCollection findByPlayerCupMatchPK(int $PrimaryKey) Return ChildPlayercupmatches objects filtered by the PrimaryKey column
 * @method     ChildPlayercupmatches[]|ObjectCollection findByPlayerhomekey(int $PlayerHomeKey) Return ChildPlayercupmatches objects filtered by the PlayerHomeKey column
 * @method     ChildPlayercupmatches[]|ObjectCollection findByPlayerawaykey(int $PlayerAwayKey) Return ChildPlayercupmatches objects filtered by the PlayerAwayKey column
 * @method     ChildPlayercupmatches[]|ObjectCollection findByCuproundkey(int $CupRoundKey) Return ChildPlayercupmatches objects filtered by the CupRoundKey column
 * @method     ChildPlayercupmatches[]|ObjectCollection findBySeasonkey(int $SeasonKey) Return ChildPlayercupmatches objects filtered by the SeasonKey column
 * @method     ChildPlayercupmatches[]|ObjectCollection findByGroupkey(int $GroupKey) Return ChildPlayercupmatches objects filtered by the GroupKey column
 * @method     ChildPlayercupmatches[]|ObjectCollection findByHomescore(int $HomeScore) Return ChildPlayercupmatches objects filtered by the HomeScore column
 * @method     ChildPlayercupmatches[]|ObjectCollection findByAwayscore(int $AwayScore) Return ChildPlayercupmatches objects filtered by the AwayScore column
 * @method     ChildPlayercupmatches[]|ObjectCollection findByScheduledate(string $ScheduleDate) Return ChildPlayercupmatches objects filtered by the ScheduleDate column
 * @method     ChildPlayercupmatches[]|ObjectCollection findByResultdate(string $ResultDate) Return ChildPlayercupmatches objects filtered by the ResultDate column
 * @method     ChildPlayercupmatches[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class PlayercupmatchesQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Base\PlayercupmatchesQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Playercupmatches', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildPlayercupmatchesQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildPlayercupmatchesQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildPlayercupmatchesQuery) {
            return $criteria;
        }
        $query = new ChildPlayercupmatchesQuery();
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
     * @return ChildPlayercupmatches|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = PlayercupmatchesTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(PlayercupmatchesTableMap::DATABASE_NAME);
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
     * @return ChildPlayercupmatches A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT PrimaryKey, PlayerHomeKey, PlayerAwayKey, CupRoundKey, SeasonKey, GroupKey, HomeScore, AwayScore, ScheduleDate, ResultDate FROM playercupmatches WHERE PrimaryKey = :p0';
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
            /** @var ChildPlayercupmatches $obj */
            $obj = new ChildPlayercupmatches();
            $obj->hydrate($row);
            PlayercupmatchesTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildPlayercupmatches|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildPlayercupmatchesQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(PlayercupmatchesTableMap::COL_PRIMARYKEY, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildPlayercupmatchesQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(PlayercupmatchesTableMap::COL_PRIMARYKEY, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the PrimaryKey column
     *
     * Example usage:
     * <code>
     * $query->filterByPlayerCupMatchPK(1234); // WHERE PrimaryKey = 1234
     * $query->filterByPlayerCupMatchPK(array(12, 34)); // WHERE PrimaryKey IN (12, 34)
     * $query->filterByPlayerCupMatchPK(array('min' => 12)); // WHERE PrimaryKey > 12
     * </code>
     *
     * @param     mixed $playerCupMatchPK The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlayercupmatchesQuery The current query, for fluid interface
     */
    public function filterByPlayerCupMatchPK($playerCupMatchPK = null, $comparison = null)
    {
        if (is_array($playerCupMatchPK)) {
            $useMinMax = false;
            if (isset($playerCupMatchPK['min'])) {
                $this->addUsingAlias(PlayercupmatchesTableMap::COL_PRIMARYKEY, $playerCupMatchPK['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($playerCupMatchPK['max'])) {
                $this->addUsingAlias(PlayercupmatchesTableMap::COL_PRIMARYKEY, $playerCupMatchPK['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayercupmatchesTableMap::COL_PRIMARYKEY, $playerCupMatchPK, $comparison);
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
     * @param     mixed $playerhomekey The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlayercupmatchesQuery The current query, for fluid interface
     */
    public function filterByPlayerhomekey($playerhomekey = null, $comparison = null)
    {
        if (is_array($playerhomekey)) {
            $useMinMax = false;
            if (isset($playerhomekey['min'])) {
                $this->addUsingAlias(PlayercupmatchesTableMap::COL_PLAYERHOMEKEY, $playerhomekey['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($playerhomekey['max'])) {
                $this->addUsingAlias(PlayercupmatchesTableMap::COL_PLAYERHOMEKEY, $playerhomekey['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayercupmatchesTableMap::COL_PLAYERHOMEKEY, $playerhomekey, $comparison);
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
     * @param     mixed $playerawaykey The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlayercupmatchesQuery The current query, for fluid interface
     */
    public function filterByPlayerawaykey($playerawaykey = null, $comparison = null)
    {
        if (is_array($playerawaykey)) {
            $useMinMax = false;
            if (isset($playerawaykey['min'])) {
                $this->addUsingAlias(PlayercupmatchesTableMap::COL_PLAYERAWAYKEY, $playerawaykey['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($playerawaykey['max'])) {
                $this->addUsingAlias(PlayercupmatchesTableMap::COL_PLAYERAWAYKEY, $playerawaykey['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayercupmatchesTableMap::COL_PLAYERAWAYKEY, $playerawaykey, $comparison);
    }

    /**
     * Filter the query on the CupRoundKey column
     *
     * Example usage:
     * <code>
     * $query->filterByCuproundkey(1234); // WHERE CupRoundKey = 1234
     * $query->filterByCuproundkey(array(12, 34)); // WHERE CupRoundKey IN (12, 34)
     * $query->filterByCuproundkey(array('min' => 12)); // WHERE CupRoundKey > 12
     * </code>
     *
     * @param     mixed $cuproundkey The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlayercupmatchesQuery The current query, for fluid interface
     */
    public function filterByCuproundkey($cuproundkey = null, $comparison = null)
    {
        if (is_array($cuproundkey)) {
            $useMinMax = false;
            if (isset($cuproundkey['min'])) {
                $this->addUsingAlias(PlayercupmatchesTableMap::COL_CUPROUNDKEY, $cuproundkey['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($cuproundkey['max'])) {
                $this->addUsingAlias(PlayercupmatchesTableMap::COL_CUPROUNDKEY, $cuproundkey['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayercupmatchesTableMap::COL_CUPROUNDKEY, $cuproundkey, $comparison);
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
     * @param     mixed $seasonkey The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlayercupmatchesQuery The current query, for fluid interface
     */
    public function filterBySeasonkey($seasonkey = null, $comparison = null)
    {
        if (is_array($seasonkey)) {
            $useMinMax = false;
            if (isset($seasonkey['min'])) {
                $this->addUsingAlias(PlayercupmatchesTableMap::COL_SEASONKEY, $seasonkey['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($seasonkey['max'])) {
                $this->addUsingAlias(PlayercupmatchesTableMap::COL_SEASONKEY, $seasonkey['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayercupmatchesTableMap::COL_SEASONKEY, $seasonkey, $comparison);
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
     * @param     mixed $groupkey The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlayercupmatchesQuery The current query, for fluid interface
     */
    public function filterByGroupkey($groupkey = null, $comparison = null)
    {
        if (is_array($groupkey)) {
            $useMinMax = false;
            if (isset($groupkey['min'])) {
                $this->addUsingAlias(PlayercupmatchesTableMap::COL_GROUPKEY, $groupkey['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($groupkey['max'])) {
                $this->addUsingAlias(PlayercupmatchesTableMap::COL_GROUPKEY, $groupkey['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayercupmatchesTableMap::COL_GROUPKEY, $groupkey, $comparison);
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
     * @return $this|ChildPlayercupmatchesQuery The current query, for fluid interface
     */
    public function filterByHomescore($homescore = null, $comparison = null)
    {
        if (is_array($homescore)) {
            $useMinMax = false;
            if (isset($homescore['min'])) {
                $this->addUsingAlias(PlayercupmatchesTableMap::COL_HOMESCORE, $homescore['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($homescore['max'])) {
                $this->addUsingAlias(PlayercupmatchesTableMap::COL_HOMESCORE, $homescore['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayercupmatchesTableMap::COL_HOMESCORE, $homescore, $comparison);
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
     * @return $this|ChildPlayercupmatchesQuery The current query, for fluid interface
     */
    public function filterByAwayscore($awayscore = null, $comparison = null)
    {
        if (is_array($awayscore)) {
            $useMinMax = false;
            if (isset($awayscore['min'])) {
                $this->addUsingAlias(PlayercupmatchesTableMap::COL_AWAYSCORE, $awayscore['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($awayscore['max'])) {
                $this->addUsingAlias(PlayercupmatchesTableMap::COL_AWAYSCORE, $awayscore['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayercupmatchesTableMap::COL_AWAYSCORE, $awayscore, $comparison);
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
     * @return $this|ChildPlayercupmatchesQuery The current query, for fluid interface
     */
    public function filterByScheduledate($scheduledate = null, $comparison = null)
    {
        if (is_array($scheduledate)) {
            $useMinMax = false;
            if (isset($scheduledate['min'])) {
                $this->addUsingAlias(PlayercupmatchesTableMap::COL_SCHEDULEDATE, $scheduledate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($scheduledate['max'])) {
                $this->addUsingAlias(PlayercupmatchesTableMap::COL_SCHEDULEDATE, $scheduledate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayercupmatchesTableMap::COL_SCHEDULEDATE, $scheduledate, $comparison);
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
     * @return $this|ChildPlayercupmatchesQuery The current query, for fluid interface
     */
    public function filterByResultdate($resultdate = null, $comparison = null)
    {
        if (is_array($resultdate)) {
            $useMinMax = false;
            if (isset($resultdate['min'])) {
                $this->addUsingAlias(PlayercupmatchesTableMap::COL_RESULTDATE, $resultdate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($resultdate['max'])) {
                $this->addUsingAlias(PlayercupmatchesTableMap::COL_RESULTDATE, $resultdate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayercupmatchesTableMap::COL_RESULTDATE, $resultdate, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   ChildPlayercupmatches $playercupmatches Object to remove from the list of results
     *
     * @return $this|ChildPlayercupmatchesQuery The current query, for fluid interface
     */
    public function prune($playercupmatches = null)
    {
        if ($playercupmatches) {
            $this->addUsingAlias(PlayercupmatchesTableMap::COL_PRIMARYKEY, $playercupmatches->getPlayerCupMatchPK(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the playercupmatches table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PlayercupmatchesTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            PlayercupmatchesTableMap::clearInstancePool();
            PlayercupmatchesTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(PlayercupmatchesTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(PlayercupmatchesTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            PlayercupmatchesTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            PlayercupmatchesTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // PlayercupmatchesQuery
