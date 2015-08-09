<?php

use Map\PlayersTableMap;


/**
 * Skeleton subclass for representing a row from one of the subclasses of the 'players' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class PlayersDisabled extends Players
{

    /**
     * Constructs a new PlayersDisabled class, setting the IsEnabled column to PlayersTableMap::CLASSKEY_FALSE.
     */
    public function __construct()
    {
        parent::__construct();
        $this->setIsenabled(PlayersTableMap::CLASSKEY_FALSE);
    }

} // PlayersDisabled
