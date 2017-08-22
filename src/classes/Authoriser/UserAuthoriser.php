<?php

namespace TechWilk\Rota\Authoriser;

use TechWilk\Rota\AuthoriserInterface;
use TechWilk\Rota\User;
use TechWilk\Rota\GroupQuery;

class UserAuthoriser implements AuthoriserInterface
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function readableBy(User $user)
    {
        // it's me!
        if ($this->user === $user) {
            return true;
        }
        // we're a superadmin
        if ($user->isAdmin()) {
            return true;
        }
        // we're in the same group
        $myGroups = GroupQuery::create()
            ->useRoleQuery()
                ->useUserRoleQuery()
                    ->filterByUser($user)
                ->endUse()
            ->endUse()
            ->find();
        $sameGroups = GroupQuery::create()
            ->useRoleQuery()
                ->useUserRoleQuery()
                    ->filterByUser($this->user)
                ->endUse()
                ->filterByGroup($myGroups)
            ->endUse()
            ->count();
        if ($sameGroups > 0) {
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
        // it's me!
        if ($this->user === $user) {
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
        // it's me!
        if ($this->user === $user) {
            return true;
        }
        // we're a superadmin
        if ($user->isAdmin()) {
            return true;
        }
        return false;
    }
}
