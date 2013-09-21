<?php

/**
 * This file is part of the Ksn135HtpasswdBundle package.
 *
 * (c) Serg N. Kalachev <serg@kalachev.ru>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Ksn135\HtpasswdBundle\Tests\Security\User;

use Ksn135\HtpasswdBundle\Tests\TestCase;
use Ksn135\HtpasswdBundle\Security\User\HtpasswdUser;

class HtpasswdUserTest extends TestCase
{
    public function testConstuction()
    {
        // original password was "secret" for user "test"
        $line_from_htpasswd_file = 'test:$apr1$JwR3/X6a$s/MRkx5jEm9cmso2bKypV.';
        $array = explode(':', $line_from_htpasswd_file );
        $username = $array[0]; $hash = chop($array[1]);
        $roles = array( 'ROLE_USER' );

        $user = new HtpasswdUser($username, $hash, $roles);

        $this->assertEquals($username, $user->__toString());
        $this->assertEquals($username, $user->getUsername());
        $this->assertEquals($roles, $user->getRoles());
        $this->assertEquals($hash, $user->getPassword());

        $arr = explode('$',$hash); $salt = $arr[2];
        $this->assertEquals($salt, $user->getSalt());
    }
}
