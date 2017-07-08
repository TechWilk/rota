<?php  namespace TechWilk\Rota;

use TechWilk\Rota\Base\UserRole as BaseUserRole;

/**
 * Skeleton subclass for representing a row from the 'cr_userRoles' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class UserRole extends BaseUserRole
{
    /**
    * Determine if the user is marked as unavailable for an event.
    *
    * @return bool if user is unavailable
    */
    public function isUnavailableForEvent(Event $event)
    {
        return UnavailableQuery::create()
            ->filterByUser($this->getUser())
            ->filterByEvent($event)
            ->count() > 0;
    }
}
