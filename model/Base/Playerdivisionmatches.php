<?php

namespace Base;

use \PlayerdivisionmatchesQuery as ChildPlayerdivisionmatchesQuery;
use \DateTime;
use \Exception;
use \PDO;
use Map\PlayerdivisionmatchesTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\BadMethodCallException;
use Propel\Runtime\Exception\LogicException;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Parser\AbstractParser;
use Propel\Runtime\Util\PropelDateTime;

/**
 * Base class that represents a row from the 'playerdivisionmatches' table.
 *
 *
 *
* @package    propel.generator..Base
*/
abstract class Playerdivisionmatches implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\Map\\PlayerdivisionmatchesTableMap';


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
     * The value for the playerhomekey field.
     * @var        int
     */
    protected $playerhomekey;

    /**
     * The value for the playerawaykey field.
     * @var        int
     */
    protected $playerawaykey;

    /**
     * The value for the seasonkey field.
     * @var        int
     */
    protected $seasonkey;

    /**
     * The value for the divisionkey field.
     * @var        int
     */
    protected $divisionkey;

    /**
     * The value for the groupkey field.
     * @var        int
     */
    protected $groupkey;

    /**
     * The value for the homescore field.
     * @var        int
     */
    protected $homescore;

    /**
     * The value for the awayscore field.
     * @var        int
     */
    protected $awayscore;

    /**
     * The value for the scheduledate field.
     * @var        \DateTime
     */
    protected $scheduledate;

    /**
     * The value for the resultdate field.
     * @var        \DateTime
     */
    protected $resultdate;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * Initializes internal state of Base\Playerdivisionmatches object.
     */
    public function __construct()
    {
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
     * Compares this with another <code>Playerdivisionmatches</code> instance.  If
     * <code>obj</code> is an instance of <code>Playerdivisionmatches</code>, delegates to
     * <code>equals(Playerdivisionmatches)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|Playerdivisionmatches The current object, for fluid interface
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
    public function getPlayerDivisionMatchPK()
    {
        return $this->primarykey;
    }

    /**
     * Get the [playerhomekey] column value.
     *
     * @return int
     */
    public function getPlayerhomekey()
    {
        return $this->playerhomekey;
    }

    /**
     * Get the [playerawaykey] column value.
     *
     * @return int
     */
    public function getPlayerawaykey()
    {
        return $this->playerawaykey;
    }

    /**
     * Get the [seasonkey] column value.
     *
     * @return int
     */
    public function getSeasonkey()
    {
        return $this->seasonkey;
    }

    /**
     * Get the [divisionkey] column value.
     *
     * @return int
     */
    public function getDivisionkey()
    {
        return $this->divisionkey;
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
     * Get the [homescore] column value.
     *
     * @return int
     */
    public function getHomescore()
    {
        return $this->homescore;
    }

    /**
     * Get the [awayscore] column value.
     *
     * @return int
     */
    public function getAwayscore()
    {
        return $this->awayscore;
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
     * Get the [optionally formatted] temporal [resultdate] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getResultdate($format = NULL)
    {
        if ($format === null) {
            return $this->resultdate;
        } else {
            return $this->resultdate instanceof \DateTime ? $this->resultdate->format($format) : null;
        }
    }

    /**
     * Set the value of [primarykey] column.
     *
     * @param int $v new value
     * @return $this|\Playerdivisionmatches The current object (for fluent API support)
     */
    public function setPlayerDivisionMatchPK($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->primarykey !== $v) {
            $this->primarykey = $v;
            $this->modifiedColumns[PlayerdivisionmatchesTableMap::COL_PRIMARYKEY] = true;
        }

        return $this;
    } // setPlayerDivisionMatchPK()

    /**
     * Set the value of [playerhomekey] column.
     *
     * @param int $v new value
     * @return $this|\Playerdivisionmatches The current object (for fluent API support)
     */
    public function setPlayerhomekey($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->playerhomekey !== $v) {
            $this->playerhomekey = $v;
            $this->modifiedColumns[PlayerdivisionmatchesTableMap::COL_PLAYERHOMEKEY] = true;
        }

        return $this;
    } // setPlayerhomekey()

    /**
     * Set the value of [playerawaykey] column.
     *
     * @param int $v new value
     * @return $this|\Playerdivisionmatches The current object (for fluent API support)
     */
    public function setPlayerawaykey($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->playerawaykey !== $v) {
            $this->playerawaykey = $v;
            $this->modifiedColumns[PlayerdivisionmatchesTableMap::COL_PLAYERAWAYKEY] = true;
        }

        return $this;
    } // setPlayerawaykey()

    /**
     * Set the value of [seasonkey] column.
     *
     * @param int $v new value
     * @return $this|\Playerdivisionmatches The current object (for fluent API support)
     */
    public function setSeasonkey($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->seasonkey !== $v) {
            $this->seasonkey = $v;
            $this->modifiedColumns[PlayerdivisionmatchesTableMap::COL_SEASONKEY] = true;
        }

        return $this;
    } // setSeasonkey()

    /**
     * Set the value of [divisionkey] column.
     *
     * @param int $v new value
     * @return $this|\Playerdivisionmatches The current object (for fluent API support)
     */
    public function setDivisionkey($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->divisionkey !== $v) {
            $this->divisionkey = $v;
            $this->modifiedColumns[PlayerdivisionmatchesTableMap::COL_DIVISIONKEY] = true;
        }

        return $this;
    } // setDivisionkey()

    /**
     * Set the value of [groupkey] column.
     *
     * @param int $v new value
     * @return $this|\Playerdivisionmatches The current object (for fluent API support)
     */
    public function setGroupkey($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->groupkey !== $v) {
            $this->groupkey = $v;
            $this->modifiedColumns[PlayerdivisionmatchesTableMap::COL_GROUPKEY] = true;
        }

        return $this;
    } // setGroupkey()

    /**
     * Set the value of [homescore] column.
     *
     * @param int $v new value
     * @return $this|\Playerdivisionmatches The current object (for fluent API support)
     */
    public function setHomescore($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->homescore !== $v) {
            $this->homescore = $v;
            $this->modifiedColumns[PlayerdivisionmatchesTableMap::COL_HOMESCORE] = true;
        }

        return $this;
    } // setHomescore()

    /**
     * Set the value of [awayscore] column.
     *
     * @param int $v new value
     * @return $this|\Playerdivisionmatches The current object (for fluent API support)
     */
    public function setAwayscore($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->awayscore !== $v) {
            $this->awayscore = $v;
            $this->modifiedColumns[PlayerdivisionmatchesTableMap::COL_AWAYSCORE] = true;
        }

        return $this;
    } // setAwayscore()

    /**
     * Sets the value of [scheduledate] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTime value.
     *               Empty strings are treated as NULL.
     * @return $this|\Playerdivisionmatches The current object (for fluent API support)
     */
    public function setScheduledate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->scheduledate !== null || $dt !== null) {
            if ($this->scheduledate === null || $dt === null || $dt->format("Y-m-d H:i:s") !== $this->scheduledate->format("Y-m-d H:i:s")) {
                $this->scheduledate = $dt === null ? null : clone $dt;
                $this->modifiedColumns[PlayerdivisionmatchesTableMap::COL_SCHEDULEDATE] = true;
            }
        } // if either are not null

        return $this;
    } // setScheduledate()

    /**
     * Sets the value of [resultdate] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTime value.
     *               Empty strings are treated as NULL.
     * @return $this|\Playerdivisionmatches The current object (for fluent API support)
     */
    public function setResultdate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->resultdate !== null || $dt !== null) {
            if ($this->resultdate === null || $dt === null || $dt->format("Y-m-d") !== $this->resultdate->format("Y-m-d")) {
                $this->resultdate = $dt === null ? null : clone $dt;
                $this->modifiedColumns[PlayerdivisionmatchesTableMap::COL_RESULTDATE] = true;
            }
        } // if either are not null

        return $this;
    } // setResultdate()

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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : PlayerdivisionmatchesTableMap::translateFieldName('PlayerDivisionMatchPK', TableMap::TYPE_PHPNAME, $indexType)];
            $this->primarykey = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : PlayerdivisionmatchesTableMap::translateFieldName('Playerhomekey', TableMap::TYPE_PHPNAME, $indexType)];
            $this->playerhomekey = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : PlayerdivisionmatchesTableMap::translateFieldName('Playerawaykey', TableMap::TYPE_PHPNAME, $indexType)];
            $this->playerawaykey = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : PlayerdivisionmatchesTableMap::translateFieldName('Seasonkey', TableMap::TYPE_PHPNAME, $indexType)];
            $this->seasonkey = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : PlayerdivisionmatchesTableMap::translateFieldName('Divisionkey', TableMap::TYPE_PHPNAME, $indexType)];
            $this->divisionkey = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : PlayerdivisionmatchesTableMap::translateFieldName('Groupkey', TableMap::TYPE_PHPNAME, $indexType)];
            $this->groupkey = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : PlayerdivisionmatchesTableMap::translateFieldName('Homescore', TableMap::TYPE_PHPNAME, $indexType)];
            $this->homescore = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : PlayerdivisionmatchesTableMap::translateFieldName('Awayscore', TableMap::TYPE_PHPNAME, $indexType)];
            $this->awayscore = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : PlayerdivisionmatchesTableMap::translateFieldName('Scheduledate', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->scheduledate = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 9 + $startcol : PlayerdivisionmatchesTableMap::translateFieldName('Resultdate', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00') {
                $col = null;
            }
            $this->resultdate = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 10; // 10 = PlayerdivisionmatchesTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Playerdivisionmatches'), 0, $e);
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
            $con = Propel::getServiceContainer()->getReadConnection(PlayerdivisionmatchesTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildPlayerdivisionmatchesQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Playerdivisionmatches::setDeleted()
     * @see Playerdivisionmatches::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(PlayerdivisionmatchesTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildPlayerdivisionmatchesQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(PlayerdivisionmatchesTableMap::DATABASE_NAME);
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
                PlayerdivisionmatchesTableMap::addInstanceToPool($this);
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

        $this->modifiedColumns[PlayerdivisionmatchesTableMap::COL_PRIMARYKEY] = true;
        if (null !== $this->primarykey) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . PlayerdivisionmatchesTableMap::COL_PRIMARYKEY . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(PlayerdivisionmatchesTableMap::COL_PRIMARYKEY)) {
            $modifiedColumns[':p' . $index++]  = 'PrimaryKey';
        }
        if ($this->isColumnModified(PlayerdivisionmatchesTableMap::COL_PLAYERHOMEKEY)) {
            $modifiedColumns[':p' . $index++]  = 'PlayerHomeKey';
        }
        if ($this->isColumnModified(PlayerdivisionmatchesTableMap::COL_PLAYERAWAYKEY)) {
            $modifiedColumns[':p' . $index++]  = 'PlayerAwayKey';
        }
        if ($this->isColumnModified(PlayerdivisionmatchesTableMap::COL_SEASONKEY)) {
            $modifiedColumns[':p' . $index++]  = 'SeasonKey';
        }
        if ($this->isColumnModified(PlayerdivisionmatchesTableMap::COL_DIVISIONKEY)) {
            $modifiedColumns[':p' . $index++]  = 'DivisionKey';
        }
        if ($this->isColumnModified(PlayerdivisionmatchesTableMap::COL_GROUPKEY)) {
            $modifiedColumns[':p' . $index++]  = 'GroupKey';
        }
        if ($this->isColumnModified(PlayerdivisionmatchesTableMap::COL_HOMESCORE)) {
            $modifiedColumns[':p' . $index++]  = 'HomeScore';
        }
        if ($this->isColumnModified(PlayerdivisionmatchesTableMap::COL_AWAYSCORE)) {
            $modifiedColumns[':p' . $index++]  = 'AwayScore';
        }
        if ($this->isColumnModified(PlayerdivisionmatchesTableMap::COL_SCHEDULEDATE)) {
            $modifiedColumns[':p' . $index++]  = 'ScheduleDate';
        }
        if ($this->isColumnModified(PlayerdivisionmatchesTableMap::COL_RESULTDATE)) {
            $modifiedColumns[':p' . $index++]  = 'ResultDate';
        }

        $sql = sprintf(
            'INSERT INTO playerdivisionmatches (%s) VALUES (%s)',
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
                    case 'PlayerHomeKey':
                        $stmt->bindValue($identifier, $this->playerhomekey, PDO::PARAM_INT);
                        break;
                    case 'PlayerAwayKey':
                        $stmt->bindValue($identifier, $this->playerawaykey, PDO::PARAM_INT);
                        break;
                    case 'SeasonKey':
                        $stmt->bindValue($identifier, $this->seasonkey, PDO::PARAM_INT);
                        break;
                    case 'DivisionKey':
                        $stmt->bindValue($identifier, $this->divisionkey, PDO::PARAM_INT);
                        break;
                    case 'GroupKey':
                        $stmt->bindValue($identifier, $this->groupkey, PDO::PARAM_INT);
                        break;
                    case 'HomeScore':
                        $stmt->bindValue($identifier, $this->homescore, PDO::PARAM_INT);
                        break;
                    case 'AwayScore':
                        $stmt->bindValue($identifier, $this->awayscore, PDO::PARAM_INT);
                        break;
                    case 'ScheduleDate':
                        $stmt->bindValue($identifier, $this->scheduledate ? $this->scheduledate->format("Y-m-d H:i:s") : null, PDO::PARAM_STR);
                        break;
                    case 'ResultDate':
                        $stmt->bindValue($identifier, $this->resultdate ? $this->resultdate->format("Y-m-d H:i:s") : null, PDO::PARAM_STR);
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
        $this->setPlayerDivisionMatchPK($pk);

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
        $pos = PlayerdivisionmatchesTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getPlayerDivisionMatchPK();
                break;
            case 1:
                return $this->getPlayerhomekey();
                break;
            case 2:
                return $this->getPlayerawaykey();
                break;
            case 3:
                return $this->getSeasonkey();
                break;
            case 4:
                return $this->getDivisionkey();
                break;
            case 5:
                return $this->getGroupkey();
                break;
            case 6:
                return $this->getHomescore();
                break;
            case 7:
                return $this->getAwayscore();
                break;
            case 8:
                return $this->getScheduledate();
                break;
            case 9:
                return $this->getResultdate();
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
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = TableMap::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array())
    {

        if (isset($alreadyDumpedObjects['Playerdivisionmatches'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Playerdivisionmatches'][$this->hashCode()] = true;
        $keys = PlayerdivisionmatchesTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getPlayerDivisionMatchPK(),
            $keys[1] => $this->getPlayerhomekey(),
            $keys[2] => $this->getPlayerawaykey(),
            $keys[3] => $this->getSeasonkey(),
            $keys[4] => $this->getDivisionkey(),
            $keys[5] => $this->getGroupkey(),
            $keys[6] => $this->getHomescore(),
            $keys[7] => $this->getAwayscore(),
            $keys[8] => $this->getScheduledate(),
            $keys[9] => $this->getResultdate(),
        );

        $utc = new \DateTimeZone('utc');
        if ($result[$keys[8]] instanceof \DateTime) {
            // When changing timezone we don't want to change existing instances
            $dateTime = clone $result[$keys[8]];
            $result[$keys[8]] = $dateTime->setTimezone($utc)->format('Y-m-d\TH:i:s\Z');
        }

        if ($result[$keys[9]] instanceof \DateTime) {
            // When changing timezone we don't want to change existing instances
            $dateTime = clone $result[$keys[9]];
            $result[$keys[9]] = $dateTime->setTimezone($utc)->format('Y-m-d\TH:i:s\Z');
        }

        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
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
     * @return $this|\Playerdivisionmatches
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = PlayerdivisionmatchesTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\Playerdivisionmatches
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setPlayerDivisionMatchPK($value);
                break;
            case 1:
                $this->setPlayerhomekey($value);
                break;
            case 2:
                $this->setPlayerawaykey($value);
                break;
            case 3:
                $this->setSeasonkey($value);
                break;
            case 4:
                $this->setDivisionkey($value);
                break;
            case 5:
                $this->setGroupkey($value);
                break;
            case 6:
                $this->setHomescore($value);
                break;
            case 7:
                $this->setAwayscore($value);
                break;
            case 8:
                $this->setScheduledate($value);
                break;
            case 9:
                $this->setResultdate($value);
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
        $keys = PlayerdivisionmatchesTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setPlayerDivisionMatchPK($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setPlayerhomekey($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setPlayerawaykey($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setSeasonkey($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setDivisionkey($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setGroupkey($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setHomescore($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setAwayscore($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->setScheduledate($arr[$keys[8]]);
        }
        if (array_key_exists($keys[9], $arr)) {
            $this->setResultdate($arr[$keys[9]]);
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
     * @return $this|\Playerdivisionmatches The current object, for fluid interface
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
        $criteria = new Criteria(PlayerdivisionmatchesTableMap::DATABASE_NAME);

        if ($this->isColumnModified(PlayerdivisionmatchesTableMap::COL_PRIMARYKEY)) {
            $criteria->add(PlayerdivisionmatchesTableMap::COL_PRIMARYKEY, $this->primarykey);
        }
        if ($this->isColumnModified(PlayerdivisionmatchesTableMap::COL_PLAYERHOMEKEY)) {
            $criteria->add(PlayerdivisionmatchesTableMap::COL_PLAYERHOMEKEY, $this->playerhomekey);
        }
        if ($this->isColumnModified(PlayerdivisionmatchesTableMap::COL_PLAYERAWAYKEY)) {
            $criteria->add(PlayerdivisionmatchesTableMap::COL_PLAYERAWAYKEY, $this->playerawaykey);
        }
        if ($this->isColumnModified(PlayerdivisionmatchesTableMap::COL_SEASONKEY)) {
            $criteria->add(PlayerdivisionmatchesTableMap::COL_SEASONKEY, $this->seasonkey);
        }
        if ($this->isColumnModified(PlayerdivisionmatchesTableMap::COL_DIVISIONKEY)) {
            $criteria->add(PlayerdivisionmatchesTableMap::COL_DIVISIONKEY, $this->divisionkey);
        }
        if ($this->isColumnModified(PlayerdivisionmatchesTableMap::COL_GROUPKEY)) {
            $criteria->add(PlayerdivisionmatchesTableMap::COL_GROUPKEY, $this->groupkey);
        }
        if ($this->isColumnModified(PlayerdivisionmatchesTableMap::COL_HOMESCORE)) {
            $criteria->add(PlayerdivisionmatchesTableMap::COL_HOMESCORE, $this->homescore);
        }
        if ($this->isColumnModified(PlayerdivisionmatchesTableMap::COL_AWAYSCORE)) {
            $criteria->add(PlayerdivisionmatchesTableMap::COL_AWAYSCORE, $this->awayscore);
        }
        if ($this->isColumnModified(PlayerdivisionmatchesTableMap::COL_SCHEDULEDATE)) {
            $criteria->add(PlayerdivisionmatchesTableMap::COL_SCHEDULEDATE, $this->scheduledate);
        }
        if ($this->isColumnModified(PlayerdivisionmatchesTableMap::COL_RESULTDATE)) {
            $criteria->add(PlayerdivisionmatchesTableMap::COL_RESULTDATE, $this->resultdate);
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
        $criteria = ChildPlayerdivisionmatchesQuery::create();
        $criteria->add(PlayerdivisionmatchesTableMap::COL_PRIMARYKEY, $this->primarykey);

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
        $validPk = null !== $this->getPlayerDivisionMatchPK();

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
        return $this->getPlayerDivisionMatchPK();
    }

    /**
     * Generic method to set the primary key (primarykey column).
     *
     * @param       int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setPlayerDivisionMatchPK($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {
        return null === $this->getPlayerDivisionMatchPK();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of \Playerdivisionmatches (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setPlayerhomekey($this->getPlayerhomekey());
        $copyObj->setPlayerawaykey($this->getPlayerawaykey());
        $copyObj->setSeasonkey($this->getSeasonkey());
        $copyObj->setDivisionkey($this->getDivisionkey());
        $copyObj->setGroupkey($this->getGroupkey());
        $copyObj->setHomescore($this->getHomescore());
        $copyObj->setAwayscore($this->getAwayscore());
        $copyObj->setScheduledate($this->getScheduledate());
        $copyObj->setResultdate($this->getResultdate());
        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setPlayerDivisionMatchPK(NULL); // this is a auto-increment column, so set to default value
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
     * @return \Playerdivisionmatches Clone of current object.
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
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        $this->primarykey = null;
        $this->playerhomekey = null;
        $this->playerawaykey = null;
        $this->seasonkey = null;
        $this->divisionkey = null;
        $this->groupkey = null;
        $this->homescore = null;
        $this->awayscore = null;
        $this->scheduledate = null;
        $this->resultdate = null;
        $this->alreadyInSave = false;
        $this->clearAllReferences();
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
        } // if ($deep)

    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(PlayerdivisionmatchesTableMap::DEFAULT_STRING_FORMAT);
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
