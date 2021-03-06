<?php

namespace Base;

use \Playercupmatches as ChildPlayercupmatches;
use \PlayercupmatchesQuery as ChildPlayercupmatchesQuery;
use \Playerdivisionmatches as ChildPlayerdivisionmatches;
use \PlayerdivisionmatchesQuery as ChildPlayerdivisionmatchesQuery;
use \Playerdivisionranking as ChildPlayerdivisionranking;
use \PlayerdivisionrankingQuery as ChildPlayerdivisionrankingQuery;
use \Seasons as ChildSeasons;
use \SeasonsQuery as ChildSeasonsQuery;
use \Exception;
use \PDO;
use Map\SeasonsTableMap;
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
 * Base class that represents a row from the 'seasons' table.
 *
 *
 *
* @package    propel.generator..Base
*/
abstract class Seasons implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\Map\\SeasonsTableMap';


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
     * The value for the order field.
     * @var        int
     */
    protected $order;

    /**
     * The value for the competitionkey field.
     * @var        int
     */
    protected $competitionkey;

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
     * @var        ObjectCollection|ChildPlayerdivisionranking[] Collection to store aggregation of ChildPlayerdivisionranking objects.
     */
    protected $collPlayerdivisionrankings;
    protected $collPlayerdivisionrankingsPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

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
     * @var ObjectCollection|ChildPlayerdivisionranking[]
     */
    protected $playerdivisionrankingsScheduledForDeletion = null;

    /**
     * Initializes internal state of Base\Seasons object.
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
     * Compares this with another <code>Seasons</code> instance.  If
     * <code>obj</code> is an instance of <code>Seasons</code>, delegates to
     * <code>equals(Seasons)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|Seasons The current object, for fluid interface
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
    public function getSeasonPK()
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
     * Get the [order] column value.
     *
     * @return int
     */
    public function getOrder()
    {
        return $this->order;
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
     * Set the value of [primarykey] column.
     *
     * @param int $v new value
     * @return $this|\Seasons The current object (for fluent API support)
     */
    public function setSeasonPK($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->primarykey !== $v) {
            $this->primarykey = $v;
            $this->modifiedColumns[SeasonsTableMap::COL_PRIMARYKEY] = true;
        }

        return $this;
    } // setSeasonPK()

    /**
     * Set the value of [description] column.
     *
     * @param string $v new value
     * @return $this|\Seasons The current object (for fluent API support)
     */
    public function setDescription($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->description !== $v) {
            $this->description = $v;
            $this->modifiedColumns[SeasonsTableMap::COL_DESCRIPTION] = true;
        }

        return $this;
    } // setDescription()

    /**
     * Set the value of [code] column.
     *
     * @param string $v new value
     * @return $this|\Seasons The current object (for fluent API support)
     */
    public function setCode($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->code !== $v) {
            $this->code = $v;
            $this->modifiedColumns[SeasonsTableMap::COL_CODE] = true;
        }

        return $this;
    } // setCode()

    /**
     * Set the value of [order] column.
     *
     * @param int $v new value
     * @return $this|\Seasons The current object (for fluent API support)
     */
    public function setOrder($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->order !== $v) {
            $this->order = $v;
            $this->modifiedColumns[SeasonsTableMap::COL_ORDER] = true;
        }

        return $this;
    } // setOrder()

    /**
     * Set the value of [competitionkey] column.
     *
     * @param int $v new value
     * @return $this|\Seasons The current object (for fluent API support)
     */
    public function setCompetitionkey($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->competitionkey !== $v) {
            $this->competitionkey = $v;
            $this->modifiedColumns[SeasonsTableMap::COL_COMPETITIONKEY] = true;
        }

        return $this;
    } // setCompetitionkey()

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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : SeasonsTableMap::translateFieldName('SeasonPK', TableMap::TYPE_PHPNAME, $indexType)];
            $this->primarykey = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : SeasonsTableMap::translateFieldName('Description', TableMap::TYPE_PHPNAME, $indexType)];
            $this->description = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : SeasonsTableMap::translateFieldName('Code', TableMap::TYPE_PHPNAME, $indexType)];
            $this->code = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : SeasonsTableMap::translateFieldName('Order', TableMap::TYPE_PHPNAME, $indexType)];
            $this->order = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : SeasonsTableMap::translateFieldName('Competitionkey', TableMap::TYPE_PHPNAME, $indexType)];
            $this->competitionkey = (null !== $col) ? (int) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 5; // 5 = SeasonsTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Seasons'), 0, $e);
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
            $con = Propel::getServiceContainer()->getReadConnection(SeasonsTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildSeasonsQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collPlayercupmatchess = null;

            $this->collPlayerdivisionmatchess = null;

            $this->collPlayerdivisionrankings = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Seasons::setDeleted()
     * @see Seasons::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(SeasonsTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildSeasonsQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(SeasonsTableMap::DATABASE_NAME);
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
                SeasonsTableMap::addInstanceToPool($this);
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

            if ($this->playerdivisionrankingsScheduledForDeletion !== null) {
                if (!$this->playerdivisionrankingsScheduledForDeletion->isEmpty()) {
                    \PlayerdivisionrankingQuery::create()
                        ->filterByPrimaryKeys($this->playerdivisionrankingsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->playerdivisionrankingsScheduledForDeletion = null;
                }
            }

            if ($this->collPlayerdivisionrankings !== null) {
                foreach ($this->collPlayerdivisionrankings as $referrerFK) {
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

        $this->modifiedColumns[SeasonsTableMap::COL_PRIMARYKEY] = true;
        if (null !== $this->primarykey) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . SeasonsTableMap::COL_PRIMARYKEY . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(SeasonsTableMap::COL_PRIMARYKEY)) {
            $modifiedColumns[':p' . $index++]  = 'PrimaryKey';
        }
        if ($this->isColumnModified(SeasonsTableMap::COL_DESCRIPTION)) {
            $modifiedColumns[':p' . $index++]  = 'Description';
        }
        if ($this->isColumnModified(SeasonsTableMap::COL_CODE)) {
            $modifiedColumns[':p' . $index++]  = 'Code';
        }
        if ($this->isColumnModified(SeasonsTableMap::COL_ORDER)) {
            $modifiedColumns[':p' . $index++]  = 'Order';
        }
        if ($this->isColumnModified(SeasonsTableMap::COL_COMPETITIONKEY)) {
            $modifiedColumns[':p' . $index++]  = 'CompetitionKey';
        }

        $sql = sprintf(
            'INSERT INTO seasons (%s) VALUES (%s)',
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
                    case 'Order':
                        $stmt->bindValue($identifier, $this->order, PDO::PARAM_INT);
                        break;
                    case 'CompetitionKey':
                        $stmt->bindValue($identifier, $this->competitionkey, PDO::PARAM_INT);
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
        $this->setSeasonPK($pk);

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
        $pos = SeasonsTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getSeasonPK();
                break;
            case 1:
                return $this->getDescription();
                break;
            case 2:
                return $this->getCode();
                break;
            case 3:
                return $this->getOrder();
                break;
            case 4:
                return $this->getCompetitionkey();
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

        if (isset($alreadyDumpedObjects['Seasons'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Seasons'][$this->hashCode()] = true;
        $keys = SeasonsTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getSeasonPK(),
            $keys[1] => $this->getDescription(),
            $keys[2] => $this->getCode(),
            $keys[3] => $this->getOrder(),
            $keys[4] => $this->getCompetitionkey(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
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
            if (null !== $this->collPlayerdivisionrankings) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'playerdivisionrankings';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'playerdivisionrankings';
                        break;
                    default:
                        $key = 'Playerdivisionrankings';
                }

                $result[$key] = $this->collPlayerdivisionrankings->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
     * @return $this|\Seasons
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = SeasonsTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\Seasons
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setSeasonPK($value);
                break;
            case 1:
                $this->setDescription($value);
                break;
            case 2:
                $this->setCode($value);
                break;
            case 3:
                $this->setOrder($value);
                break;
            case 4:
                $this->setCompetitionkey($value);
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
        $keys = SeasonsTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setSeasonPK($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setDescription($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setCode($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setOrder($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setCompetitionkey($arr[$keys[4]]);
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
     * @return $this|\Seasons The current object, for fluid interface
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
        $criteria = new Criteria(SeasonsTableMap::DATABASE_NAME);

        if ($this->isColumnModified(SeasonsTableMap::COL_PRIMARYKEY)) {
            $criteria->add(SeasonsTableMap::COL_PRIMARYKEY, $this->primarykey);
        }
        if ($this->isColumnModified(SeasonsTableMap::COL_DESCRIPTION)) {
            $criteria->add(SeasonsTableMap::COL_DESCRIPTION, $this->description);
        }
        if ($this->isColumnModified(SeasonsTableMap::COL_CODE)) {
            $criteria->add(SeasonsTableMap::COL_CODE, $this->code);
        }
        if ($this->isColumnModified(SeasonsTableMap::COL_ORDER)) {
            $criteria->add(SeasonsTableMap::COL_ORDER, $this->order);
        }
        if ($this->isColumnModified(SeasonsTableMap::COL_COMPETITIONKEY)) {
            $criteria->add(SeasonsTableMap::COL_COMPETITIONKEY, $this->competitionkey);
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
        $criteria = ChildSeasonsQuery::create();
        $criteria->add(SeasonsTableMap::COL_PRIMARYKEY, $this->primarykey);

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
        $validPk = null !== $this->getSeasonPK();

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
        return $this->getSeasonPK();
    }

    /**
     * Generic method to set the primary key (primarykey column).
     *
     * @param       int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setSeasonPK($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {
        return null === $this->getSeasonPK();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of \Seasons (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setDescription($this->getDescription());
        $copyObj->setCode($this->getCode());
        $copyObj->setOrder($this->getOrder());
        $copyObj->setCompetitionkey($this->getCompetitionkey());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

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

            foreach ($this->getPlayerdivisionrankings() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPlayerdivisionranking($relObj->copy($deepCopy));
                }
            }

        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setSeasonPK(NULL); // this is a auto-increment column, so set to default value
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
     * @return \Seasons Clone of current object.
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
        if ('Playercupmatches' == $relationName) {
            return $this->initPlayercupmatchess();
        }
        if ('Playerdivisionmatches' == $relationName) {
            return $this->initPlayerdivisionmatchess();
        }
        if ('Playerdivisionranking' == $relationName) {
            return $this->initPlayerdivisionrankings();
        }
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
     * If this ChildSeasons is new, it will return
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
                    ->filterByCupMatchesSeason($this)
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
     * @return $this|ChildSeasons The current object (for fluent API support)
     */
    public function setPlayercupmatchess(Collection $playercupmatchess, ConnectionInterface $con = null)
    {
        /** @var ChildPlayercupmatches[] $playercupmatchessToDelete */
        $playercupmatchessToDelete = $this->getPlayercupmatchess(new Criteria(), $con)->diff($playercupmatchess);


        $this->playercupmatchessScheduledForDeletion = $playercupmatchessToDelete;

        foreach ($playercupmatchessToDelete as $playercupmatchesRemoved) {
            $playercupmatchesRemoved->setCupMatchesSeason(null);
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
                ->filterByCupMatchesSeason($this)
                ->count($con);
        }

        return count($this->collPlayercupmatchess);
    }

    /**
     * Method called to associate a ChildPlayercupmatches object to this object
     * through the ChildPlayercupmatches foreign key attribute.
     *
     * @param  ChildPlayercupmatches $l ChildPlayercupmatches
     * @return $this|\Seasons The current object (for fluent API support)
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
        $playercupmatches->setCupMatchesSeason($this);
    }

    /**
     * @param  ChildPlayercupmatches $playercupmatches The ChildPlayercupmatches object to remove.
     * @return $this|ChildSeasons The current object (for fluent API support)
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
            $playercupmatches->setCupMatchesSeason(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Seasons is new, it will return
     * an empty collection; or if this Seasons has previously
     * been saved, it will retrieve related Playercupmatchess from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Seasons.
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
     * Otherwise if this Seasons is new, it will return
     * an empty collection; or if this Seasons has previously
     * been saved, it will retrieve related Playercupmatchess from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Seasons.
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
     * Otherwise if this Seasons is new, it will return
     * an empty collection; or if this Seasons has previously
     * been saved, it will retrieve related Playercupmatchess from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Seasons.
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
     * Otherwise if this Seasons is new, it will return
     * an empty collection; or if this Seasons has previously
     * been saved, it will retrieve related Playercupmatchess from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Seasons.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildPlayercupmatches[] List of ChildPlayercupmatches objects
     */
    public function getPlayercupmatchessJoinCupMatchesGroup(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildPlayercupmatchesQuery::create(null, $criteria);
        $query->joinWith('CupMatchesGroup', $joinBehavior);

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
     * If this ChildSeasons is new, it will return
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
                    ->filterByDivisionMatchesSeason($this)
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
     * @return $this|ChildSeasons The current object (for fluent API support)
     */
    public function setPlayerdivisionmatchess(Collection $playerdivisionmatchess, ConnectionInterface $con = null)
    {
        /** @var ChildPlayerdivisionmatches[] $playerdivisionmatchessToDelete */
        $playerdivisionmatchessToDelete = $this->getPlayerdivisionmatchess(new Criteria(), $con)->diff($playerdivisionmatchess);


        $this->playerdivisionmatchessScheduledForDeletion = $playerdivisionmatchessToDelete;

        foreach ($playerdivisionmatchessToDelete as $playerdivisionmatchesRemoved) {
            $playerdivisionmatchesRemoved->setDivisionMatchesSeason(null);
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
                ->filterByDivisionMatchesSeason($this)
                ->count($con);
        }

        return count($this->collPlayerdivisionmatchess);
    }

    /**
     * Method called to associate a ChildPlayerdivisionmatches object to this object
     * through the ChildPlayerdivisionmatches foreign key attribute.
     *
     * @param  ChildPlayerdivisionmatches $l ChildPlayerdivisionmatches
     * @return $this|\Seasons The current object (for fluent API support)
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
        $playerdivisionmatches->setDivisionMatchesSeason($this);
    }

    /**
     * @param  ChildPlayerdivisionmatches $playerdivisionmatches The ChildPlayerdivisionmatches object to remove.
     * @return $this|ChildSeasons The current object (for fluent API support)
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
            $playerdivisionmatches->setDivisionMatchesSeason(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Seasons is new, it will return
     * an empty collection; or if this Seasons has previously
     * been saved, it will retrieve related Playerdivisionmatchess from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Seasons.
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
     * Otherwise if this Seasons is new, it will return
     * an empty collection; or if this Seasons has previously
     * been saved, it will retrieve related Playerdivisionmatchess from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Seasons.
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
     * Otherwise if this Seasons is new, it will return
     * an empty collection; or if this Seasons has previously
     * been saved, it will retrieve related Playerdivisionmatchess from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Seasons.
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
     * Otherwise if this Seasons is new, it will return
     * an empty collection; or if this Seasons has previously
     * been saved, it will retrieve related Playerdivisionmatchess from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Seasons.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildPlayerdivisionmatches[] List of ChildPlayerdivisionmatches objects
     */
    public function getPlayerdivisionmatchessJoinDivisionMatchesGroup(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildPlayerdivisionmatchesQuery::create(null, $criteria);
        $query->joinWith('DivisionMatchesGroup', $joinBehavior);

        return $this->getPlayerdivisionmatchess($query, $con);
    }

    /**
     * Clears out the collPlayerdivisionrankings collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addPlayerdivisionrankings()
     */
    public function clearPlayerdivisionrankings()
    {
        $this->collPlayerdivisionrankings = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collPlayerdivisionrankings collection loaded partially.
     */
    public function resetPartialPlayerdivisionrankings($v = true)
    {
        $this->collPlayerdivisionrankingsPartial = $v;
    }

    /**
     * Initializes the collPlayerdivisionrankings collection.
     *
     * By default this just sets the collPlayerdivisionrankings collection to an empty array (like clearcollPlayerdivisionrankings());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPlayerdivisionrankings($overrideExisting = true)
    {
        if (null !== $this->collPlayerdivisionrankings && !$overrideExisting) {
            return;
        }
        $this->collPlayerdivisionrankings = new ObjectCollection();
        $this->collPlayerdivisionrankings->setModel('\Playerdivisionranking');
    }

    /**
     * Gets an array of ChildPlayerdivisionranking objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildSeasons is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildPlayerdivisionranking[] List of ChildPlayerdivisionranking objects
     * @throws PropelException
     */
    public function getPlayerdivisionrankings(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collPlayerdivisionrankingsPartial && !$this->isNew();
        if (null === $this->collPlayerdivisionrankings || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPlayerdivisionrankings) {
                // return empty collection
                $this->initPlayerdivisionrankings();
            } else {
                $collPlayerdivisionrankings = ChildPlayerdivisionrankingQuery::create(null, $criteria)
                    ->filterByDivisionRankingSeason($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collPlayerdivisionrankingsPartial && count($collPlayerdivisionrankings)) {
                        $this->initPlayerdivisionrankings(false);

                        foreach ($collPlayerdivisionrankings as $obj) {
                            if (false == $this->collPlayerdivisionrankings->contains($obj)) {
                                $this->collPlayerdivisionrankings->append($obj);
                            }
                        }

                        $this->collPlayerdivisionrankingsPartial = true;
                    }

                    return $collPlayerdivisionrankings;
                }

                if ($partial && $this->collPlayerdivisionrankings) {
                    foreach ($this->collPlayerdivisionrankings as $obj) {
                        if ($obj->isNew()) {
                            $collPlayerdivisionrankings[] = $obj;
                        }
                    }
                }

                $this->collPlayerdivisionrankings = $collPlayerdivisionrankings;
                $this->collPlayerdivisionrankingsPartial = false;
            }
        }

        return $this->collPlayerdivisionrankings;
    }

    /**
     * Sets a collection of ChildPlayerdivisionranking objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $playerdivisionrankings A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildSeasons The current object (for fluent API support)
     */
    public function setPlayerdivisionrankings(Collection $playerdivisionrankings, ConnectionInterface $con = null)
    {
        /** @var ChildPlayerdivisionranking[] $playerdivisionrankingsToDelete */
        $playerdivisionrankingsToDelete = $this->getPlayerdivisionrankings(new Criteria(), $con)->diff($playerdivisionrankings);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->playerdivisionrankingsScheduledForDeletion = clone $playerdivisionrankingsToDelete;

        foreach ($playerdivisionrankingsToDelete as $playerdivisionrankingRemoved) {
            $playerdivisionrankingRemoved->setDivisionRankingSeason(null);
        }

        $this->collPlayerdivisionrankings = null;
        foreach ($playerdivisionrankings as $playerdivisionranking) {
            $this->addPlayerdivisionranking($playerdivisionranking);
        }

        $this->collPlayerdivisionrankings = $playerdivisionrankings;
        $this->collPlayerdivisionrankingsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Playerdivisionranking objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Playerdivisionranking objects.
     * @throws PropelException
     */
    public function countPlayerdivisionrankings(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collPlayerdivisionrankingsPartial && !$this->isNew();
        if (null === $this->collPlayerdivisionrankings || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPlayerdivisionrankings) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPlayerdivisionrankings());
            }

            $query = ChildPlayerdivisionrankingQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByDivisionRankingSeason($this)
                ->count($con);
        }

        return count($this->collPlayerdivisionrankings);
    }

    /**
     * Method called to associate a ChildPlayerdivisionranking object to this object
     * through the ChildPlayerdivisionranking foreign key attribute.
     *
     * @param  ChildPlayerdivisionranking $l ChildPlayerdivisionranking
     * @return $this|\Seasons The current object (for fluent API support)
     */
    public function addPlayerdivisionranking(ChildPlayerdivisionranking $l)
    {
        if ($this->collPlayerdivisionrankings === null) {
            $this->initPlayerdivisionrankings();
            $this->collPlayerdivisionrankingsPartial = true;
        }

        if (!$this->collPlayerdivisionrankings->contains($l)) {
            $this->doAddPlayerdivisionranking($l);
        }

        return $this;
    }

    /**
     * @param ChildPlayerdivisionranking $playerdivisionranking The ChildPlayerdivisionranking object to add.
     */
    protected function doAddPlayerdivisionranking(ChildPlayerdivisionranking $playerdivisionranking)
    {
        $this->collPlayerdivisionrankings[]= $playerdivisionranking;
        $playerdivisionranking->setDivisionRankingSeason($this);
    }

    /**
     * @param  ChildPlayerdivisionranking $playerdivisionranking The ChildPlayerdivisionranking object to remove.
     * @return $this|ChildSeasons The current object (for fluent API support)
     */
    public function removePlayerdivisionranking(ChildPlayerdivisionranking $playerdivisionranking)
    {
        if ($this->getPlayerdivisionrankings()->contains($playerdivisionranking)) {
            $pos = $this->collPlayerdivisionrankings->search($playerdivisionranking);
            $this->collPlayerdivisionrankings->remove($pos);
            if (null === $this->playerdivisionrankingsScheduledForDeletion) {
                $this->playerdivisionrankingsScheduledForDeletion = clone $this->collPlayerdivisionrankings;
                $this->playerdivisionrankingsScheduledForDeletion->clear();
            }
            $this->playerdivisionrankingsScheduledForDeletion[]= clone $playerdivisionranking;
            $playerdivisionranking->setDivisionRankingSeason(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Seasons is new, it will return
     * an empty collection; or if this Seasons has previously
     * been saved, it will retrieve related Playerdivisionrankings from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Seasons.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildPlayerdivisionranking[] List of ChildPlayerdivisionranking objects
     */
    public function getPlayerdivisionrankingsJoinDivisionRankingPlayer(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildPlayerdivisionrankingQuery::create(null, $criteria);
        $query->joinWith('DivisionRankingPlayer', $joinBehavior);

        return $this->getPlayerdivisionrankings($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Seasons is new, it will return
     * an empty collection; or if this Seasons has previously
     * been saved, it will retrieve related Playerdivisionrankings from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Seasons.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildPlayerdivisionranking[] List of ChildPlayerdivisionranking objects
     */
    public function getPlayerdivisionrankingsJoinDivisionRankingDivision(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildPlayerdivisionrankingQuery::create(null, $criteria);
        $query->joinWith('DivisionRankingDivision', $joinBehavior);

        return $this->getPlayerdivisionrankings($query, $con);
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        $this->primarykey = null;
        $this->description = null;
        $this->code = null;
        $this->order = null;
        $this->competitionkey = null;
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
            if ($this->collPlayerdivisionrankings) {
                foreach ($this->collPlayerdivisionrankings as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collPlayercupmatchess = null;
        $this->collPlayerdivisionmatchess = null;
        $this->collPlayerdivisionrankings = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(SeasonsTableMap::DEFAULT_STRING_FORMAT);
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
