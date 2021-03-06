<?php

namespace Base;

use \Divisions as ChildDivisions;
use \DivisionsQuery as ChildDivisionsQuery;
use \PlayerdivisionrankingQuery as ChildPlayerdivisionrankingQuery;
use \Players as ChildPlayers;
use \PlayersQuery as ChildPlayersQuery;
use \Seasons as ChildSeasons;
use \SeasonsQuery as ChildSeasonsQuery;
use \DateTime;
use \Exception;
use \PDO;
use Map\PlayerdivisionrankingTableMap;
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
 * Base class that represents a row from the 'playerdivisionranking' table.
 *
 *
 *
* @package    propel.generator..Base
*/
abstract class Playerdivisionranking implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\Map\\PlayerdivisionrankingTableMap';


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
     * The value for the playerkey field.
     * @var        int
     */
    protected $playerkey;

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
     * The value for the score field.
     * @var        int
     */
    protected $score;

    /**
     * The value for the rankdate field.
     * Note: this column has a database default value of: (expression) CURRENT_TIMESTAMP
     * @var        \DateTime
     */
    protected $rankdate;

    /**
     * The value for the rank field.
     * @var        int
     */
    protected $rank;

    /**
     * The value for the won field.
     * @var        int
     */
    protected $won;

    /**
     * The value for the drawn field.
     * @var        int
     */
    protected $drawn;

    /**
     * The value for the lost field.
     * @var        int
     */
    protected $lost;

    /**
     * The value for the pointsfor field.
     * @var        int
     */
    protected $pointsfor;

    /**
     * The value for the pointsagainst field.
     * @var        int
     */
    protected $pointsagainst;

    /**
     * The value for the pointsdifference field.
     * @var        int
     */
    protected $pointsdifference;

    /**
     * @var        ChildPlayers
     */
    protected $aDivisionRankingPlayer;

    /**
     * @var        ChildSeasons
     */
    protected $aDivisionRankingSeason;

    /**
     * @var        ChildDivisions
     */
    protected $aDivisionRankingDivision;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see __construct()
     */
    public function applyDefaultValues()
    {
    }

    /**
     * Initializes internal state of Base\Playerdivisionranking object.
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
     * Compares this with another <code>Playerdivisionranking</code> instance.  If
     * <code>obj</code> is an instance of <code>Playerdivisionranking</code>, delegates to
     * <code>equals(Playerdivisionranking)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|Playerdivisionranking The current object, for fluid interface
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
     * Get the [playerkey] column value.
     *
     * @return int
     */
    public function getPlayerkey()
    {
        return $this->playerkey;
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
     * Get the [score] column value.
     *
     * @return int
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * Get the [optionally formatted] temporal [rankdate] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getRankdate($format = NULL)
    {
        if ($format === null) {
            return $this->rankdate;
        } else {
            return $this->rankdate instanceof \DateTime ? $this->rankdate->format($format) : null;
        }
    }

    /**
     * Get the [rank] column value.
     *
     * @return int
     */
    public function getRank()
    {
        return $this->rank;
    }

    /**
     * Get the [won] column value.
     *
     * @return int
     */
    public function getWon()
    {
        return $this->won;
    }

    /**
     * Get the [drawn] column value.
     *
     * @return int
     */
    public function getDrawn()
    {
        return $this->drawn;
    }

    /**
     * Get the [lost] column value.
     *
     * @return int
     */
    public function getLost()
    {
        return $this->lost;
    }

    /**
     * Get the [pointsfor] column value.
     *
     * @return int
     */
    public function getPointsfor()
    {
        return $this->pointsfor;
    }

    /**
     * Get the [pointsagainst] column value.
     *
     * @return int
     */
    public function getPointsagainst()
    {
        return $this->pointsagainst;
    }

    /**
     * Get the [pointsdifference] column value.
     *
     * @return int
     */
    public function getPointsdifference()
    {
        return $this->pointsdifference;
    }

    /**
     * Set the value of [playerkey] column.
     *
     * @param int $v new value
     * @return $this|\Playerdivisionranking The current object (for fluent API support)
     */
    public function setPlayerkey($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->playerkey !== $v) {
            $this->playerkey = $v;
            $this->modifiedColumns[PlayerdivisionrankingTableMap::COL_PLAYERKEY] = true;
        }

        if ($this->aDivisionRankingPlayer !== null && $this->aDivisionRankingPlayer->getPlayerPK() !== $v) {
            $this->aDivisionRankingPlayer = null;
        }

        return $this;
    } // setPlayerkey()

    /**
     * Set the value of [seasonkey] column.
     *
     * @param int $v new value
     * @return $this|\Playerdivisionranking The current object (for fluent API support)
     */
    public function setSeasonkey($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->seasonkey !== $v) {
            $this->seasonkey = $v;
            $this->modifiedColumns[PlayerdivisionrankingTableMap::COL_SEASONKEY] = true;
        }

        if ($this->aDivisionRankingSeason !== null && $this->aDivisionRankingSeason->getSeasonPK() !== $v) {
            $this->aDivisionRankingSeason = null;
        }

        return $this;
    } // setSeasonkey()

    /**
     * Set the value of [divisionkey] column.
     *
     * @param int $v new value
     * @return $this|\Playerdivisionranking The current object (for fluent API support)
     */
    public function setDivisionkey($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->divisionkey !== $v) {
            $this->divisionkey = $v;
            $this->modifiedColumns[PlayerdivisionrankingTableMap::COL_DIVISIONKEY] = true;
        }

        if ($this->aDivisionRankingDivision !== null && $this->aDivisionRankingDivision->getDivisionPK() !== $v) {
            $this->aDivisionRankingDivision = null;
        }

        return $this;
    } // setDivisionkey()

    /**
     * Set the value of [score] column.
     *
     * @param int $v new value
     * @return $this|\Playerdivisionranking The current object (for fluent API support)
     */
    public function setScore($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->score !== $v) {
            $this->score = $v;
            $this->modifiedColumns[PlayerdivisionrankingTableMap::COL_SCORE] = true;
        }

        return $this;
    } // setScore()

    /**
     * Sets the value of [rankdate] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTime value.
     *               Empty strings are treated as NULL.
     * @return $this|\Playerdivisionranking The current object (for fluent API support)
     */
    public function setRankdate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->rankdate !== null || $dt !== null) {
            if ($this->rankdate === null || $dt === null || $dt->format("Y-m-d H:i:s") !== $this->rankdate->format("Y-m-d H:i:s")) {
                $this->rankdate = $dt === null ? null : clone $dt;
                $this->modifiedColumns[PlayerdivisionrankingTableMap::COL_RANKDATE] = true;
            }
        } // if either are not null

        return $this;
    } // setRankdate()

    /**
     * Set the value of [rank] column.
     *
     * @param int $v new value
     * @return $this|\Playerdivisionranking The current object (for fluent API support)
     */
    public function setRank($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->rank !== $v) {
            $this->rank = $v;
            $this->modifiedColumns[PlayerdivisionrankingTableMap::COL_RANK] = true;
        }

        return $this;
    } // setRank()

    /**
     * Set the value of [won] column.
     *
     * @param int $v new value
     * @return $this|\Playerdivisionranking The current object (for fluent API support)
     */
    public function setWon($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->won !== $v) {
            $this->won = $v;
            $this->modifiedColumns[PlayerdivisionrankingTableMap::COL_WON] = true;
        }

        return $this;
    } // setWon()

    /**
     * Set the value of [drawn] column.
     *
     * @param int $v new value
     * @return $this|\Playerdivisionranking The current object (for fluent API support)
     */
    public function setDrawn($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->drawn !== $v) {
            $this->drawn = $v;
            $this->modifiedColumns[PlayerdivisionrankingTableMap::COL_DRAWN] = true;
        }

        return $this;
    } // setDrawn()

    /**
     * Set the value of [lost] column.
     *
     * @param int $v new value
     * @return $this|\Playerdivisionranking The current object (for fluent API support)
     */
    public function setLost($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->lost !== $v) {
            $this->lost = $v;
            $this->modifiedColumns[PlayerdivisionrankingTableMap::COL_LOST] = true;
        }

        return $this;
    } // setLost()

    /**
     * Set the value of [pointsfor] column.
     *
     * @param int $v new value
     * @return $this|\Playerdivisionranking The current object (for fluent API support)
     */
    public function setPointsfor($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->pointsfor !== $v) {
            $this->pointsfor = $v;
            $this->modifiedColumns[PlayerdivisionrankingTableMap::COL_POINTSFOR] = true;
        }

        return $this;
    } // setPointsfor()

    /**
     * Set the value of [pointsagainst] column.
     *
     * @param int $v new value
     * @return $this|\Playerdivisionranking The current object (for fluent API support)
     */
    public function setPointsagainst($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->pointsagainst !== $v) {
            $this->pointsagainst = $v;
            $this->modifiedColumns[PlayerdivisionrankingTableMap::COL_POINTSAGAINST] = true;
        }

        return $this;
    } // setPointsagainst()

    /**
     * Set the value of [pointsdifference] column.
     *
     * @param int $v new value
     * @return $this|\Playerdivisionranking The current object (for fluent API support)
     */
    public function setPointsdifference($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->pointsdifference !== $v) {
            $this->pointsdifference = $v;
            $this->modifiedColumns[PlayerdivisionrankingTableMap::COL_POINTSDIFFERENCE] = true;
        }

        return $this;
    } // setPointsdifference()

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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : PlayerdivisionrankingTableMap::translateFieldName('Playerkey', TableMap::TYPE_PHPNAME, $indexType)];
            $this->playerkey = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : PlayerdivisionrankingTableMap::translateFieldName('Seasonkey', TableMap::TYPE_PHPNAME, $indexType)];
            $this->seasonkey = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : PlayerdivisionrankingTableMap::translateFieldName('Divisionkey', TableMap::TYPE_PHPNAME, $indexType)];
            $this->divisionkey = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : PlayerdivisionrankingTableMap::translateFieldName('Score', TableMap::TYPE_PHPNAME, $indexType)];
            $this->score = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : PlayerdivisionrankingTableMap::translateFieldName('Rankdate', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->rankdate = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : PlayerdivisionrankingTableMap::translateFieldName('Rank', TableMap::TYPE_PHPNAME, $indexType)];
            $this->rank = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : PlayerdivisionrankingTableMap::translateFieldName('Won', TableMap::TYPE_PHPNAME, $indexType)];
            $this->won = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : PlayerdivisionrankingTableMap::translateFieldName('Drawn', TableMap::TYPE_PHPNAME, $indexType)];
            $this->drawn = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : PlayerdivisionrankingTableMap::translateFieldName('Lost', TableMap::TYPE_PHPNAME, $indexType)];
            $this->lost = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 9 + $startcol : PlayerdivisionrankingTableMap::translateFieldName('Pointsfor', TableMap::TYPE_PHPNAME, $indexType)];
            $this->pointsfor = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 10 + $startcol : PlayerdivisionrankingTableMap::translateFieldName('Pointsagainst', TableMap::TYPE_PHPNAME, $indexType)];
            $this->pointsagainst = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 11 + $startcol : PlayerdivisionrankingTableMap::translateFieldName('Pointsdifference', TableMap::TYPE_PHPNAME, $indexType)];
            $this->pointsdifference = (null !== $col) ? (int) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 12; // 12 = PlayerdivisionrankingTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Playerdivisionranking'), 0, $e);
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
        if ($this->aDivisionRankingPlayer !== null && $this->playerkey !== $this->aDivisionRankingPlayer->getPlayerPK()) {
            $this->aDivisionRankingPlayer = null;
        }
        if ($this->aDivisionRankingSeason !== null && $this->seasonkey !== $this->aDivisionRankingSeason->getSeasonPK()) {
            $this->aDivisionRankingSeason = null;
        }
        if ($this->aDivisionRankingDivision !== null && $this->divisionkey !== $this->aDivisionRankingDivision->getDivisionPK()) {
            $this->aDivisionRankingDivision = null;
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
            $con = Propel::getServiceContainer()->getReadConnection(PlayerdivisionrankingTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildPlayerdivisionrankingQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aDivisionRankingPlayer = null;
            $this->aDivisionRankingSeason = null;
            $this->aDivisionRankingDivision = null;
        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Playerdivisionranking::setDeleted()
     * @see Playerdivisionranking::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(PlayerdivisionrankingTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildPlayerdivisionrankingQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(PlayerdivisionrankingTableMap::DATABASE_NAME);
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
                PlayerdivisionrankingTableMap::addInstanceToPool($this);
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

            if ($this->aDivisionRankingPlayer !== null) {
                if ($this->aDivisionRankingPlayer->isModified() || $this->aDivisionRankingPlayer->isNew()) {
                    $affectedRows += $this->aDivisionRankingPlayer->save($con);
                }
                $this->setDivisionRankingPlayer($this->aDivisionRankingPlayer);
            }

            if ($this->aDivisionRankingSeason !== null) {
                if ($this->aDivisionRankingSeason->isModified() || $this->aDivisionRankingSeason->isNew()) {
                    $affectedRows += $this->aDivisionRankingSeason->save($con);
                }
                $this->setDivisionRankingSeason($this->aDivisionRankingSeason);
            }

            if ($this->aDivisionRankingDivision !== null) {
                if ($this->aDivisionRankingDivision->isModified() || $this->aDivisionRankingDivision->isNew()) {
                    $affectedRows += $this->aDivisionRankingDivision->save($con);
                }
                $this->setDivisionRankingDivision($this->aDivisionRankingDivision);
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


         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(PlayerdivisionrankingTableMap::COL_PLAYERKEY)) {
            $modifiedColumns[':p' . $index++]  = 'PlayerKey';
        }
        if ($this->isColumnModified(PlayerdivisionrankingTableMap::COL_SEASONKEY)) {
            $modifiedColumns[':p' . $index++]  = 'SeasonKey';
        }
        if ($this->isColumnModified(PlayerdivisionrankingTableMap::COL_DIVISIONKEY)) {
            $modifiedColumns[':p' . $index++]  = 'DivisionKey';
        }
        if ($this->isColumnModified(PlayerdivisionrankingTableMap::COL_SCORE)) {
            $modifiedColumns[':p' . $index++]  = 'Score';
        }
        if ($this->isColumnModified(PlayerdivisionrankingTableMap::COL_RANKDATE)) {
            $modifiedColumns[':p' . $index++]  = 'RankDate';
        }
        if ($this->isColumnModified(PlayerdivisionrankingTableMap::COL_RANK)) {
            $modifiedColumns[':p' . $index++]  = 'Rank';
        }
        if ($this->isColumnModified(PlayerdivisionrankingTableMap::COL_WON)) {
            $modifiedColumns[':p' . $index++]  = 'Won';
        }
        if ($this->isColumnModified(PlayerdivisionrankingTableMap::COL_DRAWN)) {
            $modifiedColumns[':p' . $index++]  = 'Drawn';
        }
        if ($this->isColumnModified(PlayerdivisionrankingTableMap::COL_LOST)) {
            $modifiedColumns[':p' . $index++]  = 'Lost';
        }
        if ($this->isColumnModified(PlayerdivisionrankingTableMap::COL_POINTSFOR)) {
            $modifiedColumns[':p' . $index++]  = 'PointsFor';
        }
        if ($this->isColumnModified(PlayerdivisionrankingTableMap::COL_POINTSAGAINST)) {
            $modifiedColumns[':p' . $index++]  = 'PointsAgainst';
        }
        if ($this->isColumnModified(PlayerdivisionrankingTableMap::COL_POINTSDIFFERENCE)) {
            $modifiedColumns[':p' . $index++]  = 'PointsDifference';
        }

        $sql = sprintf(
            'INSERT INTO playerdivisionranking (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'PlayerKey':
                        $stmt->bindValue($identifier, $this->playerkey, PDO::PARAM_INT);
                        break;
                    case 'SeasonKey':
                        $stmt->bindValue($identifier, $this->seasonkey, PDO::PARAM_INT);
                        break;
                    case 'DivisionKey':
                        $stmt->bindValue($identifier, $this->divisionkey, PDO::PARAM_INT);
                        break;
                    case 'Score':
                        $stmt->bindValue($identifier, $this->score, PDO::PARAM_INT);
                        break;
                    case 'RankDate':
                        $stmt->bindValue($identifier, $this->rankdate ? $this->rankdate->format("Y-m-d H:i:s") : null, PDO::PARAM_STR);
                        break;
                    case 'Rank':
                        $stmt->bindValue($identifier, $this->rank, PDO::PARAM_INT);
                        break;
                    case 'Won':
                        $stmt->bindValue($identifier, $this->won, PDO::PARAM_INT);
                        break;
                    case 'Drawn':
                        $stmt->bindValue($identifier, $this->drawn, PDO::PARAM_INT);
                        break;
                    case 'Lost':
                        $stmt->bindValue($identifier, $this->lost, PDO::PARAM_INT);
                        break;
                    case 'PointsFor':
                        $stmt->bindValue($identifier, $this->pointsfor, PDO::PARAM_INT);
                        break;
                    case 'PointsAgainst':
                        $stmt->bindValue($identifier, $this->pointsagainst, PDO::PARAM_INT);
                        break;
                    case 'PointsDifference':
                        $stmt->bindValue($identifier, $this->pointsdifference, PDO::PARAM_INT);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), 0, $e);
        }

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
        $pos = PlayerdivisionrankingTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getPlayerkey();
                break;
            case 1:
                return $this->getSeasonkey();
                break;
            case 2:
                return $this->getDivisionkey();
                break;
            case 3:
                return $this->getScore();
                break;
            case 4:
                return $this->getRankdate();
                break;
            case 5:
                return $this->getRank();
                break;
            case 6:
                return $this->getWon();
                break;
            case 7:
                return $this->getDrawn();
                break;
            case 8:
                return $this->getLost();
                break;
            case 9:
                return $this->getPointsfor();
                break;
            case 10:
                return $this->getPointsagainst();
                break;
            case 11:
                return $this->getPointsdifference();
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

        if (isset($alreadyDumpedObjects['Playerdivisionranking'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Playerdivisionranking'][$this->hashCode()] = true;
        $keys = PlayerdivisionrankingTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getPlayerkey(),
            $keys[1] => $this->getSeasonkey(),
            $keys[2] => $this->getDivisionkey(),
            $keys[3] => $this->getScore(),
            $keys[4] => $this->getRankdate(),
            $keys[5] => $this->getRank(),
            $keys[6] => $this->getWon(),
            $keys[7] => $this->getDrawn(),
            $keys[8] => $this->getLost(),
            $keys[9] => $this->getPointsfor(),
            $keys[10] => $this->getPointsagainst(),
            $keys[11] => $this->getPointsdifference(),
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
            if (null !== $this->aDivisionRankingPlayer) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'players';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'players';
                        break;
                    default:
                        $key = 'Players';
                }

                $result[$key] = $this->aDivisionRankingPlayer->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aDivisionRankingSeason) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'seasons';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'seasons';
                        break;
                    default:
                        $key = 'Seasons';
                }

                $result[$key] = $this->aDivisionRankingSeason->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aDivisionRankingDivision) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'divisions';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'divisions';
                        break;
                    default:
                        $key = 'Divisions';
                }

                $result[$key] = $this->aDivisionRankingDivision->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
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
     * @return $this|\Playerdivisionranking
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = PlayerdivisionrankingTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\Playerdivisionranking
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setPlayerkey($value);
                break;
            case 1:
                $this->setSeasonkey($value);
                break;
            case 2:
                $this->setDivisionkey($value);
                break;
            case 3:
                $this->setScore($value);
                break;
            case 4:
                $this->setRankdate($value);
                break;
            case 5:
                $this->setRank($value);
                break;
            case 6:
                $this->setWon($value);
                break;
            case 7:
                $this->setDrawn($value);
                break;
            case 8:
                $this->setLost($value);
                break;
            case 9:
                $this->setPointsfor($value);
                break;
            case 10:
                $this->setPointsagainst($value);
                break;
            case 11:
                $this->setPointsdifference($value);
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
        $keys = PlayerdivisionrankingTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setPlayerkey($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setSeasonkey($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setDivisionkey($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setScore($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setRankdate($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setRank($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setWon($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setDrawn($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->setLost($arr[$keys[8]]);
        }
        if (array_key_exists($keys[9], $arr)) {
            $this->setPointsfor($arr[$keys[9]]);
        }
        if (array_key_exists($keys[10], $arr)) {
            $this->setPointsagainst($arr[$keys[10]]);
        }
        if (array_key_exists($keys[11], $arr)) {
            $this->setPointsdifference($arr[$keys[11]]);
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
     * @return $this|\Playerdivisionranking The current object, for fluid interface
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
        $criteria = new Criteria(PlayerdivisionrankingTableMap::DATABASE_NAME);

        if ($this->isColumnModified(PlayerdivisionrankingTableMap::COL_PLAYERKEY)) {
            $criteria->add(PlayerdivisionrankingTableMap::COL_PLAYERKEY, $this->playerkey);
        }
        if ($this->isColumnModified(PlayerdivisionrankingTableMap::COL_SEASONKEY)) {
            $criteria->add(PlayerdivisionrankingTableMap::COL_SEASONKEY, $this->seasonkey);
        }
        if ($this->isColumnModified(PlayerdivisionrankingTableMap::COL_DIVISIONKEY)) {
            $criteria->add(PlayerdivisionrankingTableMap::COL_DIVISIONKEY, $this->divisionkey);
        }
        if ($this->isColumnModified(PlayerdivisionrankingTableMap::COL_SCORE)) {
            $criteria->add(PlayerdivisionrankingTableMap::COL_SCORE, $this->score);
        }
        if ($this->isColumnModified(PlayerdivisionrankingTableMap::COL_RANKDATE)) {
            $criteria->add(PlayerdivisionrankingTableMap::COL_RANKDATE, $this->rankdate);
        }
        if ($this->isColumnModified(PlayerdivisionrankingTableMap::COL_RANK)) {
            $criteria->add(PlayerdivisionrankingTableMap::COL_RANK, $this->rank);
        }
        if ($this->isColumnModified(PlayerdivisionrankingTableMap::COL_WON)) {
            $criteria->add(PlayerdivisionrankingTableMap::COL_WON, $this->won);
        }
        if ($this->isColumnModified(PlayerdivisionrankingTableMap::COL_DRAWN)) {
            $criteria->add(PlayerdivisionrankingTableMap::COL_DRAWN, $this->drawn);
        }
        if ($this->isColumnModified(PlayerdivisionrankingTableMap::COL_LOST)) {
            $criteria->add(PlayerdivisionrankingTableMap::COL_LOST, $this->lost);
        }
        if ($this->isColumnModified(PlayerdivisionrankingTableMap::COL_POINTSFOR)) {
            $criteria->add(PlayerdivisionrankingTableMap::COL_POINTSFOR, $this->pointsfor);
        }
        if ($this->isColumnModified(PlayerdivisionrankingTableMap::COL_POINTSAGAINST)) {
            $criteria->add(PlayerdivisionrankingTableMap::COL_POINTSAGAINST, $this->pointsagainst);
        }
        if ($this->isColumnModified(PlayerdivisionrankingTableMap::COL_POINTSDIFFERENCE)) {
            $criteria->add(PlayerdivisionrankingTableMap::COL_POINTSDIFFERENCE, $this->pointsdifference);
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
        $criteria = ChildPlayerdivisionrankingQuery::create();
        $criteria->add(PlayerdivisionrankingTableMap::COL_PLAYERKEY, $this->playerkey);
        $criteria->add(PlayerdivisionrankingTableMap::COL_SEASONKEY, $this->seasonkey);
        $criteria->add(PlayerdivisionrankingTableMap::COL_DIVISIONKEY, $this->divisionkey);
        $criteria->add(PlayerdivisionrankingTableMap::COL_RANKDATE, $this->rankdate);

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
        $validPk = null !== $this->getPlayerkey() &&
            null !== $this->getSeasonkey() &&
            null !== $this->getDivisionkey() &&
            null !== $this->getRankdate();

        $validPrimaryKeyFKs = 3;
        $primaryKeyFKs = [];

        //relation playerdivisionranking_fk_d784ed to table players
        if ($this->aDivisionRankingPlayer && $hash = spl_object_hash($this->aDivisionRankingPlayer)) {
            $primaryKeyFKs[] = $hash;
        } else {
            $validPrimaryKeyFKs = false;
        }

        //relation playerdivisionranking_fk_1a4951 to table seasons
        if ($this->aDivisionRankingSeason && $hash = spl_object_hash($this->aDivisionRankingSeason)) {
            $primaryKeyFKs[] = $hash;
        } else {
            $validPrimaryKeyFKs = false;
        }

        //relation playerdivisionranking_fk_5dff2d to table divisions
        if ($this->aDivisionRankingDivision && $hash = spl_object_hash($this->aDivisionRankingDivision)) {
            $primaryKeyFKs[] = $hash;
        } else {
            $validPrimaryKeyFKs = false;
        }

        if ($validPk) {
            return crc32(json_encode($this->getPrimaryKey(), JSON_UNESCAPED_UNICODE));
        } elseif ($validPrimaryKeyFKs) {
            return crc32(json_encode($primaryKeyFKs, JSON_UNESCAPED_UNICODE));
        }

        return spl_object_hash($this);
    }

    /**
     * Returns the composite primary key for this object.
     * The array elements will be in same order as specified in XML.
     * @return array
     */
    public function getPrimaryKey()
    {
        $pks = array();
        $pks[0] = $this->getPlayerkey();
        $pks[1] = $this->getSeasonkey();
        $pks[2] = $this->getDivisionkey();
        $pks[3] = $this->getRankdate();

        return $pks;
    }

    /**
     * Set the [composite] primary key.
     *
     * @param      array $keys The elements of the composite key (order must match the order in XML file).
     * @return void
     */
    public function setPrimaryKey($keys)
    {
        $this->setPlayerkey($keys[0]);
        $this->setSeasonkey($keys[1]);
        $this->setDivisionkey($keys[2]);
        $this->setRankdate($keys[3]);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {
        return (null === $this->getPlayerkey()) && (null === $this->getSeasonkey()) && (null === $this->getDivisionkey()) && (null === $this->getRankdate());
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of \Playerdivisionranking (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setPlayerkey($this->getPlayerkey());
        $copyObj->setSeasonkey($this->getSeasonkey());
        $copyObj->setDivisionkey($this->getDivisionkey());
        $copyObj->setScore($this->getScore());
        $copyObj->setRankdate($this->getRankdate());
        $copyObj->setRank($this->getRank());
        $copyObj->setWon($this->getWon());
        $copyObj->setDrawn($this->getDrawn());
        $copyObj->setLost($this->getLost());
        $copyObj->setPointsfor($this->getPointsfor());
        $copyObj->setPointsagainst($this->getPointsagainst());
        $copyObj->setPointsdifference($this->getPointsdifference());
        if ($makeNew) {
            $copyObj->setNew(true);
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
     * @return \Playerdivisionranking Clone of current object.
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
     * Declares an association between this object and a ChildPlayers object.
     *
     * @param  ChildPlayers $v
     * @return $this|\Playerdivisionranking The current object (for fluent API support)
     * @throws PropelException
     */
    public function setDivisionRankingPlayer(ChildPlayers $v = null)
    {
        if ($v === null) {
            $this->setPlayerkey(NULL);
        } else {
            $this->setPlayerkey($v->getPlayerPK());
        }

        $this->aDivisionRankingPlayer = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildPlayers object, it will not be re-added.
        if ($v !== null) {
            $v->addPlayerdivisionranking($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildPlayers object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildPlayers The associated ChildPlayers object.
     * @throws PropelException
     */
    public function getDivisionRankingPlayer(ConnectionInterface $con = null)
    {
        if ($this->aDivisionRankingPlayer === null && ($this->playerkey !== null)) {
            $this->aDivisionRankingPlayer = ChildPlayersQuery::create()->findPk($this->playerkey, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aDivisionRankingPlayer->addPlayerdivisionrankings($this);
             */
        }

        return $this->aDivisionRankingPlayer;
    }

    /**
     * Declares an association between this object and a ChildSeasons object.
     *
     * @param  ChildSeasons $v
     * @return $this|\Playerdivisionranking The current object (for fluent API support)
     * @throws PropelException
     */
    public function setDivisionRankingSeason(ChildSeasons $v = null)
    {
        if ($v === null) {
            $this->setSeasonkey(NULL);
        } else {
            $this->setSeasonkey($v->getSeasonPK());
        }

        $this->aDivisionRankingSeason = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildSeasons object, it will not be re-added.
        if ($v !== null) {
            $v->addPlayerdivisionranking($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildSeasons object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildSeasons The associated ChildSeasons object.
     * @throws PropelException
     */
    public function getDivisionRankingSeason(ConnectionInterface $con = null)
    {
        if ($this->aDivisionRankingSeason === null && ($this->seasonkey !== null)) {
            $this->aDivisionRankingSeason = ChildSeasonsQuery::create()->findPk($this->seasonkey, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aDivisionRankingSeason->addPlayerdivisionrankings($this);
             */
        }

        return $this->aDivisionRankingSeason;
    }

    /**
     * Declares an association between this object and a ChildDivisions object.
     *
     * @param  ChildDivisions $v
     * @return $this|\Playerdivisionranking The current object (for fluent API support)
     * @throws PropelException
     */
    public function setDivisionRankingDivision(ChildDivisions $v = null)
    {
        if ($v === null) {
            $this->setDivisionkey(NULL);
        } else {
            $this->setDivisionkey($v->getDivisionPK());
        }

        $this->aDivisionRankingDivision = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildDivisions object, it will not be re-added.
        if ($v !== null) {
            $v->addPlayerdivisionranking($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildDivisions object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildDivisions The associated ChildDivisions object.
     * @throws PropelException
     */
    public function getDivisionRankingDivision(ConnectionInterface $con = null)
    {
        if ($this->aDivisionRankingDivision === null && ($this->divisionkey !== null)) {
            $this->aDivisionRankingDivision = ChildDivisionsQuery::create()->findPk($this->divisionkey, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aDivisionRankingDivision->addPlayerdivisionrankings($this);
             */
        }

        return $this->aDivisionRankingDivision;
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        if (null !== $this->aDivisionRankingPlayer) {
            $this->aDivisionRankingPlayer->removePlayerdivisionranking($this);
        }
        if (null !== $this->aDivisionRankingSeason) {
            $this->aDivisionRankingSeason->removePlayerdivisionranking($this);
        }
        if (null !== $this->aDivisionRankingDivision) {
            $this->aDivisionRankingDivision->removePlayerdivisionranking($this);
        }
        $this->playerkey = null;
        $this->seasonkey = null;
        $this->divisionkey = null;
        $this->score = null;
        $this->rankdate = null;
        $this->rank = null;
        $this->won = null;
        $this->drawn = null;
        $this->lost = null;
        $this->pointsfor = null;
        $this->pointsagainst = null;
        $this->pointsdifference = null;
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
        } // if ($deep)

        $this->aDivisionRankingPlayer = null;
        $this->aDivisionRankingSeason = null;
        $this->aDivisionRankingDivision = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(PlayerdivisionrankingTableMap::DEFAULT_STRING_FORMAT);
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
