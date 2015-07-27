<?php

namespace Base;

use \Connectedusers as ChildConnectedusers;
use \ConnectedusersQuery as ChildConnectedusersQuery;
use \Exception;
use \PDO;
use Map\ConnectedusersTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'connectedusers' table.
 *
 *
 *
 * @method     ChildConnectedusersQuery orderByVisitedate($order = Criteria::ASC) Order by the VisiteDate column
 * @method     ChildConnectedusersQuery orderByUseruniqueid($order = Criteria::ASC) Order by the UserUniqueId column
 * @method     ChildConnectedusersQuery orderByPlayerkey($order = Criteria::ASC) Order by the PlayerKey column
 *
 * @method     ChildConnectedusersQuery groupByVisitedate() Group by the VisiteDate column
 * @method     ChildConnectedusersQuery groupByUseruniqueid() Group by the UserUniqueId column
 * @method     ChildConnectedusersQuery groupByPlayerkey() Group by the PlayerKey column
 *
 * @method     ChildConnectedusersQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildConnectedusersQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildConnectedusersQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildConnectedusers findOne(ConnectionInterface $con = null) Return the first ChildConnectedusers matching the query
 * @method     ChildConnectedusers findOneOrCreate(ConnectionInterface $con = null) Return the first ChildConnectedusers matching the query, or a new ChildConnectedusers object populated from the query conditions when no match is found
 *
 * @method     ChildConnectedusers findOneByVisitedate(string $VisiteDate) Return the first ChildConnectedusers filtered by the VisiteDate column
 * @method     ChildConnectedusers findOneByUseruniqueid(string $UserUniqueId) Return the first ChildConnectedusers filtered by the UserUniqueId column
 * @method     ChildConnectedusers findOneByPlayerkey(int $PlayerKey) Return the first ChildConnectedusers filtered by the PlayerKey column *

 * @method     ChildConnectedusers requirePk($key, ConnectionInterface $con = null) Return the ChildConnectedusers by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildConnectedusers requireOne(ConnectionInterface $con = null) Return the first ChildConnectedusers matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildConnectedusers requireOneByVisitedate(string $VisiteDate) Return the first ChildConnectedusers filtered by the VisiteDate column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildConnectedusers requireOneByUseruniqueid(string $UserUniqueId) Return the first ChildConnectedusers filtered by the UserUniqueId column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildConnectedusers requireOneByPlayerkey(int $PlayerKey) Return the first ChildConnectedusers filtered by the PlayerKey column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildConnectedusers[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildConnectedusers objects based on current ModelCriteria
 * @method     ChildConnectedusers[]|ObjectCollection findByVisitedate(string $VisiteDate) Return ChildConnectedusers objects filtered by the VisiteDate column
 * @method     ChildConnectedusers[]|ObjectCollection findByUseruniqueid(string $UserUniqueId) Return ChildConnectedusers objects filtered by the UserUniqueId column
 * @method     ChildConnectedusers[]|ObjectCollection findByPlayerkey(int $PlayerKey) Return ChildConnectedusers objects filtered by the PlayerKey column
 * @method     ChildConnectedusers[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class ConnectedusersQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Base\ConnectedusersQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Connectedusers', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildConnectedusersQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildConnectedusersQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildConnectedusersQuery) {
            return $criteria;
        }
        $query = new ChildConnectedusersQuery();
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
     * @return ChildConnectedusers|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = ConnectedusersTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(ConnectedusersTableMap::DATABASE_NAME);
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
     * @return ChildConnectedusers A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT VisiteDate, UserUniqueId, PlayerKey FROM connectedusers WHERE UserUniqueId = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_STR);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildConnectedusers $obj */
            $obj = new ChildConnectedusers();
            $obj->hydrate($row);
            ConnectedusersTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildConnectedusers|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildConnectedusersQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(ConnectedusersTableMap::COL_USERUNIQUEID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildConnectedusersQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(ConnectedusersTableMap::COL_USERUNIQUEID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the VisiteDate column
     *
     * Example usage:
     * <code>
     * $query->filterByVisitedate('2011-03-14'); // WHERE VisiteDate = '2011-03-14'
     * $query->filterByVisitedate('now'); // WHERE VisiteDate = '2011-03-14'
     * $query->filterByVisitedate(array('max' => 'yesterday')); // WHERE VisiteDate > '2011-03-13'
     * </code>
     *
     * @param     mixed $visitedate The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildConnectedusersQuery The current query, for fluid interface
     */
    public function filterByVisitedate($visitedate = null, $comparison = null)
    {
        if (is_array($visitedate)) {
            $useMinMax = false;
            if (isset($visitedate['min'])) {
                $this->addUsingAlias(ConnectedusersTableMap::COL_VISITEDATE, $visitedate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($visitedate['max'])) {
                $this->addUsingAlias(ConnectedusersTableMap::COL_VISITEDATE, $visitedate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ConnectedusersTableMap::COL_VISITEDATE, $visitedate, $comparison);
    }

    /**
     * Filter the query on the UserUniqueId column
     *
     * Example usage:
     * <code>
     * $query->filterByUseruniqueid('fooValue');   // WHERE UserUniqueId = 'fooValue'
     * $query->filterByUseruniqueid('%fooValue%'); // WHERE UserUniqueId LIKE '%fooValue%'
     * </code>
     *
     * @param     string $useruniqueid The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildConnectedusersQuery The current query, for fluid interface
     */
    public function filterByUseruniqueid($useruniqueid = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($useruniqueid)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $useruniqueid)) {
                $useruniqueid = str_replace('*', '%', $useruniqueid);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ConnectedusersTableMap::COL_USERUNIQUEID, $useruniqueid, $comparison);
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
     * @return $this|ChildConnectedusersQuery The current query, for fluid interface
     */
    public function filterByPlayerkey($playerkey = null, $comparison = null)
    {
        if (is_array($playerkey)) {
            $useMinMax = false;
            if (isset($playerkey['min'])) {
                $this->addUsingAlias(ConnectedusersTableMap::COL_PLAYERKEY, $playerkey['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($playerkey['max'])) {
                $this->addUsingAlias(ConnectedusersTableMap::COL_PLAYERKEY, $playerkey['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ConnectedusersTableMap::COL_PLAYERKEY, $playerkey, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   ChildConnectedusers $connectedusers Object to remove from the list of results
     *
     * @return $this|ChildConnectedusersQuery The current query, for fluid interface
     */
    public function prune($connectedusers = null)
    {
        if ($connectedusers) {
            $this->addUsingAlias(ConnectedusersTableMap::COL_USERUNIQUEID, $connectedusers->getUseruniqueid(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the connectedusers table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ConnectedusersTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            ConnectedusersTableMap::clearInstancePool();
            ConnectedusersTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(ConnectedusersTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(ConnectedusersTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            ConnectedusersTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            ConnectedusersTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // ConnectedusersQuery
