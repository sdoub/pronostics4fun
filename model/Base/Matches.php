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
            $keys[1] => $this->getGroupk