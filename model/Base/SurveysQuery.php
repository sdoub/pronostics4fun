<?php

namespace Base;

use \Surveys as ChildSurveys;
use \SurveysQuery as ChildSurveysQuery;
use \Exception;
use \PDO;
use Map\SurveysTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'surveys' table.
 *
 *
 *
 * @method     ChildSurveysQuery orderByPrimarykey($order = Criteria::ASC) Order by the PrimaryKey column
 * @method     ChildSurveysQuery orderByQuestion($order = Criteria::ASC) Order by the Question column
 * @method     ChildSurveysQuery orderByAnswer1($order = Criteria::ASC) Order by the Answer1 column
 * @method     ChildSurveysQuery orderByAnswer2($order = Criteria::ASC) Order by the Answer2 column
 * @method     ChildSurveysQuery orderByAnswer3($order = Criteria::ASC) Order by the Answer3 column
 * @method     ChildSurveysQuery orderByAnswer4($order = Criteria::ASC) Order by the Answer4 column
 * @method     ChildSurveysQuery orderByScore1($order = Criteria::ASC) Order by the Score1 column
 * @method     ChildSurveysQuery orderByScore2($order = Criteria::ASC) Order by the Score2 column
 * @method     ChildSurveysQuery orderByScore3($order = Criteria::ASC) Order by the Score3 column
 * @method     ChildSurveysQuery orderByScore4($order = Criteria::ASC) Order by the Score4 column
 * @method     ChildSurveysQuery orderByParticipants($order = Criteria::ASC) Order by the Participants column
 * @method     ChildSurveysQuery orderByStartdate($order = Criteria::ASC) Order by the StartDate column
 * @method     ChildSurveysQuery orderByEnddate($order = Criteria::ASC) Order by the EndDate column
 *
 * @method     ChildSurveysQuery groupByPrimarykey() Group by the PrimaryKey column
 * @method     ChildSurveysQuery groupByQuestion() Group by the Question column
 * @method     ChildSurveysQuery groupByAnswer1() Group by the Answer1 column
 * @method     ChildSurveysQuery groupByAnswer2() Group by the Answer2 column
 * @method     ChildSurveysQuery groupByAnswer3() Group by the Answer3 column
 * @method     ChildSurveysQuery groupByAnswer4() Group by the Answer4 column
 * @method     ChildSurveysQuery groupByScore1() Group by the Score1 column
 * @method     ChildSurveysQuery groupByScore2() Group by the Score2 column
 * @method     ChildSurveysQuery groupByScore3() Group by the Score3 column
 * @method     ChildSurveysQuery groupByScore4() Group by the Score4 column
 * @method     ChildSurveysQuery groupByParticipants() Group by the Participants column
 * @method     ChildSurveysQuery groupByStartdate() Group by the StartDate column
 * @method     ChildSurveysQuery groupByEnddate() Group by the EndDate column
 *
 * @method     ChildSurveysQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildSurveysQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildSurveysQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildSurveys findOne(ConnectionInterface $con = null) Return the first ChildSurveys matching the query
 * @method     ChildSurveys findOneOrCreate(ConnectionInterface $con = null) Return the first ChildSurveys matching the query, or a new ChildSurveys object populated from the query conditions when no match is found
 *
 * @method     ChildSurveys findOneByPrimarykey(int $PrimaryKey) Return the first ChildSurveys filtered by the PrimaryKey column
 * @method     ChildSurveys findOneByQuestion(string $Question) Return the first ChildSurveys filtered by the Question column
 * @method     ChildSurveys findOneByAnswer1(string $Answer1) Return the first ChildSurveys filtered by the Answer1 column
 * @method     ChildSurveys findOneByAnswer2(string $Answer2) Return the first ChildSurveys filtered by the Answer2 column
 * @method     ChildSurveys findOneByAnswer3(string $Answer3) Return the first ChildSurveys filtered by the Answer3 column
 * @method     ChildSurveys findOneByAnswer4(string $Answer4) Return the first ChildSurveys filtered by the Answer4 column
 * @method     ChildSurveys findOneByScore1(int $Score1) Return the first ChildSurveys filtered by the Score1 column
 * @method     ChildSurveys findOneByScore2(int $Score2) Return the first ChildSurveys filtered by the Score2 column
 * @method     ChildSurveys findOneByScore3(int $Score3) Return the first ChildSurveys filtered by the Score3 column
 * @method     ChildSurveys findOneByScore4(int $Score4) Return the first ChildSurveys filtered by the Score4 column
 * @method     ChildSurveys findOneByParticipants(string $Participants) Return the first ChildSurveys filtered by the Participants column
 * @method     ChildSurveys findOneByStartdate(string $StartDate) Return the first ChildSurveys filtered by the StartDate column
 * @method     ChildSurveys findOneByEnddate(string $EndDate) Return the first ChildSurveys filtered by the EndDate column *

 * @method     ChildSurveys requirePk($key, ConnectionInterface $con = null) Return the ChildSurveys by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSurveys requireOne(ConnectionInterface $con = null) Return the first ChildSurveys matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildSurveys requireOneByPrimarykey(int $PrimaryKey) Return the first ChildSurveys filtered by the PrimaryKey column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSurveys requireOneByQuestion(string $Question) Return the first ChildSurveys filtered by the Question column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSurveys requireOneByAnswer1(string $Answer1) Return the first ChildSurveys filtered by the Answer1 column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSurveys requireOneByAnswer2(string $Answer2) Return the first ChildSurveys filtered by the Answer2 column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSurveys requireOneByAnswer3(string $Answer3) Return the first ChildSurveys filtered by the Answer3 column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSurveys requireOneByAnswer4(string $Answer4) Return the first ChildSurveys filtered by the Answer4 column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSurveys requireOneByScore1(int $Score1) Return the first ChildSurveys filtered by the Score1 column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSurveys requireOneByScore2(int $Score2) Return the first ChildSurveys filtered by the Score2 column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSurveys requireOneByScore3(int $Score3) Return the first ChildSurveys filtered by the Score3 column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSurveys requireOneByScore4(int $Score4) Return the first ChildSurveys filtered by the Score4 column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSurveys requireOneByParticipants(string $Participants) Return the first ChildSurveys filtered by the Participants column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSurveys requireOneByStartdate(string $StartDate) Return the first ChildSurveys filtered by the StartDate column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSurveys requireOneByEnddate(string $EndDate) Return the first ChildSurveys filtered by the EndDate column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildSurveys[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildSurveys objects based on current ModelCriteria
 * @method     ChildSurveys[]|ObjectCollection findByPrimarykey(int $PrimaryKey) Return ChildSurveys objects filtered by the PrimaryKey column
 * @method     ChildSurveys[]|ObjectCollection findByQuestion(string $Question) Return ChildSurveys objects filtered by the Question column
 * @method     ChildSurveys[]|ObjectCollection findByAnswer1(string $Answer1) Return ChildSurveys objects filtered by the Answer1 column
 * @method     ChildSurveys[]|ObjectCollection findByAnswer2(string $Answer2) Return ChildSurveys objects filtered by the Answer2 column
 * @method     ChildSurveys[]|ObjectCollection findByAnswer3(string $Answer3) Return ChildSurveys objects filtered by the Answer3 column
 * @method     ChildSurveys[]|ObjectCollection findByAnswer4(string $Answer4) Return ChildSurveys objects filtered by the Answer4 column
 * @method     ChildSurveys[]|ObjectCollection findByScore1(int $Score1) Return ChildSurveys objects filtered by the Score1 column
 * @method     ChildSurveys[]|ObjectCollection findByScore2(int $Score2) Return ChildSurveys objects filtered by the Score2 column
 * @method     ChildSurveys[]|ObjectCollection findByScore3(int $Score3) Return ChildSurveys objects filtered by the Score3 column
 * @method     ChildSurveys[]|ObjectCollection findByScore4(int $Score4) Return ChildSurveys objects filtered by the Score4 column
 * @method     ChildSurveys[]|ObjectCollection findByParticipants(string $Participants) Return ChildSurveys objects filtered by the Participants column
 * @method     ChildSurveys[]|ObjectCollection findByStartdate(string $StartDate) Return ChildSurveys objects filtered by the StartDate column
 * @method     ChildSurveys[]|ObjectCollection findByEnddate(string $EndDate) Return ChildSurveys objects filtered by the EndDate column
 * @method     ChildSurveys[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class SurveysQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Base\SurveysQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Surveys', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildSurveysQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildSurveysQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildSurveysQuery) {
            return $criteria;
        }
        $query = new ChildSurveysQuery();
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
     * @return ChildSurveys|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = SurveysTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(SurveysTableMap::DATABASE_NAME);
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
     * @return ChildSurveys A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT PrimaryKey, Question, Answer1, Answer2, Answer3, Answer4, Score1, Score2, Score3, Score4, Participants, StartDate, EndDate FROM surveys WHERE PrimaryKey = :p0';
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
            /** @var ChildSurveys $obj */
            $obj = new ChildSurveys();
            $obj->hydrate($row);
            SurveysTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildSurveys|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildSurveysQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(SurveysTableMap::COL_PRIMARYKEY, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildSurveysQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(SurveysTableMap::COL_PRIMARYKEY, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the PrimaryKey column
     *
     * Example usage:
     * <code>
     * $query->filterByPrimarykey(1234); // WHERE PrimaryKey = 1234
     * $query->filterByPrimarykey(array(12, 34)); // WHERE PrimaryKey IN (12, 34)
     * $query->filterByPrimarykey(array('min' => 12)); // WHERE PrimaryKey > 12
     * </code>
     *
     * @param     mixed $primarykey The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSurveysQuery The current query, for fluid interface
     */
    public function filterByPrimarykey($primarykey = null, $comparison = null)
    {
        if (is_array($primarykey)) {
            $useMinMax = false;
            if (isset($primarykey['min'])) {
                $this->addUsingAlias(SurveysTableMap::COL_PRIMARYKEY, $primarykey['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($primarykey['max'])) {
                $this->addUsingAlias(SurveysTableMap::COL_PRIMARYKEY, $primarykey['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SurveysTableMap::COL_PRIMARYKEY, $primarykey, $comparison);
    }

    /**
     * Filter the query on the Question column
     *
     * Example usage:
     * <code>
     * $query->filterByQuestion('fooValue');   // WHERE Question = 'fooValue'
     * $query->filterByQuestion('%fooValue%'); // WHERE Question LIKE '%fooValue%'
     * </code>
     *
     * @param     string $question The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSurveysQuery The current query, for fluid interface
     */
    public function filterByQuestion($question = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($question)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $question)) {
                $question = str_replace('*', '%', $question);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(SurveysTableMap::COL_QUESTION, $question, $comparison);
    }

    /**
     * Filter the query on the Answer1 column
     *
     * Example usage:
     * <code>
     * $query->filterByAnswer1('fooValue');   // WHERE Answer1 = 'fooValue'
     * $query->filterByAnswer1('%fooValue%'); // WHERE Answer1 LIKE '%fooValue%'
     * </code>
     *
     * @param     string $answer1 The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSurveysQuery The current query, for fluid interface
     */
    public function filterByAnswer1($answer1 = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($answer1)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $answer1)) {
                $answer1 = str_replace('*', '%', $answer1);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(SurveysTableMap::COL_ANSWER1, $answer1, $comparison);
    }

    /**
     * Filter the query on the Answer2 column
     *
     * Example usage:
     * <code>
     * $query->filterByAnswer2('fooValue');   // WHERE Answer2 = 'fooValue'
     * $query->filterByAnswer2('%fooValue%'); // WHERE Answer2 LIKE '%fooValue%'
     * </code>
     *
     * @param     string $answer2 The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSurveysQuery The current query, for fluid interface
     */
    public function filterByAnswer2($answer2 = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($answer2)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $answer2)) {
                $answer2 = str_replace('*', '%', $answer2);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(SurveysTableMap::COL_ANSWER2, $answer2, $comparison);
    }

    /**
     * Filter the query on the Answer3 column
     *
     * Example usage:
     * <code>
     * $query->filterByAnswer3('fooValue');   // WHERE Answer3 = 'fooValue'
     * $query->filterByAnswer3('%fooValue%'); // WHERE Answer3 LIKE '%fooValue%'
     * </code>
     *
     * @param     string $answer3 The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSurveysQuery The current query, for fluid interface
     */
    public function filterByAnswer3($answer3 = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($answer3)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $answer3)) {
                $answer3 = str_replace('*', '%', $answer3);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(SurveysTableMap::COL_ANSWER3, $answer3, $comparison);
    }

    /**
     * Filter the query on the Answer4 column
     *
     * Example usage:
     * <code>
     * $query->filterByAnswer4('fooValue');   // WHERE Answer4 = 'fooValue'
     * $query->filterByAnswer4('%fooValue%'); // WHERE Answer4 LIKE '%fooValue%'
     * </code>
     *
     * @param     string $answer4 The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSurveysQuery The current query, for fluid interface
     */
    public function filterByAnswer4($answer4 = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($answer4)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $answer4)) {
                $answer4 = str_replace('*', '%', $answer4);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(SurveysTableMap::COL_ANSWER4, $answer4, $comparison);
    }

    /**
     * Filter the query on the Score1 column
     *
     * Example usage:
     * <code>
     * $query->filterByScore1(1234); // WHERE Score1 = 1234
     * $query->filterByScore1(array(12, 34)); // WHERE Score1 IN (12, 34)
     * $query->filterByScore1(array('min' => 12)); // WHERE Score1 > 12
     * </code>
     *
     * @param     mixed $score1 The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSurveysQuery The current query, for fluid interface
     */
    public function filterByScore1($score1 = null, $comparison = null)
    {
        if (is_array($score1)) {
            $useMinMax = false;
            if (isset($score1['min'])) {
                $this->addUsingAlias(SurveysTableMap::COL_SCORE1, $score1['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($score1['max'])) {
                $this->addUsingAlias(SurveysTableMap::COL_SCORE1, $score1['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SurveysTableMap::COL_SCORE1, $score1, $comparison);
    }

    /**
     * Filter the query on the Score2 column
     *
     * Example usage:
     * <code>
     * $query->filterByScore2(1234); // WHERE Score2 = 1234
     * $query->filterByScore2(array(12, 34)); // WHERE Score2 IN (12, 34)
     * $query->filterByScore2(array('min' => 12)); // WHERE Score2 > 12
     * </code>
     *
     * @param     mixed $score2 The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSurveysQuery The current query, for fluid interface
     */
    public function filterByScore2($score2 = null, $comparison = null)
    {
        if (is_array($score2)) {
            $useMinMax = false;
            if (isset($score2['min'])) {
                $this->addUsingAlias(SurveysTableMap::COL_SCORE2, $score2['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($score2['max'])) {
                $this->addUsingAlias(SurveysTableMap::COL_SCORE2, $score2['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SurveysTableMap::COL_SCORE2, $score2, $comparison);
    }

    /**
     * Filter the query on the Score3 column
     *
     * Example usage:
     * <code>
     * $query->filterByScore3(1234); // WHERE Score3 = 1234
     * $query->filterByScore3(array(12, 34)); // WHERE Score3 IN (12, 34)
     * $query->filterByScore3(array('min' => 12)); // WHERE Score3 > 12
     * </code>
     *
     * @param     mixed $score3 The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSurveysQuery The current query, for fluid interface
     */
    public function filterByScore3($score3 = null, $comparison = null)
    {
        if (is_array($score3)) {
            $useMinMax = false;
            if (isset($score3['min'])) {
                $this->addUsingAlias(SurveysTableMap::COL_SCORE3, $score3['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($score3['max'])) {
                $this->addUsingAlias(SurveysTableMap::COL_SCORE3, $score3['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SurveysTableMap::COL_SCORE3, $score3, $comparison);
    }

    /**
     * Filter the query on the Score4 column
     *
     * Example usage:
     * <code>
     * $query->filterByScore4(1234); // WHERE Score4 = 1234
     * $query->filterByScore4(array(12, 34)); // WHERE Score4 IN (12, 34)
     * $query->filterByScore4(array('min' => 12)); // WHERE Score4 > 12
     * </code>
     *
     * @param     mixed $score4 The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSurveysQuery The current query, for fluid interface
     */
    public function filterByScore4($score4 = null, $comparison = null)
    {
        if (is_array($score4)) {
            $useMinMax = false;
            if (isset($score4['min'])) {
                $this->addUsingAlias(SurveysTableMap::COL_SCORE4, $score4['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($score4['max'])) {
                $this->addUsingAlias(SurveysTableMap::COL_SCORE4, $score4['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SurveysTableMap::COL_SCORE4, $score4, $comparison);
    }

    /**
     * Filter the query on the Participants column
     *
     * Example usage:
     * <code>
     * $query->filterByParticipants('fooValue');   // WHERE Participants = 'fooValue'
     * $query->filterByParticipants('%fooValue%'); // WHERE Participants LIKE '%fooValue%'
     * </code>
     *
     * @param     string $participants The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSurveysQuery The current query, for fluid interface
     */
    public function filterByParticipants($participants = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($participants)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $participants)) {
                $participants = str_replace('*', '%', $participants);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(SurveysTableMap::COL_PARTICIPANTS, $participants, $comparison);
    }

    /**
     * Filter the query on the StartDate column
     *
     * Example usage:
     * <code>
     * $query->filterByStartdate('2011-03-14'); // WHERE StartDate = '2011-03-14'
     * $query->filterByStartdate('now'); // WHERE StartDate = '2011-03-14'
     * $query->filterByStartdate(array('max' => 'yesterday')); // WHERE StartDate > '2011-03-13'
     * </code>
     *
     * @param     mixed $startdate The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSurveysQuery The current query, for fluid interface
     */
    public function filterByStartdate($startdate = null, $comparison = null)
    {
        if (is_array($startdate)) {
            $useMinMax = false;
            if (isset($startdate['min'])) {
                $this->addUsingAlias(SurveysTableMap::COL_STARTDATE, $startdate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($startdate['max'])) {
                $this->addUsingAlias(SurveysTableMap::COL_STARTDATE, $startdate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SurveysTableMap::COL_STARTDATE, $startdate, $comparison);
    }

    /**
     * Filter the query on the EndDate column
     *
     * Example usage:
     * <code>
     * $query->filterByEnddate('2011-03-14'); // WHERE EndDate = '2011-03-14'
     * $query->filterByEnddate('now'); // WHERE EndDate = '2011-03-14'
     * $query->filterByEnddate(array('max' => 'yesterday')); // WHERE EndDate > '2011-03-13'
     * </code>
     *
     * @param     mixed $enddate The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSurveysQuery The current query, for fluid interface
     */
    public function filterByEnddate($enddate = null, $comparison = null)
    {
        if (is_array($enddate)) {
            $useMinMax = false;
            if (isset($enddate['min'])) {
                $this->addUsingAlias(SurveysTableMap::COL_ENDDATE, $enddate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($enddate['max'])) {
                $this->addUsingAlias(SurveysTableMap::COL_ENDDATE, $enddate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SurveysTableMap::COL_ENDDATE, $enddate, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   ChildSurveys $surveys Object to remove from the list of results
     *
     * @return $this|ChildSurveysQuery The current query, for fluid interface
     */
    public function prune($surveys = null)
    {
        if ($surveys) {
            $this->addUsingAlias(SurveysTableMap::COL_PRIMARYKEY, $surveys->getPrimarykey(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the surveys table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(SurveysTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            SurveysTableMap::clearInstancePool();
            SurveysTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(SurveysTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(SurveysTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            SurveysTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            SurveysTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // SurveysQuery
