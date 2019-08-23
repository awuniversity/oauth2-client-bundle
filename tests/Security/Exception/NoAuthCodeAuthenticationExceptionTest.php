<?php

/*
 * OAuth2 Client Bundle
 * Copyright (c) AwUniversity <http://awuniversity.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AwU\OAuth2ClientBundle\tests\Security\Exception;

use AwU\OAuth2ClientBundle\Security\Exception\NoAuthCodeAuthenticationException;
use PHPUnit\Framework\TestCase;

/**
 * @author Serghei Luchianenco (s@luchianenco.com)
 */
class NoAuthCodeAuthenticationExceptionTest extends TestCase
{
    public function testException()
    {
        $e = new NoAuthCodeAuthenticationException();

        $this->assertInternalType('string', $e->getMessageKey());
    }
}
