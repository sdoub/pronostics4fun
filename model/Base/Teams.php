<?php

namespace Base;

use \Events as ChildEvents;
use \EventsQuery as ChildEventsQuery;
use \Lineups as ChildLineups;
use \LineupsQuery as ChildLineupsQuery;
use \Matches as ChildMatches;
use \MatchesQuery as ChildMatchesQuery;
use \Teams as ChildTeams;
use \TeamsQuery as ChildTeamsQuery;
use \Exception;
use \PDO;
use Map\TeamsTableMap;
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
 * Base class that represents a row from the 'teams' table.
 *
 *
 *
* @package    propel.generator..Base
*/
abstract class Teams implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\Map\\TeamsTableMap';


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
     * The value for the name field.
     * @var        string
     */
    protected $name;

    /**
     * The value for the code field.
     * @var        string
     */
    protected $code;

    /**
     * @var        ObjectCollection|ChildEvents[] Collection to store aggregation of ChildEvents objects.
     */
    protected $collEventss;
    protected $collEventssPartial;

    /**
     * @var        ObjectCollection|ChildLineups[] Collection to store aggregation of ChildLineups objects.
     */
    protected $collLineupss;
    protected $collLineupssPartial;

    /**
     * @var        ObjectCollection|ChildMatches[] Collection to store aggregation of ChildMatches objects.
     */
    protected $collMatchessRelatedByTeamhomekey;
    protected $collMatchessRelatedByTeamhomekeyPartial;

    /**
     * @var        ObjectCollection|ChildMatches[] Collection to store aggregation of ChildMatches objects.
     */
    protected $collMatchessRelatedByTeamawaykey;
    protected $collMatchessRelatedByTeamawaykeyPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildEvents[]
     */
    protected $eventssScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildLineups[]
     */
    protected $lineupssScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildMatches[]
     */
    protected $matchessRelatedByTeamhomekeyScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildMatches[]
     */
    protected $matchessRelatedByTeamawaykeyScheduledForDeletion = null;

    /**
     * Initializes internal state of Base\Teams object.
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
     * Compares this with another <code>Teams</code> instance.  If
     * <code>obj</code> is an instance of <code>Teams</code>, delegates to
     * <code>equals(Teams)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|Teams The current object, for fluid interface
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
    public function getTeamPK()
    {
        return $this->primarykey;
    }

    /**
     * Get the [name] column value.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
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
     * Set the value of [primarykey] column.
     *
     * @param int $v new value
     * @return $this|\Teams The current object (for fluent API support)
     */
    public function setTeamPK($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->primarykey !== $v) {
            $this->primarykey = $v;
            $this->modifiedColumns[TeamsTableMap::COL_PRIMARYKEY] = true;
        }

        return $this;
    } // setTeamPK()

    /**
     * Set the value of [name] column.
     *
     * @param string $v new value
     * @return $this|\Teams The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[TeamsTableMap::COL_NAME] = true;
        }

        return $this;
    } // setName()

    /**
     * Set the value of [code] column.
     *
     * @param string $v new value
     * @return $this|\Teams The current object (for fluent API support)
     */
    public function setCode($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->code !== $v) {
            $this->code = $v;
            $this->modifiedColumns[TeamsTableMap::COL_CODE] = true;
        }

        return $this;
    } // setCode()

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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : TeamsTableMap::translateFieldName('TeamPK', TableMap::TYPE_PHPNAME, $indexType)];
            $this->primarykey = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : TeamsTableMap::translateFieldName('Name', TableMap::TYPE_PHPNAME, $indexType)];
            $this->name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : TeamsTableMap::translateFieldName('Code', TableMap::TYPE_PHPNAME, $indexType)];
            $this->code = (null !== $col) ? (string) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 3; // 3 = TeamsTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Teams'), 0, $e);
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
            $con = Propel::getServiceContainer()->getReadConnection(TeamsTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildTeamsQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collEventss = null;

            $this->collLineupss = null;

            $this->collMatchessRelatedByTeamhomekey = null;

            $this->collMatchessRelatedByTeamawaykey = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Teams::setDeleted()
     * @see Teams::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(TeamsTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildTeamsQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(TeamsTableMap::DATABASE_NAME);
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
                TeamsTableMap::addInstanceToPool($this);
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

            if ($this->eventssScheduledForDeletion !== null) {
                if (!$this->eventssScheduledForDeletion->isEmpty()) {
                    \EventsQuery::create()
                        ->filterByPrimaryKeys($this->eventssScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->eventssScheduledForDeletion = null;
                }
            }

            if ($this->collEventss !== null) {
                foreach ($this->collEventss as $referrerFK) {
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

            if ($this->matchessRelatedByTeamhomekeyScheduledForDeletion !== null) {
                if (!$this->matchessRelatedByTeamhomekeyScheduledForDeletion->isEmpty()) {
                    \MatchesQuery::create()
                        ->filterByPrimaryKeys($this->matchessRelatedByTeamhomekeyScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->matchessRelatedByTeamhomekeyScheduledForDeletion = null;
                }
            }

            if ($this->collMatchessRelatedByTeamhomekey !== null) {
                foreach ($this->collMatchessRelatedByTeamhomekey as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->matchessRelatedByTeamawaykeyScheduledForDeletion !== null) {
                if (!$this->matchessRelatedByTeamawaykeyScheduledForDeletion->isEmpty()) {
                    \MatchesQuery::create()
                        ->filterByPrimaryKeys($this->matchessRelatedByTeamawaykeyScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->matchessRelatedByTeamawaykeyScheduledForDeletion = null;
                }
            }

            if ($this->collMatchessRelatedByTeamawaykey !== null) {
                foreach ($this->collMatchessRelatedByTeamawaykey as $referrerFK) {
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

        $this->modifiedColumns[TeamsTableMap::COL_PRIMARYKEY] = true;
        if (null !== $this->primarykey) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . TeamsTableMap::COL_PRIMARYKEY . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(TeamsTableMap::COL_PRIMARYKEY)) {
            $modifiedColumns[':p' . $index++]  = 'PrimaryKey';
        }
        if ($this->isColumnModified(TeamsTableMap::COL_NAME)) {
            $modifiedColumns[':p' . $index++]  = 'Name';
        }
        if ($this->isColumnModified(TeamsTableMap::COL_CODE)) {
            $modifiedColumns[':p' . $index++]  = 'Code';
        }

        $sql = sprintf(
            'INSERT INTO teams (%s) VALUES (%s)',
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
                    case 'Name':
                        $stmt->bindValue($identifier, $this->name, PDO::PARAM_STR);
                        break;
                    case 'Code':
                        $stmt->bindValue($identifier, $this->code, PDO::PARAM_STR);
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
        $this->setTeamPK($pk);

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
        $pos = TeamsTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getTeamPK();
                break;
            case 1:
                return $this->getName();
                break;
            case 2:
                return $this->getCode();
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

        if (isset($alreadyDumpedObjects['Teams'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Teams'][$this->hashCode()] = true;
        $keys = TeamsTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getTeamPK(),
            $keys[1] => $this->getName(),
            $keys[2] => $this->getCode(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->collEventss) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'eventss';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'eventss';
                        break;
                    default:
                        $key = 'Eventss';
                }

                $result[$key] = $this->collEventss->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
            if (null !== $this->collMatchessRelatedByTeamhomekey) {

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

                $result[$key] = $this->collMatchessRelatedByTeamhomekey->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collMatchessRelatedByTeamawaykey) {

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

                $result[$key] = $this->collMatchessRelatedByTeamawaykey->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
     * @return $this|\Teams
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = TeamsTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\Teams
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setTeamPK($value);
                break;
            case 1:
                $this->setName($value);
                break;
            case 2:
                $this->setCode($value);
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
        $keys = TeamsTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setTeamPK($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setName($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setCode($arr[$keys[2]]);
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
     * @return $this|\Teams The current object, for fluid interface
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
        $criteria = new Criteria(TeamsTableMap::DATABASE_NAME);

        if ($this->isColumnModified(TeamsTableMap::COL_PRIMARYKEY)) {
            $criteria->add(TeamsTableMap::COL_PRIMARYKEY, $this->primarykey);
        }
        if ($this->isColumnModified(TeamsTableMap::COL_NAME)) {
            $criteria->add(TeamsTableMap::COL_NAME, $this->name);
        }
        if ($this->isColumnModified(TeamsTableMap::COL_CODE)) {
            $criteria->add(TeamsTableMap::COL_CODE, $this->code);
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
        $criteria = ChildTeamsQuery::create();
        $criteria->add(TeamsTableMap::COL_PRIMARYKEY, $this->primarykey);

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
        $validPk = null !== $this->getTeamPK();

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
        return $this->getTeamPK();
    }

    /**
     * Generic method to set the primary key (primarykey column).
     *
     * @param       int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setTeamPK($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {
        return null === $this->getTeamPK();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of \Teams (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setName($this->getName());
        $copyObj->setCode($this->getCode());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getEventss() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addEvents($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getLineupss() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addLineups($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getMatchessRelatedByTeamhomekey() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addMatchesRelatedByTeamhomekey($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getMatchessRelatedByTeamawaykey() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addMatchesRelatedByTeamawaykey($relObj->copy($deepCopy));
                }
            }

        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setTeamPK(NULL); // this is a auto-increment column, so set to default value
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
     * @return \Teams Clone of current object.
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
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param      string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('Events' == $relationName) {
            return $this->initEventss();
        }
        if ('Lineups' == $relationName) {
            return $this->initLineupss();
        }
        if ('MatchesRelatedByTeamhomekey' == $relationName) {
            return $this->initMatchessRelatedByTeamhomekey();
        }
        if ('MatchesRelatedByTeamawaykey' == $relationName) {
            return $this->initMatchessRelatedByTeamawaykey();
        }
    }

    /**
     * Clears out the collEventss collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addEventss()
     */
    public function clearEventss()
    {
        $this->collEventss = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collEventss collection loaded partially.
     */
    public function resetPartialEventss($v = true)
    {
        $this->collEventssPartial = $v;
    }

    /**
     * Initializes the collEventss collection.
     *
     * By default this just sets the collEventss collection to an empty array (like clearcollEventss());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initEventss($overrideExisting = true)
    {
        if (null !== $this->collEventss && !$overrideExisting) {
            return;
        }
        $this->collEventss = new ObjectCollection();
        $this->collEventss->setModel('\Events');
    }

    /**
     * Gets an array of ChildEvents objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildTeams is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildEvents[] List of ChildEvents objects
     * @throws PropelException
     */
    public function getEventss(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collEventssPartial && !$this->isNew();
        if (null === $this->collEventss || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collEventss) {
                // return empty collection
                $this->initEventss();
            } else {
                $collEventss = ChildEventsQuery::create(null, $criteria)
                    ->filterByTeams($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collEventssPartial && count($collEventss)) {
                        $this->initEventss(false);

                        foreach ($collEventss as $obj) {
                            if (false == $this->collEventss->contains($obj)) {
                                $this->collEventss->append($obj);
                            }
                        }

                        $this->collEventssPartial = true;
                    }

                    return $collEventss;
                }

                if ($partial && $this->collEventss) {
                    foreach ($this->collEventss as $obj) {
                        if ($obj->isNew()) {
                            $collEventss[] = $obj;
                        }
                    }
                }

                $this->collEventss = $collEventss;
                $this->collEventssPartial = false;
            }
        }

        return $this->collEventss;
    }

    /**
     * Sets a collection of ChildEvents objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $eventss A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildTeams The current object (for fluent API support)
     */
    public function setEventss(Collection $eventss, ConnectionInterface $con = null)
    {
        /** @var ChildEvents[] $eventssToDelete */
        $eventssToDelete = $this->getEventss(new Criteria(), $con)->diff($eventss);


        $this->eventssScheduledForDeletion = $eventssToDelete;

        foreach ($eventssToDelete as $eventsRemoved) {
            $eventsRemoved->setTeams(null);
        }

        $this->collEventss = null;
        foreach ($eventss as $events) {
            $this->addEvents($events);
        }

        $this->collEventss = $eventss;
        $this->collEventssPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Events objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Events objects.
     * @throws PropelException
     */
    public function countEventss(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collEventssPartial && !$this->isNew();
        if (null === $this->collEventss || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collEventss) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getEventss());
            }

            $query = ChildEventsQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByTeams($this)
                ->count($con);
        }

        return count($this->collEventss);
    }

    /**
     * Method called to associate a ChildEvents object to this object
     * through the ChildEvents foreign key attribute.
     *
     * @param  ChildEvents $l ChildEvents
     * @return $this|\Teams The current object (for fluent API support)
     */
    public function addEvents(ChildEvents $l)
    {
        if ($this->collEventss === null) {
            $this->initEventss();
            $this->collEventssPartial = true;
        }

        if (!$this->collEventss->contains($l)) {
            $this->doAddEvents($l);
        }

        return $this;
    }

    /**
     * @param ChildEvents $events The ChildEvents object to add.
     */
    protected function doAddEvents(ChildEvents $events)
    {
        $this->collEventss[]= $events;
        $events->setTeams($this);
    }

    /**
     * @param  ChildEvents $events The ChildEvents object to remove.
     * @return $this|ChildTeams The current object (for fluent API support)
     */
    public function removeEvents(ChildEvents $events)
    {
        if ($this->getEventss()->contains($events)) {
            $pos = $this->collEventss->search($events);
            $this->collEventss->remove($pos);
            if (null === $this->eventssScheduledForDeletion) {
                $this->eventssScheduledForDeletion = clone $this->collEventss;
                $this->eventssScheduledForDeletion->clear();
            }
            $this->eventssScheduledForDeletion[]= clone $events;
            $events->setTeams(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Teams is new, it will return
     * an empty collection; or if this Teams has previously
     * been saved, it will retrieve related Eventss from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Teams.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildEvents[] List of ChildEvents objects
     */
    public function getEventssJoinResults(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildEventsQuery::create(null, $criteria);
        $query->joinWith('Results', $joinBehavior);

        return $this->getEventss($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Teams is new, it will return
     * an empty collection; or if this Teams has previously
     * been saved, it will retrieve related Eventss from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Teams.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildEvents[] List of ChildEvents objects
     */
    public function getEventssJoinTeamplayers(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildEventsQuery::create(null, $criteria);
        $query->joinWith('Teamplayers', $joinBehavior);

        return $this->getEventss($query, $con);
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
     * If this ChildTeams is new, it will return
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
                    ->filterByLineUpTeam($this)
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
     * @return $this|ChildTeams The current object (for fluent API support)
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
            $lineupsRemoved->setLineUpTeam(null);
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
                ->filterByLineUpTeam($this)
                ->count($con);
        }

        return count($this->collLineupss);
    }

    /**
     * Method called to associate a ChildLineups object to this object
     * through the ChildLineups foreign key attribute.
     *
     * @param  ChildLineups $l ChildLineups
     * @return $this|\Teams The current object (for fluent API support)
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
        $lineups->setLineUpTeam($this);
    }

    /**
     * @param  ChildLineups $lineups The ChildLineups object to remove.
     * @return $this|ChildTeams The current object (for fluent API support)
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
            $lineups->setLineUpTeam(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Teams is new, it will return
     * an empty collection; or if this Teams has previously
     * been saved, it will retrieve related Lineupss from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Teams.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildLineups[] List of ChildLineups objects
     */
    public function getLineupssJoinLineUpMatch(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildLineupsQuery::create(null, $criteria);
        $query->joinWith('LineUpMatch', $joinBehavior);

        return $this->getLineupss($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Teams is new, it will return
     * an empty collection; or if this Teams has previously
     * been saved, it will retrieve related Lineupss from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Teams.
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
     * Clears out the collMatchessRelatedByTeamhomekey collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addMatchessRelatedByTeamhomekey()
     */
    public function clearMatchessRelatedByTeamhomekey()
    {
        $this->collMatchessRelatedByTeamhomekey = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collMatchessRelatedByTeamhomekey collection loaded partially.
     */
    public function resetPartialMatchessRelatedByTeamhomekey($v = true)
    {
        $this->collMatchessRelatedByTeamhomekeyPartial = $v;
    }

    /**
     * Initializes the collMatchessRelatedByTeamhomekey collection.
     *
     * By default this just sets the collMatchessRelatedByTeamhomekey collection to an empty array (like clearcollMatchessRelatedByTeamhomekey());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initMatchessRelatedByTeamhomekey($overrideExisting = true)
    {
        if (null !== $this->collMatchessRelatedByTeamhomekey && !$overrideExisting) {
            return;
        }
        $this->collMatchessRelatedByTeamhomekey = new ObjectCollection();
        $this->collMatchessRelatedByTeamhomekey->setModel('\Matches');
    }

    /**
     * Gets an array of ChildMatches objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildTeams is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildMatches[] List of ChildMatches objects
     * @throws PropelException
     */
    public function getMatchessRelatedByTeamhomekey(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collMatchessRelatedByTeamhomekeyPartial && !$this->isNew();
        if (null === $this->collMatchessRelatedByTeamhomekey || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collMatchessRelatedByTeamhomekey) {
                // return empty collection
                $this->initMatchessRelatedByTeamhomekey();
            } else {
                $collMatchessRelatedByTeamhomekey = ChildMatchesQuery::create(null, $criteria)
                    ->filterByTeamHome($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collMatchessRelatedByTeamhomekeyPartial && count($collMatchessRelatedByTeamhomekey)) {
                        $this->initMatchessRelatedByTeamhomekey(false);

                        foreach ($collMatchessRelatedByTeamhomekey as $obj) {
                            if (false == $this->collMatchessRelatedByTeamhomekey->contains($obj)) {
                                $this->collMatchessRelatedByTeamhomekey->append($obj);
                            }
                        }

                        $this->collMatchessRelatedByTeamhomekeyPartial = true;
                    }

                    return $collMatchessRelatedByTeamhomekey;
                }

                if ($partial && $this->collMatchessRelatedByTeamhomekey) {
                    foreach ($this->collMatchessRelatedByTeamhomekey as $obj) {
                        if ($obj->isNew()) {
                            $collMatchessRelatedByTeamhomekey[] = $obj;
                        }
                    }
                }

                $this->collMatchessRelatedByTeamhomekey = $collMatchessRelatedByTeamhomekey;
                $this->collMatchessRelatedByTeamhomekeyPartial = false;
            }
        }

        return $this->collMatchessRelatedByTeamhomekey;
    }

    /**
     * Sets a collection of ChildMatches objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $matchessRelatedByTeamhomekey A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildTeams The current object (for fluent API support)
     */
    public function setMatchessRelatedByTeamhomekey(Collection $matchessRelatedByTeamhomekey, ConnectionInterface $con = null)
    {
        /** @var ChildMatches[] $matchessRelatedByTeamhomekeyToDelete */
        $matchessRelatedByTeamhomekeyToDelete = $this->getMatchessRelatedByTeamhomekey(new Criteria(), $con)->diff($matchessRelatedByTeamhomekey);


        $this->matchessRelatedByTeamhomekeyScheduledForDeletion = $matchessRelatedByTeamhomekeyToDelete;

        foreach ($matchessRelatedByTeamhomekeyToDelete as $matchesRelatedByTeamhomekeyRemoved) {
            $matchesRelatedByTeamhomekeyRemoved->setTeamHome(null);
        }

        $this->collMatchessRelatedByTeamhomekey = null;
        foreach ($matchessRelatedByTeamhomekey as $matchesRelatedByTeamhomekey) {
            $this->addMatchesRelatedByTeamhomekey($matchesRelatedByTeamhomekey);
        }

        $this->collMatchessRelatedByTeamhomekey = $matchessRelatedByTeamhomekey;
        $this->collMatchessRelatedByTeamhomekeyPartial = false;

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
    public function countMatchessRelatedByTeamhomekey(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collMatchessRelatedByTeamhomekeyPartial && !$this->isNew();
        if (null === $this->collMatchessRelatedByTeamhomekey || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collMatchessRelatedByTeamhomekey) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getMatchessRelatedByTeamhomekey());
            }

            $query = ChildMatchesQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByTeamHome($this)
                ->count($con);
        }

        return count($this->collMatchessRelatedByTeamhomekey);
    }

    /**
     * Method called to associate a ChildMatches object to this object
     * through the ChildMatches foreign key attribute.
     *
     * @param  ChildMatches $l ChildMatches
     * @return $this|\Teams The current object (for fluent API support)
     */
    public function addMatchesRelatedByTeamhomekey(ChildMatches $l)
    {
        if ($this->collMatchessRelatedByTeamhomekey === null) {
            $this->initMatchessRelatedByTeamhomekey();
            $this->collMatchessRelatedByTeamhomekeyPartial = true;
        }

        if (!$this->collMatchessRelatedByTeamhomekey->contains($l)) {
            $this->doAddMatchesRelatedByTeamhomekey($l);
        }

        return $this;
    }

    /**
     * @param ChildMatches $matchesRelatedByTeamhomekey The ChildMatches object to add.
     */
    protected function doAddMatchesRelatedByTeamhomekey(ChildMatches $matchesRelatedByTeamhomekey)
    {
        $this->collMatchessRelatedByTeamhomekey[]= $matchesRelatedByTeamhomekey;
        $matchesRelatedByTeamhomekey->setTeamHome($this);
    }

    /**
     * @param  ChildMatches $matchesRelatedByTeamhomekey The ChildMatches object to remove.
     * @return $this|ChildTeams The current object (for fluent API support)
     */
    public function removeMatchesRelatedByTeamhomekey(ChildMatches $matchesRelatedByTeamhomekey)
    {
        if ($this->getMatchessRelatedByTeamhomekey()->contains($matchesRelatedByTeamhomekey)) {
            $pos = $this->collMatchessRelatedByTeamhomekey->search($matchesRelatedByTeamhomekey);
            $this->collMatchessRelatedByTeamhomekey->remove($pos);
            if (null === $this->matchessRelatedByTeamhomekeyScheduledForDeletion) {
                $this->matchessRelatedByTeamhomekeyScheduledForDeletion = clone $this->collMatchessRelatedByTeamhomekey;
                $this->matchessRelatedByTeamhomekeyScheduledForDeletion->clear();
            }
            $this->matchessRelatedByTeamhomekeyScheduledForDeletion[]= clone $matchesRelatedByTeamhomekey;
            $matchesRelatedByTeamhomekey->setTeamHome(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Teams is new, it will return
     * an empty collection; or if this Teams has previously
     * been saved, it will retrieve related MatchessRelatedByTeamhomekey from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Teams.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildMatches[] List of ChildMatches objects
     */
    public function getMatchessRelatedByTeamhomekeyJoinGroups(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildMatchesQuery::create(null, $criteria);
        $query->joinWith('Groups', $joinBehavior);

        return $this->getMatchessRelatedByTeamhomekey($query, $con);
    }

    /**
     * Clears out the collMatchessRelatedByTeamawaykey collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addMatchessRelatedByTeamawaykey()
     */
    public function clearMatchessRelatedByTeamawaykey()
    {
        $this->collMatchessRelatedByTeamawaykey = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collMatchessRelatedByTeamawaykey collection loaded partially.
     */
    public function resetPartialMatchessRelatedByTeamawaykey($v = true)
    {
        $this->collMatchessRelatedByTeamawaykeyPartial = $v;
    }

    /**
     * Initializes the collMatchessRelatedByTeamawaykey collection.
     *
     * By default this just sets the collMatchessRelatedByTeamawaykey collection to an empty array (like clearcollMatchessRelatedByTeamawaykey());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initMatchessRelatedByTeamawaykey($overrideExisting = true)
    {
        if (null !== $this->collMatchessRelatedByTeamawaykey && !$overrideExisting) {
            return;
        }
        $this->collMatchessRelatedByTeamawaykey = new ObjectCollection();
        $this->collMatchessRelatedByTeamawaykey->setModel('\Matches');
    }

    /**
     * Gets an array of ChildMatches objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildTeams is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildMatches[] List of ChildMatches objects
     * @throws PropelException
     */
    public function getMatchessRelatedByTeamawaykey(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collMatchessRelatedByTeamawaykeyPartial && !$this->isNew();
        if (null === $this->collMatchessRelatedByTeamawaykey || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collMatchessRelatedByTeamawaykey) {
                // return empty collection
                $this->initMatchessRelatedByTeamawaykey();
            } else {
                $collMatchessRelatedByTeamawaykey = ChildMatchesQuery::create(null, $criteria)
                    ->filterByTeamAway($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collMatchessRelatedByTeamawaykeyPartial && count($collMatchessRelatedByTeamawaykey)) {
                        $this->initMatchessRelatedByTeamawaykey(false);

                        foreach ($collMatchessRelatedByTeamawaykey as $obj) {
                            if (false == $this->collMatchessRelatedByTeamawaykey->contains($obj)) {
                                $this->collMatchessRelatedByTeamawaykey->append($obj);
                            }
                        }

                        $this->collMatchessRelatedByTeamawaykeyPartial = true;
                    }

                    return $collMatchessRelatedByTeamawaykey;
                }

                if ($partial && $this->collMatchessRelatedByTeamawaykey) {
                    foreach ($this->collMatchessRelatedByTeamawaykey as $obj) {
                        if ($obj->isNew()) {
                            $collMatchessRelatedByTeamawaykey[] = $obj;
                        }
                    }
                }

                $this->collMatchessRelatedByTeamawaykey = $collMatchessRelatedByTeamawaykey;
                $this->collMatchessRelatedByTeamawaykeyPartial = false;
            }
        }

        return $this->collMatchessRelatedByTeamawaykey;
    }

    /**
     * Sets a collection of ChildMatches objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $matchessRelatedByTeamawaykey A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildTeams The current object (for fluent API support)
     */
    public function setMatchessRelatedByTeamawaykey(Collection $matchessRelatedByTeamawaykey, ConnectionInterface $con = null)
    {
        /** @var ChildMatches[] $matchessRelatedByTeamawaykeyToDelete */
        $matchessRelatedByTeamawaykeyToDelete = $this->getMatchessRelatedByTeamawaykey(new Criteria(), $con)->diff($matchessRelatedByTeamawaykey);


        $this->matchessRelatedByTeamawaykeyScheduledForDeletion = $matchessRelatedByTeamawaykeyToDelete;

        foreach ($matchessRelatedByTeamawaykeyToDelete as $matchesRelatedByTeamawaykeyRemoved) {
            $matchesRelatedByTeamawaykeyRemoved->setTeamAway(null);
        }

        $this->collMatchessRelatedByTeamawaykey = null;
        foreach ($matchessRelatedByTeamawaykey as $matchesRelatedByTeamawaykey) {
            $this->addMatchesRelatedByTeamawaykey($matchesRelatedByTeamawaykey);
        }

        $this->collMatchessRelatedByTeamawaykey = $matchessRelatedByTeamawaykey;
        $this->collMatchessRelatedByTeamawaykeyPartial = false;

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
    public function countMatchessRelatedByTeamawaykey(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collMatchessRelatedByTeamawaykeyPartial && !$this->isNew();
        if (null === $this->collMatchessRelatedByTeamawaykey || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collMatchessRelatedByTeamawaykey) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getMatchessRelatedByTeamawaykey());
            }

            $query = ChildMatchesQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByTeamAway($this)
                ->count($con);
        }

        return count($this->collMatchessRelatedByTeamawaykey);
    }

    /**
     * Method called to associate a ChildMatches object to this object
     * through the ChildMatches foreign key attribute.
     *
     * @param  ChildMatches $l ChildMatches
     * @return $this|\Teams The current object (for fluent API support)
     */
    public function addMatchesRelatedByTeamawaykey(ChildMatches $l)
    {
        if ($this->collMatchessRelatedByTeamawaykey === null) {
            $this->initMatchessRelatedByTeamawaykey();
            $this->collMatchessRelatedByTeamawaykeyPartial = true;
        }

        if (!$this->collMatchessRelatedByTeamawaykey->contains($l)) {
            $this->doAddMatchesRelatedByTeamawaykey($l);
        }

        return $this;
    }

    /**
     * @param ChildMatches $matchesRelatedByTeamawaykey The ChildMatches object to add.
     */
    protected function doAddMatchesRelatedByTeamawaykey(ChildMatches $matchesRelatedByTeamawaykey)
    {
        $this->collMatchessRelatedByTeamawaykey[]= $matchesRelatedByTeamawaykey;
        $matchesRelatedByTeamawaykey->setTeamAway($this);
    }

    /**
     * @param  ChildMatches $matchesRelatedByTeamawaykey The ChildMatches object to remove.
     * @return $this|ChildTeams The current object (for fluent API support)
     */
    public function removeMatchesRelatedByTeamawaykey(ChildMatches $matchesRelatedByTeamawaykey)
    {
        if ($this->getMatchessRelatedByTeamawaykey()->contains($matchesRelatedByTeamawaykey)) {
            $pos = $this->collMatchessRelatedByTeamawaykey->search($matchesRelatedByTeamawaykey);
            $this->collMatchessRelatedByTeamawaykey->remove($pos);
            if (null === $this->matchessRelatedByTeamawaykeyScheduledForDeletion) {
                $this->matchessRelatedByTeamawaykeyScheduledForDeletion = clone $this->collMatchessRelatedByTeamawaykey;
                $this->matchessRelatedByTeamawaykeyScheduledForDeletion->clear();
            }
            $this->matchessRelatedByTeamawaykeyScheduledForDeletion[]= clone $matchesRelatedByTeamawaykey;
            $matchesRelatedByTeamawaykey->setTeamAway(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Teams is new, it will return
     * an empty collection; or if this Teams has previously
     * been saved, it will retrieve related MatchessRelatedByTeamawaykey from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Teams.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildMatches[] List of ChildMatches objects
     */
    public function getMatchessRelatedByTeamawaykeyJoinGroups(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildMatchesQuery::create(null, $criteria);
        $query->joinWith('Groups', $joinBehavior);

        return $this->getMatchessRelatedByTeamawaykey($query, $con);
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        $this->primarykey = null;
        $this->name = null;
        $this->code = null;
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
            if ($this->collEventss) {
                foreach ($this->collEventss as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collLineupss) {
                foreach ($this->collLineupss as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collMatchessRelatedByTeamhomekey) {
                foreach ($this->collMatchessRelatedByTeamhomekey as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collMatchessRelatedByTeamawaykey) {
                foreach ($this->collMatchessRelatedByTeamawaykey as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collEventss = null;
        $this->collLineupss = null;
        $this->collMatchessRelatedByTeamhomekey = null;
        $this->collMatchessRelatedByTeamawaykey = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(TeamsTableMap::DEFAULT_STRING_FORMAT);
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
