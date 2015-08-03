<?php

namespace Base;

use Map\EventsTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Connection\ConnectionInterface;

/**
 * Skeleton subclass for representing a query for one of the subclasses of the 'events' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class SubstituteQuery extends
{

    /**
     * Returns a new \SubstituteQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return \SubstituteQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof \SubstituteQuery) {
            return $criteria;
        }
        $query = new \SubstituteQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Filters the query to target only Substitute objects.
     */
    public function preSelect(ConnectionInterface $con)
    {
        $this->addUsingAlias(EventsTableMap::COL_EVENTTYPE, EventsTableMap::CLASSKEY_6);
    }

    /**
     * Filters the query to target only Substitute objects.
     */
    public function preUpdate(&$values, ConnectionInterface $con, $forceIndividualSaves = false)
    {
        $this->addUsingAlias(EventsTableMap::COL_EVENTTYPE, EventsTableMap::CLASSKEY_6);
    }

    /**
     * Filters the query to target only Substitute objects.
     */
    public function preDelete(ConnectionInterface $con)
    {
        $this->addUsingAlias(EventsTableMap::COL_EVENTTYPE, EventsTableMap::CLASSKEY_6);
    }

    /**
     * Issue a DELETE query based on the current ModelCriteria deleting all rows in the table
     * Having the Substitute class.
     * This method is called by ModelCriteria::deleteAll() inside a transaction
     *
     * @param ConnectionInterface $con a connection object
     *
     * @return integer the number of deleted rows
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        // condition on class key is already added in preDelete()
        return parent::delete($con);
    }

} // SubstituteQuery
