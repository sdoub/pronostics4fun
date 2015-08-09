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
class PlayersEnabled extends Players
{

    /**
     * Constructs a new PlayersEnabled class, setting the IsEnabled column to PlayersTableMap::CLASSKEY_TRUE.
     */
    public function __construct()
    {
        parent::__construct();
        $this->setIsenabled(PlayersTableMap::CLASSKEY_TRUE);
    }

} // PlayersEnabled
