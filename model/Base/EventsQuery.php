<?php

namespace Base;

use \Events as ChildEvents;
use \EventsQuery as ChildEventsQuery;
use \Exception;
use \PDO;
use Map\EventsTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'events' table.
 *
 *
 *
 * @method     ChildEventsQuery orderByEventPK($order = Criteria::ASC) Order by the PrimaryKey column
 * @method     ChildEventsQuery orderByResultkey($order = Criteria::ASC) Order by the ResultKey column
 * @method     ChildEventsQuery orderByTeamplayerkey($order = Criteria::ASC) Order by the TeamPlayerKey column
 * @method     ChildEventsQuery orderByEventtime($order = Criteria::ASC) Order by the EventTime column
 * @method     ChildEventsQuery orderByEventtype($order = Criteria::ASC) Order by the EventType column
 * @method     ChildEventsQuery orderByHalf($order = Criteria::ASC) Order by the Half column
 * @method     ChildEventsQuery orderByTeamkey($order = Criteria::ASC) Order by the TeamKey column
 *
 * @method     ChildEventsQuery groupByEventPK() Group by the PrimaryKey column
 * @method     ChildEventsQuery groupByResultkey() Group by the ResultKey column
 * @method     ChildEventsQuery groupByTeamplayerkey() Group by the TeamPlayerKey column
 * @method     ChildEventsQuery groupByEventtime() Group by the EventTime column
 * @method     ChildEventsQuery groupByEventtype() Group by the EventType column
 * @method     ChildEventsQuery groupByHalf() Group by the Half column
 * @method     ChildEventsQuery groupByTeamkey() Group by the TeamKey column
 *
 * @method     ChildEventsQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildEventsQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildEventsQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildEvents findOne(ConnectionInterface $con = null) Return the first ChildEvents matching the query
 * @method     ChildEvents findOneOrCreate(ConnectionInterface $con = null) Return the first ChildEvents matching the query, or a new ChildEvents object populated from the query conditions when no match is found
 *
 * @method     ChildEvents findOneByEventPK(int $PrimaryKey) Return the first ChildEvents filtered by the PrimaryKey column
 * @method     ChildEvents findOneByResultkey(int $ResultKey) Return the first ChildEvents filtered by the ResultKey column
 * @method     ChildEvents findOneByTeamplayerkey(int $TeamPlayerKey) Return the first ChildEvents filtered by the TeamPlayerKey column
 * @method     ChildEvents findOneByEventtime(int $EventTime) Return the first ChildEvents filtered by the EventTime column
 * @method     ChildEvents findOneByEventtype(int $EventType) Return the first ChildEvents filtered by the EventType column
 * @method     ChildEvents findOneByHalf(int $Half) Return the first ChildEvents filtered by the Half column
 * @method     ChildEvents findOneByTeamkey(int $TeamKey) Return the first ChildEvents filtered by the TeamKey column *

 * @method     ChildEvents requirePk($key, ConnectionInterface $con = null) Return the ChildEvents by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEvents requireOne(ConnectionInterface $con = null) Return the first ChildEvents matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildEvents requireOneByEventPK(int $PrimaryKey) Return the first ChildEvents filtered by the PrimaryKey column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEvents requireOneByResultkey(int $ResultKey) Return the first ChildEvents filtered by the ResultKey column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEvents requireOneByTeamplayerkey(int $TeamPlayerKey) Return the first ChildEvents filtered by the TeamPlayerKey column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEvents requireOneByEventtime(int $EventTime) Return the first ChildEvents filtered by the EventTime column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEvents requireOneByEventtype(int $EventType) Return the first ChildEvents filtered by the EventType column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEvents requireOneByHalf(int $Half) Return the first ChildEvents filtered by the Half column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEvents requireOneByTeamkey(int $TeamKey) Return the first ChildEvents filtered by the TeamKey column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildEvents[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildEvents objects based on current ModelCriteria
 * @method     ChildEvents[]|ObjectCollection findByEventPK(int $PrimaryKey) Return ChildEvents objects filtered by the PrimaryKey column
 * @method     ChildEvents[]|ObjectCollection findByResultkey(int $ResultKey) Return ChildEvents objects filtered by the ResultKey column
 * @method     ChildEvents[]|ObjectCollection findByTeamplayerkey(int $TeamPlayerKey) Return ChildEvents objects filtered by the TeamPlayerKey column
 * @method     ChildEvents[]|ObjectCollection findByEventtime(int $EventTime) Return ChildEvents objects filtered by the EventTime column
 * @method     ChildEvents[]|ObjectCollection findByEventtype(int $EventType) Return ChildEvents objects filtered by the EventType column
 * @method     ChildEvents[]|ObjectCollection findByHalf(int $Half) Return ChildEvents objects filtered by the Half column
 * @method     ChildEvents[]|ObjectCollection findByTeamkey(int $TeamKey) Return ChildEvents objects filtered by the TeamKey column
 * @method     ChildEvents[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class EventsQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Base\EventsQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Events', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildEventsQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildEventsQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildEventsQuery) {
            return $criteria;
        }
        $query = new ChildEventsQuery();
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
     * @return ChildEvents|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = EventsTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(EventsTableMap::DATABASE_NAME);
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
     * @return ChildEvents A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT PrimaryKey, ResultKey, TeamPlayerKey, EventTime, EventType, Half, TeamKey FROM events WHERE PrimaryKey = :p0';
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
            /** @var ChildEvents $obj */
            $obj = new ChildEvents();
            $obj->hydrate($row);
            EventsTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildEvents|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildEventsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(EventsTableMap::COL_PRIMARYKEY, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildEventsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(EventsTableMap::COL_PRIMARYKEY, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the PrimaryKey column
     *
     * Example usage:
     * <code>
     * $query->filterByEventPK(1234); // WHERE PrimaryKey = 1234
     * $query->filterByEventPK(array(12, 34)); // WHERE PrimaryKey IN (12, 34)
     * $query->filterByEventPK(array('min' => 12)); // WHERE PrimaryKey > 12
     * </code>
     *
     * @param     mixed $eventPK The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildEventsQuery The current query, for fluid interface
     */
    public function filterByEventPK($eventPK = null, $comparison = null)
    {
        if (is_array($eventPK)) {
            $useMinMax = false;
            if (isset($eventPK['min'])) {
                $this->addUsingAlias(EventsTableMap::COL_PRIMARYKEY, $eventPK['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($eventPK['max'])) {
                $this->addUsingAlias(EventsTableMap::COL_PRIMARYKEY, $eventPK['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventsTableMap::COL_PRIMARYKEY, $eventPK, $comparison);
    }

    /**
     * Filter the query on the ResultKey column
     *
     * Example usage:
     * <code>
     * $query->filterByResultkey(1234); // WHERE ResultKey = 1234
     * $query->filterByResultkey(array(12, 34)); // WHERE ResultKey IN (12, 34)
     * $query->filterByResultkey(array('min' => 12)); // WHERE ResultKey > 12
     * </code>
     *
     * @param     mixed $resultkey The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildEventsQuery The current query, for fluid interface
     */
    public function filterByResultkey($resultkey = null, $comparison = null)
    {
        if (is_array($resultkey)) {
            $useMinMax = false;
            if (isset($resultkey['min'])) {
                $this->addUsingAlias(EventsTableMap::COL_RESULTKEY, $resultkey['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($resultkey['max'])) {
                $this->addUsingAlias(EventsTableMap::COL_RESULTKEY, $resultkey['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventsTableMap::COL_RESULTKEY, $resultkey, $comparison);
    }

    /**
     * Filter the query on the TeamPlayerKey column
     *
     * Example usage:
     * <code>
     * $query->filterByTeamplayerkey(1234); // WHERE TeamPlayerKey = 1234
     * $query->filterByTeamplayerkey(array(12, 34)); // WHERE TeamPlayerKey IN (12, 34)
     * $query->filterByTeamplayerkey(array('min' => 12)); // WHERE TeamPlayerKey > 12
     * </code>
     *
     * @param     mixed $teamplayerkey The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildEventsQuery The current query, for fluid interface
     */
    public function filterByTeamplayerkey($teamplayerkey = null, $comparison = null)
    {
        if (is_array($teamplayerkey)) {
            $useMinMax = false;
            if (isset($teamplayerkey['min'])) {
                $this->addUsingAlias(EventsTableMap::COL_TEAMPLAYERKEY, $teamplayerkey['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($teamplayerkey['max'])) {
                $this->addUsingAlias(EventsTableMap::COL_TEAMPLAYERKEY, $teamplayerkey['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventsTableMap::COL_TEAMPLAYERKEY, $teamplayerkey, $comparison);
    }

    /**
     * Filter the query on the EventTime column
     *
     * Example usage:
     * <code>
     * $query->filterByEventtime(1234); // WHERE EventTime = 1234
     * $query->filterByEventtime(array(12, 34)); // WHERE EventTime IN (12, 34)
     * $query->filterByEventtime(array('min' => 12)); // WHERE EventTime > 12
     * </code>
     *
     * @param     mixed $eventtime The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildEventsQuery The current query, for fluid interface
     */
    public function filterByEventtime($eventtime = null, $comparison = null)
    {
        if (is_array($eventtime)) {
            $useMinMax = false;
            if (isset($eventtime['min'])) {
                $this->addUsingAlias(EventsTableMap::COL_EVENTTIME, $eventtime['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($eventtime['max'])) {
                $this->addUsingAlias(EventsTableMap::COL_EVENTTIME, $eventtime['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventsTableMap::COL_EVENTTIME, $eventtime, $comparison);
    }

    /**
     * Filter the query on the EventType column
     *
     * Example usage:
     * <code>
     * $query->filterByEventtype(1234); // WHERE EventType = 1234
     * $query->filterByEventtype(array(12, 34)); // WHERE EventType IN (12, 34)
     * $query->filterByEventtype(array('min' => 12)); // WHERE EventType > 12
     * </code>
     *
     * @param     mixed $eventtype The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildEventsQuery The current query, for fluid interface
     */
    public function filterByEventtype($eventtype = null, $comparison = null)
    {
        if (is_array($eventtype)) {
            $useMinMax = false;
            if (isset($eventtype['min'])) {
                $this->addUsingAlias(EventsTableMap::COL_EVENTTYPE, $eventtype['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($eventtype['max'])) {
                $this->addUsingAlias(EventsTableMap::COL_EVENTTYPE, $eventtype['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventsTableMap::COL_EVENTTYPE, $eventtype, $comparison);
    }

    /**
     * Filter the query on the Half column
     *
     * Example usage:
     * <code>
     * $query->filterByHalf(1234); // WHERE Half = 1234
     * $query->filterByHalf(array(12, 34)); // WHERE Half IN (12, 34)
     * $query->filterByHalf(array('min' => 12)); // WHERE Half > 12
     * </code>
     *
     * @param     mixed $half The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildEventsQuery The current query, for fluid interface
     */
    public function filterByHalf($half = null, $comparison = null)
    {
        if (is_array($half)) {
            $useMinMax = false;
            if (isset($half['min'])) {
                $this->addUsingAlias(EventsTableMap::COL_HALF, $half['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($half['max'])) {
                $this->addUsingAlias(EventsTableMap::COL_HALF, $half['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventsTableMap::COL_HALF, $half, $comparison);
    }

    /**
     * Filter the query on the TeamKey column
     *
     * Example usage:
     * <code>
     * $query->filterByTeamkey(1234); // WHERE TeamKey = 1234
     * $query->filterByTeamkey(array(12, 34)); // WHERE TeamKey IN (12, 34)
     * $query->filterByTeamkey(array('min' => 12)); // WHERE TeamKey > 12
     * </code>
     *
     * @param     mixed $teamkey The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildEventsQuery The current query, for fluid interface
     */
    public function filterByTeamkey($teamkey = null, $comparison = null)
    {
        if (is_array($teamkey)) {
            $useMinMax = false;
            if (isset($teamkey['min'])) {
                $this->addUsingAlias(EventsTableMap::COL_TEAMKEY, $teamkey['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($teamkey['max'])) {
                $this->addUsingAlias(EventsTableMap::COL_TEAMKEY, $teamkey['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventsTableMap::COL_TEAMKEY, $teamkey, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   ChildEvents $events Object to remove from the list of results
     *
     * @return $this|ChildEventsQuery The current query, for fluid interface
     */
    public function prune($events = null)
    {
        if ($events) {
            $this->addUsingAlias(EventsTableMap::COL_PRIMARYKEY, $events->getEventPK(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the events table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(EventsTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            EventsTableMap::clearInstancePool();
            EventsTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(EventsTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(EventsTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            EventsTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            EventsTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // EventsQuery
