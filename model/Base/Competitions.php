<?php

namespace Base;

use \Competitions as ChildCompetitions;
use \CompetitionsQuery as ChildCompetitionsQuery;
use \Groups as ChildGroups;
use \GroupsQuery as ChildGroupsQuery;
use \News as ChildNews;
use \NewsQuery as ChildNewsQuery;
use \Playerranking as ChildPlayerranking;
use \PlayerrankingQuery as ChildPlayerrankingQuery;
use \Exception;
use \PDO;
use Map\CompetitionsTableMap;
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
 * Base class that represents a row from the 'competitions' table.
 *
 *
 *
* @package    propel.generator..Base
*/
abstract class Competitions implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\Map\\CompetitionsTableMap';


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
     * @var        ObjectCollection|ChildGroups[] Collection to store aggregation of ChildGroups objects.
     */
    protected $collGroupss;
    protected $collGroupssPartial;

    /**
     * @var        ObjectCollection|ChildNews[] Collection to store aggregation of ChildNews objects.
     */
    protected $collNews;
    protected $collNewsPartial;

    /**
     * @var        ObjectCollection|ChildPlayerranking[] Collection to store aggregation of ChildPlayerranking objects.
     */
    protected $collPlayerrankings;
    protected $collPlayerrankingsPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildGroups[]
     */
    protected $groupssScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildNews[]
     */
    protected $newsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildPlayerranking[]
     */
    protected $playerrankingsScheduledForDeletion = null;

    /**
     * Initializes internal state of Base\Competitions object.
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
     * Compares this with another <code>Competitions</code> instance.  If
     * <code>obj</code> is an instance of <code>Competitions</code>, delegates to
     * <code>equals(Competitions)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|Competitions The current object, for fluid interface
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
    public function getCompetitionPK()
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
     * Set the value of [primarykey] column.
     *
     * @param int $v new value
     * @return $this|\Competitions The current object (for fluent API support)
     */
    public function setCompetitionPK($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->primarykey !== $v) {
            $this->primarykey = $v;
            $this->modifiedColumns[CompetitionsTableMap::COL_PRIMARYKEY] = true;
        }

        return $this;
    } // setCompetitionPK()

    /**
     * Set the value of [name] column.
     *
     * @param string $v new value
     * @return $this|\Competitions The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[CompetitionsTableMap::COL_NAME] = true;
        }

        return $this;
    } // setName()

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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : CompetitionsTableMap::translateFieldName('CompetitionPK', TableMap::TYPE_PHPNAME, $indexType)];
            $this->primarykey = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : CompetitionsTableMap::translateFieldName('Name', TableMap::TYPE_PHPNAME, $indexType)];
            $this->name = (null !== $col) ? (string) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 2; // 2 = CompetitionsTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Competitions'), 0, $e);
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
            $con = Propel::getServiceContainer()->getReadConnection(CompetitionsTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildCompetitionsQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collGroupss = null;

            $this->collNews = null;

            $this->collPlayerrankings = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Competitions::setDeleted()
     * @see Competitions::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(CompetitionsTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildCompetitionsQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(CompetitionsTableMap::DATABASE_NAME);
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
                CompetitionsTableMap::addInstanceToPool($this);
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

            if ($this->groupssScheduledForDeletion !== null) {
                if (!$this->groupssScheduledForDeletion->isEmpty()) {
                    \GroupsQuery::create()
                        ->filterByPrimaryKeys($this->groupssScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->groupssScheduledForDeletion = null;
                }
            }

            if ($this->collGroupss !== null) {
                foreach ($this->collGroupss as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->newsScheduledForDeletion !== null) {
                if (!$this->newsScheduledForDeletion->isEmpty()) {
                    \NewsQuery::create()
                        ->filterByPrimaryKeys($this->newsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->newsScheduledForDeletion = null;
                }
            }

            if ($this->collNews !== null) {
                foreach ($this->collNews as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->playerrankingsScheduledForDeletion !== null) {
                if (!$this->playerrankingsScheduledForDeletion->isEmpty()) {
                    \PlayerrankingQuery::create()
                        ->filterByPrimaryKeys($this->playerrankingsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->playerrankingsScheduledForDeletion = null;
                }
            }

            if ($this->collPlayerrankings !== null) {
                foreach ($this->collPlayerrankings as $referrerFK) {
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

        $this->modifiedColumns[CompetitionsTableMap::COL_PRIMARYKEY] = true;
        if (null !== $this->primarykey) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . CompetitionsTableMap::COL_PRIMARYKEY . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(CompetitionsTableMap::COL_PRIMARYKEY)) {
            $modifiedColumns[':p' . $index++]  = 'PrimaryKey';
        }
        if ($this->isColumnModified(CompetitionsTableMap::COL_NAME)) {
            $modifiedColumns[':p' . $index++]  = 'Name';
        }

        $sql = sprintf(
            'INSERT INTO competitions (%s) VALUES (%s)',
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
        $this->setCompetitionPK($pk);

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
        $pos = CompetitionsTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getCompetitionPK();
                break;
            case 1:
                return $this->getName();
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

        if (isset($alreadyDumpedObjects['Competitions'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Competitions'][$this->hashCode()] = true;
        $keys = CompetitionsTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getCompetitionPK(),
            $keys[1] => $this->getName(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->collGroupss) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'groupss';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'groupss';
                        break;
                    default:
                        $key = 'Groupss';
                }

                $result[$key] = $this->collGroupss->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collNews) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'news';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'news';
                        break;
                    default:
                        $key = 'News';
                }

                $result[$key] = $this->collNews->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPlayerrankings) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'playerrankings';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'playerrankings';
                        break;
                    default:
                        $key = 'Playerrankings';
                }

                $result[$key] = $this->collPlayerrankings->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
     * @return $this|\Competitions
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = CompetitionsTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\Competitions
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setCompetitionPK($value);
                break;
            case 1:
                $this->setName($value);
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
        $keys = CompetitionsTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setCompetitionPK($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setName($arr[$keys[1]]);
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
     * @return $this|\Competitions The current object, for fluid interface
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
        $criteria = new Criteria(CompetitionsTableMap::DATABASE_NAME);

        if ($this->isColumnModified(CompetitionsTableMap::COL_PRIMARYKEY)) {
            $criteria->add(CompetitionsTableMap::COL_PRIMARYKEY, $this->primarykey);
        }
        if ($this->isColumnModified(CompetitionsTableMap::COL_NAME)) {
            $criteria->add(CompetitionsTableMap::COL_NAME, $this->name);
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
        $criteria = ChildCompetitionsQuery::create();
        $criteria->add(CompetitionsTableMap::COL_PRIMARYKEY, $this->primarykey);

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
        $validPk = null !== $this->getCompetitionPK();

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
        return $this->getCompetitionPK();
    }

    /**
     * Generic method to set the primary key (primarykey column).
     *
     * @param       int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setCompetitionPK($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {
        return null === $this->getCompetitionPK();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of \Competitions (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setName($this->getName());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getGroupss() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addGroups($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getNews() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addNews($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPlayerrankings() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPlayerranking($relObj->copy($deepCopy));
                }
            }

        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setCompetitionPK(NULL); // this is a auto-increment column, so set to default value
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
     * @return \Competitions Clone of current object.
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
        if ('Groups' == $relationName) {
            return $this->initGroupss();
        }
        if ('News' == $relationName) {
            return $this->initNews();
        }
        if ('Playerranking' == $relationName) {
            return $this->initPlayerrankings();
        }
    }

    /**
     * Clears out the collGroupss collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addGroupss()
     */
    public function clearGroupss()
    {
        $this->collGroupss = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collGroupss collection loaded partially.
     */
    public function resetPartialGroupss($v = true)
    {
        $this->collGroupssPartial = $v;
    }

    /**
     * Initializes the collGroupss collection.
     *
     * By default this just sets the collGroupss collection to an empty array (like clearcollGroupss());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initGroupss($overrideExisting = true)
    {
        if (null !== $this->collGroupss && !$overrideExisting) {
            return;
        }
        $this->collGroupss = new ObjectCollection();
        $this->collGroupss->setModel('\Groups');
    }

    /**
     * Gets an array of ChildGroups objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildCompetitions is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildGroups[] List of ChildGroups objects
     * @throws PropelException
     */
    public function getGroupss(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collGroupssPartial && !$this->isNew();
        if (null === $this->collGroupss || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collGroupss) {
                // return empty collection
                $this->initGroupss();
            } else {
                $collGroupss = ChildGroupsQuery::create(null, $criteria)
                    ->filterByCompetitions($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collGroupssPartial && count($collGroupss)) {
                        $this->initGroupss(false);

                        foreach ($collGroupss as $obj) {
                            if (false == $this->collGroupss->contains($obj)) {
                                $this->collGroupss->append($obj);
                            }
                        }

                        $this->collGroupssPartial = true;
                    }

                    return $collGroupss;
                }

                if ($partial && $this->collGroupss) {
                    foreach ($this->collGroupss as $obj) {
                        if ($obj->isNew()) {
                            $collGroupss[] = $obj;
                        }
                    }
                }

                $this->collGroupss = $collGroupss;
                $this->collGroupssPartial = false;
            }
        }

        return $this->collGroupss;
    }

    /**
     * Sets a collection of ChildGroups objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $groupss A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildCompetitions The current object (for fluent API support)
     */
    public function setGroupss(Collection $groupss, ConnectionInterface $con = null)
    {
        /** @var ChildGroups[] $groupssToDelete */
        $groupssToDelete = $this->getGroupss(new Criteria(), $con)->diff($groupss);


        $this->groupssScheduledForDeletion = $groupssToDelete;

        foreach ($groupssToDelete as $groupsRemoved) {
            $groupsRemoved->setCompetitions(null);
        }

        $this->collGroupss = null;
        foreach ($groupss as $groups) {
            $this->addGroups($groups);
        }

        $this->collGroupss = $groupss;
        $this->collGroupssPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Groups objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Groups objects.
     * @throws PropelException
     */
    public function countGroupss(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collGroupssPartial && !$this->isNew();
        if (null === $this->collGroupss || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collGroupss) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getGroupss());
            }

            $query = ChildGroupsQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByCompetitions($this)
                ->count($con);
        }

        return count($this->collGroupss);
    }

    /**
     * Method called to associate a ChildGroups object to this object
     * through the ChildGroups foreign key attribute.
     *
     * @param  ChildGroups $l ChildGroups
     * @return $this|\Competitions The current object (for fluent API support)
     */
    public function addGroups(ChildGroups $l)
    {
        if ($this->collGroupss === null) {
            $this->initGroupss();
            $this->collGroupssPartial = true;
        }

        if (!$this->collGroupss->contains($l)) {
            $this->doAddGroups($l);
        }

        return $this;
    }

    /**
     * @param ChildGroups $groups The ChildGroups object to add.
     */
    protected function doAddGroups(ChildGroups $groups)
    {
        $this->collGroupss[]= $groups;
        $groups->setCompetitions($this);
    }

    /**
     * @param  ChildGroups $groups The ChildGroups object to remove.
     * @return $this|ChildCompetitions The current object (for fluent API support)
     */
    public function removeGroups(ChildGroups $groups)
    {
        if ($this->getGroupss()->contains($groups)) {
            $pos = $this->collGroupss->search($groups);
            $this->collGroupss->remove($pos);
            if (null === $this->groupssScheduledForDeletion) {
                $this->groupssScheduledForDeletion = clone $this->collGroupss;
                $this->groupssScheduledForDeletion->clear();
            }
            $this->groupssScheduledForDeletion[]= clone $groups;
            $groups->setCompetitions(null);
        }

        return $this;
    }

    /**
     * Clears out the collNews collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addNews()
     */
    public function clearNews()
    {
        $this->collNews = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collNews collection loaded partially.
     */
    public function resetPartialNews($v = true)
    {
        $this->collNewsPartial = $v;
    }

    /**
     * Initializes the collNews collection.
     *
     * By default this just sets the collNews collection to an empty array (like clearcollNews());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initNews($overrideExisting = true)
    {
        if (null !== $this->collNews && !$overrideExisting) {
            return;
        }
        $this->collNews = new ObjectCollection();
        $this->collNews->setModel('\News');
    }

    /**
     * Gets an array of ChildNews objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildCompetitions is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildNews[] List of ChildNews objects
     * @throws PropelException
     */
    public function getNews(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collNewsPartial && !$this->isNew();
        if (null === $this->collNews || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collNews) {
                // return empty collection
                $this->initNews();
            } else {
                $collNews = ChildNewsQuery::create(null, $criteria)
                    ->filterByCompetitions($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collNewsPartial && count($collNews)) {
                        $this->initNews(false);

                        foreach ($collNews as $obj) {
                            if (false == $this->collNews->contains($obj)) {
                                $this->collNews->append($obj);
                            }
                        }

                        $this->collNewsPartial = true;
                    }

                    return $collNews;
                }

                if ($partial && $this->collNews) {
                    foreach ($this->collNews as $obj) {
                        if ($obj->isNew()) {
                            $collNews[] = $obj;
                        }
                    }
                }

                $this->collNews = $collNews;
                $this->collNewsPartial = false;
            }
        }

        return $this->collNews;
    }

    /**
     * Sets a collection of ChildNews objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $news A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildCompetitions The current object (for fluent API support)
     */
    public function setNews(Collection $news, ConnectionInterface $con = null)
    {
        /** @var ChildNews[] $newsToDelete */
        $newsToDelete = $this->getNews(new Criteria(), $con)->diff($news);


        $this->newsScheduledForDeletion = $newsToDelete;

        foreach ($newsToDelete as $newsRemoved) {
            $newsRemoved->setCompetitions(null);
        }

        $this->collNews = null;
        foreach ($news as $news) {
            $this->addNews($news);
        }

        $this->collNews = $news;
        $this->collNewsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related News objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related News objects.
     * @throws PropelException
     */
    public function countNews(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collNewsPartial && !$this->isNew();
        if (null === $this->collNews || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collNews) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getNews());
            }

            $query = ChildNewsQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByCompetitions($this)
                ->count($con);
        }

        return count($this->collNews);
    }

    /**
     * Method called to associate a ChildNews object to this object
     * through the ChildNews foreign key attribute.
     *
     * @param  ChildNews $l ChildNews
     * @return $this|\Competitions The current object (for fluent API support)
     */
    public function addNews(ChildNews $l)
    {
        if ($this->collNews === null) {
            $this->initNews();
            $this->collNewsPartial = true;
        }

        if (!$this->collNews->contains($l)) {
            $this->doAddNews($l);
        }

        return $this;
    }

    /**
     * @param ChildNews $news The ChildNews object to add.
     */
    protected function doAddNews(ChildNews $news)
    {
        $this->collNews[]= $news;
        $news->setCompetitions($this);
    }

    /**
     * @param  ChildNews $news The ChildNews object to remove.
     * @return $this|ChildCompetitions The current object (for fluent API support)
     */
    public function removeNews(ChildNews $news)
    {
        if ($this->getNews()->contains($news)) {
            $pos = $this->collNews->search($news);
            $this->collNews->remove($pos);
            if (null === $this->newsScheduledForDeletion) {
                $this->newsScheduledForDeletion = clone $this->collNews;
                $this->newsScheduledForDeletion->clear();
            }
            $this->newsScheduledForDeletion[]= clone $news;
            $news->setCompetitions(null);
        }

        return $this;
    }

    /**
     * Clears out the collPlayerrankings collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addPlayerrankings()
     */
    public function clearPlayerrankings()
    {
        $this->collPlayerrankings = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collPlayerrankings collection loaded partially.
     */
    public function resetPartialPlayerrankings($v = true)
    {
        $this->collPlayerrankingsPartial = $v;
    }

    /**
     * Initializes the collPlayerrankings collection.
     *
     * By default this just sets the collPlayerrankings collection to an empty array (like clearcollPlayerrankings());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPlayerrankings($overrideExisting = true)
    {
        if (null !== $this->collPlayerrankings && !$overrideExisting) {
            return;
        }
        $this->collPlayerrankings = new ObjectCollection();
        $this->collPlayerrankings->setModel('\Playerranking');
    }

    /**
     * Gets an array of ChildPlayerranking objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildCompetitions is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildPlayerranking[] List of ChildPlayerranking objects
     * @throws PropelException
     */
    public function getPlayerrankings(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collPlayerrankingsPartial && !$this->isNew();
        if (null === $this->collPlayerrankings || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPlayerrankings) {
                // return empty collection
                $this->initPlayerrankings();
            } else {
                $collPlayerrankings = ChildPlayerrankingQuery::create(null, $criteria)
                    ->filterByCompetitionRanking($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collPlayerrankingsPartial && count($collPlayerrankings)) {
                        $this->initPlayerrankings(false);

                        foreach ($collPlayerrankings as $obj) {
                            if (false == $this->collPlayerrankings->contains($obj)) {
                                $this->collPlayerrankings->append($obj);
                            }
                        }

                        $this->collPlayerrankingsPartial = true;
                    }

                    return $collPlayerrankings;
                }

                if ($partial && $this->collPlayerrankings) {
                    foreach ($this->collPlayerrankings as $obj) {
                        if ($obj->isNew()) {
                            $collPlayerrankings[] = $obj;
                        }
                    }
                }

                $this->collPlayerrankings = $collPlayerrankings;
                $this->collPlayerrankingsPartial = false;
            }
        }

        return $this->collPlayerrankings;
    }

    /**
     * Sets a collection of ChildPlayerranking objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $playerrankings A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildCompetitions The current object (for fluent API support)
     */
    public function setPlayerrankings(Collection $playerrankings, ConnectionInterface $con = null)
    {
        /** @var ChildPlayerranking[] $playerrankingsToDelete */
        $playerrankingsToDelete = $this->getPlayerrankings(new Criteria(), $con)->diff($playerrankings);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->playerrankingsScheduledForDeletion = clone $playerrankingsToDelete;

        foreach ($playerrankingsToDelete as $playerrankingRemoved) {
            $playerrankingRemoved->setCompetitionRanking(null);
        }

        $this->collPlayerrankings = null;
        foreach ($playerrankings as $playerranking) {
            $this->addPlayerranking($playerranking);
        }

        $this->collPlayerrankings = $playerrankings;
        $this->collPlayerrankingsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Playerranking objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Playerranking objects.
     * @throws PropelException
     */
    public function countPlayerrankings(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collPlayerrankingsPartial && !$this->isNew();
        if (null === $this->collPlayerrankings || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPlayerrankings) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPlayerrankings());
            }

            $query = ChildPlayerrankingQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByCompetitionRanking($this)
                ->count($con);
        }

        return count($this->collPlayerrankings);
    }

    /**
     * Method called to associate a ChildPlayerranking object to this object
     * through the ChildPlayerranking foreign key attribute.
     *
     * @param  ChildPlayerranking $l ChildPlayerranking
     * @return $this|\Competitions The current object (for fluent API support)
     */
    public function addPlayerranking(ChildPlayerranking $l)
    {
        if ($this->collPlayerrankings === null) {
            $this->initPlayerrankings();
            $this->collPlayerrankingsPartial = true;
        }

        if (!$this->collPlayerrankings->contains($l)) {
            $this->doAddPlayerranking($l);
        }

        return $this;
    }

    /**
     * @param ChildPlayerranking $playerranking The ChildPlayerranking object to add.
     */
    protected function doAddPlayerranking(ChildPlayerranking $playerranking)
    {
        $this->collPlayerrankings[]= $playerranking;
        $playerranking->setCompetitionRanking($this);
    }

    /**
     * @param  ChildPlayerranking $playerranking The ChildPlayerranking object to remove.
     * @return $this|ChildCompetitions The current object (for fluent API support)
     */
    public function removePlayerranking(ChildPlayerranking $playerranking)
    {
        if ($this->getPlayerrankings()->contains($playerranking)) {
            $pos = $this->collPlayerrankings->search($playerranking);
            $this->collPlayerrankings->remove($pos);
            if (null === $this->playerrankingsScheduledForDeletion) {
                $this->playerrankingsScheduledForDeletion = clone $this->collPlayerrankings;
                $this->playerrankingsScheduledForDeletion->clear();
            }
            $this->playerrankingsScheduledForDeletion[]= clone $playerranking;
            $playerranking->setCompetitionRanking(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Competitions is new, it will return
     * an empty collection; or if this Competitions has previously
     * been saved, it will retrieve related Playerrankings from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Competitions.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildPlayerranking[] List of ChildPlayerranking objects
     */
    public function getPlayerrankingsJoinRankingPlayer(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildPlayerrankingQuery::create(null, $criteria);
        $query->joinWith('RankingPlayer', $joinBehavior);

        return $this->getPlayerrankings($query, $con);
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
            if ($this->collGroupss) {
                foreach ($this->collGroupss as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collNews) {
                foreach ($this->collNews as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPlayerrankings) {
                foreach ($this->collPlayerrankings as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collGroupss = null;
        $this->collNews = null;
        $this->collPlayerrankings = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(CompetitionsTableMap::DEFAULT_STRING_FORMAT);
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
