<?php

namespace Base;

use \SurveysQuery as ChildSurveysQuery;
use \DateTime;
use \Exception;
use \PDO;
use Map\SurveysTableMap;
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
 * Base class that represents a row from the 'surveys' table.
 *
 *
 *
* @package    propel.generator..Base
*/
abstract class Surveys implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\Map\\SurveysTableMap';


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
     * The value for the question field.
     * @var        string
     */
    protected $question;

    /**
     * The value for the answer1 field.
     * @var        string
     */
    protected $answer1;

    /**
     * The value for the answer2 field.
     * @var        string
     */
    protected $answer2;

    /**
     * The value for the answer3 field.
     * @var        string
     */
    protected $answer3;

    /**
     * The value for the answer4 field.
     * @var        string
     */
    protected $answer4;

    /**
     * The value for the score1 field.
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $score1;

    /**
     * The value for the score2 field.
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $score2;

    /**
     * The value for the score3 field.
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $score3;

    /**
     * The value for the score4 field.
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $score4;

    /**
     * The value for the participants field.
     * @var        string
     */
    protected $participants;

    /**
     * The value for the startdate field.
     * Note: this column has a database default value of: (expression) CURRENT_TIMESTAMP
     * @var        \DateTime
     */
    protected $startdate;

    /**
     * The value for the enddate field.
     * Note: this column has a database default value of: NULL
     * @var        \DateTime
     */
    protected $enddate;

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
        $this->score1 = 0;
        $this->score2 = 0;
        $this->score3 = 0;
        $this->score4 = 0;
        $this->enddate = PropelDateTime::newInstance(NULL, null, 'DateTime');
    }

    /**
     * Initializes internal state of Base\Surveys object.
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
     * Compares this with another <code>Surveys</code> instance.  If
     * <code>obj</code> is an instance of <code>Surveys</code>, delegates to
     * <code>equals(Surveys)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|Surveys The current object, for fluid interface
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
    public function getSurveyPK()
    {
        return $this->primarykey;
    }

    /**
     * Get the [question] column value.
     *
     * @return string
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * Get the [answer1] column value.
     *
     * @return string
     */
    public function getAnswer1()
    {
        return $this->answer1;
    }

    /**
     * Get the [answer2] column value.
     *
     * @return string
     */
    public function getAnswer2()
    {
        return $this->answer2;
    }

    /**
     * Get the [answer3] column value.
     *
     * @return string
     */
    public function getAnswer3()
    {
        return $this->answer3;
    }

    /**
     * Get the [answer4] column value.
     *
     * @return string
     */
    public function getAnswer4()
    {
        return $this->answer4;
    }

    /**
     * Get the [score1] column value.
     *
     * @return int
     */
    public function getScore1()
    {
        return $this->score1;
    }

    /**
     * Get the [score2] column value.
     *
     * @return int
     */
    public function getScore2()
    {
        return $this->score2;
    }

    /**
     * Get the [score3] column value.
     *
     * @return int
     */
    public function getScore3()
    {
        return $this->score3;
    }

    /**
     * Get the [score4] column value.
     *
     * @return int
     */
    public function getScore4()
    {
        return $this->score4;
    }

    /**
     * Get the [participants] column value.
     *
     * @return string
     */
    public function getParticipants()
    {
        return $this->participants;
    }

    /**
     * Get the [optionally formatted] temporal [startdate] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getStartdate($format = NULL)
    {
        if ($format === null) {
            return $this->startdate;
        } else {
            return $this->startdate instanceof \DateTime ? $this->startdate->format($format) : null;
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
     * Set the value of [primarykey] column.
     *
     * @param int $v new value
     * @return $this|\Surveys The current object (for fluent API support)
     */
    public function setSurveyPK($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->primarykey !== $v) {
            $this->primarykey = $v;
            $this->modifiedColumns[SurveysTableMap::COL_PRIMARYKEY] = true;
        }

        return $this;
    } // setSurveyPK()

    /**
     * Set the value of [question] column.
     *
     * @param string $v new value
     * @return $this|\Surveys The current object (for fluent API support)
     */
    public function setQuestion($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->question !== $v) {
            $this->question = $v;
            $this->modifiedColumns[SurveysTableMap::COL_QUESTION] = true;
        }

        return $this;
    } // setQuestion()

    /**
     * Set the value of [answer1] column.
     *
     * @param string $v new value
     * @return $this|\Surveys The current object (for fluent API support)
     */
    public function setAnswer1($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->answer1 !== $v) {
            $this->answer1 = $v;
            $this->modifiedColumns[SurveysTableMap::COL_ANSWER1] = true;
        }

        return $this;
    } // setAnswer1()

    /**
     * Set the value of [answer2] column.
     *
     * @param string $v new value
     * @return $this|\Surveys The current object (for fluent API support)
     */
    public function setAnswer2($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->answer2 !== $v) {
            $this->answer2 = $v;
            $this->modifiedColumns[SurveysTableMap::COL_ANSWER2] = true;
        }

        return $this;
    } // setAnswer2()

    /**
     * Set the value of [answer3] column.
     *
     * @param string $v new value
     * @return $this|\Surveys The current object (for fluent API support)
     */
    public function setAnswer3($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->answer3 !== $v) {
            $this->answer3 = $v;
            $this->modifiedColumns[SurveysTableMap::COL_ANSWER3] = true;
        }

        return $this;
    } // setAnswer3()

    /**
     * Set the value of [answer4] column.
     *
     * @param string $v new value
     * @return $this|\Surveys The current object (for fluent API support)
     */
    public function setAnswer4($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->answer4 !== $v) {
            $this->answer4 = $v;
            $this->modifiedColumns[SurveysTableMap::COL_ANSWER4] = true;
        }

        return $this;
    } // setAnswer4()

    /**
     * Set the value of [score1] column.
     *
     * @param int $v new value
     * @return $this|\Surveys The current object (for fluent API support)
     */
    public function setScore1($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->score1 !== $v) {
            $this->score1 = $v;
            $this->modifiedColumns[SurveysTableMap::COL_SCORE1] = true;
        }

        return $this;
    } // setScore1()

    /**
     * Set the value of [score2] column.
     *
     * @param int $v new value
     * @return $this|\Surveys The current object (for fluent API support)
     */
    public function setScore2($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->score2 !== $v) {
            $this->score2 = $v;
            $this->modifiedColumns[SurveysTableMap::COL_SCORE2] = true;
        }

        return $this;
    } // setScore2()

    /**
     * Set the value of [score3] column.
     *
     * @param int $v new value
     * @return $this|\Surveys The current object (for fluent API support)
     */
    public function setScore3($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->score3 !== $v) {
            $this->score3 = $v;
            $this->modifiedColumns[SurveysTableMap::COL_SCORE3] = true;
        }

        return $this;
    } // setScore3()

    /**
     * Set the value of [score4] column.
     *
     * @param int $v new value
     * @return $this|\Surveys The current object (for fluent API support)
     */
    public function setScore4($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->score4 !== $v) {
            $this->score4 = $v;
            $this->modifiedColumns[SurveysTableMap::COL_SCORE4] = true;
        }

        return $this;
    } // setScore4()

    /**
     * Set the value of [participants] column.
     *
     * @param string $v new value
     * @return $this|\Surveys The current object (for fluent API support)
     */
    public function setParticipants($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->participants !== $v) {
            $this->participants = $v;
            $this->modifiedColumns[SurveysTableMap::COL_PARTICIPANTS] = true;
        }

        return $this;
    } // setParticipants()

    /**
     * Sets the value of [startdate] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTime value.
     *               Empty strings are treated as NULL.
     * @return $this|\Surveys The current object (for fluent API support)
     */
    public function setStartdate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->startdate !== null || $dt !== null) {
            if ($this->startdate === null || $dt === null || $dt->format("Y-m-d H:i:s") !== $this->startdate->format("Y-m-d H:i:s")) {
                $this->startdate = $dt === null ? null : clone $dt;
                $this->modifiedColumns[SurveysTableMap::COL_STARTDATE] = true;
            }
        } // if either are not null

        return $this;
    } // setStartdate()

    /**
     * Sets the value of [enddate] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTime value.
     *               Empty strings are treated as NULL.
     * @return $this|\Surveys The current object (for fluent API support)
     */
    public function setEnddate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->enddate !== null || $dt !== null) {
            if ( ($dt != $this->enddate) // normalized values don't match
                || ($dt->format('Y-m-d H:i:s') === NULL) // or the entered value matches the default
                 ) {
                $this->enddate = $dt === null ? null : clone $dt;
                $this->modifiedColumns[SurveysTableMap::COL_ENDDATE] = true;
            }
        } // if either are not null

        return $this;
    } // setEnddate()

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
            if ($this->score1 !== 0) {
                return false;
            }

            if ($this->score2 !== 0) {
                return false;
            }

            if ($this->score3 !== 0) {
                return false;
            }

            if ($this->score4 !== 0) {
                return false;
            }

            if ($this->enddate && $this->enddate->format('Y-m-d H:i:s') !== NULL) {
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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : SurveysTableMap::translateFieldName('SurveyPK', TableMap::TYPE_PHPNAME, $indexType)];
            $this->primarykey = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : SurveysTableMap::translateFieldName('Question', TableMap::TYPE_PHPNAME, $indexType)];
            $this->question = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : SurveysTableMap::translateFieldName('Answer1', TableMap::TYPE_PHPNAME, $indexType)];
            $this->answer1 = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : SurveysTableMap::translateFieldName('Answer2', TableMap::TYPE_PHPNAME, $indexType)];
            $this->answer2 = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : SurveysTableMap::translateFieldName('Answer3', TableMap::TYPE_PHPNAME, $indexType)];
            $this->answer3 = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : SurveysTableMap::translateFieldName('Answer4', TableMap::TYPE_PHPNAME, $indexType)];
            $this->answer4 = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : SurveysTableMap::translateFieldName('Score1', TableMap::TYPE_PHPNAME, $indexType)];
            $this->score1 = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : SurveysTableMap::translateFieldName('Score2', TableMap::TYPE_PHPNAME, $indexType)];
            $this->score2 = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : SurveysTableMap::translateFieldName('Score3', TableMap::TYPE_PHPNAME, $indexType)];
            $this->score3 = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 9 + $startcol : SurveysTableMap::translateFieldName('Score4', TableMap::TYPE_PHPNAME, $indexType)];
            $this->score4 = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 10 + $startcol : SurveysTableMap::translateFieldName('Participants', TableMap::TYPE_PHPNAME, $indexType)];
            $this->participants = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 11 + $startcol : SurveysTableMap::translateFieldName('Startdate', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->startdate = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 12 + $startcol : SurveysTableMap::translateFieldName('Enddate', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->enddate = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 13; // 13 = SurveysTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Surveys'), 0, $e);
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
            $con = Propel::getServiceContainer()->getReadConnection(SurveysTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildSurveysQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
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
     * @see Surveys::setDeleted()
     * @see Surveys::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(SurveysTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildSurveysQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(SurveysTableMap::DATABASE_NAME);
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
                SurveysTableMap::addInstanceToPool($this);
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

        $this->modifiedColumns[SurveysTableMap::COL_PRIMARYKEY] = true;
        if (null !== $this->primarykey) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . SurveysTableMap::COL_PRIMARYKEY . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(SurveysTableMap::COL_PRIMARYKEY)) {
            $modifiedColumns[':p' . $index++]  = 'PrimaryKey';
        }
        if ($this->isColumnModified(SurveysTableMap::COL_QUESTION)) {
            $modifiedColumns[':p' . $index++]  = 'Question';
        }
        if ($this->isColumnModified(SurveysTableMap::COL_ANSWER1)) {
            $modifiedColumns[':p' . $index++]  = 'Answer1';
        }
        if ($this->isColumnModified(SurveysTableMap::COL_ANSWER2)) {
            $modifiedColumns[':p' . $index++]  = 'Answer2';
        }
        if ($this->isColumnModified(SurveysTableMap::COL_ANSWER3)) {
            $modifiedColumns[':p' . $index++]  = 'Answer3';
        }
        if ($this->isColumnModified(SurveysTableMap::COL_ANSWER4)) {
            $modifiedColumns[':p' . $index++]  = 'Answer4';
        }
        if ($this->isColumnModified(SurveysTableMap::COL_SCORE1)) {
            $modifiedColumns[':p' . $index++]  = 'Score1';
        }
        if ($this->isColumnModified(SurveysTableMap::COL_SCORE2)) {
            $modifiedColumns[':p' . $index++]  = 'Score2';
        }
        if ($this->isColumnModified(SurveysTableMap::COL_SCORE3)) {
            $modifiedColumns[':p' . $index++]  = 'Score3';
        }
        if ($this->isColumnModified(SurveysTableMap::COL_SCORE4)) {
            $modifiedColumns[':p' . $index++]  = 'Score4';
        }
        if ($this->isColumnModified(SurveysTableMap::COL_PARTICIPANTS)) {
            $modifiedColumns[':p' . $index++]  = 'Participants';
        }
        if ($this->isColumnModified(SurveysTableMap::COL_STARTDATE)) {
            $modifiedColumns[':p' . $index++]  = 'StartDate';
        }
        if ($this->isColumnModified(SurveysTableMap::COL_ENDDATE)) {
            $modifiedColumns[':p' . $index++]  = 'EndDate';
        }

        $sql = sprintf(
            'INSERT INTO surveys (%s) VALUES (%s)',
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
                    case 'Question':
                        $stmt->bindValue($identifier, $this->question, PDO::PARAM_STR);
                        break;
                    case 'Answer1':
                        $stmt->bindValue($identifier, $this->answer1, PDO::PARAM_STR);
                        break;
                    case 'Answer2':
                        $stmt->bindValue($identifier, $this->answer2, PDO::PARAM_STR);
                        break;
                    case 'Answer3':
                        $stmt->bindValue($identifier, $this->answer3, PDO::PARAM_STR);
                        break;
                    case 'Answer4':
                        $stmt->bindValue($identifier, $this->answer4, PDO::PARAM_STR);
                        break;
                    case 'Score1':
                        $stmt->bindValue($identifier, $this->score1, PDO::PARAM_INT);
                        break;
                    case 'Score2':
                        $stmt->bindValue($identifier, $this->score2, PDO::PARAM_INT);
                        break;
                    case 'Score3':
                        $stmt->bindValue($identifier, $this->score3, PDO::PARAM_INT);
                        break;
                    case 'Score4':
                        $stmt->bindValue($identifier, $this->score4, PDO::PARAM_INT);
                        break;
                    case 'Participants':
                        $stmt->bindValue($identifier, $this->participants, PDO::PARAM_STR);
                        break;
                    case 'StartDate':
                        $stmt->bindValue($identifier, $this->startdate ? $this->startdate->format("Y-m-d H:i:s") : null, PDO::PARAM_STR);
                        break;
                    case 'EndDate':
                        $stmt->bindValue($identifier, $this->enddate ? $this->enddate->format("Y-m-d H:i:s") : null, PDO::PARAM_STR);
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
        $this->setSurveyPK($pk);

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
        $pos = SurveysTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getSurveyPK();
                break;
            case 1:
                return $this->getQuestion();
                break;
            case 2:
                return $this->getAnswer1();
                break;
            case 3:
                return $this->getAnswer2();
                break;
            case 4:
                return $this->getAnswer3();
                break;
            case 5:
                return $this->getAnswer4();
                break;
            case 6:
                return $this->getScore1();
                break;
            case 7:
                return $this->getScore2();
                break;
            case 8:
                return $this->getScore3();
                break;
            case 9:
                return $this->getScore4();
                break;
            case 10:
                return $this->getParticipants();
                break;
            case 11:
                return $this->getStartdate();
                break;
            case 12:
                return $this->getEnddate();
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

        if (isset($alreadyDumpedObjects['Surveys'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Surveys'][$this->hashCode()] = true;
        $keys = SurveysTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getSurveyPK(),
            $keys[1] => $this->getQuestion(),
            $keys[2] => $this->getAnswer1(),
            $keys[3] => $this->getAnswer2(),
            $keys[4] => $this->getAnswer3(),
            $keys[5] => $this->getAnswer4(),
            $keys[6] => $this->getScore1(),
            $keys[7] => $this->getScore2(),
            $keys[8] => $this->getScore3(),
            $keys[9] => $this->getScore4(),
            $keys[10] => $this->getParticipants(),
            $keys[11] => $this->getStartdate(),
            $keys[12] => $this->getEnddate(),
        );

        $utc = new \DateTimeZone('utc');
        if ($result[$keys[11]] instanceof \DateTime) {
            // When changing timezone we don't want to change existing instances
            $dateTime = clone $result[$keys[11]];
            $result[$keys[11]] = $dateTime->setTimezone($utc)->format('Y-m-d\TH:i:s\Z');
        }

        if ($result[$keys[12]] instanceof \DateTime) {
            // When changing timezone we don't want to change existing instances
            $dateTime = clone $result[$keys[12]];
            $result[$keys[12]] = $dateTime->setTimezone($utc)->format('Y-m-d\TH:i:s\Z');
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
     * @return $this|\Surveys
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = SurveysTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\Surveys
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setSurveyPK($value);
                break;
            case 1:
                $this->setQuestion($value);
                break;
            case 2:
                $this->setAnswer1($value);
                break;
            case 3:
                $this->setAnswer2($value);
                break;
            case 4:
                $this->setAnswer3($value);
                break;
            case 5:
                $this->setAnswer4($value);
                break;
            case 6:
                $this->setScore1($value);
                break;
            case 7:
                $this->setScore2($value);
                break;
            case 8:
                $this->setScore3($value);
                break;
            case 9:
                $this->setScore4($value);
                break;
            case 10:
                $this->setParticipants($value);
                break;
            case 11:
                $this->setStartdate($value);
                break;
            case 12:
                $this->setEnddate($value);
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
        $keys = SurveysTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setSurveyPK($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setQuestion($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setAnswer1($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setAnswer2($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setAnswer3($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setAnswer4($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setScore1($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setScore2($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->setScore3($arr[$keys[8]]);
        }
        if (array_key_exists($keys[9], $arr)) {
            $this->setScore4($arr[$keys[9]]);
        }
        if (array_key_exists($keys[10], $arr)) {
            $this->setParticipants($arr[$keys[10]]);
        }
        if (array_key_exists($keys[11], $arr)) {
            $this->setStartdate($arr[$keys[11]]);
        }
        if (array_key_exists($keys[12], $arr)) {
            $this->setEnddate($arr[$keys[12]]);
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
     * @return $this|\Surveys The current object, for fluid interface
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
        $criteria = new Criteria(SurveysTableMap::DATABASE_NAME);

        if ($this->isColumnModified(SurveysTableMap::COL_PRIMARYKEY)) {
            $criteria->add(SurveysTableMap::COL_PRIMARYKEY, $this->primarykey);
        }
        if ($this->isColumnModified(SurveysTableMap::COL_QUESTION)) {
            $criteria->add(SurveysTableMap::COL_QUESTION, $this->question);
        }
        if ($this->isColumnModified(SurveysTableMap::COL_ANSWER1)) {
            $criteria->add(SurveysTableMap::COL_ANSWER1, $this->answer1);
        }
        if ($this->isColumnModified(SurveysTableMap::COL_ANSWER2)) {
            $criteria->add(SurveysTableMap::COL_ANSWER2, $this->answer2);
        }
        if ($this->isColumnModified(SurveysTableMap::COL_ANSWER3)) {
            $criteria->add(SurveysTableMap::COL_ANSWER3, $this->answer3);
        }
        if ($this->isColumnModified(SurveysTableMap::COL_ANSWER4)) {
            $criteria->add(SurveysTableMap::COL_ANSWER4, $this->answer4);
        }
        if ($this->isColumnModified(SurveysTableMap::COL_SCORE1)) {
            $criteria->add(SurveysTableMap::COL_SCORE1, $this->score1);
        }
        if ($this->isColumnModified(SurveysTableMap::COL_SCORE2)) {
            $criteria->add(SurveysTableMap::COL_SCORE2, $this->score2);
        }
        if ($this->isColumnModified(SurveysTableMap::COL_SCORE3)) {
            $criteria->add(SurveysTableMap::COL_SCORE3, $this->score3);
        }
        if ($this->isColumnModified(SurveysTableMap::COL_SCORE4)) {
            $criteria->add(SurveysTableMap::COL_SCORE4, $this->score4);
        }
        if ($this->isColumnModified(SurveysTableMap::COL_PARTICIPANTS)) {
            $criteria->add(SurveysTableMap::COL_PARTICIPANTS, $this->participants);
        }
        if ($this->isColumnModified(SurveysTableMap::COL_STARTDATE)) {
            $criteria->add(SurveysTableMap::COL_STARTDATE, $this->startdate);
        }
        if ($this->isColumnModified(SurveysTableMap::COL_ENDDATE)) {
            $criteria->add(SurveysTableMap::COL_ENDDATE, $this->enddate);
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
        $criteria = ChildSurveysQuery::create();
        $criteria->add(SurveysTableMap::COL_PRIMARYKEY, $this->primarykey);

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
        $validPk = null !== $this->getSurveyPK();

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
        return $this->getSurveyPK();
    }

    /**
     * Generic method to set the primary key (primarykey column).
     *
     * @param       int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setSurveyPK($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {
        return null === $this->getSurveyPK();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of \Surveys (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setQuestion($this->getQuestion());
        $copyObj->setAnswer1($this->getAnswer1());
        $copyObj->setAnswer2($this->getAnswer2());
        $copyObj->setAnswer3($this->getAnswer3());
        $copyObj->setAnswer4($this->getAnswer4());
        $copyObj->setScore1($this->getScore1());
        $copyObj->setScore2($this->getScore2());
        $copyObj->setScore3($this->getScore3());
        $copyObj->setScore4($this->getScore4());
        $copyObj->setParticipants($this->getParticipants());
        $copyObj->setStartdate($this->getStartdate());
        $copyObj->setEnddate($this->getEnddate());
        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setSurveyPK(NULL); // this is a auto-increment column, so set to default value
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
     * @return \Surveys Clone of current object.
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
        $this->question = null;
        $this->answer1 = null;
        $this->answer2 = null;
        $this->answer3 = null;
        $this->answer4 = null;
        $this->score1 = null;
        $this->score2 = null;
        $this->score3 = null;
        $this->score4 = null;
        $this->participants = null;
        $this->startdate = null;
        $this->enddate = null;
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

    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(SurveysTableMap::DEFAULT_STRING_FORMAT);
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
