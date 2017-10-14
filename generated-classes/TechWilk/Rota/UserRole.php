<?php

namespace TechWilk\Rota;

use TechWilk\Rota\Base\UserRole as BaseUserRole;

/**
 * Skeleton subclass for representing a row from the 'userRoles' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class UserRole extends BaseUserRole
{
    /**
     * Determine if the user is marked as available for an event.
     *
     * @return bool if user is available
     */
    public function isAvailableForEvent(Event $event)
    {
        return $this->getUser()->isAvailableForEvent($event);
    }

    /**
     * Fetch Availability object for the event.
     *
     * @return \TechWilk\Rota\Availability
     */
    public function getAvailabilityForEvent(Event $event)
    {
        return $this->getUser()->getAvailabilityForEvent($event);
    }
}
