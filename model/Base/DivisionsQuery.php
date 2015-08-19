<?php

namespace Base;

use \Divisions as ChildDivisions;
use \DivisionsQuery as ChildDivisionsQuery;
use \Exception;
use \PDO;
use Map\DivisionsTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'divisions' table.
 *
 *
 *
 * @method     ChildDivisionsQuery orderByDivisionPK($order = Criteria::ASC) Order by the PrimaryKey column
 * @method     ChildDivisionsQuery orderByDescription($order = Criteria::ASC) Order by the Description column
 * @method     ChildDivisionsQuery orderByCode($order = Criteria::ASC) Order by the Code column
 * @method     ChildDivisionsQuery orderByOrder($order = Criteria::ASC) Order by the Order column
 *
 * @method     ChildDivisionsQuery groupByDivisionPK() Group by the PrimaryKey column
 * @method     ChildDivisionsQuery groupByDescription() Group by the Description column
 * @method     ChildDivisionsQuery groupByCode() Group by the Code column
 * @method     ChildDivisionsQuery groupByOrder() Group by the Order column
 *
 * @method     ChildDivisionsQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildDivisionsQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildDivisionsQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildDivisionsQuery leftJoinPlayerdivisionmatches($relationAlias = null) Adds a LEFT JOIN clause to the query using the Playerdivisionmatches relation
 * @method     ChildDivisionsQuery rightJoinPlayerdivisionmatches($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Playerdivisionmatches relation
 * @method     ChildDivisionsQuery innerJoinPlayerdivisionmatches($relationAlias = null) Adds a INNER JOIN clause to the query using the Playerdivisionmatches relation
 *
 * @method     ChildDivisionsQuery leftJoinPlayerdivisionranking($relationAlias = null) Adds a LEFT JOIN clause to the query using the Playerdivisionranking relation
 * @method     ChildDivisionsQuery rightJoinPlayerdivisionranking($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Playerdivisionranking relation
 * @method     ChildDivisionsQuery innerJoinPlayerdivisionranking($relationAlias = null) Adds a INNER JOIN clause to the query using the Playerdivisionranking relation
 *
 * @method     \PlayerdivisionmatchesQuery|\PlayerdivisionrankingQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildDivisions findOne(ConnectionInterface $con = null) Return the first ChildDivisions matching the query
 * @method     ChildDivisions findOneOrCreate(ConnectionInterface $con = null) Return the first ChildDivisions matching the query, or a new ChildDivisions object populated from the query conditions when no match is found
 *
 * @method     ChildDivisions findOneByDivisionPK(int $PrimaryKey) Return the first ChildDivisions filtered by the PrimaryKey column
 * @method     ChildDivisions findOneByDescription(string $Description) Return the first ChildDivisions filtered by the Description column
 * @method     ChildDivisions findOneByCode(string $Code) Return the first ChildDivisions filtered by the Code column
 * @method     ChildDivisions findOneByOrder(int $Order) Return the first ChildDivisions filtered by the Order column *

 * @method     ChildDivisions requirePk($key, ConnectionInterface $con = null) Return the ChildDivisions by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildDivisions requireOne(ConnectionInterface $con = null) Return the first ChildDivisions matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildDivisions requireOneByDivisionPK(int $PrimaryKey) Return the first ChildDivisions filtered by the PrimaryKey column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildDivisions requireOneByDescription(string $Description) Return the first ChildDivisions filtered by the Description column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildDivisions requireOneByCode(string $Code) Return the first ChildDivisions filtered by the Code column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildDivisions requireOneByOrder(int $Order) Return the first ChildDivisions filtered by the Order column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildDivisions[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildDivisions objects based on current ModelCriteria
 * @method     ChildDivisions[]|ObjectCollection findByDivisionPK(int $PrimaryKey) Return ChildDivisions objects filtered by the PrimaryKey column
 * @method     ChildDivisions[]|ObjectCollection findByDescription(string $Description) Return ChildDivisions objects filtered by the Description column
 * @method     ChildDivisions[]|ObjectCollection findByCode(string $Code) Return ChildDivisions objects filtered by the Code column
 * @method     ChildDivisions[]|ObjectCollection findByOrder(int $Order) Return ChildDivisions objects filtered by the Order column
 * @method     ChildDivisions[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class DivisionsQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Base\DivisionsQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Divisions', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildDivisionsQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildDivisionsQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildDivisionsQuery) {
            return $criteria;
        }
        $query = new ChildDivisionsQuery();
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
     * @return ChildDivisions|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = DivisionsTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(DivisionsTableMap::DATABASE_NAME);
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
     * @return ChildDivisions A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT PrimaryKey, Description, Code, Order FROM divisions WHERE PrimaryKey = :p0';
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
            /** @var ChildDivisions $obj */
            $obj = new ChildDivisions();
            $obj->hydrate($row);
            DivisionsTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildDivisions|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildDivisionsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(DivisionsTableMap::COL_PRIMARYKEY, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildDivisionsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(DivisionsTableMap::COL_PRIMARYKEY, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the PrimaryKey column
     *
     * Example usage:
     * <code>
     * $query->filterByDivisionPK(1234); // WHERE PrimaryKey = 1234
     * $query->filterByDivisionPK(array(12, 34)); // WHERE PrimaryKey IN (12, 34)
     * $query->filterByDivisionPK(array('min' => 12)); // WHERE PrimaryKey > 12
     * </code>
     *
     * @param     mixed $divisionPK The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildDivisionsQuery The current query, for fluid interface
     */
    public function filterByDivisionPK($divisionPK = null, $comparison = null)
    {
        if (is_array($divisionPK)) {
            $useMinMax = false;
            if (isset($divisionPK['min'])) {
                $this->addUsingAlias(DivisionsTableMap::COL_PRIMARYKEY, $divisionPK['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($divisionPK['max'])) {
                $this->addUsingAlias(DivisionsTableMap::COL_PRIMARYKEY, $divisionPK['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DivisionsTableMap::COL_PRIMARYKEY, $divisionPK, $comparison);
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
     * @return $this|ChildDivisionsQuery The current query, for fluid interface
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

        return $this->addUsingAlias(DivisionsTableMap::COL_DESCRIPTION, $description, $comparison);
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
     * @return $this|ChildDivisionsQuery The current query, for fluid interface
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

        return $this->addUsingAlias(DivisionsTableMap::COL_CODE, $code, $comparison);
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
     * @return $this|ChildDivisionsQuery The current query, for fluid interface
     */
    public function filterByOrder($order = null, $comparison = null)
    {
        if (is_array($order)) {
            $useMinMax = false;
            if (isset($order['min'])) {
                $this->addUsingAlias(DivisionsTableMap::COL_ORDER, $order['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($order['max'])) {
                $this->addUsingAlias(DivisionsTableMap::COL_ORDER, $order['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DivisionsTableMap::COL_ORDER, $order, $comparison);
    }

    /**
     * Filter the query by a related \Playerdivisionmatches object
     *
     * @param \Playerdivisionmatches|ObjectCollection $playerdivisionmatches the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildDivisionsQuery The current query, for fluid interface
     */
    public function filterByPlayerdivisionmatches($playerdivisionmatches, $comparison = null)
    {
        if ($playerdivisionmatches instanceof \Playerdivisionmatches) {
            return $this
                ->addUsingAlias(DivisionsTableMap::COL_PRIMARYKEY, $playerdivisionmatches->getDivisionkey(), $comparison);
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
     * @return $this|ChildDivisionsQuery The current query, for fluid interface
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
     * @return ChildDivisionsQuery The current query, for fluid interface
     */
    public function filterByPlayerdivisionranking($playerdivisionranking, $comparison = null)
    {
        if ($playerdivisionranking instanceof \Playerdivisionranking) {
            return $this
                ->addUsingAlias(DivisionsTableMap::COL_PRIMARYKEY, $playerdivisionranking->getDivisionkey(), $comparison);
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
     * @return $this|ChildDivisionsQuery The current query, for fluid interface
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
     * @param   ChildDivisions $divisions Object to remove from the list of results
     *
     * @return $this|ChildDivisionsQuery The current query, for fluid interface
     */
    public function prune($divisions = null)
    {
        if ($divisions) {
            $this->addUsingAlias(DivisionsTableMap::COL_PRIMARYKEY, $divisions->getDivisionPK(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the divisions table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(DivisionsTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            DivisionsTableMap::clearInstancePool();
            DivisionsTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(DivisionsTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(DivisionsTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            DivisionsTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            DivisionsTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // DivisionsQuery
