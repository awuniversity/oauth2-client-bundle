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
use League\OAuth2\Client\Token\AccessToken;
use AlexMasterov\OAuth2\Client\Provider\HeadHunterResourceOwner;

class HeadHunterClient extends OAuth2Client
{
    /**
     * @param AccessToken $accessToken
     * @return HeadHunterResourceOwner
     */
    public function fetchUserFromToken(AccessToken $accessToken)
    {
        return parent::fetchUserFromToken($accessToken);
    }

    /**
     * @return HeadHunterResourceOwner
     */
    public function fetchUser()
    {
        return parent::fetchUser();
    }
}
