<?php

namespace TechWilk\Rota;

use TechWilk\Rota\Authoriser\EventAuthoriser;
use TechWilk\Rota\Base\Event as BaseEvent;

/**
 * Skeleton subclass for representing a row from the 'cr_events' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class Event extends BaseEvent
{
    /**
     * Get array of userroles currently assigned to the event.
     *
     * @return array of UserRole() objects
     */
    public function getCurrentUserRoles()
    {
        $eventPeople = $this->getEventPeople();

        $userRoles = [];
        foreach ($eventPeople as $eventPerson) {
            $userRoles[] = $eventPerson->getUserRole();
        }

        return $userRoles;
    }

    public function authoriser()
    {
        return new EventAuthoriser($this);
    }

    /**
     * Get the [createdby] column value.
     *
     * @return User
     */
    public function getCreatedBy()
    {
        return UserQuery::create()->findPk($this->createdby);
    }

    /**
     * Set the value of [createdby] column.
     *
     * @param User|int $v new value
     * @return $this|\TechWilk\Rota\Event The current object (for fluent API support)
     */
    public function setCreatedBy($v)
    {
        if (is_a('\TechWilk\Rota\User', $v)) {
            $v = $v->getId();
        }

        return parent::setCreatedBy($v);
    } // setCreatedBy()
}
