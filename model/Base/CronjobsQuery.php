<?php

namespace Base;

use \Cronjobs as ChildCronjobs;
use \CronjobsQuery as ChildCronjobsQuery;
use \Exception;
use \PDO;
use Map\CronjobsTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'cronjobs' table.
 *
 *
 *
 * @method     ChildCronjobsQuery orderByJobname($order = Criteria::ASC) Order by the JobName column
 * @method     ChildCronjobsQuery orderByLastexecution($order = Criteria::ASC) Order by the LastExecution column
 * @method     ChildCronjobsQuery orderByLaststatus($order = Criteria::ASC) Order by the LastStatus column
 * @method     ChildCronjobsQuery orderByLastexecutioninformation($order = Criteria::ASC) Order by the LastExecutionInformation column
 *
 * @method     ChildCronjobsQuery groupByJobname() Group by the JobName column
 * @method     ChildCronjobsQuery groupByLastexecution() Group by the LastExecution column
 * @method     ChildCronjobsQuery groupByLaststatus() Group by the LastStatus column
 * @method     ChildCronjobsQuery groupByLastexecutioninformation() Group by the LastExecutionInformation column
 *
 * @method     ChildCronjobsQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildCronjobsQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildCronjobsQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildCronjobs findOne(ConnectionInterface $con = null) Return the first ChildCronjobs matching the query
 * @method     ChildCronjobs findOneOrCreate(ConnectionInterface $con = null) Return the first ChildCronjobs matching the query, or a new ChildCronjobs object populated from the query conditions when no match is found
 *
 * @method     ChildCronjobs findOneByJobname(string $JobName) Return the first ChildCronjobs filtered by the JobName column
 * @method     ChildCronjobs findOneByLastexecution(string $LastExecution) Return the first ChildCronjobs filtered by the LastExecution column
 * @method     ChildCronjobs findOneByLaststatus(boolean $LastStatus) Return the first ChildCronjobs filtered by the LastStatus column
 * @method     ChildCronjobs findOneByLastexecutioninformation(string $LastExecutionInformation) Return the first ChildCronjobs filtered by the LastExecutionInformation column *

 * @method     ChildCronjobs requirePk($key, ConnectionInterface $con = null) Return the ChildCronjobs by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCronjobs requireOne(ConnectionInterface $con = null) Return the first ChildCronjobs matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildCronjobs requireOneByJobname(string $JobName) Return the first ChildCronjobs filtered by the JobName column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCronjobs requireOneByLastexecution(string $LastExecution) Return the first ChildCronjobs filtered by the LastExecution column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCronjobs requireOneByLaststatus(boolean $LastStatus) Return the first ChildCronjobs filtered by the LastStatus column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCronjobs requireOneByLastexecutioninformation(string $LastExecutionInformation) Return the first ChildCronjobs filtered by the LastExecutionInformation column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildCronjobs[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildCronjobs objects based on current ModelCriteria
 * @method     ChildCronjobs[]|ObjectCollection findByJobname(string $JobName) Return ChildCronjobs objects filtered by the JobName column
 * @method     ChildCronjobs[]|ObjectCollection findByLastexecution(string $LastExecution) Return ChildCronjobs objects filtered by the LastExecution column
 * @method     ChildCronjobs[]|ObjectCollection findByLaststatus(boolean $LastStatus) Return ChildCronjobs objects filtered by the LastStatus column
 * @method     ChildCronjobs[]|ObjectCollection findByLastexecutioninformation(string $LastExecutionInformation) Return ChildCronjobs objects filtered by the LastExecutionInformation column
 * @method     ChildCronjobs[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class CronjobsQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Base\CronjobsQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Cronjobs', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildCronjobsQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildCronjobsQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildCronjobsQuery) {
            return $criteria;
        }
        $query = new ChildCronjobsQuery();
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
     * @return ChildCronjobs|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = CronjobsTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(CronjobsTableMap::DATABASE_NAME);
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
     * @return ChildCronjobs A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT JobName, LastExecution, LastStatus, LastExecutionInformation FROM cronjobs WHERE JobName = :p0';
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
            /** @var ChildCronjobs $obj */
            $obj = new ChildCronjobs();
            $obj->hydrate($row);
            CronjobsTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildCronjobs|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildCronjobsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(CronjobsTableMap::COL_JOBNAME, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildCronjobsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(CronjobsTableMap::COL_JOBNAME, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the JobName column
     *
     * Example usage:
     * <code>
     * $query->filterByJobname('fooValue');   // WHERE JobName = 'fooValue'
     * $query->filterByJobname('%fooValue%'); // WHERE JobName LIKE '%fooValue%'
     * </code>
     *
     * @param     string $jobname The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCronjobsQuery The current query, for fluid interface
     */
    public function filterByJobname($jobname = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($jobname)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $jobname)) {
                $jobname = str_replace('*', '%', $jobname);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(CronjobsTableMap::COL_JOBNAME, $jobname, $comparison);
    }

    /**
     * Filter the query on the LastExecution column
     *
     * Example usage:
     * <code>
     * $query->filterByLastexecution('2011-03-14'); // WHERE LastExecution = '2011-03-14'
     * $query->filterByLastexecution('now'); // WHERE LastExecution = '2011-03-14'
     * $query->filterByLastexecution(array('max' => 'yesterday')); // WHERE LastExecution > '2011-03-13'
     * </code>
     *
     * @param     mixed $lastexecution The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCronjobsQuery The current query, for fluid interface
     */
    public function filterByLastexecution($lastexecution = null, $comparison = null)
    {
        if (is_array($lastexecution)) {
            $useMinMax = false;
            if (isset($lastexecution['min'])) {
                $this->addUsingAlias(CronjobsTableMap::COL_LASTEXECUTION, $lastexecution['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($lastexecution['max'])) {
                $this->addUsingAlias(CronjobsTableMap::COL_LASTEXECUTION, $lastexecution['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CronjobsTableMap::COL_LASTEXECUTION, $lastexecution, $comparison);
    }

    /**
     * Filter the query on the LastStatus column
     *
     * Example usage:
     * <code>
     * $query->filterByLaststatus(true); // WHERE LastStatus = true
     * $query->filterByLaststatus('yes'); // WHERE LastStatus = true
     * </code>
     *
     * @param     boolean|string $laststatus The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCronjobsQuery The current query, for fluid interface
     */
    public function filterByLaststatus($laststatus = null, $comparison = null)
    {
        if (is_string($laststatus)) {
            $laststatus = in_array(strtolower($laststatus), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(CronjobsTableMap::COL_LASTSTATUS, $laststatus, $comparison);
    }

    /**
     * Filter the query on the LastExecutionInformation column
     *
     * Example usage:
     * <code>
     * $query->filterByLastexecutioninformation('fooValue');   // WHERE LastExecutionInformation = 'fooValue'
     * $query->filterByLastexecutioninformation('%fooValue%'); // WHERE LastExecutionInformation LIKE '%fooValue%'
     * </code>
     *
     * @param     string $lastexecutioninformation The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCronjobsQuery The current query, for fluid interface
     */
    public function filterByLastexecutioninformation($lastexecutioninformation = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($lastexecutioninformation)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $lastexecutioninformation)) {
                $lastexecutioninformation = str_replace('*', '%', $lastexecutioninformation);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(CronjobsTableMap::COL_LASTEXECUTIONINFORMATION, $lastexecutioninformation, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   ChildCronjobs $cronjobs Object to remove from the list of results
     *
     * @return $this|ChildCronjobsQuery The current query, for fluid interface
     */
    public function prune($cronjobs = null)
    {
        if ($cronjobs) {
            $this->addUsingAlias(CronjobsTableMap::COL_JOBNAME, $cronjobs->getJobname(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the cronjobs table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(CronjobsTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            CronjobsTableMap::clearInstancePool();
            CronjobsTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(CronjobsTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(CronjobsTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            CronjobsTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            CronjobsTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // CronjobsQuery
