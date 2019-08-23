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
use Aego\OAuth2\Client\Provider\OdnoklassnikiResourceOwner;

class OdnoklassnikiClient extends OAuth2Client
{
    /**
     * @param AccessToken $accessToken
     * @return OdnoklassnikiResourceOwner
     */
    public function fetchUserFromToken(AccessToken $accessToken)
    {
        return parent::fetchUserFromToken($accessToken);
    }

    /**
     * @return OdnoklassnikiResourceOwner
     */
    public function fetchUser()
    {
        return parent::fetchUser();
    }
}
