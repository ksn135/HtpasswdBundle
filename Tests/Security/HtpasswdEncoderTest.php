<?php

/**
 * This file is part of the Ksn135HtpasswdBundle package.
 *
 * (c) Serg N. Kalachev <serg@kalachev.ru>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Ksn135\HtpasswdBundle\Tests\Security;

use Ksn135\HtpasswdBundle\Tests\TestCase;
use Ksn135\HtpasswdBundle\Security\HtpasswdEncoder;

class HtpasswdEncoderTest extends TestCase
{
    public function testConstuction()
    {
        // original password was "secret" for user "test"
        // generated line in .htpasswd is "test:$apr1$JwR3/X6a$s/MRkx5jEm9cmso2bKypV."

        $encoder = new HtpasswdEncoder;
        $encoded =  $encoder->encodePassword('secret', 'JwR3/X6a');

        $this->assertEquals('$apr1$JwR3/X6a$s/MRkx5jEm9cmso2bKypV.', $encoded);
    }
}
