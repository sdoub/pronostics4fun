<?php

namespace Base;

use \LineupsQuery as ChildLineupsQuery;
use \Matches as ChildMatches;
use \MatchesQuery as ChildMatchesQuery;
use \Teamplayers as ChildTeamplayers;
use \TeamplayersQuery as ChildTeamplayersQuery;
use \Teams as ChildTeams;
use \TeamsQuery as ChildTeamsQuery;
use \Exception;
use \PDO;
use Map\LineupsTableMap;
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

/**
 * Base class that represents a row from the 'lineups' table.
 *
 *
 *
* @package    propel.generator..Base
*/
abstract class Lineups implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\Map\\LineupsTableMap';


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
     * The value for the matchkey field.
     * @var        int
     */
    protected $matchkey;

    /**
     * The value for the teamkey field.
     * @var        int
     */
    protected $teamkey;

    /**
     * The value for the teamplayerkey field.
     * @var        int
     */
    protected $teamplayerkey;

    /**
     * The value for the issubstitute field.
     * Note: this column has a database default value of: false
     * @var        boolean
     */
    protected $issubstitute;

    /**
     * The value for the timein field.
     * @var        int
     */
    protected $timein;

    /**
     * The value for the teamplayerreplacedkey field.
     * @var        int
     */
    protected $teamplayerreplacedkey;

    /**
     * @var        ChildMatches
     */
    protected $aLineUpMatch;

    /**
     * @var        ChildTeams
     */
    protected $aLineUpTeam;

    /**
     * @var        ChildTeamplayers
     */
    protected $aLineUpTeamPlayer;

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
        $this->issubstitute = false;
    }

    /**
     * Initializes internal state of Base\Lineups object.
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
     * Compares this with another <code>Lineups</code> instance.  If
     * <code>obj</code> is an instance of <code>Lineups</code>, delegates to
     * <code>equals(Lineups)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|Lineups The current object, for fluid interface
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
     * Get the [matchkey] column value.
     *
     * @return int
     */
    public function getMatchkey()
    {
        return $this->matchkey;
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
     * Get the [teamplayerkey] column value.
     *
     * @return int
     */
    public function getTeamplayerkey()
    {
        return $this->teamplayerkey;
    }

    /**
     * Get the [issubstitute] column value.
     *
     * @return boolean
     */
    public function getIssubstitute()
    {
        return $this->issubstitute;
    }

    /**
     * Get the [issubstitute] column value.
     *
     * @return boolean
     */
    public function isIssubstitute()
    {
        return $this->getIssubstitute();
    }

    /**
     * Get the [timein] column value.
     *
     * @return int
     */
    public function getTimein()
    {
        return $this->timein;
    }

    /**
     * Get the [teamplayerreplacedkey] column value.
     *
     * @return int
     */
    public function getTeamplayerreplacedkey()
    {
        return $this->teamplayerreplacedkey;
    }

    /**
     * Set the value of [matchkey] column.
     *
     * @param int $v new value
     * @return $this|\Lineups The current object (for fluent API support)
     */
    public function setMatchkey($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->matchkey !== $v) {
            $this->matchkey = $v;
            $this->modifiedColumns[LineupsTableMap::COL_MATCHKEY] = true;
        }

        if ($this->aLineUpMatch !== null && $this->aLineUpMatch->getMatchPK() !== $v) {
            $this->aLineUpMatch = null;
        }

        return $this;
    } // setMatchkey()

    /**
     * Set the value of [teamkey] column.
     *
     * @param int $v new value
     * @return $this|\Lineups The current object (for fluent API support)
     */
    public function setTeamkey($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->teamkey !== $v) {
            $this->teamkey = $v;
            $this->modifiedColumns[LineupsTableMap::COL_TEAMKEY] = true;
        }

        if ($this->aLineUpTeam !== null && $this->aLineUpTeam->getTeamPK() !== $v) {
            $this->aLineUpTeam = null;
        }

        return $this;
    } // setTeamkey()

    /**
     * Set the value of [teamplayerkey] column.
     *
     * @param int $v new value
     * @return $this|\Lineups The current object (for fluent API support)
     */
    public function setTeamplayerkey($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->teamplayerkey !== $v) {
            $this->teamplayerkey = $v;
            $this->modifiedColumns[LineupsTableMap::COL_TEAMPLAYERKEY] = true;
        }

        if ($this->aLineUpTeamPlayer !== null && $this->aLineUpTeamPlayer->getTeamPlayerPK() !== $v) {
            $this->aLineUpTeamPlayer = null;
        }

        return $this;
    } // setTeamplayerkey()

    /**
     * Sets the value of the [issubstitute] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param  boolean|integer|string $v The new value
     * @return $this|\Lineups The current object (for fluent API support)
     */
    public function setIssubstitute($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->issubstitute !== $v) {
            $this->issubstitute = $v;
            $this->modifiedColumns[LineupsTableMap::COL_ISSUBSTITUTE] = true;
        }

        return $this;
    } // setIssubstitute()

    /**
     * Set the value of [timein] column.
     *
     * @param int $v new value
     * @return $this|\Lineups The current object (for fluent API support)
     */
    public function setTimein($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->timein !== $v) {
            $this->timein = $v;
            $this->modifiedColumns[LineupsTableMap::COL_TIMEIN] = true;
        }

        return $this;
    } // setTimein()

    /**
     * Set the value of [teamplayerreplacedkey] column.
     *
     * @param int $v new value
     * @return $this|\Lineups The current object (for fluent API support)
     */
    public function setTeamplayerreplacedkey($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->teamplayerreplacedkey !== $v) {
            $this->teamplayerreplacedkey = $v;
            $this->modifiedColumns[LineupsTableMap::COL_TEAMPLAYERREPLACEDKEY] = true;
        }

        return $this;
    } // setTeamplayerreplacedkey()

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
            if ($this->issubstitute !== false) {
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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : LineupsTableMap::translateFieldName('Matchkey', TableMap::TYPE_PHPNAME, $indexType)];
            $this->matchkey = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : LineupsTableMap::translateFieldName('Teamkey', TableMap::TYPE_PHPNAME, $indexType)];
            $this->teamkey = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : LineupsTableMap::translateFieldName('Teamplayerkey', TableMap::TYPE_PHPNAME, $indexType)];
            $this->teamplayerkey = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : LineupsTableMap::translateFieldName('Issubstitute', TableMap::TYPE_PHPNAME, $indexType)];
            $this->issubstitute = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : LineupsTableMap::translateFieldName('Timein', TableMap::TYPE_PHPNAME, $indexType)];
            $this->timein = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : LineupsTableMap::translateFieldName('Teamplayerreplacedkey', TableMap::TYPE_PHPNAME, $indexType)];
            $this->teamplayerreplacedkey = (null !== $col) ? (int) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 6; // 6 = LineupsTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Lineups'), 0, $e);
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
        if ($this->aLineUpMatch !== null && $this->matchkey !== $this->aLineUpMatch->getMatchPK()) {
            $this->aLineUpMatch = null;
        }
        if ($this->aLineUpTeam !== null && $this->teamkey !== $this->aLineUpTeam->getTeamPK()) {
            $this->aLineUpTeam = null;
        }
        if ($this->aLineUpTeamPlayer !== null && $this->teamplayerkey !== $this->aLineUpTeamPlayer->getTeamPlayerPK()) {
            $this->aLineUpTeamPlayer = null;
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
            $con = Propel::getServiceContainer()->getReadConnection(LineupsTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildLineupsQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aLineUpMatch = null;
            $this->aLineUpTeam = null;
            $this->aLineUpTeamPlayer = null;
        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Lineups::setDeleted()
     * @see Lineups::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(LineupsTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildLineupsQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(LineupsTableMap::DATABASE_NAME);
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
                LineupsTableMap::addInstanceToPool($this);
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

            if ($this->aLineUpMatch !== null) {
                if ($this->aLineUpMatch->isModified() || $this->aLineUpMatch->isNew()) {
                    $affectedRows += $this->aLineUpMatch->save($con);
                }
                $this->setLineUpMatch($this->aLineUpMatch);
            }

            if ($this->aLineUpTeam !== null) {
                if ($this->aLineUpTeam->isModified() || $this->aLineUpTeam->isNew()) {
                    $affectedRows += $this->aLineUpTeam->save($con);
                }
                $this->setLineUpTeam($this->aLineUpTeam);
            }

            if ($this->aLineUpTeamPlayer !== null) {
                if ($this->aLineUpTeamPlayer->isModified() || $this->aLineUpTeamPlayer->isNew()) {
                    $affectedRows += $this->aLineUpTeamPlayer->save($con);
                }
                $this->setLineUpTeamPlayer($this->aLineUpTeamPlayer);
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
        if ($this->isColumnModified(LineupsTableMap::COL_MATCHKEY)) {
            $modifiedColumns[':p' . $index++]  = 'MatchKey';
        }
        if ($this->isColumnModified(LineupsTableMap::COL_TEAMKEY)) {
            $modifiedColumns[':p' . $index++]  = 'TeamKey';
        }
        if ($this->isColumnModified(LineupsTableMap::COL_TEAMPLAYERKEY)) {
            $modifiedColumns[':p' . $index++]  = 'TeamPlayerKey';
        }
        if ($this->isColumnModified(LineupsTableMap::COL_ISSUBSTITUTE)) {
            $modifiedColumns[':p' . $index++]  = 'IsSubstitute';
        }
        if ($this->isColumnModified(LineupsTableMap::COL_TIMEIN)) {
            $modifiedColumns[':p' . $index++]  = 'TimeIn';
        }
        if ($this->isColumnModified(LineupsTableMap::COL_TEAMPLAYERREPLACEDKEY)) {
            $modifiedColumns[':p' . $index++]  = 'TeamPlayerReplacedKey';
        }

        $sql = sprintf(
            'INSERT INTO lineups (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'MatchKey':
                        $stmt->bindValue($identifier, $this->matchkey, PDO::PARAM_INT);
                        break;
                    case 'TeamKey':
                        $stmt->bindValue($identifier, $this->teamkey, PDO::PARAM_INT);
                        break;
                    case 'TeamPlayerKey':
                        $stmt->bindValue($identifier, $this->teamplayerkey, PDO::PARAM_INT);
                        break;
                    case 'IsSubstitute':
                        $stmt->bindValue($identifier, (int) $this->issubstitute, PDO::PARAM_INT);
                        break;
                    case 'TimeIn':
                        $stmt->bindValue($identifier, $this->timein, PDO::PARAM_INT);
                        break;
                    case 'TeamPlayerReplacedKey':
                        $stmt->bindValue($identifier, $this->teamplayerreplacedkey, PDO::PARAM_INT);
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
        $pos = LineupsTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getMatchkey();
                break;
            case 1:
                return $this->getTeamkey();
                break;
            case 2:
                return $this->getTeamplayerkey();
                break;
            case 3:
                return $this->getIssubstitute();
                break;
            case 4:
                return $this->getTimein();
                break;
            case 5:
                return $this->getTeamplayerreplacedkey();
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

        if (isset($alreadyDumpedObjects['Lineups'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Lineups'][$this->hashCode()] = true;
        $keys = LineupsTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getMatchkey(),
            $keys[1] => $this->getTeamkey(),
            $keys[2] => $this->getTeamplayerkey(),
            $keys[3] => $this->getIssubstitute(),
            $keys[4] => $this->getTimein(),
            $keys[5] => $this->getTeamplayerreplacedkey(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aLineUpMatch) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'matches';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'matches';
                        break;
                    default:
                        $key = 'Matches';
                }

                $result[$key] = $this->aLineUpMatch->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aLineUpTeam) {

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

                $result[$key] = $this->aLineUpTeam->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aLineUpTeamPlayer) {

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

                $result[$key] = $this->aLineUpTeamPlayer->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
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
     * @return $this|\Lineups
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = LineupsTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\Lineups
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setMatchkey($value);
                break;
            case 1:
                $this->setTeamkey($value);
                break;
            case 2:
                $this->setTeamplayerkey($value);
                break;
            case 3:
                $this->setIssubstitute($value);
                break;
            case 4:
                $this->setTimein($value);
                break;
            case 5:
                $this->setTeamplayerreplacedkey($value);
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
        $keys = LineupsTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setMatchkey($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setTeamkey($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setTeamplayerkey($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setIssubstitute($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setTimein($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setTeamplayerreplacedkey($arr[$keys[5]]);
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
     * @return $this|\Lineups The current object, for fluid interface
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
        $criteria = new Criteria(LineupsTableMap::DATABASE_NAME);

        if ($this->isColumnModified(LineupsTableMap::COL_MATCHKEY)) {
            $criteria->add(LineupsTableMap::COL_MATCHKEY, $this->matchkey);
        }
        if ($this->isColumnModified(LineupsTableMap::COL_TEAMKEY)) {
            $criteria->add(LineupsTableMap::COL_TEAMKEY, $this->teamkey);
        }
        if ($this->isColumnModified(LineupsTableMap::COL_TEAMPLAYERKEY)) {
            $criteria->add(LineupsTableMap::COL_TEAMPLAYERKEY, $this->teamplayerkey);
        }
        if ($this->isColumnModified(LineupsTableMap::COL_ISSUBSTITUTE)) {
            $criteria->add(LineupsTableMap::COL_ISSUBSTITUTE, $this->issubstitute);
        }
        if ($this->isColumnModified(LineupsTableMap::COL_TIMEIN)) {
            $criteria->add(LineupsTableMap::COL_TIMEIN, $this->timein);
        }
        if ($this->isColumnModified(LineupsTableMap::COL_TEAMPLAYERREPLACEDKEY)) {
            $criteria->add(LineupsTableMap::COL_TEAMPLAYERREPLACEDKEY, $this->teamplayerreplacedkey);
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
        $criteria = ChildLineupsQuery::create();
        $criteria->add(LineupsTableMap::COL_MATCHKEY, $this->matchkey);
        $criteria->add(LineupsTableMap::COL_TEAMKEY, $this->teamkey);
        $criteria->add(LineupsTableMap::COL_TEAMPLAYERKEY, $this->teamplayerkey);

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
        $validPk = null !== $this->getMatchkey() &&
            null !== $this->getTeamkey() &&
            null !== $this->getTeamplayerkey();

        $validPrimaryKeyFKs = 3;
        $primaryKeyFKs = [];

        //relation lineups_fk_0ba10f to table matches
        if ($this->aLineUpMatch && $hash = spl_object_hash($this->aLineUpMatch)) {
            $primaryKeyFKs[] = $hash;
        } else {
            $validPrimaryKeyFKs = false;
        }

        //relation lineups_fk_33a421 to table teams
        if ($this->aLineUpTeam && $hash = spl_object_hash($this->aLineUpTeam)) {
            $primaryKeyFKs[] = $hash;
        } else {
            $validPrimaryKeyFKs = false;
        }

        //relation lineups_fk_97ca7e to table teamplayers
        if ($this->aLineUpTeamPlayer && $hash = spl_object_hash($this->aLineUpTeamPlayer)) {
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
        $pks[0] = $this->getMatchkey();
        $pks[1] = $this->getTeamkey();
        $pks[2] = $this->getTeamplayerkey();

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
        $this->setMatchkey($keys[0]);
        $this->setTeamkey($keys[1]);
        $this->setTeamplayerkey($keys[2]);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {
        return (null === $this->getMatchkey()) && (null === $this->getTeamkey()) && (null === $this->getTeamplayerkey());
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of \Lineups (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setMatchkey($this->getMatchkey());
        $copyObj->setTeamkey($this->getTeamkey());
        $copyObj->setTeamplayerkey($this->getTeamplayerkey());
        $copyObj->setIssubstitute($this->getIssubstitute());
        $copyObj->setTimein($this->getTimein());
        $copyObj->setTeamplayerreplacedkey($this->getTeamplayerreplacedkey());
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
     * @return \Lineups Clone of current object.
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
     * Declares an association between this object and a ChildMatches object.
     *
     * @param  ChildMatches $v
     * @return $this|\Lineups The current object (for fluent API support)
     * @throws PropelException
     */
    public function setLineUpMatch(ChildMatches $v = null)
    {
        if ($v === null) {
            $this->setMatchkey(NULL);
        } else {
            $this->setMatchkey($v->getMatchPK());
        }

        $this->aLineUpMatch = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildMatches object, it will not be re-added.
        if ($v !== null) {
            $v->addLineups($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildMatches object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildMatches The associated ChildMatches object.
     * @throws PropelException
     */
    public function getLineUpMatch(ConnectionInterface $con = null)
    {
        if ($this->aLineUpMatch === null && ($this->matchkey !== null)) {
            $this->aLineUpMatch = ChildMatchesQuery::create()->findPk($this->matchkey, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aLineUpMatch->addLineupss($this);
             */
        }

        return $this->aLineUpMatch;
    }

    /**
     * Declares an association between this object and a ChildTeams object.
     *
     * @param  ChildTeams $v
     * @return $this|\Lineups The current object (for fluent API support)
     * @throws PropelException
     */
    public function setLineUpTeam(ChildTeams $v = null)
    {
        if ($v === null) {
            $this->setTeamkey(NULL);
        } else {
            $this->setTeamkey($v->getTeamPK());
        }

        $this->aLineUpTeam = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildTeams object, it will not be re-added.
        if ($v !== null) {
            $v->addLineups($this);
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
    public function getLineUpTeam(ConnectionInterface $con = null)
    {
        if ($this->aLineUpTeam === null && ($this->teamkey !== null)) {
            $this->aLineUpTeam = ChildTeamsQuery::create()->findPk($this->teamkey, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aLineUpTeam->addLineupss($this);
             */
        }

        return $this->aLineUpTeam;
    }

    /**
     * Declares an association between this object and a ChildTeamplayers object.
     *
     * @param  ChildTeamplayers $v
     * @return $this|\Lineups The current object (for fluent API support)
     * @throws PropelException
     */
    public function setLineUpTeamPlayer(ChildTeamplayers $v = null)
    {
        if ($v === null) {
            $this->setTeamplayerkey(NULL);
        } else {
            $this->setTeamplayerkey($v->getTeamPlayerPK());
        }

        $this->aLineUpTeamPlayer = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildTeamplayers object, it will not be re-added.
        if ($v !== null) {
            $v->addLineups($this);
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
    public function getLineUpTeamPlayer(ConnectionInterface $con = null)
    {
        if ($this->aLineUpTeamPlayer === null && ($this->teamplayerkey !== null)) {
            $this->aLineUpTeamPlayer = ChildTeamplayersQuery::create()->findPk($this->teamplayerkey, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aLineUpTeamPlayer->addLineupss($this);
             */
        }

        return $this->aLineUpTeamPlayer;
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        if (null !== $this->aLineUpMatch) {
            $this->aLineUpMatch->removeLineups($this);
        }
        if (null !== $this->aLineUpTeam) {
            $this->aLineUpTeam->removeLineups($this);
        }
        if (null !== $this->aLineUpTeamPlayer) {
            $this->aLineUpTeamPlayer->removeLineups($this);
        }
        $this->matchkey = null;
        $this->teamkey = null;
        $this->teamplayerkey = null;
        $this->issubstitute = null;
        $this->timein = null;
        $this->teamplayerreplacedkey = null;
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

        $this->aLineUpMatch = null;
        $this->aLineUpTeam = null;
        $this->aLineUpTeamPlayer = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(LineupsTableMap::DEFAULT_STRING_FORMAT);
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
