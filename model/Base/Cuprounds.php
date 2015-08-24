<?php

namespace Base;

use \Cuprounds as ChildCuprounds;
use \CuproundsQuery as ChildCuproundsQuery;
use \Playercupmatches as ChildPlayercupmatches;
use \PlayercupmatchesQuery as ChildPlayercupmatchesQuery;
use \Exception;
use \PDO;
use Map\CuproundsTableMap;
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
 * Base class that represents a row from the 'cuprounds' table.
 *
 *
 *
* @package    propel.generator..Base
*/
abstract class Cuprounds implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\Map\\CuproundsTableMap';


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
     * The value for the nextroundkey field.
     * @var        int
     */
    protected $nextroundkey;

    /**
     * The value for the previousroundkey field.
     * @var        int
     */
    protected $previousroundkey;

    /**
     * @var        ChildCuprounds
     */
    protected $aNextRound;

    /**
     * @var        ChildCuprounds
     */
    protected $aPreviousRound;

    /**
     * @var        ObjectCollection|ChildCuprounds[] Collection to store aggregation of ChildCuprounds objects.
     */
    protected $collCuproundssRelatedByCupRoundPK0;
    protected $collCuproundssRelatedByCupRoundPK0Partial;

    /**
     * @var        ObjectCollection|ChildCuprounds[] Collection to store aggregation of ChildCuprounds objects.
     */
    protected $collCuproundssRelatedByCupRoundPK1;
    protected $collCuproundssRelatedByCupRoundPK1Partial;

    /**
     * @var        ObjectCollection|ChildPlayercupmatches[] Collection to store aggregation of ChildPlayercupmatches objects.
     */
    protected $collPlayercupmatchess;
    protected $collPlayercupmatchessPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildCuprounds[]
     */
    protected $cuproundssRelatedByCupRoundPK0ScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildCuprounds[]
     */
    protected $cuproundssRelatedByCupRoundPK1ScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildPlayercupmatches[]
     */
    protected $playercupmatchessScheduledForDeletion = null;

    /**
     * Initializes internal state of Base\Cuprounds object.
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
     * Compares this with another <code>Cuprounds</code> instance.  If
     * <code>obj</code> is an instance of <code>Cuprounds</code>, delegates to
     * <code>equals(Cuprounds)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|Cuprounds The current object, for fluid interface
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
    public function getCupRoundPK()
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
     * Get the [nextroundkey] column value.
     *
     * @return int
     */
    public function getNextroundkey()
    {
        return $this->nextroundkey;
    }

    /**
     * Get the [previousroundkey] column value.
     *
     * @return int
     */
    public function getPreviousroundkey()
    {
        return $this->previousroundkey;
    }

    /**
     * Set the value of [primarykey] column.
     *
     * @param int $v new value
     * @return $this|\Cuprounds The current object (for fluent API support)
     */
    public function setCupRoundPK($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->primarykey !== $v) {
            $this->primarykey = $v;
            $this->modifiedColumns[CuproundsTableMap::COL_PRIMARYKEY] = true;
        }

        return $this;
    } // setCupRoundPK()

    /**
     * Set the value of [description] column.
     *
     * @param string $v new value
     * @return $this|\Cuprounds The current object (for fluent API support)
     */
    public function setDescription($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->description !== $v) {
            $this->description = $v;
            $this->modifiedColumns[CuproundsTableMap::COL_DESCRIPTION] = true;
        }

        return $this;
    } // setDescription()

    /**
     * Set the value of [code] column.
     *
     * @param string $v new value
     * @return $this|\Cuprounds The current object (for fluent API support)
     */
    public function setCode($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->code !== $v) {
            $this->code = $v;
            $this->modifiedColumns[CuproundsTableMap::COL_CODE] = true;
        }

        return $this;
    } // setCode()

    /**
     * Set the value of [nextroundkey] column.
     *
     * @param int $v new value
     * @return $this|\Cuprounds The current object (for fluent API support)
     */
    public function setNextroundkey($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->nextroundkey !== $v) {
            $this->nextroundkey = $v;
            $this->modifiedColumns[CuproundsTableMap::COL_NEXTROUNDKEY] = true;
        }

        if ($this->aNextRound !== null && $this->aNextRound->getCupRoundPK() !== $v) {
            $this->aNextRound = null;
        }

        return $this;
    } // setNextroundkey()

    /**
     * Set the value of [previousroundkey] column.
     *
     * @param int $v new value
     * @return $this|\Cuprounds The current object (for fluent API support)
     */
    public function setPreviousroundkey($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->previousroundkey !== $v) {
            $this->previousroundkey = $v;
            $this->modifiedColumns[CuproundsTableMap::COL_PREVIOUSROUNDKEY] = true;
        }

        if ($this->aPreviousRound !== null && $this->aPreviousRound->getCupRoundPK() !== $v) {
            $this->aPreviousRound = null;
        }

        return $this;
    } // setPreviousroundkey()

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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : CuproundsTableMap::translateFieldName('CupRoundPK', TableMap::TYPE_PHPNAME, $indexType)];
            $this->primarykey = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : CuproundsTableMap::translateFieldName('Description', TableMap::TYPE_PHPNAME, $indexType)];
            $this->description = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : CuproundsTableMap::translateFieldName('Code', TableMap::TYPE_PHPNAME, $indexType)];
            $this->code = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : CuproundsTableMap::translateFieldName('Nextroundkey', TableMap::TYPE_PHPNAME, $indexType)];
            $this->nextroundkey = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : CuproundsTableMap::translateFieldName('Previousroundkey', TableMap::TYPE_PHPNAME, $indexType)];
            $this->previousroundkey = (null !== $col) ? (int) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 5; // 5 = CuproundsTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Cuprounds'), 0, $e);
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
        if ($this->aNextRound !== null && $this->nextroundkey !== $this->aNextRound->getCupRoundPK()) {
            $this->aNextRound = null;
        }
        if ($this->aPreviousRound !== null && $this->previousroundkey !== $this->aPreviousRound->getCupRoundPK()) {
            $this->aPreviousRound = null;
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
            $con = Propel::getServiceContainer()->getReadConnection(CuproundsTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildCuproundsQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aNextRound = null;
            $this->aPreviousRound = null;
            $this->collCuproundssRelatedByCupRoundPK0 = null;

            $this->collCuproundssRelatedByCupRoundPK1 = null;

            $this->collPlayercupmatchess = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Cuprounds::setDeleted()
     * @see Cuprounds::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(CuproundsTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildCuproundsQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(CuproundsTableMap::DATABASE_NAME);
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
                CuproundsTableMap::addInstanceToPool($this);
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

            if ($this->aNextRound !== null) {
                if ($this->aNextRound->isModified() || $this->aNextRound->isNew()) {
                    $affectedRows += $this->aNextRound->save($con);
                }
                $this->setNextRound($this->aNextRound);
            }

            if ($this->aPreviousRound !== null) {
                if ($this->aPreviousRound->isModified() || $this->aPreviousRound->isNew()) {
                    $affectedRows += $this->aPreviousRound->save($con);
                }
                $this->setPreviousRound($this->aPreviousRound);
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

            if ($this->cuproundssRelatedByCupRoundPK0ScheduledForDeletion !== null) {
                if (!$this->cuproundssRelatedByCupRoundPK0ScheduledForDeletion->isEmpty()) {
                    foreach ($this->cuproundssRelatedByCupRoundPK0ScheduledForDeletion as $cuproundsRelatedByCupRoundPK0) {
                        // need to save related object because we set the relation to null
                        $cuproundsRelatedByCupRoundPK0->save($con);
                    }
                    $this->cuproundssRelatedByCupRoundPK0ScheduledForDeletion = null;
                }
            }

            if ($this->collCuproundssRelatedByCupRoundPK0 !== null) {
                foreach ($this->collCuproundssRelatedByCupRoundPK0 as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->cuproundssRelatedByCupRoundPK1ScheduledForDeletion !== null) {
                if (!$this->cuproundssRelatedByCupRoundPK1ScheduledForDeletion->isEmpty()) {
                    foreach ($this->cuproundssRelatedByCupRoundPK1ScheduledForDeletion as $cuproundsRelatedByCupRoundPK1) {
                        // need to save related object because we set the relation to null
                        $cuproundsRelatedByCupRoundPK1->save($con);
                    }
                    $this->cuproundssRelatedByCupRoundPK1ScheduledForDeletion = null;
                }
            }

            if ($this->collCuproundssRelatedByCupRoundPK1 !== null) {
                foreach ($this->collCuproundssRelatedByCupRoundPK1 as $referrerFK) {
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

        $this->modifiedColumns[CuproundsTableMap::COL_PRIMARYKEY] = true;
        if (null !== $this->primarykey) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . CuproundsTableMap::COL_PRIMARYKEY . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(CuproundsTableMap::COL_PRIMARYKEY)) {
            $modifiedColumns[':p' . $index++]  = 'PrimaryKey';
        }
        if ($this->isColumnModified(CuproundsTableMap::COL_DESCRIPTION)) {
            $modifiedColumns[':p' . $index++]  = 'Description';
        }
        if ($this->isColumnModified(CuproundsTableMap::COL_CODE)) {
            $modifiedColumns[':p' . $index++]  = 'Code';
        }
        if ($this->isColumnModified(CuproundsTableMap::COL_NEXTROUNDKEY)) {
            $modifiedColumns[':p' . $index++]  = 'NextRoundKey';
        }
        if ($this->isColumnModified(CuproundsTableMap::COL_PREVIOUSROUNDKEY)) {
            $modifiedColumns[':p' . $index++]  = 'PreviousRoundKey';
        }

        $sql = sprintf(
            'INSERT INTO cuprounds (%s) VALUES (%s)',
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
                    case 'NextRoundKey':
                        $stmt->bindValue($identifier, $this->nextroundkey, PDO::PARAM_INT);
                        break;
                    case 'PreviousRoundKey':
                        $stmt->bindValue($identifier, $this->previousroundkey, PDO::PARAM_INT);
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
        $this->setCupRoundPK($pk);

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
        $pos = CuproundsTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getCupRoundPK();
                break;
            case 1:
                return $this->getDescription();
                break;
            case 2:
                return $this->getCode();
                break;
            case 3:
                return $this->getNextroundkey();
                break;
            case 4:
                return $this->getPreviousroundkey();
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

        if (isset($alreadyDumpedObjects['Cuprounds'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Cuprounds'][$this->hashCode()] = true;
        $keys = CuproundsTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getCupRoundPK(),
            $keys[1] => $this->getDescription(),
            $keys[2] => $this->getCode(),
            $keys[3] => $this->getNextroundkey(),
            $keys[4] => $this->getPreviousroundkey(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aNextRound) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'cuprounds';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'cuprounds';
                        break;
                    default:
                        $key = 'Cuprounds';
                }

                $result[$key] = $this->aNextRound->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aPreviousRound) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'cuprounds';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'cuprounds';
                        break;
                    default:
                        $key = 'Cuprounds';
                }

                $result[$key] = $this->aPreviousRound->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collCuproundssRelatedByCupRoundPK0) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'cuproundss';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'cuproundss';
                        break;
                    default:
                        $key = 'Cuproundss';
                }

                $result[$key] = $this->collCuproundssRelatedByCupRoundPK0->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collCuproundssRelatedByCupRoundPK1) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'cuproundss';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'cuproundss';
                        break;
                    default:
                        $key = 'Cuproundss';
                }

                $result[$key] = $this->collCuproundssRelatedByCupRoundPK1->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
     * @return $this|\Cuprounds
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = CuproundsTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\Cuprounds
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setCupRoundPK($value);
                break;
            case 1:
                $this->setDescription($value);
                break;
            case 2:
                $this->setCode($value);
                break;
            case 3:
                $this->setNextroundkey($value);
                break;
            case 4:
                $this->setPreviousroundkey($value);
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
        $keys = CuproundsTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setCupRoundPK($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setDescription($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setCode($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setNextroundkey($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setPreviousroundkey($arr[$keys[4]]);
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
     * @return $this|\Cuprounds The current object, for fluid interface
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
        $criteria = new Criteria(CuproundsTableMap::DATABASE_NAME);

        if ($this->isColumnModified(CuproundsTableMap::COL_PRIMARYKEY)) {
            $criteria->add(CuproundsTableMap::COL_PRIMARYKEY, $this->primarykey);
        }
        if ($this->isColumnModified(CuproundsTableMap::COL_DESCRIPTION)) {
            $criteria->add(CuproundsTableMap::COL_DESCRIPTION, $this->description);
        }
        if ($this->isColumnModified(CuproundsTableMap::COL_CODE)) {
            $criteria->add(CuproundsTableMap::COL_CODE, $this->code);
        }
        if ($this->isColumnModified(CuproundsTableMap::COL_NEXTROUNDKEY)) {
            $criteria->add(CuproundsTableMap::COL_NEXTROUNDKEY, $this->nextroundkey);
        }
        if ($this->isColumnModified(CuproundsTableMap::COL_PREVIOUSROUNDKEY)) {
            $criteria->add(CuproundsTableMap::COL_PREVIOUSROUNDKEY, $this->previousroundkey);
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
        $criteria = ChildCuproundsQuery::create();
        $criteria->add(CuproundsTableMap::COL_PRIMARYKEY, $this->primarykey);

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
        $validPk = null !== $this->getCupRoundPK();

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
        return $this->getCupRoundPK();
    }

    /**
     * Generic method to set the primary key (primarykey column).
     *
     * @param       int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setCupRoundPK($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {
        return null === $this->getCupRoundPK();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of \Cuprounds (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setDescription($this->getDescription());
        $copyObj->setCode($this->getCode());
        $copyObj->setNextroundkey($this->getNextroundkey());
        $copyObj->setPreviousroundkey($this->getPreviousroundkey());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getCuproundssRelatedByCupRoundPK0() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addCuproundsRelatedByCupRoundPK0($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getCuproundssRelatedByCupRoundPK1() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addCuproundsRelatedByCupRoundPK1($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPlayercupmatchess() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPlayercupmatches($relObj->copy($deepCopy));
                }
            }

        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setCupRoundPK(NULL); // this is a auto-increment column, so set to default value
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
     * @return \Cuprounds Clone of current object.
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
     * Declares an association between this object and a ChildCuprounds object.
     *
     * @param  ChildCuprounds $v
     * @return $this|\Cuprounds The current object (for fluent API support)
     * @throws PropelException
     */
    public function setNextRound(ChildCuprounds $v = null)
    {
        if ($v === null) {
            $this->setNextroundkey(NULL);
        } else {
            $this->setNextroundkey($v->getCupRoundPK());
        }

        $this->aNextRound = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildCuprounds object, it will not be re-added.
        if ($v !== null) {
            $v->addCuproundsRelatedByCupRoundPK0($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildCuprounds object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildCuprounds The associated ChildCuprounds object.
     * @throws PropelException
     */
    public function getNextRound(ConnectionInterface $con = null)
    {
        if ($this->aNextRound === null && ($this->nextroundkey !== null)) {
            $this->aNextRound = ChildCuproundsQuery::create()->findPk($this->nextroundkey, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aNextRound->addCuproundssRelatedByCupRoundPK0($this);
             */
        }

        return $this->aNextRound;
    }

    /**
     * Declares an association between this object and a ChildCuprounds object.
     *
     * @param  ChildCuprounds $v
     * @return $this|\Cuprounds The current object (for fluent API support)
     * @throws PropelException
     */
    public function setPreviousRound(ChildCuprounds $v = null)
    {
        if ($v === null) {
            $this->setPreviousroundkey(NULL);
        } else {
            $this->setPreviousroundkey($v->getCupRoundPK());
        }

        $this->aPreviousRound = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildCuprounds object, it will not be re-added.
        if ($v !== null) {
            $v->addCuproundsRelatedByCupRoundPK1($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildCuprounds object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildCuprounds The associated ChildCuprounds object.
     * @throws PropelException
     */
    public function getPreviousRound(ConnectionInterface $con = null)
    {
        if ($this->aPreviousRound === null && ($this->previousroundkey !== null)) {
            $this->aPreviousRound = ChildCuproundsQuery::create()->findPk($this->previousroundkey, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aPreviousRound->addCuproundssRelatedByCupRoundPK1($this);
             */
        }

        return $this->aPreviousRound;
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
        if ('CuproundsRelatedByCupRoundPK0' == $relationName) {
            return $this->initCuproundssRelatedByCupRoundPK0();
        }
        if ('CuproundsRelatedByCupRoundPK1' == $relationName) {
            return $this->initCuproundssRelatedByCupRoundPK1();
        }
        if ('Playercupmatches' == $relationName) {
            return $this->initPlayercupmatchess();
        }
    }

    /**
     * Clears out the collCuproundssRelatedByCupRoundPK0 collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addCuproundssRelatedByCupRoundPK0()
     */
    public function clearCuproundssRelatedByCupRoundPK0()
    {
        $this->collCuproundssRelatedByCupRoundPK0 = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collCuproundssRelatedByCupRoundPK0 collection loaded partially.
     */
    public function resetPartialCuproundssRelatedByCupRoundPK0($v = true)
    {
        $this->collCuproundssRelatedByCupRoundPK0Partial = $v;
    }

    /**
     * Initializes the collCuproundssRelatedByCupRoundPK0 collection.
     *
     * By default this just sets the collCuproundssRelatedByCupRoundPK0 collection to an empty array (like clearcollCuproundssRelatedByCupRoundPK0());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initCuproundssRelatedByCupRoundPK0($overrideExisting = true)
    {
        if (null !== $this->collCuproundssRelatedByCupRoundPK0 && !$overrideExisting) {
            return;
        }
        $this->collCuproundssRelatedByCupRoundPK0 = new ObjectCollection();
        $this->collCuproundssRelatedByCupRoundPK0->setModel('\Cuprounds');
    }

    /**
     * Gets an array of ChildCuprounds objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildCuprounds is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildCuprounds[] List of ChildCuprounds objects
     * @throws PropelException
     */
    public function getCuproundssRelatedByCupRoundPK0(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collCuproundssRelatedByCupRoundPK0Partial && !$this->isNew();
        if (null === $this->collCuproundssRelatedByCupRoundPK0 || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collCuproundssRelatedByCupRoundPK0) {
                // return empty collection
                $this->initCuproundssRelatedByCupRoundPK0();
            } else {
                $collCuproundssRelatedByCupRoundPK0 = ChildCuproundsQuery::create(null, $criteria)
                    ->filterByNextRound($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collCuproundssRelatedByCupRoundPK0Partial && count($collCuproundssRelatedByCupRoundPK0)) {
                        $this->initCuproundssRelatedByCupRoundPK0(false);

                        foreach ($collCuproundssRelatedByCupRoundPK0 as $obj) {
                            if (false == $this->collCuproundssRelatedByCupRoundPK0->contains($obj)) {
                                $this->collCuproundssRelatedByCupRoundPK0->append($obj);
                            }
                        }

                        $this->collCuproundssRelatedByCupRoundPK0Partial = true;
                    }

                    return $collCuproundssRelatedByCupRoundPK0;
                }

                if ($partial && $this->collCuproundssRelatedByCupRoundPK0) {
                    foreach ($this->collCuproundssRelatedByCupRoundPK0 as $obj) {
                        if ($obj->isNew()) {
                            $collCuproundssRelatedByCupRoundPK0[] = $obj;
                        }
                    }
                }

                $this->collCuproundssRelatedByCupRoundPK0 = $collCuproundssRelatedByCupRoundPK0;
                $this->collCuproundssRelatedByCupRoundPK0Partial = false;
            }
        }

        return $this->collCuproundssRelatedByCupRoundPK0;
    }

    /**
     * Sets a collection of ChildCuprounds objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $cuproundssRelatedByCupRoundPK0 A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildCuprounds The current object (for fluent API support)
     */
    public function setCuproundssRelatedByCupRoundPK0(Collection $cuproundssRelatedByCupRoundPK0, ConnectionInterface $con = null)
    {
        /** @var ChildCuprounds[] $cuproundssRelatedByCupRoundPK0ToDelete */
        $cuproundssRelatedByCupRoundPK0ToDelete = $this->getCuproundssRelatedByCupRoundPK0(new Criteria(), $con)->diff($cuproundssRelatedByCupRoundPK0);


        $this->cuproundssRelatedByCupRoundPK0ScheduledForDeletion = $cuproundssRelatedByCupRoundPK0ToDelete;

        foreach ($cuproundssRelatedByCupRoundPK0ToDelete as $cuproundsRelatedByCupRoundPK0Removed) {
            $cuproundsRelatedByCupRoundPK0Removed->setNextRound(null);
        }

        $this->collCuproundssRelatedByCupRoundPK0 = null;
        foreach ($cuproundssRelatedByCupRoundPK0 as $cuproundsRelatedByCupRoundPK0) {
            $this->addCuproundsRelatedByCupRoundPK0($cuproundsRelatedByCupRoundPK0);
        }

        $this->collCuproundssRelatedByCupRoundPK0 = $cuproundssRelatedByCupRoundPK0;
        $this->collCuproundssRelatedByCupRoundPK0Partial = false;

        return $this;
    }

    /**
     * Returns the number of related Cuprounds objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Cuprounds objects.
     * @throws PropelException
     */
    public function countCuproundssRelatedByCupRoundPK0(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collCuproundssRelatedByCupRoundPK0Partial && !$this->isNew();
        if (null === $this->collCuproundssRelatedByCupRoundPK0 || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collCuproundssRelatedByCupRoundPK0) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getCuproundssRelatedByCupRoundPK0());
            }

            $query = ChildCuproundsQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByNextRound($this)
                ->count($con);
        }

        return count($this->collCuproundssRelatedByCupRoundPK0);
    }

    /**
     * Method called to associate a ChildCuprounds object to this object
     * through the ChildCuprounds foreign key attribute.
     *
     * @param  ChildCuprounds $l ChildCuprounds
     * @return $this|\Cuprounds The current object (for fluent API support)
     */
    public function addCuproundsRelatedByCupRoundPK0(ChildCuprounds $l)
    {
        if ($this->collCuproundssRelatedByCupRoundPK0 === null) {
            $this->initCuproundssRelatedByCupRoundPK0();
            $this->collCuproundssRelatedByCupRoundPK0Partial = true;
        }

        if (!$this->collCuproundssRelatedByCupRoundPK0->contains($l)) {
            $this->doAddCuproundsRelatedByCupRoundPK0($l);
        }

        return $this;
    }

    /**
     * @param ChildCuprounds $cuproundsRelatedByCupRoundPK0 The ChildCuprounds object to add.
     */
    protected function doAddCuproundsRelatedByCupRoundPK0(ChildCuprounds $cuproundsRelatedByCupRoundPK0)
    {
        $this->collCuproundssRelatedByCupRoundPK0[]= $cuproundsRelatedByCupRoundPK0;
        $cuproundsRelatedByCupRoundPK0->setNextRound($this);
    }

    /**
     * @param  ChildCuprounds $cuproundsRelatedByCupRoundPK0 The ChildCuprounds object to remove.
     * @return $this|ChildCuprounds The current object (for fluent API support)
     */
    public function removeCuproundsRelatedByCupRoundPK0(ChildCuprounds $cuproundsRelatedByCupRoundPK0)
    {
        if ($this->getCuproundssRelatedByCupRoundPK0()->contains($cuproundsRelatedByCupRoundPK0)) {
            $pos = $this->collCuproundssRelatedByCupRoundPK0->search($cuproundsRelatedByCupRoundPK0);
            $this->collCuproundssRelatedByCupRoundPK0->remove($pos);
            if (null === $this->cuproundssRelatedByCupRoundPK0ScheduledForDeletion) {
                $this->cuproundssRelatedByCupRoundPK0ScheduledForDeletion = clone $this->collCuproundssRelatedByCupRoundPK0;
                $this->cuproundssRelatedByCupRoundPK0ScheduledForDeletion->clear();
            }
            $this->cuproundssRelatedByCupRoundPK0ScheduledForDeletion[]= $cuproundsRelatedByCupRoundPK0;
            $cuproundsRelatedByCupRoundPK0->setNextRound(null);
        }

        return $this;
    }

    /**
     * Clears out the collCuproundssRelatedByCupRoundPK1 collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addCuproundssRelatedByCupRoundPK1()
     */
    public function clearCuproundssRelatedByCupRoundPK1()
    {
        $this->collCuproundssRelatedByCupRoundPK1 = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collCuproundssRelatedByCupRoundPK1 collection loaded partially.
     */
    public function resetPartialCuproundssRelatedByCupRoundPK1($v = true)
    {
        $this->collCuproundssRelatedByCupRoundPK1Partial = $v;
    }

    /**
     * Initializes the collCuproundssRelatedByCupRoundPK1 collection.
     *
     * By default this just sets the collCuproundssRelatedByCupRoundPK1 collection to an empty array (like clearcollCuproundssRelatedByCupRoundPK1());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initCuproundssRelatedByCupRoundPK1($overrideExisting = true)
    {
        if (null !== $this->collCuproundssRelatedByCupRoundPK1 && !$overrideExisting) {
            return;
        }
        $this->collCuproundssRelatedByCupRoundPK1 = new ObjectCollection();
        $this->collCuproundssRelatedByCupRoundPK1->setModel('\Cuprounds');
    }

    /**
     * Gets an array of ChildCuprounds objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildCuprounds is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildCuprounds[] List of ChildCuprounds objects
     * @throws PropelException
     */
    public function getCuproundssRelatedByCupRoundPK1(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collCuproundssRelatedByCupRoundPK1Partial && !$this->isNew();
        if (null === $this->collCuproundssRelatedByCupRoundPK1 || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collCuproundssRelatedByCupRoundPK1) {
                // return empty collection
                $this->initCuproundssRelatedByCupRoundPK1();
            } else {
                $collCuproundssRelatedByCupRoundPK1 = ChildCuproundsQuery::create(null, $criteria)
                    ->filterByPreviousRound($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collCuproundssRelatedByCupRoundPK1Partial && count($collCuproundssRelatedByCupRoundPK1)) {
                        $this->initCuproundssRelatedByCupRoundPK1(false);

                        foreach ($collCuproundssRelatedByCupRoundPK1 as $obj) {
                            if (false == $this->collCuproundssRelatedByCupRoundPK1->contains($obj)) {
                                $this->collCuproundssRelatedByCupRoundPK1->append($obj);
                            }
                        }

                        $this->collCuproundssRelatedByCupRoundPK1Partial = true;
                    }

                    return $collCuproundssRelatedByCupRoundPK1;
                }

                if ($partial && $this->collCuproundssRelatedByCupRoundPK1) {
                    foreach ($this->collCuproundssRelatedByCupRoundPK1 as $obj) {
                        if ($obj->isNew()) {
                            $collCuproundssRelatedByCupRoundPK1[] = $obj;
                        }
                    }
                }

                $this->collCuproundssRelatedByCupRoundPK1 = $collCuproundssRelatedByCupRoundPK1;
                $this->collCuproundssRelatedByCupRoundPK1Partial = false;
            }
        }

        return $this->collCuproundssRelatedByCupRoundPK1;
    }

    /**
     * Sets a collection of ChildCuprounds objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $cuproundssRelatedByCupRoundPK1 A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildCuprounds The current object (for fluent API support)
     */
    public function setCuproundssRelatedByCupRoundPK1(Collection $cuproundssRelatedByCupRoundPK1, ConnectionInterface $con = null)
    {
        /** @var ChildCuprounds[] $cuproundssRelatedByCupRoundPK1ToDelete */
        $cuproundssRelatedByCupRoundPK1ToDelete = $this->getCuproundssRelatedByCupRoundPK1(new Criteria(), $con)->diff($cuproundssRelatedByCupRoundPK1);


        $this->cuproundssRelatedByCupRoundPK1ScheduledForDeletion = $cuproundssRelatedByCupRoundPK1ToDelete;

        foreach ($cuproundssRelatedByCupRoundPK1ToDelete as $cuproundsRelatedByCupRoundPK1Removed) {
            $cuproundsRelatedByCupRoundPK1Removed->setPreviousRound(null);
        }

        $this->collCuproundssRelatedByCupRoundPK1 = null;
        foreach ($cuproundssRelatedByCupRoundPK1 as $cuproundsRelatedByCupRoundPK1) {
            $this->addCuproundsRelatedByCupRoundPK1($cuproundsRelatedByCupRoundPK1);
        }

        $this->collCuproundssRelatedByCupRoundPK1 = $cuproundssRelatedByCupRoundPK1;
        $this->collCuproundssRelatedByCupRoundPK1Partial = false;

        return $this;
    }

    /**
     * Returns the number of related Cuprounds objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Cuprounds objects.
     * @throws PropelException
     */
    public function countCuproundssRelatedByCupRoundPK1(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collCuproundssRelatedByCupRoundPK1Partial && !$this->isNew();
        if (null === $this->collCuproundssRelatedByCupRoundPK1 || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collCuproundssRelatedByCupRoundPK1) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getCuproundssRelatedByCupRoundPK1());
            }

            $query = ChildCuproundsQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPreviousRound($this)
                ->count($con);
        }

        return count($this->collCuproundssRelatedByCupRoundPK1);
    }

    /**
     * Method called to associate a ChildCuprounds object to this object
     * through the ChildCuprounds foreign key attribute.
     *
     * @param  ChildCuprounds $l ChildCuprounds
     * @return $this|\Cuprounds The current object (for fluent API support)
     */
    public function addCuproundsRelatedByCupRoundPK1(ChildCuprounds $l)
    {
        if ($this->collCuproundssRelatedByCupRoundPK1 === null) {
            $this->initCuproundssRelatedByCupRoundPK1();
            $this->collCuproundssRelatedByCupRoundPK1Partial = true;
        }

        if (!$this->collCuproundssRelatedByCupRoundPK1->contains($l)) {
            $this->doAddCuproundsRelatedByCupRoundPK1($l);
        }

        return $this;
    }

    /**
     * @param ChildCuprounds $cuproundsRelatedByCupRoundPK1 The ChildCuprounds object to add.
     */
    protected function doAddCuproundsRelatedByCupRoundPK1(ChildCuprounds $cuproundsRelatedByCupRoundPK1)
    {
        $this->collCuproundssRelatedByCupRoundPK1[]= $cuproundsRelatedByCupRoundPK1;
        $cuproundsRelatedByCupRoundPK1->setPreviousRound($this);
    }

    /**
     * @param  ChildCuprounds $cuproundsRelatedByCupRoundPK1 The ChildCuprounds object to remove.
     * @return $this|ChildCuprounds The current object (for fluent API support)
     */
    public function removeCuproundsRelatedByCupRoundPK1(ChildCuprounds $cuproundsRelatedByCupRoundPK1)
    {
        if ($this->getCuproundssRelatedByCupRoundPK1()->contains($cuproundsRelatedByCupRoundPK1)) {
            $pos = $this->collCuproundssRelatedByCupRoundPK1->search($cuproundsRelatedByCupRoundPK1);
            $this->collCuproundssRelatedByCupRoundPK1->remove($pos);
            if (null === $this->cuproundssRelatedByCupRoundPK1ScheduledForDeletion) {
                $this->cuproundssRelatedByCupRoundPK1ScheduledForDeletion = clone $this->collCuproundssRelatedByCupRoundPK1;
                $this->cuproundssRelatedByCupRoundPK1ScheduledForDeletion->clear();
            }
            $this->cuproundssRelatedByCupRoundPK1ScheduledForDeletion[]= $cuproundsRelatedByCupRoundPK1;
            $cuproundsRelatedByCupRoundPK1->setPreviousRound(null);
        }

        return $this;
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
     * If this ChildCuprounds is new, it will return
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
                    ->filterByCupMatchesCupRound($this)
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
     * @return $this|ChildCuprounds The current object (for fluent API support)
     */
    public function setPlayercupmatchess(Collection $playercupmatchess, ConnectionInterface $con = null)
    {
        /** @var ChildPlayercupmatches[] $playercupmatchessToDelete */
        $playercupmatchessToDelete = $this->getPlayercupmatchess(new Criteria(), $con)->diff($playercupmatchess);


        $this->playercupmatchessScheduledForDeletion = $playercupmatchessToDelete;

        foreach ($playercupmatchessToDelete as $playercupmatchesRemoved) {
            $playercupmatchesRemoved->setCupMatchesCupRound(null);
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
                ->filterByCupMatchesCupRound($this)
                ->count($con);
        }

        return count($this->collPlayercupmatchess);
    }

    /**
     * Method called to associate a ChildPlayercupmatches object to this object
     * through the ChildPlayercupmatches foreign key attribute.
     *
     * @param  ChildPlayercupmatches $l ChildPlayercupmatches
     * @return $this|\Cuprounds The current object (for fluent API support)
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
        $playercupmatches->setCupMatchesCupRound($this);
    }

    /**
     * @param  ChildPlayercupmatches $playercupmatches The ChildPlayercupmatches object to remove.
     * @return $this|ChildCuprounds The current object (for fluent API support)
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
            $playercupmatches->setCupMatchesCupRound(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Cuprounds is new, it will return
     * an empty collection; or if this Cuprounds has previously
     * been saved, it will retrieve related Playercupmatchess from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Cuprounds.
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
     * Otherwise if this Cuprounds is new, it will return
     * an empty collection; or if this Cuprounds has previously
     * been saved, it will retrieve related Playercupmatchess from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Cuprounds.
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
     * Otherwise if this Cuprounds is new, it will return
     * an empty collection; or if this Cuprounds has previously
     * been saved, it will retrieve related Playercupmatchess from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Cuprounds.
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
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Cuprounds is new, it will return
     * an empty collection; or if this Cuprounds has previously
     * been saved, it will retrieve related Playercupmatchess from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Cuprounds.
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
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        if (null !== $this->aNextRound) {
            $this->aNextRound->removeCuproundsRelatedByCupRoundPK0($this);
        }
        if (null !== $this->aPreviousRound) {
            $this->aPreviousRound->removeCuproundsRelatedByCupRoundPK1($this);
        }
        $this->primarykey = null;
        $this->description = null;
        $this->code = null;
        $this->nextroundkey = null;
        $this->previousroundkey = null;
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
            if ($this->collCuproundssRelatedByCupRoundPK0) {
                foreach ($this->collCuproundssRelatedByCupRoundPK0 as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collCuproundssRelatedByCupRoundPK1) {
                foreach ($this->collCuproundssRelatedByCupRoundPK1 as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPlayercupmatchess) {
                foreach ($this->collPlayercupmatchess as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collCuproundssRelatedByCupRoundPK0 = null;
        $this->collCuproundssRelatedByCupRoundPK1 = null;
        $this->collPlayercupmatchess = null;
        $this->aNextRound = null;
        $this->aPreviousRound = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(CuproundsTableMap::DEFAULT_STRING_FORMAT);
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
