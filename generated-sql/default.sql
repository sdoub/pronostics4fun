
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- Queries
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `Queries`;

CREATE TABLE `Queries`
(
    `Name` VARCHAR(100) NOT NULL,
    `Query` TEXT NOT NULL,
    UNIQUE INDEX `Name` (`Name`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- competitions
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `competitions`;

CREATE TABLE `competitions`
(
    `PrimaryKey` INTEGER NOT NULL AUTO_INCREMENT,
    `Name` VARCHAR(30) NOT NULL,
    PRIMARY KEY (`PrimaryKey`),
    UNIQUE INDEX `Name` (`Name`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- connectedusers
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `connectedusers`;

CREATE TABLE `connectedusers`
(
    `VisiteDate` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `UserUniqueId` VARCHAR(100) NOT NULL,
    `PlayerKey` INTEGER NOT NULL,
    PRIMARY KEY (`UserUniqueId`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- cronjobs
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `cronjobs`;

CREATE TABLE `cronjobs`
(
    `JobName` VARCHAR(30) NOT NULL,
    `LastExecution` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `LastStatus` TINYINT(1) DEFAULT 0,
    `LastExecutionInformation` TEXT,
    UNIQUE INDEX `JobName` (`JobName`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- cuprounds
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `cuprounds`;

CREATE TABLE `cuprounds`
(
    `PrimaryKey` INTEGER NOT NULL AUTO_INCREMENT,
    `Description` VARCHAR(50) NOT NULL,
    `Code` VARCHAR(5) NOT NULL,
    `NextRoundKey` INTEGER,
    `PreviousRoundKey` INTEGER,
    PRIMARY KEY (`PrimaryKey`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- divisions
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `divisions`;

CREATE TABLE `divisions`
(
    `PrimaryKey` INTEGER NOT NULL AUTO_INCREMENT,
    `Description` VARCHAR(50) NOT NULL,
    `Code` VARCHAR(3) NOT NULL,
    `Order` TINYINT(2) DEFAULT 1 NOT NULL,
    PRIMARY KEY (`PrimaryKey`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- events
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `events`;

CREATE TABLE `events`
(
    `PrimaryKey` INTEGER NOT NULL AUTO_INCREMENT,
    `ResultKey` INTEGER NOT NULL,
    `TeamPlayerKey` INTEGER NOT NULL,
    `EventTime` INTEGER NOT NULL,
    `EventType` INTEGER NOT NULL,
    `Half` INTEGER NOT NULL,
    `TeamKey` INTEGER NOT NULL,
    PRIMARY KEY (`PrimaryKey`),
    UNIQUE INDEX `ResultKey` (`ResultKey`, `EventTime`, `EventType`, `TeamKey`, `TeamPlayerKey`),
    INDEX `ResultKey_2` (`ResultKey`),
    INDEX `TeamKey` (`TeamKey`),
    INDEX `Half` (`Half`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- forecasts
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `forecasts`;

CREATE TABLE `forecasts`
(
    `PrimaryKey` INTEGER NOT NULL AUTO_INCREMENT,
    `MatchKey` INTEGER NOT NULL,
    `PlayerKey` INTEGER NOT NULL,
    `TeamHomeScore` INTEGER NOT NULL,
    `TeamAwayScore` INTEGER NOT NULL,
    `ForecastDate` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`PrimaryKey`),
    UNIQUE INDEX `MatchKey` (`MatchKey`, `PlayerKey`),
    INDEX `PlayerKey` (`PlayerKey`),
    INDEX `MatchKey_2` (`MatchKey`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- groups
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `groups`;

CREATE TABLE `groups`
(
    `PrimaryKey` INTEGER NOT NULL AUTO_INCREMENT,
    `Description` VARCHAR(30) NOT NULL,
    `Code` VARCHAR(10) NOT NULL,
    `CompetitionKey` INTEGER NOT NULL,
    `BeginDate` DATETIME,
    `EndDate` DATETIME,
    `Status` TINYINT(1) NOT NULL,
    `IsCompleted` TINYINT(1) DEFAULT 0 NOT NULL,
    `DayKey` INTEGER,
    PRIMARY KEY (`PrimaryKey`),
    UNIQUE INDEX `Code` (`Code`, `CompetitionKey`),
    UNIQUE INDEX `Description` (`Description`, `CompetitionKey`),
    INDEX `CompetitionKey` (`CompetitionKey`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- lineups
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `lineups`;

CREATE TABLE `lineups`
(
    `MatchKey` INTEGER NOT NULL,
    `TeamKey` INTEGER NOT NULL,
    `TeamPlayerKey` INTEGER NOT NULL,
    `IsSubstitute` TINYINT(1) DEFAULT 0 NOT NULL,
    `TimeIn` INTEGER,
    `TeamPlayerReplacedKey` INTEGER,
    PRIMARY KEY (`MatchKey`,`TeamKey`,`TeamPlayerKey`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- matches
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `matches`;

CREATE TABLE `matches`
(
    `PrimaryKey` INTEGER NOT NULL AUTO_INCREMENT,
    `GroupKey` INTEGER NOT NULL,
    `TeamHomeKey` INTEGER NOT NULL,
    `TeamAwayKey` INTEGER NOT NULL,
    `ScheduleDate` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `IsBonusMatch` TINYINT(1) DEFAULT 0 NOT NULL,
    `Status` INTEGER DEFAULT 0 NOT NULL,
    `ExternalKey` INTEGER,
    PRIMARY KEY (`PrimaryKey`),
    UNIQUE INDEX `GroupKey` (`GroupKey`, `TeamHomeKey`, `TeamAwayKey`),
    INDEX `ScheduleDate` (`ScheduleDate`),
    INDEX `GroupKey_IS` (`GroupKey`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- matchstates
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `matchstates`;

CREATE TABLE `matchstates`
(
    `PrimaryKey` INTEGER NOT NULL AUTO_INCREMENT,
    `MatchKey` INTEGER NOT NULL,
    `StateDate` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `EventKey` INTEGER NOT NULL,
    `TeamHomeScore` INTEGER NOT NULL,
    `TeamAwayScore` INTEGER NOT NULL,
    PRIMARY KEY (`PrimaryKey`),
    UNIQUE INDEX `MatchKey` (`MatchKey`, `StateDate`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- news
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `news`;

CREATE TABLE `news`
(
    `PrimaryKey` INTEGER NOT NULL AUTO_INCREMENT,
    `CompetitionKey` INTEGER NOT NULL,
    `Information` VARCHAR(4000) NOT NULL,
    `InfoDate` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `InfoType` TINYINT(1) DEFAULT 1 NOT NULL,
    PRIMARY KEY (`PrimaryKey`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- playercupmatches
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `playercupmatches`;

CREATE TABLE `playercupmatches`
(
    `PrimaryKey` INTEGER NOT NULL AUTO_INCREMENT,
    `PlayerHomeKey` INTEGER NOT NULL,
    `PlayerAwayKey` INTEGER NOT NULL,
    `CupRoundKey` INTEGER NOT NULL,
    `SeasonKey` INTEGER NOT NULL,
    `GroupKey` INTEGER NOT NULL,
    `HomeScore` TINYINT(3),
    `AwayScore` TINYINT(3),
    `ScheduleDate` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `ResultDate` DATE,
    PRIMARY KEY (`PrimaryKey`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- playerdivisionmatches
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `playerdivisionmatches`;

CREATE TABLE `playerdivisionmatches`
(
    `PrimaryKey` INTEGER NOT NULL AUTO_INCREMENT,
    `PlayerHomeKey` INTEGER NOT NULL,
    `PlayerAwayKey` INTEGER NOT NULL,
    `SeasonKey` INTEGER NOT NULL,
    `DivisionKey` INTEGER NOT NULL,
    `GroupKey` INTEGER NOT NULL,
    `HomeScore` TINYINT(3),
    `AwayScore` TINYINT(3),
    `ScheduleDate` DATETIME,
    `ResultDate` DATE,
    PRIMARY KEY (`PrimaryKey`),
    UNIQUE INDEX `PlayerHomeKey` (`PlayerHomeKey`, `PlayerAwayKey`, `SeasonKey`, `DivisionKey`, `GroupKey`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- playerdivisionranking
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `playerdivisionranking`;

CREATE TABLE `playerdivisionranking`
(
    `PlayerKey` INTEGER NOT NULL,
    `SeasonKey` INTEGER NOT NULL,
    `DivisionKey` INTEGER NOT NULL,
    `Score` TINYINT(2) NOT NULL,
    `RankDate` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `Rank` TINYINT(2) NOT NULL,
    `Won` TINYINT(2) NOT NULL,
    `Drawn` TINYINT(2) NOT NULL,
    `Lost` TINYINT(2) NOT NULL,
    `PointsFor` SMALLINT(4) NOT NULL,
    `PointsAgainst` SMALLINT(4) NOT NULL,
    `PointsDifference` SMALLINT(4) NOT NULL,
    PRIMARY KEY (`PlayerKey`,`SeasonKey`,`DivisionKey`,`RankDate`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- playergroupranking
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `playergroupranking`;

CREATE TABLE `playergroupranking`
(
    `PlayerKey` INTEGER NOT NULL,
    `GroupKey` INTEGER NOT NULL,
    `RankDate` DATE NOT NULL,
    `Rank` INTEGER NOT NULL,
    PRIMARY KEY (`PlayerKey`,`GroupKey`,`RankDate`),
    INDEX `GroupKey` (`GroupKey`),
    INDEX `RankDate` (`RankDate`),
    INDEX `PlayerKey` (`PlayerKey`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- playergroupresults
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `playergroupresults`;

CREATE TABLE `playergroupresults`
(
    `PlayerKey` INTEGER NOT NULL,
    `GroupKey` INTEGER NOT NULL,
    `Score` INTEGER,
    PRIMARY KEY (`PlayerKey`,`GroupKey`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- playergroupstates
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `playergroupstates`;

CREATE TABLE `playergroupstates`
(
    `PlayerKey` INTEGER NOT NULL,
    `GroupKey` INTEGER NOT NULL,
    `StateDate` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `Rank` INTEGER DEFAULT 1 NOT NULL,
    `Score` INTEGER NOT NULL,
    `Bonus` INTEGER DEFAULT 0 NOT NULL,
    PRIMARY KEY (`PlayerKey`,`GroupKey`,`StateDate`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- playermatchresults
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `playermatchresults`;

CREATE TABLE `playermatchresults`
(
    `PlayerKey` INTEGER NOT NULL,
    `MatchKey` INTEGER NOT NULL,
    `Score` INTEGER,
    `IsPerfect` TINYINT(1) DEFAULT 0 NOT NULL,
    PRIMARY KEY (`PlayerKey`,`MatchKey`),
    INDEX `MatchKey` (`MatchKey`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- playermatchstates
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `playermatchstates`;

CREATE TABLE `playermatchstates`
(
    `PlayerKey` INTEGER NOT NULL,
    `MatchStateKey` INTEGER NOT NULL,
    `Score` INTEGER NOT NULL,
    PRIMARY KEY (`PlayerKey`,`MatchStateKey`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- playerranking
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `playerranking`;

CREATE TABLE `playerranking`
(
    `CompetitionKey` INTEGER NOT NULL,
    `PlayerKey` INTEGER NOT NULL,
    `RankDate` DATE NOT NULL,
    `Rank` INTEGER NOT NULL,
    PRIMARY KEY (`CompetitionKey`,`PlayerKey`,`RankDate`),
    INDEX `PlayerKey` (`PlayerKey`),
    INDEX `RankDate` (`RankDate`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- players
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `players`;

CREATE TABLE `players`
(
    `PrimaryKey` INTEGER NOT NULL AUTO_INCREMENT,
    `NickName` VARCHAR(20) NOT NULL,
    `FirstName` VARCHAR(50) NOT NULL,
    `LastName` VARCHAR(50) NOT NULL,
    `EmailAddress` VARCHAR(80) NOT NULL,
    `Password` VARCHAR(100) NOT NULL,
    `IsAdministrator` TINYINT(1) DEFAULT 0 NOT NULL,
    `ActivationKey` VARCHAR(255),
    `IsEnabled` TINYINT(1) DEFAULT 1 NOT NULL,
    `LastConnection` DATE NOT NULL,
    `Token` VARCHAR(50),
    `AvatarName` VARCHAR(20),
    `CreationDate` DATE NOT NULL,
    `IsCalendarDefaultView` TINYINT(1) DEFAULT 0 NOT NULL,
    `ReceiveAlert` TINYINT(1) DEFAULT 1 NOT NULL,
    `ReceiveNewletter` TINYINT(1) DEFAULT 1 NOT NULL,
    `ReceiveResult` TINYINT(1) DEFAULT 1 NOT NULL,
    `IsReminderEmailSent` TINYINT(1) DEFAULT 0 NOT NULL,
    `IsResultEmailSent` TINYINT(1) DEFAULT 0 NOT NULL,
    PRIMARY KEY (`PrimaryKey`),
    UNIQUE INDEX `NickName` (`NickName`),
    UNIQUE INDEX `EmailAddress` (`EmailAddress`),
    INDEX `Token` (`Token`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- results
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `results`;

CREATE TABLE `results`
(
    `PrimaryKey` INTEGER NOT NULL AUTO_INCREMENT,
    `MatchKey` INTEGER NOT NULL,
    `LiveStatus` INTEGER NOT NULL,
    `ActualTime` INTEGER NOT NULL,
    `ResultDate` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`PrimaryKey`),
    UNIQUE INDEX `MatchKey` (`MatchKey`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- seasons
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `seasons`;

CREATE TABLE `seasons`
(
    `PrimaryKey` INTEGER NOT NULL AUTO_INCREMENT,
    `Description` VARCHAR(50) NOT NULL,
    `Code` VARCHAR(3) NOT NULL,
    `Order` TINYINT(2) NOT NULL,
    `CompetitionKey` INTEGER NOT NULL,
    PRIMARY KEY (`PrimaryKey`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- surveys
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `surveys`;

CREATE TABLE `surveys`
(
    `PrimaryKey` INTEGER NOT NULL AUTO_INCREMENT,
    `Question` VARCHAR(2000) NOT NULL,
    `Answer1` VARCHAR(200) NOT NULL,
    `Answer2` VARCHAR(200) NOT NULL,
    `Answer3` VARCHAR(200),
    `Answer4` VARCHAR(200),
    `Score1` INTEGER(3) DEFAULT 0 NOT NULL,
    `Score2` INTEGER(3) DEFAULT 0 NOT NULL,
    `Score3` INTEGER(3) DEFAULT 0 NOT NULL,
    `Score4` INTEGER(3) DEFAULT 0 NOT NULL,
    `Participants` VARCHAR(2000),
    `StartDate` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `EndDate` DATETIME DEFAULT '0000-00-00 00:00:00' NOT NULL,
    PRIMARY KEY (`PrimaryKey`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- teamplayers
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `teamplayers`;

CREATE TABLE `teamplayers`
(
    `PrimaryKey` INTEGER NOT NULL AUTO_INCREMENT,
    `FullName` VARCHAR(255) NOT NULL,
    PRIMARY KEY (`PrimaryKey`),
    UNIQUE INDEX `FullName` (`FullName`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- teams
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `teams`;

CREATE TABLE `teams`
(
    `PrimaryKey` INTEGER NOT NULL AUTO_INCREMENT,
    `Name` VARCHAR(30) NOT NULL,
    `Code` VARCHAR(10) NOT NULL,
    PRIMARY KEY (`PrimaryKey`),
    UNIQUE INDEX `Name` (`Name`),
    UNIQUE INDEX `Code` (`Code`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- votes
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `votes`;

CREATE TABLE `votes`
(
    `PrimaryKey` INTEGER NOT NULL AUTO_INCREMENT,
    `MatchKey` INTEGER NOT NULL,
    `PlayerKey` INTEGER NOT NULL,
    `Value` TINYINT(1) NOT NULL,
    `VoteDate` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`PrimaryKey`),
    UNIQUE INDEX `MatchKey` (`MatchKey`, `PlayerKey`)
) ENGINE=MyISAM;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
