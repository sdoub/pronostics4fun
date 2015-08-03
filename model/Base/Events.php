<?php

namespace Base;

use \Events as ChildEvents;
use \EventsQuery as ChildEventsQuery;
use \Matchstates as ChildMatchstates;
use \MatchstatesQuery as ChildMatchstatesQuery;
use \Results as ChildResults;
use \ResultsQuery as ChildResultsQuery;
use \Teamplayers as ChildTeamplayers;
use \TeamplayersQuery as ChildTeamplayersQuery;
use \Teams as ChildTeams;
use \TeamsQuery as ChildTeamsQuery;
use \Exception;
use \PDO;
use Map\EventsTableMap;
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

/**
 * Base class that represents a row from the 'events' table.
 *
 *
 *
* @package    propel.generator..Base
*/
abstract class Events implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\Map\\EventsTableMap';


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
     * The value for the resultkey field.
     * @var        int
     */
    protected $resultkey;

    /**
     * The value for the teamplayerkey field.
     * @var        int
     */
    protected $teamplayerkey;

    /**
     * The value for the eventtime field.
     * @var        int
     */
    protected $eventtime;

    /**
     * The value for the eventtype field.
     * @var        int
     */
    protected $eventtype;

    /**
     * The value for the half field.
     * @var        int
     */
    protected $half;

    /**
     * The value for the teamkey field.
     * @var        int
     */
    protected $teamkey;

    /**
     * @var        ChildResults
     */
    protected $aResults;

    /**
     * @var        ChildTeamplayers
     */
    protected $aTeamplayers;

    /**
     * @var        ChildTeams
     */
    protected $aTeams;

    /**
     * @var        ObjectCollection|ChildMatchstates[] Collection to store aggregation of ChildMatchstates objects.
     */
    protected $collMatchstatess;
    protected $collMatchstatessPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildMatchstates[]
     */
    protected $matchstatessScheduledForDeletion = null;

    /**
     * Initializes internal state of Base\Events object.
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
     * Compares this with another <code>Events</code> instance.  If
     * <code>obj</code> is an instance of <code>Events</code>, delegates to
     * <code>equals(Events)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|Events The current object, for fluid interface
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
    public function getEventPK()
    {
        return $this->primarykey;
    }

    /**
     * Get the [resultkey] column value.
     *
     * @return int
     */
    public function getResultkey()
    {
        return $this->resultkey;
    }

    /**
     * Get the [teamplayerkey] column value.
     *
     * @return int
     */
    public function getTeamplayerkey()
    {
        return $this->teamplayerkey;
    }

    /**
     * Get the [eventtime] column value.
     *
     * @return int
     */
    public function getEventtime()
    {
        return $this->eventtime;
    }

    /**
     * Get the [eventtype] column value.
     *
     * @return int
     */
    public function getEventtype()
    {
        return $this->eventtype;
    }

    /**
     * Get the [half] column value.
     *
     * @return string
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getHalf()
    {
        if (null === $this->half) {
            return null;
        }
        $valueSet = EventsTableMap::getValueSet(EventsTableMap::COL_HALF);
        if (!isset($valueSet[$this->half])) {
            throw new PropelException('Unknown stored enum key: ' . $this->half);
        }

        return $valueSet[$this->half];
    }

    /**
     * Get the [teamkey] column value.
     *
     * @return int
     */
    public function getTeamkey()
    {
        return $this->teamkey;
    }

    /**
     * Set the value of [primarykey] column.
     *
     * @param int $v new value
     * @return $this|\Events The current object (for fluent API support)
     */
    public function setEventPK($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->primarykey !== $v) {
            $this->primarykey = $v;
            $this->modifiedColumns[EventsTableMap::COL_PRIMARYKEY] = true;
        }

        return $this;
    } // setEventPK()

    /**
     * Set the value of [resultkey] column.
     *
     * @param int $v new value
     * @return $this|\Events The current object (for fluent API support)
     */
    public function setResultkey($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->resultkey !== $v) {
            $this->resultkey = $v;
            $this->modifiedColumns[EventsTableMap::COL_RESULTKEY] = true;
        }

        if ($this->aResults !== null && $this->aResults->getResultPK() !== $v) {
            $this->aResults = null;
        }

        return $this;
    } // setResultkey()

    /**
     * Set the value of [teamplayerkey] column.
     *
     * @param int $v new value
     * @return $this|\Events The current object (for fluent API support)
     */
    public function setTeamplayerkey($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->teamplayerkey !== $v) {
            $this->teamplayerkey = $v;
            $this->modifiedColumns[EventsTableMap::COL_TEAMPLAYERKEY] = true;
        }

        if ($this->aTeamplayers !== null && $this->aTeamplayers->getTeamPlayerPK() !== $v) {
            $this->aTeamplayers = null;
        }

        return $this;
    } // setTeamplayerkey()

    /**
     * Set the value of [eventtime] column.
     *
     * @param int $v new value
     * @return $this|\Events The current object (for fluent API support)
     */
    public function setEventtime($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->eventtime !== $v) {
            $this->eventtime = $v;
            $this->modifiedColumns[EventsTableMap::COL_EVENTTIME] = true;
        }

        return $this;
    } // setEventtime()

    /**
     * Set the value of [eventtype] column.
     *
     * @param int $v new value
     * @return $this|\Events The current object (for fluent API support)
     */
    public function setEventtype($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->eventtype !== $v) {
            $this->eventtype = $v;
            $this->modifiedColumns[EventsTableMap::COL_EVENTTYPE] = true;
        }

        return $this;
    } // setEventtype()

    /**
     * Set the value of [half] column.
     *
     * @param  string $v new value
     * @return $this|\Events The current object (for fluent API support)
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function setHalf($v)
    {
        if ($v !== null) {
            $valueSet = EventsTableMap::getValueSet(EventsTableMap::COL_HALF);
            if (!in_array($v, $valueSet)) {
                throw new PropelException(sprintf('Value "%s" is not accepted in this enumerated column', $v));
            }
            $v = array_search($v, $valueSet);
        }

        if ($this->half !== $v) {
            $this->half = $v;
            $this->modifiedColumns[EventsTableMap::COL_HALF] = true;
        }

        return $this;
    } // setHalf()

    /**
     * Set the value of [teamkey] column.
     *
     * @param int $v new value
     * @return $this|\Events The current object (for fluent API support)
     */
    public function setTeamkey($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->teamkey !== $v) {
            $this->teamkey = $v;
            $this->modifiedColumns[EventsTableMap::COL_TEAMKEY] = true;
        }

        if ($this->aTeams !== null && $this->aTeams->getTeamPK() !== $v) {
            $this->aTeams = null;
        }

        return $this;
    } // setTeamkey()

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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : EventsTableMap::translateFieldName('EventPK', TableMap::TYPE_PHPNAME, $indexType)];
            $this->primarykey = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : EventsTableMap::translateFieldName('Resultkey', TableMap::TYPE_PHPNAME, $indexType)];
            $this->resultkey = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : EventsTableMap::translateFieldName('Teamplayerkey', TableMap::TYPE_PHPNAME, $indexType)];
            $this->teamplayerkey = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : EventsTableMap::translateFieldName('Eventtime', TableMap::TYPE_PHPNAME, $indexType)];
            $this->eventtime = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : EventsTableMap::translateFieldName('Eventtype', TableMap::TYPE_PHPNAME, $indexType)];
            $this->eventtype = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : EventsTableMap::translateFieldName('Half', TableMap::TYPE_PHPNAME, $indexType)];
            $this->half = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : EventsTableMap::translateFieldName('Teamkey', TableMap::TYPE_PHPNAME, $indexType)];
            $this->teamkey = (null !== $col) ? (int) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 7; // 7 = EventsTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Events'), 0, $e);
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
        if ($this->aResults !== null && $this->resultkey !== $this->aResults->getResultPK()) {
            $this->aResults = null;
        }
        if ($this->aTeamplayers !== null && $this->teamplayerkey !== $this->aTeamplayers->getTeamPlayerPK()) {
            $this->aTeamplayers = null;
        }
        if ($this->aTeams !== null && $this->teamkey !== $this->aTeams->getTeamPK()) {
            $this->aTeams = null;
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
            $con = Propel::getServiceContainer()->getReadConnection(EventsTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildEventsQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aResults = null;
            $this->aTeamplayers = null;
            $this->aTeams = null;
            $this->collMatchstatess = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Events::setDeleted()
     * @see Events::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(EventsTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildEventsQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(EventsTableMap::DATABASE_NAME);
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
                EventsTableMap::addInstanceToPool($this);
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

            if ($this->aResults !== null) {
                if ($this->aResults->isModified() || $this->aResults->isNew()) {
                    $affectedRows += $this->aResults->save($con);
                }
                $this->setResults($this->aResults);
            }

            if ($this->aTeamplayers !== null) {
                if ($this->aTeamplayers->isModified() || $this->aTeamplayers->isNew()) {
                    $affectedRows += $this->aTeamplayers->save($con);
                }
                $this->setTeamplayers($this->aTeamplayers);
            }

            if ($this->aTeams !== null) {
                if ($this->aTeams->isModified() || $this->aTeams->isNew()) {
                    $affectedRows += $this->aTeams->save($con);
                }
                $this->setTeams($this->aTeams);
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

        $this->modifiedColumns[EventsTableMap::COL_PRIMARYKEY] = true;
        if (null !== $this->primarykey) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . EventsTableMap::COL_PRIMARYKEY . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(EventsTableMap::COL_PRIMARYKEY)) {
            $modifiedColumns[':p' . $index++]  = 'PrimaryKey';
        }
        if ($this->isColumnModified(EventsTableMap::COL_RESULTKEY)) {
            $modifiedColumns[':p' . $index++]  = 'ResultKey';
        }
        if ($this->isColumnModified(EventsTableMap::COL_TEAMPLAYERKEY)) {
            $modifiedColumns[':p' . $index++]  = 'TeamPlayerKey';
        }
        if ($this->isColumnModified(EventsTableMap::COL_EVENTTIME)) {
            $modifiedColumns[':p' . $index++]  = 'EventTime';
        }
        if ($this->isColumnModified(EventsTableMap::COL_EVENTTYPE)) {
            $modifiedColumns[':p' . $index++]  = 'EventType';
        }
        if ($this->isColumnModified(EventsTableMap::COL_HALF)) {
            $modifiedColumns[':p' . $index++]  = 'Half';
        }
        if ($this->isColumnModified(EventsTableMap::COL_TEAMKEY)) {
            $modifiedColumns[':p' . $index++]  = 'TeamKey';
        }

        $sql = sprintf(
            'INSERT INTO events (%s) VALUES (%s)',
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
                    case 'ResultKey':
                        $stmt->bindValue($identifier, $this->resultkey, PDO::PARAM_INT);
                        break;
                    case 'TeamPlayerKey':
                        $stmt->bindValue($identifier, $this->teamplayerkey, PDO::PARAM_INT);
                        break;
                    case 'EventTime':
                        $stmt->bindValue($identifier, $this->eventtime, PDO::PARAM_INT);
                        break;
                    case 'EventType':
                        $stmt->bindValue($identifier, $this->eventtype, PDO::PARAM_INT);
                        break;
                    case 'Half':
                        $stmt->bindValue($identifier, $this->half, PDO::PARAM_INT);
                        break;
                    case 'TeamKey':
                        $stmt->bindValue($identifier, $this->teamkey, PDO::PARAM_INT);
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
        $this->setEventPK($pk);

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
        $pos = EventsTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getEventPK();
                break;
            case 1:
                return $this->getResultkey();
                break;
            case 2:
                return $this->getTeamplayerkey();
                break;
            case 3:
                return $this->getEventtime();
                break;
            case 4:
                return $this->getEventtype();
                break;
            case 5:
                return $this->getHalf();
                break;
            case 6:
                return $this->getTeamkey();
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

        if (isset($alreadyDumpedObjects['Events'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Events'][$this->hashCode()] = true;
        $keys = EventsTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getEventPK(),
            $keys[1] => $this->getResultkey(),
            $keys[2] => $this->getTeamplayerkey(),
            $keys[3] => $this->getEventtime(),
            $keys[4] => $this->getEventtype(),
            $keys[5] => $this->getHalf(),
            $keys[6] => $this->getTeamkey(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aResults) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'results';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'results';
                        break;
                    default:
                        $key = 'Results';
                }

                $result[$key] = $this->aResults->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aTeamplayers) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'teamplayers';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'teamplayers';
                        break;
                    default:
                        $key = 'Teamplayers';
                }

                $result[$key] = $this->aTeamplayers->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aTeams) {

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

                $result[$key] = $this->aTeams->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
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
     * @return $this|\Events
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = EventsTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\Events
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setEventPK($value);
                break;
            case 1:
                $this->setResultkey($value);
                break;
            case 2:
                $this->setTeamplayerkey($value);
                break;
            case 3:
                $this->setEventtime($value);
                break;
            case 4:
                $this->setEventtype($value);
                break;
            case 5:
                $valueSet = EventsTableMap::getValueSet(EventsTableMap::COL_HALF);
                if (isset($valueSet[$value])) {
                    $value = $valueSet[$value];
                }
                $this->setHalf($value);
                break;
            case 6:
                $this->setTeamkey($value);
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
        $keys = EventsTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setEventPK($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setResultkey($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setTeamplayerkey($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setEventtime($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setEventtype($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setHalf($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setTeamkey($arr[$keys[6]]);
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
     * @return $this|\Events The current object, for fluid interface
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
        $criteria = new Criteria(EventsTableMap::DATABASE_NAME);

        if ($this->isColumnModified(EventsTableMap::COL_PRIMARYKEY)) {
            $criteria->add(EventsTableMap::COL_PRIMARYKEY, $this->primarykey);
        }
        if ($this->isColumnModified(EventsTableMap::COL_RESULTKEY)) {
            $criteria->add(EventsTableMap::COL_RESULTKEY, $this->resultkey);
        }
        if ($this->isColumnModified(EventsTableMap::COL_TEAMPLAYERKEY)) {
            $criteria->add(EventsTableMap::COL_TEAMPLAYERKEY, $this->teamplayerkey);
        }
        if ($this->isColumnModified(EventsTableMap::COL_EVENTTIME)) {
            $criteria->add(EventsTableMap::COL_EVENTTIME, $this->eventtime);
        }
        if ($this->isColumnModified(EventsTableMap::COL_EVENTTYPE)) {
            $criteria->add(EventsTableMap::COL_EVENTTYPE, $this->eventtype);
        }
        if ($this->isColumnModified(EventsTableMap::COL_HALF)) {
            $criteria->add(EventsTableMap::COL_HALF, $this->half);
        }
        if ($this->isColumnModified(EventsTableMap::COL_TEAMKEY)) {
            $criteria->add(EventsTableMap::COL_TEAMKEY, $this->teamkey);
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
        $criteria = ChildEventsQuery::create();
        $criteria->add(EventsTableMap::COL_PRIMARYKEY, $this->primarykey);

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
        $validPk = null !== $this->getEventPK();

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
        return $this->getEventPK();
    }

    /**
     * Generic method to set the primary key (primarykey column).
     *
     * @param       int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setEventPK($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {
        return null === $this->getEventPK();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of \Events (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setResultkey($this->getResultkey());
        $copyObj->setTeamplayerkey($this->getTeamplayerkey());
        $copyObj->setEventtime($this->getEventtime());
        $copyObj->setEventtype($this->getEventtype());
        $copyObj->setHalf($this->getHalf());
        $copyObj->setTeamkey($this->getTeamkey());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getMatchstatess() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addMatchstates($relObj->copy($deepCopy));
                }
            }

        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setEventPK(NULL); // this is a auto-increment column, so set to default value
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
     * @return \Events Clone of current object.
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
     * Declares an association between this object and a ChildResults object.
     *
     * @param  ChildResults $v
     * @return $this|\Events The current object (for fluent API support)
     * @throws PropelException
     */
    public function setResults(ChildResults $v = null)
    {
        if ($v === null) {
            $this->setResultkey(NULL);
        } else {
            $this->setResultkey($v->getResultPK());
        }

        $this->aResults = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildResults object, it will not be re-added.
        if ($v !== null) {
            $v->addEvents($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildResults object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildResults The associated ChildResults object.
     * @throws PropelException
     */
    public function getResults(ConnectionInterface $con = null)
    {
        if ($this->aResults === null && ($this->resultkey !== null)) {
            $this->aResults = ChildResultsQuery::create()->findPk($this->resultkey, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aResults->addEventss($this);
             */
        }

        return $this->aResults;
    }

    /**
     * Declares an association between this object and a ChildTeamplayers object.
     *
     * @param  ChildTeamplayers $v
     * @return $this|\Events The current object (for fluent API support)
     * @throws PropelException
     */
    public function setTeamplayers(ChildTeamplayers $v = null)
    {
        if ($v === null) {
            $this->setTeamplayerkey(NULL);
        } else {
            $this->setTeamplayerkey($v->getTeamPlayerPK());
        }

        $this->aTeamplayers = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildTeamplayers object, it will not be re-added.
        if ($v !== null) {
            $v->addEvents($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildTeamplayers object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildTeamplayers The associated ChildTeamplayers object.
     * @throws PropelException
     */
    public function getTeamplayers(ConnectionInterface $con = null)
    {
        if ($this->aTeamplayers === null && ($this->teamplayerkey !== null)) {
            $this->aTeamplayers = ChildTeamplayersQuery::create()->findPk($this->teamplayerkey, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aTeamplayers->addEventss($this);
             */
        }

        return $this->aTeamplayers;
    }

    /**
     * Declares an association between this object and a ChildTeams object.
     *
     * @param  ChildTeams $v
     * @return $this|\Events The current object (for fluent API support)
     * @throws PropelException
     */
    public function setTeams(ChildTeams $v = null)
    {
        if ($v === null) {
            $this->setTeamkey(NULL);
        } else {
            $this->setTeamkey($v->getTeamPK());
        }

        $this->aTeams = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildTeams object, it will not be re-added.
        if ($v !== null) {
            $v->addEvents($this);
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
    public function getTeams(ConnectionInterface $con = null)
    {
        if ($this->aTeams === null && ($this->teamkey !== null)) {
            $this->aTeams = ChildTeamsQuery::create()->findPk($this->teamkey, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aTeams->addEventss($this);
             */
        }

        return $this->aTeams;
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
        if ('Matchstates' == $relationName) {
            return $this->initMatchstatess();
        }
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
     * If this ChildEvents is new, it will return
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
                    ->filterByEvents($this)
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
     * @return $this|ChildEvents The current object (for fluent API support)
     */
    public function setMatchstatess(Collection $matchstatess, ConnectionInterface $con = null)
    {
        /** @var ChildMatchstates[] $matchstatessToDelete */
        $matchstatessToDelete = $this->getMatchstatess(new Criteria(), $con)->diff($matchstatess);


        $this->matchstatessScheduledForDeletion = $matchstatessToDelete;

        foreach ($matchstatessToDelete as $matchstatesRemoved) {
            $matchstatesRemoved->setEvents(null);
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
                ->filterByEvents($this)
                ->count($con);
        }

        return count($this->collMatchstatess);
    }

    /**
     * Method called to associate a ChildMatchstates object to this object
     * through the ChildMatchstates foreign key attribute.
     *
     * @param  ChildMatchstates $l ChildMatchstates
     * @return $this|\Events The current object (for fluent API support)
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
        $matchstates->setEvents($this);
    }

    /**
     * @param  ChildMatchstates $matchstates The ChildMatchstates object to remove.
     * @return $this|ChildEvents The current object (for fluent API support)
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
            $matchstates->setEvents(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Events is new, it will return
     * an empty collection; or if this Events has previously
     * been saved, it will retrieve related Matchstatess from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Events.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildMatchstates[] List of ChildMatchstates objects
     */
    public function getMatchstatessJoinMatchState(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildMatchstatesQuery::create(null, $criteria);
        $query->joinWith('MatchState', $joinBehavior);

        return $this->getMatchstatess($query, $con);
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        if (null !== $this->aResults) {
            $this->aResults->removeEvents($this);
        }
        if (null !== $this->aTeamplayers) {
            $this->aTeamplayers->removeEvents($this);
        }
        if (null !== $this->aTeams) {
            $this->aTeams->removeEvents($this);
        }
        $this->primarykey = null;
        $this->resultkey = null;
        $this->teamplayerkey = null;
        $this->eventtime = null;
        $this->eventtype = null;
        $this->half = null;
        $this->teamkey = null;
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
            if ($this->collMatchstatess) {
                foreach ($this->collMatchstatess as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collMatchstatess = null;
        $this->aResults = null;
        $this->aTeamplayers = null;
        $this->aTeams = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(EventsTableMap::DEFAULT_STRING_FORMAT);
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
