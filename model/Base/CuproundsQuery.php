<?php

namespace Base;

use \Cuprounds as ChildCuprounds;
use \CuproundsQuery as ChildCuproundsQuery;
use \Exception;
use \PDO;
use Map\CuproundsTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'cuprounds' table.
 *
 *
 *
 * @method     ChildCuproundsQuery orderByCupRoundPK($order = Criteria::ASC) Order by the PrimaryKey column
 * @method     ChildCuproundsQuery orderByDescription($order = Criteria::ASC) Order by the Description column
 * @method     ChildCuproundsQuery orderByCode($order = Criteria::ASC) Order by the Code column
 * @method     ChildCuproundsQuery orderByNextroundkey($order = Criteria::ASC) Order by the NextRoundKey column
 * @method     ChildCuproundsQuery orderByPreviousroundkey($order = Criteria::ASC) Order by the PreviousRoundKey column
 *
 * @method     ChildCuproundsQuery groupByCupRoundPK() Group by the PrimaryKey column
 * @method     ChildCuproundsQuery groupByDescription() Group by the Description column
 * @method     ChildCuproundsQuery groupByCode() Group by the Code column
 * @method     ChildCuproundsQuery groupByNextroundkey() Group by the NextRoundKey column
 * @method     ChildCuproundsQuery groupByPreviousroundkey() Group by the PreviousRoundKey column
 *
 * @method     ChildCuproundsQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildCuproundsQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildCuproundsQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildCuprounds findOne(ConnectionInterface $con = null) Return the first ChildCuprounds matching the query
 * @method     ChildCuprounds findOneOrCreate(ConnectionInterface $con = null) Return the first ChildCuprounds matching the query, or a new ChildCuprounds object populated from the query conditions when no match is found
 *
 * @method     ChildCuprounds findOneByCupRoundPK(int $PrimaryKey) Return the first ChildCuprounds filtered by the PrimaryKey column
 * @method     ChildCuprounds findOneByDescription(string $Description) Return the first ChildCuprounds filtered by the Description column
 * @method     ChildCuprounds findOneByCode(string $Code) Return the first ChildCuprounds filtered by the Code column
 * @method     ChildCuprounds findOneByNextroundkey(int $NextRoundKey) Return the first ChildCuprounds filtered by the NextRoundKey column
 * @method     ChildCuprounds findOneByPreviousroundkey(int $PreviousRoundKey) Return the first ChildCuprounds filtered by the PreviousRoundKey column *

 * @method     ChildCuprounds requirePk($key, ConnectionInterface $con = null) Return the ChildCuprounds by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCuprounds requireOne(ConnectionInterface $con = null) Return the first ChildCuprounds matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildCuprounds requireOneByCupRoundPK(int $PrimaryKey) Return the first ChildCuprounds filtered by the PrimaryKey column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCuprounds requireOneByDescription(string $Description) Return the first ChildCuprounds filtered by the Description column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCuprounds requireOneByCode(string $Code) Return the first ChildCuprounds filtered by the Code column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCuprounds requireOneByNextroundkey(int $NextRoundKey) Return the first ChildCuprounds filtered by the NextRoundKey column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCuprounds requireOneByPreviousroundkey(int $PreviousRoundKey) Return the first ChildCuprounds filtered by the PreviousRoundKey column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildCuprounds[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildCuprounds objects based on current ModelCriteria
 * @method     ChildCuprounds[]|ObjectCollection findByCupRoundPK(int $PrimaryKey) Return ChildCuprounds objects filtered by the PrimaryKey column
 * @method     ChildCuprounds[]|ObjectCollection findByDescription(string $Description) Return ChildCuprounds objects filtered by the Description column
 * @method     ChildCuprounds[]|ObjectCollection findByCode(string $Code) Return ChildCuprounds objects filtered by the Code column
 * @method     ChildCuprounds[]|ObjectCollection findByNextroundkey(int $NextRoundKey) Return ChildCuprounds objects filtered by the NextRoundKey column
 * @method     ChildCuprounds[]|ObjectCollection findByPreviousroundkey(int $PreviousRoundKey) Return ChildCuprounds objects filtered by the PreviousRoundKey column
 * @method     ChildCuprounds[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class CuproundsQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Base\CuproundsQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Cuprounds', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildCuproundsQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildCuproundsQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildCuproundsQuery) {
            return $criteria;
        }
        $query = new ChildCuproundsQuery();
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
     * @return ChildCuprounds|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = CuproundsTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(CuproundsTableMap::DATABASE_NAME);
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
     * @return ChildCuprounds A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT PrimaryKey, Description, Code, NextRoundKey, PreviousRoundKey FROM cuprounds WHERE PrimaryKey = :p0';
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
            /** @var ChildCuprounds $obj */
            $obj = new ChildCuprounds();
            $obj->hydrate($row);
            CuproundsTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildCuprounds|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildCuproundsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(CuproundsTableMap::COL_PRIMARYKEY, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildCuproundsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(CuproundsTableMap::COL_PRIMARYKEY, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the PrimaryKey column
     *
     * Example usage:
     * <code>
     * $query->filterByCupRoundPK(1234); // WHERE PrimaryKey = 1234
     * $query->filterByCupRoundPK(array(12, 34)); // WHERE PrimaryKey IN (12, 34)
     * $query->filterByCupRoundPK(array('min' => 12)); // WHERE PrimaryKey > 12
     * </code>
     *
     * @param     mixed $cupRoundPK The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCuproundsQuery The current query, for fluid interface
     */
    public function filterByCupRoundPK($cupRoundPK = null, $comparison = null)
    {
        if (is_array($cupRoundPK)) {
            $useMinMax = false;
            if (isset($cupRoundPK['min'])) {
                $this->addUsingAlias(CuproundsTableMap::COL_PRIMARYKEY, $cupRoundPK['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($cupRoundPK['max'])) {
                $this->addUsingAlias(CuproundsTableMap::COL_PRIMARYKEY, $cupRoundPK['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CuproundsTableMap::COL_PRIMARYKEY, $cupRoundPK, $comparison);
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
     * @return $this|ChildCuproundsQuery The current query, for fluid interface
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

        return $this->addUsingAlias(CuproundsTableMap::COL_DESCRIPTION, $description, $comparison);
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
     * @return $this|ChildCuproundsQuery The current query, for fluid interface
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

        return $this->addUsingAlias(CuproundsTableMap::COL_CODE, $code, $comparison);
    }

    /**
     * Filter the query on the NextRoundKey column
     *
     * Example usage:
     * <code>
     * $query->filterByNextroundkey(1234); // WHERE NextRoundKey = 1234
     * $query->filterByNextroundkey(array(12, 34)); // WHERE NextRoundKey IN (12, 34)
     * $query->filterByNextroundkey(array('min' => 12)); // WHERE NextRoundKey > 12
     * </code>
     *
     * @param     mixed $nextroundkey The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCuproundsQuery The current query, for fluid interface
     */
    public function filterByNextroundkey($nextroundkey = null, $comparison = null)
    {
        if (is_array($nextroundkey)) {
            $useMinMax = false;
            if (isset($nextroundkey['min'])) {
                $this->addUsingAlias(CuproundsTableMap::COL_NEXTROUNDKEY, $nextroundkey['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($nextroundkey['max'])) {
                $this->addUsingAlias(CuproundsTableMap::COL_NEXTROUNDKEY, $nextroundkey['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CuproundsTableMap::COL_NEXTROUNDKEY, $nextroundkey, $comparison);
    }

    /**
     * Filter the query on the PreviousRoundKey column
     *
     * Example usage:
     * <code>
     * $query->filterByPreviousroundkey(1234); // WHERE PreviousRoundKey = 1234
     * $query->filterByPreviousroundkey(array(12, 34)); // WHERE PreviousRoundKey IN (12, 34)
     * $query->filterByPreviousroundkey(array('min' => 12)); // WHERE PreviousRoundKey > 12
     * </code>
     *
     * @param     mixed $previousroundkey The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCuproundsQuery The current query, for fluid interface
     */
    public function filterByPreviousroundkey($previousroundkey = null, $comparison = null)
    {
        if (is_array($previousroundkey)) {
            $useMinMax = false;
            if (isset($previousroundkey['min'])) {
                $this->addUsingAlias(CuproundsTableMap::COL_PREVIOUSROUNDKEY, $previousroundkey['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($previousroundkey['max'])) {
                $this->addUsingAlias(CuproundsTableMap::COL_PREVIOUSROUNDKEY, $previousroundkey['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CuproundsTableMap::COL_PREVIOUSROUNDKEY, $previousroundkey, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   ChildCuprounds $cuprounds Object to remove from the list of results
     *
     * @return $this|ChildCuproundsQuery The current query, for fluid interface
     */
    public function prune($cuprounds = null)
    {
        if ($cuprounds) {
            $this->addUsingAlias(CuproundsTableMap::COL_PRIMARYKEY, $cuprounds->getCupRoundPK(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the cuprounds table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(CuproundsTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            CuproundsTableMap::clearInstancePool();
            CuproundsTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(CuproundsTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(CuproundsTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            CuproundsTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            CuproundsTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // CuproundsQuery
