<?php

namespace TechWilk\Rota\Authoriser;

use TechWilk\Rota\AuthoriserInterface;
use TechWilk\Rota\Event;
use TechWilk\Rota\EventPersonQuery;
use TechWilk\Rota\User;

class EventAuthoriser implements AuthoriserInterface
{
    private $event;

    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    public function readableBy(User $user)
    {
        // created by me
        if ($this->event->getCreatedBy() === $user) {
            return true;
        }
        // we're a superadmin
        if ($user->isAdmin()) {
            return true;
        }
        // we're involved in the event
        $involvementCount = EventPersonQuery::create()
            ->useUserRoleQuery()
                ->filterByUser($user)
            ->endUse()
            ->filterByEvent($this->event)
            ->count();
        if ($involvementCount > 0) {
            return true;
        }

        return false;
    }

    public static function createableBy(User $user)
    {
        // we're a superadmin
        if ($user->isAdmin()) {
            return true;
        }

        return false;
    }

    public function updatableBy(User $user)
    {
        // created by me
        if ($this->event->getCreatedBy() === $user) {
            return true;
        }
        // we're a superadmin
        if ($user->isAdmin()) {
            return true;
        }

        return false;
    }

    public function deteteableBy(User $user)
    {
        // created by me
        if ($this->event->getCreatedBy() === $user) {
            return true;
        }
        // we're a superadmin
        if ($user->isAdmin()) {
            return true;
        }

        return false;
    }
}
