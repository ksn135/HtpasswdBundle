<?php

/**
 * This file is part of the Ksn135HtpasswdBundle package.
 *
 * (c) Serg N. Kalachev <serg@kalachev.ru>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Ksn135\HtpasswdBundle\Security\User;

use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;

class HtpasswdUser implements UserInterface, EquatableInterface
{
    private $username;
    private $password;
    private $salt;
    private $roles;

    public function __construct($username, $hash, array $roles)
    {
        $this->username = $username;
        $this->roles = $roles;

        if( strpos($hash, "$") !== false ){
            $array = explode('$',$hash);

            $this->salt = $array[2];
            $this->password = $hash;
        }
        elseif( "{SHA}" === substr($hash, 0, 5) ){
            $this->salt = null;
            $this->password = $hash;
        }
        else{
            throw new BadCredentialsException("unable to encode password");
        }


    }

    public function __toString()
    {
        return $this->username;
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getSalt()
    {
        return $this->salt;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function eraseCredentials()
    {
    }

    public function isEqualTo(UserInterface $user)
    {
        if (!$user instanceof HtpasswdUser) {
            return false;
        }

        if ($this->password !== $user->getPassword()) {
            return false;
        }

        if ($this->getSalt() !== $user->getSalt()) {
            return false;
        }

        if ($this->username !== $user->getUsername()) {
            return false;
        }

        return true;
    }
}