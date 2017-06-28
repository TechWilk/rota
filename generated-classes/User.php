<?php

use Base\User as BaseUser;
use Map\UserTableMap;

/**
 * Skeleton subclass for representing a row from the 'cr_users' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class User extends BaseUser
{
    /**
  * Set the value of [password] column.
  *
  * @param string $v new value
  * @return $this|\User The current object (for fluent API support)
  */
  public function setPassword($v)
  {
      if ($v !== null) {
          $v = (string) $v;
      }

      if (!password_verify($v, $this->password)) {
          $bcrypt_options = [
        'cost' => 12,
      ];
          $this->password = password_hash($v, PASSWORD_BCRYPT, $bcrypt_options);
          ;
          $this->modifiedColumns[UserTableMap::COL_PASSWORD] = true;
      }

      return $this;
  } // setPassword()


  /**
  * Check a plain text password against the value of [password] column.
  *
  * @param string $v plain text password
  * @return $this|\User The current object (for fluent API support)
  */
  public function checkPassword($v)
  {
      if ($v !== null) {
          $v = (string) $v;
      } else {
          return false;
      }

      return password_verify($v, $this->password);
  } // checkPassword()


  /**
  * Get the [firstname] and [lastname] column value concatenated with a space.
  *
  * @return string
  */
  public function getName()
  {
      return $this->firstname . ' ' . $this->lastname;
  }

  /**
  * Get the URL for the user's profile image.
  *
  * @param string $size either 'small' or 'large'
  * @return string
  */
  public function getProfileImage($size)
  {
      $socialAuth = $this->getSocialAuth();

      if (isset($socialAuth)) {
          switch ($size) {
        case 'small': // 50px x 50px
          return '//graph.facebook.com/' . $socialAuth->getSocialId() . '/picture?type=square';
          break;
        case 'large': // 200px x 200px
          return '//graph.facebook.com/' . $socialAuth->getSocialId() . '/picture?type=large';
          break;
        default:
          return '//graph.facebook.com/' . $socialAuth->getSocialId() . '/picture';
          break;
      }
      } else {
          switch ($size) {
        case 'small': // 50px x 50px
          return '//www.gravatar.com/avatar/' . md5(strtolower(trim($this->email))) . '?s=50&d=mm';
          break;
        case 'large': // 200px x 200px
          return '//www.gravatar.com/avatar/' . md5(strtolower(trim($this->email)))  . '?s=200&d=mm';
          break;
        default:
          return '//www.gravatar.com/avatar/' . md5(strtolower(trim($this->email))) . '?s=50&d=mm';
          break;
      }
      }
  }

  /**
  * Get array of roles currently assigned to the user.
  *
  * @return array of Role() objects
  */
  public function getCurrentRoles()
  {
      $userRoles = $this->getUserRoles();

      $roles = [];
      foreach ($userRoles as $userRole) {
          $roles[] = $userRole->getRole();
      }

      return $roles;
  }

  /**
  * Get object of upcoming events currently assigned to the user.
  *
  * @return array of Event() objects
  */
  public function getUpcomingEvents()
  {
      return EventQuery::create()->filterByDate(['min' => new DateTime()])->useEventPersonQuery()->useUserRoleQuery()->filterByUser($this)->endUse()->endUse()->distinct()->find();
  }

  /**
  * Get object of upcoming events currently assigned to the user.
  *
  * @return array of Event() objects
  */
  public function getRolesInEvent(Event $event)
  {
      return RoleQuery::create()->useUserRoleQuery()->filterByUser($this)->useEventPersonQuery()->filterByEvent($event)->endUse()->endUse()->orderByName()->find();
  }

    public function getCurrentNotifications()
    {
        return NotificationQuery::create()->filterByUser($this)->filterByDismissed(false)->filterByArchived(false)->orderByTimestamp('desc')->find();
    }

    public function getUnreadNotifications()
    {
        return NotificationQuery::create()->filterBySeen(false)->filterByUser($this)->filterByDismissed(false)->filterByArchived(false)->orderByTimestamp('desc')->find();
    }
}
