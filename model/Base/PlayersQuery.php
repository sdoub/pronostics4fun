<?php

namespace Base;

use \Players as ChildPlayers;
use \PlayersQuery as ChildPlayersQuery;
use \Exception;
use \PDO;
use Map\PlayersTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'players' table.
 *
 *
 *
 * @method     ChildPlayersQuery orderByPrimarykey($order = Criteria::ASC) Order by the PrimaryKey column
 * @method     ChildPlayersQuery orderByNickname($order = Criteria::ASC) Order by the NickName column
 * @method     ChildPlayersQuery orderByFirstname($order = Criteria::ASC) Order by the FirstName column
 * @method     ChildPlayersQuery orderByLastname($order = Criteria::ASC) Order by the LastName column
 * @method     ChildPlayersQuery orderByEmailaddress($order = Criteria::ASC) Order by the EmailAddress column
 * @method     ChildPlayersQuery orderByPassword($order = Criteria::ASC) Order by the Password column
 * @method     ChildPlayersQuery orderByIsadministrator($order = Criteria::ASC) Order by the IsAdministrator column
 * @method     ChildPlayersQuery orderByActivationkey($order = Criteria::ASC) Order by the ActivationKey column
 * @method     ChildPlayersQuery orderByIsenabled($order = Criteria::ASC) Order by the IsEnabled column
 * @method     ChildPlayersQuery orderByLastconnection($order = Criteria::ASC) Order by the LastConnection column
 * @method     ChildPlayersQuery orderByToken($order = Criteria::ASC) Order by the Token column
 * @method     ChildPlayersQuery orderByAvatarname($order = Criteria::ASC) Order by the AvatarName column
 * @method     ChildPlayersQuery orderByCreationdate($order = Criteria::ASC) Order by the CreationDate column
 * @method     ChildPlayersQuery orderByIscalendardefaultview($order = Criteria::ASC) Order by the IsCalendarDefaultView column
 * @method     ChildPlayersQuery orderByReceivealert($order = Criteria::ASC) Order by the ReceiveAlert column
 * @method     ChildPlayersQuery orderByReceivenewletter($order = Criteria::ASC) Order by the ReceiveNewletter column
 * @method     ChildPlayersQuery orderByReceiveresult($order = Criteria::ASC) Order by the ReceiveResult column
 * @method     ChildPlayersQuery orderByIsreminderemailsent($order = Criteria::ASC) Order by the IsReminderEmailSent column
 * @method     ChildPlayersQuery orderByIsresultemailsent($order = Criteria::ASC) Order by the IsResultEmailSent column
 *
 * @method     ChildPlayersQuery groupByPrimarykey() Group by the PrimaryKey column
 * @method     ChildPlayersQuery groupByNickname() Group by the NickName column
 * @method     ChildPlayersQuery groupByFirstname() Group by the FirstName column
 * @method     ChildPlayersQuery groupByLastname() Group by the LastName column
 * @method     ChildPlayersQuery groupByEmailaddress() Group by the EmailAddress column
 * @method     ChildPlayersQuery groupByPassword() Group by the Password column
 * @method     ChildPlayersQuery groupByIsadministrator() Group by the IsAdministrator column
 * @method     ChildPlayersQuery groupByActivationkey() Group by the ActivationKey column
 * @method     ChildPlayersQuery groupByIsenabled() Group by the IsEnabled column
 * @method     ChildPlayersQuery groupByLastconnection() Group by the LastConnection column
 * @method     ChildPlayersQuery groupByToken() Group by the Token column
 * @method     ChildPlayersQuery groupByAvatarname() Group by the AvatarName column
 * @method     ChildPlayersQuery groupByCreationdate() Group by the CreationDate column
 * @method     ChildPlayersQuery groupByIscalendardefaultview() Group by the IsCalendarDefaultView column
 * @method     ChildPlayersQuery groupByReceivealert() Group by the ReceiveAlert column
 * @method     ChildPlayersQuery groupByReceivenewletter() Group by the ReceiveNewletter column
 * @method     ChildPlayersQuery groupByReceiveresult() Group by the ReceiveResult column
 * @method     ChildPlayersQuery groupByIsreminderemailsent() Group by the IsReminderEmailSent column
 * @method     ChildPlayersQuery groupByIsresultemailsent() Group by the IsResultEmailSent column
 *
 * @method     ChildPlayersQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildPlayersQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildPlayersQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildPlayers findOne(ConnectionInterface $con = null) Return the first ChildPlayers matching the query
 * @method     ChildPlayers findOneOrCreate(ConnectionInterface $con = null) Return the first ChildPlayers matching the query, or a new ChildPlayers object populated from the query conditions when no match is found
 *
 * @method     ChildPlayers findOneByPrimarykey(int $PrimaryKey) Return the first ChildPlayers filtered by the PrimaryKey column
 * @method     ChildPlayers findOneByNickname(string $NickName) Return the first ChildPlayers filtered by the NickName column
 * @method     ChildPlayers findOneByFirstname(string $FirstName) Return the first ChildPlayers filtered by the FirstName column
 * @method     ChildPlayers findOneByLastname(string $LastName) Return the first ChildPlayers filtered by the LastName column
 * @method     ChildPlayers findOneByEmailaddress(string $EmailAddress) Return the first ChildPlayers filtered by the EmailAddress column
 * @method     ChildPlayers findOneByPassword(string $Password) Return the first ChildPlayers filtered by the Password column
 * @method     ChildPlayers findOneByIsadministrator(boolean $IsAdministrator) Return the first ChildPlayers filtered by the IsAdministrator column
 * @method     ChildPlayers findOneByActivationkey(string $ActivationKey) Return the first ChildPlayers filtered by the ActivationKey column
 * @method     ChildPlayers findOneByIsenabled(boolean $IsEnabled) Return the first ChildPlayers filtered by the IsEnabled column
 * @method     ChildPlayers findOneByLastconnection(string $LastConnection) Return the first ChildPlayers filtered by the LastConnection column
 * @method     ChildPlayers findOneByToken(string $Token) Return the first ChildPlayers filtered by the Token column
 * @method     ChildPlayers findOneByAvatarname(string $AvatarName) Return the first ChildPlayers filtered by the AvatarName column
 * @method     ChildPlayers findOneByCreationdate(string $CreationDate) Return the first ChildPlayers filtered by the CreationDate column
 * @method     ChildPlayers findOneByIscalendardefaultview(boolean $IsCalendarDefaultView) Return the first ChildPlayers filtered by the IsCalendarDefaultView column
 * @method     ChildPlayers findOneByReceivealert(boolean $ReceiveAlert) Return the first ChildPlayers filtered by the ReceiveAlert column
 * @method     ChildPlayers findOneByReceivenewletter(boolean $ReceiveNewletter) Return the first ChildPlayers filtered by the ReceiveNewletter column
 * @method     ChildPlayers findOneByReceiveresult(boolean $ReceiveResult) Return the first ChildPlayers filtered by the ReceiveResult column
 * @method     ChildPlayers findOneByIsreminderemailsent(boolean $IsReminderEmailSent) Return the first ChildPlayers filtered by the IsReminderEmailSent column
 * @method     ChildPlayers findOneByIsresultemailsent(boolean $IsResultEmailSent) Return the first ChildPlayers filtered by the IsResultEmailSent column *

 * @method     ChildPlayers requirePk($key, ConnectionInterface $con = null) Return the ChildPlayers by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayers requireOne(ConnectionInterface $con = null) Return the first ChildPlayers matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPlayers requireOneByPrimarykey(int $PrimaryKey) Return the first ChildPlayers filtered by the PrimaryKey column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayers requireOneByNickname(string $NickName) Return the first ChildPlayers filtered by the NickName column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayers requireOneByFirstname(string $FirstName) Return the first ChildPlayers filtered by the FirstName column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayers requireOneByLastname(string $LastName) Return the first ChildPlayers filtered by the LastName column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayers requireOneByEmailaddress(string $EmailAddress) Return the first ChildPlayers filtered by the EmailAddress column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayers requireOneByPassword(string $Password) Return the first ChildPlayers filtered by the Password column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayers requireOneByIsadministrator(boolean $IsAdministrator) Return the first ChildPlayers filtered by the IsAdministrator column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayers requireOneByActivationkey(string $ActivationKey) Return the first ChildPlayers filtered by the ActivationKey column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayers requireOneByIsenabled(boolean $IsEnabled) Return the first ChildPlayers filtered by the IsEnabled column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayers requireOneByLastconnection(string $LastConnection) Return the first ChildPlayers filtered by the LastConnection column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayers requireOneByToken(string $Token) Return the first ChildPlayers filtered by the Token column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayers requireOneByAvatarname(string $AvatarName) Return the first ChildPlayers filtered by the AvatarName column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayers requireOneByCreationdate(string $CreationDate) Return the first ChildPlayers filtered by the CreationDate column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayers requireOneByIscalendardefaultview(boolean $IsCalendarDefaultView) Return the first ChildPlayers filtered by the IsCalendarDefaultView column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayers requireOneByReceivealert(boolean $ReceiveAlert) Return the first ChildPlayers filtered by the ReceiveAlert column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayers requireOneByReceivenewletter(boolean $ReceiveNewletter) Return the first ChildPlayers filtered by the ReceiveNewletter column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayers requireOneByReceiveresult(boolean $ReceiveResult) Return the first ChildPlayers filtered by the ReceiveResult column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayers requireOneByIsreminderemailsent(boolean $IsReminderEmailSent) Return the first ChildPlayers filtered by the IsReminderEmailSent column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayers requireOneByIsresultemailsent(boolean $IsResultEmailSent) Return the first ChildPlayers filtered by the IsResultEmailSent column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPlayers[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildPlayers objects based on current ModelCriteria
 * @method     ChildPlayers[]|ObjectCollection findByPrimarykey(int $PrimaryKey) Return ChildPlayers objects filtered by the PrimaryKey column
 * @method     ChildPlayers[]|ObjectCollection findByNickname(string $NickName) Return ChildPlayers objects filtered by the NickName column
 * @method     ChildPlayers[]|ObjectCollection findByFirstname(string $FirstName) Return ChildPlayers objects filtered by the FirstName column
 * @method     ChildPlayers[]|ObjectCollection findByLastname(string $LastName) Return ChildPlayers objects filtered by the LastName column
 * @method     ChildPlayers[]|ObjectCollection findByEmailaddress(string $EmailAddress) Return ChildPlayers objects filtered by the EmailAddress column
 * @method     ChildPlayers[]|ObjectCollection findByPassword(string $Password) Return ChildPlayers objects filtered by the Password column
 * @method     ChildPlayers[]|ObjectCollection findByIsadministrator(boolean $IsAdministrator) Return ChildPlayers objects filtered by the IsAdministrator column
 * @method     ChildPlayers[]|ObjectCollection findByActivationkey(string $ActivationKey) Return ChildPlayers objects filtered by the ActivationKey column
 * @method     ChildPlayers[]|ObjectCollection findByIsenabled(boolean $IsEnabled) Return ChildPlayers objects filtered by the IsEnabled column
 * @method     ChildPlayers[]|ObjectCollection findByLastconnection(string $LastConnection) Return ChildPlayers objects filtered by the LastConnection column
 * @method     ChildPlayers[]|ObjectCollection findByToken(string $Token) Return ChildPlayers objects filtered by the Token column
 * @method     ChildPlayers[]|ObjectCollection findByAvatarname(string $AvatarName) Return ChildPlayers objects filtered by the AvatarName column
 * @method     ChildPlayers[]|ObjectCollection findByCreationdate(string $CreationDate) Return ChildPlayers objects filtered by the CreationDate column
 * @method     ChildPlayers[]|ObjectCollection findByIscalendardefaultview(boolean $IsCalendarDefaultView) Return ChildPlayers objects filtered by the IsCalendarDefaultView column
 * @method     ChildPlayers[]|ObjectCollection findByReceivealert(boolean $ReceiveAlert) Return ChildPlayers objects filtered by the ReceiveAlert column
 * @method     ChildPlayers[]|ObjectCollection findByReceivenewletter(boolean $ReceiveNewletter) Return ChildPlayers objects filtered by the ReceiveNewletter column
 * @method     ChildPlayers[]|ObjectCollection findByReceiveresult(boolean $ReceiveResult) Return ChildPlayers objects filtered by the ReceiveResult column
 * @method     ChildPlayers[]|ObjectCollection findByIsreminderemailsent(boolean $IsReminderEmailSent) Return ChildPlayers objects filtered by the IsReminderEmailSent column
 * @method     ChildPlayers[]|ObjectCollection findByIsresultemailsent(boolean $IsResultEmailSent) Return ChildPlayers objects filtered by the IsResultEmailSent column
 * @method     ChildPlayers[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class PlayersQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Base\PlayersQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Players', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildPlayersQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildPlayersQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildPlayersQuery) {
            return $criteria;
        }
        $query = new ChildPlayersQuery();
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
     * @return ChildPlayers|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = PlayersTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(PlayersTableMap::DATABASE_NAME);
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
     * @return ChildPlayers A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT PrimaryKey, NickName, FirstName, LastName, EmailAddress, Password, IsAdministrator, ActivationKey, IsEnabled, LastConnection, Token, AvatarName, CreationDate, IsCalendarDefaultView, ReceiveAlert, ReceiveNewletter, ReceiveResult, IsReminderEmailSent, IsResultEmailSent FROM players WHERE PrimaryKey = :p0';
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
            /** @var ChildPlayers $obj */
            $obj = new ChildPlayers();
            $obj->hydrate($row);
            PlayersTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildPlayers|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildPlayersQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(PlayersTableMap::COL_PRIMARYKEY, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildPlayersQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(PlayersTableMap::COL_PRIMARYKEY, $keys, Criteria::IN);
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
     * @return $this|ChildPlayersQuery The current query, for fluid interface
     */
    public function filterByPrimarykey($primarykey = null, $comparison = null)
    {
        if (is_array($primarykey)) {
            $useMinMax = false;
            if (isset($primarykey['min'])) {
                $this->addUsingAlias(PlayersTableMap::COL_PRIMARYKEY, $primarykey['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($primarykey['max'])) {
                $this->addUsingAlias(PlayersTableMap::COL_PRIMARYKEY, $primarykey['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayersTableMap::COL_PRIMARYKEY, $primarykey, $comparison);
    }

    /**
     * Filter the query on the NickName column
     *
     * Example usage:
     * <code>
     * $query->filterByNickname('fooValue');   // WHERE NickName = 'fooValue'
     * $query->filterByNickname('%fooValue%'); // WHERE NickName LIKE '%fooValue%'
     * </code>
     *
     * @param     string $nickname The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlayersQuery The current query, for fluid interface
     */
    public function filterByNickname($nickname = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($nickname)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $nickname)) {
                $nickname = str_replace('*', '%', $nickname);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PlayersTableMap::COL_NICKNAME, $nickname, $comparison);
    }

    /**
     * Filter the query on the FirstName column
     *
     * Example usage:
     * <code>
     * $query->filterByFirstname('fooValue');   // WHERE FirstName = 'fooValue'
     * $query->filterByFirstname('%fooValue%'); // WHERE FirstName LIKE '%fooValue%'
     * </code>
     *
     * @param     string $firstname The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlayersQuery The current query, for fluid interface
     */
    public function filterByFirstname($firstname = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($firstname)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $firstname)) {
                $firstname = str_replace('*', '%', $firstname);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PlayersTableMap::COL_FIRSTNAME, $firstname, $comparison);
    }

    /**
     * Filter the query on the LastName column
     *
     * Example usage:
     * <code>
     * $query->filterByLastname('fooValue');   // WHERE LastName = 'fooValue'
     * $query->filterByLastname('%fooValue%'); // WHERE LastName LIKE '%fooValue%'
     * </code>
     *
     * @param     string $lastname The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlayersQuery The current query, for fluid interface
     */
    public function filterByLastname($lastname = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($lastname)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $lastname)) {
                $lastname = str_replace('*', '%', $lastname);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PlayersTableMap::COL_LASTNAME, $lastname, $comparison);
    }

    /**
     * Filter the query on the EmailAddress column
     *
     * Example usage:
     * <code>
     * $query->filterByEmailaddress('fooValue');   // WHERE EmailAddress = 'fooValue'
     * $query->filterByEmailaddress('%fooValue%'); // WHERE EmailAddress LIKE '%fooValue%'
     * </code>
     *
     * @param     string $emailaddress The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlayersQuery The current query, for fluid interface
     */
    public function filterByEmailaddress($emailaddress = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($emailaddress)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $emailaddress)) {
                $emailaddress = str_replace('*', '%', $emailaddress);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PlayersTableMap::COL_EMAILADDRESS, $emailaddress, $comparison);
    }

    /**
     * Filter the query on the Password column
     *
     * Example usage:
     * <code>
     * $query->filterByPassword('fooValue');   // WHERE Password = 'fooValue'
     * $query->filterByPassword('%fooValue%'); // WHERE Password LIKE '%fooValue%'
     * </code>
     *
     * @param     string $password The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlayersQuery The current query, for fluid interface
     */
    public function filterByPassword($password = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($password)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $password)) {
                $password = str_replace('*', '%', $password);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PlayersTableMap::COL_PASSWORD, $password, $comparison);
    }

    /**
     * Filter the query on the IsAdministrator column
     *
     * Example usage:
     * <code>
     * $query->filterByIsadministrator(true); // WHERE IsAdministrator = true
     * $query->filterByIsadministrator('yes'); // WHERE IsAdministrator = true
     * </code>
     *
     * @param     boolean|string $isadministrator The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlayersQuery The current query, for fluid interface
     */
    public function filterByIsadministrator($isadministrator = null, $comparison = null)
    {
        if (is_string($isadministrator)) {
            $isadministrator = in_array(strtolower($isadministrator), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(PlayersTableMap::COL_ISADMINISTRATOR, $isadministrator, $comparison);
    }

    /**
     * Filter the query on the ActivationKey column
     *
     * Example usage:
     * <code>
     * $query->filterByActivationkey('fooValue');   // WHERE ActivationKey = 'fooValue'
     * $query->filterByActivationkey('%fooValue%'); // WHERE ActivationKey LIKE '%fooValue%'
     * </code>
     *
     * @param     string $activationkey The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlayersQuery The current query, for fluid interface
     */
    public function filterByActivationkey($activationkey = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($activationkey)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $activationkey)) {
                $activationkey = str_replace('*', '%', $activationkey);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PlayersTableMap::COL_ACTIVATIONKEY, $activationkey, $comparison);
    }

    /**
     * Filter the query on the IsEnabled column
     *
     * Example usage:
     * <code>
     * $query->filterByIsenabled(true); // WHERE IsEnabled = true
     * $query->filterByIsenabled('yes'); // WHERE IsEnabled = true
     * </code>
     *
     * @param     boolean|string $isenabled The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlayersQuery The current query, for fluid interface
     */
    public function filterByIsenabled($isenabled = null, $comparison = null)
    {
        if (is_string($isenabled)) {
            $isenabled = in_array(strtolower($isenabled), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(PlayersTableMap::COL_ISENABLED, $isenabled, $comparison);
    }

    /**
     * Filter the query on the LastConnection column
     *
     * Example usage:
     * <code>
     * $query->filterByLastconnection('2011-03-14'); // WHERE LastConnection = '2011-03-14'
     * $query->filterByLastconnection('now'); // WHERE LastConnection = '2011-03-14'
     * $query->filterByLastconnection(array('max' => 'yesterday')); // WHERE LastConnection > '2011-03-13'
     * </code>
     *
     * @param     mixed $lastconnection The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlayersQuery The current query, for fluid interface
     */
    public function filterByLastconnection($lastconnection = null, $comparison = null)
    {
        if (is_array($lastconnection)) {
            $useMinMax = false;
            if (isset($lastconnection['min'])) {
                $this->addUsingAlias(PlayersTableMap::COL_LASTCONNECTION, $lastconnection['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($lastconnection['max'])) {
                $this->addUsingAlias(PlayersTableMap::COL_LASTCONNECTION, $lastconnection['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayersTableMap::COL_LASTCONNECTION, $lastconnection, $comparison);
    }

    /**
     * Filter the query on the Token column
     *
     * Example usage:
     * <code>
     * $query->filterByToken('fooValue');   // WHERE Token = 'fooValue'
     * $query->filterByToken('%fooValue%'); // WHERE Token LIKE '%fooValue%'
     * </code>
     *
     * @param     string $token The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlayersQuery The current query, for fluid interface
     */
    public function filterByToken($token = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($token)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $token)) {
                $token = str_replace('*', '%', $token);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PlayersTableMap::COL_TOKEN, $token, $comparison);
    }

    /**
     * Filter the query on the AvatarName column
     *
     * Example usage:
     * <code>
     * $query->filterByAvatarname('fooValue');   // WHERE AvatarName = 'fooValue'
     * $query->filterByAvatarname('%fooValue%'); // WHERE AvatarName LIKE '%fooValue%'
     * </code>
     *
     * @param     string $avatarname The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlayersQuery The current query, for fluid interface
     */
    public function filterByAvatarname($avatarname = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($avatarname)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $avatarname)) {
                $avatarname = str_replace('*', '%', $avatarname);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PlayersTableMap::COL_AVATARNAME, $avatarname, $comparison);
    }

    /**
     * Filter the query on the CreationDate column
     *
     * Example usage:
     * <code>
     * $query->filterByCreationdate('2011-03-14'); // WHERE CreationDate = '2011-03-14'
     * $query->filterByCreationdate('now'); // WHERE CreationDate = '2011-03-14'
     * $query->filterByCreationdate(array('max' => 'yesterday')); // WHERE CreationDate > '2011-03-13'
     * </code>
     *
     * @param     mixed $creationdate The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlayersQuery The current query, for fluid interface
     */
    public function filterByCreationdate($creationdate = null, $comparison = null)
    {
        if (is_array($creationdate)) {
            $useMinMax = false;
            if (isset($creationdate['min'])) {
                $this->addUsingAlias(PlayersTableMap::COL_CREATIONDATE, $creationdate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($creationdate['max'])) {
                $this->addUsingAlias(PlayersTableMap::COL_CREATIONDATE, $creationdate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayersTableMap::COL_CREATIONDATE, $creationdate, $comparison);
    }

    /**
     * Filter the query on the IsCalendarDefaultView column
     *
     * Example usage:
     * <code>
     * $query->filterByIscalendardefaultview(true); // WHERE IsCalendarDefaultView = true
     * $query->filterByIscalendardefaultview('yes'); // WHERE IsCalendarDefaultView = true
     * </code>
     *
     * @param     boolean|string $iscalendardefaultview The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlayersQuery The current query, for fluid interface
     */
    public function filterByIscalendardefaultview($iscalendardefaultview = null, $comparison = null)
    {
        if (is_string($iscalendardefaultview)) {
            $iscalendardefaultview = in_array(strtolower($iscalendardefaultview), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(PlayersTableMap::COL_ISCALENDARDEFAULTVIEW, $iscalendardefaultview, $comparison);
    }

    /**
     * Filter the query on the ReceiveAlert column
     *
     * Example usage:
     * <code>
     * $query->filterByReceivealert(true); // WHERE ReceiveAlert = true
     * $query->filterByReceivealert('yes'); // WHERE ReceiveAlert = true
     * </code>
     *
     * @param     boolean|string $receivealert The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlayersQuery The current query, for fluid interface
     */
    public function filterByReceivealert($receivealert = null, $comparison = null)
    {
        if (is_string($receivealert)) {
            $receivealert = in_array(strtolower($receivealert), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(PlayersTableMap::COL_RECEIVEALERT, $receivealert, $comparison);
    }

    /**
     * Filter the query on the ReceiveNewletter column
     *
     * Example usage:
     * <code>
     * $query->filterByReceivenewletter(true); // WHERE ReceiveNewletter = true
     * $query->filterByReceivenewletter('yes'); // WHERE ReceiveNewletter = true
     * </code>
     *
     * @param     boolean|string $receivenewletter The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlayersQuery The current query, for fluid interface
     */
    public function filterByReceivenewletter($receivenewletter = null, $comparison = null)
    {
        if (is_string($receivenewletter)) {
            $receivenewletter = in_array(strtolower($receivenewletter), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(PlayersTableMap::COL_RECEIVENEWLETTER, $receivenewletter, $comparison);
    }

    /**
     * Filter the query on the ReceiveResult column
     *
     * Example usage:
     * <code>
     * $query->filterByReceiveresult(true); // WHERE ReceiveResult = true
     * $query->filterByReceiveresult('yes'); // WHERE ReceiveResult = true
     * </code>
     *
     * @param     boolean|string $receiveresult The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlayersQuery The current query, for fluid interface
     */
    public function filterByReceiveresult($receiveresult = null, $comparison = null)
    {
        if (is_string($receiveresult)) {
            $receiveresult = in_array(strtolower($receiveresult), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(PlayersTableMap::COL_RECEIVERESULT, $receiveresult, $comparison);
    }

    /**
     * Filter the query on the IsReminderEmailSent column
     *
     * Example usage:
     * <code>
     * $query->filterByIsreminderemailsent(true); // WHERE IsReminderEmailSent = true
     * $query->filterByIsreminderemailsent('yes'); // WHERE IsReminderEmailSent = true
     * </code>
     *
     * @param     boolean|string $isreminderemailsent The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlayersQuery The current query, for fluid interface
     */
    public function filterByIsreminderemailsent($isreminderemailsent = null, $comparison = null)
    {
        if (is_string($isreminderemailsent)) {
            $isreminderemailsent = in_array(strtolower($isreminderemailsent), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(PlayersTableMap::COL_ISREMINDEREMAILSENT, $isreminderemailsent, $comparison);
    }

    /**
     * Filter the query on the IsResultEmailSent column
     *
     * Example usage:
     * <code>
     * $query->filterByIsresultemailsent(true); // WHERE IsResultEmailSent = true
     * $query->filterByIsresultemailsent('yes'); // WHERE IsResultEmailSent = true
     * </code>
     *
     * @param     boolean|string $isresultemailsent The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlayersQuery The current query, for fluid interface
     */
    public function filterByIsresultemailsent($isresultemailsent = null, $comparison = null)
    {
        if (is_string($isresultemailsent)) {
            $isresultemailsent = in_array(strtolower($isresultemailsent), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(PlayersTableMap::COL_ISRESULTEMAILSENT, $isresultemailsent, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   ChildPlayers $players Object to remove from the list of results
     *
     * @return $this|ChildPlayersQuery The current query, for fluid interface
     */
    public function prune($players = null)
    {
        if ($players) {
            $this->addUsingAlias(PlayersTableMap::COL_PRIMARYKEY, $players->getPrimarykey(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the players table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PlayersTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            PlayersTableMap::clearInstancePool();
            PlayersTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(PlayersTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(PlayersTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            PlayersTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            PlayersTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // PlayersQuery