<?php  namespace TechWilk\Rota;

use TechWilk\Rota\Base\Event as BaseEvent;

/**
 * Skeleton subclass for representing a row from the 'cr_events' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
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
}
