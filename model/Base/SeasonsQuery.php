<?php

namespace Base;

use \Seasons as ChildSeasons;
use \SeasonsQuery as ChildSeasonsQuery;
use \Exception;
use \PDO;
use Map\SeasonsTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'seasons' table.
 *
 *
 *
 * @method     ChildSeasonsQuery orderBySeasonPK($order = Criteria::ASC) Order by the PrimaryKey column
 * @method     ChildSeasonsQuery orderByDescription($order = Criteria::ASC) Order by the Description column
 * @method     ChildSeasonsQuery orderByCode($order = Criteria::ASC) Order by the Code column
 * @method     ChildSeasonsQuery orderByOrder($order = Criteria::ASC) Order by the Order column
 * @method     ChildSeasonsQuery orderByCompetitionkey($order = Criteria::ASC) Order by the CompetitionKey column
 *
 * @method     ChildSeasonsQuery groupBySeasonPK() Group by the PrimaryKey column
 * @method     ChildSeasonsQuery groupByDescription() Group by the Description column
 * @method     ChildSeasonsQuery groupByCode() Group by the Code column
 * @method     ChildSeasonsQuery groupByOrder() Group by the Order column
 * @method     ChildSeasonsQuery groupByCompetitionkey() Group by the CompetitionKey column
 *
 * @method     ChildSeasonsQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildSeasonsQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildSeasonsQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildSeasonsQuery leftJoinPlayercupmatches($relationAlias = null) Adds a LEFT JOIN clause to the query using the Playercupmatches relation
 * @method     ChildSeasonsQuery rightJoinPlayercupmatches($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Playercupmatches relation
 * @method     ChildSeasonsQuery innerJoinPlayercupmatches($relationAlias = null) Adds a INNER JOIN clause to the query using the Playercupmatches relation
 *
 * @method     ChildSeasonsQuery leftJoinPlayerdivisionmatches($relationAlias = null) Adds a LEFT JOIN clause to the query using the Playerdivisionmatches relation
 * @method     ChildSeasonsQuery rightJoinPlayerdivisionmatches($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Playerdivisionmatches relation
 * @method     ChildSeasonsQuery innerJoinPlayerdivisionmatches($relationAlias = null) Adds a INNER JOIN clause to the query using the Playerdivisionmatches relation
 *
 * @method     ChildSeasonsQuery leftJoinPlayerdivisionranking($relationAlias = null) Adds a LEFT JOIN clause to the query using the Playerdivisionranking relation
 * @method     ChildSeasonsQuery rightJoinPlayerdivisionranking($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Playerdivisionranking relation
 * @method     ChildSeasonsQuery innerJoinPlayerdivisionranking($relationAlias = null) Adds a INNER JOIN clause to the query using the Playerdivisionranking relation
 *
 * @method     \PlayercupmatchesQuery|\PlayerdivisionmatchesQuery|\PlayerdivisionrankingQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildSeasons findOne(ConnectionInterface $con = null) Return the first ChildSeasons matching the query
 * @method     ChildSeasons findOneOrCreate(ConnectionInterface $con = null) Return the first ChildSeasons matching the query, or a new ChildSeasons object populated from the query conditions when no match is found
 *
 * @method     ChildSeasons findOneBySeasonPK(int $PrimaryKey) Return the first ChildSeasons filtered by the PrimaryKey column
 * @method     ChildSeasons findOneByDescription(string $Description) Return the first ChildSeasons filtered by the Description column
 * @method     ChildSeasons findOneByCode(string $Code) Return the first ChildSeasons filtered by the Code column
 * @method     ChildSeasons findOneByOrder(int $Order) Return the first ChildSeasons filtered by the Order column
 * @method     ChildSeasons findOneByCompetitionkey(int $CompetitionKey) Return the first ChildSeasons filtered by the CompetitionKey column *

 * @method     ChildSeasons requirePk($key, ConnectionInterface $con = null) Return the ChildSeasons by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSeasons requireOne(ConnectionInterface $con = null) Return the first ChildSeasons matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildSeasons requireOneBySeasonPK(int $PrimaryKey) Return the first ChildSeasons filtered by the PrimaryKey column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSeasons requireOneByDescription(string $Description) Return the first ChildSeasons filtered by the Description column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSeasons requireOneByCode(string $Code) Return the first ChildSeasons filtered by the Code column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSeasons requireOneByOrder(int $Order) Return the first ChildSeasons filtered by the Order column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSeasons requireOneByCompetitionkey(int $CompetitionKey) Return the first ChildSeasons filtered by the CompetitionKey column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildSeasons[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildSeasons objects based on current ModelCriteria
 * @method     ChildSeasons[]|ObjectCollection findBySeasonPK(int $PrimaryKey) Return ChildSeasons objects filtered by the PrimaryKey column
 * @method     ChildSeasons[]|ObjectCollection findByDescription(string $Description) Return ChildSeasons objects filtered by the Description column
 * @method     ChildSeasons[]|ObjectCollection findByCode(string $Code) Return ChildSeasons objects filtered by the Code column
 * @method     ChildSeasons[]|ObjectCollection findByOrder(int $Order) Return ChildSeasons objects filtered by the Order column
 * @method     ChildSeasons[]|ObjectCollection findByCompetitionkey(int $CompetitionKey) Return ChildSeasons objects filtered by the CompetitionKey column
 * @method     ChildSeasons[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class SeasonsQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Base\SeasonsQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Seasons', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildSeasonsQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildSeasonsQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildSeasonsQuery) {
            return $criteria;
        }
        $query = new ChildSeasonsQuery();
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
     * @return ChildSeasons|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = SeasonsTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(SeasonsTableMap::DATABASE_NAME);
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
     * @return ChildSeasons A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT PrimaryKey, Description, Code, Order, CompetitionKey FROM seasons WHERE PrimaryKey = :p0';
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
            /** @var ChildSeasons $obj */
            $obj = new ChildSeasons();
            $obj->hydrate($row);
            SeasonsTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildSeasons|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildSeasonsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(SeasonsTableMap::COL_PRIMARYKEY, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildSeasonsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(SeasonsTableMap::COL_PRIMARYKEY, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the PrimaryKey column
     *
     * Example usage:
     * <code>
     * $query->filterBySeasonPK(1234); // WHERE PrimaryKey = 1234
     * $query->filterBySeasonPK(array(12, 34)); // WHERE PrimaryKey IN (12, 34)
     * $query->filterBySeasonPK(array('min' => 12)); // WHERE PrimaryKey > 12
     * </code>
     *
     * @param     mixed $seasonPK The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSeasonsQuery The current query, for fluid interface
     */
    public function filterBySeasonPK($seasonPK = null, $comparison = null)
    {
        if (is_array($seasonPK)) {
            $useMinMax = false;
            if (isset($seasonPK['min'])) {
                $this->addUsingAlias(SeasonsTableMap::COL_PRIMARYKEY, $seasonPK['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($seasonPK['max'])) {
                $this->addUsingAlias(SeasonsTableMap::COL_PRIMARYKEY, $seasonPK['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SeasonsTableMap::COL_PRIMARYKEY, $seasonPK, $comparison);
    }

    /**
     * Filter the query on the Description column
     *
     * Example usage:
     * <code>
     * $query->filterByDescription('fooValue');   // WHERE Description = 'fooValue'
     * $query->filterByDescription('%fooValue%'); // WHERE Description LIKE '%fooValue%'
     * </code>
     *
     * @param     string $description The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSeasonsQuery The current query, for fluid interface
     */
    public function filterByDescription($description = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($description)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $description)) {
                $description = str_replace('*', '%', $description);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(SeasonsTableMap::COL_DESCRIPTION, $description, $comparison);
    }

    /**
     * Filter the query on the Code column
     *
     * Example usage:
     * <code>
     * $query->filterByCode('fooValue');   // WHERE Code = 'fooValue'
     * $query->filterByCode('%fooValue%'); // WHERE Code LIKE '%fooValue%'
     * </code>
     *
     * @param     string $code The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSeasonsQuery The current query, for fluid interface
     */
    public function filterByCode($code = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($code)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $code)) {
                $code = str_replace('*', '%', $code);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(SeasonsTableMap::COL_CODE, $code, $comparison);
    }

    /**
     * Filter the query on the Order column
     *
     * Example usage:
     * <code>
     * $query->filterByOrder(1234); // WHERE Order = 1234
     * $query->filterByOrder(array(12, 34)); // WHERE Order IN (12, 34)
     * $query->filterByOrder(array('min' => 12)); // WHERE Order > 12
     * </code>
     *
     * @param     mixed $order The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSeasonsQuery The current query, for fluid interface
     */
    public function filterByOrder($order = null, $comparison = null)
    {
        if (is_array($order)) {
            $useMinMax = false;
            if (isset($order['min'])) {
                $this->addUsingAlias(SeasonsTableMap::COL_ORDER, $order['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($order['max'])) {
                $this->addUsingAlias(SeasonsTableMap::COL_ORDER, $order['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SeasonsTableMap::COL_ORDER, $order, $comparison);
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
     * @param     mixed $competitionkey The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSeasonsQuery The current query, for fluid interface
     */
    public function filterByCompetitionkey($competitionkey = null, $comparison = null)
    {
        if (is_array($competitionkey)) {
            $useMinMax = false;
            if (isset($competitionkey['min'])) {
                $this->addUsingAlias(SeasonsTableMap::COL_COMPETITIONKEY, $competitionkey['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($competitionkey['max'])) {
                $this->addUsingAlias(SeasonsTableMap::COL_COMPETITIONKEY, $competitionkey['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SeasonsTableMap::COL_COMPETITIONKEY, $competitionkey, $comparison);
    }

    /**
     * Filter the query by a related \Playercupmatches object
     *
     * @param \Playercupmatches|ObjectCollection $playercupmatches the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildSeasonsQuery The current query, for fluid interface
     */
    public function filterByPlayercupmatches($playercupmatches, $comparison = null)
    {
        if ($playercupmatches instanceof \Playercupmatches) {
            return $this
                ->addUsingAlias(SeasonsTableMap::COL_PRIMARYKEY, $playercupmatches->getSeasonkey(), $comparison);
        } elseif ($playercupmatches instanceof ObjectCollection) {
            return $this
                ->usePlayercupmatchesQuery()
                ->filterByPrimaryKeys($playercupmatches->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPlayercupmatches() only accepts arguments of type \Playercupmatches or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Playercupmatches relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildSeasonsQuery The current query, for fluid interface
     */
    public function joinPlayercupmatches($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Playercupmatches');

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
            $this->addJoinObject($join, 'Playercupmatches');
        }

        return $this;
    }

    /**
     * Use the Playercupmatches relation Playercupmatches object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \PlayercupmatchesQuery A secondary query class using the current class as primary query
     */
    public function usePlayercupmatchesQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPlayercupmatches($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Playercupmatches', '\PlayercupmatchesQuery');
    }

    /**
     * Filter the query by a related \Playerdivisionmatches object
     *
     * @param \Playerdivisionmatches|ObjectCollection $playerdivisionmatches the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildSeasonsQuery The current query, for fluid interface
     */
    public function filterByPlayerdivisionmatches($playerdivisionmatches, $comparison = null)
    {
        if ($playerdivisionmatches instanceof \Playerdivisionmatches) {
            return $this
                ->addUsingAlias(SeasonsTableMap::COL_PRIMARYKEY, $playerdivisionmatches->getSeasonkey(), $comparison);
        } elseif ($playerdivisionmatches instanceof ObjectCollection) {
            return $this
                ->usePlayerdivisionmatchesQuery()
                ->filterByPrimaryKeys($playerdivisionmatches->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPlayerdivisionmatches() only accepts arguments of type \Playerdivisionmatches or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Playerdivisionmatches relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildSeasonsQuery The current query, for fluid interface
     */
    public function joinPlayerdivisionmatches($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Playerdivisionmatches');

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
            $this->addJoinObject($join, 'Playerdivisionmatches');
        }

        return $this;
    }

    /**
     * Use the Playerdivisionmatches relation Playerdivisionmatches object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \PlayerdivisionmatchesQuery A secondary query class using the current class as primary query
     */
    public function usePlayerdivisionmatchesQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPlayerdivisionmatches($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Playerdivisionmatches', '\PlayerdivisionmatchesQuery');
    }

    /**
     * Filter the query by a related \Playerdivisionranking object
     *
     * @param \Playerdivisionranking|ObjectCollection $playerdivisionranking the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildSeasonsQuery The current query, for fluid interface
     */
    public function filterByPlayerdivisionranking($playerdivisionranking, $comparison = null)
    {
        if ($playerdivisionranking instanceof \Playerdivisionranking) {
            return $this
                ->addUsingAlias(SeasonsTableMap::COL_PRIMARYKEY, $playerdivisionranking->getSeasonkey(), $comparison);
        } elseif ($playerdivisionranking instanceof ObjectCollection) {
            return $this
                ->usePlayerdivisionrankingQuery()
                ->filterByPrimaryKeys($playerdivisionranking->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPlayerdivisionranking() only accepts arguments of type \Playerdivisionranking or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Playerdivisionranking relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildSeasonsQuery The current query, for fluid interface
     */
    public function joinPlayerdivisionranking($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Playerdivisionranking');

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
            $this->addJoinObject($join, 'Playerdivisionranking');
        }

        return $this;
    }

    /**
     * Use the Playerdivisionranking relation Playerdivisionranking object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \PlayerdivisionrankingQuery A secondary query class using the current class as primary query
     */
    public function usePlayerdivisionrankingQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPlayerdivisionranking($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Playerdivisionranking', '\PlayerdivisionrankingQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildSeasons $seasons Object to remove from the list of results
     *
     * @return $this|ChildSeasonsQuery The current query, for fluid interface
     */
    public function prune($seasons = null)
    {
        if ($seasons) {
            $this->addUsingAlias(SeasonsTableMap::COL_PRIMARYKEY, $seasons->getSeasonPK(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the seasons table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(SeasonsTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            SeasonsTableMap::clearInstancePool();
            SeasonsTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(SeasonsTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(SeasonsTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            SeasonsTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            SeasonsTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // SeasonsQuery
