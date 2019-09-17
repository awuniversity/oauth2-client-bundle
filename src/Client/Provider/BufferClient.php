<?php

/*
 * OAuth2 Client Bundle
 * Copyright (c) AwUniversity <http://awuniversity.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AwU\OAuth2ClientBundle\Client\Provider;

use AwU\OAuth2ClientBundle\Client\OAuth2Client;
use AwU\OAuth2\Client\Token\AccessToken;
use Tgallice\OAuth2\Client\Provider\BufferUser;

class BufferClient extends OAuth2Client
{
    /**
     * @param AccessToken $accessToken
     * @return BufferUser
     */
    public function fetchUserFromToken(AccessToken $accessToken)
    {
        return parent::fetchUserFromToken($accessToken);
    }

    /**
     * @return BufferUser
     */
    public function fetchUser()
    {
        return parent::fetchUser();
    }
}
