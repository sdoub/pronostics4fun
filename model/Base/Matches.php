<?php

namespace Base;

use \Forecasts as ChildForecasts;
use \ForecastsQuery as ChildForecastsQuery;
use \Groups as ChildGroups;
use \GroupsQuery as ChildGroupsQuery;
use \Lineups as ChildLineups;
use \LineupsQuery as ChildLineupsQuery;
use \Matches as ChildMatches;
use \MatchesQuery as ChildMatchesQuery;
use \Matchstates as ChildMatchstates;
use \MatchstatesQuery as ChildMatchstatesQuery;
use \Playermatchresults as ChildPlayermatchresults;
use \PlayermatchresultsQuery as ChildPlayermatchresultsQuery;
use \Results as ChildResults;
use \ResultsQuery as ChildResultsQuery;
use \Teams as ChildTeams;
use \TeamsQuery as ChildTeamsQuery;
use \Votes as ChildVotes;
use \VotesQuery as ChildVotesQuery;
use \DateTime;
use \Exception;
use \PDO;
use Map\MatchesTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\BadMethodCallException;
use Propel\Runtime\Exception\LogicException;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Parser\AbstractParser;
use Propel\Runtime\Util\PropelDateTime;

/**
 * Base class that represents a row from the 'matches' table.
 *
 *
 *
* @package    propel.generator..Base
*/
abstract class Matches implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\Map\\MatchesTableMap';


    /**
     * attribute to determine if this object has previously been saved.
     * @var boolean
     */
    protected $new = true;

    /**
     * attribute to determine whether this object has been deleted.
     * @var boolean
     */
    protected $deleted = false;

    /**
     * The columns that have been modified in current object.
     * Tracking modified columns allows us to only update modified columns.
     * @var array
     */
    protected $modifiedColumns = array();

    /**
     * The (virtual) columns that are added at runtime
     * The formatters can add supplementary columns based on a resultset
     * @var array
     */
    protected $virtualColumns = array();

    /**
     * The value for the primarykey field.
     * @var        int
     */
    protected $primarykey;

    /**
     * The value for the groupkey field.
     * @var        int
     */
    protected $groupkey;

    /**
     * The value for the teamhomekey field.
     * @var        int
     */
    protected $teamhomekey;

    /**
     * The value for the teamawaykey field.
     * @var        int
     */
    protected $teamawaykey;

    /**
     * The value for the scheduledate field.
     * Note: this column has a database default value of: (expression) CURRENT_TIMESTAMP
     * @var        \DateTime
     */
    protected $scheduledate;

    /**
     * The value for the isbonusmatch field.
     * Note: this column has a database default value of: false
     * @var        boolean
     */
    protected $isbonusmatch;

    /**
     * The value for the status field.
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $status;

    /**
     * The value for the externalkey field.
     * @var        int
     */
    protected $externalkey;

    /**
     * @var        ChildGroups
     */
    protected $aGroups;

    /**
     * @var        ChildTeams
     */
    protected $aTeamHome;

    /**
     * @var        ChildTeams
     */
    protected $aTeamAway;

    /**
     * @var        ObjectCollection|ChildForecasts[] Collection to store aggregation of ChildForecasts objects.
     */
    protected $collForecastss;
    protected $collForecastssPartial;

    /**
     * @var        ObjectCollection|ChildLineups[] Collection to store aggregation of ChildLineups objects.
     */
    protected $collLineupss;
    protected $collLineupssPartial;

    /**
     * @var        ObjectCollection|ChildMatchstates[] Collection to store aggregation of ChildMatchstates objects.
     */
    protected $collMatchstatess;
    protected $collMatchstatessPartial;

    /**
     * @var        ObjectCollection|ChildPlayermatchresults[] Collection to store aggregation of ChildPlayermatchresults objects.
     */
    protected $collPlayermatchresultss;
    protected $collPlayermatchresultssPartial;

    /**
     * @var        ObjectCollection|ChildResults[] Collection to store aggregation of ChildResults objects.
     */
    protected $collResultss;
    protected $collResultssPartial;

    /**
     * @var        ObjectCollection|ChildVotes[] Collection to store aggregation of ChildVotes objects.
     */
    protected $collVotess;
    protected $collVotessPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildForecasts[]
     */
    protected $forecastssScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildLineups[]
     */
    protected $lineupssScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildMatchstates[]
     */
    protected $matchstatessScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildPlayermatchresults[]
     */
    protected $playermatchresultssScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildResults[]
     */
    protected $resultssScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildVotes[]
     */
    protected $votessScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see __construct()
     */
    public function applyDefaultValues()
    {
        $this->isbonusmatch = false;
        $this->status = 0;
    }

    /**
     * Initializes internal state of Base\Matches object.
     * @see applyDefaults()
     */
    public function __construct()
    {
        $this->applyDefaultValues();
    }

    /**
     * Returns whether the object has been modified.
     *
     * @return boolean True if the object has been modified.
     */
    public function isModified()
    {
        return !!$this->modifiedColumns;
    }

    /**
     * Has specified column been modified?
     *
     * @param  string  $col column fully qualified name (TableMap::TYPE_COLNAME), e.g. Book::AUTHOR_ID
     * @return boolean True if $col has been modified.
     */
    public function isColumnModified($col)
    {
        return $this->modifiedColumns && isset($this->modifiedColumns[$col]);
    }

    /**
     * Get the columns that have been modified in this object.
     * @return array A unique list of the modified column names for this object.
     */
    public function getModifiedColumns()
    {
        return $this->modifiedColumns ? array_keys($this->modifiedColumns) : [];
    }

    /**
     * Returns whether the object has ever been saved.  This will
     * be false, if the object was retrieved from storage or was created
     * and then saved.
     *
     * @return boolean true, if the object has never been persisted.
     */
    public function isNew()
    {
        return $this->new;
    }

    /**
     * Setter for the isNew attribute.  This method will be called
     * by Propel-generated children and objects.
     *
     * @param boolean $b the state of the object.
     */
    public function setNew($b)
    {
        $this->new = (boolean) $b;
    }

    /**
     * Whether this object has been deleted.
     * @return boolean The deleted state of this object.
     */
    public function isDeleted()
    {
        return $this->deleted;
    }

    /**
     * Specify whether this object has been deleted.
     * @param  boolean $b The deleted state of this object.
     * @return void
     */
    public function setDeleted($b)
    {
        $this->deleted = (boolean) $b;
    }

    /**
     * Sets the modified state for the object to be false.
     * @param  string $col If supplied, only the specified column is reset.
     * @return void
     */
    public function resetModified($col = null)
    {
        if (null !== $col) {
            if (isset($this->modifiedColumns[$col])) {
                unset($this->modifiedColumns[$col]);
            }
        } else {
            $this->modifiedColumns = array();
        }
    }

    /**
     * Compares this with another <code>Matches</code> instance.  If
     * <code>obj</code> is an instance of <code>Matches</code>, delegates to
     * <code>equals(Matches)</code>.  Otherwise, returns <code>false</code>.
     *
     * @param  mixed   $obj The object to compare to.
     * @return boolean Whether equal to the object specified.
     */
    public function equals($obj)
    {
        if (!$obj instanceof static) {
            return false;
        }

        if ($this === $obj) {
            return true;
        }

        if (null === $this->getPrimaryKey() || null === $obj->getPrimaryKey()) {
            return false;
        }

        return $this->getPrimaryKey() === $obj->getPrimaryKey();
    }

    /**
     * Get the associative array of the virtual columns in this object
     *
     * @return array
     */
    public function getVirtualColumns()
    {
        return $this->virtualColumns;
    }

    /**
     * Checks the existence of a virtual column in this object
     *
     * @param  string  $name The virtual column name
     * @return boolean
     */
    public function hasVirtualColumn($name)
    {
        return array_key_exists($name, $this->virtualColumns);
    }

    /**
     * Get the value of a virtual column in this object
     *
     * @param  string $name The virtual column name
     * @return mixed
     *
     * @throws PropelException
     */
    public function getVirtualColumn($name)
    {
        if (!$this->hasVirtualColumn($name)) {
            throw new PropelException(sprintf('Cannot get value of inexistent virtual column %s.', $name));
        }

        return $this->virtualColumns[$name];
    }

    /**
     * Set the value of a virtual column in this object
     *
     * @param string $name  The virtual column name
     * @param mixed  $value The value to give to the virtual column
     *
     * @return $this|Matches The current object, for fluid interface
     */
    public function setVirtualColumn($name, $value)
    {
        $this->virtualColumns[$name] = $value;

        return $this;
    }

    /**
     * Logs a message using Propel::log().
     *
     * @param  string  $msg
     * @param  int     $priority One of the Propel::LOG_* logging levels
     * @return boolean
     */
    protected function log($msg, $priority = Propel::LOG_INFO)
    {
        return Propel::log(get_class($this) . ': ' . $msg, $priority);
    }

    /**
     * Export the current object properties to a string, using a given parser format
     * <code>
     * $book = BookQuery::create()->findPk(9012);
     * echo $book->exportTo('JSON');
     *  => {"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * @param  mixed   $parser                 A AbstractParser instance, or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param  boolean $includeLazyLoadColumns (optional) Whether to include lazy load(ed) columns. Defaults to TRUE.
     * @return string  The exported data
     */
    public function exportTo($parser, $includeLazyLoadColumns = true)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        return $parser->fromArray($this->toArray(TableMap::TYPE_PHPNAME, $includeLazyLoadColumns, array(), true));
    }

    /**
     * Clean up internal collections prior to serializing
     * Avoids recursive loops that turn into segmentation faults when serializing
     */
    public function __sleep()
    {
        $this->clearAllReferences();

        return array_keys(get_object_vars($this));
    }

    /**
     * Get the [primarykey] column value.
     *
     * @return int
     */
    public function getMatchPK()
    {
        return $this->primarykey;
    }

    /**
     * Get the [groupkey] column value.
     *
     * @return int
     */
    public function getGroupkey()
    {
        return $this->groupkey;
    }

    /**
     * Get the [teamhomekey] column value.
     *
     * @return int
     */
    public function getTeamhomekey()
    {
        return $this->teamhomekey;
    }

    /**
     * Get the [teamawaykey] column value.
     *
     * @return int
     */
    public function getTeamawaykey()
    {
        return $this->teamawaykey;
    }

    /**
     * Get the [optionally formatted] temporal [scheduledate] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getScheduledate($format = NULL)
    {
        if ($format === null) {
            return $this->scheduledate;
        } else {
            return $this->scheduledate instanceof \DateTime ? $this->scheduledate->format($format) : null;
        }
    }

    /**
     * Get the [isbonusmatch] column value.
     *
     * @return boolean
     */
    public function getIsbonusmatch()
    {
        return $this->isbonusmatch;
    }

    /**
     * Get the [isbonusmatch] column value.
     *
     * @return boolean
     */
    public function isIsbonusmatch()
    {
        return $this->getIsbonusmatch();
    }

    /**
     * Get the [status] column value.
     *
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Get the [externalkey] column value.
     *
     * @return int
     */
    public function getExternalkey()
    {
        return $this->externalkey;
    }

    /**
     * Set the value of [primarykey] column.
     *
     * @param int $v new value
     * @return $this|\Matches The current object (for fluent API support)
     */
    public function setMatchPK($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->primarykey !== $v) {
            $this->primarykey = $v;
            $this->modifiedColumns[MatchesTableMap::COL_PRIMARYKEY] = true;
        }

        return $this;
    } // setMatchPK()

    /**
     * Set the value of [groupkey] column.
     *
     * @param int $v new value
     * @return $this|\Matches The current object (for fluent API support)
     */
    public function setGroupkey($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->groupkey !== $v) {
            $this->groupkey = $v;
            $this->modifiedColumns[MatchesTableMap::COL_GROUPKEY] = true;
        }

        if ($this->aGroups !== null && $this->aGroups->getGroupPK() !== $v) {
            $this->aGroups = null;
        }

        return $this;
    } // setGroupkey()

    /**
     * Set the value of [teamhomekey] column.
     *
     * @param int $v new value
     * @return $this|\Matches The current object (for fluent API support)
     */
    public function setTeamhomekey($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->teamhomekey !== $v) {
            $this->teamhomekey = $v;
            $this->modifiedColumns[MatchesTableMap::COL_TEAMHOMEKEY] = true;
        }

        if ($this->aTeamHome !== null && $this->aTeamHome->getTeamPK() !== $v) {
            $this->aTeamHome = null;
        }

        return $this;
    } // setTeamhomekey()

    /**
     * Set the value of [teamawaykey] column.
     *
     * @param int $v new value
     * @return $this|\Matches The current object (for fluent API support)
     */
    public function setTeamawaykey($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->teamawaykey !== $v) {
            $this->teamawaykey = $v;
            $this->modifiedColumns[MatchesTableMap::COL_TEAMAWAYKEY] = true;
        }

        if ($this->aTeamAway !== null && $this->aTeamAway->getTeamPK() !== $v) {
            $this->aTeamAway = null;
        }

        return $this;
    } // setTeamawaykey()

    /**
     * Sets the value of [scheduledate] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTime value.
     *               Empty strings are treated as NULL.
     * @return $this|\Matches The current object (for fluent API support)
     */
    public function setScheduledate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->scheduledate !== null || $dt !== null) {
            if ($this->scheduledate === null || $dt === null || $dt->format("Y-m-d H:i:s") !== $this->scheduledate->format("Y-m-d H:i:s")) {
                $this->scheduledate = $dt === null ? null : clone $dt;
                $this->modifiedColumns[MatchesTableMap::COL_SCHEDULEDATE] = true;
            }
        } // if either are not null

        return $this;
    } // setScheduledate()

    /**
     * Sets the value of the [isbonusmatch] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param  boolean|integer|string $v The new value
     * @return $this|\Matches The current object (for fluent API support)
     */
    public function setIsbonusmatch($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->isbonusmatch !== $v) {
            $this->isbonusmatch = $v;
            $this->modifiedColumns[MatchesTableMap::COL_ISBONUSMATCH] = true;
        }

        return $this;
    } // setIsbonusmatch()

    /**
     * Set the value of [status] column.
     *
     * @param int $v new value
     * @return $this|\Matches The current object (for fluent API support)
     */
    public function setStatus($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->status !== $v) {
            $this->status = $v;
            $this->modifiedColumns[MatchesTableMap::COL_STATUS] = true;
        }

        return $this;
    } // setStatus()

    /**
     * Set the value of [externalkey] column.
     *
     * @param int $v new value
     * @return $this|\Matches The current object (for fluent API support)
     */
    public function setExternalkey($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->externalkey !== $v) {
            $this->externalkey = $v;
            $this->modifiedColumns[MatchesTableMap::COL_EXTERNALKEY] = true;
        }

        return $this;
    } // setExternalkey()

    /**
     * Indicates whether the columns in this object are only set to default values.
     *
     * This method can be used in conjunction with isModified() to indicate whether an object is both
     * modified _and_ has some values set which are non-default.
     *
     * @return boolean Whether the columns in this object are only been set with default values.
     */
    public function hasOnlyDefaultValues()
    {
            if ($this->isbonusmatch !== false) {
                return false;
            }

            if ($this->status !== 0) {
                return false;
            }

        // otherwise, everything was equal, so return TRUE
        return true;
    } // hasOnlyDefaultValues()

    /**
     * Hydrates (populates) the object variables with values from the database resultset.
     *
     * An offset (0-based "start column") is specified so that objects can be hydrated
     * with a subset of the columns in the resultset rows.  This is needed, for example,
     * for results of JOIN queries where the resultset row includes columns from two or
     * more tables.
     *
     * @param array   $row       The row returned by DataFetcher->fetch().
     * @param int     $startcol  0-based offset column which indicates which restultset column to start with.
     * @param boolean $rehydrate Whether this object is being re-hydrated from the database.
     * @param string  $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                  One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                            TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @return int             next starting column
     * @throws PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate($row, $startcol = 0, $rehydrate = false, $indexType = TableMap::TYPE_NUM)
    {
        try {

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : MatchesTableMap::translateFieldName('MatchPK', TableMap::TYPE_PHPNAME, $indexType)];
            $this->primarykey = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : MatchesTableMap::translateFieldName('Groupkey', TableMap::TYPE_PHPNAME, $indexType)];
            $this->groupkey = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : MatchesTableMap::translateFieldName('Teamhomekey', TableMap::TYPE_PHPNAME, $indexType)];
            $this->teamhomekey = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : MatchesTableMap::translateFieldName('Teamawaykey', TableMap::TYPE_PHPNAME, $indexType)];
            $this->teamawaykey = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : MatchesTableMap::translateFieldName('Scheduledate', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->scheduledate = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : MatchesTableMap::translateFieldName('Isbonusmatch', TableMap::TYPE_PHPNAME, $indexType)];
            $this->isbonusmatch = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : MatchesTableMap::translateFieldName('Status', TableMap::TYPE_PHPNAME, $indexType)];
            $this->status = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : MatchesTableMap::translateFieldName('Externalkey', TableMap::TYPE_PHPNAME, $indexType)];
            $this->externalkey = (null !== $col) ? (int) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 8; // 8 = MatchesTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Matches'), 0, $e);
        }
    }

    /**
     * Checks and repairs the internal consistency of the object.
     *
     * This method is executed after an already-instantiated object is re-hydrated
     * from the database.  It exists to check any foreign keys to make sure that
     * the objects related to the current object are correct based on foreign key.
     *
     * You can override this method in the stub class, but you should always invoke
     * the base method from the overridden method (i.e. parent::ensureConsistency()),
     * in case your model changes.
     *
     * @throws PropelException
     */
    public function ensureConsistency()
    {
        if ($this->aGroups !== null && $this->groupkey !== $this->aGroups->getGroupPK()) {
            $this->aGroups = null;
        }
        if ($this->aTeamHome !== null && $this->teamhomekey !== $this->aTeamHome->getTeamPK()) {
            $this->aTeamHome = null;
        }
        if ($this->aTeamAway !== null && $this->teamawaykey !== $this->aTeamAway->getTeamPK()) {
            $this->aTeamAway = null;
        }
    } // ensureConsistency

    /**
     * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
     *
     * This will only work if the object has been saved and has a valid primary key set.
     *
     * @param      boolean $deep (optional) Whether to also de-associated any related objects.
     * @param      ConnectionInterface $con (optional) The ConnectionInterface connection to use.
     * @return void
     * @throws PropelException - if this object is deleted, unsaved or doesn't have pk match in db
     */
    public function reload($deep = false, ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("Cannot reload a deleted object.");
        }

        if ($this->isNew()) {
            throw new PropelException("Cannot reload an unsaved object.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(MatchesTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildMatchesQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aGroups = null;
            $this->aTeamHome = null;
            $this->aTeamAway = null;
            $this->collForecastss = null;

            $this->collLineupss = null;

            $this->collMatchstatess = null;

            $this->collPlayermatchresultss = null;

            $this->collResultss = null;

            $this->collVotess = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Matches::setDeleted()
     * @see Matches::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(MatchesTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildMatchesQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
                $this->setDeleted(true);
            }
        });
    }

    /**
     * Persists this object to the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All modified related objects will also be persisted in the doSave()
     * method.  This method wraps all precipitate database operations in a
     * single transaction.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see doSave()
     */
    public function save(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("You cannot save an object that has been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(MatchesTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $isInsert = $this->isNew();
            $ret = $this->preSave($con);
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
            } else {
                $ret = $ret && $this->preUpdate($con);
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                MatchesTableMap::addInstanceToPool($this);
            } else {
                $affectedRows = 0;
            }

            return $affectedRows;
        });
    }

    /**
     * Performs the work of inserting or updating the row in the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All related objects are also updated in this method.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see save()
     */
    protected function doSave(ConnectionInterface $con)
    {
        $affectedRows = 0; // initialize var to track total num of affected rows
        if (!$this->alreadyInSave) {
            $this->alreadyInSave = true;

            // We call the save method on the following object(s) if they
            // were passed to this object by their corresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aGroups !== null) {
                if ($this->aGroups->isModified() || $this->aGroups->isNew()) {
                    $affectedRows += $this->aGroups->save($con);
                }
                $this->setGroups($this->aGroups);
            }

            if ($this->aTeamHome !== null) {
                if ($this->aTeamHome->isModified() || $this->aTeamHome->isNew()) {
                    $affectedRows += $this->aTeamHome->save($con);
                }
                $this->setTeamHome($this->aTeamHome);
            }

            if ($this->aTeamAway !== null) {
                if ($this->aTeamAway->isModified() || $this->aTeamAway->isNew()) {
                    $affectedRows += $this->aTeamAway->save($con);
                }
                $this->setTeamAway($this->aTeamAway);
            }

            if ($this->isNew() || $this->isModified()) {
                // persist changes
                if ($this->isNew()) {
                    $this->doInsert($con);
                    $affectedRows += 1;
                } else {
                    $affectedRows += $this->doUpdate($con);
                }
                $this->resetModified();
            }

            if ($this->forecastssScheduledForDeletion !== null) {
                if (!$this->forecastssScheduledForDeletion->isEmpty()) {
                    \ForecastsQuery::create()
                        ->filterByPrimaryKeys($this->forecastssScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->forecastssScheduledForDeletion = null;
                }
            }

            if ($this->collForecastss !== null) {
                foreach ($this->collForecastss as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->lineupssScheduledForDeletion !== null) {
                if (!$this->lineupssScheduledForDeletion->isEmpty()) {
                    \LineupsQuery::create()
                        ->filterByPrimaryKeys($this->lineupssScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->lineupssScheduledForDeletion = null;
                }
            }

            if ($this->collLineupss !== null) {
                foreach ($this->collLineupss as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->matchstatessScheduledForDeletion !== null) {
                if (!$this->matchstatessScheduledForDeletion->isEmpty()) {
                    \MatchstatesQuery::create()
                        ->filterByPrimaryKeys($this->matchstatessScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->matchstatessScheduledForDeletion = null;
                }
            }

            if ($this->collMatchstatess !== null) {
                foreach ($this->collMatchstatess as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->playermatchresultssScheduledForDeletion !== null) {
                if (!$this->playermatchresultssScheduledForDeletion->isEmpty()) {
                    \PlayermatchresultsQuery::create()
                        ->filterByPrimaryKeys($this->playermatchresultssScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->playermatchresultssScheduledForDeletion = null;
                }
            }

            if ($this->collPlayermatchresultss !== null) {
                foreach ($this->collPlayermatchresultss as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->resultssScheduledForDeletion !== null) {
                if (!$this->resultssScheduledForDeletion->isEmpty()) {
                    \ResultsQuery::create()
                        ->filterByPrimaryKeys($this->resultssScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->resultssScheduledForDeletion = null;
                }
            }

            if ($this->collResultss !== null) {
                foreach ($this->collResultss as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->votessScheduledForDeletion !== null) {
                if (!$this->votessScheduledForDeletion->isEmpty()) {
                    \VotesQuery::create()
                        ->filterByPrimaryKeys($this->votessScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->votessScheduledForDeletion = null;
                }
            }

            if ($this->collVotess !== null) {
                foreach ($this->collVotess as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            $this->alreadyInSave = false;

        }

        return $affectedRows;
    } // doSave()

    /**
     * Insert the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @throws PropelException
     * @see doSave()
     */
    protected function doInsert(ConnectionInterface $con)
    {
        $modifiedColumns = array();
        $index = 0;

        $this->modifiedColumns[MatchesTableMap::COL_PRIMARYKEY] = true;
        if (null !== $this->primarykey) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . MatchesTableMap::COL_PRIMARYKEY . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(MatchesTableMap::COL_PRIMARYKEY)) {
            $modifiedColumns[':p' . $index++]  = 'PrimaryKey';
        }
        if ($this->isColumnModified(MatchesTableMap::COL_GROUPKEY)) {
            $modifiedColumns[':p' . $index++]  = 'GroupKey';
        }
        if ($this->isColumnModified(MatchesTableMap::COL_TEAMHOMEKEY)) {
            $modifiedColumns[':p' . $index++]  = 'TeamHomeKey';
        }
        if ($this->isColumnModified(MatchesTableMap::COL_TEAMAWAYKEY)) {
            $modifiedColumns[':p' . $index++]  = 'TeamAwayKey';
        }
        if ($this->isColumnModified(MatchesTableMap::COL_SCHEDULEDATE)) {
            $modifiedColumns[':p' . $index++]  = 'ScheduleDate';
        }
        if ($this->isColumnModified(MatchesTableMap::COL_ISBONUSMATCH)) {
            $modifiedColumns[':p' . $index++]  = 'IsBonusMatch';
        }
        if ($this->isColumnModified(MatchesTableMap::COL_STATUS)) {
            $modifiedColumns[':p' . $index++]  = 'Status';
        }
        if ($this->isColumnModified(MatchesTableMap::COL_EXTERNALKEY)) {
            $modifiedColumns[':p' . $index++]  = 'ExternalKey';
        }

        $sql = sprintf(
            'INSERT INTO matches (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'PrimaryKey':
                        $stmt->bindValue($identifier, $this->primarykey, PDO::PARAM_INT);
                        break;
                    case 'GroupKey':
                        $stmt->bindValue($identifier, $this->groupkey, PDO::PARAM_INT);
                        break;
                    case 'TeamHomeKey':
                        $stmt->bindValue($identifier, $this->teamhomekey, PDO::PARAM_INT);
                        break;
                    case 'TeamAwayKey':
                        $stmt->bindValue($identifier, $this->teamawaykey, PDO::PARAM_INT);
                        break;
                    case 'ScheduleDate':
                        $stmt->bindValue($identifier, $this->scheduledate ? $this->scheduledate->format("Y-m-d H:i:s") : null, PDO::PARAM_STR);
                        break;
                    case 'IsBonusMatch':
                        $stmt->bindValue($identifier, (int) $this->isbonusmatch, PDO::PARAM_INT);
                        break;
                    case 'Status':
                        $stmt->bindValue($identifier, $this->status, PDO::PARAM_INT);
                        break;
                    case 'ExternalKey':
                        $stmt->bindValue($identifier, $this->externalkey, PDO::PARAM_INT);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), 0, $e);
        }

        try {
            $pk = $con->lastInsertId();
        } catch (Exception $e) {
            throw new PropelException('Unable to get autoincrement id.', 0, $e);
        }
        $this->setMatchPK($pk);

        $this->setNew(false);
    }

    /**
     * Update the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @return Integer Number of updated rows
     * @see doSave()
     */
    protected function doUpdate(ConnectionInterface $con)
    {
        $selectCriteria = $this->buildPkeyCriteria();
        $valuesCriteria = $this->buildCriteria();

        return $selectCriteria->doUpdate($valuesCriteria, $con);
    }

    /**
     * Retrieves a field from the object by name passed in as a string.
     *
     * @param      string $name name
     * @param      string $type The type of fieldname the $name is of:
     *                     one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                     TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                     Defaults to TableMap::TYPE_PHPNAME.
     * @return mixed Value of field.
     */
    public function getByName($name, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = MatchesTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
        $field = $this->getByPosition($pos);

        return $field;
    }

    /**
     * Retrieves a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param      int $pos position in xml schema
     * @return mixed Value of field at $pos
     */
    public function getByPosition($pos)
    {
        switch ($pos) {
            case 0:
                return $this->getMatchPK();
                break;
            case 1:
                return $this->getGroupkey();
                break;
            case 2:
                return $this->getTeamhomekey();
                break;
            case 3:
                return $this->getTeamawaykey();
                break;
            case 4:
                return $this->getScheduledate();
                break;
            case 5:
                return $this->getIsbonusmatch();
                break;
            case 6:
                return $this->getStatus();
                break;
            case 7:
                return $this->getExternalkey();
                break;
            default:
                return null;
                break;
        } // switch()
    }

    /**
     * Exports the object as an array.
     *
     * You can specify the key type of the array by passing one of the class
     * type constants.
     *
     * @param     string  $keyType (optional) One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     *                    TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                    Defaults to TableMap::TYPE_PHPNAME.
     * @param     boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to TRUE.
     * @param     array $alreadyDumpedObjects List of objects to skip to avoid recursion
     * @param     boolean $includeForeignObjects (optional) Whether to include hydrated related objects. Default to FALSE.
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = TableMap::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array(), $includeForeignObjects = false)
    {

        if (isset($alreadyDumpedObjects['Matches'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Matches'][$this->hashCode()] = true;
        $keys = MatchesTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getMatchPK(),
            $keys[1] => $this->getGroupkey(),
            $keys[2] => $this->getTeamhomekey(),
            $keys[3] => $this->getTeamawaykey(),
            $keys[4] => $this->getScheduledate(),
            $keys[5] => $this->getIsbonusmatch(),
            $keys[6] => $this->getStatus(),
            $keys[7] => $this->getExternalkey(),
        );

        $utc = new \DateTimeZone('utc');
        if ($result[$keys[4]] instanceof \DateTime) {
            // When changing timezone we don't want to change existing instances
            $dateTime = clone $result[$keys[4]];
            $result[$keys[4]] = $dateTime->setTimezone($utc)->format('Y-m-d\TH:i:s\Z');
        }

        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aGroups) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'groups';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'groups';
                        break;
                    default:
                        $key = 'Groups';
                }

                $result[$key] = $this->aGroups->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aTeamHome) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'teams';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'teams';
                        break;
                    default:
                        $key = 'Teams';
                }

                $result[$key] = $this->aTeamHome->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aTeamAway) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'teams';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'teams';
                        break;
                    default:
                        $key = 'Teams';
                }

                $result[$key] = $this->aTeamAway->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collForecastss) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'forecastss';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'forecastss';
                        break;
                    default:
                        $key = 'Forecastss';
                }

                $result[$key] = $this->collForecastss->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collLineupss) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'lineupss';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'lineupss';
                        break;
                    default:
                        $key = 'Lineupss';
                }

                $result[$key] = $this->collLineupss->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collMatchstatess) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'matchstatess';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'matchstatess';
                        break;
                    default:
                        $key = 'Matchstatess';
                }

                $result[$key] = $this->collMatchstatess->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPlayermatchresultss) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'playermatchresultss';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'playermatchresultss';
                        break;
                    default:
                        $key = 'Playermatchresultss';
                }

                $result[$key] = $this->collPlayermatchresultss->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collResultss) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'resultss';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'resultss';
                        break;
                    default:
                        $key = 'Resultss';
                }

                $result[$key] = $this->collResultss->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collVotess) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'votess';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'votess';
                        break;
                    default:
                        $key = 'Votess';
                }

                $result[$key] = $this->collVotess->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
        }

        return $result;
    }

    /**
     * Sets a field from the object by name passed in as a string.
     *
     * @param  string $name
     * @param  mixed  $value field value
     * @param  string $type The type of fieldname the $name is of:
     *                one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                Defaults to TableMap::TYPE_PHPNAME.
     * @return $this|\Matches
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = MatchesTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\Matches
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setMatchPK($value);
                break;
            case 1:
                $this->setGroupkey($value);
                break;
            case 2:
                $this->setTeamhomekey($value);
                break;
            case 3:
                $this->setTeamawaykey($value);
                break;
            case 4:
                $this->setScheduledate($value);
                break;
            case 5:
                $this->setIsbonusmatch($value);
                break;
            case 6:
                $this->setStatus($value);
                break;
            case 7:
                $this->setExternalkey($value);
                break;
        } // switch()

        return $this;
    }

    /**
     * Populates the object using an array.
     *
     * This is particularly useful when populating an object from one of the
     * request arrays (e.g. $_POST).  This method goes through the column
     * names, checking to see whether a matching key exists in populated
     * array. If so the setByName() method is called for that column.
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_PHPNAME.
     *
     * @param      array  $arr     An array to populate the object from.
     * @param      string $keyType The type of keys the array uses.
     * @return void
     */
    public function fromArray($arr, $keyType = TableMap::TYPE_PHPNAME)
    {
        $keys = MatchesTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setMatchPK($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setGroupkey($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setTeamhomekey($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setTeamawaykey($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setScheduledate($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setIsbonusmatch($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setStatus($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setExternalkey($arr[$keys[7]]);
        }
    }

     /**
     * Populate the current object from a string, using a given parser format
     * <code>
     * $book = new Book();
     * $book->importFrom('JSON', '{"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_PHPNAME.
     *
     * @param mixed $parser A AbstractParser instance,
     *                       or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param string $data The source data to import from
     * @param string $keyType The type of keys the array uses.
     *
     * @return $this|\Matches The current object, for fluid interface
     */
    public function importFrom($parser, $data, $keyType = TableMap::TYPE_PHPNAME)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        $this->fromArray($parser->toArray($data), $keyType);

        return $this;
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(MatchesTableMap::DATABASE_NAME);

        if ($this->isColumnModified(MatchesTableMap::COL_PRIMARYKEY)) {
            $criteria->add(MatchesTableMap::COL_PRIMARYKEY, $this->primarykey);
        }
        if ($this->isColumnModified(MatchesTableMap::COL_GROUPKEY)) {
            $criteria->add(MatchesTableMap::COL_GROUPKEY, $this->groupkey);
        }
        if ($this->isColumnModified(MatchesTableMap::COL_TEAMHOMEKEY)) {
            $criteria->add(MatchesTableMap::COL_TEAMHOMEKEY, $this->teamhomekey);
        }
        if ($this->isColumnModified(MatchesTableMap::COL_TEAMAWAYKEY)) {
            $criteria->add(MatchesTableMap::COL_TEAMAWAYKEY, $this->teamawaykey);
        }
        if ($this->isColumnModified(MatchesTableMap::COL_SCHEDULEDATE)) {
            $criteria->add(MatchesTableMap::COL_SCHEDULEDATE, $this->scheduledate);
        }
        if ($this->isColumnModified(MatchesTableMap::COL_ISBONUSMATCH)) {
            $criteria->add(MatchesTableMap::COL_ISBONUSMATCH, $this->isbonusmatch);
        }
        if ($this->isColumnModified(MatchesTableMap::COL_STATUS)) {
            $criteria->add(MatchesTableMap::COL_STATUS, $this->status);
        }
        if ($this->isColumnModified(MatchesTableMap::COL_EXTERNALKEY)) {
            $criteria->add(MatchesTableMap::COL_EXTERNALKEY, $this->externalkey);
        }

        return $criteria;
    }

    /**
     * Builds a Criteria object containing the primary key for this object.
     *
     * Unlike buildCriteria() this method includes the primary key values regardless
     * of whether or not they have been modified.
     *
     * @throws LogicException if no primary key is defined
     *
     * @return Criteria The Criteria object containing value(s) for primary key(s).
     */
    public function buildPkeyCriteria()
    {
        $criteria = ChildMatchesQuery::create();
        $criteria->add(MatchesTableMap::COL_PRIMARYKEY, $this->primarykey);

        return $criteria;
    }

    /**
     * If the primary key is not null, return the hashcode of the
     * primary key. Otherwise, return the hash code of the object.
     *
     * @return int Hashcode
     */
    public function hashCode()
    {
        $validPk = null !== $this->getMatchPK();

        $validPrimaryKeyFKs = 0;
        $primaryKeyFKs = [];

        if ($validPk) {
            return crc32(json_encode($this->getPrimaryKey(), JSON_UNESCAPED_UNICODE));
        } elseif ($validPrimaryKeyFKs) {
            return crc32(json_encode($primaryKeyFKs, JSON_UNESCAPED_UNICODE));
        }

        return spl_object_hash($this);
    }

    /**
     * Returns the primary key for this object (row).
     * @return int
     */
    public function getPrimaryKey()
    {
        return $this->getMatchPK();
    }

    /**
     * Generic method to set the primary key (primarykey column).
     *
     * @param       int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setMatchPK($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {
        return null === $this->getMatchPK();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of \Matches (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setGroupkey($this->getGroupkey());
        $copyObj->setTeamhomekey($this->getTeamhomekey());
        $copyObj->setTeamawaykey($this->getTeamawaykey());
        $copyObj->setScheduledate($this->getScheduledate());
        $copyObj->setIsbonusmatch($this->getIsbonusmatch());
        $copyObj->setStatus($this->getStatus());
        $copyObj->setExternalkey($this->getExternalkey());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getForecastss() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addForecasts($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getLineupss() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addLineups($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getMatchstatess() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addMatchstates($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPlayermatchresultss() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPlayermatchresults($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getResultss() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addResults($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getVotess() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addVotes($relObj->copy($deepCopy));
                }
            }

        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setMatchPK(NULL); // this is a auto-increment column, so set to default value
        }
    }

    /**
     * Makes a copy of this object that will be inserted as a new row in table when saved.
     * It creates a new object filling in the simple attributes, but skipping any primary
     * keys that are defined for the table.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param  boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @return \Matches Clone of current object.
     * @throws PropelException
     */
    public function copy($deepCopy = false)
    {
        // we use get_class(), because this might be a subclass
        $clazz = get_class($this);
        $copyObj = new $clazz();
        $this->copyInto($copyObj, $deepCopy);

        return $copyObj;
    }

    /**
     * Declares an association between this object and a ChildGroups object.
     *
     * @param  ChildGroups $v
     * @return $this|\Matches The current object (for fluent API support)
     * @throws PropelException
     */
    public function setGroups(ChildGroups $v = null)
    {
        if ($v === null) {
            $this->setGroupkey(NULL);
        } else {
            $this->setGroupkey($v->getGroupPK());
        }

        $this->aGroups = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildGroups object, it will not be re-added.
        if ($v !== null) {
            $v->addMatches($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildGroups object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildGroups The associated ChildGroups object.
     * @throws PropelException
     */
    public function getGroups(ConnectionInterface $con = null)
    {
        if ($this->aGroups === null && ($this->groupkey !== null)) {
            $this->aGroups = ChildGroupsQuery::create()->findPk($this->groupkey, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aGroups->addMatchess($this);
             */
        }

        return $this->aGroups;
    }

    /**
     * Declares an association between this object and a ChildTeams object.
     *
     * @param  ChildTeams $v
     * @return $this|\Matches The current object (for fluent API support)
     * @throws PropelException
     */
    public function setTeamHome(ChildTeams $v = null)
    {
        if ($v === null) {
            $this->setTeamhomekey(NULL);
        } else {
            $this->setTeamhomekey($v->getTeamPK());
        }

        $this->aTeamHome = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildTeams object, it will not be re-added.
        if ($v !== null) {
            $v->addMatchesRelatedByTeamhomekey($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildTeams object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildTeams The associated ChildTeams object.
     * @throws PropelException
     */
    public function getTeamHome(ConnectionInterface $con = null)
    {
        if ($this->aTeamHome === null && ($this->teamhomekey !== null)) {
            $this->aTeamHome = ChildTeamsQuery::create()->findPk($this->teamhomekey, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aTeamHome->addMatchessRelatedByTeamhomekey($this);
             */
        }

        return $this->aTeamHome;
    }

    /**
     * Declares an association between this object and a ChildTeams object.
     *
     * @param  ChildTeams $v
     * @return $this|\Matches The current object (for fluent API support)
     * @throws PropelException
     */
    public function setTeamAway(ChildTeams $v = null)
    {
        if ($v === null) {
            $this->setTeamawaykey(NULL);
        } else {
            $this->setTeamawaykey($v->getTeamPK());
        }

        $this->aTeamAway = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildTeams object, it will not be re-added.
        if ($v !== null) {
            $v->addMatchesRelatedByTeamawaykey($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildTeams object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildTeams The associated ChildTeams object.
     * @throws PropelException
     */
    public function getTeamAway(ConnectionInterface $con = null)
    {
        if ($this->aTeamAway === null && ($this->teamawaykey !== null)) {
            $this->aTeamAway = ChildTeamsQuery::create()->findPk($this->teamawaykey, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aTeamAway->addMatchessRelatedByTeamawaykey($this);
             */
        }

        return $this->aTeamAway;
    }


    /**
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param      string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('Forecasts' == $relationName) {
            return $this->initForecastss();
        }
        if ('Lineups' == $relationName) {
            return $this->initLineupss();
        }
        if ('Matchstates' == $relationName) {
            return $this->initMatchstatess();
        }
        if ('Playermatchresults' == $relationName) {
            return $this->initPlayermatchresultss();
        }
        if ('Results' == $relationName) {
            return $this->initResultss();
        }
        if ('Votes' == $relationName) {
            return $this->initVotess();
        }
    }

    /**
     * Clears out the collForecastss collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addForecastss()
     */
    public function clearForecastss()
    {
        $this->collForecastss = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collForecastss collection loaded partially.
     */
    public function resetPartialForecastss($v = true)
    {
        $this->collForecastssPartial = $v;
    }

    /**
     * Initializes the collForecastss collection.
     *
     * By default this just sets the collForecastss collection to an empty array (like clearcollForecastss());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initForecastss($overrideExisting = true)
    {
        if (null !== $this->collForecastss && !$overrideExisting) {
            return;
        }
        $this->collForecastss = new ObjectCollection();
        $this->collForecastss->setModel('\Forecasts');
    }

    /**
     * Gets an array of ChildForecasts objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildMatches is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildForecasts[] List of ChildForecasts objects
     * @throws PropelException
     */
    public function getForecastss(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collForecastssPartial && !$this->isNew();
        if (null === $this->collForecastss || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collForecastss) {
                // return empty collection
                $this->initForecastss();
            } else {
                $collForecastss = ChildForecastsQuery::create(null, $criteria)
                    ->filterByMatches($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collForecastssPartial && count($collForecastss)) {
                        $this->initForecastss(false);

                        foreach ($collForecastss as $obj) {
                            if (false == $this->collForecastss->contains($obj)) {
                                $this->collForecastss->append($obj);
                            }
                        }

                        $this->collForecastssPartial = true;
                    }

                    return $collForecastss;
                }

                if ($partial && $this->collForecastss) {
                    foreach ($this->collForecastss as $obj) {
                        if ($obj->isNew()) {
                            $collForecastss[] = $obj;
                        }
                    }
                }

                $this->collForecastss = $collForecastss;
                $this->collForecastssPartial = false;
            }
        }

        return $this->collForecastss;
    }

    /**
     * Sets a collection of ChildForecasts objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $forecastss A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildMatches The current object (for fluent API support)
     */
    public function setForecastss(Collection $forecastss, ConnectionInterface $con = null)
    {
        /** @var ChildForecasts[] $forecastssToDelete */
        $forecastssToDelete = $this->getForecastss(new Criteria(), $con)->diff($forecastss);


        $this->forecastssScheduledForDeletion = $forecastssToDelete;

        foreach ($forecastssToDelete as $forecastsRemoved) {
            $forecastsRemoved->setMatches(null);
        }

        $this->collForecastss = null;
        foreach ($forecastss as $forecasts) {
            $this->addForecasts($forecasts);
        }

        $this->collForecastss = $forecastss;
        $this->collForecastssPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Forecasts objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Forecasts objects.
     * @throws PropelException
     */
    public function countForecastss(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collForecastssPartial && !$this->isNew();
        if (null === $this->collForecastss || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collForecastss) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getForecastss());
            }

            $query = ChildForecastsQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByMatches($this)
                ->count($con);
        }

        return count($this->collForecastss);
    }

    /**
     * Method called to associate a ChildForecasts object to this object
     * through the ChildForecasts foreign key attribute.
     *
     * @param  ChildForecasts $l ChildForecasts
     * @return $this|\Matches The current object (for fluent API support)
     */
    public function addForecasts(ChildForecasts $l)
    {
        if ($this->collForecastss === null) {
            $this->initForecastss();
            $this->collForecastssPartial = true;
        }

        if (!$this->collForecastss->contains($l)) {
            $this->doAddForecasts($l);
        }

        return $this;
    }

    /**
     * @param ChildForecasts $forecasts The ChildForecasts object to add.
     */
    protected function doAddForecasts(ChildForecasts $forecasts)
    {
        $this->collForecastss[]= $forecasts;
        $forecasts->setMatches($this);
    }

    /**
     * @param  ChildForecasts $forecasts The ChildForecasts object to remove.
     * @return $this|ChildMatches The current object (for fluent API support)
     */
    public function removeForecasts(ChildForecasts $forecasts)
    {
        if ($this->getForecastss()->contains($forecasts)) {
            $pos = $this->collForecastss->search($forecasts);
            $this->collForecastss->remove($pos);
            if (null === $this->forecastssScheduledForDeletion) {
                $this->forecastssScheduledForDeletion = clone $this->collForecastss;
                $this->forecastssScheduledForDeletion->clear();
            }
            $this->forecastssScheduledForDeletion[]= clone $forecasts;
            $forecasts->setMatches(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Matches is new, it will return
     * an empty collection; or if this Matches has previously
     * been saved, it will retrieve related Forecastss from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Matches.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildForecasts[] List of ChildForecasts objects
     */
    public function getForecastssJoinForecastPlayer(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildForecastsQuery::create(null, $criteria);
        $query->joinWith('ForecastPlayer', $joinBehavior);

        return $this->getForecastss($query, $con);
    }

    /**
     * Clears out the collLineupss collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addLineupss()
     */
    public function clearLineupss()
    {
        $this->collLineupss = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collLineupss collection loaded partially.
     */
    public function resetPartialLineupss($v = true)
    {
        $this->collLineupssPartial = $v;
    }

    /**
     * Initializes the collLineupss collection.
     *
     * By default this just sets the collLineupss collection to an empty array (like clearcollLineupss());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initLineupss($overrideExisting = true)
    {
        if (null !== $this->collLineupss && !$overrideExisting) {
            return;
        }
        $this->collLineupss = new ObjectCollection();
        $this->collLineupss->setModel('\Lineups');
    }

    /**
     * Gets an array of ChildLineups objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildMatches is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildLineups[] List of ChildLineups objects
     * @throws PropelException
     */
    public function getLineupss(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collLineupssPartial && !$this->isNew();
        if (null === $this->collLineupss || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collLineupss) {
                // return empty collection
                $this->initLineupss();
            } else {
                $collLineupss = ChildLineupsQuery::create(null, $criteria)
                    ->filterByLineUpMatch($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collLineupssPartial && count($collLineupss)) {
                        $this->initLineupss(false);

                        foreach ($collLineupss as $obj) {
                            if (false == $this->collLineupss->contains($obj)) {
                                $this->collLineupss->append($obj);
                            }
                        }

                        $this->collLineupssPartial = true;
                    }

                    return $collLineupss;
                }

                if ($partial && $this->collLineupss) {
                    foreach ($this->collLineupss as $obj) {
                        if ($obj->isNew()) {
                            $collLineupss[] = $obj;
                        }
                    }
                }

                $this->collLineupss = $collLineupss;
                $this->collLineupssPartial = false;
            }
        }

        return $this->collLineupss;
    }

    /**
     * Sets a collection of ChildLineups objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $lineupss A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildMatches The current object (for fluent API support)
     */
    public function setLineupss(Collection $lineupss, ConnectionInterface $con = null)
    {
        /** @var ChildLineups[] $lineupssToDelete */
        $lineupssToDelete = $this->getLineupss(new Criteria(), $con)->diff($lineupss);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->lineupssScheduledForDeletion = clone $lineupssToDelete;

        foreach ($lineupssToDelete as $lineupsRemoved) {
            $lineupsRemoved->setLineUpMatch(null);
        }

        $this->collLineupss = null;
        foreach ($lineupss as $lineups) {
            $this->addLineups($lineups);
        }

        $this->collLineupss = $lineupss;
        $this->collLineupssPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Lineups objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Lineups objects.
     * @throws PropelException
     */
    public function countLineupss(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collLineupssPartial && !$this->isNew();
        if (null === $this->collLineupss || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collLineupss) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getLineupss());
            }

            $query = ChildLineupsQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByLineUpMatch($this)
                ->count($con);
        }

        return count($this->collLineupss);
    }

    /**
     * Method called to associate a ChildLineups object to this object
     * through the ChildLineups foreign key attribute.
     *
     * @param  ChildLineups $l ChildLineups
     * @return $this|\Matches The current object (for fluent API support)
     */
    public function addLineups(ChildLineups $l)
    {
        if ($this->collLineupss === null) {
            $this->initLineupss();
            $this->collLineupssPartial = true;
        }

        if (!$this->collLineupss->contains($l)) {
            $this->doAddLineups($l);
        }

        return $this;
    }

    /**
     * @param ChildLineups $lineups The ChildLineups object to add.
     */
    protected function doAddLineups(ChildLineups $lineups)
    {
        $this->collLineupss[]= $lineups;
        $lineups->setLineUpMatch($this);
    }

    /**
     * @param  ChildLineups $lineups The ChildLineups object to remove.
     * @return $this|ChildMatches The current object (for fluent API support)
     */
    public function removeLineups(ChildLineups $lineups)
    {
        if ($this->getLineupss()->contains($lineups)) {
            $pos = $this->collLineupss->search($lineups);
            $this->collLineupss->remove($pos);
            if (null === $this->lineupssScheduledForDeletion) {
                $this->lineupssScheduledForDeletion = clone $this->collLineupss;
                $this->lineupssScheduledForDeletion->clear();
            }
            $this->lineupssScheduledForDeletion[]= clone $lineups;
            $lineups->setLineUpMatch(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Matches is new, it will return
     * an empty collection; or if this Matches has previously
     * been saved, it will retrieve related Lineupss from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Matches.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildLineups[] List of ChildLineups objects
     */
    public function getLineupssJoinLineUpTeam(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildLineupsQuery::create(null, $criteria);
        $query->joinWith('LineUpTeam', $joinBehavior);

        return $this->getLineupss($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Matches is new, it will return
     * an empty collection; or if this Matches has previously
     * been saved, it will retrieve related Lineupss from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Matches.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildLineups[] List of ChildLineups objects
     */
    public function getLineupssJoinLineUpTeamPlayer(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildLineupsQuery::create(null, $criteria);
        $query->joinWith('LineUpTeamPlayer', $joinBehavior);

        return $this->getLineupss($query, $con);
    }

    /**
     * Clears out the collMatchstatess collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addMatchstatess()
     */
    public function clearMatchstatess()
    {
        $this->collMatchstatess = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collMatchstatess collection loaded partially.
     */
    public function resetPartialMatchstatess($v = true)
    {
        $this->collMatchstatessPartial = $v;
    }

    /**
     * Initializes the collMatchstatess collection.
     *
     * By default this just sets the collMatchstatess collection to an empty array (like clearcollMatchstatess());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initMatchstatess($overrideExisting = true)
    {
        if (null !== $this->collMatchstatess && !$overrideExisting) {
            return;
        }
        $this->collMatchstatess = new ObjectCollection();
        $this->collMatchstatess->setModel('\Matchstates');
    }

    /**
     * Gets an array of ChildMatchstates objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildMatches is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildMatchstates[] List of ChildMatchstates objects
     * @throws PropelException
     */
    public function getMatchstatess(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collMatchstatessPartial && !$this->isNew();
        if (null === $this->collMatchstatess || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collMatchstatess) {
                // return empty collection
                $this->initMatchstatess();
            } else {
                $collMatchstatess = ChildMatchstatesQuery::create(null, $criteria)
                    ->filterByMatchState($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collMatchstatessPartial && count($collMatchstatess)) {
                        $this->initMatchstatess(false);

                        foreach ($collMatchstatess as $obj) {
                            if (false == $this->collMatchstatess->contains($obj)) {
                                $this->collMatchstatess->append($obj);
                            }
                        }

                        $this->collMatchstatessPartial = true;
                    }

                    return $collMatchstatess;
                }

                if ($partial && $this->collMatchstatess) {
                    foreach ($this->collMatchstatess as $obj) {
                        if ($obj->isNew()) {
                            $collMatchstatess[] = $obj;
                        }
                    }
                }

                $this->collMatchstatess = $collMatchstatess;
                $this->collMatchstatessPartial = false;
            }
        }

        return $this->collMatchstatess;
    }

    /**
     * Sets a collection of ChildMatchstates objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $matchstatess A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildMatches The current object (for fluent API support)
     */
    public function setMatchstatess(Collection $matchstatess, ConnectionInterface $con = null)
    {
        /** @var ChildMatchstates[] $matchstatessToDelete */
        $matchstatessToDelete = $this->getMatchstatess(new Criteria(), $con)->diff($matchstatess);


        $this->matchstatessScheduledForDeletion = $matchstatessToDelete;

        foreach ($matchstatessToDelete as $matchstatesRemoved) {
            $matchstatesRemoved->setMatchState(null);
        }

        $this->collMatchstatess = null;
        foreach ($matchstatess as $matchstates) {
            $this->addMatchstates($matchstates);
        }

        $this->collMatchstatess = $matchstatess;
        $this->collMatchstatessPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Matchstates objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Matchstates objects.
     * @throws PropelException
     */
    public function countMatchstatess(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collMatchstatessPartial && !$this->isNew();
        if (null === $this->collMatchstatess || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collMatchstatess) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getMatchstatess());
            }

            $query = ChildMatchstatesQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByMatchState($this)
                ->count($con);
        }

        return count($this->collMatchstatess);
    }

    /**
     * Method called to associate a ChildMatchstates object to this object
     * through the ChildMatchstates foreign key attribute.
     *
     * @param  ChildMatchstates $l ChildMatchstates
     * @return $this|\Matches The current object (for fluent API support)
     */
    public function addMatchstates(ChildMatchstates $l)
    {
        if ($this->collMatchstatess === null) {
            $this->initMatchstatess();
            $this->collMatchstatessPartial = true;
        }

        if (!$this->collMatchstatess->contains($l)) {
            $this->doAddMatchstates($l);
        }

        return $this;
    }

    /**
     * @param ChildMatchstates $matchstates The ChildMatchstates object to add.
     */
    protected function doAddMatchstates(ChildMatchstates $matchstates)
    {
        $this->collMatchstatess[]= $matchstates;
        $matchstates->setMatchState($this);
    }

    /**
     * @param  ChildMatchstates $matchstates The ChildMatchstates object to remove.
     * @return $this|ChildMatches The current object (for fluent API support)
     */
    public function removeMatchstates(ChildMatchstates $matchstates)
    {
        if ($this->getMatchstatess()->contains($matchstates)) {
            $pos = $this->collMatchstatess->search($matchstates);
            $this->collMatchstatess->remove($pos);
            if (null === $this->matchstatessScheduledForDeletion) {
                $this->matchstatessScheduledForDeletion = clone $this->collMatchstatess;
                $this->matchstatessScheduledForDeletion->clear();
            }
            $this->matchstatessScheduledForDeletion[]= clone $matchstates;
            $matchstates->setMatchState(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Matches is new, it will return
     * an empty collection; or if this Matches has previously
     * been saved, it will retrieve related Matchstatess from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Matches.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildMatchstates[] List of ChildMatchstates objects
     */
    public function getMatchstatessJoinEvents(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildMatchstatesQuery::create(null, $criteria);
        $query->joinWith('Events', $joinBehavior);

        return $this->getMatchstatess($query, $con);
    }

    /**
     * Clears out the collPlayermatchresultss collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addPlayermatchresultss()
     */
    public function clearPlayermatchresultss()
    {
        $this->collPlayermatchresultss = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collPlayermatchresultss collection loaded partially.
     */
    public function resetPartialPlayermatchresultss($v = true)
    {
        $this->collPlayermatchresultssPartial = $v;
    }

    /**
     * Initializes the collPlayermatchresultss collection.
     *
     * By default this just sets the collPlayermatchresultss collection to an empty array (like clearcollPlayermatchresultss());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPlayermatchresultss($overrideExisting = true)
    {
        if (null !== $this->collPlayermatchresultss && !$overrideExisting) {
            return;
        }
        $this->collPlayermatchresultss = new ObjectCollection();
        $this->collPlayermatchresultss->setModel('\Playermatchresults');
    }

    /**
     * Gets an array of ChildPlayermatchresults objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildMatches is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildPlayermatchresults[] List of ChildPlayermatchresults objects
     * @throws PropelException
     */
    public function getPlayermatchresultss(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collPlayermatchresultssPartial && !$this->isNew();
        if (null === $this->collPlayermatchresultss || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPlayermatchresultss) {
                // return empty collection
                $this->initPlayermatchresultss();
            } else {
                $collPlayermatchresultss = ChildPlayermatchresultsQuery::create(null, $criteria)
                    ->filterByMatchPlayerResult($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collPlayermatchresultssPartial && count($collPlayermatchresultss)) {
                        $this->initPlayermatchresultss(false);

                        foreach ($collPlayermatchresultss as $obj) {
                            if (false == $this->collPlayermatchresultss->contains($obj)) {
                                $this->collPlayermatchresultss->append($obj);
                            }
                        }

                        $this->collPlayermatchresultssPartial = true;
                    }

                    return $collPlayermatchresultss;
                }

                if ($partial && $this->collPlayermatchresultss) {
                    foreach ($this->collPlayermatchresultss as $obj) {
                        if ($obj->isNew()) {
                            $collPlayermatchresultss[] = $obj;
                        }
                    }
                }

                $this->collPlayermatchresultss = $collPlayermatchresultss;
                $this->collPlayermatchresultssPartial = false;
            }
        }

        return $this->collPlayermatchresultss;
    }

    /**
     * Sets a collection of ChildPlayermatchresults objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $playermatchresultss A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildMatches The current object (for fluent API support)
     */
    public function setPlayermatchresultss(Collection $playermatchresultss, ConnectionInterface $con = null)
    {
        /** @var ChildPlayermatchresults[] $playermatchresultssToDelete */
        $playermatchresultssToDelete = $this->getPlayermatchresultss(new Criteria(), $con)->diff($playermatchresultss);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->playermatchresultssScheduledForDeletion = clone $playermatchresultssToDelete;

        foreach ($playermatchresultssToDelete as $playermatchresultsRemoved) {
            $playermatchresultsRemoved->setMatchPlayerResult(null);
        }

        $this->collPlayermatchresultss = null;
        foreach ($playermatchresultss as $playermatchresults) {
            $this->addPlayermatchresults($playermatchresults);
        }

        $this->collPlayermatchresultss = $playermatchresultss;
        $this->collPlayermatchresultssPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Playermatchresults objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Playermatchresults objects.
     * @throws PropelException
     */
    public function countPlayermatchresultss(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collPlayermatchresultssPartial && !$this->isNew();
        if (null === $this->collPlayermatchresultss || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPlayermatchresultss) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPlayermatchresultss());
            }

            $query = ChildPlayermatchresultsQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByMatchPlayerResult($this)
                ->count($con);
        }

        return count($this->collPlayermatchresultss);
    }

    /**
     * Method called to associate a ChildPlayermatchresults object to this object
     * through the ChildPlayermatchresults foreign key attribute.
     *
     * @param  ChildPlayermatchresults $l ChildPlayermatchresults
     * @return $this|\Matches The current object (for fluent API support)
     */
    public function addPlayermatchresults(ChildPlayermatchresults $l)
    {
        if ($this->collPlayermatchresultss === null) {
            $this->initPlayermatchresultss();
            $this->collPlayermatchresultssPartial = true;
        }

        if (!$this->collPlayermatchresultss->contains($l)) {
            $this->doAddPlayermatchresults($l);
        }

        return $this;
    }

    /**
     * @param ChildPlayermatchresults $playermatchresults The ChildPlayermatchresults object to add.
     */
    protected function doAddPlayermatchresults(ChildPlayermatchresults $playermatchresults)
    {
        $this->collPlayermatchresultss[]= $playermatchresults;
        $playermatchresults->setMatchPlayerResult($this);
    }

    /**
     * @param  ChildPlayermatchresults $playermatchresults The ChildPlayermatchresults object to remove.
     * @return $this|ChildMatches The current object (for fluent API support)
     */
    public function removePlayermatchresults(ChildPlayermatchresults $playermatchresults)
    {
        if ($this->getPlayermatchresultss()->contains($playermatchresults)) {
            $pos = $this->collPlayermatchresultss->search($playermatchresults);
            $this->collPlayermatchresultss->remove($pos);
            if (null === $this->playermatchresultssScheduledForDeletion) {
                $this->playermatchresultssScheduledForDeletion = clone $this->collPlayermatchresultss;
                $this->playermatchresultssScheduledForDeletion->clear();
            }
            $this->playermatchresultssScheduledForDeletion[]= clone $playermatchresults;
            $playermatchresults->setMatchPlayerResult(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Matches is new, it will return
     * an empty collection; or if this Matches has previously
     * been saved, it will retrieve related Playermatchresultss from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Matches.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildPlayermatchresults[] List of ChildPlayermatchresults objects
     */
    public function getPlayermatchresultssJoinPlayerResult(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildPlayermatchresultsQuery::create(null, $criteria);
        $query->joinWith('PlayerResult', $joinBehavior);

        return $this->getPlayermatchresultss($query, $con);
    }

    /**
     * Clears out the collResultss collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addResultss()
     */
    public function clearResultss()
    {
        $this->collResultss = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collResultss collection loaded partially.
     */
    public function resetPartialResultss($v = true)
    {
        $this->collResultssPartial = $v;
    }

    /**
     * Initializes the collResultss collection.
     *
     * By default this just sets the collResultss collection to an empty array (like clearcollResultss());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initResultss($overrideExisting = true)
    {
        if (null !== $this->collResultss && !$overrideExisting) {
            return;
        }
        $this->collResultss = new ObjectCollection();
        $this->collResultss->setModel('\Results');
    }

    /**
     * Gets an array of ChildResults objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildMatches is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildResults[] List of ChildResults objects
     * @throws PropelException
     */
    public function getResultss(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collResultssPartial && !$this->isNew();
        if (null === $this->collResultss || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collResultss) {
                // return empty collection
                $this->initResultss();
            } else {
                $collResultss = ChildResultsQuery::create(null, $criteria)
                    ->filterByMatches($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collResultssPartial && count($collResultss)) {
                        $this->initResultss(false);

                        foreach ($collResultss as $obj) {
                            if (false == $this->collResultss->contains($obj)) {
                                $this->collResultss->append($obj);
                            }
                        }

                        $this->collResultssPartial = true;
                    }

                    return $collResultss;
                }

                if ($partial && $this->collResultss) {
                    foreach ($this->collResultss as $obj) {
                        if ($obj->isNew()) {
                            $collResultss[] = $obj;
                        }
                    }
                }

                $this->collResultss = $collResultss;
                $this->collResultssPartial = false;
            }
        }

        return $this->collResultss;
    }

    /**
     * Sets a collection of ChildResults objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $resultss A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildMatches The current object (for fluent API support)
     */
    public function setResultss(Collection $resultss, ConnectionInterface $con = null)
    {
        /** @var ChildResults[] $resultssToDelete */
        $resultssToDelete = $this->getResultss(new Criteria(), $con)->diff($resultss);


        $this->resultssScheduledForDeletion = $resultssToDelete;

        foreach ($resultssToDelete as $resultsRemoved) {
            $resultsRemoved->setMatches(null);
        }

        $this->collResultss = null;
        foreach ($resultss as $results) {
            $this->addResults($results);
        }

        $this->collResultss = $resultss;
        $this->collResultssPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Results objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Results objects.
     * @throws PropelException
     */
    public function countResultss(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collResultssPartial && !$this->isNew();
        if (null === $this->collResultss || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collResultss) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getResultss());
            }

            $query = ChildResultsQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByMatches($this)
                ->count($con);
        }

        return count($this->collResultss);
    }

    /**
     * Method called to associate a ChildResults object to this object
     * through the ChildResults foreign key attribute.
     *
     * @param  ChildResults $l ChildResults
     * @return $this|\Matches The current object (for fluent API support)
     */
    public function addResults(ChildResults $l)
    {
        if ($this->collResultss === null) {
            $this->initResultss();
            $this->collResultssPartial = true;
        }

        if (!$this->collResultss->contains($l)) {
            $this->doAddResults($l);
        }

        return $this;
    }

    /**
     * @param ChildResults $results The ChildResults object to add.
     */
    protected function doAddResults(ChildResults $results)
    {
        $this->collResultss[]= $results;
        $results->setMatches($this);
    }

    /**
     * @param  ChildResults $results The ChildResults object to remove.
     * @return $this|ChildMatches The current object (for fluent API support)
     */
    public function removeResults(ChildResults $results)
    {
        if ($this->getResultss()->contains($results)) {
            $pos = $this->collResultss->search($results);
            $this->collResultss->remove($pos);
            if (null === $this->resultssScheduledForDeletion) {
                $this->resultssScheduledForDeletion = clone $this->collResultss;
                $this->resultssScheduledForDeletion->clear();
            }
            $this->resultssScheduledForDeletion[]= clone $results;
            $results->setMatches(null);
        }

        return $this;
    }

    /**
     * Clears out the collVotess collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addVotess()
     */
    public function clearVotess()
    {
        $this->collVotess = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collVotess collection loaded partially.
     */
    public function resetPartialVotess($v = true)
    {
        $this->collVotessPartial = $v;
    }

    /**
     * Initializes the collVotess collection.
     *
     * By default this just sets the collVotess collection to an empty array (like clearcollVotess());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initVotess($overrideExisting = true)
    {
        if (null !== $this->collVotess && !$overrideExisting) {
            return;
        }
        $this->collVotess = new ObjectCollection();
        $this->collVotess->setModel('\Votes');
    }

    /**
     * Gets an array of ChildVotes objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildMatches is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildVotes[] List of ChildVotes objects
     * @throws PropelException
     */
    public function getVotess(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collVotessPartial && !$this->isNew();
        if (null === $this->collVotess || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collVotess) {
                // return empty collection
                $this->initVotess();
            } else {
                $collVotess = ChildVotesQuery::create(null, $criteria)
                    ->filterByVoteMatch($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collVotessPartial && count($collVotess)) {
                        $this->initVotess(false);

                        foreach ($collVotess as $obj) {
                            if (false == $this->collVotess->contains($obj)) {
                                $this->collVotess->append($obj);
                            }
                        }

                        $this->collVotessPartial = true;
                    }

                    return $collVotess;
                }

                if ($partial && $this->collVotess) {
                    foreach ($this->collVotess as $obj) {
                        if ($obj->isNew()) {
                            $collVotess[] = $obj;
                        }
                    }
                }

                $this->collVotess = $collVotess;
                $this->collVotessPartial = false;
            }
        }

        return $this->collVotess;
    }

    /**
     * Sets a collection of ChildVotes objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $votess A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildMatches The current object (for fluent API support)
     */
    public function setVotess(Collection $votess, ConnectionInterface $con = null)
    {
        /** @var ChildVotes[] $votessToDelete */
        $votessToDelete = $this->getVotess(new Criteria(), $con)->diff($votess);


        $this->votessScheduledForDeletion = $votessToDelete;

        foreach ($votessToDelete as $votesRemoved) {
            $votesRemoved->setVoteMatch(null);
        }

        $this->collVotess = null;
        foreach ($votess as $votes) {
            $this->addVotes($votes);
        }

        $this->collVotess = $votess;
        $this->collVotessPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Votes objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Votes objects.
     * @throws PropelException
     */
    public function countVotess(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collVotessPartial && !$this->isNew();
        if (null === $this->collVotess || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collVotess) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getVotess());
            }

            $query = ChildVotesQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByVoteMatch($this)
                ->count($con);
        }

        return count($this->collVotess);
    }

    /**
     * Method called to associate a ChildVotes object to this object
     * through the ChildVotes foreign key attribute.
     *
     * @param  ChildVotes $l ChildVotes
     * @return $this|\Matches The current object (for fluent API support)
     */
    public function addVotes(ChildVotes $l)
    {
        if ($this->collVotess === null) {
            $this->initVotess();
            $this->collVotessPartial = true;
        }

        if (!$this->collVotess->contains($l)) {
            $this->doAddVotes($l);
        }

        return $this;
    }

    /**
     * @param ChildVotes $votes The ChildVotes object to add.
     */
    protected function doAddVotes(ChildVotes $votes)
    {
        $this->collVotess[]= $votes;
        $votes->setVoteMatch($this);
    }

    /**
     * @param  ChildVotes $votes The ChildVotes object to remove.
     * @return $this|ChildMatches The current object (for fluent API support)
     */
    public function removeVotes(ChildVotes $votes)
    {
        if ($this->getVotess()->contains($votes)) {
            $pos = $this->collVotess->search($votes);
            $this->collVotess->remove($pos);
            if (null === $this->votessScheduledForDeletion) {
                $this->votessScheduledForDeletion = clone $this->collVotess;
                $this->votessScheduledForDeletion->clear();
            }
            $this->votessScheduledForDeletion[]= clone $votes;
            $votes->setVoteMatch(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Matches is new, it will return
     * an empty collection; or if this Matches has previously
     * been saved, it will retrieve related Votess from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Matches.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildVotes[] List of ChildVotes objects
     */
    public function getVotessJoinVotePlayer(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildVotesQuery::create(null, $criteria);
        $query->joinWith('VotePlayer', $joinBehavior);

        return $this->getVotess($query, $con);
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        if (null !== $this->aGroups) {
            $this->aGroups->removeMatches($this);
        }
        if (null !== $this->aTeamHome) {
            $this->aTeamHome->removeMatchesRelatedByTeamhomekey($this);
        }
        if (null !== $this->aTeamAway) {
            $this->aTeamAway->removeMatchesRelatedByTeamawaykey($this);
        }
        $this->primarykey = null;
        $this->groupkey = null;
        $this->teamhomekey = null;
        $this->teamawaykey = null;
        $this->scheduledate = null;
        $this->isbonusmatch = null;
        $this->status = null;
        $this->externalkey = null;
        $this->alreadyInSave = false;
        $this->clearAllReferences();
        $this->applyDefaultValues();
        $this->resetModified();
        $this->setNew(true);
        $this->setDeleted(false);
    }

    /**
     * Resets all references and back-references to other model objects or collections of model objects.
     *
     * This method is used to reset all php object references (not the actual reference in the database).
     * Necessary for object serialisation.
     *
     * @param      boolean $deep Whether to also clear the references on all referrer objects.
     */
    public function clearAllReferences($deep = false)
    {
        if ($deep) {
            if ($this->collForecastss) {
                foreach ($this->collForecastss as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collLineupss) {
                foreach ($this->collLineupss as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collMatchstatess) {
                foreach ($this->collMatchstatess as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPlayermatchresultss) {
                foreach ($this->collPlayermatchresultss as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collResultss) {
                foreach ($this->collResultss as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collVotess) {
                foreach ($this->collVotess as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collForecastss = null;
        $this->collLineupss = null;
        $this->collMatchstatess = null;
        $this->collPlayermatchresultss = null;
        $this->collResultss = null;
        $this->collVotess = null;
        $this->aGroups = null;
        $this->aTeamHome = null;
        $this->aTeamAway = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(MatchesTableMap::DEFAULT_STRING_FORMAT);
    }

    /**
     * Code to be run before persisting the object
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preSave(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after persisting the object
     * @param ConnectionInterface $con
     */
    public function postSave(ConnectionInterface $con = null)
    {

    }

    /**
     * Code to be run before inserting to database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preInsert(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after inserting to database
     * @param ConnectionInterface $con
     */
    public function postInsert(ConnectionInterface $con = null)
    {

    }

    /**
     * Code to be run before updating the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preUpdate(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after updating the object in database
     * @param ConnectionInterface $con
     */
    public function postUpdate(ConnectionInterface $con = null)
    {

    }

    /**
     * Code to be run before deleting the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preDelete(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after deleting the object in database
     * @param ConnectionInterface $con
     */
    public function postDelete(ConnectionInterface $con = null)
    {

    }


    /**
     * Derived method to catches calls to undefined methods.
     *
     * Provides magic import/export method support (fromXML()/toXML(), fromYAML()/toYAML(), etc.).
     * Allows to define default __call() behavior if you overwrite __call()
     *
     * @param string $name
     * @param mixed  $params
     *
     * @return array|string
     */
    public function __call($name, $params)
    {
        if (0 === strpos($name, 'get')) {
            $virtualColumn = substr($name, 3);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }

            $virtualColumn = lcfirst($virtualColumn);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }
        }

        if (0 === strpos($name, 'from')) {
            $format = substr($name, 4);

            return $this->importFrom($format, reset($params));
        }

        if (0 === strpos($name, 'to')) {
            $format = substr($name, 2);
            $includeLazyLoadColumns = isset($params[0]) ? $params[0] : true;

            return $this->exportTo($format, $includeLazyLoadColumns);
        }

        throw new BadMethodCallException(sprintf('Call to undefined method: %s.', $name));
    }

}
