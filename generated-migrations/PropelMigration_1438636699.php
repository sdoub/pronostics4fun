<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1438636699.
 * Generated on 2015-08-03 17:18:19 by cabox
 */
class PropelMigration_1438636699
{
    public $comment = '';

    public function preUp($manager)
    {
        // add the pre-migration code here
    }

    public function postUp($manager)
    {
        // add the post-migration code here
    }

    public function preDown($manager)
    {
        // add the pre-migration code here
    }

    public function postDown($manager)
    {
        // add the post-migration code here
    }

    /**
     * Get the SQL statements for the Up migration
     *
     * @return array list of the SQL strings to execute for the Up migration
     *               the keys being the datasources
     */
    public function getUpSQL()
    {
        return array (
  'default' => '
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

CREATE INDEX `connectedusers_fi_d784ed` ON `connectedusers` (`PlayerKey`);

ALTER TABLE `events`

  CHANGE `Half` `Half` TINYINT NOT NULL;

CREATE INDEX `events_fi_97ca7e` ON `events` (`TeamPlayerKey`);

CREATE INDEX `lineups_fi_33a421` ON `lineups` (`TeamKey`);

CREATE INDEX `lineups_fi_97ca7e` ON `lineups` (`TeamPlayerKey`);

CREATE INDEX `matches_fi_eaaa4b` ON `matches` (`TeamHomeKey`);

CREATE INDEX `matches_fi_04ba00` ON `matches` (`TeamAwayKey`);

CREATE INDEX `matchstates_fi_eda3ac` ON `matchstates` (`EventKey`);

CREATE INDEX `news_fi_351b16` ON `news` (`CompetitionKey`);

CREATE INDEX `playercupmatches_fi_081423` ON `playercupmatches` (`PlayerHomeKey`);

CREATE INDEX `playercupmatches_fi_8b4deb` ON `playercupmatches` (`PlayerAwayKey`);

CREATE INDEX `playercupmatches_fi_1d848c` ON `playercupmatches` (`CupRoundKey`);

CREATE INDEX `playercupmatches_fi_0d5692` ON `playercupmatches` (`GroupKey`);

CREATE INDEX `playercupmatches_fi_1a4951` ON `playercupmatches` (`SeasonKey`);

CREATE INDEX `playerdivisionmatches_fi_8b4deb` ON `playerdivisionmatches` (`PlayerAwayKey`);

CREATE INDEX `playerdivisionmatches_fi_545d0b` ON `playerdivisionmatches` (`DivisionKey`);

CREATE INDEX `playerdivisionmatches_fi_0d5692` ON `playerdivisionmatches` (`GroupKey`);

CREATE INDEX `playerdivisionmatches_fi_1a4951` ON `playerdivisionmatches` (`SeasonKey`);

CREATE INDEX `playerdivisionranking_fi_1a4951` ON `playerdivisionranking` (`SeasonKey`);

CREATE INDEX `playerdivisionranking_fi_5dff2d` ON `playerdivisionranking` (`DivisionKey`);

CREATE INDEX `playergroupresults_fi_0d5692` ON `playergroupresults` (`GroupKey`);

CREATE INDEX `playergroupstates_fi_0d5692` ON `playergroupstates` (`GroupKey`);

CREATE INDEX `playermatchstates_fi_209873` ON `playermatchstates` (`MatchStateKey`);

CREATE INDEX `votes_fi_d784ed` ON `votes` (`PlayerKey`);

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

    /**
     * Get the SQL statements for the Down migration
     *
     * @return array list of the SQL strings to execute for the Down migration
     *               the keys being the datasources
     */
    public function getDownSQL()
    {
        return array (
  'default' => '
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

DROP INDEX `connectedusers_fi_d784ed` ON `connectedusers`;

DROP INDEX `events_fi_97ca7e` ON `events`;

ALTER TABLE `events`

  CHANGE `Half` `Half` INTEGER NOT NULL;

DROP INDEX `lineups_fi_33a421` ON `lineups`;

DROP INDEX `lineups_fi_97ca7e` ON `lineups`;

DROP INDEX `matches_fi_eaaa4b` ON `matches`;

DROP INDEX `matches_fi_04ba00` ON `matches`;

DROP INDEX `matchstates_fi_eda3ac` ON `matchstates`;

DROP INDEX `news_fi_351b16` ON `news`;

DROP INDEX `playercupmatches_fi_081423` ON `playercupmatches`;

DROP INDEX `playercupmatches_fi_8b4deb` ON `playercupmatches`;

DROP INDEX `playercupmatches_fi_1d848c` ON `playercupmatches`;

DROP INDEX `playercupmatches_fi_0d5692` ON `playercupmatches`;

DROP INDEX `playercupmatches_fi_1a4951` ON `playercupmatches`;

DROP INDEX `playerdivisionmatches_fi_8b4deb` ON `playerdivisionmatches`;

DROP INDEX `playerdivisionmatches_fi_545d0b` ON `playerdivisionmatches`;

DROP INDEX `playerdivisionmatches_fi_0d5692` ON `playerdivisionmatches`;

DROP INDEX `playerdivisionmatches_fi_1a4951` ON `playerdivisionmatches`;

DROP INDEX `playerdivisionranking_fi_1a4951` ON `playerdivisionranking`;

DROP INDEX `playerdivisionranking_fi_5dff2d` ON `playerdivisionranking`;

DROP INDEX `playergroupresults_fi_0d5692` ON `playergroupresults`;

DROP INDEX `playergroupstates_fi_0d5692` ON `playergroupstates`;

DROP INDEX `playermatchstates_fi_209873` ON `playermatchstates`;

DROP INDEX `votes_fi_d784ed` ON `votes`;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}