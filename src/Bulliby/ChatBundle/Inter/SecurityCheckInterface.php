<?php

namespace Bulliby\ChatBundle\Inter;

interface SecurityCheckInterface
{
    /**
     * Verify that the token and UniqueId match a user in DB
     *
     * @param $token The token given by the client
     * @param $id An unique Id who permit isolate an user in DB
     */
   public function TokenIdCheck($token, $id);
}
