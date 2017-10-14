<?php
namespace TechWilk\Rota;

use TechWilk\Rota\Base\Group as BaseGroup;

/**
 * Skeleton subclass for representing a row from the 'cr_groups' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class Group extends BaseGroup
{
    public function getLastEvent()
    {
        return EventQuery::create()
            ->useEventPersonQuery()
              ->useUserRoleQuery()
                ->useRoleQuery()
                  ->filterByGroup($this)
                ->endUse()
              ->endUse()
            ->endUse()
            ->orderByDate('desc')
            ->findOne();
    }
}
