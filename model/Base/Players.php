<?php

namespace Base;

use \PlayersQuery as ChildPlayersQuery;
use \DateTime;
use \Exception;
use \PDO;
use Map\PlayersTableMap;
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
 * Base class that represents a row from the 'players' table.
 *
 *
 *
* @package    propel.generator..Base
*/
abstract class Players implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\Map\\PlayersTableMap';


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
     * The value for the nickname field.
     * @var        string
     */
    protected $nickname;

    /**
     * The value for the firstname field.
     * @var        string
     */
    protected $firstname;

    /**
     * The value for the lastname field.
     * @var        string
     */
    protected $lastname;

    /**
     * The value for the emailaddress field.
     * @var        string
     */
    protected $emailaddress;

    /**
     * The value for the password field.
     * @var        string
     */
    protected $password;

    /**
     * The value for the isadministrator field.
     * Note: this column has a database default value of: false
     * @var        boolean
     */
    protected $isadministrator;

    /**
     * The value for the activationkey field.
     * @var        string
     */
    protected $activationkey;

    /**
     * The value for the isenabled field.
     * Note: this column has a database default value of: true
     * @var        boolean
     */
    protected $isenabled;

    /**
     * The value for the lastconnection field.
     * @var        \DateTime
     */
    protected $lastconnection;

    /**
     * The value for the token field.
     * @var        string
     */
    protected $token;

    /**
     * The value for the avatarname field.
     * @var        string
     */
    protected $avatarname;

    /**
     * The value for the creationdate field.
     * @var        \DateTime
     */
    protected $creationdate;

    /**
     * The value for the iscalendardefaultview field.
     * Note: this column has a database default value of: false
     * @var        boolean
     */
    protected $iscalendardefaultview;

    /**
     * The value for the receivealert field.
     * Note: this column has a database default value of: true
     * @var        boolean
     */
    protected $receivealert;

    /**
     * The value for the receivenewletter field.
     * Note: this column has a database default value of: true
     * @var        boolean
     */
    protected $receivenewletter;

    /**
     * The value for the receiveresult field.
     * Note: this column has a database default value of: true
     * @var        boolean
     */
    protected $receiveresult;

    /**
     * The value for the isreminderemailsent field.
     * Note: this column has a database default value of: false
     * @var        boolean
     */
    protected $isreminderemailsent;

    /**
     * The value for the isresultemailsent field.
     * Note: this column has a database default value of: false
     * @var        boolean
     */
    protected $isresultemailsent;

    /**
     * The value for the isemailvalid field.
     * Note: this column has a database default value of: false
     * @var        boolean
     */
    protected $isemailvalid;

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
        $this->isadministrator = false;
        $this->isenabled = true;
        $this->iscalendardefaultview = false;
        $this->receivealert = true;
        $this->receivenewletter = true;
        $this->receiveresult = true;
        $this->isreminderemailsent = false;
        $this->isresultemailsent = false;
        $this->isemailvalid = false;
    }

    /**
     * Initializes internal state of Base\Players object.
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
     * Compares this with another <code>Players</code> instance.  If
     * <code>obj</code> is an instance of <code>Players</code>, delegates to
     * <code>equals(Players)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|Players The current object, for fluid interface
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
    public function getPlayerPK()
    {
        return $this->primarykey;
    }

    /**
     * Get the [nickname] column value.
     *
     * @return string
     */
    public function getNickname()
    {
        return $this->nickname;
    }

    /**
     * Get the [firstname] column value.
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Get the [lastname] column value.
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Get the [emailaddress] column value.
     *
     * @return string
     */
    public function getEmailaddress()
    {
        return $this->emailaddress;
    }

    /**
     * Get the [password] column value.
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Get the [isadministrator] column value.
     *
     * @return boolean
     */
    public function getIsadministrator()
    {
        return $this->isadministrator;
    }

    /**
     * Get the [isadministrator] column value.
     *
     * @return boolean
     */
    public function isIsadministrator()
    {
        return $this->getIsadministrator();
    }

    /**
     * Get the [activationkey] column value.
     *
     * @return string
     */
    public function getActivationkey()
    {
        return $this->activationkey;
    }

    /**
     * Get the [isenabled] column value.
     *
     * @return boolean
     */
    public function getIsenabled()
    {
        return $this->isenabled;
    }

    /**
     * Get the [isenabled] column value.
     *
     * @return boolean
     */
    public function isIsenabled()
    {
        return $this->getIsenabled();
    }

    /**
     * Get the [optionally formatted] temporal [lastconnection] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getLastconnection($format = NULL)
    {
        if ($format === null) {
            return $this->lastconnection;
        } else {
            return $this->lastconnection instanceof \DateTime ? $this->lastconnection->format($format) : null;
        }
    }

    /**
     * Get the [token] column value.
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Get the [avatarname] column value.
     *
     * @return string
     */
    public function getAvatarname()
    {
        return $this->avatarname;
    }

    /**
     * Get the [optionally formatted] temporal [creationdate] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getCreationdate($format = NULL)
    {
        if ($format === null) {
            return $this->creationdate;
        } else {
            return $this->creationdate instanceof \DateTime ? $this->creationdate->format($format) : null;
        }
    }

    /**
     * Get the [iscalendardefaultview] column value.
     *
     * @return boolean
     */
    public function getIscalendardefaultview()
    {
        return $this->iscalendardefaultview;
    }

    /**
     * Get the [iscalendardefaultview] column value.
     *
     * @return boolean
     */
    public function isIscalendardefaultview()
    {
        return $this->getIscalendardefaultview();
    }

    /**
     * Get the [receivealert] column value.
     *
     * @return boolean
     */
    public function getReceivealert()
    {
        return $this->receivealert;
    }

    /**
     * Get the [receivealert] column value.
     *
     * @return boolean
     */
    public function isReceivealert()
    {
        return $this->getReceivealert();
    }

    /**
     * Get the [receivenewletter] column value.
     *
     * @return boolean
     */
    public function getReceivenewletter()
    {
        return $this->receivenewletter;
    }

    /**
     * Get the [receivenewletter] column value.
     *
     * @return boolean
     */
    public function isReceivenewletter()
    {
        return $this->getReceivenewletter();
    }

    /**
     * Get the [receiveresult] column value.
     *
     * @return boolean
     */
    public function getReceiveresult()
    {
        return $this->receiveresult;
    }

    /**
     * Get the [receiveresult] column value.
     *
     * @return boolean
     */
    public function isReceiveresult()
    {
        return $this->getReceiveresult();
    }

    /**
     * Get the [isreminderemailsent] column value.
     *
     * @return boolean
     */
    public function getIsreminderemailsent()
    {
        return $this->isreminderemailsent;
    }

    /**
     * Get the [isreminderemailsent] column value.
     *
     * @return boolean
     */
    public function isIsreminderemailsent()
    {
        return $this->getIsreminderemailsent();
    }

    /**
     * Get the [isresultemailsent] column value.
     *
     * @return boolean
     */
    public function getIsresultemailsent()
    {
        return $this->isresultemailsent;
    }

    /**
     * Get the [isresultemailsent] column value.
     *
     * @return boolean
     */
    public function isIsresultemailsent()
    {
        return $this->getIsresultemailsent();
    }

    /**
     * Get the [isemailvalid] column value.
     *
     * @return boolean
     */
    public function getIsemailvalid()
    {
        return $this->isemailvalid;
    }

    /**
     * Get the [isemailvalid] column value.
     *
     * @return boolean
     */
    public function isIsemailvalid()
    {
        return $this->getIsemailvalid();
    }

    /**
     * Set the value of [primarykey] column.
     *
     * @param int $v new value
     * @return $this|\Players The current object (for fluent API support)
     */
    public function setPlayerPK($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->primarykey !== $v) {
            $this->primarykey = $v;
            $this->modifiedColumns[PlayersTableMap::COL_PRIMARYKEY] = true;
        }

        return $this;
    } // setPlayerPK()

    /**
     * Set the value of [nickname] column.
     *
     * @param string $v new value
     * @return $this|\Players The current object (for fluent API support)
     */
    public function setNickname($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->nickname !== $v) {
            $this->nickname = $v;
            $this->modifiedColumns[PlayersTableMap::COL_NICKNAME] = true;
        }

        return $this;
    } // setNickname()

    /**
     * Set the value of [firstname] column.
     *
     * @param string $v new value
     * @return $this|\Players The current object (for fluent API support)
     */
    public function setFirstname($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->firstname !== $v) {
            $this->firstname = $v;
            $this->modifiedColumns[PlayersTableMap::COL_FIRSTNAME] = true;
        }

        return $this;
    } // setFirstname()

    /**
     * Set the value of [lastname] column.
     *
     * @param string $v new value
     * @return $this|\Players The current object (for fluent API support)
     */
    public function setLastname($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->lastname !== $v) {
            $this->lastname = $v;
            $this->modifiedColumns[PlayersTableMap::COL_LASTNAME] = true;
        }

        return $this;
    } // setLastname()

    /**
     * Set the value of [emailaddress] column.
     *
     * @param string $v new value
     * @return $this|\Players The current object (for fluent API support)
     */
    public function setEmailaddress($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->emailaddress !== $v) {
            $this->emailaddress = $v;
            $this->modifiedColumns[PlayersTableMap::COL_EMAILADDRESS] = true;
        }

        return $this;
    } // setEmailaddress()

    /**
     * Set the value of [password] column.
     *
     * @param string $v new value
     * @return $this|\Players The current object (for fluent API support)
     */
    public function setPassword($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->password !== $v) {
            $this->password = $v;
            $this->modifiedColumns[PlayersTableMap::COL_PASSWORD] = true;
        }

        return $this;
    } // setPassword()

    /**
     * Sets the value of the [isadministrator] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param  boolean|integer|string $v The new value
     * @return $this|\Players The current object (for fluent API support)
     */
    public function setIsadministrator($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->isadministrator !== $v) {
            $this->isadministrator = $v;
            $this->modifiedColumns[PlayersTableMap::COL_ISADMINISTRATOR] = true;
        }

        return $this;
    } // setIsadministrator()

    /**
     * Set the value of [activationkey] column.
     *
     * @param string $v new value
     * @return $this|\Players The current object (for fluent API support)
     */
    public function setActivationkey($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->activationkey !== $v) {
            $this->activationkey = $v;
            $this->modifiedColumns[PlayersTableMap::COL_ACTIVATIONKEY] = true;
        }

        return $this;
    } // setActivationkey()

    /**
     * Sets the value of the [isenabled] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param  boolean|integer|string $v The new value
     * @return $this|\Players The current object (for fluent API support)
     */
    public function setIsenabled($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->isenabled !== $v) {
            $this->isenabled = $v;
            $this->modifiedColumns[PlayersTableMap::COL_ISENABLED] = true;
        }

        return $this;
    } // setIsenabled()

    /**
     * Sets the value of [lastconnection] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTime value.
     *               Empty strings are treated as NULL.
     * @return $this|\Players The current object (for fluent API support)
     */
    public function setLastconnection($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->lastconnection !== null || $dt !== null) {
            if ($this->lastconnection === null || $dt === null || $dt->format("Y-m-d") !== $this->lastconnection->format("Y-m-d")) {
                $this->lastconnection = $dt === null ? null : clone $dt;
                $this->modifiedColumns[PlayersTableMap::COL_LASTCONNECTION] = true;
            }
        } // if either are not null

        return $this;
    } // setLastconnection()

    /**
     * Set the value of [token] column.
     *
     * @param string $v new value
     * @return $this|\Players The current object (for fluent API support)
     */
    public function setToken($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->token !== $v) {
            $this->token = $v;
            $this->modifiedColumns[PlayersTableMap::COL_TOKEN] = true;
        }

        return $this;
    } // setToken()

    /**
     * Set the value of [avatarname] column.
     *
     * @param string $v new value
     * @return $this|\Players The current object (for fluent API support)
     */
    public function setAvatarname($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->avatarname !== $v) {
            $this->avatarname = $v;
            $this->modifiedColumns[PlayersTableMap::COL_AVATARNAME] = true;
        }

        return $this;
    } // setAvatarname()

    /**
     * Sets the value of [creationdate] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTime value.
     *               Empty strings are treated as NULL.
     * @return $this|\Players The current object (for fluent API support)
     */
    public function setCreationdate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->creationdate !== null || $dt !== null) {
            if ($this->creationdate === null || $dt === null || $dt->format("Y-m-d") !== $this->creationdate->format("Y-m-d")) {
                $this->creationdate = $dt === null ? null : clone $dt;
                $this->modifiedColumns[PlayersTableMap::COL_CREATIONDATE] = true;
            }
        } // if either are not null

        return $this;
    } // setCreationdate()

    /**
     * Sets the value of the [iscalendardefaultview] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param  boolean|integer|string $v The new value
     * @return $this|\Players The current object (for fluent API support)
     */
    public function setIscalendardefaultview($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->iscalendardefaultview !== $v) {
            $this->iscalendardefaultview = $v;
            $this->modifiedColumns[PlayersTableMap::COL_ISCALENDARDEFAULTVIEW] = true;
        }

        return $this;
    } // setIscalendardefaultview()

    /**
     * Sets the value of the [receivealert] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param  boolean|integer|string $v The new value
     * @return $this|\Players The current object (for fluent API support)
     */
    public function setReceivealert($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->receivealert !== $v) {
            $this->receivealert = $v;
            $this->modifiedColumns[PlayersTableMap::COL_RECEIVEALERT] = true;
        }

        return $this;
    } // setReceivealert()

    /**
     * Sets the value of the [receivenewletter] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param  boolean|integer|string $v The new value
     * @return $this|\Players The current object (for fluent API support)
     */
    public function setReceivenewletter($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->receivenewletter !== $v) {
            $this->receivenewletter = $v;
            $this->modifiedColumns[PlayersTableMap::COL_RECEIVENEWLETTER] = true;
        }

        return $this;
    } // setReceivenewletter()

    /**
     * Sets the value of the [receiveresult] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param  boolean|integer|string $v The new value
     * @return $this|\Players The current object (for fluent API support)
     */
    public function setReceiveresult($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->receiveresult !== $v) {
            $this->receiveresult = $v;
            $this->modifiedColumns[PlayersTableMap::COL_RECEIVERESULT] = true;
        }

        return $this;
    } // setReceiveresult()

    /**
     * Sets the value of the [isreminderemailsent] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param  boolean|integer|string $v The new value
     * @return $this|\Players The current object (for fluent API support)
     */
    public function setIsreminderemailsent($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->isreminderemailsent !== $v) {
            $this->isreminderemailsent = $v;
            $this->modifiedColumns[PlayersTableMap::COL_ISREMINDEREMAILSENT] = true;
        }

        return $this;
    } // setIsreminderemailsent()

    /**
     * Sets the value of the [isresultemailsent] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param  boolean|integer|string $v The new value
     * @return $this|\Players The current object (for fluent API support)
     */
    public function setIsresultemailsent($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->isresultemailsent !== $v) {
            $this->isresultemailsent = $v;
            $this->modifiedColumns[PlayersTableMap::COL_ISRESULTEMAILSENT] = true;
        }

        return $this;
    } // setIsresultemailsent()

    /**
     * Sets the value of the [isemailvalid] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param  boolean|integer|string $v The new value
     * @return $this|\Players The current object (for fluent API support)
     */
    public function setIsemailvalid($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->isemailvalid !== $v) {
            $this->isemailvalid = $v;
            $this->modifiedColumns[PlayersTableMap::COL_ISEMAILVALID] = true;
        }

        return $this;
    } // setIsemailvalid()

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
            if ($this->isadministrator !== false) {
                return false;
            }

            if ($this->isenabled !== true) {
                return false;
            }

            if ($this->iscalendardefaultview !== false) {
                return false;
            }

            if ($this->receivealert !== true) {
                return false;
            }

            if ($this->receivenewletter !== true) {
                return false;
            }

            if ($this->receiveresult !== true) {
                return false;
            }

            if ($this->isreminderemailsent !== false) {
                return false;
            }

            if ($this->isresultemailsent !== false) {
                return false;
            }

            if ($this->isemailvalid !== false) {
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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : PlayersTableMap::translateFieldName('PlayerPK', TableMap::TYPE_PHPNAME, $indexType)];
            $this->primarykey = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : PlayersTableMap::translateFieldName('Nickname', TableMap::TYPE_PHPNAME, $indexType)];
            $this->nickname = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : PlayersTableMap::translateFieldName('Firstname', TableMap::TYPE_PHPNAME, $indexType)];
            $this->firstname = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : PlayersTableMap::translateFieldName('Lastname', TableMap::TYPE_PHPNAME, $indexType)];
            $this->lastname = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : PlayersTableMap::translateFieldName('Emailaddress', TableMap::TYPE_PHPNAME, $indexType)];
            $this->emailaddress = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : PlayersTableMap::translateFieldName('Password', TableMap::TYPE_PHPNAME, $indexType)];
            $this->password = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : PlayersTableMap::translateFieldName('Isadministrator', TableMap::TYPE_PHPNAME, $indexType)];
            $this->isadministrator = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : PlayersTableMap::translateFieldName('Activationkey', TableMap::TYPE_PHPNAME, $indexType)];
            $this->activationkey = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : PlayersTableMap::translateFieldName('Isenabled', TableMap::TYPE_PHPNAME, $indexType)];
            $this->isenabled = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 9 + $startcol : PlayersTableMap::translateFieldName('Lastconnection', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00') {
                $col = null;
            }
            $this->lastconnection = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 10 + $startcol : PlayersTableMap::translateFieldName('Token', TableMap::TYPE_PHPNAME, $indexType)];
            $this->token = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 11 + $startcol : PlayersTableMap::translateFieldName('Avatarname', TableMap::TYPE_PHPNAME, $indexType)];
            $this->avatarname = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 12 + $startcol : PlayersTableMap::translateFieldName('Creationdate', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00') {
                $col = null;
            }
            $this->creationdate = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 13 + $startcol : PlayersTableMap::translateFieldName('Iscalendardefaultview', TableMap::TYPE_PHPNAME, $indexType)];
            $this->iscalendardefaultview = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 14 + $startcol : PlayersTableMap::translateFieldName('Receivealert', TableMap::TYPE_PHPNAME, $indexType)];
            $this->receivealert = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 15 + $startcol : PlayersTableMap::translateFieldName('Receivenewletter', TableMap::TYPE_PHPNAME, $indexType)];
            $this->receivenewletter = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 16 + $startcol : PlayersTableMap::translateFieldName('Receiveresult', TableMap::TYPE_PHPNAME, $indexType)];
            $this->receiveresult = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 17 + $startcol : PlayersTableMap::translateFieldName('Isreminderemailsent', TableMap::TYPE_PHPNAME, $indexType)];
            $this->isreminderemailsent = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 18 + $startcol : PlayersTableMap::translateFieldName('Isresultemailsent', TableMap::TYPE_PHPNAME, $indexType)];
            $this->isresultemailsent = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 19 + $startcol : PlayersTableMap::translateFieldName('Isemailvalid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->isemailvalid = (null !== $col) ? (boolean) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 20; // 20 = PlayersTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Players'), 0, $e);
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
            $con = Propel::getServiceContainer()->getReadConnection(PlayersTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildPlayersQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
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
     * @see Players::setDeleted()
     * @see Players::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(PlayersTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildPlayersQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(PlayersTableMap::DATABASE_NAME);
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
                PlayersTableMap::addInstanceToPool($this);
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

        $this->modifiedColumns[PlayersTableMap::COL_PRIMARYKEY] = true;
        if (null !== $this->primarykey) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . PlayersTableMap::COL_PRIMARYKEY . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(PlayersTableMap::COL_PRIMARYKEY)) {
            $modifiedColumns[':p' . $index++]  = 'PrimaryKey';
        }
        if ($this->isColumnModified(PlayersTableMap::COL_NICKNAME)) {
            $modifiedColumns[':p' . $index++]  = 'NickName';
        }
        if ($this->isColumnModified(PlayersTableMap::COL_FIRSTNAME)) {
            $modifiedColumns[':p' . $index++]  = 'FirstName';
        }
        if ($this->isColumnModified(PlayersTableMap::COL_LASTNAME)) {
            $modifiedColumns[':p' . $index++]  = 'LastName';
        }
        if ($this->isColumnModified(PlayersTableMap::COL_EMAILADDRESS)) {
            $modifiedColumns[':p' . $index++]  = 'EmailAddress';
        }
        if ($this->isColumnModified(PlayersTableMap::COL_PASSWORD)) {
            $modifiedColumns[':p' . $index++]  = 'Password';
        }
        if ($this->isColumnModified(PlayersTableMap::COL_ISADMINISTRATOR)) {
            $modifiedColumns[':p' . $index++]  = 'IsAdministrator';
        }
        if ($this->isColumnModified(PlayersTableMap::COL_ACTIVATIONKEY)) {
            $modifiedColumns[':p' . $index++]  = 'ActivationKey';
        }
        if ($this->isColumnModified(PlayersTableMap::COL_ISENABLED)) {
            $modifiedColumns[':p' . $index++]  = 'IsEnabled';
        }
        if ($this->isColumnModified(PlayersTableMap::COL_LASTCONNECTION)) {
            $modifiedColumns[':p' . $index++]  = 'LastConnection';
        }
        if ($this->isColumnModified(PlayersTableMap::COL_TOKEN)) {
            $modifiedColumns[':p' . $index++]  = 'Token';
        }
        if ($this->isColumnModified(PlayersTableMap::COL_AVATARNAME)) {
            $modifiedColumns[':p' . $index++]  = 'AvatarName';
        }
        if ($this->isColumnModified(PlayersTableMap::COL_CREATIONDATE)) {
            $modifiedColumns[':p' . $index++]  = 'CreationDate';
        }
        if ($this->isColumnModified(PlayersTableMap::COL_ISCALENDARDEFAULTVIEW)) {
            $modifiedColumns[':p' . $index++]  = 'IsCalendarDefaultView';
        }
        if ($this->isColumnModified(PlayersTableMap::COL_RECEIVEALERT)) {
            $modifiedColumns[':p' . $index++]  = 'ReceiveAlert';
        }
        if ($this->isColumnModified(PlayersTableMap::COL_RECEIVENEWLETTER)) {
            $modifiedColumns[':p' . $index++]  = 'ReceiveNewletter';
        }
        if ($this->isColumnModified(PlayersTableMap::COL_RECEIVERESULT)) {
            $modifiedColumns[':p' . $index++]  = 'ReceiveResult';
        }
        if ($this->isColumnModified(PlayersTableMap::COL_ISREMINDEREMAILSENT)) {
            $modifiedColumns[':p' . $index++]  = 'IsReminderEmailSent';
        }
        if ($this->isColumnModified(PlayersTableMap::COL_ISRESULTEMAILSENT)) {
            $modifiedColumns[':p' . $index++]  = 'IsResultEmailSent';
        }
        if ($this->isColumnModified(PlayersTableMap::COL_ISEMAILVALID)) {
            $modifiedColumns[':p' . $index++]  = 'IsEmailValid';
        }

        $sql = sprintf(
            'INSERT INTO players (%s) VALUES (%s)',
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
                    case 'NickName':
                        $stmt->bindValue($identifier, $this->nickname, PDO::PARAM_STR);
                        break;
                    case 'FirstName':
                        $stmt->bindValue($identifier, $this->firstname, PDO::PARAM_STR);
                        break;
                    case 'LastName':
                        $stmt->bindValue($identifier, $this->lastname, PDO::PARAM_STR);
                        break;
                    case 'EmailAddress':
                        $stmt->bindValue($identifier, $this->emailaddress, PDO::PARAM_STR);
                        break;
                    case 'Password':
                        $stmt->bindValue($identifier, $this->password, PDO::PARAM_STR);
                        break;
                    case 'IsAdministrator':
                        $stmt->bindValue($identifier, (int) $this->isadministrator, PDO::PARAM_INT);
                        break;
                    case 'ActivationKey':
                        $stmt->bindValue($identifier, $this->activationkey, PDO::PARAM_STR);
                        break;
                    case 'IsEnabled':
                        $stmt->bindValue($identifier, (int) $this->isenabled, PDO::PARAM_INT);
                        break;
                    case 'LastConnection':
                        $stmt->bindValue($identifier, $this->lastconnection ? $this->lastconnection->format("Y-m-d H:i:s") : null, PDO::PARAM_STR);
                        break;
                    case 'Token':
                        $stmt->bindValue($identifier, $this->token, PDO::PARAM_STR);
                        break;
                    case 'AvatarName':
                        $stmt->bindValue($identifier, $this->avatarname, PDO::PARAM_STR);
                        break;
                    case 'CreationDate':
                        $stmt->bindValue($identifier, $this->creationdate ? $this->creationdate->format("Y-m-d H:i:s") : null, PDO::PARAM_STR);
                        break;
                    case 'IsCalendarDefaultView':
                        $stmt->bindValue($identifier, (int) $this->iscalendardefaultview, PDO::PARAM_INT);
                        break;
                    case 'ReceiveAlert':
                        $stmt->bindValue($identifier, (int) $this->receivealert, PDO::PARAM_INT);
                        break;
                    case 'ReceiveNewletter':
                        $stmt->bindValue($identifier, (int) $this->receivenewletter, PDO::PARAM_INT);
                        break;
                    case 'ReceiveResult':
                        $stmt->bindValue($identifier, (int) $this->receiveresult, PDO::PARAM_INT);
                        break;
                    case 'IsReminderEmailSent':
                        $stmt->bindValue($identifier, (int) $this->isreminderemailsent, PDO::PARAM_INT);
                        break;
                    case 'IsResultEmailSent':
                        $stmt->bindValue($identifier, (int) $this->isresultemailsent, PDO::PARAM_INT);
                        break;
                    case 'IsEmailValid':
                        $stmt->bindValue($identifier, (int) $this->isemailvalid, PDO::PARAM_INT);
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
        $this->setPlayerPK($pk);

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
        $pos = PlayersTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getPlayerPK();
                break;
            case 1:
                return $this->getNickname();
                break;
            case 2:
                return $this->getFirstname();
                break;
            case 3:
                return $this->getLastname();
                break;
            case 4:
                return $this->getEmailaddress();
                break;
            case 5:
                return $this->getPassword();
                break;
            case 6:
                return $this->getIsadministrator();
                break;
            case 7:
                return $this->getActivationkey();
                break;
            case 8:
                return $this->getIsenabled();
                break;
            case 9:
                return $this->getLastconnection();
                break;
            case 10:
                return $this->getToken();
                break;
            case 11:
                return $this->getAvatarname();
                break;
            case 12:
                return $this->getCreationdate();
                break;
            case 13:
                return $this->getIscalendardefaultview();
                break;
            case 14:
                return $this->getReceivealert();
                break;
            case 15:
                return $this->getReceivenewletter();
                break;
            case 16:
                return $this->getReceiveresult();
                break;
            case 17:
                return $this->getIsreminderemailsent();
                break;
            case 18:
                return $this->getIsresultemailsent();
                break;
            case 19:
                return $this->getIsemailvalid();
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

        if (isset($alreadyDumpedObjects['Players'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Players'][$this->hashCode()] = true;
        $keys = PlayersTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getPlayerPK(),
            $keys[1] => $this->getNickname(),
            $keys[2] => $this->getFirstname(),
            $keys[3] => $this->getLastname(),
            $keys[4] => $this->getEmailaddress(),
            $keys[5] => $this->getPassword(),
            $keys[6] => $this->getIsadministrator(),
            $keys[7] => $this->getActivationkey(),
            $keys[8] => $this->getIsenabled(),
            $keys[9] => $this->getLastconnection(),
            $keys[10] => $this->getToken(),
            $keys[11] => $this->getAvatarname(),
            $keys[12] => $this->getCreationdate(),
            $keys[13] => $this->getIscalendardefaultview(),
            $keys[14] => $this->getReceivealert(),
            $keys[15] => $this->getReceivenewletter(),
            $keys[16] => $this->getReceiveresult(),
            $keys[17] => $this->getIsreminderemailsent(),
            $keys[18] => $this->getIsresultemailsent(),
            $keys[19] => $this->getIsemailvalid(),
        );

        $utc = new \DateTimeZone('utc');
        if ($result[$keys[9]] instanceof \DateTime) {
            // When changing timezone we don't want to change existing instances
            $dateTime = clone $result[$keys[9]];
            $result[$keys[9]] = $dateTime->setTimezone($utc)->format('Y-m-d\TH:i:s\Z');
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
     * @return $this|\Players
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = PlayersTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\Players
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setPlayerPK($value);
                break;
            case 1:
                $this->setNickname($value);
                break;
            case 2:
                $this->setFirstname($value);
                break;
            case 3:
                $this->setLastname($value);
                break;
            case 4:
                $this->setEmailaddress($value);
                break;
            case 5:
                $this->setPassword($value);
                break;
            case 6:
                $this->setIsadministrator($value);
                break;
            case 7:
                $this->setActivationkey($value);
                break;
            case 8:
                $this->setIsenabled($value);
                break;
            case 9:
                $this->setLastconnection($value);
                break;
            case 10:
                $this->setToken($value);
                break;
            case 11:
                $this->setAvatarname($value);
                break;
            case 12:
                $this->setCreationdate($value);
                break;
            case 13:
                $this->setIscalendardefaultview($value);
                break;
            case 14:
                $this->setReceivealert($value);
                break;
            case 15:
                $this->setReceivenewletter($value);
                break;
            case 16:
                $this->setReceiveresult($value);
                break;
            case 17:
                $this->setIsreminderemailsent($value);
                break;
            case 18:
                $this->setIsresultemailsent($value);
                break;
            case 19:
                $this->setIsemailvalid($value);
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
        $keys = PlayersTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setPlayerPK($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setNickname($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setFirstname($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setLastname($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setEmailaddress($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setPassword($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setIsadministrator($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setActivationkey($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->setIsenabled($arr[$keys[8]]);
        }
        if (array_key_exists($keys[9], $arr)) {
            $this->setLastconnection($arr[$keys[9]]);
        }
        if (array_key_exists($keys[10], $arr)) {
            $this->setToken($arr[$keys[10]]);
        }
        if (array_key_exists($keys[11], $arr)) {
            $this->setAvatarname($arr[$keys[11]]);
        }
        if (array_key_exists($keys[12], $arr)) {
            $this->setCreationdate($arr[$keys[12]]);
        }
        if (array_key_exists($keys[13], $arr)) {
            $this->setIscalendardefaultview($arr[$keys[13]]);
        }
        if (array_key_exists($keys[14], $arr)) {
            $this->setReceivealert($arr[$keys[14]]);
        }
        if (array_key_exists($keys[15], $arr)) {
            $this->setReceivenewletter($arr[$keys[15]]);
        }
        if (array_key_exists($keys[16], $arr)) {
            $this->setReceiveresult($arr[$keys[16]]);
        }
        if (array_key_exists($keys[17], $arr)) {
            $this->setIsreminderemailsent($arr[$keys[17]]);
        }
        if (array_key_exists($keys[18], $arr)) {
            $this->setIsresultemailsent($arr[$keys[18]]);
        }
        if (array_key_exists($keys[19], $arr)) {
            $this->setIsemailvalid($arr[$keys[19]]);
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
     * @return $this|\Players The current object, for fluid interface
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
        $criteria = new Criteria(PlayersTableMap::DATABASE_NAME);

        if ($this->isColumnModified(PlayersTableMap::COL_PRIMARYKEY)) {
            $criteria->add(PlayersTableMap::COL_PRIMARYKEY, $this->primarykey);
        }
        if ($this->isColumnModified(PlayersTableMap::COL_NICKNAME)) {
            $criteria->add(PlayersTableMap::COL_NICKNAME, $this->nickname);
        }
        if ($this->isColumnModified(PlayersTableMap::COL_FIRSTNAME)) {
            $criteria->add(PlayersTableMap::COL_FIRSTNAME, $this->firstname);
        }
        if ($this->isColumnModified(PlayersTableMap::COL_LASTNAME)) {
            $criteria->add(PlayersTableMap::COL_LASTNAME, $this->lastname);
        }
        if ($this->isColumnModified(PlayersTableMap::COL_EMAILADDRESS)) {
            $criteria->add(PlayersTableMap::COL_EMAILADDRESS, $this->emailaddress);
        }
        if ($this->isColumnModified(PlayersTableMap::COL_PASSWORD)) {
            $criteria->add(PlayersTableMap::COL_PASSWORD, $this->password);
        }
        if ($this->isColumnModified(PlayersTableMap::COL_ISADMINISTRATOR)) {
            $criteria->add(PlayersTableMap::COL_ISADMINISTRATOR, $this->isadministrator);
        }
        if ($this->isColumnModified(PlayersTableMap::COL_ACTIVATIONKEY)) {
            $criteria->add(PlayersTableMap::COL_ACTIVATIONKEY, $this->activationkey);
        }
        if ($this->isColumnModified(PlayersTableMap::COL_ISENABLED)) {
            $criteria->add(PlayersTableMap::COL_ISENABLED, $this->isenabled);
        }
        if ($this->isColumnModified(PlayersTableMap::COL_LASTCONNECTION)) {
            $criteria->add(PlayersTableMap::COL_LASTCONNECTION, $this->lastconnection);
        }
        if ($this->isColumnModified(PlayersTableMap::COL_TOKEN)) {
            $criteria->add(PlayersTableMap::COL_TOKEN, $this->token);
        }
        if ($this->isColumnModified(PlayersTableMap::COL_AVATARNAME)) {
            $criteria->add(PlayersTableMap::COL_AVATARNAME, $this->avatarname);
        }
        if ($this->isColumnModified(PlayersTableMap::COL_CREATIONDATE)) {
            $criteria->add(PlayersTableMap::COL_CREATIONDATE, $this->creationdate);
        }
        if ($this->isColumnModified(PlayersTableMap::COL_ISCALENDARDEFAULTVIEW)) {
            $criteria->add(PlayersTableMap::COL_ISCALENDARDEFAULTVIEW, $this->iscalendardefaultview);
        }
        if ($this->isColumnModified(PlayersTableMap::COL_RECEIVEALERT)) {
            $criteria->add(PlayersTableMap::COL_RECEIVEALERT, $this->receivealert);
        }
        if ($this->isColumnModified(PlayersTableMap::COL_RECEIVENEWLETTER)) {
            $criteria->add(PlayersTableMap::COL_RECEIVENEWLETTER, $this->receivenewletter);
        }
        if ($this->isColumnModified(PlayersTableMap::COL_RECEIVERESULT)) {
            $criteria->add(PlayersTableMap::COL_RECEIVERESULT, $this->receiveresult);
        }
        if ($this->isColumnModified(PlayersTableMap::COL_ISREMINDEREMAILSENT)) {
            $criteria->add(PlayersTableMap::COL_ISREMINDEREMAILSENT, $this->isreminderemailsent);
        }
        if ($this->isColumnModified(PlayersTableMap::COL_ISRESULTEMAILSENT)) {
            $criteria->add(PlayersTableMap::COL_ISRESULTEMAILSENT, $this->isresultemailsent);
        }
        if ($this->isColumnModified(PlayersTableMap::COL_ISEMAILVALID)) {
            $criteria->add(PlayersTableMap::COL_ISEMAILVALID, $this->isemailvalid);
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
        $criteria = ChildPlayersQuery::create();
        $criteria->add(PlayersTableMap::COL_PRIMARYKEY, $this->primarykey);

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
        $validPk = null !== $this->getPlayerPK();

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
        return $this->getPlayerPK();
    }

    /**
     * Generic method to set the primary key (primarykey column).
     *
     * @param       int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setPlayerPK($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {
        return null === $this->getPlayerPK();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of \Players (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setNickname($this->getNickname());
        $copyObj->setFirstname($this->getFirstname());
        $copyObj->setLastname($this->getLastname());
        $copyObj->setEmailaddress($this->getEmailaddress());
        $copyObj->setPassword($this->getPassword());
        $copyObj->setIsadministrator($this->getIsadministrator());
        $copyObj->setActivationkey($this->getActivationkey());
        $copyObj->setIsenabled($this->getIsenabled());
        $copyObj->setLastconnection($this->getLastconnection());
        $copyObj->setToken($this->getToken());
        $copyObj->setAvatarname($this->getAvatarname());
        $copyObj->setCreationdate($this->getCreationdate());
        $copyObj->setIscalendardefaultview($this->getIscalendardefaultview());
        $copyObj->setReceivealert($this->getReceivealert());
        $copyObj->setReceivenewletter($this->getReceivenewletter());
        $copyObj->setReceiveresult($this->getReceiveresult());
        $copyObj->setIsreminderemailsent($this->getIsreminderemailsent());
        $copyObj->setIsresultemailsent($this->getIsresultemailsent());
        $copyObj->setIsemailvalid($this->getIsemailvalid());
        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setPlayerPK(NULL); // this is a auto-increment column, so set to default value
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
     * @return \Players Clone of current object.
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
        $this->nickname = null;
        $this->firstname = null;
        $this->lastname = null;
        $this->emailaddress = null;
        $this->password = null;
        $this->isadministrator = null;
        $this->activationkey = null;
        $this->isenabled = null;
        $this->lastconnection = null;
        $this->token = null;
        $this->avatarname = null;
        $this->creationdate = null;
        $this->iscalendardefaultview = null;
        $this->receivealert = null;
        $this->receivenewletter = null;
        $this->receiveresult = null;
        $this->isreminderemailsent = null;
        $this->isresultemailsent = null;
        $this->isemailvalid = null;
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
        return (string) $this->exportTo(PlayersTableMap::DEFAULT_STRING_FORMAT);
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
