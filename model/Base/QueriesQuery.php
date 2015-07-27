<?php

namespace Base;

use \Queries as ChildQueries;
use \QueriesQuery as ChildQueriesQuery;
use \Exception;
use Map\QueriesTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\LogicException;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'Queries' table.
 *
 *
 *
 * @method     ChildQueriesQuery orderByName($order = Criteria::ASC) Order by the Name column
 * @method     ChildQueriesQuery orderByQuery($order = Criteria::ASC) Order by the Query column
 *
 * @method     ChildQueriesQuery groupByName() Group by the Name column
 * @method     ChildQueriesQuery groupByQuery() Group by the Query column
 *
 * @method     ChildQueriesQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildQueriesQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildQueriesQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildQueries findOne(ConnectionInterface $con = null) Return the first ChildQueries matching the query
 * @method     ChildQueries findOneOrCreate(ConnectionInterface $con = null) Return the first ChildQueries matching the query, or a new ChildQueries object populated from the query conditions when no match is found
 *
 * @method     ChildQueries findOneByName(string $Name) Return the first ChildQueries filtered by the Name column
 * @method     ChildQueries findOneByQuery(string $Query) Return the first ChildQueries filtered by the Query column *

 * @method     ChildQueries requirePk($key, ConnectionInterface $con = null) Return the ChildQueries by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildQueries requireOne(ConnectionInterface $con = null) Return the first ChildQueries matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildQueries requireOneByName(string $Name) Return the first ChildQueries filtered by the Name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildQueries requireOneByQuery(string $Query) Return the first ChildQueries filtered by the Query column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildQueries[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildQueries objects based on current ModelCriteria
 * @method     ChildQueries[]|ObjectCollection findByName(string $Name) Return ChildQueries objects filtered by the Name column
 * @method     ChildQueries[]|ObjectCollection findByQuery(string $Query) Return ChildQueries objects filtered by the Query column
 * @method     ChildQueries[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class QueriesQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Base\QueriesQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Queries', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildQueriesQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildQueriesQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildQueriesQuery) {
            return $criteria;
        }
        $query = new ChildQueriesQuery();
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
     * @return ChildQueries|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        throw new LogicException('The Queries object has no primary key');
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
        throw new LogicException('The Queries object has no primary key');
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return $this|ChildQueriesQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        throw new LogicException('The Queries object has no primary key');
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildQueriesQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        throw new LogicException('The Queries object has no primary key');
    }

    /**
     * Filter the query on the Name column
     *
     * Example usage:
     * <code>
     * $query->filterByName('fooValue');   // WHERE Name = 'fooValue'
     * $query->filterByName('%fooValue%'); // WHERE Name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $name The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildQueriesQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $name)) {
                $name = str_replace('*', '%', $name);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(QueriesTableMap::COL_NAME, $name, $comparison);
    }

    /**
     * Filter the query on the Query column
     *
     * Example usage:
     * <code>
     * $query->filterByQuery('fooValue');   // WHERE Query = 'fooValue'
     * $query->filterByQuery('%fooValue%'); // WHERE Query LIKE '%fooValue%'
     * </code>
     *
     * @param     string $query The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildQueriesQuery The current query, for fluid interface
     */
    public function filterByQuery($query = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($query)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $query)) {
                $query = str_replace('*', '%', $query);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(QueriesTableMap::COL_QUERY, $query, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   ChildQueries $queries Object to remove from the list of results
     *
     * @return $this|ChildQueriesQuery The current query, for fluid interface
     */
    public function prune($queries = null)
    {
        if ($queries) {
            throw new LogicException('Queries object has no primary key');

        }

        return $this;
    }

    /**
     * Deletes all rows from the Queries table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(QueriesTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            QueriesTableMap::clearInstancePool();
            QueriesTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(QueriesTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(QueriesTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            QueriesTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            QueriesTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // QueriesQuery
