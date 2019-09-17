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
use Stevenmaguire\OAuth2\Client\Provider\SalesforceResourceOwner;

class SalesforceClient extends OAuth2Client
{
    /**
     * @param AccessToken $accessToken
     * @return SalesforceResourceOwner
     */
    public function fetchUserFromToken(AccessToken $accessToken)
    {
        return parent::fetchUserFromToken($accessToken);
    }

    /**
     * @return SalesforceResourceOwner
     */
    public function fetchUser()
    {
        return parent::fetchUser();
    }
}
