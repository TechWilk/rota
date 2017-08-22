<?php

namespace TechWilk\Rota;

interface AuthoriserInterface
{
    /**
     * @param \TechWilk\Rota\User $user
     *
     * @return bool If user can read object
     */
    public function readableBy(User $user);

    /**
     * @param \TechWilk\Rota\User $user
     *
     * @return bool If user can create object
     */
    public static function createableBy(User $user);

    /**
     * @param \TechWilk\Rota\User $user
     *
     * @return bool If user can update object
     */
    public function updatableBy(User $user);

    /**
     * @param \TechWilk\Rota\User $user
     *
     * @return bool If user can delete object
     */
    public function deteteableBy(User $user);
}
