<?php

/*
 * OAuth2 Client Bundle
 * Copyright (c) AwUniversity <http://awuniversity.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AwU\OAuth2ClientBundle\Security\Helper;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;

trait SaveAuthFailureMessage
{
    protected function saveAuthenticationErrorToSession(Request $request, AuthenticationException $exception)
    {
        $request->getSession()->set(Security::AUTHENTICATION_ERROR, $exception);
    }
}
