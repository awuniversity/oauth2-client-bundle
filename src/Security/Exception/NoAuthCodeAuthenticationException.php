<?php

/*
 * OAuth2 Client Bundle
 * Copyright (c) AwUniversity <http://awuniversity.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AwU\OAuth2ClientBundle\Security\Exception;

use Symfony\Component\Security\Core\Exception\AuthenticationException;

/**
 * Thrown if the user *should* have an authorization code, but there is none.
 *
 * Usually, this is because the user has denied access to your
 * OAuth application.
 */
class NoAuthCodeAuthenticationException extends AuthenticationException
{
    public function getMessageKey()
    {
        return 'Authentication failed! Did you authorize our app?';
    }
}
