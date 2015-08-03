<?php

namespace Base;

use \News as ChildNews;
use \NewsQuery as ChildNewsQuery;
use \Exception;
use \PDO;
use Map\NewsTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'news' table.
 *
 *
 *
 * @method     ChildNewsQuery orderByNewsPK($order = Criteria::ASC) Order by the PrimaryKey column
 * @method     ChildNewsQuery orderByCompetitionkey($order = Criteria::ASC) Order by the CompetitionKey column
 * @method     ChildNewsQuery orderByInformation($order = Criteria::ASC) Order by the Information column
 * @method     ChildNewsQuery orderByInfodate($order = Criteria::ASC) Order by the InfoDate column
 * @method     ChildNewsQuery orderByInfotype($order = Criteria::ASC) Order by the InfoType column
 *
 * @method     ChildNewsQuery groupByNewsPK() Group by the PrimaryKey column
 * @method     ChildNewsQuery groupByCompetitionkey() Group by the CompetitionKey column
 * @method     ChildNewsQuery groupByInformation() Group by the Information column
 * @method     ChildNewsQuery groupByInfodate() Group by the InfoDate column
 * @method     ChildNewsQuery groupByInfotype() Group by the InfoType column
 *
 * @method     ChildNewsQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildNewsQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildNewsQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildNewsQuery leftJoinCompetitions($relationAlias = null) Adds a LEFT JOIN clause to the query using the Competitions relation
 * @method     ChildNewsQuery rightJoinCompetitions($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Competitions relation
 * @method     ChildNewsQuery innerJoinCompetitions($relationAlias = null) Adds a INNER JOIN clause to the query using the Competitions relation
 *
 * @method     \CompetitionsQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildNews findOne(ConnectionInterface $con = null) Return the first ChildNews matching the query
 * @method     ChildNews findOneOrCreate(ConnectionInterface $con = null) Return the first ChildNews matching the query, or a new ChildNews object populated from the query conditions when no match is found
 *
 * @method     ChildNews findOneByNewsPK(int $PrimaryKey) Return the first ChildNews filtered by the PrimaryKey column
 * @method     ChildNews findOneByCompetitionkey(int $CompetitionKey) Return the first ChildNews filtered by the CompetitionKey column
 * @method     ChildNews findOneByInformation(string $Information) Return the first ChildNews filtered by the Information column
 * @method     ChildNews findOneByInfodate(string $InfoDate) Return the first ChildNews filtered by the InfoDate column
 * @method     ChildNews findOneByInfotype(boolean $InfoType) Return the first ChildNews filtered by the InfoType column *

 * @method     ChildNews requirePk($key, ConnectionInterface $con = null) Return the ChildNews by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildNews requireOne(ConnectionInterface $con = null) Return the first ChildNews matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildNews requireOneByNewsPK(int $PrimaryKey) Return the first ChildNews filtered by the PrimaryKey column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildNews requireOneByCompetitionkey(int $CompetitionKey) Return the first ChildNews filtered by the CompetitionKey column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildNews requireOneByInformation(string $Information) Return the first ChildNews filtered by the Information column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildNews requireOneByInfodate(string $InfoDate) Return the first ChildNews filtered by the InfoDate column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildNews requireOneByInfotype(boolean $InfoType) Return the first ChildNews filtered by the InfoType column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildNews[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildNews objects based on current ModelCriteria
 * @method     ChildNews[]|ObjectCollection findByNewsPK(int $PrimaryKey) Return ChildNews objects filtered by the PrimaryKey column
 * @method     ChildNews[]|ObjectCollection findByCompetitionkey(int $CompetitionKey) Return ChildNews objects filtered by the CompetitionKey column
 * @method     ChildNews[]|ObjectCollection findByInformation(string $Information) Return ChildNews objects filtered by the Information column
 * @method     ChildNews[]|ObjectCollection findByInfodate(string $InfoDate) Return ChildNews objects filtered by the InfoDate column
 * @method     ChildNews[]|ObjectCollection findByInfotype(boolean $InfoType) Return ChildNews objects filtered by the InfoType column
 * @method     ChildNews[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class NewsQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Base\NewsQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\News', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildNewsQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildNewsQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildNewsQuery) {
            return $criteria;
        }
        $query = new ChildNewsQuery();
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
     * @return ChildNews|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = NewsTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(NewsTableMap::DATABASE_NAME);
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
     * @return ChildNews A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT PrimaryKey, CompetitionKey, Information, InfoDate, InfoType FROM news WHERE PrimaryKey = :p0';
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
            /** @var ChildNews $obj */
            $obj = new ChildNews();
            $obj->hydrate($row);
            NewsTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildNews|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildNewsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(NewsTableMap::COL_PRIMARYKEY, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildNewsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(NewsTableMap::COL_PRIMARYKEY, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the PrimaryKey column
     *
     * Example usage:
     * <code>
     * $query->filterByNewsPK(1234); // WHERE PrimaryKey = 1234
     * $query->filterByNewsPK(array(12, 34)); // WHERE PrimaryKey IN (12, 34)
     * $query->filterByNewsPK(array('min' => 12)); // WHERE PrimaryKey > 12
     * </code>
     *
     * @param     mixed $newsPK The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildNewsQuery The current query, for fluid interface
     */
    public function filterByNewsPK($newsPK = null, $comparison = null)
    {
        if (is_array($newsPK)) {
            $useMinMax = false;
            if (isset($newsPK['min'])) {
                $this->addUsingAlias(NewsTableMap::COL_PRIMARYKEY, $newsPK['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($newsPK['max'])) {
                $this->addUsingAlias(NewsTableMap::COL_PRIMARYKEY, $newsPK['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(NewsTableMap::COL_PRIMARYKEY, $newsPK, $comparison);
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
     * @see       filterByCompetitions()
     *
     * @param     mixed $competitionkey The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildNewsQuery The current query, for fluid interface
     */
    public function filterByCompetitionkey($competitionkey = null, $comparison = null)
    {
        if (is_array($competitionkey)) {
            $useMinMax = false;
            if (isset($competitionkey['min'])) {
                $this->addUsingAlias(NewsTableMap::COL_COMPETITIONKEY, $competitionkey['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($competitionkey['max'])) {
                $this->addUsingAlias(NewsTableMap::COL_COMPETITIONKEY, $competitionkey['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(NewsTableMap::COL_COMPETITIONKEY, $competitionkey, $comparison);
    }

    /**
     * Filter the query on the Information column
     *
     * Example usage:
     * <code>
     * $query->filterByInformation('fooValue');   // WHERE Information = 'fooValue'
     * $query->filterByInformation('%fooValue%'); // WHERE Information LIKE '%fooValue%'
     * </code>
     *
     * @param     string $information The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildNewsQuery The current query, for fluid interface
     */
    public function filterByInformation($information = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($information)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $information)) {
                $information = str_replace('*', '%', $information);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(NewsTableMap::COL_INFORMATION, $information, $comparison);
    }

    /**
     * Filter the query on the InfoDate column
     *
     * Example usage:
     * <code>
     * $query->filterByInfodate('2011-03-14'); // WHERE InfoDate = '2011-03-14'
     * $query->filterByInfodate('now'); // WHERE InfoDate = '2011-03-14'
     * $query->filterByInfodate(array('max' => 'yesterday')); // WHERE InfoDate > '2011-03-13'
     * </code>
     *
     * @param     mixed $infodate The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildNewsQuery The current query, for fluid interface
     */
    public function filterByInfodate($infodate = null, $comparison = null)
    {
        if (is_array($infodate)) {
            $useMinMax = false;
            if (isset($infodate['min'])) {
                $this->addUsingAlias(NewsTableMap::COL_INFODATE, $infodate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($infodate['max'])) {
                $this->addUsingAlias(NewsTableMap::COL_INFODATE, $infodate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(NewsTableMap::COL_INFODATE, $infodate, $comparison);
    }

    /**
     * Filter the query on the InfoType column
     *
     * Example usage:
     * <code>
     * $query->filterByInfotype(true); // WHERE InfoType = true
     * $query->filterByInfotype('yes'); // WHERE InfoType = true
     * </code>
     *
     * @param     boolean|string $infotype The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildNewsQuery The current query, for fluid interface
     */
    public function filterByInfotype($infotype = null, $comparison = null)
    {
        if (is_string($infotype)) {
            $infotype = in_array(strtolower($infotype), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(NewsTableMap::COL_INFOTYPE, $infotype, $comparison);
    }

    /**
     * Filter the query by a related \Competitions object
     *
     * @param \Competitions|ObjectCollection $competitions The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildNewsQuery The current query, for fluid interface
     */
    public function filterByCompetitions($competitions, $comparison = null)
    {
        if ($competitions instanceof \Competitions) {
            return $this
                ->addUsingAlias(NewsTableMap::COL_COMPETITIONKEY, $competitions->getCompetitionPK(), $comparison);
        } elseif ($competitions instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(NewsTableMap::COL_COMPETITIONKEY, $competitions->toKeyValue('PrimaryKey', 'CompetitionPK'), $comparison);
        } else {
            throw new PropelException('filterByCompetitions() only accepts arguments of type \Competitions or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Competitions relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildNewsQuery The current query, for fluid interface
     */
    public function joinCompetitions($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Competitions');

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
            $this->addJoinObject($join, 'Competitions');
        }

        return $this;
    }

    /**
     * Use the Competitions relation Competitions object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \CompetitionsQuery A secondary query class using the current class as primary query
     */
    public function useCompetitionsQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinCompetitions($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Competitions', '\CompetitionsQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildNews $news Object to remove from the list of results
     *
     * @return $this|ChildNewsQuery The current query, for fluid interface
     */
    public function prune($news = null)
    {
        if ($news) {
            $this->addUsingAlias(NewsTableMap::COL_PRIMARYKEY, $news->getNewsPK(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the news table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(NewsTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            NewsTableMap::clearInstancePool();
            NewsTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(NewsTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(NewsTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            NewsTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            NewsTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // NewsQuery
