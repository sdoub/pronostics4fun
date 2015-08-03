<?php

use \Event;
use Map\EventsTableMap;


/**
 * Skeleton subclass for representing a row from one of the subclasses of the 'events' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class Goal extends Event
{

    /**
     * Constructs a new Goal class, setting the EventType column to EventsTableMap::CLASSKEY_1.
     */
    public function __construct()
    {
        parent::__construct();
        $this->setEventtype(EventsTableMap::CLASSKEY_1);
    }

} // Goal
