<?php

namespace Base;

use \Competitions as ChildCompetitions;
use \CompetitionsQuery as ChildCompetitionsQuery;
use \Groups as ChildGroups;
use \GroupsQuery as ChildGroupsQuery;
use \Matches as ChildMatches;
use \MatchesQuery as ChildMatchesQuery;
use \Playercupmatches as ChildPlayercupmatches;
use \PlayercupmatchesQuery as ChildPlayercupmatchesQuery;
use \Playerdivisionmatches as ChildPlayerdivisionmatches;
use \PlayerdivisionmatchesQuery as ChildPlayerdivisionmatchesQuery;
use \Playergroupranking as ChildPlayergroupranking;
use \PlayergrouprankingQuery as ChildPlayergrouprankingQuery;
use \Playergroupresults as ChildPlayergroupresults;
use \PlayergroupresultsQuery as ChildPlayergroupresultsQuery;
use \Playergroupstates as ChildPlayergroupstates;
use \PlayergroupstatesQuery as ChildPlayergroupstatesQuery;
use \DateTime;
use \Exception;
use \PDO;
use Map\GroupsTableMap;
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
 * Base class that represents a row from the 'groups' table.
 *
 *
 *
* @package    propel.generator..Base
*/
abstract class Groups implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\Map\\GroupsTableMap';


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
     * The value for the description field.
     * @var        string
     */
    protected $description;

    /**
     * The value for the code field.
     * @var        string
     */
    protected $code;

    /**
     * The value for the competitionkey field.
     * @var        int
     */
    protected $competitionkey;

    /**
     * The value for the begindate field.
     * @var        \DateTime
     */
    protected $begindate;

    /**
     * The value for the enddate field.
     * @var        \DateTime
     */
    protected $enddate;

    /**
     * The value for the status field.
     * @var        boolean
     */
    protected $status;

    /**
     * The value for the iscompleted field.
     * Note: this column has a database default value of: false
     * @var        boolean
     */
    protected $iscompleted;

    /**
     * The value for the daykey field.
     * @var        int
     */
    protected $daykey;

    /**
     * @var        ChildCompetitions
     */
    protected $aCompetitions;

    /**
     * @var        ObjectCollection|ChildMatches[] Collection to store aggregation of ChildMatches objects.
     */
    protected $collMatchess;
    protected $collMatchessPartial;

    /**
     * @var        ObjectCollection|ChildPlayercupmatches[] Collection to store aggregation of ChildPlayercupmatches objects.
     */
    protected $collPlayercupmatchess;
    protected $collPlayercupmatchessPartial;

    /**
     * @var        ObjectCollection|ChildPlayerdivisionmatches[] Collection to store aggregation of ChildPlayerdivisionmatches objects.
     */
    protected $collPlayerdivisionmatchess;
    protected $collPlayerdivisionmatchessPartial;

    /**
     * @var        ObjectCollection|ChildPlayergroupranking[] Collection to store aggregation of ChildPlayergroupranking objects.
     */
    protected $collPlayergrouprankings;
    protected $collPlayergrouprankingsPartial;

    /**
     * @var        ObjectCollection|ChildPlayergroupresults[] Collection to store aggregation of ChildPlayergroupresults objects.
     */
    protected $collPlayergroupresultss;
    protected $collPlayergroupresultssPartial;

    /**
     * @var        ObjectCollection|ChildPlayergroupstates[] Collection to store aggregation of ChildPlayergroupstates objects.
     */
    protected $collPlayergroupstatess;
    protected $collPlayergroupstatessPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildMatches[]
     */
    protected $matchessScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildPlayercupmatches[]
     */
    protected $playercupmatchessScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildPlayerdivisionmatches[]
     */
    protected $playerdivisionmatchessScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildPlayergroupranking[]
     */
    protected $playergrouprankingsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildPlayergroupresults[]
     */
    protected $playergroupresultssScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildPlayergroupstates[]
     */
    protected $playergroupstatessScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see __construct()
     */
    public function applyDefaultValues()
    {
        $this->iscompleted = false;
    }

    /**
     * Initializes internal state of Base\Groups object.
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
     * Compares this with another <code>Groups</code> instance.  If
     * <code>obj</code> is an instance of <code>Groups</code>, delegates to
     * <code>equals(Groups)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|Groups The current object, for fluid interface
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
    public function getGroupPK()
    {
        return $this->primarykey;
    }

    /**
     * Get the [description] column value.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Get the [code] column value.
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Get the [competitionkey] column value.
     *
     * @return int
     */
    public function getCompetitionkey()
    {
        return $this->competitionkey;
    }

    /**
     * Get the [optionally formatted] temporal [begindate] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getBegindate($format = NULL)
    {
        if ($format === null) {
            return $this->begindate;
        } else {
            return $this->begindate instanceof \DateTime ? $this->begindate->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [enddate] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getEnddate($format = NULL)
    {
        if ($format === null) {
            return $this->enddate;
        } else {
            return $this->enddate instanceof \DateTime ? $this->enddate->format($format) : null;
        }
    }

    /**
     * Get the [status] column value.
     *
     * @return boolean
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Get the [status] column value.
     *
     * @return boolean
     */
    public function isStatus()
    {
        return $this->getStatus();
    }

    /**
     * Get the [iscompleted] column value.
     *
     * @return boolean
     */
    public function getIscompleted()
    {
        return $this->iscompleted;
    }

    /**
     * Get the [iscompleted] column value.
     *
     * @return boolean
     */
    public function isIscompleted()
    {
        return $this->getIscompleted();
    }

    /**
     * Get the [daykey] column value.
     *
     * @return int
     */
    public function getDaykey()
    {
        return $this->daykey;
    }

    /**
     * Set the value of [primarykey] column.
     *
     * @param int $v new value
     * @return $this|\Groups The current object (for fluent API support)
     */
    public function setGroupPK($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->primarykey !== $v) {
            $this->primarykey = $v;
            $this->modifiedColumns[GroupsTableMap::COL_PRIMARYKEY] = true;
        }

        return $this;
    } // setGroupPK()

    /**
     * Set the value of [description] column.
     *
     * @param string $v new value
     * @return $this|\Groups The current object (for fluent API support)
     */
    public function setDescription($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->description !== $v) {
            $this->description = $v;
            $this->modifiedColumns[GroupsTableMap::COL_DESCRIPTION] = true;
        }

        return $this;
    } // setDescription()

    /**
     * Set the value of [code] column.
     *
     * @param string $v new value
     * @return $this|\Groups The current object (for fluent API support)
     */
    public function setCode($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->code !== $v) {
            $this->code = $v;
            $this->modifiedColumns[GroupsTableMap::COL_CODE] = true;
        }

        return $this;
    } // setCode()

    /**
     * Set the value of [competitionkey] column.
     *
     * @param int $v new value
     * @return $this|\Groups The current object (for fluent API support)
     */
    public function setCompetitionkey($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->competitionkey !== $v) {
            $this->competitionkey = $v;
            $this->modifiedColumns[GroupsTableMap::COL_COMPETITIONKEY] = true;
        }

        if ($this->aCompetitions !== null && $this->aCompetitions->getCompetitionPK() !== $v) {
            $this->aCompetitions = null;
        }

        return $this;
    } // setCompetitionkey()

    /**
     * Sets the value of [begindate] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTime value.
     *               Empty strings are treated as NULL.
     * @return $this|\Groups The current object (for fluent API support)
     */
    public function setBegindate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->begindate !== null || $dt !== null) {
            if ($this->begindate === null || $dt === null || $dt->format("Y-m-d H:i:s") !== $this->begindate->format("Y-m-d H:i:s")) {
                $this->begindate = $dt === null ? null : clone $dt;
                $this->modifiedColumns[GroupsTableMap::COL_BEGINDATE] = true;
            }
        } // if either are not null

        return $this;
    } // setBegindate()

    /**
     * Sets the value of [enddate] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTime value.
     *               Empty strings are treated as NULL.
     * @return $this|\Groups The current object (for fluent API support)
     */
    public function setEnddate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->enddate !== null || $dt !== null) {
            if ($this->enddate === null || $dt === null || $dt->format("Y-m-d H:i:s") !== $this->enddate->format("Y-m-d H:i:s")) {
                $this->enddate = $dt === null ? null : clone $dt;
                $this->modifiedColumns[GroupsTableMap::COL_ENDDATE] = true;
            }
        } // if either are not null

        return $this;
    } // setEnddate()

    /**
     * Sets the value of the [status] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param  boolean|integer|string $v The new value
     * @return $this|\Groups The current object (for fluent API support)
     */
    public function setStatus($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->status !== $v) {
            $this->status = $v;
            $this->modifiedColumns[GroupsTableMap::COL_STATUS] = true;
        }

        return $this;
    } // setStatus()

    /**
     * Sets the value of the [iscompleted] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param  boolean|integer|string $v The new value
     * @return $this|\Groups The current object (for fluent API support)
     */
    public function setIscompleted($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->iscompleted !== $v) {
            $this->iscompleted = $v;
            $this->modifiedColumns[GroupsTableMap::COL_ISCOMPLETED] = true;
        }

        return $this;
    } // setIscompleted()

    /**
     * Set the value of [daykey] column.
     *
     * @param int $v new value
     * @return $this|\Groups The current object (for fluent API support)
     */
    public function setDaykey($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->daykey !== $v) {
            $this->daykey = $v;
            $this->modifiedColumns[GroupsTableMap::COL_DAYKEY] = true;
        }

        return $this;
    } // setDaykey()

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
            if ($this->iscompleted !== false) {
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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : GroupsTableMap::translateFieldName('GroupPK', TableMap::TYPE_PHPNAME, $indexType)];
            $this->primarykey = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : GroupsTableMap::translateFieldName('Description', TableMap::TYPE_PHPNAME, $indexType)];
            $this->description = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : GroupsTableMap::translateFieldName('Code', TableMap::TYPE_PHPNAME, $indexType)];
            $this->code = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : GroupsTableMap::translateFieldName('Competitionkey', TableMap::TYPE_PHPNAME, $indexType)];
            $this->competitionkey = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : GroupsTableMap::translateFieldName('Begindate', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->begindate = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : GroupsTableMap::translateFieldName('Enddate', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->enddate = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : GroupsTableMap::translateFieldName('Status', TableMap::TYPE_PHPNAME, $indexType)];
            $this->status = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : GroupsTableMap::translateFieldName('Iscompleted', TableMap::TYPE_PHPNAME, $indexType)];
            $this->iscompleted = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : GroupsTableMap::translateFieldName('Daykey', TableMap::TYPE_PHPNAME, $indexType)];
            $this->daykey = (null !== $col) ? (int) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 9; // 9 = GroupsTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Groups'), 0, $e);
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
        if ($this->aCompetitions !== null && $this->competitionkey !== $this->aCompetitions->getCompetitionPK()) {
            $this->aCompetitions = null;
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
            $con = Propel::getServiceContainer()->getReadConnection(GroupsTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildGroupsQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aCompetitions = null;
            $this->collMatchess = null;

            $this->collPlayercupmatchess = null;

            $this->collPlayerdivisionmatchess = null;

            $this->collPlayergrouprankings = null;

            $this->collPlayergroupresultss = null;

            $this->collPlayergroupstatess = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Groups::setDeleted()
     * @see Groups::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(GroupsTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildGroupsQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(GroupsTableMap::DATABASE_NAME);
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
                GroupsTableMap::addInstanceToPool($this);
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

            if ($this->aCompetitions !== null) {
                if ($this->aCompetitions->isModified() || $this->aCompetitions->isNew()) {
                    $affectedRows += $this->aCompetitions->save($con);
                }
                $this->setCompetitions($this->aCompetitions);
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

            if ($this->matchessScheduledForDeletion !== null) {
                if (!$this->matchessScheduledForDeletion->isEmpty()) {
                    \MatchesQuery::create()
                        ->filterByPrimaryKeys($this->matchessScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->matchessScheduledForDeletion = null;
                }
            }

            if ($this->collMatchess !== null) {
                foreach ($this->collMatchess as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->playercupmatchessScheduledForDeletion !== null) {
                if (!$this->playercupmatchessScheduledForDeletion->isEmpty()) {
                    \PlayercupmatchesQuery::create()
                        ->filterByPrimaryKeys($this->playercupmatchessScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->playercupmatchessScheduledForDeletion = null;
                }
            }

            if ($this->collPlayercupmatchess !== null) {
                foreach ($this->collPlayercupmatchess as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->playerdivisionmatchessScheduledForDeletion !== null) {
                if (!$this->playerdivisionmatchessScheduledForDeletion->isEmpty()) {
                    \PlayerdivisionmatchesQuery::create()
                        ->filterByPrimaryKeys($this->playerdivisionmatchessScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->playerdivisionmatchessScheduledForDeletion = null;
                }
            }

            if ($this->collPlayerdivisionmatchess !== null) {
                foreach ($this->collPlayerdivisionmatchess as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->playergrouprankingsScheduledForDeletion !== null) {
                if (!$this->playergrouprankingsScheduledForDeletion->isEmpty()) {
                    \PlayergrouprankingQuery::create()
                        ->filterByPrimaryKeys($this->playergrouprankingsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->playergrouprankingsScheduledForDeletion = null;
                }
            }

            if ($this->collPlayergrouprankings !== null) {
                foreach ($this->collPlayergrouprankings as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->playergroupresultssScheduledForDeletion !== null) {
                if (!$this->playergroupresultssScheduledForDeletion->isEmpty()) {
                    \PlayergroupresultsQuery::create()
                        ->filterByPrimaryKeys($this->playergroupresultssScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->playergroupresultssScheduledForDeletion = null;
                }
            }

            if ($this->collPlayergroupresultss !== null) {
                foreach ($this->collPlayergroupresultss as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->playergroupstatessScheduledForDeletion !== null) {
                if (!$this->playergroupstatessScheduledForDeletion->isEmpty()) {
                    \PlayergroupstatesQuery::create()
                        ->filterByPrimaryKeys($this->playergroupstatessScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->playergroupstatessScheduledForDeletion = null;
                }
            }

            if ($this->collPlayergroupstatess !== null) {
                foreach ($this->collPlayergroupstatess as $referrerFK) {
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

        $this->modifiedColumns[GroupsTableMap::COL_PRIMARYKEY] = true;
        if (null !== $this->primarykey) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . GroupsTableMap::COL_PRIMARYKEY . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(GroupsTableMap::COL_PRIMARYKEY)) {
            $modifiedColumns[':p' . $index++]  = 'PrimaryKey';
        }
        if ($this->isColumnModified(GroupsTableMap::COL_DESCRIPTION)) {
            $modifiedColumns[':p' . $index++]  = 'Description';
        }
        if ($this->isColumnModified(GroupsTableMap::COL_CODE)) {
            $modifiedColumns[':p' . $index++]  = 'Code';
        }
        if ($this->isColumnModified(GroupsTableMap::COL_COMPETITIONKEY)) {
            $modifiedColumns[':p' . $index++]  = 'CompetitionKey';
        }
        if ($this->isColumnModified(GroupsTableMap::COL_BEGINDATE)) {
            $modifiedColumns[':p' . $index++]  = 'BeginDate';
        }
        if ($this->isColumnModified(GroupsTableMap::COL_ENDDATE)) {
            $modifiedColumns[':p' . $index++]  = 'EndDate';
        }
        if ($this->isColumnModified(GroupsTableMap::COL_STATUS)) {
            $modifiedColumns[':p' . $index++]  = 'Status';
        }
        if ($this->isColumnModified(GroupsTableMap::COL_ISCOMPLETED)) {
            $modifiedColumns[':p' . $index++]  = 'IsCompleted';
        }
        if ($this->isColumnModified(GroupsTableMap::COL_DAYKEY)) {
            $modifiedColumns[':p' . $index++]  = 'DayKey';
        }

        $sql = sprintf(
            'INSERT INTO groups (%s) VALUES (%s)',
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
                    case 'Description':
                        $stmt->bindValue($identifier, $this->description, PDO::PARAM_STR);
                        break;
                    case 'Code':
                        $stmt->bindValue($identifier, $this->code, PDO::PARAM_STR);
                        break;
                    case 'CompetitionKey':
                        $stmt->bindValue($identifier, $this->competitionkey, PDO::PARAM_INT);
                        break;
                    case 'BeginDate':
                        $stmt->bindValue($identifier, $this->begindate ? $this->begindate->format("Y-m-d H:i:s") : null, PDO::PARAM_STR);
                        break;
                    case 'EndDate':
                        $stmt->bindValue($identifier, $this->enddate ? $this->enddate->format("Y-m-d H:i:s") : null, PDO::PARAM_STR);
                        break;
                    case 'Status':
                        $stmt->bindValue($identifier, (int) $this->status, PDO::PARAM_INT);
                        break;
                    case 'IsCompleted':
                        $stmt->bindValue($identifier, (int) $this->iscompleted, PDO::PARAM_INT);
                        break;
                    case 'DayKey':
                        $stmt->bindValue($identifier, $this->daykey, PDO::PARAM_INT);
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
        $this->setGroupPK($pk);

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
        $pos = GroupsTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getGroupPK();
                break;
            case 1:
                return $this->getDescription();
                break;
            case 2:
                return $this->getCode();
                break;
            case 3:
                return $this->getCompetitionkey();
                break;
            case 4:
                return $this->getBegindate();
                break;
            case 5:
                return $this->getEnddate();
                break;
            case 6:
                return $this->getStatus();
                break;
            case 7:
                return $this->getIscompleted();
                break;
            case 8:
                return $this->getDaykey();
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

        if (isset($alreadyDumpedObjects['Groups'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Groups'][$this->hashCode()] = true;
        $keys = GroupsTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getGroupPK(),
            $keys[1] => $this->getDescription(),
            $keys[2] => $this->getCode(),
            $keys[3] => $this->getCompetitionkey(),
            $keys[4] => $this->getBegindate(),
            $keys[5] => $this->getEnddate(),
            $keys[6] => $this->getStatus(),
            $keys[7] => $this->getIscompleted(),
            $keys[8] => $this->getDaykey(),
        );

        $utc = new \DateTimeZone('utc');
        if ($result[$keys[4]] instanceof \DateTime) {
            // When changing timezone we don't want to change existing instances
            $dateTime = clone $result[$keys[4]];
            $result[$keys[4]] = $dateTime->setTimezone($utc)->format('Y-m-d\TH:i:s\Z');
        }

        if ($result[$keys[5]] instanceof \DateTime) {
            // When changing timezone we don't want to change existing instances
            $dateTime = clone $result[$keys[5]];
            $result[$keys[5]] = $dateTime->setTimezone($utc)->format('Y-m-d\TH:i:s\Z');
        }

        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aCompetitions) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'competitions';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'competitions';
                        break;
                    default:
                        $key = 'Competitions';
                }

                $result[$key] = $this->aCompetitions->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collMatchess) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'matchess';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'matchess';
                        break;
                    default:
                        $key = 'Matchess';
                }

                $result[$key] = $this->collMatchess->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPlayercupmatchess) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'playercupmatchess';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'playercupmatchess';
                        break;
                    default:
                        $key = 'Playercupmatchess';
                }

                $result[$key] = $this->collPlayercupmatchess->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPlayerdivisionmatchess) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'playerdivisionmatchess';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'playerdivisionmatchess';
                        break;
                    default:
                        $key = 'Playerdivisionmatchess';
                }

                $result[$key] = $this->collPlayerdivisionmatchess->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPlayergrouprankings) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'playergrouprankings';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'playergrouprankings';
                        break;
                    default:
                        $key = 'Playergrouprankings';
                }

                $result[$key] = $this->collPlayergrouprankings->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPlayergroupresultss) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'playergroupresultss';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'playergroupresultss';
                        break;
                    default:
                        $key = 'Playergroupresultss';
                }

                $result[$key] = $this->collPlayergroupresultss->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPlayergroupstatess) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'playergroupstatess';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'playergroupstatess';
                        break;
                    default:
                        $key = 'Playergroupstatess';
                }

                $result[$key] = $this->collPlayergroupstatess->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
     * @return $this|\Groups
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = GroupsTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\Groups
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setGroupPK($value);
                break;
            case 1:
                $this->setDescription($value);
                break;
            case 2:
                $this->setCode($value);
                break;
            case 3:
                $this->setCompetitionkey($value);
                break;
            case 4:
                $this->setBegindate($value);
                break;
            case 5:
                $this->setEnddate($value);
                break;
            case 6:
                $this->setStatus($value);
                break;
            case 7:
                $this->setIscompleted($value);
                break;
            case 8:
                $this->setDaykey($value);
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
        $keys = GroupsTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setGroupPK($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setDescription($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setCode($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setCompetitionkey($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setBegindate($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setEnddate($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setStatus($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setIscompleted($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->setDaykey($arr[$keys[8]]);
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
     * @return $this|\Groups The current object, for fluid interface
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
        $criteria = new Criteria(GroupsTableMap::DATABASE_NAME);

        if ($this->isColumnModified(GroupsTableMap::COL_PRIMARYKEY)) {
            $criteria->add(GroupsTableMap::COL_PRIMARYKEY, $this->primarykey);
        }
        if ($this->isColumnModified(GroupsTableMap::COL_DESCRIPTION)) {
            $criteria->add(GroupsTableMap::COL_DESCRIPTION, $this->description);
        }
        if ($this->isColumnModified(GroupsTableMap::COL_CODE)) {
            $criteria->add(GroupsTableMap::COL_CODE, $this->code);
        }
        if ($this->isColumnModified(GroupsTableMap::COL_COMPETITIONKEY)) {
            $criteria->add(GroupsTableMap::COL_COMPETITIONKEY, $this->competitionkey);
        }
        if ($this->isColumnModified(GroupsTableMap::COL_BEGINDATE)) {
            $criteria->add(GroupsTableMap::COL_BEGINDATE, $this->begindate);
        }
        if ($this->isColumnModified(GroupsTableMap::COL_ENDDATE)) {
            $criteria->add(GroupsTableMap::COL_ENDDATE, $this->enddate);
        }
        if ($this->isColumnModified(GroupsTableMap::COL_STATUS)) {
            $criteria->add(GroupsTableMap::COL_STATUS, $this->status);
        }
        if ($this->isColumnModified(GroupsTableMap::COL_ISCOMPLETED)) {
            $criteria->add(GroupsTableMap::COL_ISCOMPLETED, $this->iscompleted);
        }
        if ($this->isColumnModified(GroupsTableMap::COL_DAYKEY)) {
            $criteria->add(GroupsTableMap::COL_DAYKEY, $this->daykey);
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
        $criteria = ChildGroupsQuery::create();
        $criteria->add(GroupsTableMap::COL_PRIMARYKEY, $this->primarykey);

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
        $validPk = null !== $this->getGroupPK();

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
        return $this->getGroupPK();
    }

    /**
     * Generic method to set the primary key (primarykey column).
     *
     * @param       int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setGroupPK($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {
        return null === $this->getGroupPK();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of \Groups (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setDescription($this->getDescription());
        $copyObj->setCode($this->getCode());
        $copyObj->setCompetitionkey($this->getCompetitionkey());
        $copyObj->setBegindate($this->getBegindate());
        $copyObj->setEnddate($this->getEnddate());
        $copyObj->setStatus($this->getStatus());
        $copyObj->setIscompleted($this->getIscompleted());
        $copyObj->setDaykey($this->getDaykey());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getMatchess() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addMatches($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPlayercupmatchess() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPlayercupmatches($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPlayerdivisionmatchess() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPlayerdivisionmatches($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPlayergrouprankings() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPlayergroupranking($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPlayergroupresultss() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPlayergroupresults($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPlayergroupstatess() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPlayergroupstates($relObj->copy($deepCopy));
                }
            }

        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setGroupPK(NULL); // this is a auto-increment column, so set to default value
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
     * @return \Groups Clone of current object.
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
     * Declares an association between this object and a ChildCompetitions object.
     *
     * @param  ChildCompetitions $v
     * @return $this|\Groups The current object (for fluent API support)
     * @throws PropelException
     */
    public function setCompetitions(ChildCompetitions $v = null)
    {
        if ($v === null) {
            $this->setCompetitionkey(NULL);
        } else {
            $this->setCompetitionkey($v->getCompetitionPK());
        }

        $this->aCompetitions = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildCompetitions object, it will not be re-added.
        if ($v !== null) {
            $v->addGroups($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildCompetitions object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildCompetitions The associated ChildCompetitions object.
     * @throws PropelException
     */
    public function getCompetitions(ConnectionInterface $con = null)
    {
        if ($this->aCompetitions === null && ($this->competitionkey !== null)) {
            $this->aCompetitions = ChildCompetitionsQuery::create()->findPk($this->competitionkey, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aCompetitions->addGroupss($this);
             */
        }

        return $this->aCompetitions;
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
        if ('Matches' == $relationName) {
            return $this->initMatchess();
        }
        if ('Playercupmatches' == $relationName) {
            return $this->initPlayercupmatchess();
        }
        if ('Playerdivisionmatches' == $relationName) {
            return $this->initPlayerdivisionmatchess();
        }
        if ('Playergroupranking' == $relationName) {
            return $this->initPlayergrouprankings();
        }
        if ('Playergroupresults' == $relationName) {
            return $this->initPlayergroupresultss();
        }
        if ('Playergroupstates' == $relationName) {
            return $this->initPlayergroupstatess();
        }
    }

    /**
     * Clears out the collMatchess collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addMatchess()
     */
    public function clearMatchess()
    {
        $this->collMatchess = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collMatchess collection loaded partially.
     */
    public function resetPartialMatchess($v = true)
    {
        $this->collMatchessPartial = $v;
    }

    /**
     * Initializes the collMatchess collection.
     *
     * By default this just sets the collMatchess collection to an empty array (like clearcollMatchess());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initMatchess($overrideExisting = true)
    {
        if (null !== $this->collMatchess && !$overrideExisting) {
            return;
        }
        $this->collMatchess = new ObjectCollection();
        $this->collMatchess->setModel('\Matches');
    }

    /**
     * Gets an array of ChildMatches objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildGroups is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildMatches[] List of ChildMatches objects
     * @throws PropelException
     */
    public function getMatchess(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collMatchessPartial && !$this->isNew();
        if (null === $this->collMatchess || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collMatchess) {
                // return empty collection
                $this->initMatchess();
            } else {
                $collMatchess = ChildMatchesQuery::create(null, $criteria)
                    ->filterByGroups($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collMatchessPartial && count($collMatchess)) {
                        $this->initMatchess(false);

                        foreach ($collMatchess as $obj) {
                            if (false == $this->collMatchess->contains($obj)) {
                                $this->collMatchess->append($obj);
                            }
                        }

                        $this->collMatchessPartial = true;
                    }

                    return $collMatchess;
                }

                if ($partial && $this->collMatchess) {
                    foreach ($this->collMatchess as $obj) {
                        if ($obj->isNew()) {
                            $collMatchess[] = $obj;
                        }
                    }
                }

                $this->collMatchess = $collMatchess;
                $this->collMatchessPartial = false;
            }
        }

        return $this->collMatchess;
    }

    /**
     * Sets a collection of ChildMatches objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $matchess A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildGroups The current object (for fluent API support)
     */
    public function setMatchess(Collection $matchess, ConnectionInterface $con = null)
    {
        /** @var ChildMatches[] $matchessToDelete */
        $matchessToDelete = $this->getMatchess(new Criteria(), $con)->diff($matchess);


        $this->matchessScheduledForDeletion = $matchessToDelete;

        foreach ($matchessToDelete as $matchesRemoved) {
            $matchesRemoved->setGroups(null);
        }

        $this->collMatchess = null;
        foreach ($matchess as $matches) {
            $this->addMatches($matches);
        }

        $this->collMatchess = $matchess;
        $this->collMatchessPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Matches objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Matches objects.
     * @throws PropelException
     */
    public function countMatchess(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collMatchessPartial && !$this->isNew();
        if (null === $this->collMatchess || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collMatchess) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getMatchess());
            }

            $query = ChildMatchesQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByGroups($this)
                ->count($con);
        }

        return count($this->collMatchess);
    }

    /**
     * Method called to associate a ChildMatches object to this object
     * through the ChildMatches foreign key attribute.
     *
     * @param  ChildMatches $l ChildMatches
     * @return $this|\Groups The current object (for fluent API support)
     */
    public function addMatches(ChildMatches $l)
    {
        if ($this->collMatchess === null) {
            $this->initMatchess();
            $this->collMatchessPartial = true;
        }

        if (!$this->collMatchess->contains($l)) {
            $this->doAddMatches($l);
        }

        return $this;
    }

    /**
     * @param ChildMatches $matches The ChildMatches object to add.
     */
    protected function doAddMatches(ChildMatches $matches)
    {
        $this->collMatchess[]= $matches;
        $matches->setGroups($this);
    }

    /**
     * @param  ChildMatches $matches The ChildMatches object to remove.
     * @return $this|ChildGroups The current object (for fluent API support)
     */
    public function removeMatches(ChildMatches $matches)
    {
        if ($this->getMatchess()->contains($matches)) {
            $pos = $this->collMatchess->search($matches);
            $this->collMatchess->remove($pos);
            if (null === $this->matchessScheduledForDeletion) {
                $this->matchessScheduledForDeletion = clone $this->collMatchess;
                $this->matchessScheduledForDeletion->clear();
            }
            $this->matchessScheduledForDeletion[]= clone $matches;
            $matches->setGroups(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Groups is new, it will return
     * an empty collection; or if this Groups has previously
     * been saved, it will retrieve related Matchess from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Groups.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildMatches[] List of ChildMatches objects
     */
    public function getMatchessJoinTeamHome(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildMatchesQuery::create(null, $criteria);
        $query->joinWith('TeamHome', $joinBehavior);

        return $this->getMatchess($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Groups is new, it will return
     * an empty collection; or if this Groups has previously
     * been saved, it will retrieve related Matchess from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Groups.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildMatches[] List of ChildMatches objects
     */
    public function getMatchessJoinTeamAway(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildMatchesQuery::create(null, $criteria);
        $query->joinWith('TeamAway', $joinBehavior);

        return $this->getMatchess($query, $con);
    }

    /**
     * Clears out the collPlayercupmatchess collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addPlayercupmatchess()
     */
    public function clearPlayercupmatchess()
    {
        $this->collPlayercupmatchess = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collPlayercupmatchess collection loaded partially.
     */
    public function resetPartialPlayercupmatchess($v = true)
    {
        $this->collPlayercupmatchessPartial = $v;
    }

    /**
     * Initializes the collPlayercupmatchess collection.
     *
     * By default this just sets the collPlayercupmatchess collection to an empty array (like clearcollPlayercupmatchess());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPlayercupmatchess($overrideExisting = true)
    {
        if (null !== $this->collPlayercupmatchess && !$overrideExisting) {
            return;
        }
        $this->collPlayercupmatchess = new ObjectCollection();
        $this->collPlayercupmatchess->setModel('\Playercupmatches');
    }

    /**
     * Gets an array of ChildPlayercupmatches objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildGroups is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildPlayercupmatches[] List of ChildPlayercupmatches objects
     * @throws PropelException
     */
    public function getPlayercupmatchess(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collPlayercupmatchessPartial && !$this->isNew();
        if (null === $this->collPlayercupmatchess || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPlayercupmatchess) {
                // return empty collection
                $this->initPlayercupmatchess();
            } else {
                $collPlayercupmatchess = ChildPlayercupmatchesQuery::create(null, $criteria)
                    ->filterByCupMatchesGroup($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collPlayercupmatchessPartial && count($collPlayercupmatchess)) {
                        $this->initPlayercupmatchess(false);

                        foreach ($collPlayercupmatchess as $obj) {
                            if (false == $this->collPlayercupmatchess->contains($obj)) {
                                $this->collPlayercupmatchess->append($obj);
                            }
                        }

                        $this->collPlayercupmatchessPartial = true;
                    }

                    return $collPlayercupmatchess;
                }

                if ($partial && $this->collPlayercupmatchess) {
                    foreach ($this->collPlayercupmatchess as $obj) {
                        if ($obj->isNew()) {
                            $collPlayercupmatchess[] = $obj;
                        }
                    }
                }

                $this->collPlayercupmatchess = $collPlayercupmatchess;
                $this->collPlayercupmatchessPartial = false;
            }
        }

        return $this->collPlayercupmatchess;
    }

    /**
     * Sets a collection of ChildPlayercupmatches objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $playercupmatchess A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildGroups The current object (for fluent API support)
     */
    public function setPlayercupmatchess(Collection $playercupmatchess, ConnectionInterface $con = null)
    {
        /** @var ChildPlayercupmatches[] $playercupmatchessToDelete */
        $playercupmatchessToDelete = $this->getPlayercupmatchess(new Criteria(), $con)->diff($playercupmatchess);


        $this->playercupmatchessScheduledForDeletion = $playercupmatchessToDelete;

        foreach ($playercupmatchessToDelete as $playercupmatchesRemoved) {
            $playercupmatchesRemoved->setCupMatchesGroup(null);
        }

        $this->collPlayercupmatchess = null;
        foreach ($playercupmatchess as $playercupmatches) {
            $this->addPlayercupmatches($playercupmatches);
        }

        $this->collPlayercupmatchess = $playercupmatchess;
        $this->collPlayercupmatchessPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Playercupmatches objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Playercupmatches objects.
     * @throws PropelException
     */
    public function countPlayercupmatchess(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collPlayercupmatchessPartial && !$this->isNew();
        if (null === $this->collPlayercupmatchess || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPlayercupmatchess) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPlayercupmatchess());
            }

            $query = ChildPlayercupmatchesQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByCupMatchesGroup($this)
                ->count($con);
        }

        return count($this->collPlayercupmatchess);
    }

    /**
     * Method called to associate a ChildPlayercupmatches object to this object
     * through the ChildPlayercupmatches foreign key attribute.
     *
     * @param  ChildPlayercupmatches $l ChildPlayercupmatches
     * @return $this|\Groups The current object (for fluent API support)
     */
    public function addPlayercupmatches(ChildPlayercupmatches $l)
    {
        if ($this->collPlayercupmatchess === null) {
            $this->initPlayercupmatchess();
            $this->collPlayercupmatchessPartial = true;
        }

        if (!$this->collPlayercupmatchess->contains($l)) {
            $this->doAddPlayercupmatches($l);
        }

        return $this;
    }

    /**
     * @param ChildPlayercupmatches $playercupmatches The ChildPlayercupmatches object to add.
     */
    protected function doAddPlayercupmatches(ChildPlayercupmatches $playercupmatches)
    {
        $this->collPlayercupmatchess[]= $playercupmatches;
        $playercupmatches->setCupMatchesGroup($this);
    }

    /**
     * @param  ChildPlayercupmatches $playercupmatches The ChildPlayercupmatches object to remove.
     * @return $this|ChildGroups The current object (for fluent API support)
     */
    public function removePlayercupmatches(ChildPlayercupmatches $playercupmatches)
    {
        if ($this->getPlayercupmatchess()->contains($playercupmatches)) {
            $pos = $this->collPlayercupmatchess->search($playercupmatches);
            $this->collPlayercupmatchess->remove($pos);
            if (null === $this->playercupmatchessScheduledForDeletion) {
                $this->playercupmatchessScheduledForDeletion = clone $this->collPlayercupmatchess;
                $this->playercupmatchessScheduledForDeletion->clear();
            }
            $this->playercupmatchessScheduledForDeletion[]= clone $playercupmatches;
            $playercupmatches->setCupMatchesGroup(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Groups is new, it will return
     * an empty collection; or if this Groups has previously
     * been saved, it will retrieve related Playercupmatchess from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Groups.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildPlayercupmatches[] List of ChildPlayercupmatches objects
     */
    public function getPlayercupmatchessJoinCupMatchesPlayerHome(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildPlayercupmatchesQuery::create(null, $criteria);
        $query->joinWith('CupMatchesPlayerHome', $joinBehavior);

        return $this->getPlayercupmatchess($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Groups is new, it will return
     * an empty collection; or if this Groups has previously
     * been saved, it will retrieve related Playercupmatchess from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Groups.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildPlayercupmatches[] List of ChildPlayercupmatches objects
     */
    public function getPlayercupmatchessJoinCupMatchesPlayerAway(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildPlayercupmatchesQuery::create(null, $criteria);
        $query->joinWith('CupMatchesPlayerAway', $joinBehavior);

        return $this->getPlayercupmatchess($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Groups is new, it will return
     * an empty collection; or if this Groups has previously
     * been saved, it will retrieve related Playercupmatchess from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Groups.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildPlayercupmatches[] List of ChildPlayercupmatches objects
     */
    public function getPlayercupmatchessJoinCupMatchesCupRound(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildPlayercupmatchesQuery::create(null, $criteria);
        $query->joinWith('CupMatchesCupRound', $joinBehavior);

        return $this->getPlayercupmatchess($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Groups is new, it will return
     * an empty collection; or if this Groups has previously
     * been saved, it will retrieve related Playercupmatchess from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Groups.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildPlayercupmatches[] List of ChildPlayercupmatches objects
     */
    public function getPlayercupmatchessJoinCupMatchesSeason(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildPlayercupmatchesQuery::create(null, $criteria);
        $query->joinWith('CupMatchesSeason', $joinBehavior);

        return $this->getPlayercupmatchess($query, $con);
    }

    /**
     * Clears out the collPlayerdivisionmatchess collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addPlayerdivisionmatchess()
     */
    public function clearPlayerdivisionmatchess()
    {
        $this->collPlayerdivisionmatchess = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collPlayerdivisionmatchess collection loaded partially.
     */
    public function resetPartialPlayerdivisionmatchess($v = true)
    {
        $this->collPlayerdivisionmatchessPartial = $v;
    }

    /**
     * Initializes the collPlayerdivisionmatchess collection.
     *
     * By default this just sets the collPlayerdivisionmatchess collection to an empty array (like clearcollPlayerdivisionmatchess());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPlayerdivisionmatchess($overrideExisting = true)
    {
        if (null !== $this->collPlayerdivisionmatchess && !$overrideExisting) {
            return;
        }
        $this->collPlayerdivisionmatchess = new ObjectCollection();
        $this->collPlayerdivisionmatchess->setModel('\Playerdivisionmatches');
    }

    /**
     * Gets an array of ChildPlayerdivisionmatches objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildGroups is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildPlayerdivisionmatches[] List of ChildPlayerdivisionmatches objects
     * @throws PropelException
     */
    public function getPlayerdivisionmatchess(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collPlayerdivisionmatchessPartial && !$this->isNew();
        if (null === $this->collPlayerdivisionmatchess || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPlayerdivisionmatchess) {
                // return empty collection
                $this->initPlayerdivisionmatchess();
            } else {
                $collPlayerdivisionmatchess = ChildPlayerdivisionmatchesQuery::create(null, $criteria)
                    ->filterByDivisionMatchesGroup($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collPlayerdivisionmatchessPartial && count($collPlayerdivisionmatchess)) {
                        $this->initPlayerdivisionmatchess(false);

                        foreach ($collPlayerdivisionmatchess as $obj) {
                            if (false == $this->collPlayerdivisionmatchess->contains($obj)) {
                                $this->collPlayerdivisionmatchess->append($obj);
                            }
                        }

                        $this->collPlayerdivisionmatchessPartial = true;
                    }

                    return $collPlayerdivisionmatchess;
                }

                if ($partial && $this->collPlayerdivisionmatchess) {
                    foreach ($this->collPlayerdivisionmatchess as $obj) {
                        if ($obj->isNew()) {
                            $collPlayerdivisionmatchess[] = $obj;
                        }
                    }
                }

                $this->collPlayerdivisionmatchess = $collPlayerdivisionmatchess;
                $this->collPlayerdivisionmatchessPartial = false;
            }
        }

        return $this->collPlayerdivisionmatchess;
    }

    /**
     * Sets a collection of ChildPlayerdivisionmatches objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $playerdivisionmatchess A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildGroups The current object (for fluent API support)
     */
    public function setPlayerdivisionmatchess(Collection $playerdivisionmatchess, ConnectionInterface $con = null)
    {
        /** @var ChildPlayerdivisionmatches[] $playerdivisionmatchessToDelete */
        $playerdivisionmatchessToDelete = $this->getPlayerdivisionmatchess(new Criteria(), $con)->diff($playerdivisionmatchess);


        $this->playerdivisionmatchessScheduledForDeletion = $playerdivisionmatchessToDelete;

        foreach ($playerdivisionmatchessToDelete as $playerdivisionmatchesRemoved) {
            $playerdivisionmatchesRemoved->setDivisionMatchesGroup(null);
        }

        $this->collPlayerdivisionmatchess = null;
        foreach ($playerdivisionmatchess as $playerdivisionmatches) {
            $this->addPlayerdivisionmatches($playerdivisionmatches);
        }

        $this->collPlayerdivisionmatchess = $playerdivisionmatchess;
        $this->collPlayerdivisionmatchessPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Playerdivisionmatches objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Playerdivisionmatches objects.
     * @throws PropelException
     */
    public function countPlayerdivisionmatchess(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collPlayerdivisionmatchessPartial && !$this->isNew();
        if (null === $this->collPlayerdivisionmatchess || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPlayerdivisionmatchess) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPlayerdivisionmatchess());
            }

            $query = ChildPlayerdivisionmatchesQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByDivisionMatchesGroup($this)
                ->count($con);
        }

        return count($this->collPlayerdivisionmatchess);
    }

    /**
     * Method called to associate a ChildPlayerdivisionmatches object to this object
     * through the ChildPlayerdivisionmatches foreign key attribute.
     *
     * @param  ChildPlayerdivisionmatches $l ChildPlayerdivisionmatches
     * @return $this|\Groups The current object (for fluent API support)
     */
    public function addPlayerdivisionmatches(ChildPlayerdivisionmatches $l)
    {
        if ($this->collPlayerdivisionmatchess === null) {
            $this->initPlayerdivisionmatchess();
            $this->collPlayerdivisionmatchessPartial = true;
        }

        if (!$this->collPlayerdivisionmatchess->contains($l)) {
            $this->doAddPlayerdivisionmatches($l);
        }

        return $this;
    }

    /**
     * @param ChildPlayerdivisionmatches $playerdivisionmatches The ChildPlayerdivisionmatches object to add.
     */
    protected function doAddPlayerdivisionmatches(ChildPlayerdivisionmatches $playerdivisionmatches)
    {
        $this->collPlayerdivisionmatchess[]= $playerdivisionmatches;
        $playerdivisionmatches->setDivisionMatchesGroup($this);
    }

    /**
     * @param  ChildPlayerdivisionmatches $playerdivisionmatches The ChildPlayerdivisionmatches object to remove.
     * @return $this|ChildGroups The current object (for fluent API support)
     */
    public function removePlayerdivisionmatches(ChildPlayerdivisionmatches $playerdivisionmatches)
    {
        if ($this->getPlayerdivisionmatchess()->contains($playerdivisionmatches)) {
            $pos = $this->collPlayerdivisionmatchess->search($playerdivisionmatches);
            $this->collPlayerdivisionmatchess->remove($pos);
            if (null === $this->playerdivisionmatchessScheduledForDeletion) {
                $this->playerdivisionmatchessScheduledForDeletion = clone $this->collPlayerdivisionmatchess;
                $this->playerdivisionmatchessScheduledForDeletion->clear();
            }
            $this->playerdivisionmatchessScheduledForDeletion[]= clone $playerdivisionmatches;
            $playerdivisionmatches->setDivisionMatchesGroup(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Groups is new, it will return
     * an empty collection; or if this Groups has previously
     * been saved, it will retrieve related Playerdivisionmatchess from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Groups.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildPlayerdivisionmatches[] List of ChildPlayerdivisionmatches objects
     */
    public function getPlayerdivisionmatchessJoinDivisionMatchesPlayerHome(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildPlayerdivisionmatchesQuery::create(null, $criteria);
        $query->joinWith('DivisionMatchesPlayerHome', $joinBehavior);

        return $this->getPlayerdivisionmatchess($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Groups is new, it will return
     * an empty collection; or if this Groups has previously
     * been saved, it will retrieve related Playerdivisionmatchess from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Groups.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildPlayerdivisionmatches[] List of ChildPlayerdivisionmatches objects
     */
    public function getPlayerdivisionmatchessJoinDivisionMatchesPlayerAway(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildPlayerdivisionmatchesQuery::create(null, $criteria);
        $query->joinWith('DivisionMatchesPlayerAway', $joinBehavior);

        return $this->getPlayerdivisionmatchess($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Groups is new, it will return
     * an empty collection; or if this Groups has previously
     * been saved, it will retrieve related Playerdivisionmatchess from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Groups.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildPlayerdivisionmatches[] List of ChildPlayerdivisionmatches objects
     */
    public function getPlayerdivisionmatchessJoinDivisionMatchesDivision(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildPlayerdivisionmatchesQuery::create(null, $criteria);
        $query->joinWith('DivisionMatchesDivision', $joinBehavior);

        return $this->getPlayerdivisionmatchess($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Groups is new, it will return
     * an empty collection; or if this Groups has previously
     * been saved, it will retrieve related Playerdivisionmatchess from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Groups.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildPlayerdivisionmatches[] List of ChildPlayerdivisionmatches objects
     */
    public function getPlayerdivisionmatchessJoinDivisionMatchesSeason(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildPlayerdivisionmatchesQuery::create(null, $criteria);
        $query->joinWith('DivisionMatchesSeason', $joinBehavior);

        return $this->getPlayerdivisionmatchess($query, $con);
    }

    /**
     * Clears out the collPlayergrouprankings collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addPlayergrouprankings()
     */
    public function clearPlayergrouprankings()
    {
        $this->collPlayergrouprankings = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collPlayergrouprankings collection loaded partially.
     */
    public function resetPartialPlayergrouprankings($v = true)
    {
        $this->collPlayergrouprankingsPartial = $v;
    }

    /**
     * Initializes the collPlayergrouprankings collection.
     *
     * By default this just sets the collPlayergrouprankings collection to an empty array (like clearcollPlayergrouprankings());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPlayergrouprankings($overrideExisting = true)
    {
        if (null !== $this->collPlayergrouprankings && !$overrideExisting) {
            return;
        }
        $this->collPlayergrouprankings = new ObjectCollection();
        $this->collPlayergrouprankings->setModel('\Playergroupranking');
    }

    /**
     * Gets an array of ChildPlayergroupranking objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildGroups is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildPlayergroupranking[] List of ChildPlayergroupranking objects
     * @throws PropelException
     */
    public function getPlayergrouprankings(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collPlayergrouprankingsPartial && !$this->isNew();
        if (null === $this->collPlayergrouprankings || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPlayergrouprankings) {
                // return empty collection
                $this->initPlayergrouprankings();
            } else {
                $collPlayergrouprankings = ChildPlayergrouprankingQuery::create(null, $criteria)
                    ->filterByGroupRanking($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collPlayergrouprankingsPartial && count($collPlayergrouprankings)) {
                        $this->initPlayergrouprankings(false);

                        foreach ($collPlayergrouprankings as $obj) {
                            if (false == $this->collPlayergrouprankings->contains($obj)) {
                                $this->collPlayergrouprankings->append($obj);
                            }
                        }

                        $this->collPlayergrouprankingsPartial = true;
                    }

                    return $collPlayergrouprankings;
                }

                if ($partial && $this->collPlayergrouprankings) {
                    foreach ($this->collPlayergrouprankings as $obj) {
                        if ($obj->isNew()) {
                            $collPlayergrouprankings[] = $obj;
                        }
                    }
                }

                $this->collPlayergrouprankings = $collPlayergrouprankings;
                $this->collPlayergrouprankingsPartial = false;
            }
        }

        return $this->collPlayergrouprankings;
    }

    /**
     * Sets a collection of ChildPlayergroupranking objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $playergrouprankings A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildGroups The current object (for fluent API support)
     */
    public function setPlayergrouprankings(Collection $playergrouprankings, ConnectionInterface $con = null)
    {
        /** @var ChildPlayergroupranking[] $playergrouprankingsToDelete */
        $playergrouprankingsToDelete = $this->getPlayergrouprankings(new Criteria(), $con)->diff($playergrouprankings);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->playergrouprankingsScheduledForDeletion = clone $playergrouprankingsToDelete;

        foreach ($playergrouprankingsToDelete as $playergrouprankingRemoved) {
            $playergrouprankingRemoved->setGroupRanking(null);
        }

        $this->collPlayergrouprankings = null;
        foreach ($playergrouprankings as $playergroupranking) {
            $this->addPlayergroupranking($playergroupranking);
        }

        $this->collPlayergrouprankings = $playergrouprankings;
        $this->collPlayergrouprankingsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Playergroupranking objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Playergroupranking objects.
     * @throws PropelException
     */
    public function countPlayergrouprankings(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collPlayergrouprankingsPartial && !$this->isNew();
        if (null === $this->collPlayergrouprankings || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPlayergrouprankings) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPlayergrouprankings());
            }

            $query = ChildPlayergrouprankingQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByGroupRanking($this)
                ->count($con);
        }

        return count($this->collPlayergrouprankings);
    }

    /**
     * Method called to associate a ChildPlayergroupranking object to this object
     * through the ChildPlayergroupranking foreign key attribute.
     *
     * @param  ChildPlayergroupranking $l ChildPlayergroupranking
     * @return $this|\Groups The current object (for fluent API support)
     */
    public function addPlayergroupranking(ChildPlayergroupranking $l)
    {
        if ($this->collPlayergrouprankings === null) {
            $this->initPlayergrouprankings();
            $this->collPlayergrouprankingsPartial = true;
        }

        if (!$this->collPlayergrouprankings->contains($l)) {
            $this->doAddPlayergroupranking($l);
        }

        return $this;
    }

    /**
     * @param ChildPlayergroupranking $playergroupranking The ChildPlayergroupranking object to add.
     */
    protected function doAddPlayergroupranking(ChildPlayergroupranking $playergroupranking)
    {
        $this->collPlayergrouprankings[]= $playergroupranking;
        $playergroupranking->setGroupRanking($this);
    }

    /**
     * @param  ChildPlayergroupranking $playergroupranking The ChildPlayergroupranking object to remove.
     * @return $this|ChildGroups The current object (for fluent API support)
     */
    public function removePlayergroupranking(ChildPlayergroupranking $playergroupranking)
    {
        if ($this->getPlayergrouprankings()->contains($playergroupranking)) {
            $pos = $this->collPlayergrouprankings->search($playergroupranking);
            $this->collPlayergrouprankings->remove($pos);
            if (null === $this->playergrouprankingsScheduledForDeletion) {
                $this->playergrouprankingsScheduledForDeletion = clone $this->collPlayergrouprankings;
                $this->playergrouprankingsScheduledForDeletion->clear();
            }
            $this->playergrouprankingsScheduledForDeletion[]= clone $playergroupranking;
            $playergroupranking->setGroupRanking(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Groups is new, it will return
     * an empty collection; or if this Groups has previously
     * been saved, it will retrieve related Playergrouprankings from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Groups.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildPlayergroupranking[] List of ChildPlayergroupranking objects
     */
    public function getPlayergrouprankingsJoinPlayerRanking(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildPlayergrouprankingQuery::create(null, $criteria);
        $query->joinWith('PlayerRanking', $joinBehavior);

        return $this->getPlayergrouprankings($query, $con);
    }

    /**
     * Clears out the collPlayergroupresultss collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addPlayergroupresultss()
     */
    public function clearPlayergroupresultss()
    {
        $this->collPlayergroupresultss = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collPlayergroupresultss collection loaded partially.
     */
    public function resetPartialPlayergroupresultss($v = true)
    {
        $this->collPlayergroupresultssPartial = $v;
    }

    /**
     * Initializes the collPlayergroupresultss collection.
     *
     * By default this just sets the collPlayergroupresultss collection to an empty array (like clearcollPlayergroupresultss());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPlayergroupresultss($overrideExisting = true)
    {
        if (null !== $this->collPlayergroupresultss && !$overrideExisting) {
            return;
        }
        $this->collPlayergroupresultss = new ObjectCollection();
        $this->collPlayergroupresultss->setModel('\Playergroupresults');
    }

    /**
     * Gets an array of ChildPlayergroupresults objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildGroups is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildPlayergroupresults[] List of ChildPlayergroupresults objects
     * @throws PropelException
     */
    public function getPlayergroupresultss(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collPlayergroupresultssPartial && !$this->isNew();
        if (null === $this->collPlayergroupresultss || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPlayergroupresultss) {
                // return empty collection
                $this->initPlayergroupresultss();
            } else {
                $collPlayergroupresultss = ChildPlayergroupresultsQuery::create(null, $criteria)
                    ->filterByGroupResult($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collPlayergroupresultssPartial && count($collPlayergroupresultss)) {
                        $this->initPlayergroupresultss(false);

                        foreach ($collPlayergroupresultss as $obj) {
                            if (false == $this->collPlayergroupresultss->contains($obj)) {
                                $this->collPlayergroupresultss->append($obj);
                            }
                        }

                        $this->collPlayergroupresultssPartial = true;
                    }

                    return $collPlayergroupresultss;
                }

                if ($partial && $this->collPlayergroupresultss) {
                    foreach ($this->collPlayergroupresultss as $obj) {
                        if ($obj->isNew()) {
                            $collPlayergroupresultss[] = $obj;
                        }
                    }
                }

                $this->collPlayergroupresultss = $collPlayergroupresultss;
                $this->collPlayergroupresultssPartial = false;
            }
        }

        return $this->collPlayergroupresultss;
    }

    /**
     * Sets a collection of ChildPlayergroupresults objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $playergroupresultss A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildGroups The current object (for fluent API support)
     */
    public function setPlayergroupresultss(Collection $playergroupresultss, ConnectionInterface $con = null)
    {
        /** @var ChildPlayergroupresults[] $playergroupresultssToDelete */
        $playergroupresultssToDelete = $this->getPlayergroupresultss(new Criteria(), $con)->diff($playergroupresultss);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->playergroupresultssScheduledForDeletion = clone $playergroupresultssToDelete;

        foreach ($playergroupresultssToDelete as $playergroupresultsRemoved) {
            $playergroupresultsRemoved->setGroupResult(null);
        }

        $this->collPlayergroupresultss = null;
        foreach ($playergroupresultss as $playergroupresults) {
            $this->addPlayergroupresults($playergroupresults);
        }

        $this->collPlayergroupresultss = $playergroupresultss;
        $this->collPlayergroupresultssPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Playergroupresults objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Playergroupresults objects.
     * @throws PropelException
     */
    public function countPlayergroupresultss(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collPlayergroupresultssPartial && !$this->isNew();
        if (null === $this->collPlayergroupresultss || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPlayergroupresultss) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPlayergroupresultss());
            }

            $query = ChildPlayergroupresultsQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByGroupResult($this)
                ->count($con);
        }

        return count($this->collPlayergroupresultss);
    }

    /**
     * Method called to associate a ChildPlayergroupresults object to this object
     * through the ChildPlayergroupresults foreign key attribute.
     *
     * @param  ChildPlayergroupresults $l ChildPlayergroupresults
     * @return $this|\Groups The current object (for fluent API support)
     */
    public function addPlayergroupresults(ChildPlayergroupresults $l)
    {
        if ($this->collPlayergroupresultss === null) {
            $this->initPlayergroupresultss();
            $this->collPlayergroupresultssPartial = true;
        }

        if (!$this->collPlayergroupresultss->contains($l)) {
            $this->doAddPlayergroupresults($l);
        }

        return $this;
    }

    /**
     * @param ChildPlayergroupresults $playergroupresults The ChildPlayergroupresults object to add.
     */
    protected function doAddPlayergroupresults(ChildPlayergroupresults $playergroupresults)
    {
        $this->collPlayergroupresultss[]= $playergroupresults;
        $playergroupresults->setGroupResult($this);
    }

    /**
     * @param  ChildPlayergroupresults $playergroupresults The ChildPlayergroupresults object to remove.
     * @return $this|ChildGroups The current object (for fluent API support)
     */
    public function removePlayergroupresults(ChildPlayergroupresults $playergroupresults)
    {
        if ($this->getPlayergroupresultss()->contains($playergroupresults)) {
            $pos = $this->collPlayergroupresultss->search($playergroupresults);
            $this->collPlayergroupresultss->remove($pos);
            if (null === $this->playergroupresultssScheduledForDeletion) {
                $this->playergroupresultssScheduledForDeletion = clone $this->collPlayergroupresultss;
                $this->playergroupresultssScheduledForDeletion->clear();
            }
            $this->playergroupresultssScheduledForDeletion[]= clone $playergroupresults;
            $playergroupresults->setGroupResult(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Groups is new, it will return
     * an empty collection; or if this Groups has previously
     * been saved, it will retrieve related Playergroupresultss from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Groups.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildPlayergroupresults[] List of ChildPlayergroupresults objects
     */
    public function getPlayergroupresultssJoinPlayerResult(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildPlayergroupresultsQuery::create(null, $criteria);
        $query->joinWith('PlayerResult', $joinBehavior);

        return $this->getPlayergroupresultss($query, $con);
    }

    /**
     * Clears out the collPlayergroupstatess collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addPlayergroupstatess()
     */
    public function clearPlayergroupstatess()
    {
        $this->collPlayergroupstatess = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collPlayergroupstatess collection loaded partially.
     */
    public function resetPartialPlayergroupstatess($v = true)
    {
        $this->collPlayergroupstatessPartial = $v;
    }

    /**
     * Initializes the collPlayergroupstatess collection.
     *
     * By default this just sets the collPlayergroupstatess collection to an empty array (like clearcollPlayergroupstatess());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPlayergroupstatess($overrideExisting = true)
    {
        if (null !== $this->collPlayergroupstatess && !$overrideExisting) {
            return;
        }
        $this->collPlayergroupstatess = new ObjectCollection();
        $this->collPlayergroupstatess->setModel('\Playergroupstates');
    }

    /**
     * Gets an array of ChildPlayergroupstates objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildGroups is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildPlayergroupstates[] List of ChildPlayergroupstates objects
     * @throws PropelException
     */
    public function getPlayergroupstatess(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collPlayergroupstatessPartial && !$this->isNew();
        if (null === $this->collPlayergroupstatess || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPlayergroupstatess) {
                // return empty collection
                $this->initPlayergroupstatess();
            } else {
                $collPlayergroupstatess = ChildPlayergroupstatesQuery::create(null, $criteria)
                    ->filterByGroupState($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collPlayergroupstatessPartial && count($collPlayergroupstatess)) {
                        $this->initPlayergroupstatess(false);

                        foreach ($collPlayergroupstatess as $obj) {
                            if (false == $this->collPlayergroupstatess->contains($obj)) {
                                $this->collPlayergroupstatess->append($obj);
                            }
                        }

                        $this->collPlayergroupstatessPartial = true;
                    }

                    return $collPlayergroupstatess;
                }

                if ($partial && $this->collPlayergroupstatess) {
                    foreach ($this->collPlayergroupstatess as $obj) {
                        if ($obj->isNew()) {
                            $collPlayergroupstatess[] = $obj;
                        }
                    }
                }

                $this->collPlayergroupstatess = $collPlayergroupstatess;
                $this->collPlayergroupstatessPartial = false;
            }
        }

        return $this->collPlayergroupstatess;
    }

    /**
     * Sets a collection of ChildPlayergroupstates objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $playergroupstatess A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildGroups The current object (for fluent API support)
     */
    public function setPlayergroupstatess(Collection $playergroupstatess, ConnectionInterface $con = null)
    {
        /** @var ChildPlayergroupstates[] $playergroupstatessToDelete */
        $playergroupstatessToDelete = $this->getPlayergroupstatess(new Criteria(), $con)->diff($playergroupstatess);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->playergroupstatessScheduledForDeletion = clone $playergroupstatessToDelete;

        foreach ($playergroupstatessToDelete as $playergroupstatesRemoved) {
            $playergroupstatesRemoved->setGroupState(null);
        }

        $this->collPlayergroupstatess = null;
        foreach ($playergroupstatess as $playergroupstates) {
            $this->addPlayergroupstates($playergroupstates);
        }

        $this->collPlayergroupstatess = $playergroupstatess;
        $this->collPlayergroupstatessPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Playergroupstates objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Playergroupstates objects.
     * @throws PropelException
     */
    public function countPlayergroupstatess(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collPlayergroupstatessPartial && !$this->isNew();
        if (null === $this->collPlayergroupstatess || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPlayergroupstatess) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPlayergroupstatess());
            }

            $query = ChildPlayergroupstatesQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByGroupState($this)
                ->count($con);
        }

        return count($this->collPlayergroupstatess);
    }

    /**
     * Method called to associate a ChildPlayergroupstates object to this object
     * through the ChildPlayergroupstates foreign key attribute.
     *
     * @param  ChildPlayergroupstates $l ChildPlayergroupstates
     * @return $this|\Groups The current object (for fluent API support)
     */
    public function addPlayergroupstates(ChildPlayergroupstates $l)
    {
        if ($this->collPlayergroupstatess === null) {
            $this->initPlayergroupstatess();
            $this->collPlayergroupstatessPartial = true;
        }

        if (!$this->collPlayergroupstatess->contains($l)) {
            $this->doAddPlayergroupstates($l);
        }

        return $this;
    }

    /**
     * @param ChildPlayergroupstates $playergroupstates The ChildPlayergroupstates object to add.
     */
    protected function doAddPlayergroupstates(ChildPlayergroupstates $playergroupstates)
    {
        $this->collPlayergroupstatess[]= $playergroupstates;
        $playergroupstates->setGroupState($this);
    }

    /**
     * @param  ChildPlayergroupstates $playergroupstates The ChildPlayergroupstates object to remove.
     * @return $this|ChildGroups The current object (for fluent API support)
     */
    public function removePlayergroupstates(ChildPlayergroupstates $playergroupstates)
    {
        if ($this->getPlayergroupstatess()->contains($playergroupstates)) {
            $pos = $this->collPlayergroupstatess->search($playergroupstates);
            $this->collPlayergroupstatess->remove($pos);
            if (null === $this->playergroupstatessScheduledForDeletion) {
                $this->playergroupstatessScheduledForDeletion = clone $this->collPlayergroupstatess;
                $this->playergroupstatessScheduledForDeletion->clear();
            }
            $this->playergroupstatessScheduledForDeletion[]= clone $playergroupstates;
            $playergroupstates->setGroupState(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Groups is new, it will return
     * an empty collection; or if this Groups has previously
     * been saved, it will retrieve related Playergroupstatess from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Groups.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildPlayergroupstates[] List of ChildPlayergroupstates objects
     */
    public function getPlayergroupstatessJoinPlayerState(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildPlayergroupstatesQuery::create(null, $criteria);
        $query->joinWith('PlayerState', $joinBehavior);

        return $this->getPlayergroupstatess($query, $con);
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        if (null !== $this->aCompetitions) {
            $this->aCompetitions->removeGroups($this);
        }
        $this->primarykey = null;
        $this->description = null;
        $this->code = null;
        $this->competitionkey = null;
        $this->begindate = null;
        $this->enddate = null;
        $this->status = null;
        $this->iscompleted = null;
        $this->daykey = null;
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
            if ($this->collMatchess) {
                foreach ($this->collMatchess as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPlayercupmatchess) {
                foreach ($this->collPlayercupmatchess as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPlayerdivisionmatchess) {
                foreach ($this->collPlayerdivisionmatchess as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPlayergrouprankings) {
                foreach ($this->collPlayergrouprankings as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPlayergroupresultss) {
                foreach ($this->collPlayergroupresultss as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPlayergroupstatess) {
                foreach ($this->collPlayergroupstatess as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collMatchess = null;
        $this->collPlayercupmatchess = null;
        $this->collPlayerdivisionmatchess = null;
        $this->collPlayergrouprankings = null;
        $this->collPlayergroupresultss = null;
        $this->collPlayergroupstatess = null;
        $this->aCompetitions = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(GroupsTableMap::DEFAULT_STRING_FORMAT);
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
