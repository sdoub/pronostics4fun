<?php

namespace Base;

use \Connectedusers as ChildConnectedusers;
use \ConnectedusersQuery as ChildConnectedusersQuery;
use \Forecasts as ChildForecasts;
use \ForecastsQuery as ChildForecastsQuery;
use \Playercupmatches as ChildPlayercupmatches;
use \PlayercupmatchesQuery as ChildPlayercupmatchesQuery;
use \Playerdivisionmatches as ChildPlayerdivisionmatches;
use \PlayerdivisionmatchesQuery as ChildPlayerdivisionmatchesQuery;
use \Playerdivisionranking as ChildPlayerdivisionranking;
use \PlayerdivisionrankingQuery as ChildPlayerdivisionrankingQuery;
use \Playergroupranking as ChildPlayergroupranking;
use \PlayergrouprankingQuery as ChildPlayergrouprankingQuery;
use \Playergroupresults as ChildPlayergroupresults;
use \PlayergroupresultsQuery as ChildPlayergroupresultsQuery;
use \Playergroupstates as ChildPlayergroupstates;
use \PlayergroupstatesQuery as ChildPlayergroupstatesQuery;
use \Playermatchresults as ChildPlayermatchresults;
use \PlayermatchresultsQuery as ChildPlayermatchresultsQuery;
use \Playermatchstates as ChildPlayermatchstates;
use \PlayermatchstatesQuery as ChildPlayermatchstatesQuery;
use \Playerranking as ChildPlayerranking;
use \PlayerrankingQuery as ChildPlayerrankingQuery;
use \Players as ChildPlayers;
use \PlayersQuery as ChildPlayersQuery;
use \Votes as ChildVotes;
use \VotesQuery as ChildVotesQuery;
use \DateTime;
use \Exception;
use \PDO;
use Map\PlayersTableMap;
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
     * @var        ObjectCollection|ChildConnectedusers[] Collection to store aggregation of ChildConnectedusers objects.
     */
    protected $collConnecteduserss;
    protected $collConnecteduserssPartial;

    /**
     * @var        ObjectCollection|ChildForecasts[] Collection to store aggregation of ChildForecasts objects.
     */
    protected $collForecastss;
    protected $collForecastssPartial;

    /**
     * @var        ObjectCollection|ChildPlayercupmatches[] Collection to store aggregation of ChildPlayercupmatches objects.
     */
    protected $collPlayercupmatchessRelatedByPlayerhomekey;
    protected $collPlayercupmatchessRelatedByPlayerhomekeyPartial;

    /**
     * @var        ObjectCollection|ChildPlayercupmatches[] Collection to store aggregation of ChildPlayercupmatches objects.
     */
    protected $collPlayercupmatchessRelatedByPlayerawaykey;
    protected $collPlayercupmatchessRelatedByPlayerawaykeyPartial;

    /**
     * @var        ObjectCollection|ChildPlayercupmatches[] Collection to store aggregation of ChildPlayercupmatches objects.
     */
    protected $collPlayercupmatchessRelatedByCuproundkey;
    protected $collPlayercupmatchessRelatedByCuproundkeyPartial;

    /**
     * @var        ObjectCollection|ChildPlayerdivisionmatches[] Collection to store aggregation of ChildPlayerdivisionmatches objects.
     */
    protected $collPlayerdivisionmatchessRelatedByPlayerhomekey;
    protected $collPlayerdivisionmatchessRelatedByPlayerhomekeyPartial;

    /**
     * @var        ObjectCollection|ChildPlayerdivisionmatches[] Collection to store aggregation of ChildPlayerdivisionmatches objects.
     */
    protected $collPlayerdivisionmatchessRelatedByPlayerawaykey;
    protected $collPlayerdivisionmatchessRelatedByPlayerawaykeyPartial;

    /**
     * @var        ObjectCollection|ChildPlayerdivisionranking[] Collection to store aggregation of ChildPlayerdivisionranking objects.
     */
    protected $collPlayerdivisionrankings;
    protected $collPlayerdivisionrankingsPartial;

    /**
     * @var        ObjectCollection|ChildPlayergroupranking[] Collection to store aggregation of ChildPlayergroupranking objects.
     */
    protected $collPlayergrouprankings;
    protected $collPlayergrouprankingsPartial;

    /**
     * @var        ObjectCollection|ChildPlayergroupresults[] Collection to store aggregation of ChildPlayergroupresults objects.
     */
    protected $collPlayergroupresultss;
    protected $collPlayergroupresultssPartial;

    /**
     * @var        ObjectCollection|ChildPlayergroupstates[] Collection to store aggregation of ChildPlayergroupstates objects.
     */
    protected $collPlayergroupstatess;
    protected $collPlayergroupstatessPartial;

    /**
     * @var        ObjectCollection|ChildPlayermatchresults[] Collection to store aggregation of ChildPlayermatchresults objects.
     */
    protected $collPlayermatchresultss;
    protected $collPlayermatchresultssPartial;

    /**
     * @var        ObjectCollection|ChildPlayermatchstates[] Collection to store aggregation of ChildPlayermatchstates objects.
     */
    protected $collPlayermatchstatess;
    protected $collPlayermatchstatessPartial;

    /**
     * @var        ObjectCollection|ChildPlayerranking[] Collection to store aggregation of ChildPlayerranking objects.
     */
    protected $collPlayerrankings;
    protected $collPlayerrankingsPartial;

    /**
     * @var        ObjectCollection|ChildVotes[] Collection to store aggregation of ChildVotes objects.
     */
    protected $collVotess;
    protected $collVotessPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildConnectedusers[]
     */
    protected $connecteduserssScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildForecasts[]
     */
    protected $forecastssScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildPlayercupmatches[]
     */
    protected $playercupmatchessRelatedByPlayerhomekeyScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildPlayercupmatches[]
     */
    protected $playercupmatchessRelatedByPlayerawaykeyScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildPlayercupmatches[]
     */
    protected $playercupmatchessRelatedByCuproundkeyScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildPlayerdivisionmatches[]
     */
    protected $playerdivisionmatchessRelatedByPlayerhomekeyScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildPlayerdivisionmatches[]
     */
    protected $playerdivisionmatchessRelatedByPlayerawaykeyScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildPlayerdivisionranking[]
     */
    protected $playerdivisionrankingsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildPlayergroupranking[]
     */
    protected $playergrouprankingsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildPlayergroupresults[]
     */
    protected $playergroupresultssScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildPlayergroupstates[]
     */
    protected $playergroupstatessScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildPlayermatchresults[]
     */
    protected $playermatchresultssScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildPlayermatchstates[]
     */
    protected $playermatchstatessScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildPlayerranking[]
     */
    protected $playerrankingsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildVotes[]
     */
    protected $votessScheduledForDeletion = null;

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

            $this->collConnecteduserss = null;

            $this->collForecastss = null;

            $this->collPlayercupmatchessRelatedByPlayerhomekey = null;

            $this->collPlayercupmatchessRelatedByPlayerawaykey = null;

            $this->collPlayercupmatchessRelatedByCuproundkey = null;

            $this->collPlayerdivisionmatchessRelatedByPlayerhomekey = null;

            $this->collPlayerdivisionmatchessRelatedByPlayerawaykey = null;

            $this->collPlayerdivisionrankings = null;

            $this->collPlayergrouprankings = null;

            $this->collPlayergroupresultss = null;

            $this->collPlayergroupstatess = null;

            $this->collPlayermatchresultss = null;

            $this->collPlayermatchstatess = null;

            $this->collPlayerrankings = null;

            $this->collVotess = null;

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

            if ($this->connecteduserssScheduledForDeletion !== null) {
                if (!$this->connecteduserssScheduledForDeletion->isEmpty()) {
                    \ConnectedusersQuery::create()
                        ->filterByPrimaryKeys($this->connecteduserssScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->connecteduserssScheduledForDeletion = null;
                }
            }

            if ($this->collConnecteduserss !== null) {
                foreach ($this->collConnecteduserss as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->forecastssScheduledForDeletion !== null) {
                if (!$this->forecastssScheduledForDeletion->isEmpty()) {
                    \ForecastsQuery::create()
                        ->filterByPrimaryKeys($this->forecastssScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->forecastssScheduledForDeletion = null;
                }
            }

            if ($this->collForecastss !== null) {
                foreach ($this->collForecastss as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->playercupmatchessRelatedByPlayerhomekeyScheduledForDeletion !== null) {
                if (!$this->playercupmatchessRelatedByPlayerhomekeyScheduledForDeletion->isEmpty()) {
                    \PlayercupmatchesQuery::create()
                        ->filterByPrimaryKeys($this->playercupmatchessRelatedByPlayerhomekeyScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->playercupmatchessRelatedByPlayerhomekeyScheduledForDeletion = null;
                }
            }

            if ($this->collPlayercupmatchessRelatedByPlayerhomekey !== null) {
                foreach ($this->collPlayercupmatchessRelatedByPlayerhomekey as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->playercupmatchessRelatedByPlayerawaykeyScheduledForDeletion !== null) {
                if (!$this->playercupmatchessRelatedByPlayerawaykeyScheduledForDeletion->isEmpty()) {
                    \PlayercupmatchesQuery::create()
                        ->filterByPrimaryKeys($this->playercupmatchessRelatedByPlayerawaykeyScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->playercupmatchessRelatedByPlayerawaykeyScheduledForDeletion = null;
                }
            }

            if ($this->collPlayercupmatchessRelatedByPlayerawaykey !== null) {
                foreach ($this->collPlayercupmatchessRelatedByPlayerawaykey as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->playercupmatchessRelatedByCuproundkeyScheduledForDeletion !== null) {
                if (!$this->playercupmatchessRelatedByCuproundkeyScheduledForDeletion->isEmpty()) {
                    \PlayercupmatchesQuery::create()
                        ->filterByPrimaryKeys($this->playercupmatchessRelatedByCuproundkeyScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->playercupmatchessRelatedByCuproundkeyScheduledForDeletion = null;
                }
            }

            if ($this->collPlayercupmatchessRelatedByCuproundkey !== null) {
                foreach ($this->collPlayercupmatchessRelatedByCuproundkey as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->playerdivisionmatchessRelatedByPlayerhomekeyScheduledForDeletion !== null) {
                if (!$this->playerdivisionmatchessRelatedByPlayerhomekeyScheduledForDeletion->isEmpty()) {
                    \PlayerdivisionmatchesQuery::create()
                        ->filterByPrimaryKeys($this->playerdivisionmatchessRelatedByPlayerhomekeyScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->playerdivisionmatchessRelatedByPlayerhomekeyScheduledForDeletion = null;
                }
            }

            if ($this->collPlayerdivisionmatchessRelatedByPlayerhomekey !== null) {
                foreach ($this->collPlayerdivisionmatchessRelatedByPlayerhomekey as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->playerdivisionmatchessRelatedByPlayerawaykeyScheduledForDeletion !== null) {
                if (!$this->playerdivisionmatchessRelatedByPlayerawaykeyScheduledForDeletion->isEmpty()) {
                    \PlayerdivisionmatchesQuery::create()
                        ->filterByPrimaryKeys($this->playerdivisionmatchessRelatedByPlayerawaykeyScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->playerdivisionmatchessRelatedByPlayerawaykeyScheduledForDeletion = null;
                }
            }

            if ($this->collPlayerdivisionmatchessRelatedByPlayerawaykey !== null) {
                foreach ($this->collPlayerdivisionmatchessRelatedByPlayerawaykey as $referrerFK) {
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

            if ($this->playergrouprankingsScheduledForDeletion !== null) {
                if (!$this->playergrouprankingsScheduledForDeletion->isEmpty()) {
                    \PlayergrouprankingQuery::create()
                        ->filterByPrimaryKeys($this->playergrouprankingsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->playergrouprankingsScheduledForDeletion = null;
                }
            }

            if ($this->collPlayergrouprankings !== null) {
                foreach ($this->collPlayergrouprankings as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->playergroupresultssScheduledForDeletion !== null) {
                if (!$this->playergroupresultssScheduledForDeletion->isEmpty()) {
                    \PlayergroupresultsQuery::create()
                        ->filterByPrimaryKeys($this->playergroupresultssScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->playergroupresultssScheduledForDeletion = null;
                }
            }

            if ($this->collPlayergroupresultss !== null) {
                foreach ($this->collPlayergroupresultss as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->playergroupstatessScheduledForDeletion !== null) {
                if (!$this->playergroupstatessScheduledForDeletion->isEmpty()) {
                    \PlayergroupstatesQuery::create()
                        ->filterByPrimaryKeys($this->playergroupstatessScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->playergroupstatessScheduledForDeletion = null;
                }
            }

            if ($this->collPlayergroupstatess !== null) {
                foreach ($this->collPlayergroupstatess as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->playermatchresultssScheduledForDeletion !== null) {
                if (!$this->playermatchresultssScheduledForDeletion->isEmpty()) {
                    \PlayermatchresultsQuery::create()
                        ->filterByPrimaryKeys($this->playermatchresultssScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->playermatchresultssScheduledForDeletion = null;
                }
            }

            if ($this->collPlayermatchresultss !== null) {
                foreach ($this->collPlayermatchresultss as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->playermatchstatessScheduledForDeletion !== null) {
                if (!$this->playermatchstatessScheduledForDeletion->isEmpty()) {
                    \PlayermatchstatesQuery::create()
                        ->filterByPrimaryKeys($this->playermatchstatessScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->playermatchstatessScheduledForDeletion = null;
                }
            }

            if ($this->collPlayermatchstatess !== null) {
                foreach ($this->collPlayermatchstatess as $referrerFK) {
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

            if ($this->votessScheduledForDeletion !== null) {
                if (!$this->votessScheduledForDeletion->isEmpty()) {
                    \VotesQuery::create()
                        ->filterByPrimaryKeys($this->votessScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->votessScheduledForDeletion = null;
                }
            }

            if ($this->collVotess !== null) {
                foreach ($this->collVotess as $referrerFK) {
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
     * @param     boolean $includeForeignObjects (optional) Whether to include hydrated related objects. Default to FALSE.
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = TableMap::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array(), $includeForeignObjects = false)
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

        if ($includeForeignObjects) {
            if (null !== $this->collConnecteduserss) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'connecteduserss';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'connecteduserss';
                        break;
                    default:
                        $key = 'Connecteduserss';
                }

                $result[$key] = $this->collConnecteduserss->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collForecastss) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'forecastss';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'forecastss';
                        break;
                    default:
                        $key = 'Forecastss';
                }

                $result[$key] = $this->collForecastss->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPlayercupmatchessRelatedByPlayerhomekey) {

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

                $result[$key] = $this->collPlayercupmatchessRelatedByPlayerhomekey->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPlayercupmatchessRelatedByPlayerawaykey) {

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

                $result[$key] = $this->collPlayercupmatchessRelatedByPlayerawaykey->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPlayercupmatchessRelatedByCuproundkey) {

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

                $result[$key] = $this->collPlayercupmatchessRelatedByCuproundkey->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPlayerdivisionmatchessRelatedByPlayerhomekey) {

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

                $result[$key] = $this->collPlayerdivisionmatchessRelatedByPlayerhomekey->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPlayerdivisionmatchessRelatedByPlayerawaykey) {

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

                $result[$key] = $this->collPlayerdivisionmatchessRelatedByPlayerawaykey->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
            if (null !== $this->collPlayergrouprankings) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'playergrouprankings';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'playergrouprankings';
                        break;
                    default:
                        $key = 'Playergrouprankings';
                }

                $result[$key] = $this->collPlayergrouprankings->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPlayergroupresultss) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'playergroupresultss';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'playergroupresultss';
                        break;
                    default:
                        $key = 'Playergroupresultss';
                }

                $result[$key] = $this->collPlayergroupresultss->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPlayergroupstatess) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'playergroupstatess';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'playergroupstatess';
                        break;
                    default:
                        $key = 'Playergroupstatess';
                }

                $result[$key] = $this->collPlayergroupstatess->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPlayermatchresultss) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'playermatchresultss';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'playermatchresultss';
                        break;
                    default:
                        $key = 'Playermatchresultss';
                }

                $result[$key] = $this->collPlayermatchresultss->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPlayermatchstatess) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'playermatchstatess';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'playermatchstatess';
                        break;
                    default:
                        $key = 'Playermatchstatess';
                }

                $result[$key] = $this->collPlayermatchstatess->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
            if (null !== $this->collVotess) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'votess';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'votess';
                        break;
                    default:
                        $key = 'Votess';
                }

                $result[$key] = $this->collVotess->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getConnecteduserss() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addConnectedusers($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getForecastss() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addForecasts($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPlayercupmatchessRelatedByPlayerhomekey() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPlayercupmatchesRelatedByPlayerhomekey($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPlayercupmatchessRelatedByPlayerawaykey() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPlayercupmatchesRelatedByPlayerawaykey($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPlayercupmatchessRelatedByCuproundkey() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPlayercupmatchesRelatedByCuproundkey($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPlayerdivisionmatchessRelatedByPlayerhomekey() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPlayerdivisionmatchesRelatedByPlayerhomekey($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPlayerdivisionmatchessRelatedByPlayerawaykey() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPlayerdivisionmatchesRelatedByPlayerawaykey($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPlayerdivisionrankings() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPlayerdivisionranking($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPlayergrouprankings() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPlayergroupranking($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPlayergroupresultss() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPlayergroupresults($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPlayergroupstatess() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPlayergroupstates($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPlayermatchresultss() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPlayermatchresults($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPlayermatchstatess() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPlayermatchstates($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPlayerrankings() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPlayerranking($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getVotess() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addVotes($relObj->copy($deepCopy));
                }
            }

        } // if ($deepCopy)

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
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param      string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('Connectedusers' == $relationName) {
            return $this->initConnecteduserss();
        }
        if ('Forecasts' == $relationName) {
            return $this->initForecastss();
        }
        if ('PlayercupmatchesRelatedByPlayerhomekey' == $relationName) {
            return $this->initPlayercupmatchessRelatedByPlayerhomekey();
        }
        if ('PlayercupmatchesRelatedByPlayerawaykey' == $relationName) {
            return $this->initPlayercupmatchessRelatedByPlayerawaykey();
        }
        if ('PlayercupmatchesRelatedByCuproundkey' == $relationName) {
            return $this->initPlayercupmatchessRelatedByCuproundkey();
        }
        if ('PlayerdivisionmatchesRelatedByPlayerhomekey' == $relationName) {
            return $this->initPlayerdivisionmatchessRelatedByPlayerhomekey();
        }
        if ('PlayerdivisionmatchesRelatedByPlayerawaykey' == $relationName) {
            return $this->initPlayerdivisionmatchessRelatedByPlayerawaykey();
        }
        if ('Playerdivisionranking' == $relationName) {
            return $this->initPlayerdivisionrankings();
        }
        if ('Playergroupranking' == $relationName) {
            return $this->initPlayergrouprankings();
        }
        if ('Playergroupresults' == $relationName) {
            return $this->initPlayergroupresultss();
        }
        if ('Playergroupstates' == $relationName) {
            return $this->initPlayergroupstatess();
        }
        if ('Playermatchresults' == $relationName) {
            return $this->initPlayermatchresultss();
        }
        if ('Playermatchstates' == $relationName) {
            return $this->initPlayermatchstatess();
        }
        if ('Playerranking' == $relationName) {
            return $this->initPlayerrankings();
        }
        if ('Votes' == $relationName) {
            return $this->initVotess();
        }
    }

    /**
     * Clears out the collConnecteduserss collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addConnecteduserss()
     */
    public function clearConnecteduserss()
    {
        $this->collConnecteduserss = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collConnecteduserss collection loaded partially.
     */
    public function resetPartialConnecteduserss($v = true)
    {
        $this->collConnecteduserssPartial = $v;
    }

    /**
     * Initializes the collConnecteduserss collection.
     *
     * By default this just sets the collConnecteduserss collection to an empty array (like clearcollConnecteduserss());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initConnecteduserss($overrideExisting = true)
    {
        if (null !== $this->collConnecteduserss && !$overrideExisting) {
            return;
        }
        $this->collConnecteduserss = new ObjectCollection();
        $this->collConnecteduserss->setModel('\Connectedusers');
    }

    /**
     * Gets an array of ChildConnectedusers objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildPlayers is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildConnectedusers[] List of ChildConnectedusers objects
     * @throws PropelException
     */
    public function getConnecteduserss(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collConnecteduserssPartial && !$this->isNew();
        if (null === $this->collConnecteduserss || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collConnecteduserss) {
                // return empty collection
                $this->initConnecteduserss();
            } else {
                $collConnecteduserss = ChildConnectedusersQuery::create(null, $criteria)
                    ->filterByPlayers($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collConnecteduserssPartial && count($collConnecteduserss)) {
                        $this->initConnecteduserss(false);

                        foreach ($collConnecteduserss as $obj) {
                            if (false == $this->collConnecteduserss->contains($obj)) {
                                $this->collConnecteduserss->append($obj);
                            }
                        }

                        $this->collConnecteduserssPartial = true;
                    }

                    return $collConnecteduserss;
                }

                if ($partial && $this->collConnecteduserss) {
                    foreach ($this->collConnecteduserss as $obj) {
                        if ($obj->isNew()) {
                            $collConnecteduserss[] = $obj;
                        }
                    }
                }

                $this->collConnecteduserss = $collConnecteduserss;
                $this->collConnecteduserssPartial = false;
            }
        }

        return $this->collConnecteduserss;
    }

    /**
     * Sets a collection of ChildConnectedusers objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $connecteduserss A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildPlayers The current object (for fluent API support)
     */
    public function setConnecteduserss(Collection $connecteduserss, ConnectionInterface $con = null)
    {
        /** @var ChildConnectedusers[] $connecteduserssToDelete */
        $connecteduserssToDelete = $this->getConnecteduserss(new Criteria(), $con)->diff($connecteduserss);


        $this->connecteduserssScheduledForDeletion = $connecteduserssToDelete;

        foreach ($connecteduserssToDelete as $connectedusersRemoved) {
            $connectedusersRemoved->setPlayers(null);
        }

        $this->collConnecteduserss = null;
        foreach ($connecteduserss as $connectedusers) {
            $this->addConnectedusers($connectedusers);
        }

        $this->collConnecteduserss = $connecteduserss;
        $this->collConnecteduserssPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Connectedusers objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Connectedusers objects.
     * @throws PropelException
     */
    public function countConnecteduserss(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collConnecteduserssPartial && !$this->isNew();
        if (null === $this->collConnecteduserss || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collConnecteduserss) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getConnecteduserss());
            }

            $query = ChildConnectedusersQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPlayers($this)
                ->count($con);
        }

        return count($this->collConnecteduserss);
    }

    /**
     * Method called to associate a ChildConnectedusers object to this object
     * through the ChildConnectedusers foreign key attribute.
     *
     * @param  ChildConnectedusers $l ChildConnectedusers
     * @return $this|\Players The current object (for fluent API support)
     */
    public function addConnectedusers(ChildConnectedusers $l)
    {
        if ($this->collConnecteduserss === null) {
            $this->initConnecteduserss();
            $this->collConnecteduserssPartial = true;
        }

        if (!$this->collConnecteduserss->contains($l)) {
            $this->doAddConnectedusers($l);
        }

        return $this;
    }

    /**
     * @param ChildConnectedusers $connectedusers The ChildConnectedusers object to add.
     */
    protected function doAddConnectedusers(ChildConnectedusers $connectedusers)
    {
        $this->collConnecteduserss[]= $connectedusers;
        $connectedusers->setPlayers($this);
    }

    /**
     * @param  ChildConnectedusers $connectedusers The ChildConnectedusers object to remove.
     * @return $this|ChildPlayers The current object (for fluent API support)
     */
    public function removeConnectedusers(ChildConnectedusers $connectedusers)
    {
        if ($this->getConnecteduserss()->contains($connectedusers)) {
            $pos = $this->collConnecteduserss->search($connectedusers);
            $this->collConnecteduserss->remove($pos);
            if (null === $this->connecteduserssScheduledForDeletion) {
                $this->connecteduserssScheduledForDeletion = clone $this->collConnecteduserss;
                $this->connecteduserssScheduledForDeletion->clear();
            }
            $this->connecteduserssScheduledForDeletion[]= clone $connectedusers;
            $connectedusers->setPlayers(null);
        }

        return $this;
    }

    /**
     * Clears out the collForecastss collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addForecastss()
     */
    public function clearForecastss()
    {
        $this->collForecastss = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collForecastss collection loaded partially.
     */
    public function resetPartialForecastss($v = true)
    {
        $this->collForecastssPartial = $v;
    }

    /**
     * Initializes the collForecastss collection.
     *
     * By default this just sets the collForecastss collection to an empty array (like clearcollForecastss());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initForecastss($overrideExisting = true)
    {
        if (null !== $this->collForecastss && !$overrideExisting) {
            return;
        }
        $this->collForecastss = new ObjectCollection();
        $this->collForecastss->setModel('\Forecasts');
    }

    /**
     * Gets an array of ChildForecasts objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildPlayers is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildForecasts[] List of ChildForecasts objects
     * @throws PropelException
     */
    public function getForecastss(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collForecastssPartial && !$this->isNew();
        if (null === $this->collForecastss || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collForecastss) {
                // return empty collection
                $this->initForecastss();
            } else {
                $collForecastss = ChildForecastsQuery::create(null, $criteria)
                    ->filterByForecastPlayer($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collForecastssPartial && count($collForecastss)) {
                        $this->initForecastss(false);

                        foreach ($collForecastss as $obj) {
                            if (false == $this->collForecastss->contains($obj)) {
                                $this->collForecastss->append($obj);
                            }
                        }

                        $this->collForecastssPartial = true;
                    }

                    return $collForecastss;
                }

                if ($partial && $this->collForecastss) {
                    foreach ($this->collForecastss as $obj) {
                        if ($obj->isNew()) {
                            $collForecastss[] = $obj;
                        }
                    }
                }

                $this->collForecastss = $collForecastss;
                $this->collForecastssPartial = false;
            }
        }

        return $this->collForecastss;
    }

    /**
     * Sets a collection of ChildForecasts objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $forecastss A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildPlayers The current object (for fluent API support)
     */
    public function setForecastss(Collection $forecastss, ConnectionInterface $con = null)
    {
        /** @var ChildForecasts[] $forecastssToDelete */
        $forecastssToDelete = $this->getForecastss(new Criteria(), $con)->diff($forecastss);


        $this->forecastssScheduledForDeletion = $forecastssToDelete;

        foreach ($forecastssToDelete as $forecastsRemoved) {
            $forecastsRemoved->setForecastPlayer(null);
        }

        $this->collForecastss = null;
        foreach ($forecastss as $forecasts) {
            $this->addForecasts($forecasts);
        }

        $this->collForecastss = $forecastss;
        $this->collForecastssPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Forecasts objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Forecasts objects.
     * @throws PropelException
     */
    public function countForecastss(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collForecastssPartial && !$this->isNew();
        if (null === $this->collForecastss || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collForecastss) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getForecastss());
            }

            $query = ChildForecastsQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByForecastPlayer($this)
                ->count($con);
        }

        return count($this->collForecastss);
    }

    /**
     * Method called to associate a ChildForecasts object to this object
     * through the ChildForecasts foreign key attribute.
     *
     * @param  ChildForecasts $l ChildForecasts
     * @return $this|\Players The current object (for fluent API support)
     */
    public function addForecasts(ChildForecasts $l)
    {
        if ($this->collForecastss === null) {
            $this->initForecastss();
            $this->collForecastssPartial = true;
        }

        if (!$this->collForecastss->contains($l)) {
            $this->doAddForecasts($l);
        }

        return $this;
    }

    /**
     * @param ChildForecasts $forecasts The ChildForecasts object to add.
     */
    protected function doAddForecasts(ChildForecasts $forecasts)
    {
        $this->collForecastss[]= $forecasts;
        $forecasts->setForecastPlayer($this);
    }

    /**
     * @param  ChildForecasts $forecasts The ChildForecasts object to remove.
     * @return $this|ChildPlayers The current object (for fluent API support)
     */
    public function removeForecasts(ChildForecasts $forecasts)
    {
        if ($this->getForecastss()->contains($forecasts)) {
            $pos = $this->collForecastss->search($forecasts);
            $this->collForecastss->remove($pos);
            if (null === $this->forecastssScheduledForDeletion) {
                $this->forecastssScheduledForDeletion = clone $this->collForecastss;
                $this->forecastssScheduledForDeletion->clear();
            }
            $this->forecastssScheduledForDeletion[]= clone $forecasts;
            $forecasts->setForecastPlayer(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Players is new, it will return
     * an empty collection; or if this Players has previously
     * been saved, it will retrieve related Forecastss from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Players.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildForecasts[] List of ChildForecasts objects
     */
    public function getForecastssJoinMatches(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildForecastsQuery::create(null, $criteria);
        $query->joinWith('Matches', $joinBehavior);

        return $this->getForecastss($query, $con);
    }

    /**
     * Clears out the collPlayercupmatchessRelatedByPlayerhomekey collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addPlayercupmatchessRelatedByPlayerhomekey()
     */
    public function clearPlayercupmatchessRelatedByPlayerhomekey()
    {
        $this->collPlayercupmatchessRelatedByPlayerhomekey = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collPlayercupmatchessRelatedByPlayerhomekey collection loaded partially.
     */
    public function resetPartialPlayercupmatchessRelatedByPlayerhomekey($v = true)
    {
        $this->collPlayercupmatchessRelatedByPlayerhomekeyPartial = $v;
    }

    /**
     * Initializes the collPlayercupmatchessRelatedByPlayerhomekey collection.
     *
     * By default this just sets the collPlayercupmatchessRelatedByPlayerhomekey collection to an empty array (like clearcollPlayercupmatchessRelatedByPlayerhomekey());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPlayercupmatchessRelatedByPlayerhomekey($overrideExisting = true)
    {
        if (null !== $this->collPlayercupmatchessRelatedByPlayerhomekey && !$overrideExisting) {
            return;
        }
        $this->collPlayercupmatchessRelatedByPlayerhomekey = new ObjectCollection();
        $this->collPlayercupmatchessRelatedByPlayerhomekey->setModel('\Playercupmatches');
    }

    /**
     * Gets an array of ChildPlayercupmatches objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildPlayers is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildPlayercupmatches[] List of ChildPlayercupmatches objects
     * @throws PropelException
     */
    public function getPlayercupmatchessRelatedByPlayerhomekey(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collPlayercupmatchessRelatedByPlayerhomekeyPartial && !$this->isNew();
        if (null === $this->collPlayercupmatchessRelatedByPlayerhomekey || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPlayercupmatchessRelatedByPlayerhomekey) {
                // return empty collection
                $this->initPlayercupmatchessRelatedByPlayerhomekey();
            } else {
                $collPlayercupmatchessRelatedByPlayerhomekey = ChildPlayercupmatchesQuery::create(null, $criteria)
                    ->filterByDivisionMatchesPlayerHome($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collPlayercupmatchessRelatedByPlayerhomekeyPartial && count($collPlayercupmatchessRelatedByPlayerhomekey)) {
                        $this->initPlayercupmatchessRelatedByPlayerhomekey(false);

                        foreach ($collPlayercupmatchessRelatedByPlayerhomekey as $obj) {
                            if (false == $this->collPlayercupmatchessRelatedByPlayerhomekey->contains($obj)) {
                                $this->collPlayercupmatchessRelatedByPlayerhomekey->append($obj);
                            }
                        }

                        $this->collPlayercupmatchessRelatedByPlayerhomekeyPartial = true;
                    }

                    return $collPlayercupmatchessRelatedByPlayerhomekey;
                }

                if ($partial && $this->collPlayercupmatchessRelatedByPlayerhomekey) {
                    foreach ($this->collPlayercupmatchessRelatedByPlayerhomekey as $obj) {
                        if ($obj->isNew()) {
                            $collPlayercupmatchessRelatedByPlayerhomekey[] = $obj;
                        }
                    }
                }

                $this->collPlayercupmatchessRelatedByPlayerhomekey = $collPlayercupmatchessRelatedByPlayerhomekey;
                $this->collPlayercupmatchessRelatedByPlayerhomekeyPartial = false;
            }
        }

        return $this->collPlayercupmatchessRelatedByPlayerhomekey;
    }

    /**
     * Sets a collection of ChildPlayercupmatches objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $playercupmatchessRelatedByPlayerhomekey A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildPlayers The current object (for fluent API support)
     */
    public function setPlayercupmatchessRelatedByPlayerhomekey(Collection $playercupmatchessRelatedByPlayerhomekey, ConnectionInterface $con = null)
    {
        /** @var ChildPlayercupmatches[] $playercupmatchessRelatedByPlayerhomekeyToDelete */
        $playercupmatchessRelatedByPlayerhomekeyToDelete = $this->getPlayercupmatchessRelatedByPlayerhomekey(new Criteria(), $con)->diff($playercupmatchessRelatedByPlayerhomekey);


        $this->playercupmatchessRelatedByPlayerhomekeyScheduledForDeletion = $playercupmatchessRelatedByPlayerhomekeyToDelete;

        foreach ($playercupmatchessRelatedByPlayerhomekeyToDelete as $playercupmatchesRelatedByPlayerhomekeyRemoved) {
            $playercupmatchesRelatedByPlayerhomekeyRemoved->setDivisionMatchesPlayerHome(null);
        }

        $this->collPlayercupmatchessRelatedByPlayerhomekey = null;
        foreach ($playercupmatchessRelatedByPlayerhomekey as $playercupmatchesRelatedByPlayerhomekey) {
            $this->addPlayercupmatchesRelatedByPlayerhomekey($playercupmatchesRelatedByPlayerhomekey);
        }

        $this->collPlayercupmatchessRelatedByPlayerhomekey = $playercupmatchessRelatedByPlayerhomekey;
        $this->collPlayercupmatchessRelatedByPlayerhomekeyPartial = false;

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
    public function countPlayercupmatchessRelatedByPlayerhomekey(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collPlayercupmatchessRelatedByPlayerhomekeyPartial && !$this->isNew();
        if (null === $this->collPlayercupmatchessRelatedByPlayerhomekey || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPlayercupmatchessRelatedByPlayerhomekey) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPlayercupmatchessRelatedByPlayerhomekey());
            }

            $query = ChildPlayercupmatchesQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByDivisionMatchesPlayerHome($this)
                ->count($con);
        }

        return count($this->collPlayercupmatchessRelatedByPlayerhomekey);
    }

    /**
     * Method called to associate a ChildPlayercupmatches object to this object
     * through the ChildPlayercupmatches foreign key attribute.
     *
     * @param  ChildPlayercupmatches $l ChildPlayercupmatches
     * @return $this|\Players The current object (for fluent API support)
     */
    public function addPlayercupmatchesRelatedByPlayerhomekey(ChildPlayercupmatches $l)
    {
        if ($this->collPlayercupmatchessRelatedByPlayerhomekey === null) {
            $this->initPlayercupmatchessRelatedByPlayerhomekey();
            $this->collPlayercupmatchessRelatedByPlayerhomekeyPartial = true;
        }

        if (!$this->collPlayercupmatchessRelatedByPlayerhomekey->contains($l)) {
            $this->doAddPlayercupmatchesRelatedByPlayerhomekey($l);
        }

        return $this;
    }

    /**
     * @param ChildPlayercupmatches $playercupmatchesRelatedByPlayerhomekey The ChildPlayercupmatches object to add.
     */
    protected function doAddPlayercupmatchesRelatedByPlayerhomekey(ChildPlayercupmatches $playercupmatchesRelatedByPlayerhomekey)
    {
        $this->collPlayercupmatchessRelatedByPlayerhomekey[]= $playercupmatchesRelatedByPlayerhomekey;
        $playercupmatchesRelatedByPlayerhomekey->setDivisionMatchesPlayerHome($this);
    }

    /**
     * @param  ChildPlayercupmatches $playercupmatchesRelatedByPlayerhomekey The ChildPlayercupmatches object to remove.
     * @return $this|ChildPlayers The current object (for fluent API support)
     */
    public function removePlayercupmatchesRelatedByPlayerhomekey(ChildPlayercupmatches $playercupmatchesRelatedByPlayerhomekey)
    {
        if ($this->getPlayercupmatchessRelatedByPlayerhomekey()->contains($playercupmatchesRelatedByPlayerhomekey)) {
            $pos = $this->collPlayercupmatchessRelatedByPlayerhomekey->search($playercupmatchesRelatedByPlayerhomekey);
            $this->collPlayercupmatchessRelatedByPlayerhomekey->remove($pos);
            if (null === $this->playercupmatchessRelatedByPlayerhomekeyScheduledForDeletion) {
                $this->playercupmatchessRelatedByPlayerhomekeyScheduledForDeletion = clone $this->collPlayercupmatchessRelatedByPlayerhomekey;
                $this->playercupmatchessRelatedByPlayerhomekeyScheduledForDeletion->clear();
            }
            $this->playercupmatchessRelatedByPlayerhomekeyScheduledForDeletion[]= clone $playercupmatchesRelatedByPlayerhomekey;
            $playercupmatchesRelatedByPlayerhomekey->setDivisionMatchesPlayerHome(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Players is new, it will return
     * an empty collection; or if this Players has previously
     * been saved, it will retrieve related PlayercupmatchessRelatedByPlayerhomekey from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Players.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildPlayercupmatches[] List of ChildPlayercupmatches objects
     */
    public function getPlayercupmatchessRelatedByPlayerhomekeyJoinDivisionMatchesGroup(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildPlayercupmatchesQuery::create(null, $criteria);
        $query->joinWith('DivisionMatchesGroup', $joinBehavior);

        return $this->getPlayercupmatchessRelatedByPlayerhomekey($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Players is new, it will return
     * an empty collection; or if this Players has previously
     * been saved, it will retrieve related PlayercupmatchessRelatedByPlayerhomekey from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Players.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildPlayercupmatches[] List of ChildPlayercupmatches objects
     */
    public function getPlayercupmatchessRelatedByPlayerhomekeyJoinDivisionMatchesSeason(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildPlayercupmatchesQuery::create(null, $criteria);
        $query->joinWith('DivisionMatchesSeason', $joinBehavior);

        return $this->getPlayercupmatchessRelatedByPlayerhomekey($query, $con);
    }

    /**
     * Clears out the collPlayercupmatchessRelatedByPlayerawaykey collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addPlayercupmatchessRelatedByPlayerawaykey()
     */
    public function clearPlayercupmatchessRelatedByPlayerawaykey()
    {
        $this->collPlayercupmatchessRelatedByPlayerawaykey = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collPlayercupmatchessRelatedByPlayerawaykey collection loaded partially.
     */
    public function resetPartialPlayercupmatchessRelatedByPlayerawaykey($v = true)
    {
        $this->collPlayercupmatchessRelatedByPlayerawaykeyPartial = $v;
    }

    /**
     * Initializes the collPlayercupmatchessRelatedByPlayerawaykey collection.
     *
     * By default this just sets the collPlayercupmatchessRelatedByPlayerawaykey collection to an empty array (like clearcollPlayercupmatchessRelatedByPlayerawaykey());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPlayercupmatchessRelatedByPlayerawaykey($overrideExisting = true)
    {
        if (null !== $this->collPlayercupmatchessRelatedByPlayerawaykey && !$overrideExisting) {
            return;
        }
        $this->collPlayercupmatchessRelatedByPlayerawaykey = new ObjectCollection();
        $this->collPlayercupmatchessRelatedByPlayerawaykey->setModel('\Playercupmatches');
    }

    /**
     * Gets an array of ChildPlayercupmatches objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildPlayers is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildPlayercupmatches[] List of ChildPlayercupmatches objects
     * @throws PropelException
     */
    public function getPlayercupmatchessRelatedByPlayerawaykey(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collPlayercupmatchessRelatedByPlayerawaykeyPartial && !$this->isNew();
        if (null === $this->collPlayercupmatchessRelatedByPlayerawaykey || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPlayercupmatchessRelatedByPlayerawaykey) {
                // return empty collection
                $this->initPlayercupmatchessRelatedByPlayerawaykey();
            } else {
                $collPlayercupmatchessRelatedByPlayerawaykey = ChildPlayercupmatchesQuery::create(null, $criteria)
                    ->filterByDivisionMatchesPlayerAway($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collPlayercupmatchessRelatedByPlayerawaykeyPartial && count($collPlayercupmatchessRelatedByPlayerawaykey)) {
                        $this->initPlayercupmatchessRelatedByPlayerawaykey(false);

                        foreach ($collPlayercupmatchessRelatedByPlayerawaykey as $obj) {
                            if (false == $this->collPlayercupmatchessRelatedByPlayerawaykey->contains($obj)) {
                                $this->collPlayercupmatchessRelatedByPlayerawaykey->append($obj);
                            }
                        }

                        $this->collPlayercupmatchessRelatedByPlayerawaykeyPartial = true;
                    }

                    return $collPlayercupmatchessRelatedByPlayerawaykey;
                }

                if ($partial && $this->collPlayercupmatchessRelatedByPlayerawaykey) {
                    foreach ($this->collPlayercupmatchessRelatedByPlayerawaykey as $obj) {
                        if ($obj->isNew()) {
                            $collPlayercupmatchessRelatedByPlayerawaykey[] = $obj;
                        }
                    }
                }

                $this->collPlayercupmatchessRelatedByPlayerawaykey = $collPlayercupmatchessRelatedByPlayerawaykey;
                $this->collPlayercupmatchessRelatedByPlayerawaykeyPartial = false;
            }
        }

        return $this->collPlayercupmatchessRelatedByPlayerawaykey;
    }

    /**
     * Sets a collection of ChildPlayercupmatches objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $playercupmatchessRelatedByPlayerawaykey A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildPlayers The current object (for fluent API support)
     */
    public function setPlayercupmatchessRelatedByPlayerawaykey(Collection $playercupmatchessRelatedByPlayerawaykey, ConnectionInterface $con = null)
    {
        /** @var ChildPlayercupmatches[] $playercupmatchessRelatedByPlayerawaykeyToDelete */
        $playercupmatchessRelatedByPlayerawaykeyToDelete = $this->getPlayercupmatchessRelatedByPlayerawaykey(new Criteria(), $con)->diff($playercupmatchessRelatedByPlayerawaykey);


        $this->playercupmatchessRelatedByPlayerawaykeyScheduledForDeletion = $playercupmatchessRelatedByPlayerawaykeyToDelete;

        foreach ($playercupmatchessRelatedByPlayerawaykeyToDelete as $playercupmatchesRelatedByPlayerawaykeyRemoved) {
            $playercupmatchesRelatedByPlayerawaykeyRemoved->setDivisionMatchesPlayerAway(null);
        }

        $this->collPlayercupmatchessRelatedByPlayerawaykey = null;
        foreach ($playercupmatchessRelatedByPlayerawaykey as $playercupmatchesRelatedByPlayerawaykey) {
            $this->addPlayercupmatchesRelatedByPlayerawaykey($playercupmatchesRelatedByPlayerawaykey);
        }

        $this->collPlayercupmatchessRelatedByPlayerawaykey = $playercupmatchessRelatedByPlayerawaykey;
        $this->collPlayercupmatchessRelatedByPlayerawaykeyPartial = false;

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
    public function countPlayercupmatchessRelatedByPlayerawaykey(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collPlayercupmatchessRelatedByPlayerawaykeyPartial && !$this->isNew();
        if (null === $this->collPlayercupmatchessRelatedByPlayerawaykey || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPlayercupmatchessRelatedByPlayerawaykey) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPlayercupmatchessRelatedByPlayerawaykey());
            }

            $query = ChildPlayercupmatchesQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByDivisionMatchesPlayerAway($this)
                ->count($con);
        }

        return count($this->collPlayercupmatchessRelatedByPlayerawaykey);
    }

    /**
     * Method called to associate a ChildPlayercupmatches object to this object
     * through the ChildPlayercupmatches foreign key attribute.
     *
     * @param  ChildPlayercupmatches $l ChildPlayercupmatches
     * @return $this|\Players The current object (for fluent API support)
     */
    public function addPlayercupmatchesRelatedByPlayerawaykey(ChildPlayercupmatches $l)
    {
        if ($this->collPlayercupmatchessRelatedByPlayerawaykey === null) {
            $this->initPlayercupmatchessRelatedByPlayerawaykey();
            $this->collPlayercupmatchessRelatedByPlayerawaykeyPartial = true;
        }

        if (!$this->collPlayercupmatchessRelatedByPlayerawaykey->contains($l)) {
            $this->doAddPlayercupmatchesRelatedByPlayerawaykey($l);
        }

        return $this;
    }

    /**
     * @param ChildPlayercupmatches $playercupmatchesRelatedByPlayerawaykey The ChildPlayercupmatches object to add.
     */
    protected function doAddPlayercupmatchesRelatedByPlayerawaykey(ChildPlayercupmatches $playercupmatchesRelatedByPlayerawaykey)
    {
        $this->collPlayercupmatchessRelatedByPlayerawaykey[]= $playercupmatchesRelatedByPlayerawaykey;
        $playercupmatchesRelatedByPlayerawaykey->setDivisionMatchesPlayerAway($this);
    }

    /**
     * @param  ChildPlayercupmatches $playercupmatchesRelatedByPlayerawaykey The ChildPlayercupmatches object to remove.
     * @return $this|ChildPlayers The current object (for fluent API support)
     */
    public function removePlayercupmatchesRelatedByPlayerawaykey(ChildPlayercupmatches $playercupmatchesRelatedByPlayerawaykey)
    {
        if ($this->getPlayercupmatchessRelatedByPlayerawaykey()->contains($playercupmatchesRelatedByPlayerawaykey)) {
            $pos = $this->collPlayercupmatchessRelatedByPlayerawaykey->search($playercupmatchesRelatedByPlayerawaykey);
            $this->collPlayercupmatchessRelatedByPlayerawaykey->remove($pos);
            if (null === $this->playercupmatchessRelatedByPlayerawaykeyScheduledForDeletion) {
                $this->playercupmatchessRelatedByPlayerawaykeyScheduledForDeletion = clone $this->collPlayercupmatchessRelatedByPlayerawaykey;
                $this->playercupmatchessRelatedByPlayerawaykeyScheduledForDeletion->clear();
            }
            $this->playercupmatchessRelatedByPlayerawaykeyScheduledForDeletion[]= clone $playercupmatchesRelatedByPlayerawaykey;
            $playercupmatchesRelatedByPlayerawaykey->setDivisionMatchesPlayerAway(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Players is new, it will return
     * an empty collection; or if this Players has previously
     * been saved, it will retrieve related PlayercupmatchessRelatedByPlayerawaykey from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Players.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildPlayercupmatches[] List of ChildPlayercupmatches objects
     */
    public function getPlayercupmatchessRelatedByPlayerawaykeyJoinDivisionMatchesGroup(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildPlayercupmatchesQuery::create(null, $criteria);
        $query->joinWith('DivisionMatchesGroup', $joinBehavior);

        return $this->getPlayercupmatchessRelatedByPlayerawaykey($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Players is new, it will return
     * an empty collection; or if this Players has previously
     * been saved, it will retrieve related PlayercupmatchessRelatedByPlayerawaykey from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Players.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildPlayercupmatches[] List of ChildPlayercupmatches objects
     */
    public function getPlayercupmatchessRelatedByPlayerawaykeyJoinDivisionMatchesSeason(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildPlayercupmatchesQuery::create(null, $criteria);
        $query->joinWith('DivisionMatchesSeason', $joinBehavior);

        return $this->getPlayercupmatchessRelatedByPlayerawaykey($query, $con);
    }

    /**
     * Clears out the collPlayercupmatchessRelatedByCuproundkey collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addPlayercupmatchessRelatedByCuproundkey()
     */
    public function clearPlayercupmatchessRelatedByCuproundkey()
    {
        $this->collPlayercupmatchessRelatedByCuproundkey = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collPlayercupmatchessRelatedByCuproundkey collection loaded partially.
     */
    public function resetPartialPlayercupmatchessRelatedByCuproundkey($v = true)
    {
        $this->collPlayercupmatchessRelatedByCuproundkeyPartial = $v;
    }

    /**
     * Initializes the collPlayercupmatchessRelatedByCuproundkey collection.
     *
     * By default this just sets the collPlayercupmatchessRelatedByCuproundkey collection to an empty array (like clearcollPlayercupmatchessRelatedByCuproundkey());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPlayercupmatchessRelatedByCuproundkey($overrideExisting = true)
    {
        if (null !== $this->collPlayercupmatchessRelatedByCuproundkey && !$overrideExisting) {
            return;
        }
        $this->collPlayercupmatchessRelatedByCuproundkey = new ObjectCollection();
        $this->collPlayercupmatchessRelatedByCuproundkey->setModel('\Playercupmatches');
    }

    /**
     * Gets an array of ChildPlayercupmatches objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildPlayers is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildPlayercupmatches[] List of ChildPlayercupmatches objects
     * @throws PropelException
     */
    public function getPlayercupmatchessRelatedByCuproundkey(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collPlayercupmatchessRelatedByCuproundkeyPartial && !$this->isNew();
        if (null === $this->collPlayercupmatchessRelatedByCuproundkey || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPlayercupmatchessRelatedByCuproundkey) {
                // return empty collection
                $this->initPlayercupmatchessRelatedByCuproundkey();
            } else {
                $collPlayercupmatchessRelatedByCuproundkey = ChildPlayercupmatchesQuery::create(null, $criteria)
                    ->filterByDivisionMatchesCupRound($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collPlayercupmatchessRelatedByCuproundkeyPartial && count($collPlayercupmatchessRelatedByCuproundkey)) {
                        $this->initPlayercupmatchessRelatedByCuproundkey(false);

                        foreach ($collPlayercupmatchessRelatedByCuproundkey as $obj) {
                            if (false == $this->collPlayercupmatchessRelatedByCuproundkey->contains($obj)) {
                                $this->collPlayercupmatchessRelatedByCuproundkey->append($obj);
                            }
                        }

                        $this->collPlayercupmatchessRelatedByCuproundkeyPartial = true;
                    }

                    return $collPlayercupmatchessRelatedByCuproundkey;
                }

                if ($partial && $this->collPlayercupmatchessRelatedByCuproundkey) {
                    foreach ($this->collPlayercupmatchessRelatedByCuproundkey as $obj) {
                        if ($obj->isNew()) {
                            $collPlayercupmatchessRelatedByCuproundkey[] = $obj;
                        }
                    }
                }

                $this->collPlayercupmatchessRelatedByCuproundkey = $collPlayercupmatchessRelatedByCuproundkey;
                $this->collPlayercupmatchessRelatedByCuproundkeyPartial = false;
            }
        }

        return $this->collPlayercupmatchessRelatedByCuproundkey;
    }

    /**
     * Sets a collection of ChildPlayercupmatches objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $playercupmatchessRelatedByCuproundkey A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildPlayers The current object (for fluent API support)
     */
    public function setPlayercupmatchessRelatedByCuproundkey(Collection $playercupmatchessRelatedByCuproundkey, ConnectionInterface $con = null)
    {
        /** @var ChildPlayercupmatches[] $playercupmatchessRelatedByCuproundkeyToDelete */
        $playercupmatchessRelatedByCuproundkeyToDelete = $this->getPlayercupmatchessRelatedByCuproundkey(new Criteria(), $con)->diff($playercupmatchessRelatedByCuproundkey);


        $this->playercupmatchessRelatedByCuproundkeyScheduledForDeletion = $playercupmatchessRelatedByCuproundkeyToDelete;

        foreach ($playercupmatchessRelatedByCuproundkeyToDelete as $playercupmatchesRelatedByCuproundkeyRemoved) {
            $playercupmatchesRelatedByCuproundkeyRemoved->setDivisionMatchesCupRound(null);
        }

        $this->collPlayercupmatchessRelatedByCuproundkey = null;
        foreach ($playercupmatchessRelatedByCuproundkey as $playercupmatchesRelatedByCuproundkey) {
            $this->addPlayercupmatchesRelatedByCuproundkey($playercupmatchesRelatedByCuproundkey);
        }

        $this->collPlayercupmatchessRelatedByCuproundkey = $playercupmatchessRelatedByCuproundkey;
        $this->collPlayercupmatchessRelatedByCuproundkeyPartial = false;

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
    public function countPlayercupmatchessRelatedByCuproundkey(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collPlayercupmatchessRelatedByCuproundkeyPartial && !$this->isNew();
        if (null === $this->collPlayercupmatchessRelatedByCuproundkey || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPlayercupmatchessRelatedByCuproundkey) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPlayercupmatchessRelatedByCuproundkey());
            }

            $query = ChildPlayercupmatchesQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByDivisionMatchesCupRound($this)
                ->count($con);
        }

        return count($this->collPlayercupmatchessRelatedByCuproundkey);
    }

    /**
     * Method called to associate a ChildPlayercupmatches object to this object
     * through the ChildPlayercupmatches foreign key attribute.
     *
     * @param  ChildPlayercupmatches $l ChildPlayercupmatches
     * @return $this|\Players The current object (for fluent API support)
     */
    public function addPlayercupmatchesRelatedByCuproundkey(ChildPlayercupmatches $l)
    {
        if ($this->collPlayercupmatchessRelatedByCuproundkey === null) {
            $this->initPlayercupmatchessRelatedByCuproundkey();
            $this->collPlayercupmatchessRelatedByCuproundkeyPartial = true;
        }

        if (!$this->collPlayercupmatchessRelatedByCuproundkey->contains($l)) {
            $this->doAddPlayercupmatchesRelatedByCuproundkey($l);
        }

        return $this;
    }

    /**
     * @param ChildPlayercupmatches $playercupmatchesRelatedByCuproundkey The ChildPlayercupmatches object to add.
     */
    protected function doAddPlayercupmatchesRelatedByCuproundkey(ChildPlayercupmatches $playercupmatchesRelatedByCuproundkey)
    {
        $this->collPlayercupmatchessRelatedByCuproundkey[]= $playercupmatchesRelatedByCuproundkey;
        $playercupmatchesRelatedByCuproundkey->setDivisionMatchesCupRound($this);
    }

    /**
     * @param  ChildPlayercupmatches $playercupmatchesRelatedByCuproundkey The ChildPlayercupmatches object to remove.
     * @return $this|ChildPlayers The current object (for fluent API support)
     */
    public function removePlayercupmatchesRelatedByCuproundkey(ChildPlayercupmatches $playercupmatchesRelatedByCuproundkey)
    {
        if ($this->getPlayercupmatchessRelatedByCuproundkey()->contains($playercupmatchesRelatedByCuproundkey)) {
            $pos = $this->collPlayercupmatchessRelatedByCuproundkey->search($playercupmatchesRelatedByCuproundkey);
            $this->collPlayercupmatchessRelatedByCuproundkey->remove($pos);
            if (null === $this->playercupmatchessRelatedByCuproundkeyScheduledForDeletion) {
                $this->playercupmatchessRelatedByCuproundkeyScheduledForDeletion = clone $this->collPlayercupmatchessRelatedByCuproundkey;
                $this->playercupmatchessRelatedByCuproundkeyScheduledForDeletion->clear();
            }
            $this->playercupmatchessRelatedByCuproundkeyScheduledForDeletion[]= clone $playercupmatchesRelatedByCuproundkey;
            $playercupmatchesRelatedByCuproundkey->setDivisionMatchesCupRound(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Players is new, it will return
     * an empty collection; or if this Players has previously
     * been saved, it will retrieve related PlayercupmatchessRelatedByCuproundkey from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Players.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildPlayercupmatches[] List of ChildPlayercupmatches objects
     */
    public function getPlayercupmatchessRelatedByCuproundkeyJoinDivisionMatchesGroup(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildPlayercupmatchesQuery::create(null, $criteria);
        $query->joinWith('DivisionMatchesGroup', $joinBehavior);

        return $this->getPlayercupmatchessRelatedByCuproundkey($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Players is new, it will return
     * an empty collection; or if this Players has previously
     * been saved, it will retrieve related PlayercupmatchessRelatedByCuproundkey from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Players.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildPlayercupmatches[] List of ChildPlayercupmatches objects
     */
    public function getPlayercupmatchessRelatedByCuproundkeyJoinDivisionMatchesSeason(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildPlayercupmatchesQuery::create(null, $criteria);
        $query->joinWith('DivisionMatchesSeason', $joinBehavior);

        return $this->getPlayercupmatchessRelatedByCuproundkey($query, $con);
    }

    /**
     * Clears out the collPlayerdivisionmatchessRelatedByPlayerhomekey collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addPlayerdivisionmatchessRelatedByPlayerhomekey()
     */
    public function clearPlayerdivisionmatchessRelatedByPlayerhomekey()
    {
        $this->collPlayerdivisionmatchessRelatedByPlayerhomekey = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collPlayerdivisionmatchessRelatedByPlayerhomekey collection loaded partially.
     */
    public function resetPartialPlayerdivisionmatchessRelatedByPlayerhomekey($v = true)
    {
        $this->collPlayerdivisionmatchessRelatedByPlayerhomekeyPartial = $v;
    }

    /**
     * Initializes the collPlayerdivisionmatchessRelatedByPlayerhomekey collection.
     *
     * By default this just sets the collPlayerdivisionmatchessRelatedByPlayerhomekey collection to an empty array (like clearcollPlayerdivisionmatchessRelatedByPlayerhomekey());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPlayerdivisionmatchessRelatedByPlayerhomekey($overrideExisting = true)
    {
        if (null !== $this->collPlayerdivisionmatchessRelatedByPlayerhomekey && !$overrideExisting) {
            return;
        }
        $this->collPlayerdivisionmatchessRelatedByPlayerhomekey = new ObjectCollection();
        $this->collPlayerdivisionmatchessRelatedByPlayerhomekey->setModel('\Playerdivisionmatches');
    }

    /**
     * Gets an array of ChildPlayerdivisionmatches objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildPlayers is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildPlayerdivisionmatches[] List of ChildPlayerdivisionmatches objects
     * @throws PropelException
     */
    public function getPlayerdivisionmatchessRelatedByPlayerhomekey(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collPlayerdivisionmatchessRelatedByPlayerhomekeyPartial && !$this->isNew();
        if (null === $this->collPlayerdivisionmatchessRelatedByPlayerhomekey || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPlayerdivisionmatchessRelatedByPlayerhomekey) {
                // return empty collection
                $this->initPlayerdivisionmatchessRelatedByPlayerhomekey();
            } else {
                $collPlayerdivisionmatchessRelatedByPlayerhomekey = ChildPlayerdivisionmatchesQuery::create(null, $criteria)
                    ->filterByDivisionMatchesPlayerHome($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collPlayerdivisionmatchessRelatedByPlayerhomekeyPartial && count($collPlayerdivisionmatchessRelatedByPlayerhomekey)) {
                        $this->initPlayerdivisionmatchessRelatedByPlayerhomekey(false);

                        foreach ($collPlayerdivisionmatchessRelatedByPlayerhomekey as $obj) {
                            if (false == $this->collPlayerdivisionmatchessRelatedByPlayerhomekey->contains($obj)) {
                                $this->collPlayerdivisionmatchessRelatedByPlayerhomekey->append($obj);
                            }
                        }

                        $this->collPlayerdivisionmatchessRelatedByPlayerhomekeyPartial = true;
                    }

                    return $collPlayerdivisionmatchessRelatedByPlayerhomekey;
                }

                if ($partial && $this->collPlayerdivisionmatchessRelatedByPlayerhomekey) {
                    foreach ($this->collPlayerdivisionmatchessRelatedByPlayerhomekey as $obj) {
                        if ($obj->isNew()) {
                            $collPlayerdivisionmatchessRelatedByPlayerhomekey[] = $obj;
                        }
                    }
                }

                $this->collPlayerdivisionmatchessRelatedByPlayerhomekey = $collPlayerdivisionmatchessRelatedByPlayerhomekey;
                $this->collPlayerdivisionmatchessRelatedByPlayerhomekeyPartial = false;
            }
        }

        return $this->collPlayerdivisionmatchessRelatedByPlayerhomekey;
    }

    /**
     * Sets a collection of ChildPlayerdivisionmatches objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $playerdivisionmatchessRelatedByPlayerhomekey A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildPlayers The current object (for fluent API support)
     */
    public function setPlayerdivisionmatchessRelatedByPlayerhomekey(Collection $playerdivisionmatchessRelatedByPlayerhomekey, ConnectionInterface $con = null)
    {
        /** @var ChildPlayerdivisionmatches[] $playerdivisionmatchessRelatedByPlayerhomekeyToDelete */
        $playerdivisionmatchessRelatedByPlayerhomekeyToDelete = $this->getPlayerdivisionmatchessRelatedByPlayerhomekey(new Criteria(), $con)->diff($playerdivisionmatchessRelatedByPlayerhomekey);


        $this->playerdivisionmatchessRelatedByPlayerhomekeyScheduledForDeletion = $playerdivisionmatchessRelatedByPlayerhomekeyToDelete;

        foreach ($playerdivisionmatchessRelatedByPlayerhomekeyToDelete as $playerdivisionmatchesRelatedByPlayerhomekeyRemoved) {
            $playerdivisionmatchesRelatedByPlayerhomekeyRemoved->setDivisionMatchesPlayerHome(null);
        }

        $this->collPlayerdivisionmatchessRelatedByPlayerhomekey = null;
        foreach ($playerdivisionmatchessRelatedByPlayerhomekey as $playerdivisionmatchesRelatedByPlayerhomekey) {
            $this->addPlayerdivisionmatchesRelatedByPlayerhomekey($playerdivisionmatchesRelatedByPlayerhomekey);
        }

        $this->collPlayerdivisionmatchessRelatedByPlayerhomekey = $playerdivisionmatchessRelatedByPlayerhomekey;
        $this->collPlayerdivisionmatchessRelatedByPlayerhomekeyPartial = false;

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
    public function countPlayerdivisionmatchessRelatedByPlayerhomekey(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collPlayerdivisionmatchessRelatedByPlayerhomekeyPartial && !$this->isNew();
        if (null === $this->collPlayerdivisionmatchessRelatedByPlayerhomekey || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPlayerdivisionmatchessRelatedByPlayerhomekey) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPlayerdivisionmatchessRelatedByPlayerhomekey());
            }

            $query = ChildPlayerdivisionmatchesQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByDivisionMatchesPlayerHome($this)
                ->count($con);
        }

        return count($this->collPlayerdivisionmatchessRelatedByPlayerhomekey);
    }

    /**
     * Method called to associate a ChildPlayerdivisionmatches object to this object
     * through the ChildPlayerdivisionmatches foreign key attribute.
     *
     * @param  ChildPlayerdivisionmatches $l ChildPlayerdivisionmatches
     * @return $this|\Players The current object (for fluent API support)
     */
    public function addPlayerdivisionmatchesRelatedByPlayerhomekey(ChildPlayerdivisionmatches $l)
    {
        if ($this->collPlayerdivisionmatchessRelatedByPlayerhomekey === null) {
            $this->initPlayerdivisionmatchessRelatedByPlayerhomekey();
            $this->collPlayerdivisionmatchessRelatedByPlayerhomekeyPartial = true;
        }

        if (!$this->collPlayerdivisionmatchessRelatedByPlayerhomekey->contains($l)) {
            $this->doAddPlayerdivisionmatchesRelatedByPlayerhomekey($l);
        }

        return $this;
    }

    /**
     * @param ChildPlayerdivisionmatches $playerdivisionmatchesRelatedByPlayerhomekey The ChildPlayerdivisionmatches object to add.
     */
    protected function doAddPlayerdivisionmatchesRelatedByPlayerhomekey(ChildPlayerdivisionmatches $playerdivisionmatchesRelatedByPlayerhomekey)
    {
        $this->collPlayerdivisionmatchessRelatedByPlayerhomekey[]= $playerdivisionmatchesRelatedByPlayerhomekey;
        $playerdivisionmatchesRelatedByPlayerhomekey->setDivisionMatchesPlayerHome($this);
    }

    /**
     * @param  ChildPlayerdivisionmatches $playerdivisionmatchesRelatedByPlayerhomekey The ChildPlayerdivisionmatches object to remove.
     * @return $this|ChildPlayers The current object (for fluent API support)
     */
    public function removePlayerdivisionmatchesRelatedByPlayerhomekey(ChildPlayerdivisionmatches $playerdivisionmatchesRelatedByPlayerhomekey)
    {
        if ($this->getPlayerdivisionmatchessRelatedByPlayerhomekey()->contains($playerdivisionmatchesRelatedByPlayerhomekey)) {
            $pos = $this->collPlayerdivisionmatchessRelatedByPlayerhomekey->search($playerdivisionmatchesRelatedByPlayerhomekey);
            $this->collPlayerdivisionmatchessRelatedByPlayerhomekey->remove($pos);
            if (null === $this->playerdivisionmatchessRelatedByPlayerhomekeyScheduledForDeletion) {
                $this->playerdivisionmatchessRelatedByPlayerhomekeyScheduledForDeletion = clone $this->collPlayerdivisionmatchessRelatedByPlayerhomekey;
                $this->playerdivisionmatchessRelatedByPlayerhomekeyScheduledForDeletion->clear();
            }
            $this->playerdivisionmatchessRelatedByPlayerhomekeyScheduledForDeletion[]= clone $playerdivisionmatchesRelatedByPlayerhomekey;
            $playerdivisionmatchesRelatedByPlayerhomekey->setDivisionMatchesPlayerHome(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Players is new, it will return
     * an empty collection; or if this Players has previously
     * been saved, it will retrieve related PlayerdivisionmatchessRelatedByPlayerhomekey from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Players.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildPlayerdivisionmatches[] List of ChildPlayerdivisionmatches objects
     */
    public function getPlayerdivisionmatchessRelatedByPlayerhomekeyJoinDivisionMatchesDivision(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildPlayerdivisionmatchesQuery::create(null, $criteria);
        $query->joinWith('DivisionMatchesDivision', $joinBehavior);

        return $this->getPlayerdivisionmatchessRelatedByPlayerhomekey($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Players is new, it will return
     * an empty collection; or if this Players has previously
     * been saved, it will retrieve related PlayerdivisionmatchessRelatedByPlayerhomekey from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Players.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildPlayerdivisionmatches[] List of ChildPlayerdivisionmatches objects
     */
    public function getPlayerdivisionmatchessRelatedByPlayerhomekeyJoinDivisionMatchesGroup(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildPlayerdivisionmatchesQuery::create(null, $criteria);
        $query->joinWith('DivisionMatchesGroup', $joinBehavior);

        return $this->getPlayerdivisionmatchessRelatedByPlayerhomekey($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Players is new, it will return
     * an empty collection; or if this Players has previously
     * been saved, it will retrieve related PlayerdivisionmatchessRelatedByPlayerhomekey from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Players.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildPlayerdivisionmatches[] List of ChildPlayerdivisionmatches objects
     */
    public function getPlayerdivisionmatchessRelatedByPlayerhomekeyJoinDivisionMatchesSeason(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildPlayerdivisionmatchesQuery::create(null, $criteria);
        $query->joinWith('DivisionMatchesSeason', $joinBehavior);

        return $this->getPlayerdivisionmatchessRelatedByPlayerhomekey($query, $con);
    }

    /**
     * Clears out the collPlayerdivisionmatchessRelatedByPlayerawaykey collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addPlayerdivisionmatchessRelatedByPlayerawaykey()
     */
    public function clearPlayerdivisionmatchessRelatedByPlayerawaykey()
    {
        $this->collPlayerdivisionmatchessRelatedByPlayerawaykey = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collPlayerdivisionmatchessRelatedByPlayerawaykey collection loaded partially.
     */
    public function resetPartialPlayerdivisionmatchessRelatedByPlayerawaykey($v = true)
    {
        $this->collPlayerdivisionmatchessRelatedByPlayerawaykeyPartial = $v;
    }

    /**
     * Initializes the collPlayerdivisionmatchessRelatedByPlayerawaykey collection.
     *
     * By default this just sets the collPlayerdivisionmatchessRelatedByPlayerawaykey collection to an empty array (like clearcollPlayerdivisionmatchessRelatedByPlayerawaykey());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPlayerdivisionmatchessRelatedByPlayerawaykey($overrideExisting = true)
    {
        if (null !== $this->collPlayerdivisionmatchessRelatedByPlayerawaykey && !$overrideExisting) {
            return;
        }
        $this->collPlayerdivisionmatchessRelatedByPlayerawaykey = new ObjectCollection();
        $this->collPlayerdivisionmatchessRelatedByPlayerawaykey->setModel('\Playerdivisionmatches');
    }

    /**
     * Gets an array of ChildPlayerdivisionmatches objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildPlayers is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildPlayerdivisionmatches[] List of ChildPlayerdivisionmatches objects
     * @throws PropelException
     */
    public function getPlayerdivisionmatchessRelatedByPlayerawaykey(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collPlayerdivisionmatchessRelatedByPlayerawaykeyPartial && !$this->isNew();
        if (null === $this->collPlayerdivisionmatchessRelatedByPlayerawaykey || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPlayerdivisionmatchessRelatedByPlayerawaykey) {
                // return empty collection
                $this->initPlayerdivisionmatchessRelatedByPlayerawaykey();
            } else {
                $collPlayerdivisionmatchessRelatedByPlayerawaykey = ChildPlayerdivisionmatchesQuery::create(null, $criteria)
                    ->filterByDivisionMatchesPlayerAway($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collPlayerdivisionmatchessRelatedByPlayerawaykeyPartial && count($collPlayerdivisionmatchessRelatedByPlayerawaykey)) {
                        $this->initPlayerdivisionmatchessRelatedByPlayerawaykey(false);

                        foreach ($collPlayerdivisionmatchessRelatedByPlayerawaykey as $obj) {
                            if (false == $this->collPlayerdivisionmatchessRelatedByPlayerawaykey->contains($obj)) {
                                $this->collPlayerdivisionmatchessRelatedByPlayerawaykey->append($obj);
                            }
                        }

                        $this->collPlayerdivisionmatchessRelatedByPlayerawaykeyPartial = true;
                    }

                    return $collPlayerdivisionmatchessRelatedByPlayerawaykey;
                }

                if ($partial && $this->collPlayerdivisionmatchessRelatedByPlayerawaykey) {
                    foreach ($this->collPlayerdivisionmatchessRelatedByPlayerawaykey as $obj) {
                        if ($obj->isNew()) {
                            $collPlayerdivisionmatchessRelatedByPlayerawaykey[] = $obj;
                        }
                    }
                }

                $this->collPlayerdivisionmatchessRelatedByPlayerawaykey = $collPlayerdivisionmatchessRelatedByPlayerawaykey;
                $this->collPlayerdivisionmatchessRelatedByPlayerawaykeyPartial = false;
            }
        }

        return $this->collPlayerdivisionmatchessRelatedByPlayerawaykey;
    }

    /**
     * Sets a collection of ChildPlayerdivisionmatches objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $playerdivisionmatchessRelatedByPlayerawaykey A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildPlayers The current object (for fluent API support)
     */
    public function setPlayerdivisionmatchessRelatedByPlayerawaykey(Collection $playerdivisionmatchessRelatedByPlayerawaykey, ConnectionInterface $con = null)
    {
        /** @var ChildPlayerdivisionmatches[] $playerdivisionmatchessRelatedByPlayerawaykeyToDelete */
        $playerdivisionmatchessRelatedByPlayerawaykeyToDelete = $this->getPlayerdivisionmatchessRelatedByPlayerawaykey(new Criteria(), $con)->diff($playerdivisionmatchessRelatedByPlayerawaykey);


        $this->playerdivisionmatchessRelatedByPlayerawaykeyScheduledForDeletion = $playerdivisionmatchessRelatedByPlayerawaykeyToDelete;

        foreach ($playerdivisionmatchessRelatedByPlayerawaykeyToDelete as $playerdivisionmatchesRelatedByPlayerawaykeyRemoved) {
            $playerdivisionmatchesRelatedByPlayerawaykeyRemoved->setDivisionMatchesPlayerAway(null);
        }

        $this->collPlayerdivisionmatchessRelatedByPlayerawaykey = null;
        foreach ($playerdivisionmatchessRelatedByPlayerawaykey as $playerdivisionmatchesRelatedByPlayerawaykey) {
            $this->addPlayerdivisionmatchesRelatedByPlayerawaykey($playerdivisionmatchesRelatedByPlayerawaykey);
        }

        $this->collPlayerdivisionmatchessRelatedByPlayerawaykey = $playerdivisionmatchessRelatedByPlayerawaykey;
        $this->collPlayerdivisionmatchessRelatedByPlayerawaykeyPartial = false;

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
    public function countPlayerdivisionmatchessRelatedByPlayerawaykey(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collPlayerdivisionmatchessRelatedByPlayerawaykeyPartial && !$this->isNew();
        if (null === $this->collPlayerdivisionmatchessRelatedByPlayerawaykey || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPlayerdivisionmatchessRelatedByPlayerawaykey) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPlayerdivisionmatchessRelatedByPlayerawaykey());
            }

            $query = ChildPlayerdivisionmatchesQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByDivisionMatchesPlayerAway($this)
                ->count($con);
        }

        return count($this->collPlayerdivisionmatchessRelatedByPlayerawaykey);
    }

    /**
     * Method called to associate a ChildPlayerdivisionmatches object to this object
     * through the ChildPlayerdivisionmatches foreign key attribute.
     *
     * @param  ChildPlayerdivisionmatches $l ChildPlayerdivisionmatches
     * @return $this|\Players The current object (for fluent API support)
     */
    public function addPlayerdivisionmatchesRelatedByPlayerawaykey(ChildPlayerdivisionmatches $l)
    {
        if ($this->collPlayerdivisionmatchessRelatedByPlayerawaykey === null) {
            $this->initPlayerdivisionmatchessRelatedByPlayerawaykey();
            $this->collPlayerdivisionmatchessRelatedByPlayerawaykeyPartial = true;
        }

        if (!$this->collPlayerdivisionmatchessRelatedByPlayerawaykey->contains($l)) {
            $this->doAddPlayerdivisionmatchesRelatedByPlayerawaykey($l);
        }

        return $this;
    }

    /**
     * @param ChildPlayerdivisionmatches $playerdivisionmatchesRelatedByPlayerawaykey The ChildPlayerdivisionmatches object to add.
     */
    protected function doAddPlayerdivisionmatchesRelatedByPlayerawaykey(ChildPlayerdivisionmatches $playerdivisionmatchesRelatedByPlayerawaykey)
    {
        $this->collPlayerdivisionmatchessRelatedByPlayerawaykey[]= $playerdivisionmatchesRelatedByPlayerawaykey;
        $playerdivisionmatchesRelatedByPlayerawaykey->setDivisionMatchesPlayerAway($this);
    }

    /**
     * @param  ChildPlayerdivisionmatches $playerdivisionmatchesRelatedByPlayerawaykey The ChildPlayerdivisionmatches object to remove.
     * @return $this|ChildPlayers The current object (for fluent API support)
     */
    public function removePlayerdivisionmatchesRelatedByPlayerawaykey(ChildPlayerdivisionmatches $playerdivisionmatchesRelatedByPlayerawaykey)
    {
        if ($this->getPlayerdivisionmatchessRelatedByPlayerawaykey()->contains($playerdivisionmatchesRelatedByPlayerawaykey)) {
            $pos = $this->collPlayerdivisionmatchessRelatedByPlayerawaykey->search($playerdivisionmatchesRelatedByPlayerawaykey);
            $this->collPlayerdivisionmatchessRelatedByPlayerawaykey->remove($pos);
            if (null === $this->playerdivisionmatchessRelatedByPlayerawaykeyScheduledForDeletion) {
                $this->playerdivisionmatchessRelatedByPlayerawaykeyScheduledForDeletion = clone $this->collPlayerdivisionmatchessRelatedByPlayerawaykey;
                $this->playerdivisionmatchessRelatedByPlayerawaykeyScheduledForDeletion->clear();
            }
            $this->playerdivisionmatchessRelatedByPlayerawaykeyScheduledForDeletion[]= clone $playerdivisionmatchesRelatedByPlayerawaykey;
            $playerdivisionmatchesRelatedByPlayerawaykey->setDivisionMatchesPlayerAway(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Players is new, it will return
     * an empty collection; or if this Players has previously
     * been saved, it will retrieve related PlayerdivisionmatchessRelatedByPlayerawaykey from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Players.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildPlayerdivisionmatches[] List of ChildPlayerdivisionmatches objects
     */
    public function getPlayerdivisionmatchessRelatedByPlayerawaykeyJoinDivisionMatchesDivision(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildPlayerdivisionmatchesQuery::create(null, $criteria);
        $query->joinWith('DivisionMatchesDivision', $joinBehavior);

        return $this->getPlayerdivisionmatchessRelatedByPlayerawaykey($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Players is new, it will return
     * an empty collection; or if this Players has previously
     * been saved, it will retrieve related PlayerdivisionmatchessRelatedByPlayerawaykey from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Players.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildPlayerdivisionmatches[] List of ChildPlayerdivisionmatches objects
     */
    public function getPlayerdivisionmatchessRelatedByPlayerawaykeyJoinDivisionMatchesGroup(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildPlayerdivisionmatchesQuery::create(null, $criteria);
        $query->joinWith('DivisionMatchesGroup', $joinBehavior);

        return $this->getPlayerdivisionmatchessRelatedByPlayerawaykey($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Players is new, it will return
     * an empty collection; or if this Players has previously
     * been saved, it will retrieve related PlayerdivisionmatchessRelatedByPlayerawaykey from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Players.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildPlayerdivisionmatches[] List of ChildPlayerdivisionmatches objects
     */
    public function getPlayerdivisionmatchessRelatedByPlayerawaykeyJoinDivisionMatchesSeason(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildPlayerdivisionmatchesQuery::create(null, $criteria);
        $query->joinWith('DivisionMatchesSeason', $joinBehavior);

        return $this->getPlayerdivisionmatchessRelatedByPlayerawaykey($query, $con);
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
     * If this ChildPlayers is new, it will return
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
                    ->filterByDivisionRankingPlayer($this)
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
     * @return $this|ChildPlayers The current object (for fluent API support)
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
            $playerdivisionrankingRemoved->setDivisionRankingPlayer(null);
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
                ->filterByDivisionRankingPlayer($this)
                ->count($con);
        }

        return count($this->collPlayerdivisionrankings);
    }

    /**
     * Method called to associate a ChildPlayerdivisionranking object to this object
     * through the ChildPlayerdivisionranking foreign key attribute.
     *
     * @param  ChildPlayerdivisionranking $l ChildPlayerdivisionranking
     * @return $this|\Players The current object (for fluent API support)
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
        $playerdivisionranking->setDivisionRankingPlayer($this);
    }

    /**
     * @param  ChildPlayerdivisionranking $playerdivisionranking The ChildPlayerdivisionranking object to remove.
     * @return $this|ChildPlayers The current object (for fluent API support)
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
            $playerdivisionranking->setDivisionRankingPlayer(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Players is new, it will return
     * an empty collection; or if this Players has previously
     * been saved, it will retrieve related Playerdivisionrankings from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Players.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildPlayerdivisionranking[] List of ChildPlayerdivisionranking objects
     */
    public function getPlayerdivisionrankingsJoinDivisionRankingSeason(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildPlayerdivisionrankingQuery::create(null, $criteria);
        $query->joinWith('DivisionRankingSeason', $joinBehavior);

        return $this->getPlayerdivisionrankings($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Players is new, it will return
     * an empty collection; or if this Players has previously
     * been saved, it will retrieve related Playerdivisionrankings from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Players.
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
     * Clears out the collPlayergrouprankings collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addPlayergrouprankings()
     */
    public function clearPlayergrouprankings()
    {
        $this->collPlayergrouprankings = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collPlayergrouprankings collection loaded partially.
     */
    public function resetPartialPlayergrouprankings($v = true)
    {
        $this->collPlayergrouprankingsPartial = $v;
    }

    /**
     * Initializes the collPlayergrouprankings collection.
     *
     * By default this just sets the collPlayergrouprankings collection to an empty array (like clearcollPlayergrouprankings());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPlayergrouprankings($overrideExisting = true)
    {
        if (null !== $this->collPlayergrouprankings && !$overrideExisting) {
            return;
        }
        $this->collPlayergrouprankings = new ObjectCollection();
        $this->collPlayergrouprankings->setModel('\Playergroupranking');
    }

    /**
     * Gets an array of ChildPlayergroupranking objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildPlayers is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildPlayergroupranking[] List of ChildPlayergroupranking objects
     * @throws PropelException
     */
    public function getPlayergrouprankings(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collPlayergrouprankingsPartial && !$this->isNew();
        if (null === $this->collPlayergrouprankings || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPlayergrouprankings) {
                // return empty collection
                $this->initPlayergrouprankings();
            } else {
                $collPlayergrouprankings = ChildPlayergrouprankingQuery::create(null, $criteria)
                    ->filterByPlayerRanking($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collPlayergrouprankingsPartial && count($collPlayergrouprankings)) {
                        $this->initPlayergrouprankings(false);

                        foreach ($collPlayergrouprankings as $obj) {
                            if (false == $this->collPlayergrouprankings->contains($obj)) {
                                $this->collPlayergrouprankings->append($obj);
                            }
                        }

                        $this->collPlayergrouprankingsPartial = true;
                    }

                    return $collPlayergrouprankings;
                }

                if ($partial && $this->collPlayergrouprankings) {
                    foreach ($this->collPlayergrouprankings as $obj) {
                        if ($obj->isNew()) {
                            $collPlayergrouprankings[] = $obj;
                        }
                    }
                }

                $this->collPlayergrouprankings = $collPlayergrouprankings;
                $this->collPlayergrouprankingsPartial = false;
            }
        }

        return $this->collPlayergrouprankings;
    }

    /**
     * Sets a collection of ChildPlayergroupranking objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $playergrouprankings A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildPlayers The current object (for fluent API support)
     */
    public function setPlayergrouprankings(Collection $playergrouprankings, ConnectionInterface $con = null)
    {
        /** @var ChildPlayergroupranking[] $playergrouprankingsToDelete */
        $playergrouprankingsToDelete = $this->getPlayergrouprankings(new Criteria(), $con)->diff($playergrouprankings);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->playergrouprankingsScheduledForDeletion = clone $playergrouprankingsToDelete;

        foreach ($playergrouprankingsToDelete as $playergrouprankingRemoved) {
            $playergrouprankingRemoved->setPlayerRanking(null);
        }

        $this->collPlayergrouprankings = null;
        foreach ($playergrouprankings as $playergroupranking) {
            $this->addPlayergroupranking($playergroupranking);
        }

        $this->collPlayergrouprankings = $playergrouprankings;
        $this->collPlayergrouprankingsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Playergroupranking objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Playergroupranking objects.
     * @throws PropelException
     */
    public function countPlayergrouprankings(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collPlayergrouprankingsPartial && !$this->isNew();
        if (null === $this->collPlayergrouprankings || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPlayergrouprankings) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPlayergrouprankings());
            }

            $query = ChildPlayergrouprankingQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPlayerRanking($this)
                ->count($con);
        }

        return count($this->collPlayergrouprankings);
    }

    /**
     * Method called to associate a ChildPlayergroupranking object to this object
     * through the ChildPlayergroupranking foreign key attribute.
     *
     * @param  ChildPlayergroupranking $l ChildPlayergroupranking
     * @return $this|\Players The current object (for fluent API support)
     */
    public function addPlayergroupranking(ChildPlayergroupranking $l)
    {
        if ($this->collPlayergrouprankings === null) {
            $this->initPlayergrouprankings();
            $this->collPlayergrouprankingsPartial = true;
        }

        if (!$this->collPlayergrouprankings->contains($l)) {
            $this->doAddPlayergroupranking($l);
        }

        return $this;
    }

    /**
     * @param ChildPlayergroupranking $playergroupranking The ChildPlayergroupranking object to add.
     */
    protected function doAddPlayergroupranking(ChildPlayergroupranking $playergroupranking)
    {
        $this->collPlayergrouprankings[]= $playergroupranking;
        $playergroupranking->setPlayerRanking($this);
    }

    /**
     * @param  ChildPlayergroupranking $playergroupranking The ChildPlayergroupranking object to remove.
     * @return $this|ChildPlayers The current object (for fluent API support)
     */
    public function removePlayergroupranking(ChildPlayergroupranking $playergroupranking)
    {
        if ($this->getPlayergrouprankings()->contains($playergroupranking)) {
            $pos = $this->collPlayergrouprankings->search($playergroupranking);
            $this->collPlayergrouprankings->remove($pos);
            if (null === $this->playergrouprankingsScheduledForDeletion) {
                $this->playergrouprankingsScheduledForDeletion = clone $this->collPlayergrouprankings;
                $this->playergrouprankingsScheduledForDeletion->clear();
            }
            $this->playergrouprankingsScheduledForDeletion[]= clone $playergroupranking;
            $playergroupranking->setPlayerRanking(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Players is new, it will return
     * an empty collection; or if this Players has previously
     * been saved, it will retrieve related Playergrouprankings from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Players.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildPlayergroupranking[] List of ChildPlayergroupranking objects
     */
    public function getPlayergrouprankingsJoinGroupRanking(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildPlayergrouprankingQuery::create(null, $criteria);
        $query->joinWith('GroupRanking', $joinBehavior);

        return $this->getPlayergrouprankings($query, $con);
    }

    /**
     * Clears out the collPlayergroupresultss collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addPlayergroupresultss()
     */
    public function clearPlayergroupresultss()
    {
        $this->collPlayergroupresultss = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collPlayergroupresultss collection loaded partially.
     */
    public function resetPartialPlayergroupresultss($v = true)
    {
        $this->collPlayergroupresultssPartial = $v;
    }

    /**
     * Initializes the collPlayergroupresultss collection.
     *
     * By default this just sets the collPlayergroupresultss collection to an empty array (like clearcollPlayergroupresultss());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPlayergroupresultss($overrideExisting = true)
    {
        if (null !== $this->collPlayergroupresultss && !$overrideExisting) {
            return;
        }
        $this->collPlayergroupresultss = new ObjectCollection();
        $this->collPlayergroupresultss->setModel('\Playergroupresults');
    }

    /**
     * Gets an array of ChildPlayergroupresults objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildPlayers is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildPlayergroupresults[] List of ChildPlayergroupresults objects
     * @throws PropelException
     */
    public function getPlayergroupresultss(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collPlayergroupresultssPartial && !$this->isNew();
        if (null === $this->collPlayergroupresultss || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPlayergroupresultss) {
                // return empty collection
                $this->initPlayergroupresultss();
            } else {
                $collPlayergroupresultss = ChildPlayergroupresultsQuery::create(null, $criteria)
                    ->filterByPlayerResult($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collPlayergroupresultssPartial && count($collPlayergroupresultss)) {
                        $this->initPlayergroupresultss(false);

                        foreach ($collPlayergroupresultss as $obj) {
                            if (false == $this->collPlayergroupresultss->contains($obj)) {
                                $this->collPlayergroupresultss->append($obj);
                            }
                        }

                        $this->collPlayergroupresultssPartial = true;
                    }

                    return $collPlayergroupresultss;
                }

                if ($partial && $this->collPlayergroupresultss) {
                    foreach ($this->collPlayergroupresultss as $obj) {
                        if ($obj->isNew()) {
                            $collPlayergroupresultss[] = $obj;
                        }
                    }
                }

                $this->collPlayergroupresultss = $collPlayergroupresultss;
                $this->collPlayergroupresultssPartial = false;
            }
        }

        return $this->collPlayergroupresultss;
    }

    /**
     * Sets a collection of ChildPlayergroupresults objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $playergroupresultss A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildPlayers The current object (for fluent API support)
     */
    public function setPlayergroupresultss(Collection $playergroupresultss, ConnectionInterface $con = null)
    {
        /** @var ChildPlayergroupresults[] $playergroupresultssToDelete */
        $playergroupresultssToDelete = $this->getPlayergroupresultss(new Criteria(), $con)->diff($playergroupresultss);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->playergroupresultssScheduledForDeletion = clone $playergroupresultssToDelete;

        foreach ($playergroupresultssToDelete as $playergroupresultsRemoved) {
            $playergroupresultsRemoved->setPlayerResult(null);
        }

        $this->collPlayergroupresultss = null;
        foreach ($playergroupresultss as $playergroupresults) {
            $this->addPlayergroupresults($playergroupresults);
        }

        $this->collPlayergroupresultss = $playergroupresultss;
        $this->collPlayergroupresultssPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Playergroupresults objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Playergroupresults objects.
     * @throws PropelException
     */
    public function countPlayergroupresultss(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collPlayergroupresultssPartial && !$this->isNew();
        if (null === $this->collPlayergroupresultss || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPlayergroupresultss) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPlayergroupresultss());
            }

            $query = ChildPlayergroupresultsQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPlayerResult($this)
                ->count($con);
        }

        return count($this->collPlayergroupresultss);
    }

    /**
     * Method called to associate a ChildPlayergroupresults object to this object
     * through the ChildPlayergroupresults foreign key attribute.
     *
     * @param  ChildPlayergroupresults $l ChildPlayergroupresults
     * @return $this|\Players The current object (for fluent API support)
     */
    public function addPlayergroupresults(ChildPlayergroupresults $l)
    {
        if ($this->collPlayergroupresultss === null) {
            $this->initPlayergroupresultss();
            $this->collPlayergroupresultssPartial = true;
        }

        if (!$this->collPlayergroupresultss->contains($l)) {
            $this->doAddPlayergroupresults($l);
        }

        return $this;
    }

    /**
     * @param ChildPlayergroupresults $playergroupresults The ChildPlayergroupresults object to add.
     */
    protected function doAddPlayergroupresults(ChildPlayergroupresults $playergroupresults)
    {
        $this->collPlayergroupresultss[]= $playergroupresults;
        $playergroupresults->setPlayerResult($this);
    }

    /**
     * @param  ChildPlayergroupresults $playergroupresults The ChildPlayergroupresults object to remove.
     * @return $this|ChildPlayers The current object (for fluent API support)
     */
    public function removePlayergroupresults(ChildPlayergroupresults $playergroupresults)
    {
        if ($this->getPlayergroupresultss()->contains($playergroupresults)) {
            $pos = $this->collPlayergroupresultss->search($playergroupresults);
            $this->collPlayergroupresultss->remove($pos);
            if (null === $this->playergroupresultssScheduledForDeletion) {
                $this->playergroupresultssScheduledForDeletion = clone $this->collPlayergroupresultss;
                $this->playergroupresultssScheduledForDeletion->clear();
            }
            $this->playergroupresultssScheduledForDeletion[]= clone $playergroupresults;
            $playergroupresults->setPlayerResult(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Players is new, it will return
     * an empty collection; or if this Players has previously
     * been saved, it will retrieve related Playergroupresultss from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Players.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildPlayergroupresults[] List of ChildPlayergroupresults objects
     */
    public function getPlayergroupresultssJoinGroupResult(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildPlayergroupresultsQuery::create(null, $criteria);
        $query->joinWith('GroupResult', $joinBehavior);

        return $this->getPlayergroupresultss($query, $con);
    }

    /**
     * Clears out the collPlayergroupstatess collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addPlayergroupstatess()
     */
    public function clearPlayergroupstatess()
    {
        $this->collPlayergroupstatess = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collPlayergroupstatess collection loaded partially.
     */
    public function resetPartialPlayergroupstatess($v = true)
    {
        $this->collPlayergroupstatessPartial = $v;
    }

    /**
     * Initializes the collPlayergroupstatess collection.
     *
     * By default this just sets the collPlayergroupstatess collection to an empty array (like clearcollPlayergroupstatess());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPlayergroupstatess($overrideExisting = true)
    {
        if (null !== $this->collPlayergroupstatess && !$overrideExisting) {
            return;
        }
        $this->collPlayergroupstatess = new ObjectCollection();
        $this->collPlayergroupstatess->setModel('\Playergroupstates');
    }

    /**
     * Gets an array of ChildPlayergroupstates objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildPlayers is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildPlayergroupstates[] List of ChildPlayergroupstates objects
     * @throws PropelException
     */
    public function getPlayergroupstatess(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collPlayergroupstatessPartial && !$this->isNew();
        if (null === $this->collPlayergroupstatess || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPlayergroupstatess) {
                // return empty collection
                $this->initPlayergroupstatess();
            } else {
                $collPlayergroupstatess = ChildPlayergroupstatesQuery::create(null, $criteria)
                    ->filterByPlayerState($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collPlayergroupstatessPartial && count($collPlayergroupstatess)) {
                        $this->initPlayergroupstatess(false);

                        foreach ($collPlayergroupstatess as $obj) {
                            if (false == $this->collPlayergroupstatess->contains($obj)) {
                                $this->collPlayergroupstatess->append($obj);
                            }
                        }

                        $this->collPlayergroupstatessPartial = true;
                    }

                    return $collPlayergroupstatess;
                }

                if ($partial && $this->collPlayergroupstatess) {
                    foreach ($this->collPlayergroupstatess as $obj) {
                        if ($obj->isNew()) {
                            $collPlayergroupstatess[] = $obj;
                        }
                    }
                }

                $this->collPlayergroupstatess = $collPlayergroupstatess;
                $this->collPlayergroupstatessPartial = false;
            }
        }

        return $this->collPlayergroupstatess;
    }

    /**
     * Sets a collection of ChildPlayergroupstates objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $playergroupstatess A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildPlayers The current object (for fluent API support)
     */
    public function setPlayergroupstatess(Collection $playergroupstatess, ConnectionInterface $con = null)
    {
        /** @var ChildPlayergroupstates[] $playergroupstatessToDelete */
        $playergroupstatessToDelete = $this->getPlayergroupstatess(new Criteria(), $con)->diff($playergroupstatess);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->playergroupstatessScheduledForDeletion = clone $playergroupstatessToDelete;

        foreach ($playergroupstatessToDelete as $playergroupstatesRemoved) {
            $playergroupstatesRemoved->setPlayerState(null);
        }

        $this->collPlayergroupstatess = null;
        foreach ($playergroupstatess as $playergroupstates) {
            $this->addPlayergroupstates($playergroupstates);
        }

        $this->collPlayergroupstatess = $playergroupstatess;
        $this->collPlayergroupstatessPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Playergroupstates objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Playergroupstates objects.
     * @throws PropelException
     */
    public function countPlayergroupstatess(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collPlayergroupstatessPartial && !$this->isNew();
        if (null === $this->collPlayergroupstatess || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPlayergroupstatess) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPlayergroupstatess());
            }

            $query = ChildPlayergroupstatesQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPlayerState($this)
                ->count($con);
        }

        return count($this->collPlayergroupstatess);
    }

    /**
     * Method called to associate a ChildPlayergroupstates object to this object
     * through the ChildPlayergroupstates foreign key attribute.
     *
     * @param  ChildPlayergroupstates $l ChildPlayergroupstates
     * @return $this|\Players The current object (for fluent API support)
     */
    public function addPlayergroupstates(ChildPlayergroupstates $l)
    {
        if ($this->collPlayergroupstatess === null) {
            $this->initPlayergroupstatess();
            $this->collPlayergroupstatessPartial = true;
        }

        if (!$this->collPlayergroupstatess->contains($l)) {
            $this->doAddPlayergroupstates($l);
        }

        return $this;
    }

    /**
     * @param ChildPlayergroupstates $playergroupstates The ChildPlayergroupstates object to add.
     */
    protected function doAddPlayergroupstates(ChildPlayergroupstates $playergroupstates)
    {
        $this->collPlayergroupstatess[]= $playergroupstates;
        $playergroupstates->setPlayerState($this);
    }

    /**
     * @param  ChildPlayergroupstates $playergroupstates The ChildPlayergroupstates object to remove.
     * @return $this|ChildPlayers The current object (for fluent API support)
     */
    public function removePlayergroupstates(ChildPlayergroupstates $playergroupstates)
    {
        if ($this->getPlayergroupstatess()->contains($playergroupstates)) {
            $pos = $this->collPlayergroupstatess->search($playergroupstates);
            $this->collPlayergroupstatess->remove($pos);
            if (null === $this->playergroupstatessScheduledForDeletion) {
                $this->playergroupstatessScheduledForDeletion = clone $this->collPlayergroupstatess;
                $this->playergroupstatessScheduledForDeletion->clear();
            }
            $this->playergroupstatessScheduledForDeletion[]= clone $playergroupstates;
            $playergroupstates->setPlayerState(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Players is new, it will return
     * an empty collection; or if this Players has previously
     * been saved, it will retrieve related Playergroupstatess from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Players.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildPlayergroupstates[] List of ChildPlayergroupstates objects
     */
    public function getPlayergroupstatessJoinGroupState(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildPlayergroupstatesQuery::create(null, $criteria);
        $query->joinWith('GroupState', $joinBehavior);

        return $this->getPlayergroupstatess($query, $con);
    }

    /**
     * Clears out the collPlayermatchresultss collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addPlayermatchresultss()
     */
    public function clearPlayermatchresultss()
    {
        $this->collPlayermatchresultss = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collPlayermatchresultss collection loaded partially.
     */
    public function resetPartialPlayermatchresultss($v = true)
    {
        $this->collPlayermatchresultssPartial = $v;
    }

    /**
     * Initializes the collPlayermatchresultss collection.
     *
     * By default this just sets the collPlayermatchresultss collection to an empty array (like clearcollPlayermatchresultss());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPlayermatchresultss($overrideExisting = true)
    {
        if (null !== $this->collPlayermatchresultss && !$overrideExisting) {
            return;
        }
        $this->collPlayermatchresultss = new ObjectCollection();
        $this->collPlayermatchresultss->setModel('\Playermatchresults');
    }

    /**
     * Gets an array of ChildPlayermatchresults objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildPlayers is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildPlayermatchresults[] List of ChildPlayermatchresults objects
     * @throws PropelException
     */
    public function getPlayermatchresultss(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collPlayermatchresultssPartial && !$this->isNew();
        if (null === $this->collPlayermatchresultss || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPlayermatchresultss) {
                // return empty collection
                $this->initPlayermatchresultss();
            } else {
                $collPlayermatchresultss = ChildPlayermatchresultsQuery::create(null, $criteria)
                    ->filterByPlayerResult($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collPlayermatchresultssPartial && count($collPlayermatchresultss)) {
                        $this->initPlayermatchresultss(false);

                        foreach ($collPlayermatchresultss as $obj) {
                            if (false == $this->collPlayermatchresultss->contains($obj)) {
                                $this->collPlayermatchresultss->append($obj);
                            }
                        }

                        $this->collPlayermatchresultssPartial = true;
                    }

                    return $collPlayermatchresultss;
                }

                if ($partial && $this->collPlayermatchresultss) {
                    foreach ($this->collPlayermatchresultss as $obj) {
                        if ($obj->isNew()) {
                            $collPlayermatchresultss[] = $obj;
                        }
                    }
                }

                $this->collPlayermatchresultss = $collPlayermatchresultss;
                $this->collPlayermatchresultssPartial = false;
            }
        }

        return $this->collPlayermatchresultss;
    }

    /**
     * Sets a collection of ChildPlayermatchresults objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $playermatchresultss A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildPlayers The current object (for fluent API support)
     */
    public function setPlayermatchresultss(Collection $playermatchresultss, ConnectionInterface $con = null)
    {
        /** @var ChildPlayermatchresults[] $playermatchresultssToDelete */
        $playermatchresultssToDelete = $this->getPlayermatchresultss(new Criteria(), $con)->diff($playermatchresultss);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->playermatchresultssScheduledForDeletion = clone $playermatchresultssToDelete;

        foreach ($playermatchresultssToDelete as $playermatchresultsRemoved) {
            $playermatchresultsRemoved->setPlayerResult(null);
        }

        $this->collPlayermatchresultss = null;
        foreach ($playermatchresultss as $playermatchresults) {
            $this->addPlayermatchresults($playermatchresults);
        }

        $this->collPlayermatchresultss = $playermatchresultss;
        $this->collPlayermatchresultssPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Playermatchresults objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Playermatchresults objects.
     * @throws PropelException
     */
    public function countPlayermatchresultss(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collPlayermatchresultssPartial && !$this->isNew();
        if (null === $this->collPlayermatchresultss || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPlayermatchresultss) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPlayermatchresultss());
            }

            $query = ChildPlayermatchresultsQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPlayerResult($this)
                ->count($con);
        }

        return count($this->collPlayermatchresultss);
    }

    /**
     * Method called to associate a ChildPlayermatchresults object to this object
     * through the ChildPlayermatchresults foreign key attribute.
     *
     * @param  ChildPlayermatchresults $l ChildPlayermatchresults
     * @return $this|\Players The current object (for fluent API support)
     */
    public function addPlayermatchresults(ChildPlayermatchresults $l)
    {
        if ($this->collPlayermatchresultss === null) {
            $this->initPlayermatchresultss();
            $this->collPlayermatchresultssPartial = true;
        }

        if (!$this->collPlayermatchresultss->contains($l)) {
            $this->doAddPlayermatchresults($l);
        }

        return $this;
    }

    /**
     * @param ChildPlayermatchresults $playermatchresults The ChildPlayermatchresults object to add.
     */
    protected function doAddPlayermatchresults(ChildPlayermatchresults $playermatchresults)
    {
        $this->collPlayermatchresultss[]= $playermatchresults;
        $playermatchresults->setPlayerResult($this);
    }

    /**
     * @param  ChildPlayermatchresults $playermatchresults The ChildPlayermatchresults object to remove.
     * @return $this|ChildPlayers The current object (for fluent API support)
     */
    public function removePlayermatchresults(ChildPlayermatchresults $playermatchresults)
    {
        if ($this->getPlayermatchresultss()->contains($playermatchresults)) {
            $pos = $this->collPlayermatchresultss->search($playermatchresults);
            $this->collPlayermatchresultss->remove($pos);
            if (null === $this->playermatchresultssScheduledForDeletion) {
                $this->playermatchresultssScheduledForDeletion = clone $this->collPlayermatchresultss;
                $this->playermatchresultssScheduledForDeletion->clear();
            }
            $this->playermatchresultssScheduledForDeletion[]= clone $playermatchresults;
            $playermatchresults->setPlayerResult(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Players is new, it will return
     * an empty collection; or if this Players has previously
     * been saved, it will retrieve related Playermatchresultss from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Players.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildPlayermatchresults[] List of ChildPlayermatchresults objects
     */
    public function getPlayermatchresultssJoinMatchPlayerResult(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildPlayermatchresultsQuery::create(null, $criteria);
        $query->joinWith('MatchPlayerResult', $joinBehavior);

        return $this->getPlayermatchresultss($query, $con);
    }

    /**
     * Clears out the collPlayermatchstatess collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addPlayermatchstatess()
     */
    public function clearPlayermatchstatess()
    {
        $this->collPlayermatchstatess = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collPlayermatchstatess collection loaded partially.
     */
    public function resetPartialPlayermatchstatess($v = true)
    {
        $this->collPlayermatchstatessPartial = $v;
    }

    /**
     * Initializes the collPlayermatchstatess collection.
     *
     * By default this just sets the collPlayermatchstatess collection to an empty array (like clearcollPlayermatchstatess());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPlayermatchstatess($overrideExisting = true)
    {
        if (null !== $this->collPlayermatchstatess && !$overrideExisting) {
            return;
        }
        $this->collPlayermatchstatess = new ObjectCollection();
        $this->collPlayermatchstatess->setModel('\Playermatchstates');
    }

    /**
     * Gets an array of ChildPlayermatchstates objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildPlayers is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildPlayermatchstates[] List of ChildPlayermatchstates objects
     * @throws PropelException
     */
    public function getPlayermatchstatess(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collPlayermatchstatessPartial && !$this->isNew();
        if (null === $this->collPlayermatchstatess || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPlayermatchstatess) {
                // return empty collection
                $this->initPlayermatchstatess();
            } else {
                $collPlayermatchstatess = ChildPlayermatchstatesQuery::create(null, $criteria)
                    ->filterByPlayerMatchState($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collPlayermatchstatessPartial && count($collPlayermatchstatess)) {
                        $this->initPlayermatchstatess(false);

                        foreach ($collPlayermatchstatess as $obj) {
                            if (false == $this->collPlayermatchstatess->contains($obj)) {
                                $this->collPlayermatchstatess->append($obj);
                            }
                        }

                        $this->collPlayermatchstatessPartial = true;
                    }

                    return $collPlayermatchstatess;
                }

                if ($partial && $this->collPlayermatchstatess) {
                    foreach ($this->collPlayermatchstatess as $obj) {
                        if ($obj->isNew()) {
                            $collPlayermatchstatess[] = $obj;
                        }
                    }
                }

                $this->collPlayermatchstatess = $collPlayermatchstatess;
                $this->collPlayermatchstatessPartial = false;
            }
        }

        return $this->collPlayermatchstatess;
    }

    /**
     * Sets a collection of ChildPlayermatchstates objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $playermatchstatess A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildPlayers The current object (for fluent API support)
     */
    public function setPlayermatchstatess(Collection $playermatchstatess, ConnectionInterface $con = null)
    {
        /** @var ChildPlayermatchstates[] $playermatchstatessToDelete */
        $playermatchstatessToDelete = $this->getPlayermatchstatess(new Criteria(), $con)->diff($playermatchstatess);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->playermatchstatessScheduledForDeletion = clone $playermatchstatessToDelete;

        foreach ($playermatchstatessToDelete as $playermatchstatesRemoved) {
            $playermatchstatesRemoved->setPlayerMatchState(null);
        }

        $this->collPlayermatchstatess = null;
        foreach ($playermatchstatess as $playermatchstates) {
            $this->addPlayermatchstates($playermatchstates);
        }

        $this->collPlayermatchstatess = $playermatchstatess;
        $this->collPlayermatchstatessPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Playermatchstates objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Playermatchstates objects.
     * @throws PropelException
     */
    public function countPlayermatchstatess(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collPlayermatchstatessPartial && !$this->isNew();
        if (null === $this->collPlayermatchstatess || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPlayermatchstatess) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPlayermatchstatess());
            }

            $query = ChildPlayermatchstatesQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPlayerMatchState($this)
                ->count($con);
        }

        return count($this->collPlayermatchstatess);
    }

    /**
     * Method called to associate a ChildPlayermatchstates object to this object
     * through the ChildPlayermatchstates foreign key attribute.
     *
     * @param  ChildPlayermatchstates $l ChildPlayermatchstates
     * @return $this|\Players The current object (for fluent API support)
     */
    public function addPlayermatchstates(ChildPlayermatchstates $l)
    {
        if ($this->collPlayermatchstatess === null) {
            $this->initPlayermatchstatess();
            $this->collPlayermatchstatessPartial = true;
        }

        if (!$this->collPlayermatchstatess->contains($l)) {
            $this->doAddPlayermatchstates($l);
        }

        return $this;
    }

    /**
     * @param ChildPlayermatchstates $playermatchstates The ChildPlayermatchstates object to add.
     */
    protected function doAddPlayermatchstates(ChildPlayermatchstates $playermatchstates)
    {
        $this->collPlayermatchstatess[]= $playermatchstates;
        $playermatchstates->setPlayerMatchState($this);
    }

    /**
     * @param  ChildPlayermatchstates $playermatchstates The ChildPlayermatchstates object to remove.
     * @return $this|ChildPlayers The current object (for fluent API support)
     */
    public function removePlayermatchstates(ChildPlayermatchstates $playermatchstates)
    {
        if ($this->getPlayermatchstatess()->contains($playermatchstates)) {
            $pos = $this->collPlayermatchstatess->search($playermatchstates);
            $this->collPlayermatchstatess->remove($pos);
            if (null === $this->playermatchstatessScheduledForDeletion) {
                $this->playermatchstatessScheduledForDeletion = clone $this->collPlayermatchstatess;
                $this->playermatchstatessScheduledForDeletion->clear();
            }
            $this->playermatchstatessScheduledForDeletion[]= clone $playermatchstates;
            $playermatchstates->setPlayerMatchState(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Players is new, it will return
     * an empty collection; or if this Players has previously
     * been saved, it will retrieve related Playermatchstatess from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Players.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildPlayermatchstates[] List of ChildPlayermatchstates objects
     */
    public function getPlayermatchstatessJoinMatchstates(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildPlayermatchstatesQuery::create(null, $criteria);
        $query->joinWith('Matchstates', $joinBehavior);

        return $this->getPlayermatchstatess($query, $con);
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
     * If this ChildPlayers is new, it will return
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
                    ->filterByRankingPlayer($this)
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
     * @return $this|ChildPlayers The current object (for fluent API support)
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
            $playerrankingRemoved->setRankingPlayer(null);
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
                ->filterByRankingPlayer($this)
                ->count($con);
        }

        return count($this->collPlayerrankings);
    }

    /**
     * Method called to associate a ChildPlayerranking object to this object
     * through the ChildPlayerranking foreign key attribute.
     *
     * @param  ChildPlayerranking $l ChildPlayerranking
     * @return $this|\Players The current object (for fluent API support)
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
        $playerranking->setRankingPlayer($this);
    }

    /**
     * @param  ChildPlayerranking $playerranking The ChildPlayerranking object to remove.
     * @return $this|ChildPlayers The current object (for fluent API support)
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
            $playerranking->setRankingPlayer(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Players is new, it will return
     * an empty collection; or if this Players has previously
     * been saved, it will retrieve related Playerrankings from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Players.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildPlayerranking[] List of ChildPlayerranking objects
     */
    public function getPlayerrankingsJoinCompetitionRanking(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildPlayerrankingQuery::create(null, $criteria);
        $query->joinWith('CompetitionRanking', $joinBehavior);

        return $this->getPlayerrankings($query, $con);
    }

    /**
     * Clears out the collVotess collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addVotess()
     */
    public function clearVotess()
    {
        $this->collVotess = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collVotess collection loaded partially.
     */
    public function resetPartialVotess($v = true)
    {
        $this->collVotessPartial = $v;
    }

    /**
     * Initializes the collVotess collection.
     *
     * By default this just sets the collVotess collection to an empty array (like clearcollVotess());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initVotess($overrideExisting = true)
    {
        if (null !== $this->collVotess && !$overrideExisting) {
            return;
        }
        $this->collVotess = new ObjectCollection();
        $this->collVotess->setModel('\Votes');
    }

    /**
     * Gets an array of ChildVotes objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildPlayers is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildVotes[] List of ChildVotes objects
     * @throws PropelException
     */
    public function getVotess(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collVotessPartial && !$this->isNew();
        if (null === $this->collVotess || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collVotess) {
                // return empty collection
                $this->initVotess();
            } else {
                $collVotess = ChildVotesQuery::create(null, $criteria)
                    ->filterByVotePlayer($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collVotessPartial && count($collVotess)) {
                        $this->initVotess(false);

                        foreach ($collVotess as $obj) {
                            if (false == $this->collVotess->contains($obj)) {
                                $this->collVotess->append($obj);
                            }
                        }

                        $this->collVotessPartial = true;
                    }

                    return $collVotess;
                }

                if ($partial && $this->collVotess) {
                    foreach ($this->collVotess as $obj) {
                        if ($obj->isNew()) {
                            $collVotess[] = $obj;
                        }
                    }
                }

                $this->collVotess = $collVotess;
                $this->collVotessPartial = false;
            }
        }

        return $this->collVotess;
    }

    /**
     * Sets a collection of ChildVotes objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $votess A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildPlayers The current object (for fluent API support)
     */
    public function setVotess(Collection $votess, ConnectionInterface $con = null)
    {
        /** @var ChildVotes[] $votessToDelete */
        $votessToDelete = $this->getVotess(new Criteria(), $con)->diff($votess);


        $this->votessScheduledForDeletion = $votessToDelete;

        foreach ($votessToDelete as $votesRemoved) {
            $votesRemoved->setVotePlayer(null);
        }

        $this->collVotess = null;
        foreach ($votess as $votes) {
            $this->addVotes($votes);
        }

        $this->collVotess = $votess;
        $this->collVotessPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Votes objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Votes objects.
     * @throws PropelException
     */
    public function countVotess(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collVotessPartial && !$this->isNew();
        if (null === $this->collVotess || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collVotess) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getVotess());
            }

            $query = ChildVotesQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByVotePlayer($this)
                ->count($con);
        }

        return count($this->collVotess);
    }

    /**
     * Method called to associate a ChildVotes object to this object
     * through the ChildVotes foreign key attribute.
     *
     * @param  ChildVotes $l ChildVotes
     * @return $this|\Players The current object (for fluent API support)
     */
    public function addVotes(ChildVotes $l)
    {
        if ($this->collVotess === null) {
            $this->initVotess();
            $this->collVotessPartial = true;
        }

        if (!$this->collVotess->contains($l)) {
            $this->doAddVotes($l);
        }

        return $this;
    }

    /**
     * @param ChildVotes $votes The ChildVotes object to add.
     */
    protected function doAddVotes(ChildVotes $votes)
    {
        $this->collVotess[]= $votes;
        $votes->setVotePlayer($this);
    }

    /**
     * @param  ChildVotes $votes The ChildVotes object to remove.
     * @return $this|ChildPlayers The current object (for fluent API support)
     */
    public function removeVotes(ChildVotes $votes)
    {
        if ($this->getVotess()->contains($votes)) {
            $pos = $this->collVotess->search($votes);
            $this->collVotess->remove($pos);
            if (null === $this->votessScheduledForDeletion) {
                $this->votessScheduledForDeletion = clone $this->collVotess;
                $this->votessScheduledForDeletion->clear();
            }
            $this->votessScheduledForDeletion[]= clone $votes;
            $votes->setVotePlayer(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Players is new, it will return
     * an empty collection; or if this Players has previously
     * been saved, it will retrieve related Votess from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Players.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildVotes[] List of ChildVotes objects
     */
    public function getVotessJoinVoteMatch(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildVotesQuery::create(null, $criteria);
        $query->joinWith('VoteMatch', $joinBehavior);

        return $this->getVotess($query, $con);
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
            if ($this->collConnecteduserss) {
                foreach ($this->collConnecteduserss as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collForecastss) {
                foreach ($this->collForecastss as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPlayercupmatchessRelatedByPlayerhomekey) {
                foreach ($this->collPlayercupmatchessRelatedByPlayerhomekey as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPlayercupmatchessRelatedByPlayerawaykey) {
                foreach ($this->collPlayercupmatchessRelatedByPlayerawaykey as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPlayercupmatchessRelatedByCuproundkey) {
                foreach ($this->collPlayercupmatchessRelatedByCuproundkey as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPlayerdivisionmatchessRelatedByPlayerhomekey) {
                foreach ($this->collPlayerdivisionmatchessRelatedByPlayerhomekey as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPlayerdivisionmatchessRelatedByPlayerawaykey) {
                foreach ($this->collPlayerdivisionmatchessRelatedByPlayerawaykey as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPlayerdivisionrankings) {
                foreach ($this->collPlayerdivisionrankings as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPlayergrouprankings) {
                foreach ($this->collPlayergrouprankings as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPlayergroupresultss) {
                foreach ($this->collPlayergroupresultss as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPlayergroupstatess) {
                foreach ($this->collPlayergroupstatess as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPlayermatchresultss) {
                foreach ($this->collPlayermatchresultss as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPlayermatchstatess) {
                foreach ($this->collPlayermatchstatess as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPlayerrankings) {
                foreach ($this->collPlayerrankings as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collVotess) {
                foreach ($this->collVotess as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collConnecteduserss = null;
        $this->collForecastss = null;
        $this->collPlayercupmatchessRelatedByPlayerhomekey = null;
        $this->collPlayercupmatchessRelatedByPlayerawaykey = null;
        $this->collPlayercupmatchessRelatedByCuproundkey = null;
        $this->collPlayerdivisionmatchessRelatedByPlayerhomekey = null;
        $this->collPlayerdivisionmatchessRelatedByPlayerawaykey = null;
        $this->collPlayerdivisionrankings = null;
        $this->collPlayergrouprankings = null;
        $this->collPlayergroupresultss = null;
        $this->collPlayergroupstatess = null;
        $this->collPlayermatchresultss = null;
        $this->collPlayermatchstatess = null;
        $this->collPlayerrankings = null;
        $this->collVotess = null;
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
