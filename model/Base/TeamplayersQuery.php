<?php

namespace Base;

use \Teamplayers as ChildTeamplayers;
use \TeamplayersQuery as ChildTeamplayersQuery;
use \Exception;
use \PDO;
use Map\TeamplayersTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'teamplayers' table.
 *
 *
 *
 * @method     ChildTeamplayersQuery orderByTeamPlayerPK($order = Criteria::ASC) Order by the PrimaryKey column
 * @method     ChildTeamplayersQuery orderByFullname($order = Criteria::ASC) Order by the FullName column
 *
 * @method     ChildTeamplayersQuery groupByTeamPlayerPK() Group by the PrimaryKey column
 * @method     ChildTeamplayersQuery groupByFullname() Group by the FullName column
 *
 * @method     ChildTeamplayersQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildTeamplayersQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildTeamplayersQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildTeamplayersQuery leftJoinEvents($relationAlias = null) Adds a LEFT JOIN clause to the query using the Events relation
 * @method     ChildTeamplayersQuery rightJoinEvents($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Events relation
 * @method     ChildTeamplayersQuery innerJoinEvents($relationAlias = null) Adds a INNER JOIN clause to the query using the Events relation
 *
 * @method     ChildTeamplayersQuery leftJoinLineups($relationAlias = null) Adds a LEFT JOIN clause to the query using the Lineups relation
 * @method     ChildTeamplayersQuery rightJoinLineups($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Lineups relation
 * @method     ChildTeamplayersQuery innerJoinLineups($relationAlias = null) Adds a INNER JOIN clause to the query using the Lineups relation
 *
 * @method     \EventsQuery|\LineupsQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildTeamplayers findOne(ConnectionInterface $con = null) Return the first ChildTeamplayers matching the query
 * @method     ChildTeamplayers findOneOrCreate(ConnectionInterface $con = null) Return the first ChildTeamplayers matching the query, or a new ChildTeamplayers object populated from the query conditions when no match is found
 *
 * @method     ChildTeamplayers findOneByTeamPlayerPK(int $PrimaryKey) Return the first ChildTeamplayers filtered by the PrimaryKey column
 * @method     ChildTeamplayers findOneByFullname(string $FullName) Return the first ChildTeamplayers filtered by the FullName column *

 * @method     ChildTeamplayers requirePk($key, ConnectionInterface $con = null) Return the ChildTeamplayers by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildTeamplayers requireOne(ConnectionInterface $con = null) Return the first ChildTeamplayers matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildTeamplayers requireOneByTeamPlayerPK(int $PrimaryKey) Return the first ChildTeamplayers filtered by the PrimaryKey column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildTeamplayers requireOneByFullname(string $FullName) Return the first ChildTeamplayers filtered by the FullName column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildTeamplayers[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildTeamplayers objects based on current ModelCriteria
 * @method     ChildTeamplayers[]|ObjectCollection findByTeamPlayerPK(int $PrimaryKey) Return ChildTeamplayers objects filtered by the PrimaryKey column
 * @method     ChildTeamplayers[]|ObjectCollection findByFullname(string $FullName) Return ChildTeamplayers objects filtered by the FullName column
 * @method     ChildTeamplayers[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class TeamplayersQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Base\TeamplayersQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Teamplayers', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildTeamplayersQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildTeamplayersQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildTeamplayersQuery) {
            return $criteria;
        }
        $query = new ChildTeamplayersQuery();
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
     * @return ChildTeamplayers|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = TeamplayersTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(TeamplayersTableMap::DATABASE_NAME);
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
     * @return ChildTeamplayers A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT PrimaryKey, FullName FROM teamplayers WHERE PrimaryKey = :p0';
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
            /** @var ChildTeamplayers $obj */
            $obj = new ChildTeamplayers();
            $obj->hydrate($row);
            TeamplayersTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildTeamplayers|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildTeamplayersQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(TeamplayersTableMap::COL_PRIMARYKEY, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildTeamplayersQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(TeamplayersTableMap::COL_PRIMARYKEY, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the PrimaryKey column
     *
     * Example usage:
     * <code>
     * $query->filterByTeamPlayerPK(1234); // WHERE PrimaryKey = 1234
     * $query->filterByTeamPlayerPK(array(12, 34)); // WHERE PrimaryKey IN (12, 34)
     * $query->filterByTeamPlayerPK(array('min' => 12)); // WHERE PrimaryKey > 12
     * </code>
     *
     * @param     mixed $teamPlayerPK The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildTeamplayersQuery The current query, for fluid interface
     */
    public function filterByTeamPlayerPK($teamPlayerPK = null, $comparison = null)
    {
        if (is_array($teamPlayerPK)) {
            $useMinMax = false;
            if (isset($teamPlayerPK['min'])) {
                $this->addUsingAlias(TeamplayersTableMap::COL_PRIMARYKEY, $teamPlayerPK['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($teamPlayerPK['max'])) {
                $this->addUsingAlias(TeamplayersTableMap::COL_PRIMARYKEY, $teamPlayerPK['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TeamplayersTableMap::COL_PRIMARYKEY, $teamPlayerPK, $comparison);
    }

    /**
     * Filter the query on the FullName column
     *
     * Example usage:
     * <code>
     * $query->filterByFullname('fooValue');   // WHERE FullName = 'fooValue'
     * $query->filterByFullname('%fooValue%'); // WHERE FullName LIKE '%fooValue%'
     * </code>
     *
     * @param     string $fullname The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildTeamplayersQuery The current query, for fluid interface
     */
    public function filterByFullname($fullname = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($fullname)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $fullname)) {
                $fullname = str_replace('*', '%', $fullname);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(TeamplayersTableMap::COL_FULLNAME, $fullname, $comparison);
    }

    /**
     * Filter the query by a related \Events object
     *
     * @param \Events|ObjectCollection $events the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildTeamplayersQuery The current query, for fluid interface
     */
    public function filterByEvents($events, $comparison = null)
    {
        if ($events instanceof \Events) {
            return $this
                ->addUsingAlias(TeamplayersTableMap::COL_PRIMARYKEY, $events->getTeamplayerkey(), $comparison);
        } elseif ($events instanceof ObjectCollection) {
            return $this
                ->useEventsQuery()
                ->filterByPrimaryKeys($events->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByEvents() only accepts arguments of type \Events or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Events relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildTeamplayersQuery The current query, for fluid interface
     */
    public function joinEvents($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Events');

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
            $this->addJoinObject($join, 'Events');
        }

        return $this;
    }

    /**
     * Use the Events relation Events object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \EventsQuery A secondary query class using the current class as primary query
     */
    public function useEventsQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinEvents($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Events', '\EventsQuery');
    }

    /**
     * Filter the query by a related \Lineups object
     *
     * @param \Lineups|ObjectCollection $lineups the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildTeamplayersQuery The current query, for fluid interface
     */
    public function filterByLineups($lineups, $comparison = null)
    {
        if ($lineups instanceof \Lineups) {
            return $this
                ->addUsingAlias(TeamplayersTableMap::COL_PRIMARYKEY, $lineups->getTeamplayerkey(), $comparison);
        } elseif ($lineups instanceof ObjectCollection) {
            return $this
                ->useLineupsQuery()
                ->filterByPrimaryKeys($lineups->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByLineups() only accepts arguments of type \Lineups or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Lineups relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildTeamplayersQuery The current query, for fluid interface
     */
    public function joinLineups($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Lineups');

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
            $this->addJoinObject($join, 'Lineups');
        }

        return $this;
    }

    /**
     * Use the Lineups relation Lineups object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \LineupsQuery A secondary query class using the current class as primary query
     */
    public function useLineupsQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinLineups($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Lineups', '\LineupsQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildTeamplayers $teamplayers Object to remove from the list of results
     *
     * @return $this|ChildTeamplayersQuery The current query, for fluid interface
     */
    public function prune($teamplayers = null)
    {
        if ($teamplayers) {
            $this->addUsingAlias(TeamplayersTableMap::COL_PRIMARYKEY, $teamplayers->getTeamPlayerPK(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the teamplayers table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(TeamplayersTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            TeamplayersTableMap::clearInstancePool();
            TeamplayersTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(TeamplayersTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(TeamplayersTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            TeamplayersTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            TeamplayersTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // TeamplayersQuery
